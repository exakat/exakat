<?php

namespace Report\Format\Markdown;

class Table extends \Report\Format\Markdown { 
    public function render($output, $data) {

        $text = "\n";
        $text .= "| A | B |\n";
        $text .= "| :----- | :----- |\n";
        foreach($data as $k => $v) {
            $text .= "| ".join(" | ", $v). " |\n";
        }
        $text .= "\n";
        
        $output->push($text."\n");
    }

}

?>
