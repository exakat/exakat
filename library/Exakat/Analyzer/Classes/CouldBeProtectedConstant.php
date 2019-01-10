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

class CouldBeProtectedConstant extends Analyzer {
    public function analyze() {
        // Searching for properties that are never used outside the definition class or its children

        // global static constants : the one with no definition class : they are all ignored.
        $queryUndefinedConstants = <<<GREMLIN
g.V().hasLabel("Staticconstant")
     .not( __.where( __.out("CLASS").in("DEFINITION").hasLabel("Class", "Classanonymous", "Interface") ) )
     .out("CONSTANT")
     .hasLabel("Name")
     .values("code")
     .unique()
GREMLIN;
        $publicUndefinedConstants = $this->query($queryUndefinedConstants)
                                         ->toArray();

        $queryPublicConstants = <<<GREMLIN
g.V().hasLabel("Staticconstant")
     .out("CLASS")
     .as("classe")
     .has("fullnspath")
     .sideEffect{ fns = it.get().value("fullnspath"); }
     .in("CLASS")
     .out("CONSTANT")
     .hasLabel("Name")
     .sideEffect{ name = it.get().value("code"); }
     .as("constante")
     .repeat( __.in({$this->linksDown})).until(hasLabel("Class", "Interface", "Classanonymous", "File") )
     .hasLabel("File")
     .select("classe", "constante").by("fullnspath").by("code")
     .unique()
GREMLIN;
        $publicConstants = $this->query($queryPublicConstants)
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
             ->isNot('visibility', array('private', 'protected'))
             ->outIs('CONST')
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
