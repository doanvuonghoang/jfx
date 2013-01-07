<?php
define('MODE_DEBUG', 'ON');

require 'lib/jf/core/core.php';

use lib\jf\Context;

Context::getContext(PATH_CONFIG.'/sys.cnf.php');
?>