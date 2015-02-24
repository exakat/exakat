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

class Horizontal extends \Report\Format\Devoops { 
    static public $horizontal_counter = 0;
    
    public function render($output, $data) {
        $output->pushToJsLibraries( array("assets/js/jquery.dataTables.min.js",
                                          "assets/js/jquery.dataTables.bootstrap.js"));

        $counter = \Report\Format\Ace\Horizontal::$horizontal_counter++;
        
$js = <<<JS
    				var oTable1 = \$('#horizontal-{$counter}').dataTable( {
	    			"aoColumns": [
		    	      null, null, null, null
			    	] } );


JS;
        $output->pushToTheEnd($js);

        $html = <<<HTML
							<p>
								<table id="horizontal-{$counter}" class="table table-bordered table-striped table-hover table-heading table-datatable">
									<thead>
HTML;

        if ($this->css->displayTitles === true) {
            $html .= '<tr>';
            foreach($this->css->titles as $title) {
                $html .= <<<HTML
															<th>
																$title
															</th>
HTML;
        }
            $html .= "</tr>";
        }
        $html .= <<<HTML
									</thead>
									<tbody>
HTML;

        foreach($data as $row) {
            $row['code'] = $this->makeHtml($row['code']);
            if (empty($row['code'])) {
                $row['code'] = '&nbsp;';
            }
            
            $id = str_replace(' ', '-', strtolower($row['desc']));
$html .= <<<HTML

										<tr>
											<td><pre class="prettyprint linenums">{$row['code']}</pre></td>
											<td><a href="Documentation.html#$id">{$row['desc']}</a></td>
											<td>{$row['file']}</td>
											<td>{$row['line']}</td>
										</tr>
HTML;
            }


        $html .= <<<HTML
									</tbody>
								</table>
							</p>
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	$('#horizontal-{$counter}').dataTable( {
		"aaSorting": [[ 0, "asc" ]],
		"sDom": "<'box-content'<'col-sm-6'f><'col-sm-6 text-right'l><'clearfix'>>rt<'box-content'<'col-sm-6'i><'col-sm-6 text-right'p><'clearfix'>>",
		"sPaginationType": "bootstrap",
		"oLanguage": {
			"sSearch": "",
			"sLengthMenu": '_MENU_'
		}
	});
}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	WinMove();
});
</script>

HTML;

        $output->push($html);
    }
}

?>
