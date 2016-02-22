<?php
/**
 * Autocarga las clases php a partir de un directorio.
 * Los direcotios están distribuidos por namespaces
 */
spl_autoload_register(function($class) {
    $CLASS_DIR = __DIR__ . "/common/class/";
    // Comprobará archivos con extensión .class.php
    $CLASS_EXT = ".class.php";
    // Comprobará archivos con extensión .php
    $CLASS_EXT_2 = ".php";
    $subdir = str_replace("\\", "/", $class);
    $file = $CLASS_DIR . $subdir . $CLASS_EXT;
    $file2 = $CLASS_DIR . $subdir . $CLASS_EXT_2;
    if (is_readable($file)) {
        include_once $file;
    } elseif (is_readable($file2)) {
        include_once $file2;
    }
});
// incluye el autoloader de php-jwt
require_once "common/class/php-jwt/vendor/autoload.php";