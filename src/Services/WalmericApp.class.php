<?php

namespace Services;

include_once __DIR__ . '/../config/config.php';

class RawApiApp
{
    /**
     * Autoloader and error handling
     */
    public static function CreateEnvironment ()
    {
        //register psr4 classes
        set_include_path (get_include_path(). PATH_SEPARATOR . __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
        spl_autoload_extensions('.class.php');
        spl_autoload_register();
        //register an error handler to turn errors into exceptions
        set_error_handler
        (
            function ($code, $error, $file = NULL, $line = NULL) {
                throw new \RuntimeException(sprintf ('Error: %s found at %s in file %f' , $error , $file, $line), $code);
            }
        );

    }
}