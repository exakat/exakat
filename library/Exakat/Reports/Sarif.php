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

use Exakat\Reports\Helpers\Sarif as SarifJson;

class Sarif extends Reports {
    const FILE_EXTENSION = 'sarif';
    const FILE_FILENAME  = self::STDOUT;

    public function _generate(array $analyzerList): string {
        $analysisResults = $this->dump->fetchAnalysers($analyzerList);

        $results                    = array();
        $titleCache                 = array();
        $titledescriptionCacheCache = array();
        $severityCache              = array();
        $precisionCache             = array();
        $sarif = new SarifJson();

        foreach($analysisResults->toArray() as $row) {
            if (!isset($titleCache[$row['analyzer']])) {
                $analyzer = $this->rulesets->getInstance($row['analyzer'], null, $this->config);

                $titleCache[$row['analyzer']]       = $this->docs->getDocs($row['analyzer'], 'name');
                $descriptionCache[$row['analyzer']] = $this->docs->getDocs($row['analyzer'], 'description');
                $severityCache[$row['analyzer']]    = $this->docs->getDocs($row['analyzer'], 'severity');
                $precisionCache[$row['analyzer']]   = $this->docs->getDocs($row['analyzer'], 'precision');
            }


            $sarif->addRule($row['analyzer'], $titleCache[$row['analyzer']], $descriptionCache[$row['analyzer']], $severityCache[$row['analyzer']], $precisionCache[$row['analyzer']]);
            $sarif->addResult((string) $row['fullcode'], $row['analyzer'], $row['file'], (int) $row['line']);
            continue;
            print_r($row);die();
            if (!isset($results[$row['file']])) {
                $file = array('errors'   => 0,
                              'warnings' => 0,
                              'fixable'  => 0,
                              'filename' => $row['file'],
                              'messages' => array());
                $results[$row['file']] = $file;
            }

            if (!isset($titleCache[$row['analyzer']])) {
                $analyzer = $this->rulesets->getInstance($row['analyzer'], null, $this->config);

                $titleCache[$row['analyzer']]    = $this->docs->getDocs($row['analyzer'], 'name');
                $severityCache[$row['analyzer']] = $this->docs->getDocs($row['analyzer'], 'severity');
            }

            $message = array('type'     => 'warning',
                             'source'   => $row['analyzer'],
                             'severity' => $severityCache[$row['analyzer']],
                             'fixable'  => 'fixable',
                             'message'  => $titleCache[$row['analyzer']],
                             'fullcode' => $row['fullcode']);

            if (!isset($results[ $row['file'] ]['messages'][ $row['line'] ])) {
                $results[ $row['file'] ]['messages'][ $row['line'] ] = array(0 => array());
            }
            $results[ $row['file'] ]['messages'][ $row['line'] ][0][] = $message;

            ++$results[ $row['file'] ]['warnings'];
            $this->count();
        }

        return (string) $sarif;
    }
}

?>