<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

class IsComponent extends Analyzer {
    public function analyze() {
        $inert = '.not(hasLabel("Usenamespace", "Class", "Const", "Interface", "Function", "Trait", "Include", "Global", "Static", "Void", "Defineconstant"))
                  .not(where( __.hasLabel("Functioncall").filter{ it.get().value("token") in ["T_INCLUDE", "T_INCLUDE_ONCE", "T_REQUIRE_ONCE", "T_REQUIRE"] }) )';
        
        $inertWithIfthen = $inert.'
                  .where( __.hasLabel("Ifthen").where( __.out("THEN", "ELSE").out("EXPRESSION")'.$inert.'.count().is(eq(0)) ).count().is(eq(0)) )';
        
        $this->atomIs('File')
             ->outIs('FILE')
             ->outIs('EXPRESSION')
             ->outIs('CODE')
             ->raw('coalesce(__.out("EXPRESSION").hasLabel("Namespace").out("BLOCK"),  __.filter{true} )')
             ->raw('where( __.out("EXPRESSION")'.$inertWithIfthen.'.count().is(eq(0)) )')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
