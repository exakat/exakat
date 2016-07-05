<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Variables;

use Analyzer;

class VariableUsedOnceByContext extends Analyzer\Analyzer {
    
    public function dependsOn() {
        return array('Variables/Blind',
                     'Variables/Variablenames',
                     'Variables/InterfaceArguments');
    }
    
    public function analyze() {
        $this->atomIs('Function')
             ->outIs('BLOCK')
             ->raw('where( __
                   .sideEffect{counts = [:]}
                             .repeat( out() ).emit( hasLabel("Variable")).times(15)
                             .sideEffect{ k = it.get().value("code"); 
                                         if (counts[k] == null) {
                                            counts[k] = 1;
                                         } else {
                                            counts[k]++;
                                         }
                              }.fold()
                          )
                          .sideEffect{ names = counts.findAll{ a,b -> b == 1}.keySet() }
                          .repeat( out() ).emit( hasLabel("Variable")).times(15)
                          .filter{ it.get().value("code") in names }');
        $this->prepareQuery();

        return;
        // Variables outside a closure
        $this->atomIs('Variable')
        
             // Excluding closures 
             ->filter(<<<GREMLIN
    x = it; 
    it.in.loop(1){it.object.atom != "Function"}{it.object.atom == "Function"}
         .filter{it.out("USE").out("ARGUMENT").retain([x]).any() == false} // Variables in a USE clause from a closure are OK
         .out("NAME") // Variable in a closure are not OK
         .has("atom", "String")
         .any() == false
GREMLIN
)
            // Not a static property
             ->hasNoIn('PROPERTY')

             ->analyzerIs('Variables/Variablenames')
             ->analyzerIsNot('Variables/Blind')
             ->analyzerIsNot('Variables/InterfaceArguments')
             ->codeIsNot(VariablePhp::$variables, true)
             ->hasNoIn('GLOBAL')
             ->analyzerIsNot('Variables/VariableUsedOnceByContext')

             //This is not an argument in an abstract method
             ->filter(' it.in().loop(1){it.object.atom != "Function"}{ it.object.atom == "Function"}.out("ABSTRACT").any() == false')

             //This is not an argument of the method
             ->filter(' code = it.code;
                        it.in().loop(1){it.object.atom != "Function"}{ it.object.atom == "Function"}
                                        .out("ARGUMENTS").out("ARGUMENT")
                                        .transform{ a = it; while (a.out("VARIABLE", "LEFT").any()) { a = a.out("VARIABLE", "LEFT").next(); }; a;}
                                        .has("code", code)
                                        .any() == false')

             ->fetchContext(\Analyzer\Analyzer::CONTEXT_OUTSIDE_CLOSURE)

             ->eachCounted('context["Namespace"] + "/" + context["Class"] + "/" + context["Function"] + "/" + it.code', 1);
        $this->prepareQuery();

        // Variables inside a closure
        $this->atomIs('Variable')
        
             // Including closures variables
             ->filter(<<<GREMLIN
    x = it; 
    it.in.loop(1){it.object.atom != "Function"}{it.object.atom == "Function"}
         .out("NAME") // Variable in a closure are not OK
         .has("atom", "String")
         .any()
GREMLIN
)
            // Not a static property
             ->hasNoIn('PROPERTY')

             ->analyzerIs('Variables/Variablenames')
             ->analyzerIsNot('Variables/Blind')
             ->analyzerIsNot('Variables/InterfaceArguments')
             ->codeIsNot(VariablePhp::$variables, true)
             ->hasNoIn('GLOBAL')
             ->analyzerIsNot('Variables/VariableUsedOnceByContext')

             //This is not an argument in an abstract method
             ->filter(' it.in().loop(1){it.object.atom != "Function"}{ it.object.atom == "Function"}.out("ABSTRACT").any() == false')

             //This is not an argument of the method
             ->filter(' code = it.code;
                        it.in().loop(1){it.object.atom != "Function"}{ it.object.atom == "Function"}
                                        .out("ARGUMENTS").out("ARGUMENT")
                                        .transform{ a = it; while (a.out("VARIABLE", "LEFT").any()) { a = a.out("VARIABLE", "LEFT").next(); }; a;}
                                        .has("code", code)
                                        .any() == false')

             ->fetchContext(\Analyzer\Analyzer::CONTEXT_IN_CLOSURE)

             ->eachCounted('context["Namespace"] + "/" + context["Class"] + "/" + context["Function"] + "/" + it.code', 1)
             ;
        $this->prepareQuery();
    }
}

?>
