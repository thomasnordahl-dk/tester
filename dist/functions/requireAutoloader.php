<?php

/**
 * locates and loads autoloader
 *
 * @throws RuntimeException if the autoload file can not be located
 */
function requireAutoloader(): void
{
    if (file_exists(__DIR__ . "/../../vendor/autoload.php")) {
        require_once __DIR__ . "/../../vendor/autoload.php";

        return;
    }

    $dir = __DIR__;
    $prev_dir = "";

    while ($dir != $prev_dir) {

        $prev_dir = $dir;
        $dir = dirname($dir);

        if (file_exists($dir . "/autoload.php")) {
            require_once $dir . "/autoload.php";

            return;
        }
    }

    throw new RuntimeException("autoload.php not found!");
}