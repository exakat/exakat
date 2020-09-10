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

namespace Exakat\Tasks;

use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\ProjectNeeded;
use Exakat\Exceptions\InvalidProjectName;
use Exakat\Exceptions\ProjectNotInited;
use Exakat\Exceptions\NoDump;
use Exakat\Exceptions\NoDumpYet;
use Exakat\Reports\Reports as Reports;
use Exakat\Tasks\Helpers\ReportConfig;
use Exakat\Dump\Dump;

class Report extends Tasks {
    const CONCURENCE = self::ANYTIME;

    public function run(): void {
        if ($this->config->project->isDefault()) {
            throw new ProjectNeeded();
        }

        if (!$this->config->project->validate()) {
            throw new InvalidProjectName($this->config->project->getError());
        }

        if (!file_exists($this->config->project_dir)) {
            throw new NoSuchProject($this->config->project);
        }

        if (!file_exists($this->config->datastore)) {
            throw new ProjectNotInited($this->config->project);
        }

        if (!file_exists($this->config->dump)) {
            throw new NoDump((string) $this->config->project);
        }

        $dump = Dump::factory($this->config->dump, Dump::READ);
        $res = $dump->fetchAnalysersCounts(array('Project/Dump'));

        if ($res->toInt('count') !== 1) {
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

    private function format(Reports $report, ReportConfig $reportConfig) {
        $begin = microtime(true);

        if ($reportConfig->getFile() === Reports::STDOUT) {
            display("Building report for project {$this->config->project_name} to stdout, with report " . $reportConfig->getFormat() . "\n");
            $report->generate($this->config->project_dir, Reports::STDOUT);
        } elseif (empty($reportConfig->getFile())) {
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
