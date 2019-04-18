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
use Exakat\Data\GroupBy;

class CouldBeProtectedProperty extends Analyzer {
    public function analyze() {
        // Case of $object->property (that's another public access)
        $this->atomIs('Member')
             ->not(
                $this->side()
                     ->outIs('OBJECT')
                     ->atomIs('This')
             )
             ->outis('MEMBER')
             ->atomIs('Name')
             ->values('code')
             ->unique();
        $publicProperties = $this->rawQuery()->toArray();
        
        // Member that is not used outside this class or its children
        $this->atomIs('Ppp')
             ->isNot('visibility', array('protected', 'private'))
             ->isNot('static', true)
             ->hasClass()
             ->outIs('PPP')
                 ->atomIsNot('Virtualproperty')
             ->isNot('propertyname', $publicProperties);
        $this->prepareQuery();

        // Case of class::property (that's another public access)
        $this->atomIs('Staticproperty')
             ->_as('init')
             ->outIs('CLASS')
             ->atomIs(array('Identifier', 'Nsname'))
             ->_as('classe')
             ->savePropertyAs('fullnspath', 'fnp')
             ->inIs('CLASS')
             ->hasNoClass()
             ->outIs('MEMBER')
             ->atomIs('Staticpropertyname')
             ->_as('variable')
             ->raw('select("classe", "variable").by("fullnspath").by("code")')
             ->unique();
            $res = $this->rawQuery()->toArray();

        $publicStaticProperties = new GroupBy();
        foreach($res as $value) {
            $publicStaticProperties[$value['classe']] = $value['variable'];
        }
        
        if (!empty($publicStaticProperties)) {
            // Member that is not used outside this class or its children
            $this->atomIs('Ppp')
                 ->isNot('visibility', array('protected', 'private'))
                 ->is('static', true)
                 ->goToClass()
                 ->savePropertyAs('fullnspath', 'fnp')
                 ->back('first')
                 ->outIs('PPP')
                 ->atomIsNot('Virtualproperty')
                 ->isNotHash('code', $publicStaticProperties->toArray(), 'fnp');
            $this->prepareQuery();
        }
    }
}

?>
