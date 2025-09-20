<?php
defined('BASEPATH') or exit('No direct script access allowed');

// PhpSpreadsheet Autoloader without Composer
spl_autoload_register(function ($class) {
    $prefix = 'PhpOffice\\PhpSpreadsheet\\';
    $base_dir = __DIR__ . '/src/PhpSpreadsheet/';

    // Check if the class uses the namespace prefix
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// Load necessary dependencies that would normally be handled by Composer
require_once __DIR__ . '/src/PhpSpreadsheet/Shared/StringHelper.php';
require_once __DIR__ . '/src/PhpSpreadsheet/Shared/File.php';
require_once __DIR__ . '/src/PhpSpreadsheet/Calculation/Calculation.php';
