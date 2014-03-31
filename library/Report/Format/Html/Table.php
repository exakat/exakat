<?php

namespace Report\Format\Html;

class Table extends \Report\Format\Html { 
    public function render($output, $data) {

        $text = "<table><tbody>\n";
        foreach($data as $k => $v) {
            $text .= "<tr><td>".join("</td><td>". $v)."</td></tr>\n";
        }
        $text .= "</tbody></table>\n";
        
        $output->push($text."\n");
    }

}

?>