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


namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;
use Exakat\Data\Methods;

class WrongNumberOfArgumentsMethods extends Analyzer {
    
    public function analyze() {
        $data = new Methods($this->config);
        
        $methods = $data->getMethodsArgsInterval();
        $argsMins = array();
        $argsMaxs = array();
        
        // Needs to finish the list of methods and their arguments.

        // Currently, classes are not checked.
        foreach($methods as $method) {
            if ($method['args_min'] > 0) {
                $argsMins[$method['args_min']][] = $method['name'];
            }
            $argsMaxs[$method['args_max']][] = $method['name'];
        }
        
        // case for methods
        foreach($argsMins as $nb => $f) {
            $this->atomIs(array('Methodcall', 'Staticmethodcall'))
                 ->outIs('METHOD')
                 ->codeIs($f)
                 ->isLess('count', $nb)
                 ->back('first');
            $this->prepareQuery();
        }

        foreach($argsMaxs as $nb => $f) {
            $this->atomIs(array('Methodcall', 'Staticmethodcall'))
                 ->outIs('METHOD')
                 ->codeIs($f)
                 ->isMore('count', $nb)
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
