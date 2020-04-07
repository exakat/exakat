<?php declare(strict_types = 1);
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


class Dailytodo extends Reports {
    const FILE_EXTENSION = '';
    const FILE_FILENAME  = 'todo';

    private $tmpName     = '';
    private $finalName   = '';

    public function generate(string $folder, string $name= 'todo'): string {
        $this->finalName = "$folder/$name";
        $this->tmpName   = "{$this->config->tmp_dir}/.$name";

        $this->initFolder();
        $this->generateData($folder);
        $this->cleanFolder();

        return '';
    }

    private function generateData($folder, $name = 'table') {
        $project_rulesets = $this->config->project_rulesets ?? array('Analyzer');
        $list = $this->rulesets->getRulesetsAnalyzers($project_rulesets);

        $res = $this->dump->fetchAnalysersCounts($list);
        $total = $res->getCount();
        $all = $res->toArray();

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

        $tags = array('<reporting>',
                      '<count>',
                      '<total>',
                      '<thema>',
                      '<date>',
                      '<todos>',
                      '<thanks>',
                      );

        $values = array($reporting,
                        $count,
                        $total,
                        $project_rulesets,
                        date('l, F jS Y'),
                        implode('', $todos),
                        $this->getThanks(),
                        );

        $html = file_get_contents($this->tmpName . '/invoice.html');
        $html = str_replace($tags, $values, $html);
        file_put_contents($this->tmpName . '/index.html', $html);
    }

    private function initFolder() {
        if ($this->finalName === 'stdout') {
            return "Can't produce Dailytodo format to stdout";
        }

        // Clean temporary destination
        if (file_exists($this->tmpName)) {
            rmdirRecursive($this->tmpName);
        }

        // Copy template
        copyDir("{$this->config->dir_root}/media/tabler", $this->tmpName);
    }

    private function cleanFolder(): void {
        if (file_exists($this->finalName)) {
            rename($this->finalName, $this->tmpName . '2');
        }

        rename($this->tmpName, $this->finalName);

        if (file_exists($this->tmpName . '2')) {
            rmdirRecursive($this->tmpName . '2');
        }
    }

    private function syntaxColoring(string $source): string {
        $colored = highlight_string('<?php ' . $source . ' ;?>', \RETURN_VALUE);
        $colored = substr($colored, 79, -65);

        if ($colored[0] === '$') {
            $colored = '<span style="color: #0000BB">' . $colored;
        }

        return $colored;
    }

    private function getThanks(): string {
        $thanks = parse_ini_file("{$this->config->dir_root}/data/thankyou.ini", \INI_PROCESS_SECTIONS);
        $thanks = $thanks['thanks'];
        shuffle($thanks);

        return array_pop($thanks);
    }
}

?>