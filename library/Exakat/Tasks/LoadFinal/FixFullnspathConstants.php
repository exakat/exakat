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

class FixFullnspathConstants extends LoadFinal {
    public function run() {
        $query = new Query(0, $this->config->project, 'fixFullnspathConstants', null, $this->datastore);
        $query->atomIs(array('Identifier', 'Nsname'), Analyzer::WITHOUT_CONSTANTS)
              ->has('fullnspath')
              ->_as('identifier')
              ->savePropertyAs('fullnspath', 'cc')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Trait', 'Interface', 'Constant', 'Defineconstant'), Analyzer::WITHOUT_CONSTANTS)
              ->raw(<<<GREMLIN
coalesce( __.out("ARGUMENT").has("rank", 0), 
          __.hasLabel("Constant").out('NAME'), 
          filter{ true; })
GREMLIN
, array(), array())
              ->savePropertyAs('fullnspath', 'actual')
              ->filter('actual != cc', array())
              ->back('identifier')
              ->setProperty('fullnspath', 'actual')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        
        display("Fixed Fullnspath for Constants");
    }
}

?>
