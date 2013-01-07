<?php
namespace lib\jf\core;

use lib\jf\IConfiguration;
use lib\jf\core\Utils;

/**
 * @desc Ans array reader/writer.
 * @author Terry
 *
 */
class ArrayConfiguration extends \ArrayIterator implements IConfiguration {
	/**
	 * @desc Constructors of this class.
	 * @param array $array			input array
	 */
	public function __construct(array $array) {
		parent::__construct($array);
	}

	/**
	 * @desc Gets value of a key in configuration.
	 * Key is a path to access configuration data.
	 * @param string $key			key
	 * @param mixed $default		value returned when not found key
	 * @param mixed $ec
	 * @return mixed the value of key
	 */
	public function getValue($key, $default=null, $ec=null) {
		return Utils::array_read($this, $key, $default, $ec);
	}

	/**
	 * @desc Sets value for a key.
	 * Key is a path to access configuration data.
	 * @param string $key			key
	 * @param mixed $value			value of key
	 */
	public function setValue($key, $value) {
		Utils::array_write($this, $key, $value);
	}

	/**
	 * @desc Commits configuration data to memory.
	 * @return bool should return true if commit successfully, otherwise false
	 */
	public function commit() {
		throw new Exception("unsupported method");
	}

	/**
	 * Loads config from file.
	 * @param string $file file config
	 * @return lib\jf\core\ArrayConfiguration
	 */
	static function loadFromFile($file) {
		require $file;
		if(!isset($config) || !is_array($config)) throw new Exception("invalid file format: '{$file}' contains no config array variable");

		$cfg = new ArrayConfiguration($config);

		return $cfg;
	}

	/**
	 * Saves config to file.
	 * @param string $file
	 */
	function saveToFile($file) {
		if(($handle = fopen($file, 'w+')) != null) {
			fwrite($handle, "<?php \n\$config = " . var_export($this->getArrayCopy(), true) . "; \n?>");
			fclose($handle);
		}
	}
}
?>