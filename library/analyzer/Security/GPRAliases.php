<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class GPRAliases extends Analyzer\Analyzer {
    public function analyze() {
        // Web variables
        $webVariables = array('$_GET', '$_POST', '$_REQUEST', '$_COOKIE');

        // $a = $_POST
        $this->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('RIGHT')
             ->atomIs('Variable')
             ->codeIs($webVariables, true)
             ->back('first')
             ->outIs('LEFT');
        $this->prepareQuery();

        // $a = $_POST['a']
        $this->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('RIGHT')
             ->atomIs('Array')
             ->outIsIE('VARIABLE')
             ->atomIs('Variable')
             ->codeIs($webVariables, true)
             ->back('first')
             ->outIs('LEFT');
        $this->prepareQuery();
    }
}

?>
