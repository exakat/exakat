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

class RadwellCode extends Reports {
    const FILE_EXTENSION = 'txt';
    const FILE_FILENAME  = 'radwell';

    protected $themesToShow = array('RadwellCodes');

    private $descriptions = array(
                             'Structures/NestedIfthen'                      => 'Too many nested if statements',
                             'Structures/NoParenthesisForLanguageConstruct' => 'Extra brackets and braces',
                             'Structures/UselessBrackets'                   => 'Extra brackets and braces',
                             'Type/OneVariableStrings'                      => 'Extra brackets and braces and quotes',
                             'Structures/UselessCasting'                    => 'Unnecessary casting',
                             'Structures/NoIssetWithEmpty'                  => 'Useless checks',
                             'Performances/timeVsstrtotime'                 => 'Slow PHP built-in functions',
                             'Performances/SlowFunctions'                   => 'Slow PHP built-in functions',
                             'Php/IsnullVsEqualNull'                        => 'Slow PHP built-in functions',
    //                             '' => 'Long functions',
    //                             '' => 'Too many function arguments',
    //                             '' => 'Long lines',
                             'Structures/SwitchToSwitch'                    => 'Long if-else blocks',
                             'Php/UpperCaseKeyword'                         => 'Wrong function / class name casing',
                             'Classes/WrongCase'                            => 'Wrong function / class name casing',
    //                             '' => 'Lack of coding standards',

                             );

    public function generate($folder, $name = null) {
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = makeList($list);

        $sqlite = new \Sqlite3($folder.'/dump.sqlite');
        $sqlQuery = 'SELECT * FROM results WHERE analyzer in ('.$list.')';
        $res = $sqlite->query($sqlQuery);

        $results = array();
        $titleCache = array();
        $severityCache = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (!isset($results[$row['file']])) {
                $file = array('errors'   => 0,
                              'warnings' => 0,
                              'fixable'  => 0,
                              'filename' => $row['file'],
                              'messages' => array());
                $results[$row['file']] = $file;
            }

            if (!isset($titleCache[$row['analyzer']])) {
                $analyzer = Analyzer::getInstance($row['analyzer'], null, $this->config);
                $titleCache[$row['analyzer']] = $analyzer->getDescription()->getName();
                $severityCache[$row['analyzer']] = $analyzer->getSeverity();
            }

            $message = array('type'     => 'warning',
                             'source'   => $row['analyzer'],
                             'severity' => $severityCache[$row['analyzer']],
                             'fixable'  => 'fixable',
                             'message'  => $this->descriptions[$row['analyzer']]);

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
                    $text .= $file['filename'].':'.$line.' '.$message['message']."\n";
                    $this->count();
                }
            }
        }

        if ($name === null) {
            return $text;
        } else {
            file_put_contents($folder.'/'.$name.'.'.self::FILE_EXTENSION, $text);
            return true;
        }
    }
}

?>