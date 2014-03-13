<?php

namespace Report\Format\Csv;

class Summary extends \Report\Format\Csv { 
    public function render($output, $data) {
        // Do nothing, this is CSV file, no summary anyway.
    }
}

?>