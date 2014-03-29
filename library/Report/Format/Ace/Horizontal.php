<?php

namespace Report\Format\Ace;

class Horizontal extends \Report\Format\Ace { 
    static $horizontal_counter = 0;
    
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

        $html = '';
        foreach($data as $row) {
$html .= <<<HTML

										<tr>
											<td><pre class="prettyprint linenums">{$row['code']}</pre></td>
											<td>{$row['desc']}</td>
											<td>{$row['file']}</td>
											<td>{$row['line']}</td>
										</tr>
HTML;
            }

        $html = <<<HTML
							<p>
								<table id="horizontal-{$counter}" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th>Code</th>
											<th>Description</th>
											<th>File</th>
											<th>Line</th>
										</tr>
									</thead>


									<tbody>
$html
									</tbody>
								</table>
							</p>
HTML;

        $output->push($html);
    }

}

?>