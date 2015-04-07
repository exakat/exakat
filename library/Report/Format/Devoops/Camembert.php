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

class Camembert extends \Report\Format\Devoops {
    public function render($output, $data) {
        $datajs = '';
        foreach($data as $k => $v) {
            $datajs .= "{label: \"$k\", value: $v},\n";
        }
        
        $html = <<<HTML
 <label class="label label-success">Pie Chart</label>
      <div id="pie-chart" style="height: 200px;" ></div>

<script type="text/javascript">
function DrawAllMorrisCharts(){
Morris.Donut({
  element: 'pie-chart',
  colors: [
    '#1424b8',
    '#0aa623',
    '#940f3f',
    '#148585',
    '#098215',
    '#b86c14',
    '#b83214'
  ],  
  data: [
    $datajs
  ]
});
}
$(document).ready(function() {
	// Load required scripts and draw graphs
	LoadMorrisScripts(DrawAllMorrisCharts);
	WinMove();
});
</script>

HTML;
    
            $output->push($html);
    }
}

?>
