<?php

namespace Report\Format\Ace;

class SimpleTable extends \Report\Format\Ace { 
    static public $table_counter = 0;
    private $titles = array();
    
    public function render($output, $data) {
        $th = '';
        
        if ($this->css->displayTitles === true) {
            $th .= '<tr>';
            foreach($this->css->titles as $title) {
                $th .= <<<HTML
															<th>
																$title
															</th>

HTML;
        }
            $th .= "</tr>";
        }
        
        $text = <<<HTML
												<table class="table table-bordered table-striped">
													<thead>
														<tr>
{$th}
														</tr>
													</thead>

													<tbody>

HTML;
        $readOrder = $this->css->readOrder;
        if (empty($readOrder)) {
            $readOrder = range(0, count($this->css->titles) - 1);
        }
        foreach($data as $v) {
            $row = '<tr>';
            foreach($readOrder as $V) {
                $row .= "<td>$v[$V]</td>\n";
            }
            $row .= "</tr>";

            $text .= $row;
        }
        $text .= <<<HTML
													</tbody>
												</table>
HTML;
        
        $output->push($text);
    }
}

?>