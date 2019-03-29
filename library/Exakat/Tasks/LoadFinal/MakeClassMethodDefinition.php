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


namespace Exakat\Tasks\LoadFinal;

use Exakat\Analyzer\Analyzer;
use Exakat\Query\Query;

class MakeClassMethodDefinition extends LoadFinal {
    public function run() {
        // Warning : no support for overwritten methods : ALL methods are linked

        // Create link between static Class method and its definition
        // This works outside a class too, for static.
        $query = $this->newQuery('MakeClassMethodDefinition static');
        $query->atomIs('Staticmethodcall', Analyzer::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('CLASS')
              ->atomIs(array('Identifier', 'Nsname', 'Self', 'Static', 'Parent'), Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullnspath', 'classe')
              ->inIs('DEFINITION')
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->is('static', true)
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count1 = $result->toInt();

        // Create link between Class method and definition
        // This works only for $this
        $query = $this->newQuery('MakeClassMethodDefinition method');
        $query->atomIs('Methodcall', Analyzer::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('OBJECT')
              ->atomIs('This', Analyzer::WITHOUT_CONSTANTS)
              ->inIs('OBJECT')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'name')
              ->back('first')
              ->goToInstruction(array('Class', 'Classanonymous', 'Trait'))
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->raw(<<<GREMLIN
where(
    __.sideEffect{aliases = [:]; insteadofs = [:]; }
      .out("USE").out("BLOCK").out("EXPRESSION")
      .sideEffect{
        if (it.get().label() == "Insteadof") {
            method = it.get().vertices(OUT, "NAME").next().vertices(OUT, "METHOD").next().property("lccode").value();
            theTrait = it.get().vertices(OUT, "INSTEADOF").next().property("fullnspath").value();
            if (insteadofs[method] == null) {
                insteadofs[method] = [theTrait];
            } else {
                insteadofs[method].add(theTrait);
            }
        }

        if (it.get().label() == "As") {
            method = it.get().vertices(OUT, "NAME").next().property("lccode").value();
            alias = it.get().vertices(OUT, "AS").next().property("lccode").value();
            aliases[alias] = method;
        }
      }
      .fold()
    )
.sideEffect{ if (aliases[name] != null) { name = aliases[name]; } }
GREMLIN
, array(), array())
              ->goToAllTraits(Analyzer::INCLUDE_SELF)
              ->raw(<<<GREMLIN
filter{ insteadofs[name] == null || !(it.get().value('fullnspath') in insteadofs[name]); }
GREMLIN
,array(), array())
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();

        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count2 = $result->toInt();
        
        $count = $count1 + $count2;
        display("Create $count link between \$this->methodcall() and definition");

        // Create link between constructor and new call
        $query = $this->newQuery('MakeClassMethodDefinition new');
        $query->atomIs('New', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('NEW')
              ->atomIs('Newcall', Analyzer::WITHOUT_CONSTANTS)
              ->has('fullnspath')
              ->inIs('DEFINITION')
              ->outIs('MAGICMETHOD')
              ->codeIs('__construct', Analyzer::TRANSLATE, Analyzer::CASE_INSENSITIVE)
              ->addETo('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());

        display('Create '.($result->toInt()).' link between new class and definition');

        // Create link between __clone and clone
        // parenthesis, typehint, local new, 
        $query = $this->newQuery('MakeClassMethodDefinition clone');
        $query->atomIs('Clone', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('CLONE')
              ->inIs('DEFINITION')
              ->inIs('NAME')
              ->outIs('TYPEHINT')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Classanonymous', 'Trait'), Analyzer::WITHOUT_CONSTANTS)
              ->outIs('MAGICMETHOD')
              ->codeIs('__clone', Analyzer::TRANSLATE, Analyzer::CASE_INSENSITIVE)
              ->addETo('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());

        display('Create '.($result->toInt()).' link between clone and magic method');

        $this->log->log(__METHOD__);
    }
}

?>
