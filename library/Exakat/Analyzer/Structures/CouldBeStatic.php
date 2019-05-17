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

class CouldBeStatic extends Analyzer {
    public function dependsOn() {
        return array('Structures/GlobalInGlobal',
                    );
    }
    
    public function analyze() {
        $this->atomIs('Globaldefinition')
             ->raw('groupCount("m").by("code").cap("m").next().findAll{ a,b -> b == 1}.keySet()');
        $result = $this->rawQuery();
        $uniqueGlobals = $result->toArray();

        $this->atomIs('Array')
             ->values('globalvar');
        $result = $this->rawQuery();
        $globalvar = $result->toArray();

        $this->atomIs(array('Variable', 'Globaldefinition'))
             ->analyzerIs('Structures/GlobalInGlobal')
             ->values('code');
        $result = $this->rawQuery();
        $implicitvar = $result->toArray();

        $uniqueGlobals = array_values(array_diff($uniqueGlobals, $globalvar, $implicitvar));
        $superglobals = $this->loadIni('php_superglobals.ini', 'superglobal');

        $this->atomIs('Globaldefinition')
             ->codeIsNot($superglobals, self::TRANSLATE, self::CASE_SENSITIVE)
             ->codeIs($uniqueGlobals, self::NO_TRANSLATE, self::CASE_SENSITIVE)
             ->codeIsNot($globalvar, self::NO_TRANSLATE, self::CASE_SENSITIVE)
             ->savePropertyAs('code', 'theGlobal')
             ->hasFunction()
             ->back('first')
             ->inIs('GLOBAL');
        $this->prepareQuery();
    }
}

?>
