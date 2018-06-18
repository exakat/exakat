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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class AccessProtected extends Analyzer {
    public function analyze() {
        // class::method()
        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->outIs('CLASS')
             ->codeIsNot(array('parent', 'static', 'self'))
             ->classDefinition()
             ->outIs('METHOD')
             ->atomIs('Method')
             ->is('visibility', 'protected')
             ->outIs('NAME')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();

        // parent::$property
        // static or self ::$property

        // class::$property
        $this->atomIs('Staticproperty')
             ->outIs('MEMBER')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->outIs('CLASS')
             ->atomIsNot(self::$RELATIVE_CLASS)
             ->classDefinition()
             ->outIs('PPP')
             ->atomIs('Ppp')
             ->is('visibility', 'protected')
             ->outIs('PPP')
             ->outIsIE('LEFT')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();
        
        // Non-static methods/properties : ??? Don't know how to do that yet
    }
}

?>
