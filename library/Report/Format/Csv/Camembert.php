<?php

namespace Report\Format\Csv;

class Camembert extends \Report\Format\Csv {
    public function render($output, $data) { 
        // Nothing to do, can't display a piechart in a SQLITE table :)
    }
}

?>