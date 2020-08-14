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

class NonPpp extends Analyzer {
    public function analyze(): void {
        // class x { function foo() {} }
        // trait x { static $foo; }
        $this->atomIs(self::CIT)
             ->outIs(self::CLASS_ELEMENTS)
             ->atomIs(array('Method', 'Magicmethod', 'Ppp', 'Constant'))
             ->not(
                $this->side()
                     ->outIs('PPP')
                     ->atomIs('Virtualproperty')
             )
             ->is('visibility', 'none')
             ;
        $this->prepareQuery();
    }
}

?>
