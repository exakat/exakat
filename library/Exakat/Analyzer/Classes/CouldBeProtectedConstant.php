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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class CouldBeProtectedConstant extends Analyzer {
    public function analyze(): void {
        // Searching for properties that are never used outside the definition class or its children

        // global static constants : the one with no definition class : they are all ignored.
        $this->atomIs('Staticconstant')
             ->hasNoIn('DEFINITION')
             ->outIs('CONSTANT')
             ->atomIs('Name')
             ->values('code')
             ->unique();
        $undefinedConstants = $this->rawQuery()->toArray();

        $this->atomIs('Staticconstant')
             ->hasNoClass()

             ->outIs('CLASS')
             ->has('fullnspath')
             ->as('classe')
             ->back('first')

             ->outIs('CONSTANT')
             ->atomIs('Name')
             ->as('constante')

             ->select(array('classe'    => 'fullnspath',
                            'constante' => 'code',
                            ))
             ->unique();
        $publicConstants = $this->rawQuery()->toArray();

        $calls = array();
        foreach($publicConstants as $value) {
            array_collect_by($calls, $value['classe'], $value['constante']);
        }

        // global static constants : the one with no definition class : they are all ignored.
        $this->atomIs('Const')
             ->is('visibility', array('none', 'public'))
             ->goToClass()
             ->fullnspathIs(array_keys($calls))
             ->savePropertyAs('fullnspath', 'fnq')
             ->back('first')

             ->outIs('CONST')
             ->as('results')
             ->outIs('NAME')
             ->codeIsNot($undefinedConstants, self::NO_TRANSLATE, self::CASE_SENSITIVE)
             ->isNotHash('code', $calls, 'fnq')
             ->back('results');
        $this->prepareQuery();
    }
}

?>
