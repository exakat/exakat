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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class PrintfArguments extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/PropagateConstants',
                    );
    }

    public function analyze(): void {
        //The %2$s contains %1$04d monkeys
        //The %02s contains %-'.3d monkeys

        $countParameters = <<<REGEX
    d2 = it.get().value("fullcode").toString().findAll("(?<!%)%(?:\\\d+\\\\\\$)?[+-]?(?:[ 0-9\']*\\\.\\\d+)?(?:\\\d\\\d)?[bcdeEufFgGosxX]"); 
    d = [:];
    d2.each{
        x = it =~ "^%(\\\\d+)\\\\\\$"; 
        if (x.asBoolean() == true) {
            d[x[0][1]] = x[0][0];
        } else {
            d[d.size()] = it;
        }
    }
REGEX;

        // printf(' a %s %s', $a1, ...$a2);
        $this->atomFunctionIs(array('\\printf', '\\sprintf'))
             ->savePropertyAs('count', 'c')
             ->filter(
                $this->side()
                     ->outIs('ARGUMENT')
                     ->is('variadic', true)
             )
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String', self::WITH_CONSTANTS)
             ->hasNoOut('CONCAT')
             // Count the number of ...variadic
             //(?:[ 0]|\'.{1})?-?\\\d*%(?:\\\.\\\d+)?
             ->raw(<<<GREMLIN
filter{
$countParameters
    c - 1 > d.size();
}
GREMLIN
)
             ->back('first');
        $this->prepareQuery();

        // printf(' a %s ', $a1, $a2);
        $this->atomFunctionIs(array('\\printf', '\\sprintf'))
             ->savePropertyAs('count', 'c')
             ->not(
                $this->side()
                     ->outIs('ARGUMENT')
                     ->is('variadic', true)
             )
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String', self::WITH_CONSTANTS)
             ->hasNoOut('CONCAT')
             // Count the number of ...variadic
             //(?:[ 0]|\'.{1})?-?\\\d*%(?:\\\.\\\d+)?
             ->raw(<<<GREMLIN
filter{
$countParameters
    c - 1 != d.size();
}
GREMLIN
)
             ->back('first');
        $this->prepareQuery();

        // vsprintf(' a %s ',array( $a1, $a2));
        $this->atomFunctionIs('\\vsprintf')
             ->outWithRank('ARGUMENT', 1)
             ->atomIs('Arrayliteral', self::WITH_CONSTANTS)
             ->savePropertyAs('count', 'c')
             ->back('first')

             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String', self::WITH_CONSTANTS)
             ->hasNoOut('CONCAT')
             //(?:[ 0]|\'.{1})?-?\\\d*%(?:\\\.\\\d+)?
             // + 0 is silly but actually works. :(
             ->raw(<<<GREMLIN
filter{
$countParameters
    c + 0 != d.size();
}
GREMLIN
)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
