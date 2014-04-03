<?php

namespace Report\Format\Html;

class Liste extends \Report\Format\Html { 

    public function render($output, $data) {
        $html = "<ul>";
        foreach($data as $k => $v) {
            $html .= "<li>".htmlentities($v)."</li>\n";
        }
        $html = "</ul>\n";

        $output->push($html);
    }

}

?>