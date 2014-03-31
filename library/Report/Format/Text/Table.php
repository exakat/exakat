<?php

namespace Report\Format\Text;

class HashTable extends \Report\Format\Text { 
    public function render($output, $data) {

        $text = "+---------+----------+\n";
        foreach($data as $k => $v) {
            $text .= "| ".join(" | ", $v)." |\n";
        }
        $text .= "+---------+----------+\n";
        
        $output->push($text."\n");
    }

}

?>