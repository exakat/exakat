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


class Text extends Reports {
    const FILE_EXTENSION = 'txt';
    const FILE_FILENAME  = self::STDOUT;

    public function _generate(array $analyzerList): string {
        $analysisResults = $this->dump->fetchAnalysers($analyzerList);

        $results = array();
        $titleCache = array();
        $severityCache = array();
        foreach($analysisResults->toArray() as $row) {
            if (!isset($results[$row['file']])) {
                $file = array('errors'   => 0,
                              'warnings' => 0,
                              'fixable'  => 0,
                              'filename' => $row['file'],
                              'messages' => array());
                $results[$row['file']] = $file;
            }

            if (!isset($titleCache[$row['analyzer']])) {
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
        }

        $text = '';
        foreach($results as $file) {
            foreach($file['messages'] as $line => $column) {
                $messages = $column[0];
                foreach($messages as $message) {
                    $text .= $file['filename'] . ':' . $line . "\t" . $message['source'] . "\t" . $message['message'] . "\t" . $message['fullcode'] . "\n";
                    $this->count();
                }
            }
        }

        return $text;
    }
}

?>