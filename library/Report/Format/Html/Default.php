<?php

namespace Report\Format\Html;

class Default extends \Report\Format\Html {
    public function render($output, $data) { 
        // Nothing to do, can't display a piechart in a SQLITE table :)
    }
}

?>