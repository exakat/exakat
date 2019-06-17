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
             ->inIs('CLASS')
             ->atomIs('Staticmethodcall')
             ->_as('results')
             ->inIs('DEFINITION')
             ->isNot('visibility', 'private')
             ->back('results');
        $this->prepareQuery();

        // parent::constant
        $this->atomIs('Parent')
             ->inIs('CLASS')
             ->atomIs('Staticconstant')
             ->_as('results')
             ->inIs('DEFINITION')
             ->inIs('CONST') // just for constants
             ->isNot('visibility', 'private')
             ->back('results');
        $this->prepareQuery();

        // parent::$property
        $this->atomIs('Parent')
             ->inIs('CLASS')
             ->atomIs('Staticproperty')
             ->_as('results')
             ->inIs('DEFINITION')
             ->inIs('PPP')
             ->isNot('visibility', 'private')
             ->back('results');
        $this->prepareQuery();

        $this->atomIs('Parent')
             ->inIs('CLASS')
             ->atomIs('Staticproperty')
             ->_as('results')
             ->inIs('DEFINITION')
             ->inIs('PPP')
             ->atomIs('Virtualproperty')
             ->outIs('OVERWRITE')
             ->atomIs('Propertydefinition')
             ->inIs('PPP')
             ->isNot('visibility', 'private')
             ->back('results');
        $this->prepareQuery();

        // handle composer/extensions case
        $this->atomIs('Parent')
             ->inIs('CLASS') // Check it has ::
             ->analyzerIsNot('self')
             ->atomIsNot('Staticclass')
             ->goToClass()
             ->goToAllParents(self::INCLUDE_SELF)
             ->outIs('EXTENDS')
             ->analyzerIs(array('Composer/IsComposerNsname',
                                'Classes/IsExtClass',
                               ))
             ->back('first')
             ->inIs('CLASS');
        $this->prepareQuery();
    }
}

?>
