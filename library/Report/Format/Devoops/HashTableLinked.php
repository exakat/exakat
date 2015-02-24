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

class HashTableLinked extends \Report\Format\Devoops { 
    static public $hastable_counter = 0;
    
    public function render($output, $data) {

        $output->pushToJsLibraries( array("assets/js/jquery.dataTables.min.js",
                                          "assets/js/jquery.dataTables.bootstrap.js"));

        $counter = \Report\Format\Ace\HashTable::$hastable_counter++;
        
$js = <<<JS
    				var oTable1 = \$('#hashtable-{$counter}').dataTable( {
	    			"aoColumns": [
		    	      null, null
			    	] } );


JS;
        $output->pushToTheEnd($js);
//id="datatable-1"

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
            if ($v['result'] !== 0) {
                $k = $this->makeLink($k);
                $icon = '<i class="fa fa-check-o red"></i>';
                $v['result'] .= " warnings";
            } else {
                $icon = '<i class="fa fa-check-square-o green"></i>';
                $v['result'] = "";
            }
            $text .= "<tr><td>$k</td><td>$icon".$v['result']."</td></tr>\n";
        }
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

}

?>
