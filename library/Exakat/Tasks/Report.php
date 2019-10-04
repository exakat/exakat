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


namespace Exakat\Tasks;

use Exakat\Config;
use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\NoSuchFormat;
use Exakat\Exceptions\ProjectNeeded;
use Exakat\Exceptions\InvalidProjectName;
use Exakat\Exceptions\ProjectNotInited;
use Exakat\Exceptions\NoDump;
use Exakat\Exceptions\NoDumpYet;
use Exakat\Project as ProjectName;
use Exakat\Reports\Reports as Reports;
use Exakat\Tasks\Helpers\ReportConfig;

class Report extends Tasks {
    const CONCURENCE = self::ANYTIME;

    public function run() {
        $project = new ProjectName($this->config->project);

        if (!$project->validate()) {
            throw new InvalidProjectName($project->getError());
        }

        if ($this->config->project === 'default') {
            throw new ProjectNeeded();
        }

        if (!file_exists($this->config->project_dir)) {
            throw new NoSuchProject($this->config->project);
        }

        if (!file_exists($this->config->datastore)) {
            throw new ProjectNotInited($this->config->project);
        }

        if (!file_exists($this->config->dump)) {
            throw new NoDump($this->config->project);
        }

        $dump = new \Sqlite3($this->config->dump, \SQLITE3_OPEN_READONLY);
        $ProjectDumpSql = 'SELECT count FROM resultsCounts WHERE analyzer LIKE "Project/Dump"';
        $res = $dump->query($ProjectDumpSql);
        $row = $res->fetchArray(\SQLITE3_NUM);

        if (empty($row) || ($row[0] === 0)) {
            throw new NoDumpYet($this->config->project);
        }

        foreach($this->config->project_reports as $format) {
            $reportConfig = new ReportConfig($format, $this->config);
            $reportClass = $reportConfig->getFormatClass();
            if (!class_exists($reportClass)) {
                display('No such format as ' . $reportConfig->getFormat() . '. Omitting.');
                continue;
            }

            $report = new $reportClass($reportConfig->getConfig());

            $this->format($report, $reportConfig);
        }
    }
    
    private function format(Reports $report, $reportConfig) {
        $begin = microtime(true);

        if ($reportConfig->getFile() === Reports::STDOUT) {
            display("Building report for project {$this->config->project_name} to stdout, with report " . $reportConfig->getFormat() . "\n");
            $report->generate($this->config->project_dir, Reports::STDOUT);
        } elseif (empty($reportConfig->getFile())) {
            $file = $report::FILE_FILENAME . ($report::FILE_EXTENSION ? '.' . $report::FILE_EXTENSION : '');
            display("Building report for project {$this->config->project_name} in '" . $reportConfig->getFile() . "', with report " . $reportConfig->getFormat() . "\n");
            $report->generate($this->config->project_dir, $report::FILE_FILENAME);
        } else {
            // to files + extension
            $filename = basename($reportConfig->getFile());
            if (in_array($filename, array('.', '..'))) {
                $filename = $report::FILE_FILENAME;
            }
            display('Building report for project ' . $this->config->project . ' in "' . $reportConfig->getFile() . ($report::FILE_EXTENSION ? '.' . $report::FILE_EXTENSION : '') . "', with format " . $reportConfig->getFormat() . "\n");
            $report->generate( $this->config->project_dir, $filename);
        }
        display('Reported ' . $report->getCount() . ' messages in ' . $reportConfig->getFormat());

        $end = microtime(true);
        display('Processing time : ' . number_format($end - $begin, 2) . 's');

        $this->datastore->addRow('hash', array($reportConfig->getFormat() => $reportConfig->getFile() ));
        display('Done');
    }
}

?>
