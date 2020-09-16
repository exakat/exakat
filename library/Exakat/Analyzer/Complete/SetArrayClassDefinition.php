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

namespace Exakat\Analyzer\Complete;

class SetArrayClassDefinition extends Complete {
    public function dependsOn(): array {
        return array('Complete/PropagateCalls',
                    );
    }

    public function analyze(): void {
        // array(\x, foo)
        $this->atomIs('Arrayliteral', self::WITHOUT_CONSTANTS)
              ->is('count', 2)
              ->outWithRank('ARGUMENT', 1)
              ->atomIs(self::STRINGS_LITERALS, self::WITH_CONSTANTS)
              ->has('noDelimiter')
              ->savePropertyAs('noDelimiter', 'method')
              ->back('first')
              ->outWithRank('ARGUMENT', 0)
              ->atomIs(array('String', 'Heredoc', 'Concatenation', 'Staticclass'), self::WITH_CONSTANTS)
              ->outIsIE('CLASS') // For Staticclass only
              ->inIs('DEFINITION')
              ->atomIs('Class')
              ->outIs(array('MAGICMETHOD', 'METHOD'))
              ->atomIs(array('Method', 'Magicmethod'))
              ->outIs('NAME')
              ->samePropertyAs('fullcode', 'method', self::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addEto('DEFINITION', 'first');
        $this->prepareQuery();

        // array(\x, foo)
        $this->atomIs('Arrayliteral', self::WITHOUT_CONSTANTS)
             ->hasNoIn('DEFINITION')
             ->is('count', 2)
             ->outWithRank('ARGUMENT', 1)
             ->atomIs(self::STRINGS_LITERALS, self::WITH_CONSTANTS)
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
             ->samePropertyAs('fullcode', 'method', self::CASE_INSENSITIVE)
             ->inIs('NAME')
             ->addEto('DEFINITION', 'first');
        $this->prepareQuery();

        // array($this, foo)
        $this->atomIs('Arrayliteral', self::WITHOUT_CONSTANTS)
             ->hasNoIn('DEFINITION')
             ->is('count', 2)
             ->outWithRank('ARGUMENT', 1)
             ->atomIs(self::STRINGS_LITERALS, self::WITH_CONSTANTS)
             ->has('noDelimiter')
             ->savePropertyAs('noDelimiter', 'method')
             ->back('first')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('This')
             ->goToClass()
             ->atomIs('Class')
             ->outIs(array('MAGICMETHOD', 'METHOD'))
             ->atomIs(array('Method', 'Magicmethod'))
             ->outIs('NAME')
             ->samePropertyAs('fullcode', 'method', self::CASE_INSENSITIVE)
             ->inIs('NAME')
             ->addEto('DEFINITION', 'first');
        $this->prepareQuery();

        // Link to the actual method
        $this->atomIs('Arrayliteral', self::WITHOUT_CONSTANTS)
             ->is('count', 2)
             ->inIs('DEFINITION')
             ->atomIs(array('Method', 'Magicmethod'))
             ->as('method')
             ->back('first')

             ->inIs('NAME')
             ->atomIs('Functioncall')
             ->addEfrom('DEFINITION', 'method');
        $this->prepareQuery();
    }
}

?>
