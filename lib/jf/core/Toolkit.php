<?php
namespace lib\jf\core;

/**
 * Provides functions to read/write array easier, and so on other features.
 *
 * @author Terry
 */
class Toolkit {
	static function getBestSupportedMimeType($mimeTypes = null) {
		// Values will be stored in this array
		$AcceptTypes = Array ();

		// Accept header is case insensitive, and whitespace isn’t important
		$accept = strtolower(str_replace(' ', '', $_SERVER['HTTP_ACCEPT']));
		// divide it into parts in the place of a ","
		$accept = explode(',', $accept);
		foreach ($accept as $a) {
			// the default quality is 1.
			$q = 1;
			// check if there is a different quality
			if (strpos($a, ';q=')) {
				// divide "mime/type;q=X" into two parts: "mime/type" i "X"
				list($a, $q) = explode(';q=', $a);
			}
			// mime-type $a is accepted with the quality $q
			// WARNING: $q == 0 means, that mime-type isn’t supported!
			$AcceptTypes[$a] = $q;
		}
		arsort($AcceptTypes);

		// if no parameter was passed, just return parsed data
		if (!isset($mimeTypes)) return $AcceptTypes;

		$mimeTypes = array_map('strtolower', (array)$mimeTypes);

		// let’s check our supported types:
		foreach ($AcceptTypes as $mime => $q) {
			if ($q && in_array($mime, $mimeTypes)) return $mime;
		}
		// no mime-type found
		return null;
	}

	/**
	 * @desc Removes directory recursively.
	 * @param string $dir
	 */
	static function removeDir($dir) {
		if(!file_exists($dir)) return;

		//chmod($dir, 0777);

		if(is_dir($dir)) {
			$files = scandir($dir);
			array_shift($files); // remove .
			array_shift($files); // remove ..

			foreach($files as $f) {
				self::removeDir($dir.'/'.$f);
			}

			rmdir($dir);
		} else unlink($dir);
	}

	/**
	 * Creates an sid in string.
	 * @param string $salt
	 * @param array $additional
	 * @return string	SID in string, has length 32.
	 */
	static function createSID($salt, $additional=array()) {
		return md5($salt.implode('5c968fc527de104f34d71072623b44c9',$additional));
	}

	/**
	 * Created hashed password.
	 * @param string $username
	 * @param string $rawpass
	 * @return string	hashed password
	 */
	static function createPassword($username, $rawpass) {
		return self::createSID($username, array($rawpass));
	}

	/**
	 * Reads and returns value of data with specified key.
	 * @param array $array			array to read
	 * @param string $path			path to key to read
	 * @param mixed $defaultValue	default value used to return if path is not found in array
	 * @return mixed	value of path
	 */
	static function &array_read(&$array, $path, $defaultValue) {
		$path = trim($path);
		if($path == '/') return $array;
		$queue = explode('/', $path);
		// remove first slash /
		if(strpos($path, '/') === 0) array_shift($queue);
		$temp = &$array;
		while(!empty($queue)) {
			$_key = trim(array_shift($queue));
			if(array_key_exists($_key, $temp)) $temp = &$temp[$_key];
			else return $defaultValue;
		}

		return $temp;
	}

	/**
	 * Writes value to a data key in an array.
	 * @param array &$array			array to write value
	 * @param string $path			path to key to write
	 * @param mixed $value			value to set
	 */
	static function array_write(&$array, $path, $value) {
		$keys = explode('/', $path);
		if($keys[0] === '') array_shift($keys);
		$last = array_pop($keys);

		$temp = &$array;
		while(($path = array_shift($keys))) {
			if(!array_key_exists($path, $temp)) {
				if($value == null) return;
				$temp[$path] = array();
			}
			$temp = &$temp[$path];
		}
		if(is_null($value)) unset($temp[$last]);
		else $temp[$last] = $value;
	}

	/**
	 * Converts array to XML document.
	 * @param array $data
	 * @param DOMDocument $doc
	 * @param DOMNode $node
	 * @param string $nodeName
	 * @return DOMNode
	 */
	static function arrayToXML($data, $doc, $node=null, $nodeName = 'data') {
		if($node == null) $node = $doc->documentElement;

		if(method_exists($data, 'to_array')) {
			$data = $data->to_array();
		}

		if(is_array($data) || is_object($data)) {
			$assoc = self::isAssoc($data);

			if(!$assoc) {
				foreach($data as $value) {
					$child = $doc->createElement($nodeName);
					$node->appendChild($child);
					self::arrayToXML($value, $doc, $child, $nodeName);
				}
			} else {
				foreach($data as $key => $value) {
					if(self::isAssoc($value) || !is_array($value)) {
						$child = $doc->createElement($key);
						$node->appendChild($child);
						self::arrayToXML($value, $doc, $child, $key);
					} else self::arrayToXML($value, $doc, $node, $key);
				}
			}
		} else {
			if(htmlspecialchars($data) != $data) $textNode = $doc->createCDATASection($data);
			else $textNode = $doc->createTextNode($data);

			$node->appendChild($textNode);
		}

		return $node;
	}

	// 	determine if a variable is an associative array
	public static function isAssoc( $array ) {
		return (is_array($array) && 0 !== count(array_diff_key($array, array_keys(array_keys($array)))));
	}

	/**
	 * Builds a tree.
	 * @param array $items
	 * @return array tree model
	 */
	public static function buildTree($items) {
		$temp = array();

		$nodes = $items;
		foreach($nodes as $key => $v) {
			if(!$v['parent_id']) {
				unset($items[$key]);
				self::__buildtree($items, $v);
				$temp[] = $v;
			}
		}

		return $temp;
	}

	private static function __buildtree(&$items, &$node) {
		$temp = $items;
		foreach($temp as $key => $v) {
			if($v['parent_id'] == $node['id']) {
				unset($items[$key]);
				self::__buildtree($items, $v);
				$node['item'][] = $v;
			}
		}
	}

	/**
	 * Attachs info to an array.
	 * @param array $array
	 * @param callable $callable	param is row
	 * @param bool $recursive
	 * @param string $node_name		specific when $recursive is true
	 */
	static function array_walk(array &$array, $callable, $recursive=false, $node_name=null) {
		if(!self::isAssoc($array)) {
			array_walk($array, function(&$r) use($callable, $recursive, $node_name) {
				jf_Toolkit::array_walk($r, $callable, $recursive, $node_name);
			});
		} else {
			call_user_func_array($callable, array(&$array));

			if($recursive===true) {
				foreach($array as $name => &$r) {
					 if(is_array($r) && $name == $node_name) self::array_walk($r, $callable, true, $node_name);
				}
			}
		}
	}

	/**
	 * Convert a date time to db compatiable.
	 * @param string $original_dt
	 * @param string $original_format
	 * @return string
	 */
	static function toDBDatetime($original_dt, $original_format) {
		if(!$original_dt) return null;

		return Datetime::createFromFormat($original_format, $original_dt)->format('Y-m-d');
	}
}
?>
