<?php

namespace Report\Format\Csv;

class Liste extends \Report\Format\Csv { 

    public function render($output, $data) {
        foreach($data as $row) {
            $output->push(array($row));
        }
    }

}

?>
