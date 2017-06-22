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

class VariableUsedOnceByContext extends Analyzer {
    
    public function dependsOn() {
        return array('Variables/Variablenames',
                     'Variables/InterfaceArguments');
    }
    
    public function analyze() {
        $query = <<<GREMLIN
g.V().hasLabel("Variable", "Variablearray", "Variableobject")
     .not(has("code", "\\\$this"))
     .where( __.in("MEMBER").count().is(eq(0)) )
               .where( repeat( __.in({$this->linksDown}))
                            .until(hasLabel("File")).emit(hasLabel("Function")).hasLabel("Function")
                            .count().is(eq(0))
                     ).groupCount("m").by("code").cap("m")
                      .toList().get(0).findAll{ a,b -> b == 1}.keySet()
GREMLIN;
        $variables = $this->query($query);

        $this->atomIs(self::$VARIABLES_ALL)
             ->hasNoIn(array('PPP'))
             ->raw('not( where( __.in("LEFT").in("PPP") ) )')
             ->hasNoFunction()
             ->codeIs($variables);
        $this->prepareQuery();

        $this->atomIs(array('Function', 'Closure'))
             ->raw('where( __
                   .sideEffect{counts = [:]}
                             .repeat( out('.$this->linksDown.').not( where( __.hasLabel("Function", "Closure") ) ) )
                             .emit( hasLabel("Variable", "Variablearray", "Variableobject").not(has("code", "\\$this")) ).times('.self::MAX_LOOPING.')
                             .hasLabel("Variable", "Variablearray", "Variableobject").not(has("code", "\\$this"))
                             .where( __.in("MEMBER").count().is(eq(0)) )
                             .sideEffect{ k = it.get().value("code"); 
                                         if (counts[k] == null) {
                                            counts[k] = 1;
                                         } else {
                                            counts[k]++;
                                         }
                              }.fold()
                          )
                   .sideEffect{ names = counts.findAll{ a,b -> b == 1}.keySet() }
                   .repeat( out('.$this->linksDown.').not( where( __.hasLabel("Function", "Closure") ) )  )
                   .emit( hasLabel("Variable", "Variablearray", "Variableobject").not(has("code", "\\$this")) ).times('.self::MAX_LOOPING.')
                   .filter{ it.get().value("code") in names }');
        $this->prepareQuery();
    }
}

?>
