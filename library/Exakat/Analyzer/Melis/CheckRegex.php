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

namespace Exakat\Analyzer\Melis;

use Exakat\Analyzer\Analyzer;

class CheckRegex extends Analyzer {
    public function analyze() {
        ////////////////////////////////////////////////////////
        // in constraints/<ALL VALUES>
        ////////////////////////////////////////////////////////
        $this->atomIs('Keyvalue')   
             ->outIs('INDEX')
             ->atomIs('String')
             ->noDelimiterIs('constraints')
             ->inIs('INDEX')
             ->outIs('VALUE')
             ->atomIs('Arrayliteral')
             ->outIs('ARGUMENT')
             ->outIs('VALUE')
             ->values('noDelimiter');
        $regexList = $this->rawQuery()->toArray();
        
        $errors = array();
        foreach($regexList as $regex) {
            // π is probably not used anywhere as a regex delimiter. Until proven wrong...
            if (@preg_match("&$regex&", 'a') === false) {
                $errors[] = $regex;
            }
        }

        if (!empty($errors)) {
            $this->atomIs('Keyvalue')   
                 ->outIs('INDEX')
                 ->atomIs('String')
                 ->noDelimiterIs('constraints')
                 ->inIs('INDEX')
                 ->outIs('VALUE')
                 ->atomIs('Arrayliteral')
                 ->outIs('ARGUMENT')
                 ->outIs('VALUE')
                 ->noDelimiterIs($errors)
                 ->back('first');
            $this->prepareQuery();
        }
        
        ////////////////////////////////////////////////////////
        // in regex/
        ////////////////////////////////////////////////////////
        $this->atomIs('Keyvalue')   
             ->outIs('INDEX')
             ->atomIs('String')
             ->noDelimiterIs('regex')
             ->inIs('INDEX')
             ->outIs('VALUE')
             ->values('noDelimiter');
        $regexList = $this->rawQuery()->toArray();
        
        $errors = array();
        foreach($regexList as $regex) {
            // π is probably not used anywhere as a regex delimiter. Until proven wrong...
            if (@preg_match("&$regex&", 'a') === false) {
                $errors[] = $regex;
            }
        }
        
        if (!empty($errors)) {
            $this->atomIs('Keyvalue')   
                 ->outIs('INDEX')
                 ->atomIs('String')
                 ->noDelimiterIs('regex')
                 ->inIs('INDEX')
                 ->outIs('VALUE')
                 ->noDelimiterIs($errors)
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
