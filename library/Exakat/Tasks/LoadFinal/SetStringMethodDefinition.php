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

class SetStringMethodDefinition extends LoadFinal {
    public function run() {
        //$id, $project, $analyzer, $php
        $query = new Query(0, $this->config->project, 'SetClassPropertyDefinitionWithFluentInterface', null, $this->datastore);
        $query->atomIs('String', Analyzer::WITHOUT_CONSTANTS)
              ->hasIn('DEFINITION')
              ->regexIs('noDelimiter', '::')
              ->initVariable('name', '""')
              ->raw(<<<GREMLIN
filter{ 
    name = it.get().value("noDelimiter").split("::"); 
    if (name.length > 1) {
        name = name[1].toLowerCase();
    } else {
        name = false;
    }
    name != true;
}
GREMLIN
, array(), array())
              ->inIs('DEFINITION')
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->atomIs(array('Method', 'Magicmethod'), Analyzer::WITHOUT_CONSTANTS)
              ->outIs('NAME')
              ->samePropertyAs('fullcode', 'name', Analyzer::CASE_SENSITIVE)
              ->inIs('NAME')
              ->addEto('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count = $result->toInt(); 

        display("Set $count strings to Definition");
    }
}

?>
