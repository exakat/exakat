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


namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;

class UpperCaseFunction extends Analyzer {
    public function dependsOn() {
        return array('Functions/IsExtFunction',
                    );
    }
    
    public function analyze() {
        $this->atomIs('Functioncall')
             ->analyzerIs('Functions/IsExtFunction')
             ->isNotLowerCase('code');
        $this->prepareQuery();

        $this->atomIs(array('List', 'Unset', 'Echo', 'Print', 'Exit'))
             ->isNotLowerCase('code');
        $this->prepareQuery();
        
        // some of the keywords are lost anyway : implements, extends, as in foreach(), endforeach/while/for/* are lost in tokenizer (may be keep track of that)

    }
}

?>
