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
namespace Exakat\Analyzer\Variables;

use Exakat\Analyzer\Analyzer;

class CloseNaming extends Analyzer {
    
    public function analyze() {
        $closeVariables = $this->dictCode->closeVariables();

        if (!empty($closeVariables)) {
            $this->atomIs(self::$FUNCTIONS_ALL)
                 ->collectVariables('variables', 'code')
                 ->raw('sideEffect{ 
    variables = variables.unique().sort();
    found = variables.intersect(***); 
}', $closeVariables)
                ->filter(' found.size() > 1; ')
                ->atomInsideNoDefinition('Variable')
                ->filter('it.get().value("code") in found');
            $this->prepareQuery();
        }

        // Identical, except for case
        $query = <<<GREMLIN
g.V().hasLabel("Variable", "Variablearray", "Variableobject")
     .values("fullcode")
     .unique()
GREMLIN;
        $doubles = $this->query($query)->toArray();
        $uniques = array();
        foreach($doubles as $u) {
            $v = mb_strtolower($u);
            if (isset($uniques[$v])) {
                $uniques[$v][] = $u;
            } else {
                $uniques[$v] = [$u];
            }
        }
        
        $uniques = array_filter($uniques, function ($x) { return count($x) > 1; });
        if (!empty($uniques)) {
            $doubles = array_merge(...array_values($uniques));
    
            $this->atomIs(array('Variable', 'Variablearray', 'Variableobject'))
                 ->codeIs($doubles);
            $this->prepareQuery();
        }

        // Identical, except for _ in the name
        $doubles = $this->dictCode->underscoreCloseVariables();
        
        if (!empty($doubles)) {
            $this->atomIs(array("Variable", "Variablearray", "Variableobject"))
                 ->codeIs($doubles, self::NO_TRANSLATE);
            $this->prepareQuery();
        }

        // Identical, except for numbers
        $doubles = $this->dictCode->numberCloseVariables();

        if (!empty($doubles)) {
            $this->atomIs(array("Variable", "Variablearray", "Variableobject"))
                 ->codeIs($doubles, self::NO_TRANSLATE);
            $this->prepareQuery();
        }
    }
}

?>
