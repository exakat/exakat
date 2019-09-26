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

class NoPublicAccess extends Analyzer {
    public function analyze() {

        $this->atomIs('Member')
             ->not(
                $this->side()
                     ->outIs('OBJECT')
                     ->atomIs('This')
             )
             ->outIs('MEMBER')
             ->atomIs('Name')
             ->values('code')
             ->unique();
        $properties = $this->rawQuery()->toArray();

        if(!empty($properties)) {
            $properties = array_values($properties);
            $this->atomIs('Ppp')
                 ->is('visibility', 'public')
                 ->isNot('static', true)
                 ->outIs('PPP')
                 ->_as('ppp')
                 ->isNot('propertyname', $properties)
                 ->back('ppp');
            $this->prepareQuery();
        }

        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->atomIsNot(array('Self', 'Static'))
             ->savePropertyAs('fullnspath', 'fnp')
             ->inIs('CLASS')

             ->outIs('MEMBER')
             ->atomIs('Staticpropertyname')
             ->raw('map{ full = fnp + "::" + it.get().value("code"); }')
             ->unique();
        $staticproperties = $this->rawQuery()->toArray();

        if (!empty($staticproperties)) {
            $staticproperties = array_values($staticproperties);
            $this->atomIs('Ppp')
                 ->is('visibility', 'public')
                 ->is('static', true)
                 ->inIs('PPP')
                 ->savePropertyAs('fullnspath', 'fnp')
                 ->back('first')
                 ->outIs('PPP')
                 ->_as('results')
                 ->raw('filter{ !(fnp + "::" + it.get().value("code") in ***) }', $staticproperties)
                 ->back('results');
            $this->prepareQuery();
        }
    }
}

?>
