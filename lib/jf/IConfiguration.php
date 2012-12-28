<?php
namespace lib\jf;

/**
 * @desc Represents a key-value-pair list to read configuration from file, or database.
 * @author Terry
 *
 */
interface IConfiguration extends \ArrayAccess, \Iterator {
	/**
	 * @desc Gets value of a key in configuration.
	 * @param string $key			key
	 * @param mixed $default		value returned when not found key
	 * @param mixed $ec				if not NULL throws exception
	 * @return mixed the value of key
	 */
	function getValue($key, $default=null, $ec=null);

	/**
	 * @desc Sets value for a key.
	 * If value is NULL, remove the key.
	 * @param string $key			key
	 * @param mixed $value			value of key
	 */
	function setValue($key, $value);

	/**
	 * @desc Commits configuration data to storage.
	 * @return bool should return true if commit successfully, otherwise false
	 */
	function commit();
}
?>