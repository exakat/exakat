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


namespace Exakat\Analyzer\Files;

use Exakat\Analyzer\Analyzer;

class DefinitionsOnly extends Analyzer {
    public static $definitions        = array('Class',
                                              'Const',
                                              'Declare',
                                              'Defineconstant',
                                              'Function',
                                              'Global',
                                              'Include',
                                              'Interface',
                                              'Usenamespace',
                                              'Void',
                                              'Trait',
                                              'Usenamespace',
                                              );
    //'Namespace',  is excluded
    public static $definitionsFunctions = array('\\\\ini_set',
                                                '\\\\error_reporting',
                                                '\\\\register_shutdown_function',
                                                '\\\\set_session_handler',
                                                '\\\\set_error_handler',
                                                '\\\\spl_autoload_register',
                                                );

    public function dependsOn(): array {
        return array('Structures/NoDirectAccess');
    }

    public function analyze(): void {
        $definitionsFunctionsList = makeList(self::$definitionsFunctions);
        $definitionsList = makeList(self::$definitions);

        // one or several namespaces
        $this->atomIs('File')
             ->outIs('FILE')
             ->outIs('EXPRESSION')
             ->outIs('CODE')
             ->raw('coalesce( __.out("EXPRESSION").hasLabel("Namespace").out("BLOCK"), __.filter{ true; } )')
             ->raw(<<<GREMLIN
not(__.where(
    __
      .out("EXPRESSION")
      .not(where( __.hasLabel($definitionsList)) )
      .not(where( __.in("ANALYZED").has("analyzer", "Structures/NoDirectAccess")) )
      .not(where( __.hasLabel("Functioncall").has("fullnspath").has("fullnspath", within($definitionsFunctionsList)) ))
))

GREMLIN
)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
