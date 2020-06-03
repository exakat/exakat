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

class PrefixToType extends Analyzer {
    protected $prefixedType = array('is'   => '\\bool',
                                    'has'  => '\\bool',
                                    //'get'  => '\\bool', Anything, really
                                    //'find' => '\\bool',
                                    'set'  => '\\bool',
                                    'list' => '\\array',
                                    );

    protected $suffixedType = array('list'         => '\\array',
                                    'int'          => '\\int',
                                    'name'         => '\\string',
                                    'description'  => '\\string',
                                    'id'           => '\\int',
                                    'uuid'         => '\\Uuid',
                                    );

    public function analyze() {

        // Prefixes : isPath() : is => bool
        foreach($this->prefixedType as $prefix => $type) {
            $this->atomIs(self::FUNCTIONS_METHOD)
                 ->outIs('NAME')
                 ->regexIs('fullcode', '(?i)^' . $prefix)
                 ->back('first')
                 ->not(
                    $this->side()
                         ->outIs('RETURNTYPE')
                         ->atomIs('Scalartypehint')
                         ->fullnspathIs(makeFullnspath(str2array($type)))
                 )
                 ->back('first');
            $this->prepareQuery();
        }

        // Suffices : getId() : Id => int
        foreach($this->suffixedType as $suffix => $type) {
            $this->atomIs(self::FUNCTIONS_METHOD)
                 ->outIs('NAME')
                 ->regexIs('fullcode', '(?i)' . $suffix . '\\$')
                 ->back('first')
                 ->not(
                    $this->side()
                         ->outIs('RETURNTYPE')
                         ->atomIs('Scalartypehint')
                         ->fullnspathIs(makeFullnspath(str2array($type)))
                 )
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
