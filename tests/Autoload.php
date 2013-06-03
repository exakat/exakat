<?php

class Test_Autoload {
    static public function autoload($name) {
        $path = dirname(__FILE__);

        $file = $path.'/class/'.str_replace('Test_', '', $name).'.php';
        
        if (file_exists($file)) {
            include($file);
        } else { 
            // @note no display here, please. May be some error handling?
        }
    }
}

?>