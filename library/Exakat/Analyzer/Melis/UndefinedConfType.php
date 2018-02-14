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

class UndefinedConfType extends Analyzer {
    /*
    'conf' => array(
        'type' => 'a/b/c' in plugin
    )
    */
    public function analyze() {
        $this->atomIs('String')
             ->noDelimiterIs('interface')
             ->inIs('INDEX')
             ->atomIs('Keyvalue')
             ->raw(<<<GREMLIN
where(
   __.sideEffect{ x = []; }
     .repeat( 
   __.where( __.out('INDEX'). sideEffect{ x.add(it.get().value('noDelimiter')) } )
     .in('ARGUMENT').hasLabel('Arrayliteral').in('VALUE').hasLabel('Keyvalue')
     .where( __.out('INDEX'). sideEffect{ x.add(it.get().value('noDelimiter')); } )
     .in('ARGUMENT').hasLabel('Arrayliteral').in('VALUE').hasLabel('Keyvalue')
     ).until( __.out('INDEX').has('noDelimiter', 'plugins') )
    )
    .map{ "/" + x.reverse().dropRight(1).join('/');}
GREMLIN
);
        $confTypes = $this->rawQuery()->toArray();

        $this->atomIs('String')
             ->noDelimiterIs('conf')
             ->inIs('INDEX')
             ->outIs('VALUE')
             ->atomIs('Arrayliteral')
             ->outIs('ARGUMENT')
             ->outIs('INDEX')
             ->noDelimiterIs('type')
             ->inIs('INDEX')
             ->outIs('VALUE')
             ->atomIs('String')
             ->noDelimiterIsNot($confTypes);
        $this->prepareQuery();
    }
}

?>
