<?php

namespace Report\Format\Csv;

class Table extends \Report\Format\Csv { 
    public function render($output, $data) {

        foreach($data as $v) {
            $output->push($v);
        }
    }

}

?>
