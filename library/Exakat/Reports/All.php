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


class All extends Reports {
    const FILE_EXTENSION = '';
    const FILE_FILENAME  = 'allExakat';

    public function generate(string $folder, string $name = 'table'): string {
        $omit = array('Ambassadornomenu',
                      'Facetedjson',
                      'Onepagejson',
                      'Topology',
                      );
        $reports = array_diff(self::$FORMATS, $omit);

        foreach($reports as $reportName) {
            display("Reporting with $reportName\n----------------------------------------\n");
            $reportClass = self::getReportClass($reportName);

            $report = new $reportClass($this->config);
            $report->generate($folder, $report::FILE_FILENAME ===  self::STDOUT ? self::FILE_FILENAME : $report::FILE_FILENAME);
        }

        return '';
    }

    public function dependsOnAnalysis(): array {
        $themesToRun = array(array());
        foreach(self::$FORMATS as $format) {
            $reportClass = "\Exakat\Reports\\$format";
            if (!class_exists($reportClass)) {
                continue;
            }
            $report = new $reportClass($this->config);

            $themesToRun[] = $report->dependsOnAnalysis();
            unset($report);
            gc_collect_cycles();
        }

        return array_unique(array_merge(...$themesToRun));
    }
}

?>