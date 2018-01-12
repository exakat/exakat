<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
            $this->atomIs(array('Function', 'Method', 'Closure'))
                 ->raw('where( __.sideEffect{ variables = []}.out("BLOCK").repeat( out('.$this->linksDown.') ).emit( hasLabel("Variable")).times('.self::MAX_LOOPING.').sideEffect{ variables.push(it.get().value("code")); }.fold() )')
                 ->raw('sideEffect{ 
    variables = variables.unique().sort();
    found = variables.intersect(***); 
}', $closeVariables)
                ->filter(' found.size() > 1; ')
                ->atomInside('Variable')
                ->raw('filter{ it.get().value("code") in found}');
            $this->prepareQuery();
        }

        // Identical, except for case
        $doubles = $this->dictCode->caseCloseVariables();

        if (!empty($doubles)) {
            $this->atomIs(array("Variable", "Variablearray", "Variableobject"))
                 ->codeIs($doubles, self::NO_TRANSLATE);
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
