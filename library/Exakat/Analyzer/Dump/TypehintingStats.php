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

namespace Exakat\Analyzer\Dump;


class TypehintingStats extends AnalyzerArrayHashResults {
     protected $analyzerName   = 'Typehinting stats';

    public function analyze(): void {
        //total parameters
        $this->atomIs('Parameter')
             ->count();
        $totalArguments = $this->rawQuery()->toInt();

        //total fonctions, closures, etc.
        $this->atomIs(self::FUNCTIONS_ALL)
             ->not(
                $this->side()
                     ->atomIs('Magicmethod')
                     ->outIs('NAME')
                     ->codeIs(array('__destruct', '__construct', '__unset', '__wakeup'), self::TRANSLATE, self::CASE_INSENSITIVE)
             )
             ->count();
        $totalFunctions = $this->rawQuery()->toInt();

        //typehinted
        $this->atomIs('Parameter')
             ->filter(
                $this->side()
                     ->outIs('TYPEHINT')
                     ->atomIsNot('Void')
             )
             ->count();
        $withTypehint = $this->rawQuery()->toInt();

        //typehinted
        $this->atomIs(self::FUNCTIONS_ALL)
             ->filter(
                $this->side()
                     ->outIs('RETURNTYPE')
                     ->atomIsNot('Void')
             )
             ->count();
        $withReturnTypehint = $this->rawQuery()->toInt();

        //nullable typehinted
        $this->atomIs('Parameter')
             ->filter(
                $this->side()
                     ->outIs('TYPEHINT')
                     ->atomIsNot('Void')
             )
             ->isNullable()
             ->count();
        $argNullable = $this->rawQuery()->toInt();

        //nullable typehinted
        $this->atomIs(self::FUNCTIONS_ALL)
             ->isNullable()
             ->filter(
                $this->side()
                     ->outIs('RETURNTYPE')
                     ->atomIsNot('Void')
             )
             ->count();
        $returnNullable = $this->rawQuery()->toInt();

        //scalar typehint used
        $this->atomIs('Scalartypehint')
             ->count();
        $scalartype = $this->rawQuery()->toInt();

        //typehinted
        $this->atomIs('Scalartypehint')
             ->groupCount('fullnspath')
             ->raw('cap("m")');
        $scalartypes1 = $this->rawQuery()->toArray();

        //typehinted 2
        $this->atomIs(array('Identifier', 'Nsname'))
             ->fullnspathIs(array('\\resource', '\\mixed', '\\numeric', '\\false'))
             ->groupCount('fullnspath')
             ->raw('cap("m")');
        $scalartypes2 = $this->rawQuery()->toArray();

        $scalartypes = ($scalartypes1[0] ?? array()) + ($scalartypes2[0] ?? array());

        //typehinted object
        $this->atomIs('Parameter')
             ->outIs('TYPEHINT')
             ->atomIs(array('Identifier', 'Nsname'))
             ->groupCount('fullnspath')
             ->raw('cap("m")');
        $objecttypes1 = $this->rawQuery()->toArray();

        //typehinted object2
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('RETURNTYPE')
             ->atomIs(array('Identifier', 'Nsname'))
             ->groupCount('fullnspath')
             ->raw('cap("m")');
        $objecttypes2 = $this->rawQuery()->toArray();

        $objecttypes = ($objecttypes1[0] ?? array()) + ($objecttypes2[0] ?? array());

        //typehinted class
        $this->atomIs('Parameter')
             ->outIs('TYPEHINT')
             ->atomIs(array('Identifier', 'Nsname'))
             ->inIs('DEFINITION')
             ->atomIs('Class')
             ->isNot('abstract', true)
             ->count();
        $classtypes1 = $this->rawQuery()->toInt();

        //typehinted class
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('RETURNTYPE')
             ->atomIs(array('Identifier', 'Nsname'))
             ->inIs('DEFINITION')
             ->atomIs('Class')
             ->isNot('abstract', true)
             ->count();
        $classtypes2 = $this->rawQuery()->toInt();
        $classTypehint = $classtypes1 + $classtypes2;

        //typehinted interface
        $this->atomIs('Parameter')
             ->outIs('TYPEHINT')
             ->atomIs(array('Identifier', 'Nsname'))
             ->inIs('DEFINITION')
             ->interfaceLike()
             ->count();
        $interfacetypes1 = $this->rawQuery()->toInt();

        //typehinted class
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('RETURNTYPE')
             ->atomIs(array('Identifier', 'Nsname'))
             ->inIs('DEFINITION')
             ->interfaceLike()
             ->count();
        $interfacetypes2 = $this->rawQuery()->toInt();
        $interfaceTypehint = $interfacetypes1 + $interfacetypes2;

        //typehinted properties
        $this->atomIs('Ppp')
             ->outIs('TYPEHINT')
             ->atomIsNot('Void')
             ->back('first')
             ->outIs('PPP')
             ->count();
        $typedProperties = $this->rawQuery()->toInt();

        //total properties
        $this->atomIs('Propertydefinition')
             ->count();
        $totalProperties = $this->rawQuery()->toInt();

        //multiple properties
        $this->atomIs(array('Method', 'Closure', 'Magicmethod', 'Arrowfunction', 'Function'))
             ->filter(
                $this->side()
                     ->outIs('RETURNTYPE')
                     ->fullnspathIsNot(array('\\void', '\\null'))
                     ->count()
                     ->raw('is(gte(2))')
             )
             ->count();
        $multipleTypehints = $this->rawQuery()->toInt();

        $return = compact('totalArguments',
                          'totalFunctions',
                          'withTypehint',
                          'withReturnTypehint',
                          'scalartype',
                          'returnNullable',
                          'argNullable',
                          'classTypehint',
                          'interfaceTypehint',
                          'typedProperties',
                          'totalProperties',
                          'multipleTypehints'
                          );
        $return = $return + $scalartypes + $objecttypes;

        $atoms = array('all'            => self::FUNCTIONS_ALL,
                       'function'       => 'Function',
                       'method'         => array('Method', 'Magicmethod'),
                       'closure'        => 'Closure',
                       'arrowfunction'  => 'Arrowfunction',
                       );

        foreach($atoms as $name => $atom) {
            //total
            $this->atomIs($atom)
                 ->count();
            $return["{$name}Total"] = $this->rawQuery()->toInt();

            //parameter typehinted
            $this->atomIs($atom)
                 ->filter(
                    $this->side()
                         ->outIs('ARGUMENT')
                         ->outIs('TYPEHINT')
                         ->atomIsNot('Void')
                 )
                 ->count();
            $return["{$name}WithTypehint"] = $this->rawQuery()->toInt();

            //return typehinted
            $this->atomIs($atom)
                 ->filter(
                    $this->side()
                         ->outIs('RETURNTYPE')
                         ->atomIsNot('Void')
                 )
                 ->count();
            $return["{$name}WithReturnTypehint"] = $this->rawQuery()->toInt();
        }

        array_walk($return, function (&$value, $key) { $value = array($key, $value); });
        $return = array_values($return);
        $this->analyzerValues = $return;

        $this->prepareQuery();
    }
}

?>
