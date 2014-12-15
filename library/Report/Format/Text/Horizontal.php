<?php

namespace Report\Format\Text;

class Horizontal extends \Report\Format\Text { 
    public function render($output, $data) {
        $text = str_repeat("-", 100)."\n";
        foreach($data as $row) {
            foreach($row as $k => $v) {
                $text .= " $k : $v\n";
            }
            $text .= str_repeat("-", 100)."\n";
        }

        $output->push($text."\n");
    }

}

?>
