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

namespace Exakat\Analyzer\Namespaces;

use Exakat\Analyzer\Analyzer;

class ShouldMakeAlias extends Analyzer {
    public function analyze(): void {
        // No namespace ?
        $this->atomIs(array('Nsname', 'Newcall'))
             ->tokenIs(array('T_NS_SEPARATOR', 'T_NAME_RELATIVE', 'T_NAME_FULLY_QUALIFIED', 'T_NAME_QUALIFIED'))
             ->hasNoIn(array('USE', 'NAME'))
             ->hasNoParent('Usenamespace', array('NAME', 'USE'))  // use expression
             ->hasNoParent('Namespace', 'NAME')  // use expression
             ->has('fullnspath')
             ->savePropertyAs('fullnspath', 'possibleAlias')
             ->goToNamespace()
             ->raw(<<<'GREMLIN'
where( __.out("BLOCK", "CODE").out("EXPRESSION")
         .hasLabel("Usenamespace").out("USE")
         .filter{ (possibleAlias =~ "^" + it.get().value("fullnspath").replace("\\", "\\\\") ).getCount() > 0} )
GREMLIN
)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
