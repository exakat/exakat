<?php

namespace Report\Format\Ace;

class TextLead extends \Report\Format\Ace { 
    public function render($output, $data) {
        $data = nl2br(trim($data));
        $output->push("<p class=\"lead\">".$data."</p>\n");
    }

}

?>