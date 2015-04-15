<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Structures;

use Analyzer;

class NoChangeIncomingVariables extends Analyzer\Analyzer {
    public function analyze() {
        $incomingVariables = array('$_GET','$_POST','$_REQUEST','$_FILES',
                                    '$_ENV', '$_SERVER',
                                    '$PHP_SELF','$HTTP_RAW_POST_DATA');
        //'$_COOKIE', '$_SESSION' : those are OK
        
        // full array unset($_GET);
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs('T_UNSET')
             ->fullnspath('\\unset')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Variable')
             ->code($incomingVariables)
             ->back('first');
        $this->prepareQuery();

        // array unset($_GET['level1']);
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs('T_UNSET')
             ->fullnspath('\\unset')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->code($incomingVariables)
             ->back('first');
        $this->prepareQuery();

        // array unset($_GET['level1']['level2']);
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs('T_UNSET')
             ->fullnspath('\\unset')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->outIs('VARIABLE')
             ->code($incomingVariables)
             ->back('first');
        $this->prepareQuery();

        // Assignation full array $_COOKIE = 22;
        $this->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('Variable')
             ->code($incomingVariables)
             ->back('first');
        $this->prepareQuery();

        // assignation index $_FILES['level1']
        $this->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->code($incomingVariables)
             ->back('first');
        $this->prepareQuery();

        // assignation index $_FILES['level1'][]
        $this->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('Arrayappend')
             ->outIs('VARIABLE')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->code($incomingVariables)
             ->back('first');
        $this->prepareQuery();

        // assignation index $_FILES['level1']['level2']
        $this->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->outIs('VARIABLE')
             ->code($incomingVariables)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
