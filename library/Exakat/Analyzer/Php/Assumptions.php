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

namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;

class Assumptions extends Analyzer {
    public function analyze(): void {
        // if ($a !== null) { $a->p; }
        $this->atomIs('Variabledefinition')
             ->outIs('DEFINITION')
             ->inIs(array('LEFT', 'RIGHT'))
             ->atomIs('Comparison')
             ->codeIs(array('!==', '!=', '<>'), self::TRANSLATE, self::CASE_SENSITIVE)
             ->as('results')
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Null')
             ->back('first')
             ->outIs('DEFINITION')
             ->inIs(array('OBJECT', 'CLASS'))
             ->atomIs(array('Staticconstant', 'Staticproperty', 'Staticmethodcall', 'Member', 'Methodcall'))
             ->back('results');
        $this->prepareQuery();

        // if ($a !== null) { $a->p; }
        $this->atomIs('Variabledefinition')
             ->outIs('DEFINITION')
             ->inIs('LEFT')
             ->atomIs('Comparison')
             ->codeIs(array('!==', '!=', '<>'), self::TRANSLATE, self::CASE_INSENSITIVE)
             ->as('results')
             ->outIs('RIGHT')
             ->atomIs('Null')
             ->back('first')
             ->outIs('DEFINITION')
             ->inIs('VARIABLE')
             ->atomIs(array('Array', 'Arrayappend'))
             ->back('results');
        $this->prepareQuery();

        // if (array $a) { echo $a['some']}
        $this->atomIs('Parameter')
             ->outIs('TYPEHINT')
             ->atomIs('Scalartypehint')
             ->fullnspathIs('\\array')
             ->back('first')
             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->atomIs('Variablearray')
             ->inIs('VARIABLE')
             ->atomIs('Array')
             ->is('isRead', true)
             ->not(
                $this->side()
                     ->inIs('CONDITION')
                     ->atomIs('Ternary')
                     ->outIs('THEN')
                     ->atomIs('Void')
             )
             ->not(
                $this->side()
                     ->inIs('LEFT')
                     ->atomIs('Coalesce')
             )
             ->outIs('INDEX')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
