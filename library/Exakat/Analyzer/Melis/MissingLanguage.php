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

namespace Exakat\Analyzer\Melis;

use Exakat\Analyzer\Analyzer;

class MissingLanguage extends Analyzer {
    public function analyze() {
        $index = $this->atomIs('File')
                      ->regexIs('fullcode', '^/language/.+\\\\.php')
                      ->raw('count()');
        $count = $this->rawQuery()->toInt();
        
        if ($count === 0) {
            return;
        }

        $index = $this->atomIs('File')
                      ->regexIs('fullcode', '^/language/.+\\\\.php')
                      ->outIs('FILE')
                      ->outIs('EXPRESSION')
                      ->atomIs('Php')
                      ->outIs('CODE')
                      ->outIs('EXPRESSION')
                      ->atomIs('Return')
                      ->outIs('RETURN')
                      ->atomIs('Arrayliteral')
                      ->outIs('ARGUMENT')
                      ->outIs('INDEX')
                      ->values('code');
        $res = $this->rawQuery();

        $index = array_count_values($res->toArray());
        
        $index = array_filter($index, function ($x) use ($count) { return $x !== $count; });
        
        $index = $this->atomIs('File')
                      ->regexIs('fullcode', '^/language/.+\\\\.php')
                      ->outIs('FILE')
                      ->outIs('EXPRESSION')
                      ->atomIs('Php')
                      ->outIs('CODE')
                      ->outIs('EXPRESSION')
                      ->atomIs('Return')
                      ->outIs('RETURN')
                      ->atomIs('Arrayliteral')
                      ->outIs('ARGUMENT')
                      ->outIs('INDEX')
                      ->codeIs(array_keys($index), self::NO_TRANSLATE)
                      ->inIs('INDEX');
        $res = $this->prepareQuery();
    }
}

?>
