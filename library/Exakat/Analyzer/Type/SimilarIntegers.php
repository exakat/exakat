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

namespace Exakat\Analyzer\Type;

use Exakat\Analyzer\Analyzer;

class SimilarIntegers extends Analyzer {
    public function analyze() {
        // $x = 10; $y = 0xa; $z = -+-10;
        $this->atomIs(array('Integer', 'Addition', 'Power', 'Multiplication', 'Sign', 'Bitshift'))
             ->has('intval')
             ->raw(<<<'GREMLIN'
group("m").by("intval").by("fullcode").cap("m").next().findAll{ a,b -> b.unique().size() > 1}
GREMLIN
);
        $res = $this->rawQuery();
        $results = $res->toArray();

        $integers = array();
        $fullcode = array();
        foreach($results as $integerList) {
            foreach($integerList as $intval => $list) {
                $integers[] = $intval;
                $fullcode[] = $list;
            }
        }

        if (empty($integers)) {
            return;
        }

        $fullcode = array_merge(...$fullcode);

        $this->atomIs(array('Integer', 'Addition', 'Power', 'Multiplication', 'Sign', 'Bitshift'))
             ->has('intval')
             ->is('intval', $integers)
             ->is('fullcode', $fullcode);
        $this->prepareQuery();
    }
}

?>
