<?php

namespace Report\Format\Markdown;

class Text extends \Report\Format\Markdown { 
    public function render($output, $data) {
        $output->push(trim($data)."\n");
    }

}

?>
