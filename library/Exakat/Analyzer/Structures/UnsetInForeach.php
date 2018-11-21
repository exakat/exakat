<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class UnsetInForeach extends Analyzer {
    public function analyze() {
        // foreach($a as $v) { unset($v); }
        // Only valid : Objects (unset on properties) or arrays (if the blind variable is reference)
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE(array('INDEX', 'VALUE'))
             ->atomIs('Variable')
             ->is('reference', true)
             ->savePropertyAs('code', 'blind')
             ->inIsIE(array('INDEX', 'VALUE'))
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Unset')
             ->outIs('ARGUMENT')
             ->outIsIE(array('VARIABLE', 'OBJECT'))
             ->samePropertyAs('code', 'blind', self::CASE_SENSITIVE)
             ->inIsIE(array('VARIABLE', 'OBJECT'))
             ->atomIsNot(array('Member', 'Array'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE(array('INDEX', 'VALUE'))
             ->atomIs('Variable')
             ->savePropertyAs('code', 'blind')
             ->isNot('reference', true)
             ->inIsIE(array('INDEX', 'VALUE'))
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Unset')
             ->outIs('ARGUMENT')
             ->outIsIE(array('VARIABLE', 'OBJECT'))
             ->samePropertyAs('code', 'blind', self::CASE_SENSITIVE)
             ->inIsIE(array('VARIABLE', 'OBJECT'))
             ->atomIsNot('Member')
             ->back('first');
        $this->prepareQuery();

////////////////////////////////////////////////////////////
// same but with (unset) instead of unset()
////////////////////////////////////////////////////////////
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE(array('INDEX', 'VALUE'))
             ->atomIs('Variable')
             ->savePropertyAs('code', 'blind')
             ->is('reference', true)
             ->inIsIE(array('INDEX', 'VALUE'))
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Cast')
             ->tokenIs('T_UNSET_CAST')
             ->outIs('CAST')
             ->outIsIE(array('VARIABLE', 'OBJECT'))
             ->samePropertyAs('code', 'blind', self::CASE_SENSITIVE)
             ->inIsIE(array('VARIABLE', 'OBJECT'))
             ->atomIsNot(array('Member', 'Array'))
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE(array('INDEX', 'VALUE'))
             ->atomIs('Variable')
             ->savePropertyAs('code', 'blind')
             ->isNot('reference', true)
             ->inIsIE(array('INDEX', 'VALUE'))
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Cast')
             ->tokenIs('T_UNSET_CAST')
             ->outIs('CAST')
             ->outIsIE(array('VARIABLE', 'OBJECT'))
             ->samePropertyAs('code', 'blind', self::CASE_SENSITIVE)
             ->inIsIE(array('VARIABLE', 'OBJECT'))
             ->atomIsNot('Member')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
