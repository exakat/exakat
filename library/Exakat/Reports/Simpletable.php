<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
    
    private $select = array();

    public function generate($folder, $name= 'table') {
        $this->finalName = $folder.'/'.$name;
        $this->tmpName = $folder.'/.'.$name;

        $this->initFolder();
        $this->generateData($folder);
        $this->cleanFolder();
    }
    
    private function generateData($folder, $name = 'table') {
        $list = Analyzer::getThemeAnalyzers(array('Performances'));
        $list = '"'.join('", "', $list).'"';

        $sqlite = new \Sqlite3($folder.'/dump.sqlite');
        $sqlQuery = 'SELECT * FROM results WHERE analyzer in ('.$list.') ORDER BY analyzer';
        $res = $sqlite->query($sqlQuery);
        
        while($row = $res->fetchArray(\SQLITE3_ASSOC)){
            $results[$row['analyzer']][] = array('code' => $row['fullcode'], 
                                                 'file' => $row['file'].':'.$row['line']);
        }

        $table = '';
        foreach($results as $section => $lines) {
            $table .= <<<HTML
		<tbody class="labels">
			<tr>
				<td colspan="5">
					<label for="$section">$section</label>
					<input type="checkbox" name="accounting" id="$section" data-toggle="toggle">
				</td>
			</tr>
		</tbody>
		<tbody class="hide">
			<tr>
				<td>Australia</td>
				<td>$7,685.00</td>
				<td>$3,544.00</td>
				<td>$5,834.00</td>
				<td>$10,583.00</td>
			</tr>
			<tr>
				<td>Central America</td>
				<td>$7,685.00</td>
				<td>$3,544.00</td>
				<td>$5,834.00</td>
				<td>$10,583.00</td>
			</tr>
		</tbody>
HTML;
        }
        
        print strlen($table)."\n";
        $html = file_get_contents($this->tmpName.'/index.html');
        $html = str_replace('<sections />', $table, $html);
        file_put_contents($this->tmpName.'/index.html', $html);
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
        copyDir($this->config->dir_root.'/media/simpletable', $this->tmpName );
    }

    private function cleanFolder() {
        $html = file_get_contents($this->tmpName.'/index.html');

        if (file_exists($this->finalName)) {
            rename($this->finalName, $this->tmpName.'2');
        }

        rename($this->tmpName, $this->finalName);

        if (file_exists($this->tmpName.'2')) {
            rmdirRecursive($this->tmpName.'2');
        }
    }

}

?>