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

class CouldBePrivateConstante extends Analyzer {
    public function dependsOn() {
        return array('Classes/ConstantUsedBelow',
                    );
    }
    
    public function analyze() {
        // Searching for constants that are never used outside the definition class

        // global static constants : the one with no definition class : they are all ignored.
        $this->atomIs('Staticconstant')
             ->outIs('CLASS')
             ->hasNoParent(array('Class', 'Classanonymous', 'Interface'), array('DEFINITION'))
             ->inIs('CLASS')
             ->outIs('CONSTANT')
             ->atomIs('Name')
             ->values('code')
             ->unique();
        $publicUndefinedConstants = $this->rawQuery()
                                         ->toArray();

        $this->atomIs('Staticconstant')
             ->outIs('CONSTANT')
             ->atomIs('Name')
             ->savePropertyAs('code', 'name')
             ->_as('constante')
             ->back('first')
             
             ->outIs('CLASS')
             ->_as('classe')
             ->has('fullnspath')
             ->savePropertyAs('fullnspath', 'fns')

             ->goToInstruction(array('Class', 'Classanonymous', 'File'))
             ->raw(<<<GREMLIN
filter{
    if (it.get().label() == 'File') {
        true;
    } else {   // in a class
        fns != it.get().value('fullnspath');
    }
}
GREMLIN
)
             ->select(array('classe'    => 'fullnspath',
                            'constante' => 'code'))
             ->unique();
        $publicConstants = $this->rawQuery()
                                ->toArray();

        $calls = array();
        foreach($publicConstants as $value) {
            if (isset($calls[$value['constante']])) {
                $calls[$value['constante']][] = $value['classe'];
            } else {
                $calls[$value['constante']] = array($value['classe']);
            }
        }
        
        // global static constants : the one with no definition class : they are all ignored.
        $this->atomIs('Const')
             ->isNot('visibility', 'private')
             ->outIs('CONST')
             ->analyzerIsNot('Classes/ConstantUsedBelow')
             ->_as('results')
             ->outIs('NAME')
             ->codeIsNot($publicUndefinedConstants, self::NO_TRANSLATE, self::CASE_SENSITIVE)
             ->codeIsNot(array_keys($calls), self::NO_TRANSLATE, self::CASE_SENSITIVE)
             ->savePropertyAs('code', 'constante')
             ->goToClass()
             ->isNotHash('fullnspath', $calls, 'constante')
             ->back('results');
        $this->prepareQuery();
    }
}

?>
