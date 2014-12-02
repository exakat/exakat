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
                $k =  $this->makeLink($k);
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