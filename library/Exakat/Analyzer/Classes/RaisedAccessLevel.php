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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class RaisedAccessLevel extends Analyzer {
    public function analyze() {
        // raised to private
        $this->atomIs('Ppp')
             ->is('visibility', 'private')
             ->outIs('PPP')
             ->_as('results')
             ->outIs('OVERWRITE')
             ->atomIs('Propertydefinition')
             ->inIs('PPP')
             ->is('visibility', array('public', 'protected', 'none'))
             ->back('results');
        $this->prepareQuery();

        // raised to protected
        $this->atomIs('Ppp')
             ->is('visibility', 'protected')
             ->outIs('PPP')
             ->_as('results')
             ->outIs('OVERWRITE')
             ->atomIs('Propertydefinition')
             ->inIs('PPP')
             ->is('visibility', array('public', 'none'))
             ->back('results');
        $this->prepareQuery();

        // raised to private method
        $this->atomIs(array('Method', 'Magicmethod'))
             ->is('visibility', 'private')
             ->outIs('OVERWRITE')
             ->atomIs(array('Method', 'Magicmethod'))
             ->is('visibility', array('public', 'protected', 'none'))
             ->back('first');
        $this->prepareQuery();

        // raised to protected method
        $this->atomIs(array('Method', 'Magicmethod'))
             ->is('visibility', 'protected')
             ->outIs('OVERWRITE')
             ->atomIs(array('Method', 'Magicmethod'))
             ->is('visibility', array('public', 'none'))
             ->back('first');
        $this->prepareQuery();

        // raised to protected or private for const
        $this->atomIs('Const')
             ->is('visibility', 'private')
             ->outIs('CONST')
             ->_as('results')
             ->outIs('OVERWRITE')
             ->inIs('CONST')
             ->is('visibility', array('public', 'protected', 'none'))
             ->back('results');
        $this->prepareQuery();

        // raised to protected for const
        $this->atomIs('Const')
             ->is('visibility', 'protected')
             ->outIs('CONST')
             ->_as('results')
             ->outIs('OVERWRITE')
             ->inIs('CONST')
             ->is('visibility', array('public', 'none'))
             ->back('results');
        $this->prepareQuery();
    }
}

?>
