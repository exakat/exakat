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

namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class WrongReturnedType extends Analyzer {
    public function dependsOn() {
        return array('Functions/IsGenerator',
                    );
    }

    public function analyze() {
//Generator, Iterator, Traversable, or iterable

        // function foo() : A { return new A;}
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->analyzerIsNot('Functions/IsGenerator')
             ->outIs('RETURNTYPE')
             ->savePropertyAs('fullnspath', 'fqn')
             ->back('first')
             ->atomInsideNoDefinition('Return')
             ->outIs('RETURN')
             ->_as('results')
             ->outIs('NEW')
             ->not(
                 $this->side()
                      ->inIs('DEFINITION')
                      ->goToAllImplements(self::INCLUDE_SELF)
                      ->samePropertyAs('fullnspath', 'fqn')
             )
             ->back('results');
        $this->prepareQuery();

        // function foo() : A { return new A;}
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->analyzerIsNot('Functions/IsGenerator')
             ->outIs('RETURNTYPE')
             ->fullnspathIsNot(array('\\int', '\\\float', '\\object', '\\boolean', '\\string', '\\array', '\\callable', '\\iterable'))
             ->back('first')
             ->atomInsideNoDefinition('Return')
             ->_as('results')
             ->outIs('RETURN')
             ->atomIs(array('Integer', 'String', 'Heredoc', 'Float', 'Null', 'Boolean', 'Arrayliteral'))
             ->back('results');
        $this->prepareQuery();

        // function foo() : A { $a = 1; return $a;}
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->analyzerIsNot('Functions/IsGenerator')
             ->outIs('RETURNTYPE')
             ->fullnspathIsNot(array('\\int', '\\\float', '\\object', '\\boolean', '\\string', '\\array', '\\callable', '\\iterable'))
             ->back('first')
             ->atomInsideNoDefinition('Return')
             ->_as('results')
             ->outIs('RETURN')
             ->atomIs('Variable')
             ->inIs('DEFINITION')
             ->outIs('DEFAULT')
             ->atomIs(array('Integer', 'String', 'Heredoc', 'Float', 'Null', 'Boolean', 'Arrayliteral'))
             ->back('results');
        $this->prepareQuery();

        // function foo() : A { $a = B; return $a;}
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->analyzerIsNot('Functions/IsGenerator')
             ->outIs('RETURNTYPE')
             ->fullnspathIsNot(array('\\int', '\\\float', '\\object', '\\boolean', '\\string', '\\array', '\\callable', '\\iterable'))
             ->savePropertyAs('fullnspath', 'fqn')
             ->back('first')
             ->atomInsideNoDefinition('Return')
             ->_as('results')
             ->outIs('RETURN')
             ->atomIs('Variable')
             ->inIs('DEFINITION')
             ->outIs('DEFAULT')
             ->atomIs('New')
             ->outIs('NEW')
             ->not(
                 $this->side()
                      ->inIs('DEFINITION')
                      ->goToAllImplements(self::INCLUDE_SELF)
                      ->samePropertyAs('fullnspath', 'fqn')
             )
             ->back('results');
        $this->prepareQuery();

        // function foo(B $b) : A { return $b;}
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->analyzerIsNot('Functions/IsGenerator')
             ->outIs('RETURNTYPE')
             ->fullnspathIsNot(array('\\int', '\\\float', '\\object', '\\boolean', '\\string', '\\array', '\\callable', '\\iterable'))
             ->savePropertyAs('fullnspath', 'fqn')
             ->back('first')
             ->atomInsideNoDefinition('Return')
             ->_as('results')
             ->outIs('RETURN')
             ->atomIs('Variable')
             ->inIs('DEFINITION')
             ->inIs('NAME')
             ->outIs('TYPEHINT')
             ->notSamePropertyAs('fullnspath', 'fqn')
             ->back('results');
        $this->prepareQuery();

        // PHP scalar types
        // Don't process void : it is checked at lint time
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->analyzerIsNot('Functions/IsGenerator')
             ->outIs('RETURNTYPE')
             ->fullnspathIs(array('\\int', '\\\float', '\\object', '\\boolean', '\\string', '\\array', '\\callable', '\\iterable'))
             ->savePropertyAs('fullnspath', 'fqn')
             ->back('first')
             ->atomInsideNoDefinition('Return')
             ->_as('results')
             ->outIs('RETURN')
             ->raw(<<<GREMLIN
filter{
    if (fqn == "\\\\int") {
        !(it.get().label() in ["Integer"]);
    } else if (fqn == "\\\\string") {
        !(it.get().label() in ["String", "Heredoc", "Concatenation"]);
    } else if (fqn == "\\\\array") {
        !(it.get().label() in ["Arrayliteral"]);
    } else if (fqn == "\\\\float") {
        !(it.get().label() in ["Float"]);
    } else if (fqn == "\\\\boolean") {
        !(it.get().label() in ["Boolean"]);
    } else if (fqn == "\\\\object") {
        !(it.get().label() in ["Variable", "New"]);
    } else if (fqn == "\\\\void") {
        !(it.get().label() in ["Void"]);
    } else if (fqn == "\\\\callable") {
        !(it.get().label() in ["Closure"]);
    } else if (fqn == "\\\\iterable") {
        if (it.get().label() in ["Arrayliteral"]) {
            false;
        } else if("fullnspath" in it.get().properties() && it.get().value("fullnspath") in ["\\\\arrayobject", "\\\\iterator"]) {
            false;
        } else {
            true;
        }
    } else {
        true;
    }
}
GREMLIN
)
             ->back('results');
        $this->prepareQuery();
    }
}

?>
