<?php

namespace Report\Format\Ace;

class Text extends \Report\Format\Ace { 
    public function render($output, $data) {
        $output->push("<p>".trim($data)."</p>\n");
    }

}

?>