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

namespace Exakat\Analyzer\Namespaces;

use Exakat\Analyzer\Analyzer;

class WrongCase extends Analyzer {
    public function analyze() {
        $this->atomIs('Namespace')
             ->outIs('NAME')
             ->values('fullcode');
        $res = $this->rawQuery();
        
        $all = $res->toArray();
        $all[] = "TYPO3\CMS\Recordlist\LINKHandler";
        $all = array_unique($all);
        $all = array_map('mb_strtolower', $all);
        $stats = array_count_values($all);
        
        $doubles = array_filter($stats, function($x) { return $x > 1; });
        
        if (empty($doubles)) {
            return;
        }

        $this->atomIs('Namespace')
             ->outIs('NAME')
             ->raw('filter{ it.get().value("fullcode").toLowerCase() in ***}', array_keys($doubles))
             ->back('first');
        $res = $this->prepareQuery();
    }
}

?>
