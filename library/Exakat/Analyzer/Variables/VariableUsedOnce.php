<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class VariableUsedOnce extends Analyzer {
    
    public function dependsOn() {
        return array('Variables/InterfaceArguments',
                     'Variables/Variablenames'
                     );
    }
    
    public function analyze() {
        $usedOnce = $this->query(<<<'GREMLIN'
g.V().as("first").groupCount("processed").by(count())
     .hasLabel("Variable", "Variablearray", "Variableobject", "Functioncall")
     .where( __.in("ANALYZED").has("analyzer", "Variables/Variablenames").count().is(neq(0)) )
     .where( __.in("ANALYZED").has("analyzer", "Variables/InterfaceArguments").count().is(eq(0)) )
     .filter{ !(it.get().value("code") in ["\$_GET", "\$_POST", "\$_COOKIE", "\$_FILES", "\$_SESSION", "\$_REQUEST", "\$_ENV", "\$_SERVER", "\$PHP_SELF", "\$HTTP_RAW_POST_DATA", "\$HTTP_GET_VARS", "\$HTTP_POST_VARS", "\$HTTP_POST_FILES", "\$HTTP_ENV_VARS", "\$HTTP_SERVER_VARS", "\$HTTP_COOKIE_VARS", "\$GLOBALS", "\$this", "\$argv", "\$argc"]); }
     .where( __.in("GLOBAL").count().is(eq(0)) )
     .groupCount("m").by("code").cap("m").next().findAll{ a,b -> b == 1}.keySet()
GREMLIN
)->toArray();

        if (empty($usedOnce)) {
            return;
        }

        $this->atomIs(self::$VARIABLES_ALL)
             ->analyzerIs('Variables/Variablenames')
             ->analyzerIsNot('Variables/InterfaceArguments')
             ->codeIsNot(VariablePhp::$variables, true)
             ->hasNoIn('GLOBAL') // ignore global $variable; This is not a use.
             ->codeIs($usedOnce, true);
        $this->prepareQuery();
        
//         'Functioncall'
    }
}

?>
