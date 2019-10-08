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
use Exakat\Exakat;

class Rector extends Reports {
    const FILE_EXTENSION = 'yaml';
    const FILE_FILENAME  = 'rector.exakat';
    
    private $matches = array('Php/IsAWithString'                   => 'Rector\CodeQuality\Rector\FuncCall\IsAWithStringWithThirdArgumentRector',
                             'Structures/ShouldPreprocess'         => 'Rector\CodeQuality\Rector\Concat\JoinStringConcatRector',
                             'Structures/ElseIfElseif'             => 'Rector\CodeQuality\Rector\If_\ShortenElseIfR',
                             'Structures/CouldUseShortAssignation' => 'Rector\CodeQuality\Rector\Assign\CombinedAssignRector',
                            );

    public function dependsOnAnalysis() {
        return array('Rector',
                     );
    }

    protected function _generate($analyzerList) {
        $themed = $this->rulesets->getRulesetsAnalyzers($this->dependsOnAnalysis());

        $res = $this->sqlite->query('SELECT analyzer FROM resultsCounts WHERE analyzer IN (' . makeList($themed) . ') AND count >= 1');
        $services = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $services[] = $this->matches[$row['analyzer']];
            $this->count();
        }
        
        $date = date('Y-m-d h:i:j');
        $version = Exakat::VERSION . '- build '. Exakat::BUILD; 

        // preparing the list of PHP extensions to compile PHP with
        $return = <<<YAML
# Add this to your rector.yaml file
# At the root of the source to be analyzed
# Generated on $date, by Exakat ($version)

services:
    
YAML
. implode("\n    ", $services)."\n";

        return $return;
    }
}

?>