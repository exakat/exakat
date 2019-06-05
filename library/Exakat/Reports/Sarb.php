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

class Sarb extends Reports {
    const FILE_EXTENSION = 'json';
    const FILE_FILENAME  = 'exakat.sarb';

    public function _generate($analyzerList) {
        $analysisResults = new Results($this->sqlite, $analyzerList);
        $analysisResults->load();
        $code_dir = $this->config->code_dir;

        $results = array();
        foreach($analysisResults->toArray() as $row) {
            if ($row['line'] === -1) { 
                // Skip project-wide issues
                continue; 
            }
            $message = array('type' => $row['analyzer'],
                             'file' => $code_dir.$row['file'],
                             'line' => $row['line'],
                             );
            $results[] = $message;
        }

        return json_encode($results, \JSON_PRETTY_PRINT);
    }
}

?>