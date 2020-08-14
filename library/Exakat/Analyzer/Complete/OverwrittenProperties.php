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

namespace Exakat\Analyzer\Complete;

class OverwrittenProperties extends Complete {
    public function analyze(): void {
        // class x { protected $p = 1;}
        // class xx extends x { protected $p = 1;}
        $this->atomIs(array('Propertydefinition', 'Virtualproperty'), self::WITHOUT_CONSTANTS)
              ->savePropertyAs('propertyname', 'name')
              ->inIs('PPP')
              ->inIs('PPP')
              ->atomIs(self::CLASSES_TRAITS)
              ->goToAllParentsTraits(self::INCLUDE_SELF) // also covers local traits
              ->outIs('PPP')
              ->outIs('PPP')
              ->atomIs(array('Propertydefinition', 'Virtualproperty'), self::WITHOUT_CONSTANTS)
              ->samePropertyAs('propertyname', 'name',  self::CASE_SENSITIVE)
              ->distinctFrom('first')
              ->as('origin')
              ->dedup(array('first', 'origin'))
              ->addEFrom('OVERWRITE', 'first');
        $this->prepareQuery();

        // synch properties/virtual properties visibility
        $this->atomIs('Propertydefinition', self::WITHOUT_CONSTANTS)
              ->inIs('PPP')
              ->is('visibility', 'protected')
              ->back('first')
              ->inIs('OVERWRITE')
              ->atomIs('Virtualproperty')
              ->inIs('PPP')
              ->raw(<<<'GREMLIN'
 property("visibility", "protected")
.sideEffect{ it.get().property("fullcode", it.get().property("fullcode").value().toString().replace("public ", "protected "));}
GREMLIN
);
        $this->prepareQuery();
    }
}

?>
