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

namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;

class MustCallParentConstructor extends Analyzer {
    public function dependsOn() {
        return array('Classes/Constructor',
                    );
    }
        
    public function analyze() {
        $fullnspath = array('\spltempfileobject',
                            '\splfileobject',
                            );
        
        $lccode = $this->dictCode->translate('__construct');
        if (empty($lccode)) {
            return;
        }
        
        $this->atomIs(array('Class', 'Classanonymous'))
             ->outIs('EXTENDS')
             ->fullnspathIs($fullnspath)
             ->back('first')
             ->outIs('MAGICMETHOD')
             ->analyzerIs('Classes/Constructor')
             ->outIs('BLOCK')
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->atomInsideNoDefinition('Staticmethodcall')
                             ->outIs('CLASS')
                             ->atomIs('Parent')
                             ->inIs('CLASS')
                             ->outIs('METHOD')
                             ->codeIs($lccode, self::NO_TRANSLATE, self::CASE_INSENSITIVE)
                     )
             )
             ->back('first');
        $this->prepareQuery();
    }
}

?>
