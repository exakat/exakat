<?php
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

namespace Exakat\Reports;

use Exakat\Analyzer\Analyzer;

class Simpletable extends Reports {
    const FILE_EXTENSION = '';
    const FILE_FILENAME  = 'table';
    
    private $tmpName     = '';
    private $finalName   = '';

    public function generate($folder, $name= 'table') {
        $this->finalName = "$folder/$name";
        $this->tmpName   = "{$this->config->tmp_dir}/.$name";

        $this->initFolder();
        $this->generateData($folder);
        $this->cleanFolder();
    }
    
    private function generateData($folder, $name = 'table') {
        $list = $this->themes->getRulesetsAnalyzers(array('Analyze'));
        $list = makeList($list);

        $sqlQuery = 'SELECT * FROM results WHERE analyzer in (' . $list . ') ORDER BY analyzer';
        $res = $this->sqlite->query($sqlQuery);
        
        $results = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)){
            $results[$row['analyzer']][] = array('code' => $this->syntaxColoring($row['fullcode']),
                                                 'file' => $row['file'],
                                                 'line' => $row['line']);
        }

        $table = '';
        foreach($results as $section => $lines) {
            $rows = array();
            
            foreach($lines as $line) {
                $rows[] = <<<HTML
			<tr>
				<td>{$line['code']}</td>
				<td>{$line['file']}</td>
				<td>{$line['line']}</td>
			</tr>

HTML;
            }
            
            $ini = $this->getDocs($section);
            $title = makeHtml($ini['name']);

            $rows = implode('', $rows);
            $c = count($lines);
            $table .= <<<HTML
		<tbody class="labels">
			<tr>
				<td colspan="5">
					<label for="$section">($c) $title</label>
					<input type="checkbox" name="accounting" id="$section" data-toggle="toggle">
				</td>
			</tr>
		</tbody>
		<tbody class="hide">
		    $rows
		</tbody>
HTML;
        }
        
        $html = file_get_contents($this->tmpName . '/index.html');
        $html = str_replace('<sections />', $table, $html);
        file_put_contents($this->tmpName . '/index.html', $html);
    }

    private function initFolder() {
        if ($this->finalName === 'stdout') {
            return "Can't produce Simpletable format to stdout";
        }

        // Clean temporary destination
        if (file_exists($this->tmpName)) {
            rmdirRecursive($this->tmpName);
        }

        // Copy template
        copyDir($this->config->dir_root . '/media/simpletable', $this->tmpName );
    }

    private function cleanFolder() {
        if (file_exists($this->finalName)) {
            rename($this->finalName, $this->tmpName . '2');
        }

        rename($this->tmpName, $this->finalName);

        if (file_exists($this->tmpName . '2')) {
            rmdirRecursive($this->tmpName . '2');
        }
    }

    private function syntaxColoring($source) {
        $colored = highlight_string('<?php ' . $source . ' ;?>', true);
        $colored = substr($colored, 79, -65);

        if ($colored[0] === '$') {
            $colored = '<span style="color: #0000BB">' . $colored;
        }

        return $colored;
    }
}

?>