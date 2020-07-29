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

class AliasConfusion extends Analyzer {
    public function analyze() : void {
        // namespace A { class B {}}
        // namespace A { use C as B; }  // B is also a class local to the current namespace
        $this->atomIs(self::CIT)
             ->values('fullnspath');
        $fnp = $this->rawQuery()->toArray();

        $this->atomIs('Usenamespace')
             ->hasNoOut(array('FUNCTION', 'CONST'))
             ->goToNamespace()
             ->raw(<<<'GREMLIN'
sideEffect{
    if (it.get().label() == "Namespace") {
        nspath = it.get().value("fullnspath");
    } else {
        nspath = "";
    }
}
GREMLIN
)
             ->back('first')

             ->outIs('USE')
             ->raw('filter{ x = ***; nspath + "\\\\" + it.get().value("alias") in x && !(it.get().value("fullnspath") in x);}', $fnp);
        $this->prepareQuery();
    }
}

?>
