<?php

namespace Report\Format\Markdown;

class Missing extends \Report\Format\Markdown {
    public function render($output, $data) {
        $output->push("<!-- Missing widget -->");
    }
    
    public function __call($name, $args) {
        return true; 
    }
}

?>
