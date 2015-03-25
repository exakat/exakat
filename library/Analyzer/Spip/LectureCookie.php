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


namespace Analyzer\Spip;

use Analyzer;

class LectureCookie extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\IsRead',
                     'Analyzer\\Arrays\\IsRead',
                     );
    }
    
    public function analyze() {
        // $_COOKIE just read
        $this->atomIs('Variable')
             ->hasNoIn('VARIABLE') // exclude arrays
             ->code('$_COOKIE')
             ->analyzerIs('Analyzer\\Variables\\IsRead')
             ->raw('filter{ it.in.loop(1){true}{it.object.atom == "Function"}.out("NAME").has("code", "recuperer_cookies_spip").any() == false}')
             ->back('first');
        $this->prepareQuery();

        // $_COOKIE[] just read
        $this->atomIs('Array')
             ->hasNoIn('VARIABLE') // exclude arrays
             ->analyzerIs('Analyzer\\Arrays\\IsRead')
             ->outIs('VARIABLE')
             ->code('$_COOKIE')
             ->raw('filter{ it.in.loop(1){true}{it.object.atom == "Function"}.out("NAME").has("code", "recuperer_cookies_spip").any() == false}')
             ->back('first');
        $this->prepareQuery();

        // $_COOKIE['a'][] just read (2 levels)
        $this->atomIs('Array')
             ->hasNoIn('VARIABLE') // exclude arrays
             ->analyzerIs('Analyzer\\Arrays\\IsRead')
             ->outIs('VARIABLE')
             ->outIs('VARIABLE')
             ->code('$_COOKIE')
             ->raw('filter{ it.in.loop(1){true}{it.object.atom == "Function"}.out("NAME").has("code", "recuperer_cookies_spip").any() == false}')
             ->back('first');
        $this->prepareQuery();

        // $_COOKIE['a']['b'][] just read (3 levels)
        $this->atomIs('Array')
             ->hasNoIn('VARIABLE') // exclude arrays
             ->analyzerIs('Analyzer\\Arrays\\IsRead')
             ->outIs('VARIABLE')
             ->outIs('VARIABLE')
             ->outIs('VARIABLE')
             ->code('$_COOKIE')
             ->raw('filter{ it.in.loop(1){true}{it.object.atom == "Function"}.out("NAME").has("code", "recuperer_cookies_spip").any() == false}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
