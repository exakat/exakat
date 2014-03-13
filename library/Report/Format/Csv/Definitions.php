<?php

namespace Report\Format\Csv;

class Definitions extends \Report\Format\Csv { 
    public function render($output, $data) {

        foreach($data as $k => $v) {
            $output->push(array($k, $v));
        }
    }

}

?>