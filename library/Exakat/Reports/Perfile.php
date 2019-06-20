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

class Perfile extends Reports {
    const FILE_EXTENSION = 'txt';
    const FILE_FILENAME  = self::STDOUT;

    public function _generate($analyzerList) {
        $analysisResults = new Results($this->sqlite, $analyzerList);
        $analysisResults->load();

        $perfile       = array();
        $maxLine       = 0;
        $maxTitle      = 0;
        foreach($analysisResults->toArray() as $row) {
            if (!isset($titleCache[$row['analyzer']])) {
                $titleCache[$row['analyzer']] = $this->getDocs($row['analyzer'], 'name');
            }

            $maxLine = max($maxLine, $row['line']);
            $maxTitle = max($maxTitle, strlen($titleCache[$row['analyzer']]), strlen($row['file']));
            $perfile[$row['file']][] = sprintf(' % 4s  %s ', $row['line'], $titleCache[$row['analyzer']]);
        }

        $text = '';
        $line = strlen($maxLine) + $maxTitle + 10;
        foreach($perfile as $file => $issues) {
            sort($issues);
            $text .= str_repeat('-', $line) . "\n" .
                     " line  $file\n" .
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