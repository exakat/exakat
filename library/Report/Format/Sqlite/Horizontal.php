<?php

namespace Report\Format\Sqlite;

class Horizontal extends \Report\Format\Sqlite { 
    public function render($output, $data) {
        foreach($data as $row) {
            $array = array('analyzer' => \Report\Format\Sqlite::$analyzer,
                           'value' => $row['code'],
                           'count' => '');
        
            $output->push($array);
        }
    }

}

?>
