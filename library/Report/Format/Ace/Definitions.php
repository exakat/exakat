<?php

namespace Report\Format\Ace;

class Definitions extends \Report\Format\Ace { 
    public function render($output, $data) {
        $text = <<<HTML
													<dl id="dt-list-1" >
HTML;
        
        uksort($data, function ($a, $b) { return strtolower($a) > strtolower($b) ;});
        foreach($data as $name => $definition) {
            $id = str_replace(' ', '-', strtolower($name));
            $definition = nl2br($definition);
            
            $text .= "
														<dt><a name=\"$id\"></a>$name</dt>
														<dd><p>$definition</p></dd>";
        }

        $text .= <<<HTML
													</dl>
HTML;

        $output->push($text);
    }
}

?>