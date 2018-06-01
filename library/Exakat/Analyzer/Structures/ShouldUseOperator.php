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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class ShouldUseOperator extends Analyzer {
    public function analyze() {
        // array_push($array, $value)
        $this->atomFunctionIs(array('\\array_push',
                                    '\\function_get_arg',
                                    '\\function_get_args',
                                    '\\chr',
                                    '\\call_user_func',
                                    '\\is_null',
                                    ));
        $this->prepareQuery();

        // array_push($array, $value)
        $this->atomFunctionIs(array('\\is_int',
                                    '\\is_object',
                                    '\\is_array',
                                    '\\is_string',
                                    ))
            ->outWithRank('ARGUMENT', 0)
            ->atomIs('Variable')
            ->savePropertyAs('code', 'argument')
            ->goToFunction()
            ->outIs('ARGUMENT')
            ->hasNoOut('TYPEHINT')
            ->outIsIE('NAME')
            ->samePropertyAs('code', 'argument')
            ->back('first');
        $this->prepareQuery();

    }
}

?>
