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
use Exakat\Reports\Helpers\Results;

class CodeSniffer extends Reports {
    const FILE_EXTENSION = 'txt';
    const FILE_FILENAME  = 'exakat';

    public function _generate($analyzerList) {
        $analysisResults = new Results($this->sqlite, $analyzerList);
        $analysisResults->load();

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
                $analyzer = $this->themes->getInstance($row['analyzer'], null, $this->config);
                $titleCache[$row['analyzer']]    = $this->getDocs($row['analyzer'], 'name');
                $severityCache[$row['analyzer']] = $analyzer->getSeverity();
            }

            $message = array('type'     => 'warning',
                             'source'   => $row['analyzer'],
                             'severity' => $severityCache[$row['analyzer']],
                             'fixable'  => 'fixable',
                             'message'  => $titleCache[$row['analyzer']]);

            if (!isset($results[ $row['file'] ]['messages'][ $row['line'] ])) {
                $results[ $row['file'] ]['messages'][ $row['line'] ] = array(0 => array());
            }
            $results[ $row['file'] ]['messages'][ $row['line'] ][0][] = $message;

            ++$results[ $row['file'] ]['warnings'];
        }

        $separator = str_repeat('-', 80)."\n";
        $text = '';
        foreach($results as $file) {
            ksort($file['messages']);
            $text .= 'FILE : '.$file['filename']."\n";
            $text .= $separator;
            $c = count($file['messages']);
            $l = count(array_filter(array_unique(array_keys($file['messages'])), function ($x) { return $x > 0; }));
            $text .= 'FOUND '.$c.' ISSUE'.( $c > 1 ? 'S' : '').' AFFECTING '.$l.' LINE'.( $l > 1 ? 'S' : '')."\n";
            $text .= $separator;
            
            $maxSize = strlen(max(array_keys($file['messages'])));
            $padding = str_repeat(' ', $maxSize);

            $maxSize = strlen(max(array_keys($file['messages'])));
            $padding = str_repeat(' ', $maxSize);
            
            foreach($file['messages'] as $line => $column) {

                $messages = $column[0];
                foreach($messages as $message) {
                    $line = $line == -1 ? '  ' : $line;
                    $line = substr( $padding.$line, -$maxSize);
                    $text .= ' '.$line.' | '.strtoupper($message['severity']).' | '.$message['message']."\n";
                    $this->count();
                }
            }
            $text .= $separator;
            $text .= "\n\n\n";
        }
        
        return $text;
    }
}

?>