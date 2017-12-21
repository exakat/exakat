<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Analyzer\Constants;

use Exakat\Analyzer\Analyzer;

class IsPhpConstant extends Analyzer {
    public function analyze() {
        $constants = $this->loadIni('php_constants.ini', 'constants');
        $constantsFNP = $this->makeFullNsPath($constants);
        
        // Namespaced constant (\PATHINFO_BASENAME)
        $this->atomIs(array('Identifier', 'Nsname'))
             ->hasNoIn(array('DEFINITION', 'NEW', 'USE', 'NAME', 'EXTENDS', 'IMPLEMENTS', 'CLASS', 'CONST', 'TYPEHINT', 'FUNCTION', 'GROUPUSE', 'METHOD', 'MEMBER', 'ALIAS'))
             ->fullnspathIs($constantsFNP);
        $this->prepareQuery();

        // inside Use
        $this->atomIs('Usenamespace')
             ->hasOut('CONST')
             ->outIs('USE')
             ->fullnspathIs($constantsFNP);
        $this->prepareQuery();

        $this->atomIs(array('Identifier', 'Nsname'))
             ->inIs('DEFINITION')
             ->analyzerIs('self')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
