<?php
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


namespace Exakat\Analyzer\Security;

use Exakat\Analyzer\Analyzer;

class SuperGlobalContagion extends Analyzer {
    public function analyze() {
        // $_get = $_GET;
        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Phpvariable')
             ->back('first')
             ->outIs('LEFT')
             ->atomIs('Variable');
        $this->prepareQuery();

        // $_get = $_GET['3'];
        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->atomIs('Phpvariable')
             ->back('first')
             ->outIs('LEFT')
             ->_as('result')
             ->atomIs('Variable')
             ->back('result');
        $this->prepareQuery();

        // $_get['3'] = $_GET
        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Phpvariable')
             ->back('first')
             ->outIs('LEFT')
             ->_as('result')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->atomIs('Variablearray')
             ->back('result');
        $this->prepareQuery();

        // $_get['3'] = $_GET[1]
        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->atomIs('Phpvariable')
             ->back('first')
             ->outIs('LEFT')
             ->_as('result')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->atomIs('Variablearray')
             ->back('result');
        $this->prepareQuery();

        // propagation is not implemented yet.
    }
}

?>
