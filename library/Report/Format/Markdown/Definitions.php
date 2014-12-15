<?php

namespace Report\Format\Markdown;

class Definitions extends \Report\Format\Markdown { 
    public function render($output, $data) {
        $text = <<<TEXT
TEXT;
        
        uksort($data, function ($a, $b) { return strtolower($a) > strtolower($b) ;});
        foreach($data as $name => $definition) {
//            $t = wordwrap(str_repeat(' ', strlen($name)).$definition, 75);
//            $t = str_replace("\n", "\n     ", $t);
            $text .= "\n**$name** : ".trim($definition)."\n";
        }

        $text .= <<<TEXT
TEXT;

        $output->push($text);
    }
}

?>
