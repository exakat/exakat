<?php

namespace Report\Format\Text;

class Text extends \Report\Format\Text { 
    public function render($output, $data) {
        $output->push(trim($data)."\n\n");
    }
}

?>