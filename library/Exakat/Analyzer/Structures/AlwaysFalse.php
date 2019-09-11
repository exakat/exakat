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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class AlwaysFalse extends Analyzer {
    public function dependsOn() {
        return array('Complete/PropagateConstants',
                    );
    }

    public function analyze() {
        $conf = array('\\array'   => array('Boolean', 'String', 'Integer', 'Float'),
                      '\\int'     => array('Boolean', 'String', 'Arrayliteral', 'Float'),
                      '\\float'   => array('Boolean', 'String', 'Arrayliteral', 'Integer'),
                      '\\bool'    => array('Float', 'String', 'Arrayliteral', 'Integer'),
                      '\\string'  => array('Float', 'Boolean', 'Arrayliteral', 'Integer'),
                    );

        foreach($conf as $typehint => $atoms) {

            // function foo(array $a) { if ($a === 'b')}
            $this->atomIs('Parameter')
                 ->raw('or(__.has("nullable", true), 
                           __.out("DEFAULT").hasLabel("Null"))')
                 ->outIs('TYPEHINT')
                 ->fullnspathIs($typehint)
                 ->back('first')

                 ->outIs('NAME')
                 ->outIs('DEFINITION')
                 ->inIs(array('LEFT', 'RIGHT'))
                 ->atomIs('Comparison')
                 ->_as('results')
                 ->codeIs(array('===', '!=='), self::CASE_SENSITIVE)
                 ->outIs(array('LEFT', 'RIGHT'))
                 ->atomIs($atoms, self::WITH_CONSTANTS)
                 ->back('results');
            $this->prepareQuery();

            $atomsWithNull = array_merge($atoms, array('Null'));
            // function foo(array $a) { if ($a === 'b')}
            $this->atomIs('Parameter')
                 ->isNot('nullable', true)
                 ->not(
                    $this->side()
                         ->outIs('DEFAULT')
                         ->hasNoIn('RIGHT')
                         ->atomIs('Null')
                 )
                 ->outIs('TYPEHINT')
                 ->fullnspathIs($typehint)
                 ->back('first')

                 ->outIs('NAME')
                 ->outIs('DEFINITION')
                 ->inIs(array('LEFT', 'RIGHT'))
                 ->atomIs('Comparison')
                 ->_as('results')
                 ->codeIs(array('===', '!=='), self::CASE_SENSITIVE)
                 ->outIs(array('LEFT', 'RIGHT'))
                 ->atomIs($atomsWithNull, self::WITH_CONSTANTS)
                 ->back('results');
            $this->prepareQuery();
        }

        $functions = array('\is_array', '\is_int', '\is_float', '\is_bool', '\is_string', '\is_scalar');
        // function foo(array $a) { if (is_array($a))}
        $this->atomIs('Parameter')
             ->isNot('nullable', true)
             ->not(
                $this->side()
                     ->outIs('DEFAULT')
                     ->hasNoIn('RIGHT')
                     ->atomIs('Null')
             )
             ->outIs('TYPEHINT')
             ->fullnspathIs(array_keys($conf))
             ->back('first')

             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs('ARGUMENT')
             ->functioncallIs($functions)
             ;
        $this->prepareQuery();

        $functionsWithNull = array_merge($functions, array('is_null'));
        $this->atomIs('Parameter')
             ->raw('or(__.has("nullable", true), 
                       __.out("DEFAULT").hasLabel("Null"))')
             ->outIs('TYPEHINT')
             ->fullnspathIs(array_keys($conf))
             ->back('first')

             ->outIs('NAME')
             ->outIs('DEFINITION')
             ->inIs('ARGUMENT')
             ->functioncallIs($functionsWithNull);
        $this->prepareQuery();
    }
}

?>
