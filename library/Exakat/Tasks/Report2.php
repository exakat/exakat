<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Tasks;

use Exakat\Config;
use Exakat\Analyzer\Analyzer;
use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\NoSuchFormat;
use Exakat\Exceptions\ProjectNeeded;
use Exakat\Exceptions\ProjectNotInited;
use Exakat\Exceptions\NoDump;
use Exakat\Reports\Reports as Report;

class Report2 extends Tasks {
    const CONCURENCE = self::ANYTIME;

    public function run() {
        if ($this->config->project === 'default') {
            throw new ProjectNeeded();
        }

        if (!file_exists($this->config->projects_root.'/projects/')) {
            throw new NoSuchProject($this->config->project);
        }

        $reportClass = '\\Exakat\\Reports\\'.$this->config->format;

        if (!class_exists($reportClass)) {
            throw new NoSuchFormat($this->config->format, Report::$FORMATS);
        }

        if (!file_exists($this->config->projects_root.'/projects/'.$this->config->project.'/datastore.sqlite')) {
            throw new ProjectNotInited($this->config->project);
        }

        $dumpFile = $this->config->projects_root.'/projects/'.$this->config->project.'/dump.sqlite';
        if (!file_exists($dumpFile)) {
            throw new NoDump($this->config->project);
        }

        Analyzer::$datastore = $this->datastore;
        // errors, warnings, fixable and filename
        // line number => columnnumber => type, source, severity, fixable, message

        $ProjectDumpSql = 'SELECT count FROM resultsCounts WHERE analyzer LIKE "Project/Dump"';
        $dump = new \Sqlite3($dumpFile, \SQLITE3_OPEN_READONLY);
        $res = $dump->query($ProjectDumpSql);
        $row = $res->fetchArray(\SQLITE3_NUM);

        display('Building report for project '.$this->config->project.' in file "'.$this->config->file.'", with format '.$this->config->format."\n");
        $begin = microtime(true);

        // Choose format from options

        $report = new $reportClass($this->config);
        if ($this->config->file === Report::STDOUT) {
            echo $report->generate( $this->config->projects_root.'/projects/'.$this->config->project, Report::STDOUT);
        } else {
            $report->generate( $this->config->projects_root.'/projects/'.$this->config->project, $this->config->file);
        }
        display('Reported '.$report->getCount().' messages in '.$this->config->format);
        $end = microtime(true);

        display('Processing time : '.number_format($end - $begin, 2).'s');
        $this->datastore->addRow('hash', array($this->config->format => $this->config->file));
        display('Done');
    }
}

?>
