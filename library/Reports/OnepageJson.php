<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

namespace Reports;

class OnepageJson extends Reports {
    CONST FILE_EXTENSION = 'json';

    public function __construct() {
        parent::__construct();
    }

    public function generateFileReport($report) {
        return false;
    }

    public function generate($folder, $name = null) {
        $sqlite = new \Sqlite3($folder.'/dump.sqlite');
        $sqlQuery = 'SELECT * FROM results WHERE analyzer in '.$this->themesList;
        $res = $sqlite->query($sqlQuery);
        
        $results = array();
        $titleCache = array();
        $severityCache = array();
        
        $results = array();
                              
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {

            if (!isset($titleCache[$row['analyzer']])) {
                $analyzer = \Analyzer\Analyzer::getInstance($row['analyzer']);
                $titleCache[$row['analyzer']] = $analyzer->getDescription()->getName();
                $severityCache[$row['analyzer']] = $analyzer->getSeverity();
                $clearphp = $analyzer->getDescription()->getClearPHP();
            } else {
                $clearphp = '';
            }

            $message = array('code'     => $row['fullcode'],
                             'line'     => $row['line'],
                             'clearphp' => $clearphp);

            if (!isset($results[$titleCache[$row['analyzer']]])) {
                $results[$titleCache[$row['analyzer']]] = array();
            }
            $results[$titleCache[$row['analyzer']]][] = $message;

//            ++$results['warnings'];
            $this->count();
        }
        
        if ($name === null) {
            return json_encode($results);
        } else {
            file_put_contents($folder.'/'.$name.'.'.self::FILE_EXTENSION, json_encode($results));
            return true;
        }
    }
}