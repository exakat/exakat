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

use Exakat\Analyzer\Analyzer;
use Exakat\Reports\Helpers\Results;

class Typehint4all extends Reports {
    const FILE_EXTENSION = 'txt';
    const FILE_FILENAME  = self::STDOUT;
    
    const FORMAT = ' % 4s |  % 18s | %s';
    
    public function dependsOnAnalysis() : array {
        return array('Functions/CouldTypeWithInt',
                     'Functions/CouldTypeWithArray',
                     'Functions/CouldTypeWithString',
                     'Functions/CouldTypeWithBool',
                     'Functions/CouldBeCallable',
                     'Functions/CouldTypeWithIterable',
                     );
    }

    public function _generate($analyzerList) {
        $analyzerList = $this->dependsOnAnalysis();

        $analysisResults = new Results($this->sqlite, $analyzerList);
        $analysisResults->load();

        $displayResults = array();
        $titleCache    = array();
        $maxLine       = 0;
        $maxTitle      = 0;
        $previous      = '';

        foreach($analysisResults->toArray() as $row) {
            if (!isset($titleCache[$row['analyzer']])) {
                $titleCache[$row['analyzer']] = $this->getDocs($row['analyzer'], 'name');
            }

            $row['fullcode'] = trim($row['fullcode'], '&');
            if (preg_match('/^(\$.*?) = /', $row['fullcode'], $r)) {
                $row['fullcode'] = $r[1];
            }
            $maxLine = max($maxLine, $row['line'], strlen($row['fullcode']));
            $maxTitle = max($maxTitle, strlen($titleCache[$row['analyzer']]), strlen($row['file']), strlen($row['fullcode']));
            
            $displayResults[] = $row;
        }

        $perfile       = array();
        foreach($displayResults as $row) {
            array_collect_by($perfile, $row['file'], $row);
        }

        foreach($perfile as $file => &$issues) {
            usort($issues, function ($a, $b) { return $a['line'] <=> $b['line'] ?: $a['fullcode'] <=> $b['fullcode'] ?: $a['analyzer'] <=> $b['analyzer'] ;});
            
            $previous = '';

            foreach($issues as &$row) {
                if ($previous === "$row[line]-$row[fullcode]") {
                    $row['fullcode'] = '';
                } else {
                    $previous = "$row[line]-$row[fullcode]";
                }
                
                $row = sprintf(self::FORMAT, $row['line'], $row['fullcode'], $titleCache[$row['analyzer']]);
            }
        }
        
        $text = '';
        $line = strlen($maxLine) + $maxTitle + 30;

        foreach($perfile as $file => $issues) {
            $text .= str_repeat('-', $line) . "\n" .
                     sprintf(self::FORMAT, 'line', 'arg.', $file) . "\n" .
                     str_repeat('-', $line) . "\n" .
                     implode("\n", $issues) . "\n" .
                     str_repeat('-', $line) . "\n"
                     
                     . "\n"
                     . "\n";
        }

        return $text;
    }
}

?>