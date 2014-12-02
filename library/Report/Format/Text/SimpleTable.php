<?php

namespace Report\Format\Text;

class SimpleTable extends \Report\Format\Text {
    public function render($output, $data) { 
        // Nothing to do, can't display a piechart in a SQLITE table :)
    }
}

?>