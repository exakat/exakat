<?php

namespace Report\Format\Ace;

class Horizontal extends \Report\Format\Ace { 
    public function render($output, $data) {
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
								<table id="sample-table-2" class="table table-striped table-bordered table-hover">
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

HTML;

        $output->push($html);
    }

}

?>