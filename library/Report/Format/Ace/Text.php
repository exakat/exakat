<?php

namespace Report\Format\Ace;

class Text extends \Report\Format\Ace { 
    public function render($output, $data) {
        $data = nl2br(trim($data));
        $output->push("<p>".$data."</p>\n");
    }

}

?>