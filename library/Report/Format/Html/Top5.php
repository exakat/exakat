<?php

namespace Report\Format\Html;

class Top5 extends \Report\Format\Html {
    static public $top5_counter = 0;
    
    private $title = '';
    private $columnsHeaders = array();
    
    public function render($output, $data) {
        $title = "";
        $html = <<<HTML
											<h4 class="lighter">
												$title
											</h4>
												<table class="table table-bordered table-striped">
													<thead>
														<tr>
HTML;

        foreach($this->columnsHeaders as $columnHeader) {
            $html .= <<<HTML
															<th>
																name
															</th>

HTML;
        }
        
        $html .= <<<HTML
														</tr>
													</thead>

													<tbody>
HTML;

        $values = $data;
        uasort($values, function ($a, $b) { if ($a['sort'] == $b['sort']) return 0 ; return $a['sort'] < $b['sort'] ? 1 : -1;});
        $values = array_slice($values, 0, 5);
        foreach($values as $value) {
            $html .= <<<HTML
														<tr>
															<td>{$value['name']}</td>

															<td>
																<b>{$value['count']}</b>
															</td>

															<td>
																{$value['severity']}
															</td>
														</tr>

HTML;
        }
        
        $html .= <<<HTML
													</tbody>
												</table>

HTML;

        $output->push($html);
    }
    
    public function setTitle($title) {
        $this->title = $title;
    }

    public function setColumnHeaders($columnsHeaders) {
        $this->columnsHeaders = $columnsHeaders;
    }
}

?>
