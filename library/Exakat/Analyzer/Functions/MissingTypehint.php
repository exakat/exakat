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

namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class MissingTypehint extends Analyzer {
    public function analyze() {
        // function foo($a) : void;
        $this->atomIs('Parameter')
             ->not(
                $this->side()
                     ->inIs('ARGUMENT')
                     ->outIs('NAME')
                     ->codeIs(array('__get', '__set'), self::TRANSLATE, self::CASE_INSENSITIVE)
              )
             ->outIs('TYPEHINT')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();

        // function foo(string $a) ;
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->not(
                $this->side()
                     ->outIs('NAME')
                     ->codeIs(array('__construct', '__get', '__set'), self::TRANSLATE, self::CASE_INSENSITIVE)
              )
             ->outIs('RETURNTYPE')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
