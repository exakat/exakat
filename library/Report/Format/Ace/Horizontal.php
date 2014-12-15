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

        $html = <<<HTML
							<p>
								<table id="horizontal-{$counter}" class="table table-striped table-bordered table-hover">
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
            $row['code'] = htmlentities($row['code'], ENT_COMPAT, 'UTF-8');
            
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
HTML;

        $output->push($html);
    }
}

?>
