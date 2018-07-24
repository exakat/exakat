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

namespace Exakat\Reports;

use Exakat\Analyzer\Analyzer;
use Exakat\Tasks\Report;
use Exakat\Tasks\Tasks;
use Exakat\Reports\Reports;
use Exakat\Config;

class All extends Reports {
    const FILE_EXTENSION = '';
    const FILE_FILENAME  = 'exakat';

    public function generate($folder, $name) {
        $omit = array('AmbassadorNoMenu',
                      'FacetedJson',
                      'OnepageJson',
                      );
        $reports = array_diff(Reports::$FORMATS, $omit);

        foreach($reports as $reportName) {
            display("Reporting with $reportName\n----------------------------------------\n");
            $reportClass = Reports::getReportClass($reportName);
            
            $report = new $reportClass($this->config);
            $report->generate($folder, $name);
        }
    }

    public function dependsOnAnalysis() {
        return array('All',
                     );
    }

}

?>