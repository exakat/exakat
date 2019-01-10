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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class ComparedButNotAssignedStrings extends Analyzer {
    public function analyze() {
        $compareCode = $this->dictCode->translate(array('==', '===', '!=', '!=='));
        $compareCodeList = implode(',', $compareCode);
        
        $query = <<<GREMLIN
g.V().hasLabel('Comparison').has("code", within($compareCodeList)).out('LEFT', 'RIGHT').hasLabel('String').not(where(__.out('CONCAT'))).not(has("noDelimiter", "")).values('noDelimiter').unique()
GREMLIN;
        $comparedStrings = $this->query($query)->toArray();

        $query = <<<GREMLIN
g.V().hasLabel('Assignation').out('RIGHT').hasLabel('String').not(where(__.out('CONCAT'))).not(has("noDelimiter", "")).values('noDelimiter').unique()
GREMLIN;
        $assignedStrings = $this->query($query)->toArray();
        
        $unassigned = array_diff($comparedStrings, $assignedStrings);
        
        $this->atomIs('Comparison')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('String')
             ->hasNoOut('CONCAT')
             ->noDelimiterIs($unassigned);
        $this->prepareQuery();
    }
}

?>
