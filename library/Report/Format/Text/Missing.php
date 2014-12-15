<?php

namespace Report\Format\Text;

class Missing extends \Report\Format\Text {
    public function render($output, $data) {
        $output->push("/** Missing widget **/");
    }
    
    public function __call($name, $args) {
        return true; 
    }
}

?>
