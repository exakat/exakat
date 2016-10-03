<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Structures;

use Analyzer;

class UnusedLabel extends Analyzer\Analyzer {
    public function analyze() {
        $linksDown = \Tokenizer\Token::linksAsList();

        // inside functions
        $this->atomIs('Label')
             ->outIs('LABEL')
             ->savePropertyAs('code', 'name')
             ->goToFunction()
             ->raw('where( __.out("BLOCK").repeat( __.out()).emit( hasLabel("Goto") ).times('.self::MAX_LOOPING.').out("GOTO").filter{ it.get().value("code") == name}.count().is(eq(0)) )')
             ->back('first');
        $this->prepareQuery();

        // inside namespaces are not processed here.

        // in the global space
        $this->atomIs('Label')
             ->outIs('LABEL')
             ->savePropertyAs('code', 'name')
             ->hasNoFunction()
             ->raw('where( g.V().hasLabel("Goto").out("GOTO").filter{ it.get().value("code") == name}
                            .where( repeat(__.in('.$linksDown.'))
                                    .until(hasLabel("File")).emit()
                                    .hasLabel("Function").count().is(eq(0)) 
                            ).count().is(eq(0)) )')
             ->back('first');
        $this->prepareQuery();

    }
}

?>
