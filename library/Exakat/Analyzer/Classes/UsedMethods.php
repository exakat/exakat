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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class UsedMethods extends Analyzer {
    public function analyze() {
        $magicMethods = $this->loadIni('php_magic_methods.ini', 'magicMethod');
        
        // Normal Methodcall
        $this->atomIs('Methodcall')
             ->outIs('METHOD')
             ->tokenIs('T_STRING')
             ->values('lccode')
             ->unique();
        $methods = $this->rawQuery()->toArray();

        if (!empty($methods)) {
            $this->atomIs(array('Method', 'Magicmethod'))
                 ->outIs('NAME')
                 ->codeIsNot($magicMethods)
                 ->is('lccode', $methods)
                 ->back('first');
            $this->prepareQuery();
        }

        // Staticmethodcall
        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->tokenIs('T_STRING')
             ->values('lccode')
             ->unique();
        $staticmethods = $this->rawQuery()->toArray();

        if (!empty($staticmethods)) {
            $this->atomIs(array('Method', 'Magicmethod'))
                 ->_as('used')
                 ->outIs('NAME')
                 ->codeIsNot($magicMethods)
                 ->is('lccode', $staticmethods)
                 ->back('used');
            $this->prepareQuery();
        }

        // Staticmethodcall in arrays
        // non-staticmethodcall in arrays, with $this
        $this->atomIs('String')
             ->atomIs('String', self::WITH_CONSTANTS)
             ->hasIn('DEFINITION')
             ->has('noDelimiter')
             ->regexIs('noDelimiter', '::.')
             ->raw(<<<GREMLIN
map{
    // Strings
    if (it.get().label() == "String") {
        if (it.get().value("noDelimiter") =~ /::/) {
            s = it.get().value("noDelimiter").split("::");
            s[1].toLowerCase();
        } else {
            it.get().value("noDelimiter").toLowerCase();
        }
    } else if (it.get().label() == "Arrayliteral") {
        it.get().vertices(OUT, "ARGUMENT").each{
            if (it.value("rank") == 1) {
                s = it.value("noDelimiter").toLowerCase();
            }
        }
        s;
    } else {
        it.get().value("noDelimiter").toLowerCase();
    }
}
GREMLIN
)
                ->unique();
                $callablesStrings = $this->rawQuery()->toArray();

        $this->atomIs('Arrayliteral')
             ->is('count', 2)
             ->filter(
                $this->side()
                     ->outWithRank('ARGUMENT', 0)
                     ->atomIs('String', self::WITH_CONSTANTS)
                     ->inIs('DEFINITION')
             )
             ->outWithRank('ARGUMENT', 1)
             ->atomIs('String', self::WITH_CONSTANTS)
             ->has('noDelimiter')
             ->values('noDelimiter')
             ->unique();
        $callablesArray = $this->rawQuery()->toArray();

        $this->atomIs('Arrayliteral')
             ->is('count', 2)
             ->filter(
                $this->side()
                     ->outWithRank('ARGUMENT', 0)
                     ->atomIs('This', self::WITH_CONSTANTS)
             )
             ->outWithRank('ARGUMENT', 1)
             ->atomIs('String', self::WITH_CONSTANTS)
             ->has('noDelimiter')
             ->values('noDelimiter')
             ->unique();
        $callablesThisArray = $this->rawQuery()->toArray();

        $callables = array_unique(array_merge($callablesArray, $callablesThisArray, $callablesStrings));
        
        if (!empty($callables)) {
            $callables = array_map('strtolower', $callables);
            // method used statically in a callback with an array
            $this->atomIs('Method')
                 ->outIs('NAME')
                 ->codeIsNot($magicMethods)
                 ->codeIs($callables, self::TRANSLATE, self::CASE_INSENSITIVE)
                 ->back('first');
            $this->prepareQuery();
        }
        
        // Private constructors
        $this->atomIs('Class')
             ->savePropertyAs('fullnspath', 'fnp')
             ->outIs('MAGICMETHOD')
             ->atomIs('Magicmethod')
             ->is('visibility', 'private')
             ->_as('used')
             ->outIs('NAME')
             ->codeIs('__construct')
             ->back('first')
             ->outIs('METHOD')
             ->atomInsideNoDefinition('New')
             ->outIs('NEW')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->samePropertyAs('fullnspath', 'fnp')
             ->back('used');
        $this->prepareQuery();

        // Normal Constructors
        $this->atomIs('Class')
             ->outIs('MAGICMETHOD')
             ->atomIs('Magicmethod')
             ->isNot('visibility', 'private')
             ->_as('used')
             ->outIs('NAME')
             ->codeIs('__construct')
             ->back('first')
             ->outIs('DEFINITION')
             ->hasIn('NEW')
             ->back('used');
        $this->prepareQuery();

        // the special methods must be processed independantly
        // __destruct is always used, no need to spot
    }
}

?>
