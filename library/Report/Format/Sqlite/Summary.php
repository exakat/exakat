<?php

namespace Report\Format\Sqlite;

class Summary extends \Report\Format\Sqlite { 
    public function render($output, $data) {
        // Do nothing, this is CSV file, no summary anyway.
    }
}

?>