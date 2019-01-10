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

class NoMagicWithArray extends Analyzer {
    public function dependsOn() {
        return array('Classes/DefinedProperty',
                    );
    }
    
    public function analyze() {
        $__get = $this->dictCode->translate(array('__get'));
        
        // No __get found
        if (empty($__get)) {
            return ;
        }
    
        $this->atomIs(array('Array', 'Arrayappend'))
             ->outIs(array('VARIABLE', 'APPEND'))
             ->atomIs('Member')

             // Property is not defined
             // Can't use Classes/UndefinedProperty, which avoid classes with __get
             ->analyzerIsNot('Classes/DefinedProperty')

             ->_as('object')
             ->outIs('OBJECT')
             ->atomIs('This')
             ->back('object')
             ->outIs('MEMBER')
             ->savePropertyAs('code', 'name')
             ->back('first')

             // __get is defined
             ->goToClass()
             ->goToAllParents(self::INCLUDE_SELF)
             ->outIs('MAGICMETHOD')
             ->outIs('NAME')
             ->codeIs($__get, self::NO_TRANSLATE, self::CASE_INSENSITIVE)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
