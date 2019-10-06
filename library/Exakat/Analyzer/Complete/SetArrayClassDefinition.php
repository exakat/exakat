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

namespace Exakat\Analyzer\Complete;

use Exakat\Analyzer\Analyzer;

class SetArrayClassDefinition extends Analyzer {
    public function dependsOn() {
        return array('Complete/PropagateCalls',
                    );
    }

    public function analyze() {
        // array(\x, foo)
        $this->atomIs('Arrayliteral', Analyzer::WITHOUT_CONSTANTS)
              ->is('count', 2)
              ->outWithRank('ARGUMENT', 1)
              ->atomIs(array('String', 'Heredoc', 'Concatenation'), Analyzer::WITH_CONSTANTS)
              ->has('noDelimiter')
              ->savePropertyAs('noDelimiter', 'method')
              ->back('first')
              ->outWithRank('ARGUMENT', 0)
              ->atomIs(array('String', 'Heredoc', 'Concatenation', 'Staticclass'), Analyzer::WITH_CONSTANTS)
              ->outIsIE('CLASS') // For Staticclass only
              ->inIs('DEFINITION')
              ->atomIs('Class')
              ->outIs(array('MAGICMETHOD', 'METHOD'))
              ->atomIs(array('Method', 'Magicmethod'))
              ->outIs('NAME')
              ->samePropertyAs('fullcode', 'method', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addEto('DEFINITION', 'first')
              ->back('first');
        $this->prepareQuery();

        // array(\x, foo)
        $this->atomIs('Arrayliteral', Analyzer::WITHOUT_CONSTANTS)
              ->is('count', 2)
              ->outWithRank('ARGUMENT', 1)
              ->atomIs(array('String', 'Heredoc', 'Concatenation'), Analyzer::WITH_CONSTANTS)
              ->has('noDelimiter')
              ->savePropertyAs('noDelimiter', 'method')
              ->back('first')
              ->outWithRank('ARGUMENT', 0)
              ->atomIs('Variable')
              ->inIs('DEFINITION')
              ->outIs('DEFAULT')
              ->atomIs('New')
              ->outIs('NEW')
              ->inIs('DEFINITION')
              ->atomIs('Class')
              ->outIs(array('MAGICMETHOD', 'METHOD'))
              ->atomIs(array('Method', 'Magicmethod'))
              ->outIs('NAME')
              ->samePropertyAs('fullcode', 'method', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addEto('DEFINITION', 'first')
              ->back('first');
        $this->prepareQuery();
    }
}

?>
