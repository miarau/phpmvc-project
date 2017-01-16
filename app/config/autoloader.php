<?php
/**
 * Enable autoloaders.
 *
 */


/**
 * Default Miax autoloader, and the add specifics through a self invoking anonomous function.
 * Add autoloader for namespace Miax and a default directory for unknown vendor namespaces.
 */
require MIAX_INSTALL_PATH . 'src/Loader/CPsr4Autoloader.php';

call_user_func(function() {
    $loader = new \Miax\Loader\CPsr4Autoloader();
    $loader->addNameSpace('Miax', MIAX_INSTALL_PATH . 'src')
           ->addNameSpace('', MIAX_APP_PATH . 'src')
           ->addNameSpace('Michelf', MIAX_INSTALL_PATH . '3pp/php-markdown/Michelf')
           ->register();
});



/**
 * Including composer autoloader if available.
 *
 * @link https://getcomposer.org/doc/01-basic-usage.md#autoloading
 */
if(is_file(MIAX_INSTALL_PATH . 'vendor/autoload.php')) {
    include MIAX_INSTALL_PATH . 'vendor/autoload.php';
}

