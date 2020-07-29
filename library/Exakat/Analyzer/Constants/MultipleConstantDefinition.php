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


namespace Exakat\Analyzer\Constants;

use Exakat\Analyzer\Analyzer;

class MultipleConstantDefinition extends Analyzer {
    public function analyze() : void {
        // case-insensitive constants with Define
        // Search for definitions and count them
        //define()
        $this->atomIs('Defineconstant')
             ->raw('or( __.out("CASE").count().is(eq(0)),
          __.out("CASE").has("boolean", false))')
             ->outIs('NAME')
             ->atomis(self::STATIC_NAMES)
             ->values('noDelimiter');
        $csDefinitions = $this->rawQuery()->toArray();

        //const
        $this->atomIs('Const')
             ->hasNoClassTrait()
             ->outIs('CONST')
             ->outIs('NAME')
             ->values('fullcode');
        $constDefinitions = $this->rawQuery()->toArray();

        //define(, , true)
        $this->atomIs('Defineconstant')
             ->outIs('CASE')
             ->is('boolean', true)
             ->back('first')
             ->outIs('NAME')
             ->atomis(self::STATIC_NAMES)
             ->values('noDelimiter');
        $cisDefinitions = $this->rawQuery()->toArray();
        $cisDefinitions = array_map('strtolower', $cisDefinitions);

        if ($a = $this->selfCollisions($cisDefinitions)) {
            $this->applyToCisDefine($a);
        }

        if ($a = $this->selfCollisions(array_merge($constDefinitions, $csDefinitions))) {
            $this->applyToConst(array_intersect($a, $constDefinitions));
            $this->applyToCsDefine(array_intersect($a, $csDefinitions));
        }

        if ($a = $this->CsCisCollisions($csDefinitions, $cisDefinitions)) {
            $this->applyToCisDefine($a);
            $this->applyToCsDefine($a);
        }

        if ($a = $this->CsCisCollisions($constDefinitions, $cisDefinitions)) {
            $this->applyToCisDefine($a);
            $this->applyToConst($a);
        }
    }

    private function selfCollisions($array) {
        // two definitions are case sensitive
        return array_keys(array_filter(array_count_values($array), function ($x) { return $x > 1; }));
    }

    private function CsCisCollisions($csDefinitions, $cisDefinitions) {
        return array_merge( array_intersect($csDefinitions, $cisDefinitions),
                            array_intersect($csDefinitions, array_map(function ($x) { return strtoupper($x); }, $cisDefinitions) ) );
    }

    private function applyToCisDefine($array) {
        if (empty($array)) {
            return;
        }
        $array = array_values($array);

        $this->atomIs('Defineconstant')
             ->outIs('CASE')
             ->is('boolean', true)
             ->inIs('CASE')
             ->outIs('NAME')
             ->atomIs('Identifier')
             ->hasNoOut('CONCAT')
             ->noDelimiterIs($array);
        $this->prepareQuery();
    }

    private function applyToCsDefine($array) {
        if (empty($array)) {
            return;
        }
        $array = array_values($array);

        $this->atomIs('Defineconstant')
             ->outIs('CASE')
             ->is('boolean', false)
             ->inIs('CASE')
             ->outIs('NAME')
             ->atomIs('Identifier')
             ->hasNoOut('CONCAT')
             ->noDelimiterIs($array);
        $this->prepareQuery();

        $this->atomIs('Defineconstant')
             ->hasNoOut('CASE')
             ->outIs('NAME')
             ->atomIs('Identifier')
             ->hasNoOut('CONCAT')
             ->noDelimiterIs($array);
        $this->prepareQuery();
    }

    private function applyToConst($array) {
        if (empty($array)) {
            return;
        }
        $array = array_values($array);

        $this->atomIs('Const')
             ->hasNoClassTrait()
             ->outIs('CONST')
             ->outIs('NAME')
             ->codeIs($array);
        $this->prepareQuery();
    }

}

?>
