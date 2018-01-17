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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class UsedPrivateMethod extends Analyzer {

    public function analyze() {
        // method used in a static methodcall \a\b::b()
        $this->atomIs('Class')
             ->savePropertyAs('fullnspath', 'classname')
             ->outIs(array('MAGICMETHOD', 'METHOD'))
             ->atomIs(array('Method', 'Magicmethod'))
             ->_as('method')
             ->hasOut('PRIVATE')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->outIs('METHOD')
             ->atomInside('Staticmethodcall')
             ->outIs('CLASS')
             ->tokenIs(self::$STATICCALL_TOKEN)
             ->atomIsNot(array('Static', 'Self'))
             ->samePropertyAs('fullnspath', 'classname')
             ->inIs('CLASS')
             ->outIs('METHOD')
             ->samePropertyAs('code', 'name')
             ->back('method');
        $this->prepareQuery();

        // method used in a static methodcall static::b() or self
        $this->atomIs(array('Method', 'Magicmethod'))
             ->_as('method')
             ->hasOut('PRIVATE')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->inIs(array('METHOD', 'MAGICMETHOD'))
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->atomInside('Staticmethodcall')
             ->outIs('CLASS')
             ->tokenIs(self::$STATICCALL_TOKEN)
             ->atomIs(array('Static', 'Self'))
             ->inIs('CLASS')
             ->outIs('METHOD')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();

        // method used in a normal methodcall with $this $this->b()
        $this->atomIs(array('Method', 'Magicmethod'))
             ->hasOut('PRIVATE')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->inIs(array('METHOD', 'MAGICMETHOD'))
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->atomInside('Methodcall')
             ->outIs('OBJECT')
             ->atomIs('This')
             ->inIs('OBJECT')
             ->outIs('METHOD')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();

        // method used in a new (constructor)
        $this->atomIs('Class')
             ->savePropertyAs('fullnspath', 'fnp')
             ->outIs('MAGICMETHOD')
             ->atomIs('Magicmethod')
             ->hasOut('PRIVATE')
             ->_as('method')
             ->outIs('NAME')
             ->codeIs('__construct')
             ->back('first')
             ->outIs('METHOD')
             ->atomInside('New')
             ->outIs('NEW')
             ->tokenIs(self::$STATICCALL_TOKEN)
             ->samePropertyAs('fullnspath', 'fnp')
             ->back('method');
        $this->prepareQuery();

        // __destruct is considered automatically checked
        $this->atomIs('Class')
             ->outIs('MAGICMETHOD')
             ->atomIs('Magicmethod')
             ->hasOut('PRIVATE')
             ->outIs('NAME')
             ->codeIs('__destruct')
             ->inIs('NAME');
        $this->prepareQuery();
    }
}

?>
