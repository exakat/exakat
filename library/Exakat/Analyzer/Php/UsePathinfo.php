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

class UsePathinfo extends Analyzer {
    public function analyze(): void {
        // getting the file extension with explode
        /*
        $temp = explode('.', $config);
        $ext = array_pop($temp);
        */

        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->functioncallIs(array('\\explode', '\\split'))

             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             ->tokenIs('T_CONSTANT_ENCAPSED_STRING') // could be T_VARIABLE, T_QUOTE, T_OBJECT_OPERATOR, T_DOUBLE_COLON
             ->noDelimiterIs('.')
             ->back('first')
             ->outIs('LEFT')
             ->savePropertyAs('code', 'tmpvar')
             ->inIs('LEFT')
             ->nextSibling()
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->functioncallIs('\\array_pop')
             ->outIs('ARGUMENT')
             ->samePropertyAs('code', 'tmpvar')
             ->back('first');
        $this->prepareQuery();

        /*
        $exploded = explode('.', $filename);
        
        if (count($exploded) > 1) {
            $extension = array_pop($exploded);
        }
        */
        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->functioncallIs(array('\\explode', '\\split'))
             ->outWithRank('ARGUMENT', 0)
             ->tokenIs('T_CONSTANT_ENCAPSED_STRING') // could be T_VARIABLE, T_QUOTE, T_OBJECT_OPERATOR, T_DOUBLE_COLON
             ->noDelimiterIs('.')
             ->back('first')
             ->outIs('LEFT')
             ->savePropertyAs('code', 'tmpvar')
             ->inIs('LEFT')
             ->nextSibling()
             ->atomIs('Ifthen')
             ->outIs('THEN')
             ->outIs('EXPRESSION')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->functioncallIs('\\array_pop')
             ->outIs('ARGUMENT')
             ->samePropertyAs('code', 'tmpvar')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
