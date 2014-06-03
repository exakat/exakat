<?php

namespace Report\Format\Ace;

class SectionedHashTable extends \Report\Format\Ace { 
    static public $sectionedhastable_counter = 0;
    
    public function render($output, $data) {

        $counter = \Report\Format\Ace\SectionedHashTable::$sectionedhastable_counter++;
        
        $text = <<<HTML
<table id="sectionedhashtable-{$counter}" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th>Label</th>
												<th>Value</th>
											</tr>
										</thead>

										<tbody>
HTML;
        foreach($data as $k => $v) {
            $text .= "<tr><td style=\"background-color: #DDDDDD\">$k</td><td style=\"background-color: #DDDDDD\">&nbsp;</td></tr>\n";
            if (is_array($v)) {
                foreach($v as $k2 => $v2) {
                    $text .= "<tr><td>$k2</td><td>$v2</td></tr>\n";
                }
            }
        }
        $text .= <<<HTML
										</tbody>
									</table>
HTML;
        
        $output->push($text);
    }

}

?>