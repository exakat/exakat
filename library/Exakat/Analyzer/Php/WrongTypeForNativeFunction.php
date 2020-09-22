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

namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;
use Exakat\Data\Methods;

class WrongTypeForNativeFunction extends Analyzer {
    public function analyze(): void {
        $types = array('float'  => array('Integer', 'Float'),
                       'int'    => array('Integer'),
                       'string' => self::STRINGS_LITERALS,
                       'array'  => array('Arrayliteral'),
                       'bool'   => array('Boolean', 'Bitoperation', 'Logical', 'Comparison'),
                      );

        $returntypes = array();
        foreach($types as $type => $atoms) {
            $returntypes[$type] = $this->methods->getFunctionsByReturnType($type, Methods::STRICT);
        }
        $returntypes['null'] = $this->methods->getFunctionsByReturnType('null', Methods::LOOSE);

        foreach($types as $type => $atoms) {
            $ini = $this->methods->getFunctionsByArgType($type, Methods::STRICT);

            if (empty($ini)) {
                continue;
            }

            foreach($ini as $rank => $functions) {
                // class x { string $id; function foo() { array_map($this->id, '') ; }
                $this->atomFunctionIs($functions)
                     ->analyzerIsNot('self')
                     ->outWithRank('ARGUMENT', (int) $rank)
                     ->atomIs(array('Member', 'Staticproperty'))
                     ->inIs('DEFINITION')
                     ->inIs('PPP')
                     ->collectTypehints('typehints')
                     ->not(
                        $this->side()
                             ->outIs('TYPEHINT')
                             ->atomIs('Void')
                     )
                     ->raw('filter{!("\\\\' . $type . '" in typehints);}')
                     ->back('first');
                $this->prepareQuery();

                // foo($arg) { array_map($arg, '') ; }
                $this->atomFunctionIs($functions)
                     ->analyzerIsNot('self')
                     ->outWithRank('ARGUMENT', (int) $rank)
                     ->atomIs('Variable')
                     ->inIs('DEFINITION')
                     ->inIs('NAME')
                     ->collectTypehints('typehints')
                     ->not(
                        $this->side()
                             ->outIs('TYPEHINT')
                             ->atomIs('Void')
                     )
                     ->raw('filter{!("\\\\' . $type . '" in typehints);}')
                     ->back('first');
                $this->prepareQuery();

                // array_map(STRING, '')
                // raw expressions
                $this->atomFunctionIs($functions)
                     ->analyzerIsNot('self')
                     ->outWithRank('ARGUMENT', (int) $rank)
                     ->atomIsNot($atoms, self::WITH_CONSTANTS)
                     ->atomIsNot(array_merge(self::CALLS, self::CONTAINERS))
                     ->atomIsNot(array('Identifier', 'Nsname')) // Exclude undefined constants
                     ->back('first');
                $this->prepareQuery();

                // native functions
                // substr(rand(), 1)
                $this->atomFunctionIs($functions)
                     ->analyzerIsNot('self')
                     ->outWithRank('ARGUMENT', (int) $rank)
                     ->atomIs('Functioncall', self::WITH_VARIABLES)
                     ->is('isPhp', true)
                     ->fullnspathIsNot($returntypes[$type])

                     // Special case for false, inside a ?:
                     ->not(
                        $this->side()
                             ->fullnspathIs($returntypes['bool'])
                             ->inIs('CONDITION')
                             ->atomIs('Ternary')
                             ->outIs('THEN')
                             ->atomIs('Void')
                     )

                     // Special case for null, inside a ??
                     ->not(
                        $this->side()
                             ->fullnspathIs($returntypes['null'])
                             ->inIs('LEFT')
                             ->atomIs('Coalesce')
                     )
                     ->back('first');
                $this->prepareQuery();

                // custom functions
                // function foo() : int {}; substr(foo(), 1)
                $this->atomFunctionIs($functions)
                     ->analyzerIsNot('self')
                     ->outWithRank('ARGUMENT', (int) $rank)
                     ->atomIs(self::CALLS, self::WITH_VARIABLES)
                     ->inIs('DEFINITION')
                     ->outIs('RETURNTYPE')
                     ->atomIsNot('Void')
                     ->fullnspathIsNot('\\' . $type)
                     ->back('first');
                $this->prepareQuery();
            }
        }
    }
}

?>
