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

namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;
use Exakat\Query\DSL\FollowParAs;

class WrongReturnedType extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/CreateDefaultValues',
                     'Functions/IsGenerator',
                     'Complete/SetClassRemoteDefinitionWithGlobal',
                     'Complete/SetClassRemoteDefinitionWithInjection',
                     'Complete/SetClassRemoteDefinitionWithLocalNew',
                     'Complete/SetClassRemoteDefinitionWithParenthesis',
                     'Complete/SetClassRemoteDefinitionWithReturnTypehint',
                     'Complete/SetClassRemoteDefinitionWithTypehint',
                    );
    }

    public function analyze(): void {
//Generator, Iterator, Traversable, or iterable
    // missing support for return typehint from functions (custom and natives)

        // function foo() : A { return new A;}
        $this->atomIs(self::FUNCTIONS_ALL)
             ->analyzerIsNot('Functions/IsGenerator')
             ->outIs('RETURNTYPE')
             ->atomIsNot('Void')
             ->savePropertyAs('fullnspath', 'fqn')
             ->back('first')
             ->outIs('RETURNED')
             ->as('results')

             ->outIs('NEW')
             ->notSamePropertyAs('fullnspath', 'fqn')
             /*
             ->not(
                 $this->side()
                      ->inIs('DEFINITION')
                      ->goToAllImplements(self::INCLUDE_SELF)
                      ->samePropertyAs('fullnspath', 'fqn')
             )*/
             ->back('results')
             ->inIs('RETURN');
            $this->prepareQuery();

        // function foo() : A { return new A;}
        $this->atomIs(self::FUNCTIONS_ALL)
             ->analyzerIsNot('Functions/IsGenerator')
             ->outIs('RETURNTYPE')
             ->atomIsNot(array('Void', 'Scalartypehint'))
             ->back('first')
             ->outIs('RETURNED')
             ->atomIs(array('Integer', 'String', 'Heredoc', 'Float', 'Null', 'Boolean', 'Arrayliteral'))
             ->inIs('RETURN');
        $this->prepareQuery();

        // function foo() : A { $a = 1; return $a;}
        $this->atomIs(self::FUNCTIONS_ALL)
             ->analyzerIsNot('Functions/IsGenerator')
             ->outIs('RETURNTYPE')
             ->atomIsNot(array('Void', 'Scalartypehint'))
             ->back('first')
             ->outIs('RETURNED')
             ->as('results')
             ->atomIs('Variable')
             ->inIs('DEFINITION')
             ->inIsIE('NAME')
             ->outIs('TYPEHINT')
             ->atomIs('Void')
             ->inIs('TYPEHINT')
             ->outIs('DEFAULT')
             ->atomIs(array('Integer', 'String', 'Heredoc', 'Float', 'Null', 'Boolean', 'Arrayliteral', 'Void'))
             ->back('results')
             ->inIs('RETURN');
        $this->prepareQuery();

        // function foo() : A { $a = new B; return $a;}
        $this->atomIs(self::FUNCTIONS_ALL)
             ->analyzerIsNot('Functions/IsGenerator')
             ->outIs('RETURNTYPE')
             ->atomIsNot(array('Void', 'Scalartypehint'))
             ->savePropertyAs('fullnspath', 'fqn')
             ->back('first')
             ->outIs('RETURNED')
             ->as('results')
             ->atomIs('Variable')
             ->inIs('DEFINITION')
             ->outIs('DEFAULT')
             ->atomIs('New')
             ->outIs('NEW')

              ->notSamePropertyAs('fullnspath', 'fqn')
              /*
            ->not(
                 $this->side()
                      ->inIs('DEFINITION')
                      ->goToAllImplements(self::INCLUDE_SELF)
                      ->samePropertyAs('fullnspath', 'fqn')
             )
             */
             ->back('results')
             ->inIs('RETURN')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // function foo(B $b) : A { return $b;}
        $this->atomIs(self::FUNCTIONS_ALL)
             ->analyzerIsNot('Functions/IsGenerator')
             ->outIs('RETURNTYPE')
             ->atomIsNot(array('Void', 'Scalartypehint'))
             ->savePropertyAs('fullnspath', 'fqn')
             ->back('first')
             ->outIs('RETURNED')
             ->as('results')
             ->atomIs('Variable')
             ->inIs('DEFINITION')
             ->inIs('NAME')
             ->outIs('TYPEHINT')
             ->notSamePropertyAs('fullnspath', 'fqn')
             ->back('results')
             ->inIs('RETURN')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        // PHP scalar types
        // Don't process void : it is checked at lint time
        $this->atomIs(self::FUNCTIONS_ALL)
             ->analyzerIsNot('Functions/IsGenerator')
             ->outIs('RETURNTYPE')
             ->atomIs('Scalartypehint')
             ->savePropertyAs('fullnspath', 'fqn')
             ->back('first')
             ->outIs('RETURNED')
             ->hasIn('RETURN')
             ->as('results')
             ->followParAs(FollowParAs::FOLLOW_NONE)
             ->atomIsNot(array('Variable', 'Staticproperty', 'Member', 'Functioncall', 'Methodcall', 'Staticmethodcall'))
             ->optional(
                $this->side()
                     ->atomIs(array('Identifier', 'Nsname'), self::WITH_CONSTANTS)
             )
             ->checkTypeWithAtom('fqn')
             ->back('results')
             ->inIs('RETURN')
             ->analyzerIsNot('self');
        $this->prepareQuery();

        //Relayed return types
        // Don't process void : it is checked at lint time
        $this->atomIs(self::FUNCTIONS_ALL)
             ->analyzerIsNot('Functions/IsGenerator')
             ->outIs('RETURNTYPE')
             ->atomIs('Scalartypehint')
             ->savePropertyAs('fullnspath', 'fqn')
             ->back('first')
             ->outIs('RETURNED')
             ->hasIn('RETURN')
             ->as('results')
             ->followParAs(FollowParAs::FOLLOW_NONE)
             ->atomIs(array('Functioncall', 'Methodcall', 'Staticmethodcall'))
             ->inIs('DEFINITION')
             ->outIs('RETURNTYPE')
             ->savePropertyAs('fullnspath', 'fqn2')
             ->raw('filter{ fqn != fqn2; }')
             ->back('results')
             ->inIs('RETURN');
        $this->prepareQuery();

        // Type is not the argument type
        $this->atomIs(self::FUNCTIONS_ALL)
             ->analyzerIsNot('Functions/IsGenerator')
             ->outIs('RETURNTYPE')
             ->atomIs('Scalartypehint')
             ->savePropertyAs('fullnspath', 'fqn')
             ->back('first')
             ->outIs('RETURNED')
             ->hasIn('RETURN')
             ->as('results')
             ->atomIs('Variable')
             ->inIs('DEFINITION')
             ->inIs('NAME')
             ->outIs('TYPEHINT')
             ->notSamePropertyAs('fullnspath', 'fqn')
             ->back('results')
             ->inIs('RETURN')
             ->analyzerIsNot('self');
        $this->prepareQuery();
    }
}

?>
