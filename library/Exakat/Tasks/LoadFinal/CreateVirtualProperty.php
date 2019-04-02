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

class CreateVirtualProperty extends LoadFinal {
    public function run() {
        $query = $this->newQuery('CreateVirtualProperty VirtualProperty');
        $query->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
              ->_as('member')
              ->hasNoIn('DEFINITION')
              ->dedup('fullcode')
              ->outIs('MEMBER')
              ->tokenIs('T_STRING')
              ->savePropertyAs('lccode', 'lower')
              ->savePropertyAs('code', 'ncode')
              ->savePropertyAs('fullcode', 'full')
              
              ->goToClass()
              ->not(
                $query->side()
                      ->goToAllParentsTraits(Analyzer::INCLUDE_SELF)
                      ->outIs('PPP')
                      ->isNot('visibility', 'private')
                      ->outIs('PPP')
                      ->atomIsNot('Virtualproperty', Analyzer::WITHOUT_CONSTANTS)
                      ->samePropertyAs('propertyname', 'ncode', Analyzer::CASE_SENSITIVE)
                      ->prepareSide()
              )
              ->_as('laClasse')

              ->raw(<<<GREMLIN
addV("Ppp").sideEffect{ it.get().property("code", 0);
                        it.get().property("lccode", 0); 
                        it.get().property("fullcode", '\$' + full); 
                        it.get().property("line", -1); 
                        it.get().property("count", 1); 
                        it.get().property("visibility", "none");
                       }.as('ppp').addE("PPP").from("laClasse").
addV("Virtualproperty").sideEffect{ it.get().property("code", 0);
                                    it.get().property("lccode", 0); 
                                    it.get().property("fullcode", '\$' + full); 
                                    it.get().property("propertyname", ncode); 
                                    it.get().property("line", -1); 
                                  }.addE("PPP").from("ppp")
GREMLIN
, array(), array())
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count = $result->toInt();

        display("Created $count virtual properties");

        $query = $this->newQuery('CreateVirtualProperty definitions');
        $query->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
              ->_as('member')
              ->hasNoIn('DEFINITION')
              ->outIs('MEMBER')
              ->savePropertyAs('lccode', 'name')
              
              ->goToClass()
              ->outIs('PPP')
              ->outIs('PPP')
              ->atomIs('Virtualproperty', Analyzer::WITHOUT_CONSTANTS)
              ->samePropertyAs('propertyname', 'name', Analyzer::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'member')

              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count = $result->toInt();

        display("Created $count definitions to virtual properties");
    }
}

?>
