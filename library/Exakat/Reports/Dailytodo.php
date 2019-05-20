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

class Dailytodo extends Reports {
    const FILE_EXTENSION = '';
    const FILE_FILENAME  = 'todo';
    
    private $tmpName     = '';
    private $finalName   = '';

    public function generate($folder, $name= 'todo') {
        $this->finalName = "$folder/$name";
        $this->tmpName   = "{$this->config->tmp_dir}/.$name";

        $this->initFolder();
        $this->generateData($folder);
        $this->cleanFolder();
    }
    
    private function generateData($folder, $name = 'table') {
        $thema = $this->config->thema ?? array('Analyzer');
        $list = $this->themes->getThemeAnalyzers($thema);
        $list = makeList($list);

        $sqlQuery = "SELECT count(*) AS nb FROM results WHERE analyzer in ($list)";
        $res = $this->sqlite->query($sqlQuery);
        $row = $res->fetchArray(\SQLITE3_ASSOC);
        $total = $row['nb'];
        $reporting = 10;

        $sqlQuery = "SELECT * FROM results WHERE analyzer in ($list)";
        $res = $sqlite->query($sqlQuery);
        while($row = $res->fetchArray(\SQLITE3_ASSOC)){
            $all[] = $row;
        }
        
        if (empty($all)) {
            return;
        }
        srand(date('dmY'));
        shuffle($all);
        $res = array_slice($all, 0, $reporting);

        $todos = array();
        $count = 0;
        foreach($res as $row) {
            $docs = $this->getDocs($row['analyzer']);
            
            $fullcode = $this->syntaxColoring($row['fullcode']);
            $file = $row['file'] . ':' . $row['line'];
            $first = substr($docs['description'], 0, strpos($docs['description'], '.') + 1 );
            $todos[] = <<<HTML
                <tr>
                  <td class="text-center">
                    $count
                  </td>
                  <td class="text-center">
                    <label class="colorinput">
                        <input name="color" type="checkbox" value="azure" class="colorinput-input" />
                        <span class="colorinput-color bg-azure"></span>
                    </label>
                  </td>
                  <td class="text-right">$file</td>
                  <td>
                    <p class="font-w600 mb-1">$docs[name] : $first</p>
                    <div class="text-muted"><pre>$fullcode</pre></div>
                  </td>
                </tr>

HTML;
            ++$count;
        }
        
        $html = file_get_contents($this->tmpName . '/invoice.html');
        $html = str_replace('<reporting>', $reporting, $html);
        $html = str_replace('<count>', $count, $html);
        $html = str_replace('<total>', $total, $html);
        $html = str_replace('<thema>', $thema, $html);
        $html = str_replace('<date>', date('l, F jS Y'), $html);
        $html = str_replace('<todos>', implode('', $todos), $html);
        $html = str_replace('<thanks>', $this->getThanks(), $html);
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
        copyDir("{$this->config->dir_root}/media/tabler", $this->tmpName );
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
    
    private function getThanks() {
        $thanks = parse_ini_file("{$this->config->dir_root}/data/thankyou.ini", INI_PROCESS_SECTIONS);
        $thanks = $thanks['thanks'];
        shuffle($thanks);
        
        return array_pop($thanks);
    }
}

?>