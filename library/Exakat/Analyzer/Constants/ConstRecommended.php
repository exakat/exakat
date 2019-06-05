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


namespace Exakat\Analyzer\Constants;

use Exakat\Analyzer\Analyzer;

class ConstRecommended extends Analyzer {
    public function dependsOn() {
        return array('Constants/ConstantUsage',
                    );
    }
    
    public function analyze() {
        // define('const', literal);
        // define('const', other constant);
        $this->atomIs('Defineconstant')
             ->_as('args')
             ->outIs('NAME')
             ->atomIs('Identifier')
             ->back('args')
             ->outIs('VALUE')
             ->atomIs(array('String', 'Float', 'Integer', 'Boolean', 'Null', 'Staticconstant', 'Concatenation'), self::WITH_CONSTANTS)
             ->is('constant', true)
             ->back('first');
        $this->prepareQuery();

        // define('const', expression);
        $this->atomIs('Defineconstant')
             ->_as('args')
             ->outIs('NAME')
             ->atomIs('Identifier')
             ->back('args')
             ->outIs('VALUE')
             ->atomIsNot(array('Identifier', 'Nsname','String', 'Float', 'Integer', 'Boolean', 'Null', 'Staticconstant', 'Variable'))
             ->noAtomInside(array('Variable', 'Functioncall'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
