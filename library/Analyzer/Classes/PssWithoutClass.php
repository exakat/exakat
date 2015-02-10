<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Classes;

use Analyzer;

class PssWithoutClass extends Analyzer\Analyzer {
    public function analyze() {
        // new pss()
        $this->atomIs('New')
             ->outIs('NEW')
             ->code(array('parent', 'self', 'static'))
             ->fullnspath(array('parent', 'self', 'static'))
             ->back('first');
        $this->prepareQuery();

        // pss::$property
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->code(array('parent', 'self', 'static'))
             ->fullnspath(array('parent', 'self', 'static'))
             ->back('first');
        $this->prepareQuery();

        // pss::method
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->code(array('parent', 'self', 'static'))
             ->fullnspath(array('parent', 'self', 'static'))
             ->back('first');
        $this->prepareQuery();

        // pss::constant
        $this->atomIs('Staticconstant')
             ->outIs('CLASS')
             ->code(array('parent', 'self', 'static'))
             ->fullnspath(array('parent', 'self', 'static'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
