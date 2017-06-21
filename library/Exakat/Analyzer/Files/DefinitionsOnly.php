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


namespace Exakat\Analyzer\Files;

use Exakat\Analyzer\Analyzer;

class DefinitionsOnly extends Analyzer {
    public static $definitions        = array("Trait", "Class", "Interface", "Const", "Use", "Global", "Declare", "Void", "Include");
    //'Namespace',  is excluded

    public static $definitionsFunctions = array('define', 'ini_set', 'error_reporting',
                                                'register_shutdown_function', 'set_session_handler', 'set_error_handler',
                                                'require_once', 'require', 'include', 'include_once',
                                                'spl_autoload_register');
    
    public function dependsOn() {
        return array('Structures/NoDirectAccess');
    }
    
    public function analyze() {
        $definitionsFunctionsList = "\"\\\\".implode("\", \"\\\\", self::$definitionsFunctions)."\"";
        $definitionsList = "\"".implode("\", \"", self::$definitions)."\"";

        // one or several namespaces
        $this->atomIs('File')
             ->outIs('FILE')
             ->outIs('EXPRESSION')
//             ->atomIs('Php')
             ->outIs('CODE')
             ->raw('coalesce( __.out("EXPRESSION").hasLabel("Namespace").out("BLOCK"), __.filter{ true; } )')
             ->raw(<<<GREMLIN
not(__.where(
    __
      .out("EXPRESSION")
      .where( __.hasLabel($definitionsList).count().is(eq(0)) )
      .where( __.hasLabel("Function").where( __.out("NAME").hasLabel("Void").count().is(eq(0))).count().is(eq(0)) )
      .where( __.in("ANALYZED").has("analyzer", "Structures/NoDirectAccess").count().is(eq(0)) )
      .not(where( __.hasLabel("Functioncall").not(has("fullnspath")) ))
      .where( __.hasLabel("Functioncall").has("fullnspath").filter{ it.get().value("fullnspath") in [$definitionsFunctionsList] }.count().is(eq(0)) )
))

GREMLIN
)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
