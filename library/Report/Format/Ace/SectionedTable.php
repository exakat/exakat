<?php

namespace Report\Format\Ace;

class SectionedTable extends \Report\Format\Ace { 
    static public $sectionedtable_counter = 0;
    
    public function render($output, $data) {

        $counter = \Report\Format\Ace\SectionedHashTable::$sectionedhastable_counter++;
        
        $text = <<<HTML
<table id="sectionedhashtable-{$counter}" class="table table-striped table-bordered table-hover">
										<thead>
HTML;
        
        if ($this->css->displayTitles === true) {
            $text .= '<tr>';
            foreach($this->css->titles as $title) {
                $text .= <<<HTML
															<th>
																$title
															</th>

HTML;
            }
            $text .= "</tr>";
        }

$text .= <<<HTML
										</thead>

										<tbody>
HTML;
        $readOrder = $this->css->readOrder;
        if (empty($readOrder)) {
            $readOrder = range(0, count($this->css->titles)-1);
        }
        foreach($data as $k => $v) {
            $text .= "<tr><td style=\"background-color: {$this->css->backgroundColor}\">$k</td>".
            str_repeat("<td style=\"background-color: {$this->css->backgroundColor}\">&nbsp;</td>", count($this->css->titles) -1)."</tr>\n";
            if (is_array($v)) {
                foreach($v as $v2) {
                    $text .= "<tr>";
                    foreach($readOrder as $id) {
                        $text .= "<td>$v2[$id]</td>\n";
                    }
                    $text .= "</tr>\n";
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