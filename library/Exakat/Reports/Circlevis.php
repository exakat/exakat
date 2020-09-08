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

namespace Exakat\Reports;


class Circlevis extends Reports {
    const FILE_EXTENSION = 'json';
    const FILE_FILENAME  = 'exakat.circle';

    public function _generate(array $analyzerList): string {
        $analysisResults = $this->dump->fetchTable('namespaces');
        $analysisResults->load();

        $results = array('name' => '\\',
                         'children' => array(),
                         'size' => 0,
                         );
        foreach($analysisResults->toArray() as $row) {
            $bits = explode('\\', trim($row['namespace'], '\\'));

            $level = &$results;
            foreach($bits as $bit) {
                $id = strtolower($bit);
                if (isset($level['children'][$id])) {
                    $level = &$level['children'][$id];
                    ++$level['size'];
                } else {
                    $level['children'][$id] = array('name' => $bit,
                                                'children' => array(array('name' => '\\',
                                                'children' => array(),
                                                'size' => 0,
                                                )),
                                                'size' => 1,
                                                );
                    $level = &$level['children'][$id];
                }
            }
        }

        $results = $this->cleanResults($results);

        return json_encode($results, \JSON_PRETTY_PRINT);
    }

    private function cleanResults($results): array {
        if (empty($results['children'])) {
            unset($results['children']);
            $results['size'] = 1;
        } else {
            unset($results['size']);
            $results['children'] = array_values($results['children']);
            foreach($results['children'] as &$child) {
                $child = $this->cleanResults($child);
            }
            unset($child);
        }

        return $results;
    }
}

?>