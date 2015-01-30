<?php

namespace Report\Format\Html;

class Definitions extends \Report\Format\Html { 
    public function render($output, $data) {
        $text = <<<HTML
													<dl>
HTML;
        
        uksort($data, function ($a, $b) { return strtolower($a) > strtolower($b) ;});
        foreach($data as $name => $definition) {
            $text .= "
														<dt>$name</dt>
														<dd><p>{$definition->getDescription()}</p></dd>";
        }

        $text .= <<<HTML
													</dl>
HTML;

        $output->push($text);
    }
}

?>
