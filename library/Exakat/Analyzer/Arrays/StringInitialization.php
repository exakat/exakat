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

namespace Exakat\Analyzer\Arrays;

use Exakat\Analyzer\Analyzer;

class StringInitialization extends Analyzer {
    public function analyze() {
        // $a = ''; $a[1] = 3;
        $this->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('RIGHT')
             ->atomIs(self::$STRINGS_ALL)
             ->inIs('RIGHT')
             ->outIs('LEFT')
             ->atomIs('Variable')
             ->inIs('DEFINITION')
             ->outIs('DEFINITION')
             ->atomIs('Variablearray')
             ->back('first');
        $this->prepareQuery();

        // const C = ''; $a = C; $a[1] = 3;
        $this->atomIs('Assignation')
             ->codeIs('=', self::TRANSLATE, self::CASE_SENSITIVE)
             ->outIs('RIGHT')
             ->atomIs(self::$CONSTANTS_ALL)
             ->inIs('DEFINITION')
             ->outIs('VALUE')
             ->atomIs(self::$STRINGS_ALL)
             ->back('first')
             ->outIs('LEFT')
             ->atomIs('Variable')
             ->inIs('DEFINITION')
             ->outIs('DEFINITION')
             ->atomIs('Variablearray')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
