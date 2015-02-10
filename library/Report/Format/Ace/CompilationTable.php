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


namespace Report\Format\Ace;

class CompilationTable extends \Report\Format\Ace\SimpleTable { 
    static public $table_counter = 0;
    
    public function render($output, $data) {
        $th = '<tr>';
        foreach($this->css->titles as $title) {
            $th .= <<<HTML
															<th>
																$title
															</th>

HTML;
        }
        $th .= "</tr>";
        
        $text = <<<HTML
												<table class="table table-bordered table-striped">
													<thead>
														<tr>
{$th}
														</tr>
													</thead>

													<tbody>

HTML;
        foreach($data as $v) {
            $row = '<tr>';
            foreach($v as $V) {
                if( is_array($V)) {
                    if (empty($V)) {
                        $row .= "<td>&nbsp;</td>\n";
                    } else {
                        $row .= "<td><ul><li>".join("</li><li>", $V)."</li></ul></td>\n";
                    }
                } else {
                    $row .= "<td>$V</td>\n";
                }
            }
            $row .= "</tr>";

            $text .= $row;
        }
        $text .= <<<HTML
													</tbody>
												</table>
HTML;
        
        $output->push($text);
    }
}

?>
