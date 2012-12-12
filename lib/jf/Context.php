<?php
namespace lib\jf;

require 'core/core.php';

use lib\jf\core\ArrayConfiguration;
use lib\jf\NoServiceException;
use lib\jf\NoExtensionException;

/**
 * Entry point of every processes.
 *
 * @author Terry
 */
final class Context {

	/**
	 *
	 * @var Context
	 */
	private static $inst;
	
	private static $PATH_SERVICES;
	private static $PATH_EXTS;
	private static $PATH_TMP;

	/**
	 * Returns context instance of current request.
	 * @param string $cfg	config path
	 * @return Context
	 */
	static function getContext($cfg=null) {
		if (!is_null(self::$inst)) {
			if (!is_null($cfg)) {
				self::$inst->init($cfg);
				self::$inst->initStartupServices();
			}

			return self::$inst;
		}

		self::$inst = new Context($cfg);
		self::$inst->initStartupServices();

		return self::$inst;
	}

	/**
	 *
	 * @var IConfiguration
	 */
	private $cfg;

	protected function __construct($cfg) {
		// @TODO: Config paths
		self::$PATH_SERVICES = PATH_BASE.'/services';
		self::$PATH_EXTS = PATH_BASE.'/exts';
		self::$PATH_TMP = PATH_BASE.'/tmp';
		
		$this->init(ArrayConfiguration::loadFromFile($cfg));
	}

	private function init(IConfiguration $cfg) {
		$this->cfg = $cfg;
	}

	private function initStartupServices() {
		$startup = $this->cfg->getValue('startUpServices', array());

		foreach ($startup as $svc) {
			$this->getService($svc);
		}
	}

	/**
	 * Gets config.
	 * @return lib\jf\IConfiguration
	 */
	function getConfiguration() {
		return $this->cfg;
	}

	// @TODO: Region of Services
	/**
	 * Gets a service by name.
	 * @param string $name	name of service
	 * @param bool $renew	if TRUE, force renew service
	 * @return lib\jf\IService
	 */
	function getService($name, $renew=false) {
		static $svcs = array();

		if (isset($svcs[$name]) && $renew === false) return $svcs[$name];

		$svcf = $this->getServiceConfigPath($name);
		if(!file_exists($svcf)) {
			throw new NoServiceException($name, $svcf);
		}
		$svcCfg = ArrayConfiguration::loadFromFile($svcf);

		foreach($svcCfg->getValue('depends', array()) as $other) {
			$this->getService($other);
		}

		$service = createInstanceFromArray($svcCfg, 'lib\jf\IService');
		$svcs[$name] = $service;
		$service->init(new ArrayConfiguration($svcCfg->getValue('params', array())));

		return $service;
	}
	
	function getServiceConfigPath($name) {
		$svcf = self::$PATH_SERVICES . "/{$name}/sc.cnf.php";
		
		return $svcf;
	}
	
	// @TODO: Region of extensions
	function getExtensionPath($extName) {
		return self::$PATH_EXTS."/$extName";
	}
	
	function getExtensionConfigFile($extName) {
		return $this->getExtensionPath($extName).'/ext.cnf.php';
	}
	
	function getExtensionDefFile($extName) {
		return $this->getExtensionPath($extName).'/ext.def.php';
	}
	
	function isExtensionExists($extName) {
		$extFile = $this->getExtensionConfigFile($extName);
		if(!file_exists($extFile)) {
			return FALSE;
		}
		
		return $extFile;
	}
	
	/**
	 * @param string $extName
	 * @return IConfiguration
	 * @throws NoExtensionException
	 */
	function getExtensionConfiguration($extName) {
		$extFile = $this->isExtensionExists($extName);
		if($extFile===FALSE) {
			throw new NoExtensionException($extName, $extFile);
		}
		return ArrayConfiguration::loadFromFile($extFile);
	}
	
	/**
	 * @param string $extName
	 * @return IConfiguration
	 */
	function getExtensionDefinition($extName) {
		return ArrayConfiguration::loadFromFile($this->getExtensionDefFile($extName));
	}
	
	function packageExtension($extName, $dest) {
		$cfg = $this->getExtensionConfiguration($extName);
		$zip = new \ZipArchive();
		
		if($zip->open($dest.'/'.str_replace('/', '.', $extName).'.zip', \ZIPARCHIVE::CREATE | \ZIPARCHIVE::OVERWRITE) === TRUE) {
			// add manifest info
			$zip->setArchiveComment('CREATED BY JFX FRAMEWORK 2.0 - DO NOT EDIT THIS ARCHIVE!');
			$zip->addFromString('.manifest', $extName);
			// get ext path
			$extPath = $this->getExtensionPath($extName);
			// add config file
			$zip->addFile("$extPath/ext.def.php", "$extName/ext.def.php");
			
			// add source code of extension
			foreach($cfg->getValue('srcs', array()) as $filename) {
				if($zip->addFile($extPath."/$filename", "$extName/$filename") === FALSE) echo $zip->getStatusString();
			}
			// commit zip content
			if($zip->close()===FALSE) echo $zip->getStatusString();
		} else echo $zip->getStatusString();
	}

	function installExtension($extPkgFile, $overwrite=false, $dbkey='default') {
		if(!file_exists($extPkgFile)) {
			throw new \Exception('extension file has not existed: '.$extPkgFile);
		}
		
		// Firstly, extract extension files to temporary directory
		$zip = new \ZipArchive();
		if($zip->open($extPkgFile) === TRUE) {
			// extract to temp dir
			$tmpdir = sys_get_temp_dir();
			if($zip->extractTo($tmpdir)===FALSE) echo $zip->getStatusString();
			// get extension name
			$extName = $zip->getFromName('.manifest');
			// close archive
			if($zip->close()===FALSE) echo $zip->getStatusString();
			if($extName === FALSE)
				throw new \Exception('invalid package');

			// get ext path
			$extPath = $this->getExtensionPath ($extName);
			// check if extension has already existed or not
			// if existed
			if($this->isExtensionExists($extName) !== FALSE) {
				if($overwrite === FALSE) {
					throw new \Exception('extension has been already exist: '.$extName);
				} else core\Toolkit::removeDir($extPath);
			}
			
			// otherwise, copy extension files from temp dir to exts dir
			if(!file_exists($extPath)) mkdir ($extPath, 0777, true);
			rename($tmpdir."/$extName", $extPath);
			
			// Secondly, execute install scripts
			$cfg = $this->getExtensionDefinition($extName);
			// get database service
			$dbs = $this->getService('jf/database');
			if(is_a($dbs, 'services\jf\database\DatabaseService')) {
				foreach($cfg->getValue('install_scripts', array()) as $sqlfile) {
					$dbs->executeSQLFile($dbkey, $extPath."/$sqlfile");
				}
			}
		} else echo $zip->getStatusString();
	}

	function uninstallExtension($extName, $deleteSource = false, $dbkey='default') {
		$cfg = $this->getExtensionConfiguration($extName);
		$extPath = $this->getExtensionPath($extName);

		$dbs = $this->getService('jf/database');
		if (is_a($dbs, 'services\jf\database\DatabaseService')) {
			foreach ($cfg->getValue('uninstall_scripts', array()) as $sqlfile) {
				$dbs->executeSQLFile($dbkey, $extPath . "/$sqlfile");
			}
		}
		
		if($deleteSource === TRUE) {
			$as = explode('/', $extName);
			$deletePath = $extPath;
			$count = count($as) - 1;
			
			for($i = 0; $i < $count; $i++) {
				$deletePath = dirname($deletePath);
			}
			
			core\Toolkit::removeDir($deletePath);
		}
	}
}

?>
