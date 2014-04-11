<?php

namespace Report\Format\Ace;

class SimpleTable extends \Report\Format\Ace { 
    static public $table_counter = 0;
    private $titles = array();
    
    public function render($output, $data) {

        $th = '<tr>';
        foreach($this->titles as $title) {
            $th .= <<<HTML
															<th>
																$title
															</th>

HTML;
        }
        $th .= "</tr>";
        
        $text = <<<HTML
												<table class="table table-bordered table-striped">
													<thead>
														<tr>
{$th}
														</tr>
													</thead>

													<tbody>

HTML;
        foreach($data as $v) {
            $row = '<tr>';
            foreach($v as $V) {
                $row .= "<td>$V</td>\n";
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

    public function setTitles($titles) {
        $this->titles = $titles;
    }
}

?>