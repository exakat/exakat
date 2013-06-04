<?php

error_reporting(-1);
ini_set('display_errors', 1);

class Autoload {
    static public function autoload_library($name) {
        $path = __DIR__;
        
        $file = $path.'/'.str_replace('\\', DIRECTORY_SEPARATOR, $name).'.php';
        
        if (file_exists($file)) {
            include($file);
        }
    }

    static public function autoload_test($name) {
        $path = dirname(__DIR__);
        
        $file = $path.'/tests/'.str_replace('\\', DIRECTORY_SEPARATOR, $name).'.php';
        var_dump($file);
        
        if (file_exists($file)) {
            include($file);
        } 
    }

    static public function autoload_phpunit($name) {
        $path = dirname(__DIR__);

        $file = str_replace('_', DIRECTORY_SEPARATOR, $name).'.php';
        $file = str_replace('Test\\', '', $file);
        include($file);
    }
}

?>