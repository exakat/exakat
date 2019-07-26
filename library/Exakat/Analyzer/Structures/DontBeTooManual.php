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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class DontBeTooManual extends Analyzer {
    public function analyze() {
        // preg_match('/a/', $x, $matches);
        $values = $this->loadJson('php_manual_values.json');

        foreach($values as $value) {
            $this->atomFunctionIs($value->function)
                 ->outWithRank('ARGUMENT', $value->position)
                 ->codeIs($value->name, self::TRANSLATE, self::CASE_INSENSITIVE)
                 ->back('first');
            $this->prepareQuery();
        }

        // catch (Exception $e)
        $this->atomIs('Catch')
             ->outIs('VARIABLE')
             ->codeIs('$e')
             ->back('first');
        $this->prepareQuery();

        // for($i = 0; ...)
        $this->atomIs('For')
             ->outIs('INIT')
             ->outIs('EXPRESSION')
             ->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('Variable')
             ->codeIs('$i')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
