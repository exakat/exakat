<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class UselessInstruction extends Analyzer {
    public function dependsOn() {
        return array('Classes/IsaMagicProperty',
                    );
    }

    public function analyze() {
        // Structures that should be put somewhere, and never left alone
        $this->atomIs('Sequence')
             ->hasNoIn('FINAL')
             ->outIs('EXPRESSION')
             ->analyzerIsNot('Classes/IsaMagicProperty')
             ->atomIs(array('Array', 'Addition', 'Multiplication', 'Member', 'Staticproperty', 'Boolean',
                            'Magicconstant', 'Staticconstant', 'Integer', 'Real', 'Sign', 'Nsname',
                            'Identifier', 'String', 'Instanceof', 'Bitshift', 'Comparison', 'Null', 'Logical',
                            'Heredoc', 'Power', 'Spaceship', 'Coalesce', 'New'))
             ->noAtomInside(array('Functioncall', 'Staticmethodcall', 'Methodcall', 'Assignation', 'Defineconstant'));
        $this->prepareQuery();
        
        // foreach($i = 0; $i < 10, $j < 20; $i++)
        $this->atomIs('For')
             ->outIs('FINAL')
             ->outWithoutLastRank()
             ->atomIs(array('Array', 'Addition', 'Multiplication', 'Member', 'Staticproperty', 'Boolean',
                            'Magicconstant', 'Staticconstant', 'Integer', 'Real', 'Sign', 'Nsname',
                            'Identifier', 'String', 'Instanceof', 'Bitshift', 'Comparison', 'Null', 'Logical',
                            'Heredoc', 'Power', 'Spaceship', 'Coalesce', 'New'))
             ->noAtomInside(array('Functioncall', 'Staticmethodcall', 'Methodcall', 'Assignation'));
        $this->prepareQuery();

        // -$x = 3
        $this->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('Sign');
        $this->prepareQuery();

        // closures that are not assigned to something (argument or variable)
        $this->atomIs('Sequence')
             ->outIs('EXPRESSION')
             ->atomIs('Closure');
        $this->prepareQuery();

        // return $a++; (unless it is an argument/use by reference)
        // May also check if it is static or global (those stays).
        $this->atomIs('Return')
             ->outIs('RETURN')
             ->atomIs('Postplusplus')
             ->outIs('POSTPLUSPLUS')
             ->atomIsNot('Variable')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Return')
             ->outIs('RETURN')
             ->atomIs('Postplusplus')
             ->outIs('POSTPLUSPLUS')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'variable')
             ->goToFunction()
             ->outIs(array('ARGUMENT', 'USE'))
             ->isNot('reference', true)
             ->outIsIE('NAME')
             ->samePropertyAs('code', 'variable')
             ->back('first');
        $this->prepareQuery();

        // return an argument that is also a reference
        $this->atomIs('Return')
             ->outIs('RETURN')
             ->atomIs('Postplusplus')
             ->outIs('POSTPLUSPLUS')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'variable')
             ->goToFunction()
             ->raw('not(where( __.out("ARGUMENT").out("NAME").filter{ it.get().value("code") == variable; }))')
             ->raw('not(where( __.out("USE").filter{ it.get().value("code") == variable; }))')
             ->back('first');
        $this->prepareQuery();

        // array_merge($a); one argument is useless.
        $this->atomFunctionIs('\\array_replace')
             ->isLess('count', 2)
             ->outWithRank('ARGUMENT',0)
             ->isNot('variadic', true)
             ->back('first');
        $this->prepareQuery();

        // foreach(@$a as $b);
        $this->atomIs('Foreach')
             ->outIs('SOURCE')
             ->atomIs('Noscream');
        $this->prepareQuery();

        // @$x = 3;
        $this->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('Noscream')
             ->outIs('AT')
             ->atomIs('Variable')
             ->inIs('AT');
        $this->prepareQuery();

        // Closure with some operations
        $this->atomIs('Function')
             ->inIs('LEFT')
             ->atomIs(array('Addition', 'Multiplication', 'Power'))
             ->back('first');
        $this->prepareQuery();

        // $x = 'a' . function ($a) {} (Concatenating a closure...)
        $this->atomIs('Function')
             ->inIs('CONCAT')
             ->atomIs('Concatenation')
             ->back('first');
        $this->prepareQuery();

        // New in a instanceof (with/without parenthesis)
        $this->atomIs('New')
             ->inIsIE(array('CODE', 'RIGHT'))
             ->inIs('VARIABLE')
             ->atomIs('Instanceof')
             ->back('first');
        $this->prepareQuery();

        // Empty string in a concatenation
        $this->atomIs('Concatenation')
             ->outIs('CONCAT')
             ->outIsIE('CODE') // skip parenthesis
             ->codeIs(array("''", '""'))
             ->back('first');
        $this->prepareQuery();

        // array_unique(array_keys())
        $this->atomFunctionIs('\\array_unique')
             ->outWithRank('ARGUMENT', 0)
             ->functioncallIs('array_keys')
             ->back('first');
        $this->prepareQuery();

        $this->atomFunctionIs('\\count')
             ->outWithRank('ARGUMENT', 0)
             ->functioncallIs(array('\\array_keys', 
                                    '\\array_values', 
                                    '\\array_flip', 
                                    '\\array_fill', 
                                    '\\array_walk', 
                                    '\\array_map', 
                                    '\\array_change_key_case', 
                                    '\\array_combine', 
                                    '\\array_multisort', 
                                    '\\array_replace', 
                                    '\\array_reverse',
                                    ))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
