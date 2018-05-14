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
use Exakat\Config;

class All extends Reports {
    const FILE_EXTENSION = '';
    const FILE_FILENAME  = 'exakat';

    public function generate($folder, $name) {
        $reports = array('Ambassador',
                         'AmbassadorNoMenu',
                         'Clustergrammer',
                         'CodeSniffer',
                         'Codeflower',
                         'Composer',
                         'Dependencies',
                         'Dependencywheel',
                         'Diplomat',
                         'Drillinstructor',
//                         'FacetedJson',
                         'FileDependencies',
                         'FileDependenciesHtml',
                         'Inventories',
                         'Json',
                         'Marmelab',
                         'Melis',
                         'None',
//                         'OnepageJson',
                         'Owasp',
                         'PhpCompilation',
                         'PhpConfiguration',
                         'PlantUml',
                         'RadwellCode',
                         'SimpleHtml',
                         'Simpletable',
                         'Slim',
                         'Stats',
                         'Text',
                         'Uml',
                         'Xml',
                         'ZendFramework',
        );

        foreach($reports as $report) {
            display("Reporting with $report\n----------------------------------------\n");
            $classReport = '\\Exakat\\Reports\\'.$report;
            
            $report = new $classReport($this->config);
            $report->generate($folder, $name);


        }
    }
}

?>