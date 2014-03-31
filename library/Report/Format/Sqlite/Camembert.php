<?php

namespace Report\Format\Sqlite;

class Camembert extends \Report\Format\Sqlite {
    public function render($output, $data) { 
        // Nothing to do, can't display a piechart in a SQLITE table :)
    }
}

?>