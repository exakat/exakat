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

class HashTableLinked extends \Report\Format\Ace { 
    static public $hastableCounter = 0;
    
    public function render($output, $data) {

        $output->pushToJsLibraries( array("assets/js/jquery.dataTables.min.js",
                                          "assets/js/jquery.dataTables.bootstrap.js"));

        $counter = \Report\Format\Ace\HashTable::$hastableCounter++;
        
$js = <<<JS
    				var oTable1 = \$('#hashtable-{$counter}').dataTable( {
	    			"aoColumns": [
		    	      null, null
			    	] } );


JS;
        $output->pushToTheEnd($js);

        $text = <<<HTML
<table id="hashtable-{$counter}" class="table table-striped table-bordered table-hover">
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
                $icon = '<i class="icon-remove red"></i> ';
                $v['result'] .= " warnings";
            } else {
                $icon = '<i class="icon-ok green"></i>';
                $v['result'] = "";
            }
            $text .= "<tr><td>$k</td><td>$icon".$v['result']."</td></tr>\n";
        }
        $text .= <<<HTML
										</tbody>
									</table>
HTML;
        
        $output->push($text);
    }

}

?>
