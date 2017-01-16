<?php
/**
 * Sample configuration file for Miax webroot.
 *
 */


/**
 * Define essential Miax paths, end with /
 *
 */
define('MIAX_INSTALL_PATH', realpath(__DIR__ . '/../') . '/');
define('MIAX_APP_PATH',     MIAX_INSTALL_PATH . 'app/');



/**
 * Include autoloader.
 *
 */
include(MIAX_APP_PATH . 'config/autoloader.php'); 



/**
 * Include global functions.
 *
 */
include(MIAX_INSTALL_PATH . 'src/functions.php'); 


