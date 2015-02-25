<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/


namespace Report\Format\Devoops;

class TableForVersions extends \Report\Format\Devoops { 
    static public $tableforversions_counter = 0;

    public function render($output, $data) {

        $counter = self::$tableforversions_counter++;

        $html = <<<HTML
<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="tableforversions-{$counter}">
										<thead>
											<tr>
												<th>File</th>
												<th>PHP 7.0</th>
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
                                'php70' => '<button class="btn btn-app btn-success .btn-circle"><i class="fa fa-thumbs-o-up"></i></button>',
                                'php56' => '<button class="btn btn-app btn-success .btn-circle"><i class="fa fa-thumbs-o-up"></i></button>',
                                'php55' => '<button class="btn btn-app btn-success .btn-circle"><i class="fa fa-thumbs-o-up"></i></i></button>',
                                'php54' => '<button class="btn btn-app btn-success .btn-circle"><i class="fa fa-thumbs-o-up"></i></i></button>',
                                'php53' => '<button class="btn btn-app btn-success .btn-circle"><i class="fa fa-thumbs-o-up"></i></i></button>',
                                );
            }
            $rows[$d['file']]['php'.$d['version']] = $d['error'];
        }

        foreach($rows as $d) {
            $row = <<<HTML
											<tr>
												<td>{$d['file']}</td>
												<td>{$d['php70']}</td>
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
