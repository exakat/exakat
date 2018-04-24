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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;
use Exakat\Analyzer\Structures\UnknownPregOption;

class InvalidRegex extends Analyzer {
    public function analyze() {
        $functionList = makeList(array_map( 'addslashes', UnknownPregOption::$functions));

        $regexQuery = <<<GREMLIN
g.V().hasLabel("Functioncall").has("fullnspath", within($functionList))
                              .out("ARGUMENT")
                              .has("rank", 0)
                              .hasLabel("String")
                              .not( where( __.out("CONCAT")) )
                              .map{
                              if (it.get().value("delimiter") == "'") {
                                regex = it.get().value('noDelimiter').replaceAll("\\\\\\\\(['\\\\\\\\])", "\\$1");
                              } else {
                                regex = it.get().value('noDelimiter').replaceAll('\\\\\\\\(["\\\\\\\\])', "\\$1");
                              }
                              
                              [regex, it.get().value('fullcode')]};
GREMLIN;
        $regexSimple = $this->query($regexQuery);

        $regexQuery = <<<GREMLIN
g.V().hasLabel("Functioncall").has("fullnspath", within($functionList))
                              .out("ARGUMENT")
                              .has("rank", 0)
                              .hasLabel("Concatenation", "String")
                              .where( __.out("CONCAT"))
                              .not( where( __.out("CONCAT").hasLabel("String", "Identifier", "Nsname", "Staticconstant").not(has("noDelimiter"))) )
                              .where( __.sideEffect{ liste = [];}
                                   .out("CONCAT").order().by('rank')
                                   .hasLabel("String", "Variable", "Array", "Functioncall", "Methodcall", "Staticmethodcall", "Member", "Staticproperty", "Identifier", "Nsname", "Staticconstant")
                                   .sideEffect{ 
                                        if (it.get().label() in ["String", "Identifier", "Nsname", "Staticconstant"] ) {
                                            liste.add(it.get().value("noDelimiter"));
                                         } else {
                                            liste.add("smi"); // smi is compatible with flags
                                         }
                                    }
                                   .count()
                              )
                              .map{[liste.join(''), it.get().value('fullcode')]};
GREMLIN;
        $regexComplex = $this->query($regexQuery);
        
        $regexList = array_merge($regexSimple->toArray(), $regexComplex->toArray());

        $invalid = array();
        foreach($regexList as list($regex, $fullcode)) {
            // @ is important here : we want preg_match to fail silently.  
            if (false === @preg_match(str_replace('\\\\', '\\', $regex), '')) {
                $invalid[] = $fullcode;
            }
        }
        
        if (empty($invalid)) {
            return;
        }
        
        $this->atomFunctionIs(UnknownPregOption::$functions)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs(array('String', 'Concatenation'))
             ->fullcodeIs($invalid)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
