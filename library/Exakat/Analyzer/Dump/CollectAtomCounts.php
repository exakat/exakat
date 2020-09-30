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

namespace Exakat\Analyzer\Dump;

#use Exakat\Analyzer\Analyzer;

class CollectAtomCounts extends AnalyzerTable {
    protected $analyzerName = 'atomsCounts';

    protected $analyzerTable = 'atomsCounts';

    protected $analyzerSQLTable = <<<'SQL'
CREATE TABLE atomsCounts (id INTEGER PRIMARY KEY AUTOINCREMENT,
                          atom STRING,
                          count INTEGER
                         )
SQL;


    public function analyze(): void {

        $query = <<<'GREMLIN'
g.V().groupCount("b").by(label).cap("b").select("b").map{ x = []; for(key in it.get().keySet()) { x.add(["atom":key, "count":it.get().getAt(key)]);}; x }[0];
GREMLIN;
        $this->prepareDirectQuery($query);
    }
}

?>
