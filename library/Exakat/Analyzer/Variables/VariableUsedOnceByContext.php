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


namespace Exakat\Analyzer\Variables;

use Exakat\Analyzer\Analyzer;

class VariableUsedOnceByContext extends Analyzer {
    
    public function dependsOn() {
        return array('Variables/Variablenames',
                     'Variables/InterfaceArguments');
    }
    
    public function analyze() {
        $variables = $this->query('g.V().hasLabel("Variable").not(has("code", "\\$this")).where( __.in("PROPERTY").count().is(eq(0)) ).where( 
repeat(__.in("ABSTRACT", "APPEND", "ARGUMENT", "ARGUMENTS", "AS", "AT", "BLOCK", "BREAK", "CASE", "CASES", "CAST", "CATCH", "CLASS", "CLONE", "CODE", "CONCAT", "CONDITION", "CONST", "CONSTANT", "CONTINUE", "DECLARE", "ELEMENT", "ELSE", "EXTENDS", "FILE", "FINAL", "FINALLY", "FUNCTION", "GLOBAL", "GOTO", "GROUPUSE", "IMPLEMENTS", "INCREMENT", "INDEX", "INIT", "KEY", "LABEL", "LEFT", "METHOD", "NAME", "NEW", "NOT", "OBJECT", "PPP", "POSTPLUSPLUS", "PREPLUSPLUS", "PRIVATE", "PROJECT", "PROPERTY", "PROTECTED", "PUBLIC", "RETURN", "RETURNTYPE", "RIGHT", "SIGN", "SOURCE", "STATIC", "SUBNAME", "THEN", "THROW", "TYPEHINT", "USE", "VALUE", "VAR", "VARIABLE", "YIELD"))
.until(hasLabel("File")).emit().hasLabel("Function").count().is(eq(0))).groupCount("m").by("code").cap("m")
.toList().get(0).findAll{ a,b -> b == 1}.keySet()');

        $this->atomIs('Variable')
             ->hasNoIn(array('PPP'))
             ->raw('where( __.in("LEFT").in("PPP").count().is(eq(0)) )')
             ->hasNoFunction()
             ->codeIs($variables);
        $this->prepareQuery();

        $this->atomIs('Function')
             ->raw('where( __
                   .sideEffect{counts = [:]}
                             .repeat( out().where( __.hasLabel("Function").out("NAME").hasLabel("Void").count().is(eq(0)) ) )
                             .emit( hasLabel("Variable").not(has("code", "\\$this")) ).times('.self::MAX_LOOPING.')
                             .hasLabel("Variable").not(has("code", "\\$this"))
                             .where( __.in("PROPERTY").count().is(eq(0)) )
                             .sideEffect{ k = it.get().value("code"); 
                                         if (counts[k] == null) {
                                            counts[k] = 1;
                                         } else {
                                            counts[k]++;
                                         }
                              }.fold()
                          )
                          .sideEffect{ names = counts.findAll{ a,b -> b == 1}.keySet() }
                          .repeat( out().where( __.hasLabel("Function").out("NAME").hasLabel("Void").count().is(eq(0)) )  )
                          .emit( hasLabel("Variable").not(has("code", "\\$this")) ).times('.self::MAX_LOOPING.')
                          .filter{ it.get().value("code") in names }');
        $this->prepareQuery();
    }
}

?>
