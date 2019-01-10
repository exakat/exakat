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

class CouldBePrivate extends Analyzer {
    public function dependsOn() {
        return array('Classes/PropertyUsedBelow',
                    );
    }
    
    public function analyze() {
        // Searching for properties that are never used outside the definition class or its children

        // Non-static properties
        // Case of object->property (that's another public access)
        $this->atomIs('Member')
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->outIs('OBJECT')
                             ->atomIs('This')
                     )
             )
             ->outIs('MEMBER')
             ->atomIs('Name')
             ->values('code')
             ->unique();
        $publicProperties = $this->rawQuery()
                                 ->toArray();

        if (!empty($publicProperties)) {
            $this->atomIs('Ppp')
                 ->isNot('visibility', 'private')
                 ->isNot('static', true)
                 ->outIs('PPP')
                 ->analyzerIsNot('Classes/PropertyUsedBelow')
                 ->isNot('propertyname', $publicProperties);
            $this->prepareQuery();
        }

        // Static properties
        // Case of property::property (that's another public access)
        $this->atomIs('Staticproperty')
             ->hasNoClass()
             ->outIs('CLASS')
             ->_as('classe')
             ->has('fullnspath')
             ->back('first')
             ->outIs('MEMBER')
             ->_as('property')
             ->raw('select("classe", "property").by("fullnspath").by("code").unique()');
        $nakedPublicStaticProperties = $this->rawQuery()
                                            ->toArray();

        $this->atomIs('Staticproperty')
             ->inIs('DEFINITION')
             ->inIs('PPP')
             ->inIs('PPP')
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')
             ->outIs('MEMBER')
             ->_as('property')
             ->goToClass()
             ->_as('classe')
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->goToAllParentsTraits(self::INCLUDE_SELF)
                             ->samePropertyAs('fullnspath', 'fnp')
                     )
             )
             ->raw('select("classe", "property").by("fullnspath").by("code").unique()');
        $insidePublicStaticProperties = $this->rawQuery()
                                             ->toArray();
        $publicStaticProperties = array_merge($insidePublicStaticProperties, $nakedPublicStaticProperties);

        // Empty $publicStaticProperties also matters
        $calls = array();
        foreach($publicStaticProperties as $value) {
            if (isset($calls[$value['property']])) {
                $calls[$value['property']][] = $value['classe'];
            } else {
                $calls[$value['property']] = array($value['classe']);
            }
        }
        
        // Property that is not used outside this class or its children
        $this->atomIs('Ppp')
             ->isNot('visibility', 'private')
             ->is('static', true)
             ->outIs('PPP')
             ->analyzerIsNot('Classes/PropertyUsedBelow')
             ->_as('results')
             ->codeIsNot(array_keys($calls), self::NO_TRANSLATE, self::CASE_SENSITIVE)
             ->savePropertyAs('code', 'variable')
             ->goToClass()
             ->isNotHash('fullnspath', $calls, 'variable')
             ->back('results');
        $this->prepareQuery();

    }
}

?>
