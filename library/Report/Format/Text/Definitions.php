<?php

namespace Report\Format\Text;

class Definitions extends \Report\Format\Text { 
    public function render($output, $data) {
        $text = <<<TEXT
TEXT;
        
        uksort($data, function ($a, $b) { return strtolower($a) > strtolower($b) ;});
        foreach($data as $name => $definition) {
            $t = wordwrap(str_repeat(' ', strlen($name)).$definition, 75);
            $t = str_replace("\n", "\n     ", $t);
            $text .= "\n$name : ".trim($t)."\n";
        }

        $text .= <<<TEXT
TEXT;

        $output->push($text);
    }
}

?>