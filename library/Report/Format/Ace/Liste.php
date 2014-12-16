<?php

namespace Report\Format\Ace;

class Liste extends \Report\Format\Ace { 
    public function render($output, $data) {

        $html = '<ul>';
        foreach($data as $row) {
            $html .= '<li>'.$row."</li>\n";
        }

        $html .= '</ul>';

        $output->push($html);
    }
}

?>
