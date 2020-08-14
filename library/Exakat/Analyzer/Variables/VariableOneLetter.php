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


namespace Exakat\Analyzer\Variables;

use Exakat\Analyzer\Analyzer;

class VariableOneLetter extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/PropagateConstants',
                    );
    }

    public function analyze(): void {
        // Normal variables
        $this->atomIs(self::VARIABLES_USER)
             ->tokenIs('T_VARIABLE')
             ->fullcodeLength(' == 2 ');
        $this->prepareQuery();

        // Normal variables in a string : {$y}
        $this->atomIs(self::VARIABLES_USER)
             ->tokenIs('T_CURLY_OPEN')
             ->fullcodeLength(' == 4 '); // fullcode includes the {}
        $this->prepareQuery();

        // ${variables}
        $this->atomIs(self::VARIABLES_USER)
             ->tokenIs(array('T_CURLY_OPEN', 'T_DOLLAR_OPEN_CURLY_BRACES', 'T_STRING_VARNAME'))
             ->outIs('NAME')
             ->atomIs(array('String', 'Identifier', 'Nsname'), self::WITH_CONSTANTS)
             ->fullcodeLength(' == 3 ')
             ->back('first');
        $this->prepareQuery();

        // {$variables}
        $this->atomIs(self::VARIABLES_USER)
             ->tokenIs('T_DOLLAR')
             ->outIs('NAME')
             ->atomIs(array('String', 'Identifier', 'Nsname'), self::WITH_CONSTANTS)
             ->fullcodeLength(' == 3 ')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
