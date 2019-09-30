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


namespace Exakat\Query\DSL;

use Exakat\Query\Query;
use Exakat\Analyzer\Analyzer;

class FollowParAs extends DSL {
    public function run() : Command {
        list($out) = func_get_args();

        if ($out === null) {
            $out = 'out(' . self::$linksDown . ').';
        } elseif (empty($out)) { // To be used in-place
            $out = 'filter{ true; }.';
        } else {
            $this->assertLink($out);
            $out = $this->normalizeLinks($out);

            if (empty($out)) {
                return new Command(Query::STOP_QUERY);
            }
            
            $out = 'out(' . makeList($out) . ').';
        }

        return new Command(<<<GREMLIN
 {$out}repeat( 
    __.coalesce(__.hasLabel("Parenthesis").out("CODE"), 
                __.hasLabel("Assignation").out("RIGHT"), 
                __.hasLabel("Ternary").where(__.out("THEN").not(hasLabel("Void"))).out("THEN", "ELSE"), 
                __.hasLabel("Ternary").where(__.out("THEN").    hasLabel("Void" )).out("CONDITION", "ELSE"), 
                __.hasLabel("Coalesce").out("RIGHT", "LEFT"), 
                __.filter{true})
      )
.until(__.not(hasLabel("Parenthesis", "Assignation", "Ternary", "Coalesce")))
GREMLIN
);
    }
}
?>
