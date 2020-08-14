<?php declare(strict_types = 1);
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
    public function dependsOn(): array {
        return array('Classes/PropertyUsedBelow',
                    );
    }

    public function analyze(): void {
        // Searching for properties that are never used outside the definition class or its children

        // Non-static properties
        // Case of object->property (that's another public access)
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
        $publicProperties = $this->rawQuery()
                                 ->toArray();

        if (!empty($publicProperties)) {
            $this->atomIs('Ppp')
                 ->isNot('visibility', 'private')
                 ->isNot('static', true)
                 ->outIs('PPP')
                 ->atomIsNot('Virtualproperty')
                 ->analyzerIsNot('Classes/PropertyUsedBelow')
                 ->isNot('propertyname', $publicProperties);
            $this->prepareQuery();
        }

        // Static properties
        // Case of property::property (that's another public access)
        $this->atomIs('Staticproperty')
             ->hasNoClass()
             ->outIs('CLASS')
             ->as('classe')
             ->has('fullnspath')
             ->back('first')
             ->outIs('MEMBER')
             ->as('property')
             ->select(array('classe'    => 'fullnspath',
                            'property' => 'code'))
             ->unique();
        $nakedPublicStaticProperties = $this->rawQuery()
                                            ->toArray();

        $this->atomIs('Staticproperty')
             ->inIs('DEFINITION')
             ->atomIsNot('Virtualproperty')
             ->inIs('PPP')
             ->inIs('PPP')
             ->savePropertyAs('fullnspath', 'fnp')
             ->back('first')
             ->outIs('MEMBER')
             ->as('property')
             ->goToClass()
             ->as('classe')
             ->not(
                $this->side()
                     ->atomIs(self::CLASSES_ALL)
                     ->goToAllParentsTraits(self::INCLUDE_SELF)
                     ->samePropertyAs('fullnspath', 'fnp')
             )
             ->select(array('classe'    => 'fullnspath',
                            'property'  => 'code'))
             ->unique();
        $insidePublicStaticProperties = $this->rawQuery()
                                             ->toArray();
        $publicStaticProperties = array_merge($insidePublicStaticProperties, $nakedPublicStaticProperties);

        // Empty $publicStaticProperties also matters
        $calls = array();
        foreach($publicStaticProperties as $value) {
            array_collect_by($calls, $value['classe'], $value['property']);
        }

        // Property that is not used outside this class or its children
        $this->atomIs('Ppp')
             ->isNot('visibility', 'private')
             ->is('static', true)

             ->goToClass()
             ->fullnspathis(array_keys($calls))
             ->savePropertyAs('fullnspath', 'fnq')

             ->back('first')
             ->outIs('PPP')
             ->atomIsNot('Virtualproperty')
             ->analyzerIsNot('Classes/PropertyUsedBelow')
             ->as('results')
             ->isNotHash('code', $calls, 'fnq')
             ->back('results');
        $this->prepareQuery();
    }
}

?>
