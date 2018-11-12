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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class CouldBePrivateConstante extends Analyzer {
    public function dependsOn() {
        return array('Classes/ConstantUsedBelow',
                    );
    }
    
    public function analyze() {
        // Searching for properties that are never used outside the definition class or its children

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

        $MAX_LOOPING = self::MAX_LOOPING;
        $this->atomIs('Staticconstant')
             ->outIs('CLASS')
             ->_as('classe')
             ->has('fullnspath')
             ->savePropertyAs('fullnspath', 'fns')
             ->inIs('CLASS')
             ->outIs('CONSTANT')
             ->atomIs('Name')
             ->savePropertyAs('code', 'name')
             ->_as('constante')
             ->goToInstruction(array('File', 'Class', 'Classanonymous', 'Interface'))
             ->raw(<<<GREMLIN
     or( __.hasLabel("File"), 
         __.repeat( __.as("x").out("EXTENDS", "IMPLEMENTS").in("DEFINITION").where(neq("x")) ).emit().times($MAX_LOOPING)
           .where( __.out("CONST").out("CONST").filter{ it.get().value("code") == name; } )
           .filter{it.get().value("fullnspath") != fns; }
        )
        .select("classe", "constante").by("fullnspath").by("code")
GREMLIN
                  )
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
