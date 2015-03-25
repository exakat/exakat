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


namespace Analyzer\Security;

use Analyzer;

class ShouldUsePreparedStatement extends Analyzer\Analyzer {
    public function analyze() {
        $functions = array( '\\sqlite_query',
                            '\\mysql_query',
                            '\\mysqli_query',
                            '\\pg_query');
        
        // dynamic type in the code
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($functions)
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 1)
             ->atomIs(array('Concatenation', 'Variable', 'Property'))
             /*
             ->isNot('constante', true)
             */
             ->back('first');
        $this->prepareQuery();

        // dynamic type in the code
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($functions)
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 1)
             ->atomIs('String')
             ->outIs('CONTAIN')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
