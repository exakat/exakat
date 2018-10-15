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

namespace Exakat\Reports;

use Exakat\Analyzer\Analyzer;
use Exakat\Reports\Reports;

class OnepageJson extends Reports {
    const FILE_EXTENSION = 'json';
    const FILE_FILENAME  = 'onepage';

    public function generate($folder, $name = null) {
        $sqlite = new \Sqlite3($folder.'/dump.sqlite');
        $sqlQuery = "SELECT * FROM results WHERE analyzer in $this->themesList";
        $res = $sqlite->query($sqlQuery);

        $results = array();
        $titleCache = array();
        $severityCache = array();

        $results = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {

            if (isset($titleCache[$row['analyzer']])) {
                $clearphp = '';
            } else {
                $analyzer = $this->themes->getInstance($row['analyzer'], null, $this->config);

                $titleCache[$row['analyzer']]    = $this->getDocs($row['analyzer'], 'name');
                $severityCache[$row['analyzer']] = $this->getDocs($row['analyzer'], 'severity');
                $clearphp = $this->getDocs($row['analyzer'], 'clearphp');
            }

            $message = array('code'     => $row['fullcode'],
                             'line'     => $row['line'],
                             'clearphp' => $clearphp);

            if (!isset($results[$titleCache[$row['analyzer']]])) {
                $results[$titleCache[$row['analyzer']]] = array();
            }
            $results[$titleCache[$row['analyzer']]][] = $message;

            $this->count();
        }

        if ($name === null) {
            return json_encode($results);
        } else {
            file_put_contents("$folder/$name.".self::FILE_EXTENSION, json_encode($results));
            return true;
        }
    }
}

?>