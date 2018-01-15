<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;
use Exakat\Tokenizer\Token;

class ImplicitGlobal extends Analyzer {
    public function analyze() {
        $query = <<<GREMLIN
g.V().hasLabel("Global").out("GLOBAL")
     .has("token", "T_VARIABLE")
     .not( where( repeat(__.in({$this->linksDown})).emit().until(hasLabel("File")).hasLabel("Function", "Method", "Closure", "Magicmethod") ) )
     .values("code").unique()
GREMLIN;
        $globalGlobal = $this->query($query)->toArray();

        // can't bail out here : if $globalGlobal is empty, no global was declared outside functions.
        // This is still useful

        $superglobals = $this->loadIni('php_superglobals.ini', 'superglobal');
        $superglobals = $this->dictCode->translate($superglobals);
        $explicitGlobal = array_merge($superglobals, $globalGlobal);
        $explicitGlobal = array_unique($explicitGlobal);

        $this->atomIs('Global')
             ->hasFunction()
             ->outIs('GLOBAL')
             ->tokenIs('T_VARIABLE')
             ->codeIsNot($explicitGlobal, self::NO_TRANSLATE);
        $this->prepareQuery();
    }
}

?>
