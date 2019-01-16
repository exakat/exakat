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
namespace Exakat\Analyzer\Exceptions;

use Exakat\Analyzer\Analyzer;

class UncaughtExceptions extends Analyzer {
    public function dependsOn() {
        return array('Exceptions/CaughtExceptions',
                     'Exceptions/DefinedExceptions',
                    );
    }
    
    public function analyze() {
        $caughtDirect = $this->query('g.V().hasLabel("Catch").out("CLASS").values("fullnspath").unique()');
        $caught = $caughtDirect->toArray();

        if (empty($caught)) {
            // All of them are uncaught then
            $this->atomIs('Throw')
                 ->outIs('THROW');
            $this->prepareQuery();
        } else {
            $this->atomIs('Throw')
                 ->outIs('THROW')
                 ->atomIs('New')
                 ->outIs('NEW')
                 ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
                 ->has('fullnspath')
                 ->not(
                    // Check if any parent is catchable
                    $this->side()
                         ->filter(
                             $this->side()
                                  ->inIs('DEFINITION')
                                  ->gotoAllParents(self::INCLUDE_SELF)
                                  ->fullnspathIs($caught)
                         )
                 )
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
