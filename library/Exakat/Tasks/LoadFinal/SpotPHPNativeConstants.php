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

class SpotPHPNativeConstants extends LoadFinal {
    private $PHPconstants = array();

    public function run() {
        $constants = call_user_func_array('array_merge', $this->PHPconstants);
        $constants = array_filter($constants, function ($x) { return strpos($x, '\\') === false;});
        $constantsPHP = array_values($constants);

        $query = $this->newQuery('SpotPHPNativeConstants');
        $query->atomIs('Identifier', Analyzer::WITHOUT_CONSTANTS)
              ->has('fullnspath')
              ->values('code')
              ->unique();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());

        if (empty($constantsPHP)) {
            display('No PHP Constants');
            return;
        } 
        
        $query = $this->newQuery('SpotPHPNativeConstants');
        $query->atomIs('Identifier', Analyzer::WITHOUT_CONSTANTS )
              ->has('fullnspath')
              ->hasNoIn('DEFINITION')
              ->codeIs($constantsPHP, Analyzer::TRANSLATE, Analyzer::CASE_SENSITIVE)
              ->raw(<<<'GREMLIN'
sideEffect{
   tokens = it.get().value("fullnspath").tokenize('\\');
   fullnspath = "\\" + tokens.last();
   it.get().property("fullnspath", fullnspath); 
}
GREMLIN
,array(), array())
                ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        display($result->toInt().' SpotPHPNativeConstants');
    }
    
    function setPHPconstants(array $PHPconstants = array()) {
        $this->PHPconstants = $PHPconstants;
    }
}

?>
