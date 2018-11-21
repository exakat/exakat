<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

class NoListWithString extends Analyzer {
    protected $phpVersion = '7.0-';
    
    public function analyze() {
        // list($a, $b) = 'string';
        $this->atomIs('List')
             ->inIs('LEFT')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs(array('String', 'Concatenation', 'Heredoc'))
             ->back('first');
        $this->prepareQuery();

        // $c = 'string'; list($a, $b) = $c;
        $this->atomIs('List')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'name')
             ->inIs('RIGHT')
             ->previousSibling()
             ->atomIs('Assignation')
             ->codeIs(array('=', '.='))
             ->outIs('LEFT')
             ->atomIs('Variable')
             ->samePropertyAs('code', 'name')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->atomIs(array('String', 'Concatenation', 'Heredoc'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
