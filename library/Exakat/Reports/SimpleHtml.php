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

class SimpleHtml extends Reports {
    const FILE_EXTENSION = '';
    const FILE_FILENAME  = 'exakat';

    protected $finalName       = null;
    protected $tmpName           = '';

    public function generate($folder, $name = self::FILE_FILENAME) {
        $this->finalName = $folder.'/'.$name;
        $this->tmpName = $folder.'/.'.$name;
        
        $blocks = array();
        $contents = array();

        $this->initFolder();
        
        $blocks[] = '{{INTRODUCTION}}';
        $contents[] = $this->makeIntro();

        $blocks[] = '{{SUMMARY}}';
        $contents[] = $this->makeSummary($folder);

        $blocks[] = '{{LIST}}';
        $contents[] = $this->makeList($folder);

        $html = file_get_contents($this->tmpName.'/index.html');
        $html = str_replace($blocks, $contents, $html);
        file_put_contents($this->tmpName.'/index.html', $html);
        
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
            $text .= "<tr><th>Exakat version :</th><td>".\Exakat\Exakat::VERSION." (".\Exakat\Exakat::BUILD.") </td></tr>\n";
        }

        return $text; 
    }

    private function makeSummary($folder) {
        if ($this->config->thema !== null) {
            $list = Analyzer::getThemeAnalyzers(array($this->config->thema));
            $list = '"'.implode('", "', $list).'"';
        } elseif ($this->config->program !== null) {
            $list = '"'.$this->config->program.'"';
        } else {
            $list = Analyzer::getThemeAnalyzers($this->themesToShow);
            $list = '"'.implode('", "', $list).'"';
        }

        $sqlite = new \Sqlite3($folder.'/dump.sqlite');
        $sqlQuery = 'SELECT * FROM resultsCounts WHERE analyzer in ('.$list.') AND count > 0';
        $res = $sqlite->query($sqlQuery);

        $text = '';
        $titleCache = array();
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            if (!isset($titleCache[$row['analyzer']])) {
                $analyzer = Analyzer::getInstance($row['analyzer'], null, $this->config);
                $titleCache[$row['analyzer']] = $analyzer->getDescription()->getName();
            }

            $text .= <<<HTML
<tr>
    <td class="SUMM_DESC">{$titleCache[$row['analyzer']]}</td>
    <td class="Q">{$row['count']}</td>
    <td><center><input type="checkbox" onClick="ToggleDisplay(this,'{$this->makeId($row['analyzer'])}');" checked/></center></td>
</tr>

HTML;
        }
        
        return $text;
    }
        
    private function makeList($folder) {
        if ($this->config->thema !== null) {
            $list = Analyzer::getThemeAnalyzers(array($this->config->thema));
            $list = '"'.implode('", "', $list).'"';
        } elseif ($this->config->program !== null) {
            $list = '"'.$this->config->program.'"';
        } else {
            $list = Analyzer::getThemeAnalyzers($this->themesToShow);
            $list = '"'.implode('", "', $list).'"';
        }

        $sqlite = new \Sqlite3($folder.'/dump.sqlite');
        $sqlQuery = 'SELECT * FROM results WHERE analyzer in ('.$list.')';
        $res = $sqlite->query($sqlQuery);

        $results = array();
        $titleCache = array();
        $severityCache = array();
        $timeToFixCache = array();
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            if (!isset($results[$row['file']])) {
                $file = array('errors'   => 0,
                              'warnings' => 0,
                              'fixable'  => 0,
                              'filename' => $row['file'],
                              'messages' => array());
                $results[$row['file']] = $file;
            }

            if (!isset($titleCache[$row['analyzer']])) {
                $analyzer = Analyzer::getInstance($row['analyzer'], null, $this->config);
                $titleCache[$row['analyzer']] = $analyzer->getDescription()->getName();
                $severityCache[$row['analyzer']] = $analyzer->getSeverity();
                $timeToFixCache[$row['analyzer']] = $analyzer->getTimeToFix();
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
        if ($this->finalName === 'stdout') {
            return "Can't produce SimpleHtml format to stdout";
        }

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