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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class UnusedGlobal extends Analyzer {
    public function analyze() {
        // global in a function
        $this->atomIs('Globaldefinition')
             ->_as('result')
             ->savePropertyAs('code', 'theGlobal')
             ->goToFunction()
             // Not used as a variable
             ->raw('not( where( __.repeat( __.out('.$this->linksDown.') ).emit(hasLabel("Variable", "Variablearray", "Variableobject"))
                             .times('.self::MAX_LOOPING.')
                             .hasLabel("Variable", "Variablearray", "Variableobject")
                             .not( where( __.in("GLOBAL") ) ).filter{ it.get().value("code") == theGlobal} ) )')
             ->back('result');
        $this->prepareQuery();

        // global in the global space
        $max = self::MAX_LOOPING;
        $query = <<<GREMLIN
g.V().out("FILE").out("EXPRESSION").out("CODE").out("EXPRESSION").not(hasLabel("Global", "Function", "Trait", "Class", "Interface"))
                                .repeat( __.out($this->linksDown) ).emit(hasLabel("Variable", "Variablearray", "Variableobject"))
                                .times($max).not( where( __.in("GLOBAL") ) )
                                .values("code").unique();
GREMLIN;

        $globalVariables = $this->gremlin->query($query);
        
        $this->atomIs('Globaldefinition')
             ->_as('result')
             ->savePropertyAs('code', 'theGlobal')
             ->hasNoFunction()
             ->hasNoClass()
             ->hasNoInterface()
             ->hasNoTrait()
             // Not used as a variable
             ->codeIsNot($globalVariables->toArray(), self::NO_TRANSLATE)
             ->back('result');
        $this->prepareQuery();
    }
}

?>
