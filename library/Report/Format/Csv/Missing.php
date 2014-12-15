<?php

namespace Report\Format\Csv;

class Missing extends \Report\Format\Csv {
    public function render($output, $data) {
        $output->push(array("<!-- Default widget -->"));
    }

    public function __call($name, $args) {
        return true; 
    }
}

?>
