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
        if (func_num_args() === 0) {
            $out = self::$linksDown;
        } else {
            list($out) = func_get_args();

            $this->assertLink($out);
            $out = $this->normalizeLinks($out);

            if (empty($out)) {
                return new Command(Query::STOP_QUERY);
            }
            
            $out = makeList($out);
        }

        return new Command(<<<GREMLIN
 out({$out})
.repeat( 
    __.coalesce(__.hasLabel("Parenthesis").out("CODE"), 
                __.hasLabel("Assignation").out("RIGHT"), 
                __.filter{true})
      )
.until(__.not(hasLabel("Parenthesis", "Assignation")))
GREMLIN
);
    }
}
?>
