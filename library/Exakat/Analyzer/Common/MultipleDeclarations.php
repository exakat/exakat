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


namespace Exakat\Analyzer\Common;

use Exakat\Analyzer\Analyzer;
use Exakat\Graph\Helpers\GraphResults;

class MultipleDeclarations extends Analyzer {
    protected $atom = 'Class';

    public function analyze(): void {
        // case-insensitive constants

        $this->atomIs($this->atom)
             ->raw(<<<'GREMLIN'
groupCount("m").by("fullnspath").cap("m").next().findAll{ a,b -> b > 1}
GREMLIN
);
        $multiples = $this->rawQuery();

        if ($multiples->isType(GraphResults::EMPTY)) {
            return;
        }

        $fullcode = array_merge(...array_map('array_keys', $multiples->toArray()));
        $this->atomIs($this->atom)
             ->fullnspathIs($fullcode);
        $this->prepareQuery();
    }
}

?>
