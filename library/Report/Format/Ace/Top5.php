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

class Top5 extends \Report\Format\Ace {
    static public $top5_counter = 0;
    
    public function render($output, $data) {
    
        $html = <<<HTML
									<div class="widget-box transparent">
										<div class="widget-header widget-header-flat">
											<h4 class="lighter">
												{$this->css->title}
											</h4>

											<div class="widget-toolbar">
												<a href="#" data-action="collapse">
													<i class="icon-chevron-up"></i>
												</a>
											</div>
										</div>

										<div class="widget-body">
											<div class="widget-main no-padding">
												<table class="table table-bordered table-striped">
													<thead>
														<tr>
HTML;

        $columnsHeaders = array();
        foreach($columnsHeaders as $columnHeader) {
            $html .= <<<HTML
															<th>
																name
															</th>

HTML;
        }
        
        $html .= <<<HTML
														</tr>
													</thead>

													<tbody>
HTML;

        $values = $data;
        uasort($values, function ($a, $b) { 
            if ($a['sort'] == $b['sort']) { 
                return 0 ;
            } 
            
            return $a['sort'] < $b['sort'] ? 1 : -1;
        });
        $values = array_slice($values, 0, 5);
        foreach($values as $value) {
            // @note This is the same getId() than in Section::getId()
            if ($value['severity'] == '') {
$cell = "															<td>{$value['name']}</td>";
            } else {
                $value['id'] =  str_replace(array(' ', '('  , ')'  ), array('-', '', ''), $value['name']);
$cell = "															<td><a href=\"{$value['id']}.html\">{$value['name']}</a></td>";
            }
            $html .= <<<HTML
														<tr>
$cell
															<td>
																<b>{$value['count']}</b>
															</td>

															<td class="hidden-phone">
																<span class="label label-info arrowed-right arrowed-in">{$value['severity']}</span>
															</td>
														</tr>

HTML;
        }
        
        $html .= <<<HTML
													</tbody>
												</table>
											</div><!--/widget-main-->
										</div><!--/widget-body-->
									</div><!--/widget-box-->

HTML;

        $output->push($html);
    }
}

?>
