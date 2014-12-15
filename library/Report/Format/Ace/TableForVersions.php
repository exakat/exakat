<?php

namespace Report\Format\Ace;

class TableForVersions extends \Report\Format\Ace { 

    public function render($output, $data) {

        $html = <<<HTML
<table id="sample-table-1" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th>File</th>
												<th>PHP 5.6</th>
												<th>PHP 5.5</th>
												<th>PHP 5.4</th>
												<th>PHP 5.3</th>
											</tr>
										</thead>

										<tbody>
HTML;

        $rows = array();
        foreach($data as $d) {
            if (!isset($rows[$d['file']])) {
                $rows[$d['file']] = array('file' => $d['file'],
                                'php56' => '<button class="btn btn-mini btn-success"><i class="icon-ok bigger-120"></i></button>',
                                'php55' => '<button class="btn btn-mini btn-success"><i class="icon-ok bigger-120"></i></button>',
                                'php54' => '<button class="btn btn-mini btn-success"><i class="icon-ok bigger-120"></i></button>',
                                'php53' => '<button class="btn btn-mini btn-success"><i class="icon-ok bigger-120"></i></button>',
                                );
            }
            $rows[$d['file']]['php'.$d['version']] = $d['error'];
        }

        foreach($rows as $d) {
            $row = <<<HTML
											<tr>
												<td>{$d['file']}</td>
												<td>{$d['php56']}</td>
												<td>{$d['php55']}</td>
												<td>{$d['php54']}</td>
												<td>{$d['php53']}</td>
											</tr>
HTML;
            $html .= $row;
        }

$html .= <<<HTML
										</tbody>
									</table>
HTML;
        $output->push( $html);
    }
}

?>
