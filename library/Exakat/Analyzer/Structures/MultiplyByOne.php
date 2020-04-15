<?php declare(strict_types = 1);
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

class MultiplyByOne extends Analyzer {
    public function dependsOn() : array {
        return array('Complete/PropagateConstants',
                    );
    }

    public function analyze() {
        $atoms = array('String', 'Integer', 'Boolean', 'Float', 'Identifier', 'Nsname', 'Assignation', 'Parenthesis', 'Multiplication');
        
        // $x *= 1;
        $this->atomIs('Assignation')
             ->codeIs(array('*=', '/=', '%=', '**='))
             ->outIs('RIGHT')
             ->atomIs($atoms, self::WITH_CONSTANTS)
             ->is('intval', 1)
             ->regexIs('noDelimiter', '^1\\\\.?0*\\$')
             ->back('first');
        $this->prepareQuery();

        // $x = $y * 1
        $this->atomIs('Multiplication')
             ->codeIs('*')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs($atoms, self::WITH_CONSTANTS)
             ->is('intval', 1)
             ->regexIs('noDelimiter', '^1\\\\.?0*\\$')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Multiplication')
             ->codeIs(array('/', '%'))
             ->outIs('RIGHT')
             ->atomIs($atoms, self::WITH_CONSTANTS)
             ->is('intval', 1)
             ->regexIs('noDelimiter', '^1\\\\.?0*\\$')
             ->back('first');
        $this->prepareQuery();

        // $b * 12 / 12
        $this->atomIs('Multiplication')
             ->codeIs('/')
             ->outIs('RIGHT')
             ->atomIs($atoms, self::WITH_CONSTANTS)
             ->isNot('intval', 1)
             ->savePropertyAs('intval', 'operand')
             ->back('first')

             ->outIs('LEFT')
             ->atomIs('Multiplication')
             ->codeIs('*')
             ->outIs('RIGHT')
             ->samePropertyAs('intval', 'operand')

             ->back('first');
        $this->prepareQuery();

        // $b / 12 * 12
        $this->atomIs('Multiplication')
             ->codeIs('*')
             ->outIs('RIGHT')
             ->atomIs($atoms, self::WITH_CONSTANTS)
             ->isNot('intval', 1)
             ->savePropertyAs('intval', 'operand')
             ->back('first')

             ->outIs('LEFT')
             ->atomIs('Multiplication')
             ->codeIs('/')
             ->outIs('RIGHT')
             ->samePropertyAs('intval', 'operand')

             ->back('first');
        $this->prepareQuery();

        // $x = $y ** 1
        $this->atomIs('Power')
             ->outIs('RIGHT')
             ->atomIs($atoms, self::WITH_CONSTANTS)
             ->is('intval', 1)
             ->regexIs('noDelimiter', '^1\\\\.?0*\\$')
             ->back('first');
        $this->prepareQuery();

        // 1 ** $a;
        $this->atomIs('Power')
             ->outIs('LEFT')
             ->atomIs($atoms, self::WITH_CONSTANTS)
             ->is('intval', 1)
             ->regexIs('noDelimiter', '^1\\\\.?0*\\$')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
