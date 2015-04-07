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

class SectionedTable extends \Report\Format\Devoops { 
    static public $sectionedhastableCounter = 0;
    
    public function render($output, $data) {
        $counter = self::$sectionedhastableCounter++;
        
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
        $readOrder = $this->css->readOrder;
        if (empty($readOrder)) {
            $readOrder = range(0, count($this->css->titles)-1);
        }
        foreach($data as $k => $v) {
            $text .= "<tr><td style=\"background-color: {$this->css->backgroundColor}\">$k</td>".
            str_repeat("<td style=\"background-color: {$this->css->backgroundColor}\">&nbsp;</td>", count($this->css->titles) -1)."</tr>\n";
            if ($v instanceof \Iterator) {
                if (empty($v)) { 
                    continue; 
                }

                foreach($v as $v2) {
                    $text .= "<tr>";
                    foreach($readOrder as $id) {
                        $text .= "<td>$v2[$id]</td>\n";
                    }
                    $text .= "</tr>\n";
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
