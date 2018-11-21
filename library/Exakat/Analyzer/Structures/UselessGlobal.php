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

class UselessGlobal extends Analyzer {
    public function analyze() {
        $globals = $this->dictCode->translate(array('$GLOBALS'));

        // Global are unused if used only once
        $this->atomIs('Globaldefinition')
             ->values('code');
        $inglobal = $this->rawQuery()
                         ->toArray();

        if (empty($globals)) {
            $inGlobals = array();
        } else {
            $this->atomIs('Phpvariable')
                 ->codeIs($globals[0], self::NO_TRANSLATE, self::CASE_SENSITIVE )
                 ->inIs('VARIABLE')
                 ->atomIs('Array')
                 ->values('globalvar');
            $inGlobals = $this->rawQuery()
                              ->toArray();
        }

        $this->atomIs('Php')
             ->outIs('CODE')
             ->atomInsideNoDefinition(array('Variable', 'Variablearray', 'Variableobject', 'Globaldefinition'))
             ->values('code');
        $implicitGLobals = $this->rawQuery()
                                ->toArray();

        $counts = array_count_values(array_merge($inGlobals, $inglobal, $implicitGLobals));
        $loneGlobal = array_filter($counts, function ($x) { return $x == 1; });
        $loneGlobal = array_keys($loneGlobal);

        if (!empty($loneGlobal)) {
            $this->atomIs('Globaldefinition')
                 ->codeIs($loneGlobal, self::NO_TRANSLATE, self::CASE_SENSITIVE);
            $this->prepareQuery();
            
            if (!empty($globals)) {
                $this->atomIs('Phpvariable')
                     ->codeIs($globals, self::NO_TRANSLATE, self::CASE_SENSITIVE)
                     ->inIs('VARIABLE')
                     ->atomIs('Array')
                     ->is('globalvar', $loneGlobal);
                $this->prepareQuery();
            }
        }

        $superglobals = $this->loadIni('php_superglobals.ini', 'superglobal');
        $superglobals = $this->dictCode->translate($superglobals);
        if (!empty($superglobals)) {
            $this->atomIs('Globaldefinition')
                 ->analyzerIsNot('self')
                 ->codeIs($superglobals, self::NO_TRANSLATE, self::CASE_SENSITIVE);
            $this->prepareQuery();
        }

        // used only once

        // written only
    }
}

?>
