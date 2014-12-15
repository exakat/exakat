<?php

namespace Report\Format\Sqlite;

class Missing extends \Report\Format\Sqlite {
    public function render($output, $data) {
        $output->push(array("<!-- Missing widget -->"));
    }
    
    public function __call($name, $args) {
        return true; 
    }
}

?>
