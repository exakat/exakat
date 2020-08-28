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

        foreach($types as $type => $atoms) {
            $ini = $this->methods->getFunctionsByArgType($type, Methods::STRICT);

            if (empty($ini)) {
                return;
            }

            foreach($ini as $rank => $functions) {
                // foo($arg) { array_map($arg, '') ; }
                // raw expressions
                $this->atomFunctionIs($functions)
                     ->analyzerIsNot('self')
                     ->outWithRank('ARGUMENT', (int) $rank)
                     ->atomIsNot(self::CALLS)
                     ->atomIsNot($atoms, self::WITH_CONSTANTS)
                     ->back('first');
                $this->prepareQuery();

                // custom functions
                $this->atomFunctionIs($functions)
                     ->analyzerIsNot('self')
                     ->outWithRank('ARGUMENT', (int) $rank)
                     ->atomIs(self::CALLS, self::WITH_VARIABLES)
                     ->inIs('DEFINITION')
                     ->outIs('RETURNTYPE')
                     ->fullnspathIsNot('\\' . $type)
                     ->back('first');
                $this->prepareQuery();

                // native functions
                $this->atomFunctionIs($functions)
                     ->analyzerIsNot('self')
                     ->outWithRank('ARGUMENT', (int) $rank)
                     ->atomIs('Functioncall', self::WITH_VARIABLES)
                     ->is('isPhp', true)
                     ->fullnspathIsNot($returntypes[$type])
                     ->back('first');
                $this->prepareQuery();
            }
        }
    }
}

?>
