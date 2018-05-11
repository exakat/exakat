<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Analyzer\Common;

use Exakat\Analyzer\Analyzer;
use Exakat\Graph\Helpers\GraphResults;

class MultipleDeclarations extends Analyzer {
    protected $atom = 'Class';
    
    public function analyze() {
        // case-insensitive constants

        $this->raw(<<<GREMLIN
hasLabel("{$this->atom}").groupCount("m").by("fullcode").cap("m").next().findAll{ a,b -> b > 1}
GREMLIN
);
        $multiples = $this->rawQuery();

        if ($multiples->isType(GraphResults::EMPTY)) {
            return;
        }

        $this->atomIs($this->atom)
             ->fullcodeIs(call_user_func_array('array_merge', $multiples->toArray()));
        $this->prepareQuery();
    }
}

?>
