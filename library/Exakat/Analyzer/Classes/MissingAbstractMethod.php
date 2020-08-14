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

class MissingAbstractMethod extends Analyzer {
    public function analyze(): void {
        // abstract class x { abstract function foo() {}}
        // class y extends x { NO function foo() {}}
        $this->atomIs(self::CLASSES_ALL)
             ->isNot('abstract', true)
             ->goToAllParents(self::EXCLUDE_SELF)
             ->atomIs(self::CLASSES_ALL)
             ->is('abstract', true)
             ->outIs(self::CLASS_METHODS)
             ->as('results')
             ->is('abstract', true)
             ->outIs('NAME')
             ->savePropertyAs('lccode', 'name')

             ->back('first')
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->atomIs(self::CLASSES_ALL)
                             ->goToAllParentsTraits(self::INCLUDE_SELF)
                             ->outIs(self::CLASS_METHODS)
                             ->isNot('abstract', true)
                             ->outIs('NAME')
                             ->samePropertyAs('lccode', 'name', self::CASE_INSENSITIVE)
                     )
             )

             ->back('results');
        $this->prepareQuery();

        // trait x { abstract function foo() {}}
        // class y { use x;  NO function foo() {}}
        $this->atomIs(self::CLASSES_ALL)
             ->GoToAllParentsTraits(self::EXCLUDE_SELF)
             ->atomIs('Trait')
             ->outIs(self::CLASS_METHODS)
             ->as('results')
             ->is('abstract', true)
             ->outIs('NAME')
             ->savePropertyAs('lccode', 'name')

             ->back('first')
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->outIs(self::CLASS_METHODS)
                             ->outIs('NAME')
                             ->samePropertyAs('lccode', 'name', self::CASE_INSENSITIVE)
                     )
             )

             ->back('results');
        $this->prepareQuery();
    }
}

?>
