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

class UsedPrivateMethod extends Analyzer {

    public function analyze() {
        // method used in a static methodcall \a\b::b()
        // method used in a static methodcall static::b() or self
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->has('fullnspath')
             ->savePropertyAs('fullnspath', 'classname')
             ->back('first')
             ->inIs('DEFINITION')
             ->_as('results')
             ->is('visibility','private')
             ->inIs(array('METHOD', 'MAGICMETHOD'))
             ->atomIs('Class')
             ->samePropertyAs('fullnspath', 'classname')
             ->back('results');
        $this->prepareQuery();

        // method used in a normal methodcall with $this $this->b()
        $this->atomIs(array('Method', 'Magicmethod'))
             ->is('visibility','private')
             ->outIs('DEFINITION')
             ->atomIs('Methodcall')
             ->outIs('OBJECT')
             ->atomIs('This')
             ->back('first');
        $this->prepareQuery();

        // method used in a normal methodcall with $this $this->b()
        $this->atomIs(array('Method', 'Magicmethod'))
             ->is('visibility','private')
             ->codeIs('__construct', self::TRANSLATE, self::CASE_INSENSITIVE)
             ->outIs('DEFINITION')
             ->back('first');
        $this->prepareQuery();

        // __destruct is considered automatically checked
        $this->atomIs('Class')
             ->outIs('MAGICMETHOD')
             ->atomIs('Magicmethod')
             ->is('visibility','private')
             ->outIs('NAME')
             ->codeIs('__destruct')
             ->inIs('NAME');
        $this->prepareQuery();
        
        // Other magic methods are missing
    }
}

?>
