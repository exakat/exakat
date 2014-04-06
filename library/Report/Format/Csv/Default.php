<?php

namespace Report\Format\Csv;

class Default extends \Report\Format\Csv {
    public function render($output, $data) {
        $output->push("<!-- Default widget -->");
    }
}

?>