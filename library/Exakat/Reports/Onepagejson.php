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


class Onepagejson extends Reports {
    const FILE_EXTENSION = 'json';
    const FILE_FILENAME  = 'onepage';

    public function generate(string $folder, string $name = null): string {
        $res = $this->dump->fetchAnalysers($this->themesToShow);

        $results = array();
        $titleCache = array();
        $severityCache = array();

        $results = array();

        foreach($res->toArray() as $row) {

            if (isset($titleCache[$row['analyzer']])) {
                $clearphp = '';
            } else {
                $analyzer = $this->rulesets->getInstance($row['analyzer'], null, $this->config);

                $titleCache[$row['analyzer']]    = $this->docs->getDocs($row['analyzer'], 'name');
                $severityCache[$row['analyzer']] = $this->docs->getDocs($row['analyzer'], 'severity');
                $clearphp = $this->docs->getDocs($row['analyzer'], 'clearphp');
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
            file_put_contents("$folder/$name." . self::FILE_EXTENSION, json_encode($results));
            return '';
        }
    }
}

?>