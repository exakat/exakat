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

class Table extends \Report\Format\Ace { 
    static public $table_counter = 0;
    
    public function render($output, $data) {

        $output->pushToJsLibraries( array("assets/js/jquery.dataTables.min.js",
                                          "assets/js/jquery.dataTables.bootstrap.js"));

        $counter = \Report\Format\Ace\Table::$table_counter++;
        $nulls = implode(', ', array_fill(0, count($data[0]), 'null'));
        
$js = <<<JS
    				var oTable1 = \$('#table-{$counter}').dataTable( {
	    			"aoColumns": [
		    	      $nulls
			    	] } );


JS;
        $output->pushToTheEnd($js);
        
        $text = <<<HTML
<table id="table-{$counter}" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th>Label</th>
												<th>Value</th>
												<th>Severity</th>
											</tr>
										</thead>

										<tbody>
HTML;
        foreach($data as $v) {
            $text .= "<tr><td>".implode('</td><td>', $v)."</td></tr>\n";
        }
        $text .= <<<HTML
										</tbody>
									</table>
HTML;
        
        $output->push($text);
    }

}

?>
