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
        $query = $this->newQuery('MakeClassMethodDefinition direct');
        $query->atomIs('Staticmethodcall', Analyzer::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('CLASS')
              ->atomIs(array('Identifier', 'Nsname', 'Self', 'Static'), Analyzer::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Classanonymous', 'Trait'), Analyzer::WITHOUT_CONSTANTS)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
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
        $query = $this->newQuery('MakeClassMethodDefinition with traits');
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
              
              ->outIs('USE')
              ->outIs('BLOCK')
              ->outIs('EXPRESSION')
              ->atomIs(array('As', 'Insteadof'), Analyzer::WITHOUT_CONSTANTS)
              ->outIs(array('AS', 'INSTEADOF'))
              ->samePropertyAs('lccode', 'name', Analyzer::CASE_INSENSITIVE)
              ->inIs(array('AS', 'INSTEADOF'))
              ->outIs('NAME')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'realname')
              ->inIs('METHOD')
              ->outIs('CLASS')
              ->inIs('DEFINITION')
              ->atomIs('Trait', Analyzer::WITHOUT_CONSTANTS)
              ->GoToAllParentsTraits(Analyzer::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'realname', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countT = $result->toInt();
        display("$countT with traits");

        // Create link between Class method and definition
        // This works only for $this
        $query = $this->newQuery('MakeClassMethodDefinition with traits');
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
              ->_as('theClass')
              
              ->outIs('USE')
              ->outIs('BLOCK')
              ->outIs('EXPRESSION')
              ->atomIs('As', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('AS')
              ->samePropertyAs('lccode', 'name', Analyzer::CASE_INSENSITIVE)
              ->inIs('AS')
              ->outIs('NAME')
              ->savePropertyAs('lccode', 'realname')
              ->back('theClass')

              ->outIs('USE')
              ->outIs('USE')
              ->inIs('DEFINITION')
              ->atomIs('Trait', Analyzer::WITHOUT_CONSTANTS)
              ->GoToAllParentsTraits(Analyzer::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'realname', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countT = $result->toInt();
        display("$countT with traits");

        // Create link between Class method and definition
        // This works only for $this
        $query = $this->newQuery('MakeClassMethodDefinition with traits');
        $query->atomIs('Staticmethodcall', Analyzer::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('CLASS')
              ->atomIs(array('Identifier', 'Nsname', 'Self', 'Static'), Analyzer::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              
              ->outIs('USE')
              ->outIs('BLOCK')
              ->outIs('EXPRESSION')
              ->atomIs(array('As', 'Insteadof'), Analyzer::WITHOUT_CONSTANTS)
              ->outIs(array('AS', 'INSTEADOF'))
              ->samePropertyAs('lccode', 'name', Analyzer::CASE_INSENSITIVE)
              ->inIs(array('AS', 'INSTEADOF'))
              ->outIs('NAME')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'realname')
              ->inIs('METHOD')
              ->outIs('CLASS')
              ->inIs('DEFINITION')
              ->atomIs('Trait', Analyzer::WITHOUT_CONSTANTS)
              ->GoToAllParentsTraits(Analyzer::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'realname', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countT = $result->toInt();
        display("$countT with static traits");

        // Create link between Class method and definition
        // This works only for $this
        $query = $this->newQuery('MakeClassMethodDefinition with traits and as');
        $query->atomIs('Staticmethodcall', Analyzer::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('CLASS')
              ->atomIs(array('Identifier', 'Nsname', 'Self', 'Static'), Analyzer::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->_as('theClass')

              ->outIs('USE')
              ->outIs('BLOCK')
              ->outIs('EXPRESSION')
              ->atomIs('As', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('AS')
              ->samePropertyAs('lccode', 'name', Analyzer::CASE_INSENSITIVE)
              ->inIs('AS')
              ->outIs('NAME')
              ->savePropertyAs('lccode', 'realname')
              ->back('theClass')

              ->outIs('USE')
              ->outIs('USE')
              ->inIs('DEFINITION')
              ->atomIs('Trait', Analyzer::WITHOUT_CONSTANTS)
              ->GoToAllParentsTraits(Analyzer::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))

              ->outIs('NAME')
              ->samePropertyAs('lccode', 'realname', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countT = $result->toInt();
        display("$countT with static traits");

        // Create link between static Class method and its definition
        // This works outside a class too, for static.
        $query = $this->newQuery('MakeClassMethodDefinition parents');
        $query->atomIs('Staticmethodcall', Analyzer::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('CLASS')
              ->atomIs(array('Identifier', 'Nsname', 'Self', 'Static'), Analyzer::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Classanonymous'), Analyzer::WITHOUT_CONSTANTS)
              ->goToAllParents(Analyzer::EXCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->isNot('visibility', 'private')
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count2 = $result->toInt();

        // Create link between static Class method and its definition
        // This works outside a class too, for static.
        $query = $this->newQuery('MakeClassMethodDefinition parent');
        $query->atomIs('Staticmethodcall', Analyzer::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('CLASS')
              ->atomIs('Parent', Analyzer::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->GoToAllParentsTraits(Analyzer::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->isNot('visibility', 'private')
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count3 = $result->toInt();

        $count = $count1 + $count2 + $count3;
        display("Create $count link between \$this->methodcall() and definition");

        // Create link between constructor and new call
        $query = $this->newQuery('MakeClassMethodDefinition new');
        $query->atomIs('New', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('NEW')
              ->atomIs(array('Newcall', 'Self', 'Parent'), Analyzer::WITHOUT_CONSTANTS)
              ->has('fullnspath')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Clasanonymous'), Analyzer::WITHOUT_CONSTANTS)
              ->outIs('MAGICMETHOD')
              ->outIs('NAME')
              ->codeIs('__construct', Analyzer::TRANSLATE, Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countDirect = $result->toInt();

        $query = $this->newQuery('MakeClassMethodDefinition new');
        $query->atomIs('New', Analyzer::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('NEW')
              ->atomIs(array('Newcall', 'Self', 'Parent'), Analyzer::WITHOUT_CONSTANTS)
              ->has('fullnspath')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Clasanonymous'), Analyzer::WITHOUT_CONSTANTS)
              ->outIs('NAME')
              ->savePropertyAs('lccode', 'name')
              ->inIs('NAME')
              ->outIs('METHOD')
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countOld = $result->toInt();

        $query = $this->newQuery('MakeClassMethodDefinition new');
        $query->atomIs('New', Analyzer::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('NEW')
              ->atomIs(array('Newcall', 'Self', 'Parent'), Analyzer::WITHOUT_CONSTANTS)
              ->has('fullnspath')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Clasanonymous'), Analyzer::WITHOUT_CONSTANTS)
              ->outIs('EXTENDS')
              ->inIs('DEFINITION')
              ->raw(<<<'GREMLIN'
until( __.out("MAGICMETHOD").out("NAME").has("fullcode", "__construct")).repeat( __.out("EXTENDS").in("DEFINITION"))
GREMLIN
,array(), array())
              ->outIs('MAGICMETHOD')
              ->codeIs('__construct', Analyzer::TRANSLATE, Analyzer::CASE_INSENSITIVE)
              ->addETo('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countParents = $result->toInt();

        $count = $countParents + $countOld + $countDirect;
        display("Create $count link between new class and definition");

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

        display('Create ' . ($result->toInt()) . ' link between clone and magic method');

        $this->log->log(__METHOD__);
    }
}

?>
