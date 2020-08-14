<?php declare(strict_types = 1);
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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class DynamicMethodCall extends Analyzer {
    public function analyze(): void {
        // $object->$name()
        // $class::$name()
        $this->atomIs(array('Methodcall', 'Staticmethodcall'))
             ->outIs('METHOD')
             ->outIs('NAME')
             ->tokenIs(array('T_VARIABLE', 'T_DOLLAR', 'T_DOLLAR_OPEN_CURLY_BRACES'))
             ->back('first');
        $this->prepareQuery();

        // call_user_func(array($a, $b))
        $this->atomFunctionIs(array('\call_user_func', '\call_user_func_array'))
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('Arrayliteral', self::WITH_VARIABLES)
             ->is('count', 2)
             ->outWithRank('ARGUMENT', 1)
             ->atomIs(self::CONTAINERS)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
