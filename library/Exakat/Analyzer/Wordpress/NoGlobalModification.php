<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Analyzer\Wordpress;

use Exakat\Analyzer\Analyzer;

class NoGlobalModification extends Analyzer {
    public function dependsOn() {
        return array('Variables/IsModified',
                     'Arrays/IsModified',
                    );
    }

    public function analyze() {
        $globalNames = $this->loadIni('wp_globals.ini', 'globals');
        
        // global $post => $post++;
        $this->atomIs('Globaldefinition')
             ->codeIs($globalNames)
             ->savePropertyAs('code', 'name')
             ->goToFunction()
             ->outIs('BLOCK')
             ->atomInsideNoAnonymous('Variable')
             ->samePropertyAs('code', 'name')
             ->analyzerIs('Variables/IsModified')
             ->back('first')
             ->inIs('GLOBAL');
        $this->prepareQuery();

        // Drop the $ from variable names
        $globalNames = array_map(function($x) { return substr($x, 1); }, $globalNames);
        
        // $GLOBALS['post']++;
        $this->atomIs('Array')
             ->outIs('INDEX')
             ->atomIs('String')
             ->hasNoOut('CONCAT')
             ->noDelimiterIs($globalNames)
             ->inIs('INDEX')
             ->outIs('VARIABLE')
             ->atomIs('Phpvariable')
             ->codeIs('$GLOBALS', self::TRANSLATE, self::CASE_SENSITIVE)
             ->analyzerIs('Variables/IsModified')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
