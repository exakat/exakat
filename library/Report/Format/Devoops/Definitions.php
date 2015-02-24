<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/


namespace Report\Format\Devoops;

class Definitions extends \Report\Format\Devoops { 
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
