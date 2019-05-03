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
use Exakat\Reports\Helpers\Results;

class Simplehtml extends Reports {
    const FILE_EXTENSION = '';
    const FILE_FILENAME  = 'exakat';

    protected $finalName       = null;
    protected $tmpName         = '';

    public function generate($folder, $name = self::FILE_FILENAME) {
        if ($name === self::STDOUT) {
            print "Can't produce SimpleHtml format to stdout\n";
            return false;
        }

        $this->finalName = "$folder/$name";
        $this->tmpName = "{$this->config->tmp_dir}/.$name";
        
        $blocks = array();
        $contents = array();

        $this->initFolder();
        
        $blocks[] = '{{INTRODUCTION}}';
        $contents[] = $this->makeIntro();

        $blocks[] = '{{SUMMARY}}';
        $contents[] = $this->makeSummary($folder);

        $blocks[] = '{{LIST}}';
        $contents[] = $this->makeList($folder);

        $html = file_get_contents("{$this->tmpName}/index.html");
        $html = str_replace($blocks, $contents, $html);
        file_put_contents("{$this->tmpName}/index.html", $html);
        
        $this->cleanFolder();
    }

    private function makeIntro() {
        $date = date('r');
        $text = "<tr><th>Date:</th><td>$date</td></tr>\n";

        $audit_name = $this->datastore->getHash('audit_name');
        if (!empty($audit_name)) {
            $text .= "<tr><th>Audit name :</th><td>$audit_name</td></tr>\n";
        }

        $audit_name = $this->datastore->getHash('audit_name');
        if (!empty($audit_name)) {
            $text .= '<tr><th>Exakat version :</th><td>'.\Exakat\Exakat::VERSION.' ('.\Exakat\Exakat::BUILD.") </td></tr>\n";
        }

        return $text;
    }

    private function makeSummary($folder) {
        if (empty($this->config->thema)) {
            $list = $this->themes->getThemeAnalyzers($this->config->thema);
            $list = makeList($list);
        } elseif (!empty($this->config->program)) {
            $list = '"'.$this->config->program.'"';
        } else {
            $list = $this->themes->getThemeAnalyzers($this->themesToShow);
            $list = makeList($list);
        }

        $sqlQuery = 'SELECT * FROM resultsCounts WHERE analyzer in ('.$list.') AND count > 0';
        $res = $this->sqlite->query($sqlQuery);

        $text = '';
        $titleCache = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (!isset($titleCache[$row['analyzer']])) {
                $titleCache[$row['analyzer']] = $this->getDocs($row['analyzer'], 'name');
            }

            $text .= <<<HTML
<tr>
    <td class="SUMM_DESC">{$titleCache[$row['analyzer']]}</td>
    <td class="Q">{$row['count']}</td>
    <td><center><input type="checkbox" onClick="ToggleDisplay(this,'{$this->makeId($row['analyzer'])}');" checked/></center></td>
</tr>

HTML;
            $this->count();
        }
        
        return $text;
    }
        
    private function makeList($folder) {
        if (!empty($this->config->thema)) {
            $list = $this->themes->getThemeAnalyzers(array($this->config->thema));
        } elseif (!empty($this->config->program)) {
            $list = array($this->config->program);
        } else {
            $list = $this->themes->getThemeAnalyzers($this->themesToShow);
        }

        $analysisResults = new Results($this->sqlite, $list);
        $analysisResults->load();

        $results = array();
        $titleCache = array();
        $severityCache = array();
        $timeToFixCache = array();
        foreach($analysisResults->toArray() as $row) {
            if (!isset($results[$row['file']])) {
                $file = array('errors'   => 0,
                              'warnings' => 0,
                              'fixable'  => 0,
                              'filename' => $row['file'],
                              'messages' => array());
                $results[$row['file']] = $file;
            }

            if (!isset($titleCache[$row['analyzer']])) {
                $analyzer = $this->themes->getInstance($row['analyzer'], null, $this->config);
                $titleCache[$row['analyzer']]     = $this->getDocs($row['analyzer'], 'name');

                $severityCache[$row['analyzer']]  = $this->getDocs($row['analyzer'], 'severity');
                $timeToFixCache[$row['analyzer']] = $this->getDocs($row['analyzer'], 'timetofix');
            }

            $message = array('source'      => $row['analyzer'],
                             'severity'    => $severityCache[$row['analyzer']],
                             'time to fix' => $timeToFixCache[$row['analyzer']],
                             'message'     => $titleCache[$row['analyzer']],
                             'id'          => $this->makeId($row['analyzer'])
                             );

            if (!isset($results[ $row['file'] ]['messages'][ $row['line'] ])) {
                $results[ $row['file'] ]['messages'][ $row['line'] ] = array(0 => array());
            }
            $results[ $row['file'] ]['messages'][ $row['line'] ][0][] = $message;

            ++$results[ $row['file'] ]['warnings'];
        }

        $text = '';
        foreach($results as $file) {
            foreach($file['messages'] as $line => $column) {
                $messages = $column[0];
                foreach($messages as $message) {
                    //$file['filename'].':'.$line.' '.$message['message']."\n";
                    $text .= <<<HTML
<tr class="{$message['id']}">
    <td class="DESC">{$message['message']}</td>
    <td>{$file['filename']}</td>
    <td class="Q">$line</td>
    <td class="DESC">{$message['severity']}</td>
    <td class="DESC">{$message['time to fix']}</td>
</tr>

HTML;
                }
            }
        }

        return $text;
    }

    private function initFolder() {
        // Clean temporary destination
        if (file_exists($this->tmpName)) {
            rmdirRecursive($this->tmpName);
        }

        // Copy template
        copyDir($this->config->dir_root.'/media/clang', $this->tmpName );
    }

    private function cleanFolder() {
        if (file_exists($this->finalName)) {
            rename($this->finalName, $this->tmpName.'2');
        }

        rename($this->tmpName, $this->finalName);

        if (file_exists($this->tmpName.'2')) {
            rmdirRecursive($this->tmpName.'2');
        }
    }
    
    private function makeId($id) {
        return strtolower(str_replace(array(' ', '(', ')', '/',), '_', $id));
    }
}

?>