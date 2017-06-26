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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;
use Exakat\Tokenizer\Token;

class UnusedLabel extends Analyzer {
    public function analyze() {
        // inside functions
        $this->atomIs('Gotolabel')
             ->outIs('GOTOLABEL')
             ->savePropertyAs('code', 'name')
             ->goToFunction()
             ->raw('not( where( __.out("BLOCK").repeat( __.out('.$this->linksDown.')).emit( hasLabel("Goto") ).times('.self::MAX_LOOPING.').out("GOTO").filter{ it.get().value("code") == name} ) )')
             ->back('first');
        $this->prepareQuery();

        // inside namespaces are not processed here.

        // in the global space
        $this->atomIs('Gotolabel')
             ->outIs('GOTOLABEL')
             ->savePropertyAs('code', 'name')
             ->hasNoFunction()
             ->raw('not( where( g.V().hasLabel("Goto").out("GOTO").filter{ it.get().value("code") == name}
                            .not( where( repeat(__.in('.$this->linksDown.'))
                                    .until(hasLabel("File")).emit()
                                    .hasLabel("Function") ) 
                            ) ) )')
             ->back('first');
        $this->prepareQuery();

    }
}

?>
