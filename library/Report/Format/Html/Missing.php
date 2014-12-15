<?php

namespace Report\Format\Html;

class Missing extends \Report\Format\Html {
    public function render($output, $data) { 
        $output->push('<!-- missing widget -->');
    }
    
    public function __call($name, $args) {
        return true; 
    }
}

?>
