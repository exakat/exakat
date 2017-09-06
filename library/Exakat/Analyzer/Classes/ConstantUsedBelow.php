<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class ConstantUsedBelow extends Analyzer {
    protected $phpVersion = '7.1+';
    
    public function analyze() {
        //////////////////////////////////////////////////////////////////
        // constant + CLASS::constant (no check on class itself)
        //////////////////////////////////////////////////////////////////
        $this->atomIs('Constant')
             ->outIs('NAME')
             ->savePropertyAs('code', 'constname')
             ->inIs('NAME')
             ->inIs('CONST')
             ->inIs('CONST') // class
             ->savePropertyAs('fullnspath', 'classpath')
             ->raw('where( __.repeat( __.out("DEFINITION").in("EXTENDS") ).emit().times('.self::MAX_LOOPING.')
                             .where( __.out("METHOD").out("BLOCK")
                                       .repeat( __.out('.$this->linksDown.')).emit().times('.self::MAX_LOOPING.').hasLabel("Staticconstant")
                                       .out("CONSTANT").hasLabel("Name").filter{ it.get().value("code") == constname}
                              )
                             .count().is(neq(0)) )')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
