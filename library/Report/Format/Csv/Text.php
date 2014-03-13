<?php

namespace Report\Format\Csv;

class Text extends \Report\Format\Csv { 
    public function render($output, $data) {
        // do nothing and ignore
//        $output->push("<p>".trim($data)."</p>\n");
    }

}

?>