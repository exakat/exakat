<?php

namespace Report\Format\Html;

class Text extends \Report\Format\Html { 
    public function render($output, $data) {
        $output->push("<p>".trim($data)."</p>\n");
    }

}

?>
