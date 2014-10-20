<?php

namespace Report\Format\Ace;

class HashTableLinked extends \Report\Format\Ace { 
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
            if ($v['result'] == 'OK') {
                $k = "$k"; // @todo make this bold
            } else {
                $url_file =  str_replace(array(' ', '('  , ')', ':'  ), 
                               array('-', '', ''),
                               $k).'.html';
                $k = "<a href=\"$url_file\">$k</a>"; // @todo make this bold
            }
            $text .= "<tr><td>$k</td><td>".
                ($v['result'] == 'OK' ? '<i class="icon-ok green"></i>' : '<i class="icon-remove red"></i> '.$v['result'])."</td></tr>\n";
        }
        $text .= <<<HTML
										</tbody>
									</table>
HTML;
        
        $output->push($text);
    }

}

?>