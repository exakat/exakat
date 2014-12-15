<?php

namespace Report\Format\Html;

class Horizontal extends \Report\Format\Html { 
    public function render($output, $data) {
        foreach($data as $row) {
            $text = "";
            foreach($row as $k => $v) {
                $text .= " $k : $v<br />\n";
            }
            $text .= "<hr />\n";
        
            $output->push($text);
        }
    }

}

?>
