<?php

namespace Report\Format\Markdown;

class HashTable extends \Report\Format\Markdown { 
    public function render($output, $data) {

        $text = "\n";
        $text .= "| A | B |\n";
        $text .= "| :----- | :----- |\n";
        foreach($data as $k => $v) {
            $text .= "| $k | $v |\n";
        }
        $text .= "\n";
        
        $output->push($text."\n");
    }

}

?>
