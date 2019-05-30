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

class SetClassPropertyRemoteDefinition extends LoadFinal {
    public function run() {
        // For properties in traits
        $query = $this->newQuery('SetClassPropertyRemoteDefinition property');
        $query->atomIs('Trait', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('PPP')
              ->atomIs('Ppp', Analyzer::WITHOUT_CONSTANTS)
              ->_as('source')
              ->back('first')
              ->outIs('DEFINITION')
              ->inIs('USE')
              ->inIs('USE')
              ->atomIs('Class', Analyzer::WITHOUT_CONSTANTS)
              ->_as('class')
              ->raw(<<<GREMLIN
where(
    __.addV()
      .property(label, select('source').label()).as('clone').
      sideEffect(
        select('source').properties().as('p').
        select('clone').
          property(select('p').key(), select('p').value()).
          property('virtual', true)
      )
      .addE("PPP").from("class")
    
    .select('source').where( 
        __.out('TYPEHINT').as('sourcetypehint')
          .addV()
          .property(label, select('sourcetypehint').label()).as('clonetypehint')
          .sideEffect(
            select('sourcetypehint').properties().as('p')
            .select('clonetypehint')
              .property(select('p').key(), select('p').value())
              .property('virtual', true)
           )
          .addE("TYPEHINT").from("clone")
    
    .select('source').where( 
        __.out('PPP').as('sourceppp')
          .addV().
            property(label, select('sourceppp').label()).as('cloneppp').
          sideEffect(
            select('sourceppp').properties().as('p').
            select('cloneppp').
              property(select('p').key(), select('p').value()).
              property('virtual', true)
          )
          .addE("PPP").from("clone")
      
          .select('sourceppp').where( 
            __.out('DEFAULT').as('sourcedefault')
              .addV()
              .property(label, select('sourcedefault').label()).as('clonedefault')
              .sideEffect(
                select('sourcedefault').properties().as('p').
                select('clonedefault')
                    .property(select('p').key(), select('p').value())
                    .property('virtual', true)
                )
              .addE("DEFAULT").from("cloneppp")
              .fold()
            )
    
        )
      .fold()
    ).fold()
)
GREMLIN
,array(), array())
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countReports = $result->toInt();
        display("Added $countReports traits property to class definitions");

        // For properties in classes
        $query = $this->newQuery('SetClassPropertyRemoteDefinition property');
        $query->atomIs('Class', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('PPP')
              ->isNot('visibility', 'private')
              ->atomIs('Ppp', Analyzer::WITHOUT_CONSTANTS)
              ->_as('source')
              ->back('first')
              ->outIs('DEFINITION')
              ->inIs('USE')
              ->inIs('USE')
              ->atomIs('Class', Analyzer::WITHOUT_CONSTANTS)
              ->_as('class')
              ->raw(<<<GREMLIN
where(
    __.addV()
      .property(label, select('source').label()).as('clone').
      sideEffect(
        select('source').properties().as('p').
        select('clone').
          property(select('p').key(), select('p').value()).
          property('virtual', true)
      )
      .addE("PPP").from("class")
    
    .select('source').where( 
        __.out('TYPEHINT').as('sourcetypehint')
          .addV()
          .property(label, select('sourcetypehint').label()).as('clonetypehint')
          .sideEffect(
            select('sourcetypehint').properties().as('p')
            .select('clonetypehint')
              .property(select('p').key(), select('p').value())
              .property('virtual', true)
           )
          .addE("TYPEHINT").from("clone")
    
    .select('source').where( 
        __.out('PPP').as('sourceppp')
          .addV().
            property(label, select('sourceppp').label()).as('cloneppp').
          sideEffect(
            select('sourceppp').properties().as('p').
            select('cloneppp').
              property(select('p').key(), select('p').value()).
              property('virtual', true)
          )
          .addE("PPP").from("clone")
      
          .select('sourceppp').where( 
            __.out('DEFAULT').as('sourcedefault')
              .addV()
              .property(label, select('sourcedefault').label()).as('clonedefault')
              .sideEffect(
                select('sourcedefault').properties().as('p').
                select('clonedefault')
                    .property(select('p').key(), select('p').value())
                    .property('virtual', true)
                )
              .addE("DEFAULT").from("cloneppp")
              .fold()
            )
    
        )
      .fold()
    ).fold()
)
GREMLIN
,array(), array())
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countReports = $result->toInt();
        display("Added $countReports traits property to class definitions");

        // For static properties calls, in traits
        $query = $this->newQuery('SetClassPropertyRemoteDefinition property');
        $query->atomIs('Staticproperty', Analyzer::WITHOUT_CONSTANTS)
              ->_as('property')
              ->hasNoIn('DEFINITION')
              ->outIs('MEMBER')
              ->atomIs('Staticpropertyname', Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('code', 'name')
              ->inIs('MEMBER')
              ->outIs('CLASS')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Classanonymous', 'Trait'), Analyzer::WITHOUT_CONSTANTS)
              ->GoToAllParentsTraits(Analyzer::INCLUDE_SELF)
              ->outIs('PPP')
              ->outIs('PPP')
              ->samePropertyAs('code', 'name', Analyzer::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'property')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count = $result->toInt();

        // For normal method calls, in traits
        $query = $this->newQuery('SetClassPropertyRemoteDefinition member');
        $query->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
              ->_as('property')
              ->hasNoIn('DEFINITION')
              ->outIs('MEMBER')
              ->savePropertyAs('code', 'name')
              ->inIs('MEMBER')
              ->outIs('OBJECT')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Classanonymous', 'Trait'), Analyzer::WITHOUT_CONSTANTS)
//              ->goToAllParentsTraits(Analyzer::INCLUDE_SELF)
              ->outIs('PPP')
              ->outIs('PPP')
              ->atomIs('Propertydefinition', Analyzer::WITHOUT_CONSTANTS)
              ->samePropertyAs('propertyname', 'name', Analyzer::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'property')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count += $result->toInt();

        display("Set $count property remote definitions");
    }
}

?>
