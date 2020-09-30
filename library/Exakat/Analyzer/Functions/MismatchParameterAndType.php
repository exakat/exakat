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

namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class MismatchParameterAndType extends Analyzer {
    public function analyze(): void {
        $names = array('int'     => array('\\int'),
                       'integer' => array('\\int'),
                       'bigint'  => array('\\int'),

                       'float'   => array('\\float'),
                       'double'  => array('\\float'),
                       'real'    => array('\\float'),

                       'array'   => array('\\array'),

                       'bool'    => array('\\bool'),
                       'boolean' => array('\\bool'),

                       'str'     => array('\\string'),
                       'string'  => array('\\string'),
                       'message' => array('\\string'),
                       'msg'     => array('\\string'),
                       'string'  => array('\\string'),
                       'text'    => array('\\string'),
                       'txt'     => array('\\string'),
                       );

        // function foo(string $int) {}
        $this->atomIs('Parameter')
             ->outIs('NAME')
             // Get name, remove $, lowercase
             ->raw('sideEffect{ name = it.get().value("fullcode").replace("\$", "").toLowerCase();}')
             ->raw('filter{ name in ***; }', array_keys($names))
             ->back('first')
             ->outIs('TYPEHINT')
             ->atomIsNot(array('Null', 'Void'))
             ->atomIs('Scalartypehint')
             ->isNotHash('fullnspath', $names, 'name')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
