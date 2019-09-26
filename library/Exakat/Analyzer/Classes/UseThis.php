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

class UseThis extends Analyzer {
    public function dependsOn() {
        return array('Complete/SetParentDefinition',
                    );
    }

    public function analyze() {
        // Valid for both statics and normal
        // parent::
        $this->atomIs('Parent')
             ->inIs('CLASS')
             ->atomIs(array('Staticmethodcall', 'Staticproperty', 'Staticclass'))
             ->goToInstruction('Method');
        $this->prepareQuery();

        // self or parent are local.
        $this->atomIs(array('Parent', 'Self'))
             ->inIs('NAME')
             ->inIs('NEW')
             ->atomIs('New');
        $this->prepareQuery();

        // Case for normal methods
        $this->atomIs('Method')
             ->isNot('static', true)
             ->outIs('BLOCK')
             ->atomInsideNoAnonymous('This')
             ->back('first');
        $this->prepareQuery();

        // Case for statics methods
        $this->atomIs('Method')
             ->is('static', true)
             ->outIs('BLOCK')
             ->atomInsideNoAnonymous(array('Staticmethodcall', 'Staticproperty'))
             ->outIs('CLASS')
             ->tokenIs(self::$STATICCALL_TOKEN)
             ->atomIsNot('Parent')
             ->savePropertyAs('fullnspath', 'classe')
             ->goToClassTrait()
             ->samePropertyAs('fullnspath', 'classe')
             ->back('first');
        $this->prepareQuery();

    // static constant are excluded.
    }
}

?>
