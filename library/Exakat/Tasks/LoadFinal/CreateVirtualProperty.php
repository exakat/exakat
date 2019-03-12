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
        $query = new Query(0, $this->config->project, 'CreateVirtualProperty VirtualProperty', null, $this->datastore);
        $query->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
              ->_as('member')
              ->savePropertyAs('fullcode', 'f')
              ->hasNoIn('DEFINITION')
              ->dedup('fullcode')
              
              ->goToClass()
              ->_as('laClasse')

              ->raw('addV("Virtualproperty").sideEffect{ it.get().property("code", f);
                                                         it.get().property("fullcode", f); 
                                                         it.get().property("line", -1); 
                                                       }.addE("PPP").from("laClasse")', array(), array())

              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count = $result->toInt();

        display("Created $count virtual properties");

        $query = new Query(0, $this->config->project, 'CreateVirtualProperty definitions', null, $this->datastore);
        $query->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
              ->_as('member')
              ->savePropertyAs('fullcode', 'f')
              ->hasNoIn('DEFINITION')
              
              ->goToClass()
              ->outIs('PPP')
              ->atomIs('Virtualproperty', Analyzer::WITHOUT_CONSTANTS)
              ->samePropertyAs('fullcode', 'f', Analyzer::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'member')

              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count = $result->toInt();

        display("Created $count definitions to virtual properties");
    }
}

?>
