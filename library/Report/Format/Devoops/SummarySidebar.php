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

class SummarySidebar extends \Report\Format\Ace { 
    public function render($output, $data) {
        $text = $this->render2($data);

        $text = <<<TEXT
$text

TEXT;

        $output->push($text);
    }


//class="active" class="active open"

    private function render2($data) {
    /*
				<li>
					<a href="ajax/dashboard.html" class="ajax-link">
						<i class="fa fa-dashboard"></i>
						<span class="hidden-xs">Dashboard</span>
					</a>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-bar-chart-o"></i>
						<span class="hidden-xs">Charts</span>
					</a>
					<ul class="dropdown-menu">
						<li><a class="ajax-link" href="ajax/Classes.html">xCharts</a></li>
						<li><a class="ajax-link" href="ajax/Classes.html">Flot Charts</a></li>
						<li><a class="ajax-link" href="ajax/Classes.html">Google Charts</a></li>
						<li><a class="ajax-link" href="ajax/Classes.html">Morris Charts</a></li>
						<li><a class="ajax-link" href="ajax/charts_amcharts.html">AmCharts</a></li>
						<li><a class="ajax-link" href="ajax/charts_chartist.html">Chartist</a></li>
						<li><a class="ajax-link" href="ajax/charts_coindesk.html">CoinDesk realtime</a></li>
					</ul>
				</li>
    */
        $text = '';
        foreach($data as $row) {
            if (get_class($row) != "Report\\Template\\Section") { continue; }
            
            $contents = $row->getContent();

            foreach($contents as $id => $c) {
                if (get_class($c) != "Report\\Template\\Section") { 
                    unset($contents[$id]); 
                } 
            }

            if (count($contents) == 0) {
                $text .= <<<HTML
				<li>
					<a href="ajax/{$row->getId()}.html" class="ajax-link">
						<i class="fa fa-dashboard"></i>
						<span class="hidden-xs">{$row->getName()}</span>
					</a>
				</li>
HTML;
            } else {
                $text .= <<<HTML
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-bar-chart-o"></i>
						<span class="hidden-xs">{$row->getName()}</span>
					</a>
					<ul class="dropdown-menu">

HTML;

                foreach($contents as $content) {
                    $text .= <<<HTML
						<li><a class="ajax-link" href="ajax/{$content->getId()}.html">{$content->getName()}</a></li>

HTML;
                }
                $text .= <<<HTML
					</ul>
				</li>

HTML;
            }
        }
        
        return <<<HTML
				<ul class="nav main-menu">
                    $text
                </ul>

HTML;
    }
}

?>