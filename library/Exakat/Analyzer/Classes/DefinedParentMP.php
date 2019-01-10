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

class DefinedParentMP extends Analyzer {
    public function dependsOn() {
        return array('Composer/IsComposerNsname',
                     'Classes/IsExtClass',
                    );
    }
    
    public function analyze() {
        // parent::methodcall()
        $this->atomIs('Parent')
             ->hasIn('DEFINITION')
             ->inIs('CLASS')
             ->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->savePropertyAs('lccode', 'name')
             ->back('first')
             ->inIs('DEFINITION')
             ->goToAllParents(self::INCLUDE_SELF)
             ->outIs(array('METHOD', 'MAGICMETHOD'))
             ->isNot('visibility', 'private')
             ->outIs('NAME')
             ->samePropertyAs('lccode', 'name')
             ->back('first')
             ->inIs('CLASS');
        $this->prepareQuery();

        // parent::$property
        $this->atomIs('Parent')
             ->hasIn('DEFINITION')
             ->inIs('CLASS')
             ->atomIs('Staticproperty')
             ->outIs('MEMBER')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->inIs('DEFINITION')
             ->goToAllParents(self::INCLUDE_SELF)
             ->outIs('PPP')
             ->isNot('visibility', 'private')
             ->outIs('PPP')
             ->samePropertyAs('code', 'name', self::CASE_SENSITIVE)
             ->back('first')
             ->inIs('CLASS');
        $this->prepareQuery();

        // parent::constant
        $this->atomIs('Parent')
             ->hasIn('DEFINITION')
             ->inIs('CLASS')
             ->atomIs('Staticconstant')
             ->outIs('CONSTANT')
             ->savePropertyAs('code', 'name')
             ->back('first')
             ->inIs('DEFINITION')
             ->goToAllParents(self::INCLUDE_SELF)
             ->outIs('CONST')
             ->isNot('visibility', 'private')
             ->outIs('CONST')
             ->outIs('NAME')
             ->samePropertyAs('code', 'name')
             ->back('first')
             ->inIs('CLASS');
        $this->prepareQuery();

        // parent::$property or parent::methodcall or parent::constant
        $this->atomIs('Parent')
             ->hasNoIn('DEFINITION')
             ->analyzerIs(array('Composer/IsComposerNsname',
                                'Classes/IsExtClass',
                                ))
             ->inIs('CLASS')
             ->atomIsNot('Staticclass');
        $this->prepareQuery();

        // handle composer/extensions case
        $this->atomIs('Parent')
             ->inIs('CLASS') // Check it has ::
             ->atomIsNot('Staticclass')
             ->goToClass()
             ->goToAllParents(self::INCLUDE_SELF)
             ->outIs('EXTENDS')
             ->analyzerIs(array('Composer/IsComposerNsname',
                                'Composer/IsExtClass',
                               ))
             ->back('first')
             ->inIs('CLASS');
        $this->prepareQuery();
    }
}

?>
