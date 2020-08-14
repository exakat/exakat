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
use Exakat\Query\DSL\FollowParAs;

class MbstringThirdArg extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/PropagateConstants',
                     'Complete/PropagateCalls',
                    );
    }

    public function analyze(): void {
        $encodings = $this->loadIni('mbstring_encodings.ini', 'encodings');

        $functions = array('\\mb_strrichr',
                           '\\mb_stripos' ,
                           '\\mb_strrpos' ,
                           '\\mb_strstr'  ,
                           '\\mb_stristr' ,
                           '\\mb_strpos'  ,
                           '\\mb_strripos',
                           '\\mb_strrchr' ,
                           '\\mb_strrichr',
                           '\\mb_substr'  ,
        );

        $this->atomFunctionIs($functions)
             ->outWithRank('ARGUMENT', 2)
             ->followParAs(FollowParAs::FOLLOW_NONE)
             ->atomIs(array('String', 'Concatenation'), self::WITH_CONSTANTS)
             ->noDelimiterIs($encodings, self::CASE_INSENSITIVE)
             ->back('first');
        $this->prepareQuery();
    }
}
?>
