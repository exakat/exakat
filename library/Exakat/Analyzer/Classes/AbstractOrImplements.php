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

class AbstractOrImplements extends Analyzer {
    public function analyze() {
        // an abstract parent method is not in the current children
        $this->atomIs('Class')
             ->isNot('abstract', true)
             ->filter(
                $this->side()
                     ->initVariable('methods', '[]')
                     ->initVariable('abstract_methods', '[]')
                     ->goToAllParents(self::INCLUDE_SELF)
                     ->outIs(array('METHOD', 'MAGICMETHOD'))
                     ->raw(<<<'GREMLIN'
sideEffect{
    if (it.get().properties("abstract").any()) {
        abstract_methods.add(it.get().vertices(OUT, 'NAME').next().value("code"));
    } else {
        methods.add(it.get().vertices(OUT, 'NAME').next().value("code"));
    }
}.fold()
GREMLIN
)
             )
             ->filter('missing = abstract_methods - methods; missing != [];');
        $this->prepareQuery();
    }
}

?>
