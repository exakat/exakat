<?php

namespace Report\Format\Ace;

class Definitions extends \Report\Format\Ace { 
    public function render($output, $data) {
        $text = <<<HTML
													<dl id="dt-list-1" >
HTML;
        
        uksort($data, function ($a, $b) { return strtolower($a) > strtolower($b) ;});
        
        if (!empty($this->css->dt->class)) {
            $dt_class = ' class="'.$this->css->dt->class.'"';
        } else {
            $dt_class = '';
        }

        if (!empty($this->css->dd->class)) {
            $dd_class = ' class="'.$this->css->dd->class.'"';
        } else {
            $dd_class = '';
        }

        foreach($data as $name => $definition) {
            $id = str_replace(' ', '-', strtolower($name));
            $definition = nl2br(trim($definition));
            
            $text .= "
														<dt$dt_class><a name=\"$id\"></a>$name</dt>
														<dd$dd_class><p>$definition</p></dd>";
        }

        $text .= <<<HTML
													</dl>
HTML;

        $output->push($text);
    }
}

?>