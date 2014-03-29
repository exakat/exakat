<?php

namespace Report\Format\Ace;

class HashTable extends \Report\Format\Ace { 
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
        
        $text = <<<HTML
<table id="hashtable-{$counter}" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th>Label</th>
												<th>Value</th>
											</tr>
										</thead>

										<tbody>
HTML;
        foreach($data as $k => $v) {
            $text .= "<tr><td>$k</td><td>$v</td></tr>\n";
        }
        $text .= <<<HTML
										</tbody>
									</table>
HTML;
        
        $output->push($text);
    }

}

?>