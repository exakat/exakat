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
namespace Exakat\Analyzer\Exceptions;

use Exakat\Analyzer\Analyzer;

class CaughtButNotThrown extends Analyzer {
    public function analyze() {
        // There is a catch() but its class is not defined

        $phpExceptions = $this->loadIni('php_exception.ini', 'classes');
        
        $thrown1 = $this->query('g.V().hasLabel("Throw").out("THROW").out("NEW").values("fullnspath").unique()')
                        ->toArray();

        $thrown2 = $this->query('g.V().hasLabel("Throw").out("THROW").out("NEW").in("DEFINITION")
                                     .repeat( out("EXTENDS").in("DEFINITION") ).emit().times('.self::MAX_LOOPING.').values("fullnspath").unique()')
                        ->toArray();
        $thrown = array_merge(array('\\throwable'), $thrown1, $thrown2);
        
        $this->atomIs('Catch')
             ->outIs('CLASS')
             ->fullnspathIsNot(makeFullNsPath($phpExceptions))
             ->fullnspathIsNot($thrown);
        $this->prepareQuery();
    }
}

?>
