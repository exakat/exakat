<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Report\Content;

class ComposerList extends \Report\Content {
    protected $name = 'Composer';

    public function collect() {
        $res = gremlin_query(<<<GREMLIN
g.idx("analyzers")[["analyzer":"Analyzer\\\\Composer\\\\IsComposerNsname"]].out
    .sideEffect{c = it;}
    .in.loop(1){true}{it.object.atom == 'File'}
    .sideEffect{file = it;}
    .transform{["code":c.fullnspath + ' (alias ' + c.fullcode + ")", "file":file.fullcode, "line":c.line];}

GREMLIN
);

        if (empty($res->results)) {
            $this->array = [];
            $this->hasResults = false;

            return;
        }

        foreach($res->results as &$r) {
            $r = (array) $r;
        }
        unset($r);
        
        $this->array = $res->results;
   }
}

?>
