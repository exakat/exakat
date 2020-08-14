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


namespace Exakat\Query\DSL;

use Exakat\Query\Query;

class FollowParAs extends DSL {
    const FOLLOW_ALL        = 0;
    const FOLLOW_NONE       = 1;
    const FOLLOW_PARAS_ONLY = 2;

    public function run(): Command {

        assert(func_num_args() === 1, 'Wrong number of arguments for ' . self::class);
        list($out) = func_get_args();

        switch($out) {
            case self::FOLLOW_ALL:
                $out    = 'out(' . self::$linksDown . ').';
                $labels = '';
                $follow = '';
                break 1;

            case self::FOLLOW_NONE:
                $out    = 'identity().';
                $labels = '';
                $follow = '';
                break 1;

            case self::FOLLOW_PARAS_ONLY:
                $out    = 'identity().';
                $labels = '';
                $follow = '';
                break 1;

            default:
                $this->assertLink($out);
                $out = $this->normalizeLinks($out);

                if (empty($out)) {
                    return new Command(Query::STOP_QUERY);
                }

                $out = 'out(' . makeList($out) . ').';
                $labels = ', "Ternary", "Coalesce"';
                $follow = ', 
                __.hasLabel("Ternary").where(__.out("THEN").not(hasLabel("Void"))).out("THEN", "ELSE"), 
                __.hasLabel("Ternary").where(__.out("THEN").    hasLabel("Void" )).out("CONDITION", "ELSE"), 
                __.hasLabel("Coalesce").out("RIGHT", "LEFT")';
        }

        $TIME_LIMIT = self::$TIME_LIMIT;
        return new Command(<<<GREMLIN
 {$out}emit().repeat( 
    __.timeLimit($TIME_LIMIT)
      .coalesce(__.hasLabel("Parenthesis").out("CODE"), 
                __.hasLabel("Assignation").out("RIGHT")$follow
      )
).until(__.not(hasLabel("Parenthesis", "Assignation", $labels)))
.not(hasLabel("Parenthesis" $labels))
.not(hasLabel("Assignation").has("token", "T_EQUAL"))
GREMLIN
);
    }
}
?>
