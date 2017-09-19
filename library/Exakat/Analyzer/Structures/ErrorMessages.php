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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class ErrorMessages extends Analyzer {
    public function dependsOn() {
        return array('Exceptions/DefinedExceptions');
    }
    
    public function analyze() {
        $messages = array('String', 'Concatenation', 'Integer', 'Functioncall', 'Heredoc', 'Magicconstant');

        // die('true')
        // exit ('30');
        $this->atomFunctionIs(array('\\die', '\\exit'))
             ->outWithRank('ARGUMENT', 0)
             ->atomIs($messages);
        $this->prepareQuery();

        //  new \Exception('Message');
        $this->atomIs('New')
             ->hasNoIn('THROW')
             ->outIs('NEW')
             ->atomIs('Newcall')
             ->fullnspathIs('\\exception')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs($messages);
        $this->prepareQuery();

        //  new $exception('Message');
        $this->atomIs('Throw')
             ->outIs('THROW')
             ->atomIs('New')
             ->outIs('NEW')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs($messages);
        $this->prepareQuery();

        //  new myException('Message');
        $this->atomIs('New')
             ->hasNoIn('THROW')
             ->outIs('NEW')
             ->_as('new')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->isNot('fullnspath', null)
             ->classDefinition()
             ->analyzerIs('Exceptions/DefinedExceptions')
             ->back('new')
             ->outIs('ARGUMENT')
             ->atomIs($messages);
        $this->prepareQuery();
    }
}

?>
