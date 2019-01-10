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

namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class PropertyCouldBeLocal extends Analyzer {
    public function analyze() {
        // normal property
        $this->atomIs('Propertydefinition')
             ->savePropertyAs('propertyname', 'member')
             ->inIs('PPP')
             ->isNot('static', true)
             ->is('visibility', 'private')
             ->inIs('PPP')
             ->filter(
                  $this->side()
                       ->outIs('METHOD')
                       ->not( $this->side()
                                   ->is('static', true)
                            )
                       ->filter(
                            $this->side()
                                 ->outIs('BLOCK')
                                 ->atomInsideNoDefinition('Member')
                                 ->filter( $this->side()
                                                ->outIs('OBJECT')
                                                ->atomIs('This')
                                         )
                                 ->outIs('MEMBER')
                                 ->samePropertyAs('code', 'member')
                       )
                       ->count()
                       ->isEqual(1)
            )
            ->back('first');
        $this->prepareQuery();

        // static property
        $this->atomIs('Propertydefinition')
             ->savePropertyAs('code', 'member')
             ->inIs('PPP')
             ->is('static', true)
             ->is('visibility', 'private')
             ->inIs('PPP')
             ->savePropertyAs('fullnspath', 'fnp')
             ->filter(
                $this->side()
                     ->outIs('METHOD')
                     ->filter(
                        $this->side()
                             ->atomInsideNoDefinition('Staticproperty')
                             ->outIs('CLASS')
                             ->has('fullnspath')
                             ->samePropertyAs('fullnspath', 'fnp')
                             ->inIs('CLASS')
                             ->outIs('MEMBER')
                             ->samePropertyAs('code', 'member')
                     )
                     ->count()
                     ->isEqual(1)
             )
            ->back('first');
        $this->prepareQuery();
    }
}

?>
