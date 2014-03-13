<?php

namespace Report\Format\Csv;

class Horizontal extends \Report\Format\Html { 
    public function render($output, $data) {
        foreach($data as $row) {
            $array = array();
            foreach($row as $k => $v) {
                $array[] = $v;
            }
        
            $output->push($array);
        }
    }

}

?>