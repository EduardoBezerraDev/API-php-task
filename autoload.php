<?php

spl_autoload_register(function ($class) {
    $root = __DIR__ . '/src/';

    $classPath = str_replace('\\', '/', $class) . '.php';

    $file = $root . $classPath;

    if (file_exists($file)) {
        require_once $file;
    }
});
