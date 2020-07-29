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


namespace Exakat\Analyzer\Security;

use Exakat\Analyzer\Analyzer;

class SensitiveArgument extends Analyzer {
    public function analyze() : void {
        $unsafe = $this->loadIni('security_vulnerable_functions.ini');

        foreach($unsafe as $position => $functions) {
            $functions = makeFullNsPath($functions);

            $position = (int) str_replace('functions', '', $position);

            // $_GET/_POST ... directly as argument of PHP functions
            $this->atomFunctionIs($functions)
                 ->outWithRank('ARGUMENT', $position);
            $this->prepareQuery();
        }

        $this->atomIs(array('Echo', 'Print', 'Exit', 'Eval', 'Include'))
             ->followParAs('ARGUMENT');
        $this->prepareQuery();

        $this->atomIs('Shell')
             ->outIs('CONCAT')
             ->atomIsNot('String');
        $this->prepareQuery();
    }
}

?>
