<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class NoDirectUsage extends Analyzer {
    public function analyze() {
        $functions = $this->loadIni('NoDirectUsage.ini', 'functions');
        $functionsFullNsPath = makeFullNsPath($functions);
        
        // foreach(glob() as $x) {}
        $this->atomIs('Foreach')
             ->outIs('SOURCE')
             ->functioncallIs($functionsFullNsPath)
             ->back('first');
        $this->prepareQuery();

        // Direct call with a function without check
        $this->atomFunctionIs($functionsFullNsPath)
             ->hasIn('ARGUMENT');
        $this->prepareQuery();

        // Direct usage in an operation +, *, **
        $this->atomFunctionIs($functionsFullNsPath)
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs(array('Addition', 'Multiplication', 'Power'));
        $this->prepareQuery();

    }
}

?>
