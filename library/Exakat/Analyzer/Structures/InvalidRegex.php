<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
use Exakat\Analyzer\Structures\UnknownPregOption;

class InvalidRegex extends Analyzer {
    public function analyze() {
        $functionList = '"'.implode('", "', array_map( 'addslashes', UnknownPregOption::$functions)).'"';
        $regexQuery = <<<GREMLIN
g.V().hasLabel("Functioncall").has("fullnspath", within($functionList))
                              .out("ARGUMENT")
                              .has("rank", 0)
                              .hasLabel("Concatenation", "String")
                              .values("fullcode")
                              .unique();
GREMLIN;
        $regex = $this->query($regexQuery);
        
        $invalid = array();
        foreach($regex as $r) {
            $localRegex = preg_replace('/(["\']) . \$[a-zA-Z0-9]+ . \1/', 'xxx', trim($r, '\'"'));
            if (false === @preg_match($localRegex, '')) {
                $invalid[] = $r;
            }
        }
        
        if (empty($invalid)) {
            return;
        }
        
        $this->atomFunctionIs(UnknownPregOption::$functions)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs(array('String', 'Concatenation'))
             ->fullcodeIs($invalid)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
