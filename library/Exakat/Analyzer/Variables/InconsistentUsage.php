<?php declare(strict_types = 1);
/*
 * Copyright 2012-2019 Damien Seguy Ð Exakat SAS <contact(at)exakat.io>
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

class InconsistentUsage extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/CreateDefaultValues',
                    );
    }

    public function analyze() : void {
        $this->atomIs('Variabledefinition')
             ->raw(<<<'GREMLIN'
where(
__.sideEffect{ s = [:];}
  .out('DEFINITION').sideEffect{ s[it.get().label()] = 1;}.fold()
)
.filter{s.size() > 1}

GREMLIN
)
->outIs('DEFINITION');
        $this->prepareQuery();

        // Not taking into account absence of default on purpose.
        // improve with return typehint of functions
        $this->atomIs('Propertydefinition')
             ->filter(
                $this->side()
                     ->initVariable('s', "['class':0, 'array':0, 'variable':0]")
                     ->filter(
                        $this->side()
                             ->outIs('DEFAULT')
                             ->raw(<<<'GREMLIN'
sideEffect{
        if (it.get().label() == "Null")            { s["class"]++; }
   else if (it.get().label() == "Arrayliteral")    { s["array"]++; }
   else if (it.get().label() == "New")             { s["class"]++; }
   else if (it.get().label() == "Clone")           { s["class"]++; }
   else if (it.get().label() == "Void")            { /* Nothing */ }
   else                                            { s["variable"]++; }
    }.fold()
GREMLIN
)
                     )
                     ->outIs('DEFINITION')
                     ->not(
                        $this->side()
                             ->inIs('LEFT')
                             ->atomIs('Assignation')
                             ->outIs('RIGHT')
                             ->atomIs(array('New', 'Clone'))
                     )
                     ->raw(<<<'GREMLIN'
inE().not(hasLabel("ANALYZED", "DEFINITION", "RETURN")).sideEffect{
          if (it.get().label() == "OBJECT")   { s["class"]++; }
     else if (it.get().label() == "CLASS")    { s["class"]++; }
     else if (it.get().label() == "CLONE")    { s["class"]++; }
     else if (it.get().label() == "NEW")      { s["class"]++; }
     else if (it.get().label() == "APPEND")   { s["array"]++; }
     else if (it.get().label() == "VARIABLE") { s["array"]++; }
     else { s["variable"]++; };
     }.fold()
GREMLIN
                )
             )
             ->raw('filter{s.findAll{a,b -> b != 0}.size() > 1}');
        $this->prepareQuery();
    }
}

?>
