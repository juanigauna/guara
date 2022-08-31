<?php
define('ROOT', dirname(__FILE__));

function namespaceToPath($namespace)
{
    $namespace = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
    $path = ROOT . DIRECTORY_SEPARATOR . $namespace . '.php';
    return $path;
}

spl_autoload_register(function ($namespace) {
    $path = namespaceToPath($namespace);
    if (!file_exists($path)) {
        throw new Exception("The module have an error or dont exist.");
    }
    require_once $path;
});
