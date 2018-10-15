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

class IsZero extends Analyzer {
    public function analyze() {
        // $a = $c - $c;
        // $a = $c + $d - $c;
        // $a = $c + $d -$e - $c;
        // $a = $d + $c -$e - $c;
        $minus = $this->dictCode->translate('-');
        
        $MAX_LOOPING = self::MAX_LOOPING;
        $follow = 'coalesce( __.hasLabel("Parenthesis").out("CODE"), __.hasLabel("Assignation").out("RIGHT"), __.filter{ true; })';
        $follow .= ".$follow";

        if (!empty($minus)) {
            $this->atomIs('Addition')
                 ->raw('not( where( __.in("RIGHT").hasLabel("Addition").has("token", "T_MINUS")) )')
                 ->outIs('LEFT')
                 ->atomIsNot('Sign')
                 ->savePropertyAs('fullcode', 'operand')
                 ->back('first')
    
                 ->raw(<<<GREMLIN
emit().repeat( __.out("RIGHT").$follow.hasLabel("Addition") ).times($MAX_LOOPING)
      .coalesce( __.filter{ it.get().value("code") in ***}.out("RIGHT").$follow.hasLabel("Addition").out("LEFT").$follow,
                 __.filter{ it.get().value("code") in ***}.out("RIGHT").$follow)
GREMLIN
, $minus, $minus)
                 ->samePropertyAs('fullcode', 'operand', self::CASE_SENSITIVE)
                 ->back('first');
            $this->prepareQuery();
        }

        $plus = $this->dictCode->translate('+');
        if (!empty($plus)) {
            $this->atomIs('Addition')
                 ->outIs('LEFT')
                 ->atomIs('Sign')
                 ->outIs('SIGN')
                 ->savePropertyAs('fullcode', 'operand')
                 ->back('first')
    
                 ->raw(<<<GREMLIN
emit().repeat( __.out("RIGHT").$follow.hasLabel("Addition") ).times($MAX_LOOPING)
      .coalesce( __.filter{ it.get().value("code") in ***}.out("RIGHT").$follow.hasLabel("Addition").out("LEFT").$follow,
                 __.filter{ it.get().value("code") in ***}.out("RIGHT").$follow)
GREMLIN
, $plus, $plus)
                 ->samePropertyAs('fullcode', 'operand', self::CASE_SENSITIVE)
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
