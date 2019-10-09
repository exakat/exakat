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

namespace Exakat\Analyzer\Dump;

use Exakat\Analyzer\Analyzer;

class TypehintingStats extends Analyzer {
    /* PHP version restrictions
    protected $phpVersion = '7.4-';
    */

    /* List dependencies 
    public function dependsOn() {
        return array('Category/Analyzer',
                     '',
                    );
    }
    */
    
    public function analyze() {
        //total parameters
        $this->atomIs('Parameter')
             ->count();
        $totalArguments = $this->rawQuery()->toInt();

        //total parameters
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->count();
        $totalFunctions = $this->rawQuery()->toInt();

        //typehinted
        $this->atomIs('Parameter')
             ->outIs('TYPEHINT')
             ->atomIsNot('Void')
             ->count();
        $withTypehint = $this->rawQuery()->toInt();

        //typehinted
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->outIs('RETURNTYPE')
             ->atomIsNot('Void')
             ->count();
        $withReturnTypehint = $this->rawQuery()->toInt();

        //typehinted
        $this->atomIs('Parameter')
             ->outIs('TYPEHINT')
             ->atomIsNot('Void')
             ->is('nullable', true)
             ->count();
        $argNullable = $this->rawQuery()->toInt();

        //typehinted
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->outIs('RETURNTYPE')
             ->atomIsNot('Void')
             ->is('nullable', true)
             ->count();
        $returnNullable = $this->rawQuery()->toInt();

        //typehinted
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
             ->fullnspathIs(array('\\resource', '\\mixed', '\\numeric'))
             ->groupCount('fullnspath')
             ->raw('cap("m")');
        $scalartypes2 = $this->rawQuery()->toArray();

        $scalartypes = $scalartypes1[0] + $scalartypes2[0];

        // object is the difference

        $return = compact('totalArguments', 'totalFunctions', 'withTypehint','withReturnTypehint', 'scalartype', 'returnNullable', 'argNullable');
        $return = $return + $scalartypes;

        $atoms = array('all'            => self::$FUNCTIONS_ALL, 
                       'function'       => 'Function', 
                       'method'         => array('Method', 'Magicmethod'), 
                       'closure'        => 'Closure',
                       'arrowfunction'  => 'Arrowfunction',
                       );

        foreach($atoms as $name => $atom) {
            //returntypehinted
            $this->atomIs($atom)
                 ->filter(
                    $this->side()
                         ->outIs('ARGUMENT')
                         ->outIs('TYPEHINT')
                         ->atomIsNot('Void')
                 )
                 ->count();
            $return["{$name}WithTypehint"] = $this->rawQuery()->toInt();
    
            //typehinted
            $this->atomIs($atom)
                 ->outIs('RETURNTYPE')
                 ->atomIsNot('Void')
                 ->count();
            $return["{$name}WithReturnTypehint"] = $this->rawQuery()->toInt();
        }

        $this->analyzerName   = 'Typehinting stats';
        $this->analyzedValues = $return;
        
        $this->prepareQuery(self::QUERY_PHP_HASH);
    }
}

?>
