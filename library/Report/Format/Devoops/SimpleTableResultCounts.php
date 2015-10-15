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

class SimpleTableResultCounts extends \Report\Format\Devoops { 
    static public $hastableCounter = 0;
    
    public function render($output, $data) {
        $counter = self::$hastableCounter++;
        $output->pushToJsLibraries( array("assets/js/jquery.dataTables.min.js",
                                          "assets/js/jquery.dataTables.bootstrap.js"));

$js = <<<JS
    				var oTable1 = \$('#hashtable-{$counter}').dataTable( {
	    			"aoColumns": [
		    	      null, null
			    	] } );


JS;
        $output->pushToTheEnd($js);

        $text = <<<HTML
<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="hashtable-{$counter}">
										<thead>
HTML;
        
        if ($this->css->displayTitles === true) {
            $text .= '<tr>';
            foreach($this->css->titles as $title) {
                $text .= <<<HTML
															<th>$title</th>

HTML;
            }
            $text .= "</tr>";
        }

$text .= <<<HTML
										</thead>

										<tbody>
HTML;
        foreach($data as $k => $v) {
            if ($v[0] == 'Total') { 
                $bottom = $v;
                continue; 
            }
            // below 0 are errors
            if ($v[1] >= 0) {
                $v[0] = $this->makeLink($v[0]);
            }
            $v[1] = $this->reportStatus($v[1]);
            $text .= "<tr><td>{$v[0]}</td><td>{$v[1]}</td><td>{$v[2]}</td></tr>\n";
        }
        
        $text .= "<tfoot><tr><td>{$v[0]}</td><td>{$v[1]}</td><td>{$v[2]}</td></tr></tfoot>\n";

        $text .= <<<HTML
										</tbody>
									</table>

<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	$('#hashtable-{$counter}').dataTable( {
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
        
        $output->push($text);
    }
    
    private function reportStatus($count) {
        if ($count == \Analyzer\Analyzer::VERSION_INCOMPATIBLE) {
            return '<i class="fa fa-stethoscope"></i>';
        } elseif ($count == \Analyzer\Analyzer::CONFIGURATION_INCOMPATIBLE) {
            return '<i class="fa fa-stethoscope"></i>';
        } else {
            return $count;
        }
    }

}

?>