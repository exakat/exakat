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

class PropertyUsedAbove extends Analyzer {
    public function analyze() {
        //////////////////////////////////////////////////////////////////
        // property + $this->property
        //////////////////////////////////////////////////////////////////
        $this->atomIs('Ppp')
             ->isNot('virtual', true)
             ->isNot('static', true)
             ->outIs('PPP')
             ->_as('ppp')
             ->savePropertyAs('propertyname', 'name')
             ->goToClass()
             ->filter(
                $this->side()
                     ->goToAllParents(self::EXCLUDE_SELF)
                     ->filter(
                       $this->side()
                            ->outIs(array('METHOD', 'MAGICMETHOD'))
                            ->outIs('BLOCK')
                            ->atomInsideNoDefinition('Member')
                            ->outIs('OBJECT')
                            ->atomIs('This')
                            ->inIs('OBJECT')
                            ->outIs('MEMBER')
                            ->tokenIs('T_STRING')
                            ->samePropertyAs('code', 'name', self::CASE_SENSITIVE)
                      )
             )
             ->back('ppp');
        $this->prepareQuery();

        //////////////////////////////////////////////////////////////////
        // static property : inside the self class
        //////////////////////////////////////////////////////////////////
        $this->atomIs('Ppp')
             ->isNot('virtual', true)
             ->is('static', true)
             ->outIs('PPP')
             ->_as('ppp')
             ->savePropertyAs('code', 'property')
             ->goToClass()
             ->filter(
                $this->side()
                     ->goToAllParents(self::EXCLUDE_SELF)
                     ->filter(
                       $this->side()
                            ->outIs(array('METHOD', 'MAGICMETHOD'))
                            ->outIs('BLOCK')
                            ->atomInsideNoDefinition('Staticproperty')
                            ->outIs('MEMBER') // Can't test on fnp, it is family, not exact match
                            ->tokenIs('T_VARIABLE')
                            ->samePropertyAs('code', 'property', self::CASE_SENSITIVE)
                      )
             )
             ->back('ppp');
        $this->prepareQuery();
        
        // This could be also checking for fnp : it needs to be a 'family' class check.
    }
}

?>
