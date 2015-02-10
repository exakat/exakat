<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Arrays;

use Analyzer;

class AmbiguousKeys extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs('Functioncall')
             ->code('array')
             ->raw("aggregate().findAll{ it.out('ARGUMENTS').out('ARGUMENT').has('atom', 'Keyvalue').out('KEY').groupBy{if (it.hasNot('delimiter', null).count() > 0) { it.noDelimiter; } else { it.code ;}}{it.atom}{it.unique().toList().size()}.cap.next().findAll{it.value > 1}.size() > 0}");
        $this->prepareQuery();
    }
}

?>
