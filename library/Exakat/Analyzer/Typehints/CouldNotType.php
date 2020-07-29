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

namespace Exakat\Analyzer\Typehints;

use Exakat\Analyzer\Analyzer;

class CouldNotType extends Analyzer {
    /* PHP version restrictions
    protected $phpVersion = '7.4-';
    */

    public function dependsOn() : array {
        return array('Typehints/CouldBeCIT',
                     'Typehints/CouldBeString',
                     'Typehints/CouldBeArray',
                     'Typehints/CouldBeBoolean',
                     'Typehints/CouldBeVoid',
                     'Typehints/CouldBeCallable',
                     'Typehints/CouldBeInt',

//                     'Typehints/CouldBeFloat',
//                     'Typehints/CouldBeNull',
//                     'Typehints/CouldBeIterable',
//                     'Typehints/CouldBeStringable',
//                     'Typehints/CouldBeFromPhpdoc',
                    );
    }
    
    public function analyze() : void {
        // property definition
        $this->atomIs('Propertydefinition')
             ->analyzerIsNot($this->dependsOn())
             ->inIs('PPP')
             ->outIs('TYPEHINT')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();

        // return type
        $this->atomIs(self::FUNCTIONS_ALL)
             ->analyzerIsNot($this->dependsOn())
             ->outIs('RETURNTYPE')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();

        // argument type
        // $arg . ''
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->as('result')
             ->outIs('TYPEHINT')
             ->atomIs('Void')
             ->back('result')

             ->analyzerIsNot($this->dependsOn())
             ->back('result');
        $this->prepareQuery();
    }
}

?>
