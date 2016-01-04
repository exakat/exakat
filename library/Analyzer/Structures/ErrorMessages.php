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


namespace Analyzer\Structures;

use Analyzer;

class ErrorMessages extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Exceptions/DefinedExceptions');
    }
    
    public function analyze() {
        $messages = array('String', 'Concatenation', 'Integer', 'Functioncall');

        // die('true')
        // exit ('30');
        $this->atomIs('Functioncall')
             ->tokenIs(array('T_DIE', 'T_EXIT'))
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs($messages);
        $this->prepareQuery();

        //  new \Exception('Message');
        $this->atomIs('New')
             ->outIs('NEW')
             ->atomIs('Functioncall')
             ->isNot('fullnspath', null)
             ->fullnspath('\\exception')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs($messages);
        $this->prepareQuery();

        //  new $exception('Message');
        $this->atomIs('Throw')
             ->outIs('THROW')
             ->atomIs('New')
             ->outIs('NEW')
             ->atomIsNot('Functioncall')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs($messages);
        $this->prepareQuery();

        //  new myException('Message');
        $this->atomIs('New')
             ->outIs('NEW')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->isNot('fullnspath', null)
             ->filter(' g.idx("classes")[["path":it.fullnspath]].in("ANALYZED").has("code","Analyzer\\\\Exceptions\\\\DefinedExceptions").any()')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs($messages);
        $this->prepareQuery();
    }
}

?>
