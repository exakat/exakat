<?php

namespace Report\Format\Markdown;

class Camembert extends \Report\Format\Markdown {
    public function render($output, $data) { 
        // Nothing to do, can't display a piechart in a SQLITE table :)
    }
}

?>