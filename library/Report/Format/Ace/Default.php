<?php

namespace Report\Format\Ace;

class Default extends \Report\Format\Ace {
    public function render($output, $data) {
        $output->push("<!-- Default widget -->");
    }
}

?>