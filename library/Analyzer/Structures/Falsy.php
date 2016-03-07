<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
namespace Analyzer\Structures;

use Analyzer;

class Falsy extends Analyzer\Analyzer {
    public function analyze() {
        // Integer
        $this->atomIs('Integer')
             ->code(array(0, '-0'));
        $this->prepareQuery();

        // Float
        $this->atomIs('Float')
             ->regex('fullcode', '^[+-]?[0\\\\.]+');
        $this->prepareQuery();

        // Boolean
        $this->atomIs('Boolean')
             ->code('false');
        $this->prepareQuery();

        // String
        $this->atomIs('String')
             ->hasNoOut('CONTAINS')
             ->noDelimiter('');
        $this->prepareQuery();

        $this->atomIs('Heredoc')
             ->OutIs('CONTAINS')
             ->OutIs('CONCAT')
             ->atomIs('String')
             ->noDelimiter('')
             ->back('first');
        $this->prepareQuery();

        // array
        $this->atomFunctionIs('\array')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();
        
        // NULL
        $this->atomIs('Null');
        $this->prepareQuery();

        // object
        // How to test?

        // resource
        // resources are always true
    }
}

?>
