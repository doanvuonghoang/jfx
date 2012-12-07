<?php
/**
 * Define platform version.
 */
define('PLATFORM_VERSION', '2.0');
/**
 * Define path to base directory of platform.
 */
define('PATH_BASE', __DIR__);
/**
* Defines app path.
*/
define('PATH_APP', realpath(PATH_BASE.'/apps'));
/**
 * Define path to config directory
 */
define('PATH_CONFIG', realpath(PATH_BASE.'/configs'));
/**
 * Define path to temporary directory of platform.
 */
define('PATH_TMP', PATH_BASE.'/tmp');

define('DISPLAY_ERROR', 'ON');

require 'lib/jf/Context.php';

use lib\jf\Context;

Context::getContext(PATH_CONFIG.'/sys.cnf.php');
?>