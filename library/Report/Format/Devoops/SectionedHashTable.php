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

class SectionedHashTable extends \Report\Format\Devoops { 
    static public $sectionedhastable_counter = 0;
    
    public function render($output, $data) {

        $counter = \Report\Format\Ace\SectionedHashTable::$sectionedhastable_counter++;
        
        $text = <<<HTML
<table id="sectionedhashtable-{$counter}" class="table">
										<thead>
HTML;
        
        if ($this->css->displayTitles === true) {
            $text .= '<tr>';
            foreach($this->css->titles as $title) {
                $text .= <<<HTML
															<th>
																$title
															</th>

HTML;
            }
            $text .= "</tr>";
        }

$text .= <<<HTML
										</thead>

										<tbody>
HTML;
        foreach($data as $k => $v) {
            $text .= "<tr class=\"primary\"><td>$k</td><td>&nbsp;</td></tr>\n";
            if (is_array($v)) {
                foreach($v as $k2 => $v2) {
                    $text .= "<tr><td>$k2</td><td>$v2</td></tr>\n";
                }
            }
        }
        $text .= <<<HTML
										</tbody>
									</table>
HTML;
        
        $output->push($text);
    }

}

?>
