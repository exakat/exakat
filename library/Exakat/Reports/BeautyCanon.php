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

namespace Exakat\Reports;


class BeautyCanon extends Reports {
    const FILE_EXTENSION = 'txt';
    const FILE_FILENAME  = self::STDOUT;

    public function _generate($analyzerList) {
        $list = makeList($analyzerList);
        $res = $this->sqlite->query("SELECT * FROM resultsCounts WHERE analyzer IN ($list) AND count = 0");

        $results = array();
        while( $row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $results []= sprintf('%- 70s %- 40s', $this->docs->getDocs($row['analyzer'], 'name'), $row['analyzer']);
        }

        sort($results);

        $results [] = "\nTotal : " . count($results) . ' / ' . count($analyzerList);

        return implode(PHP_EOL, $results) . PHP_EOL;
    }
}

?>