<?php

namespace Report\Format\Ace;

class Definitions extends \Report\Format\Ace { 
    public function render($output, $data) {
        $text = <<<HTML
													<dl id="dt-list-1" >
HTML;
        
        uksort($data, function ($a, $b) { 
            return strtolower($a) > strtolower($b) ;
        });
        
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
            $description = nl2br(trim($definition->getDescription()));

            $clearPHP = $definition->getClearPHP();
            if (!empty($clearPHP)) {
                $description .= "<br />\n<br />\nThis rule is named '<a href=\"https://github.com/dseguy/clearPHP/blob/master/rules/$clearPHP.md\">$clearPHP</a>', in the clearPHP reference.";
            }

            
            $text .= "
														<dt$dt_class><a name=\"$id\"></a>$name</dt>
														<dd$dd_class><p>$description</p></dd>";
        }

        $text .= <<<HTML
													</dl>
HTML;

        $output->push($text);
    }
}

?>
