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
             ->outIs('METHOD')
             ->atomIs('Method')
             ->_as('method')
             ->hasOut('PRIVATE')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->outIs('METHOD')
             ->atomInside('Staticmethodcall')
             ->outIs('CLASS')
             ->tokenIs(self::$STATICCALL_TOKEN)
             ->codeIsNot(array('static', 'self'))
             ->samePropertyAs('fullnspath', 'classname')
             ->inIs('CLASS')
             ->outIs('METHOD')
             ->samePropertyAs('code', 'name')
             ->back('method');
        $this->prepareQuery();

        // method used in a static methodcall static::b() or self
        $this->atomIs('Method')
             ->_as('method')
             ->hasOut('PRIVATE')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->inIs('METHOD')
             ->outIs('METHOD')
             ->atomInside('Staticmethodcall')
             ->outIs('CLASS')
             ->tokenIs(self::$STATICCALL_TOKEN)
             ->codeIs(array('static', 'self'))
             ->inIs('CLASS')
             ->outIs('METHOD')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();

        // method used in a normal methodcall with $this $this->b()
        $this->atomIs('Method')
             ->hasOut('PRIVATE')
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->inIs('METHOD')
             ->outIs('METHOD')
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
             ->outIs('METHOD')
             ->atomIs('Method')
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
             ->outIs('METHOD')
             ->atomIs('Method')
             ->hasOut('PRIVATE')
             ->outIs('NAME')
             ->codeIs('__destruct')
             ->inIs('NAME');
        $this->prepareQuery();
    }
}

?>
