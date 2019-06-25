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

class MakeFunctionDefinition extends LoadFinal {
    public function run() {

        // Create link between Class constant and definition
        $query = $this->newQuery('MakeFunctionDefinition collect functions');
        $query->atomIs('Function', Analyzer::WITHOUT_CONSTANTS)
              ->_as('first_id')
              ->select(array('first'       => 'fullnspath',
                             'first_id'    => 'id'
                             ));
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $list = $result->toArray();
        $definitions = array();
        foreach($list as $l) {
            array_collect_by($definitions, $l['first'], $l['first_id']);
        }

        $query = $this->newQuery('MakeFunctionDefinition collect functioncalls');
        $query->atomIs('Functioncall', Analyzer::WITHOUT_CONSTANTS)
              ->has('fullnspath')
              ->_as('first_id2')
              ->select(array('first'       => 'fullnspath',
                             'first_id2'    => 'id'
                             ));
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $list = $result->toArray();
        $calls = array();
        foreach($list as $l) {
            array_collect_by($calls, $l['first'], $l['first_id2']);
        }

        $countD = 0;
        foreach($definitions as $fqn => $defs) {
            if (empty($calls[$fqn])) { 
                continue; 
            }

            $id_defs = implode(', ', $defs);
            $id_calls = implode(', ', $calls[$fqn]);
            $query = "g.V($id_calls).addE(\"DEFINITION\").from(g.V($id_defs))";
            $result = $this->gremlin->query($query, array());
            $countD += $result->toInt();
        }

        display("Create $countD links between Functions and calls");
    }
}

?>
