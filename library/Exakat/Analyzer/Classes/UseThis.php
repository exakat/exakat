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

class UseThis extends Analyzer {
    public function analyze() {
        // Valid for both statics and normal
        // parent::
        $this->atomIs('Method')
             ->outIs('BLOCK')
             ->atomInsideNoAnonymous(array('Staticmethodcall', 'Staticproperty'))
             ->outIs('CLASS')
             ->atomIs('Parent')
             ->back('first');
        $this->prepareQuery();

        // self or parent are local.
        $this->atomIs('New')
             ->outIs('NEW')
             ->outIs('NAME')
             ->atomIs(array('Parent', 'Self'))
             ->back('first');
        $this->prepareQuery();

        // Case for normal methods
        $this->atomIs('Method')
             ->hasNoOut('STATIC')
             ->outIs('BLOCK')
             ->atomInsideNoAnonymous('This')
             ->back('first');
        $this->prepareQuery();

        // Case for statics methods
        $this->atomIs('Method')
             ->hasOut('STATIC')
             ->outIs('BLOCK')
             ->atomInsideNoAnonymous(array('Staticmethodcall', 'Staticproperty'))
             ->outIs('CLASS')
             ->tokenIs(self::$STATICCALL_TOKEN)
             ->atomIsNot('Parent')
             ->savePropertyAs('fullnspath', 'classe')
             ->goToClassTrait()
             ->samePropertyAs('fullnspath', 'classe')
             ->back('first');
        $this->prepareQuery();

    // static constant are excluded.
    }
}

?>
