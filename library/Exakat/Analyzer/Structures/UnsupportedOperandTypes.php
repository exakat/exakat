<?php declare(strict_types = 1);
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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class UnsupportedOperandTypes extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/PropagateConstants',
                     'Complete/SetClassPropertyDefinitionWithTypehint',
                     'Complete/SetClassRemoteDefinitionWithLocalNew',
                    );
    }

    public function analyze(): void {
        // const A = 1; $b = A + array();
        $this->atomIs('Addition')
             ->analyzerIsNot('self')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Arrayliteral')
             ->back('first')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(array('Identifier', 'Nsname', 'Staticconstant'))
             ->goToLiteralValue()
             ->atomIsNot('Arrayliteral')
             ->back('first');
        $this->prepareQuery();

        // const A = array(); $b = 1 + array();
        $this->atomIs('Addition')
             ->analyzerIsNot('self')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(array('String', 'Integer', 'Float', 'Boolean'))
             ->back('first')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(array('Identifier', 'Nsname', 'Staticconstant'))
             ->goToLiteralValue()
             ->atomIs('Arrayliteral')
             ->back('first');
        $this->prepareQuery();

        // $b = 1 + foo(); function foo() : array {}
        $this->atomIs('Addition')
             ->analyzerIsNot('self')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(array('String', 'Integer', 'Float', 'Boolean'))
             ->back('first')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(self::CALLS)
             ->inIs('DEFINITION')
             ->outIs('RETURNTYPE')
             ->atomIs('Scalartypehint')
             ->fullnspathIs('\\array')
             ->back('first');
        $this->prepareQuery();

        // $b = 1 + $o->p; class b { public array $p; }
        $this->atomIs('Addition')
             ->analyzerIsNot('self')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(array('String', 'Integer', 'Float', 'Boolean'))
             ->back('first')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(array('Member', 'Staticmember'))
             ->inIs('DEFINITION')
             ->inIs('PPP')
             ->outIs('TYPEHINT')
             ->atomIs('Scalartypehint')
             ->fullnspathIs('\\array')
             ->back('first');
        $this->prepareQuery();

        // function foo(array $a) { $b = 1 + $a; }
        $this->atomIs('Addition')
             ->analyzerIsNot('self')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(array('String', 'Integer', 'Float', 'Boolean'))
             ->back('first')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Variable')
             ->inIs('DEFINITION')
             ->inIs('NAME')
             ->outIs('TYPEHINT')
             ->atomIs('Scalartypehint')
             ->fullnspathIs('\\array')
             ->back('first');
        $this->prepareQuery();

        // function foo($a = array()) { $b = 1 + $a; }
        $this->atomIs('Addition')
             ->analyzerIsNot('self')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(array('String', 'Integer', 'Float', 'Boolean'))
             ->back('first')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Variable')
             ->inIs('DEFINITION')
             ->inIs('NAME')
             ->outIs('DEFAULT')
             ->atomIs('Arrayliteral', self::WITH_CONSTANTS)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
