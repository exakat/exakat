<?php

namespace Report\Format\Markdown;

class Liste extends \Report\Format\Markdown { 
    public function render($output, $data) {

        $text = "\n";
        foreach($data as $v) {
            $text .= "+ $v\n";
        }
        $text .= "\n";
        
        $output->push($text."\n&nbsp;\n");
    }

}

?>