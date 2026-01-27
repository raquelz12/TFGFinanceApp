<?php

define('BASE_PATH', realpath(__DIR__) . DIRECTORY_SEPARATOR);
define('BACKEND_PATH', BASE_PATH . 'backend' . DIRECTORY_SEPARATOR);
define('CONFIG_PATH', BACKEND_PATH . 'config' . DIRECTORY_SEPARATOR);
require(CONFIG_PATH . 'connection.php');
