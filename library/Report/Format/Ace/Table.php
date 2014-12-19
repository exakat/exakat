<?php

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
