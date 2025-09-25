<?php
defined('BASEPATH') or exit('No direct script access allowed');

// PhpSpreadsheet Autoloader without Composer
spl_autoload_register(function ($class) {
    $prefixes = [
        'PhpOffice\\PhpSpreadsheet\\' => APPPATH . 'third_party/PhpSpreadsheet/src/PhpSpreadsheet/',
        'Psr\\SimpleCache\\' => APPPATH . 'third_party/Psr/SimpleCache/src/',
        'Psr\\' => APPPATH . 'third_party/Psr/SimpleCache/src/', // Fallback for other PSR
    ];

    foreach ($prefixes as $prefix => $base_dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) === 0) {
            $relative_class = substr($class, $len);
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

            if (file_exists($file)) {
                require $file;
                return;
            }
        }
    }
});

require_once APPPATH . 'third_party/PhpSpreadsheet/src/PhpSpreadsheet/Shared/StringHelper.php';
require_once APPPATH . 'third_party/PhpSpreadsheet/src/PhpSpreadsheet/Shared/File.php';
