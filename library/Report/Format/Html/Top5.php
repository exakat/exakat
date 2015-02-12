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


namespace Report\Format\Html;

class Top5 extends \Report\Format\Html {
    static public $top5_counter = 0;
    
    private $title = '';
    private $columnsHeaders = array();
    
    public function render($output, $data) {
        $title = "&nbsp;";
        $html = <<<HTML
											<h4 class="lighter">
												$title
											</h4>
												<table class="table table-bordered table-striped">
HTML;
        if (!empty($this->columnsHeaders)) {
            $html .= '													<thead>
														<tr>
';
          foreach($this->columnsHeaders as $columnHeader) {
                $html .= <<<HTML
															<th>
																name
															</th>

HTML;
            }
            $html .= '														</tr>
													</thead>
';
        }
        
        $html .= <<<HTML

													<tbody>
HTML;

        $values = $data;
        uasort($values, function ($a, $b) { 
            if ($a['sort'] == $b['sort']) { 
                return 0 ;
            } 
            return $a['sort'] < $b['sort'] ? 1 : -1;}
              );
        $values = array_slice($values, 0, 5);
        foreach($values as $value) {
            $html .= <<<HTML
														<tr>
															<td>{$value['name']}</td>

															<td>
																<b>{$value['count']}</b>
															</td>

															<td>
																{$value['severity']}
															</td>
														</tr>

HTML;
        }
        
        $html .= <<<HTML
													</tbody>
												</table>

HTML;

        $output->push($html);
    }
    
    public function setTitle($title) {
        $this->title = $title;
    }

    public function setColumnHeaders($columnsHeaders) {
        $this->columnsHeaders = $columnsHeaders;
    }
}

?>
