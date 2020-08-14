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

class ErrorMessages extends Analyzer {
    public function dependsOn(): array {
        return array('Exceptions/DefinedExceptions',
                     'Exceptions/IsPhpException',
                    );
    }

    public function analyze(): void {
        $messages = array('String', 'Concatenation', 'Integer', 'Functioncall', 'Heredoc', 'Magicconstant');

        // die('true')
        // exit ('30');
        $this->atomIs('Exit')
             ->outWithRank('ARGUMENT', 0)
             ->outIsIE('CODE') // parenthesis for exit
             ->atomIs($messages, self::WITH_VARIABLES);
        $this->prepareQuery();

        //  new \Exception('Message');
        $this->atomIs('New')
             ->hasNoIn('THROW')
             ->outIs('NEW')
             ->atomIs('Newcall')
             ->analyzerIs('Exceptions/IsPhpException')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs($messages, self::WITH_VARIABLES);
        $this->prepareQuery();

        //  new $exception('Message');
        $this->atomIs('Throw')
             ->outIs('THROW')
             ->atomIs('New', self::WITH_VARIABLES)
             ->outIs('NEW')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs($messages);
        $this->prepareQuery();

        //  new myException('Message');
        $this->atomIs('New')
             ->hasNoIn('THROW')
             ->atomIs('New', self::WITH_VARIABLES)
             ->outIs('NEW')
             ->as('new')
             ->inIs('DEFINITION')
             ->analyzerIs('Exceptions/DefinedExceptions')
             ->back('new')
             ->outIs('ARGUMENT')
             ->atomIs($messages, self::WITH_VARIABLES);
        $this->prepareQuery();
    }
}

?>
