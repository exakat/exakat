<?php

namespace Report\Format\Sqlite;

class SimpleTable extends \Report\Format\Sqlite { 
    public function render($output, $data) {
        print_r($data);
        foreach($data as $key => $value) {
            $array = array('analyzer' => $key,
                           'value' => 'All',
                           'count' => $value);
        
            $output->push($array);
        }
    }

}

?>