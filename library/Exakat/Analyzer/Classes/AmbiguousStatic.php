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

class AmbiguousStatic extends Analyzer {
    public function analyze(): void {
        // Methods with the same name, but with static or not.
        $this->atomIs('Method')
             ->is('static', true)
             ->outIs('NAME')
             ->values('lccode')
             ->unique();
        $staticMethod = $this->rawQuery()->toArray();

        // Global are unused if used only once
        $this->atomIs('Method')
             ->isNot('static', true)
             ->outIs('NAME')
             ->values('lccode')
             ->unique();
        $normalMethod = $this->rawQuery()->toArray();

        $mixedMethod = array_values(array_intersect($normalMethod, $staticMethod));

        if (!empty($mixedMethod)){
            $this->atomIs('Method')
                 ->outIs('NAME')
                 ->codeIs($mixedMethod, self::NO_TRANSLATE, self::CASE_INSENSITIVE)
                 ->back('first');
            $this->prepareQuery();
        }

        // Properties with the same name, but with static or not.
        // Just like methods, they are case-insensitive, because static $X and $x are still ambiguous
        $this->atomIs('Ppp')
             ->is('static', true)
             ->outIs('PPP')
             ->values('code')
             ->unique();
        $staticProperty = $this->rawQuery()->toArray();

        $this->atomIs('Ppp')
             ->isNot('static', true)
             ->outIs('PPP')
             ->values('code')
             ->unique();
        $normalProperty = $this->rawQuery()->toArray();

        $mixedProperty = array_values(array_intersect($normalProperty, $staticProperty));

        if (!empty($mixedProperty)){
            $this->atomIs('Propertydefinition')
                 ->codeIs($mixedProperty, self::NO_TRANSLATE, self::CASE_SENSITIVE)
                 ->inIs('PPP');
            $this->prepareQuery();
        }
    }
}

?>
