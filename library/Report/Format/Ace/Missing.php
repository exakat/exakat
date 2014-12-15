<?php

namespace Report\Format\Ace;

class Missing extends \Report\Format\Ace {
    public function render($output, $data) {
        $output->push("<!-- Missing widget -->");
    }

    public function __call($name, $args) {
        return true; 
    }
}

?>
