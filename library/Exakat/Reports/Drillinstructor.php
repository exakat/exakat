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


class Drillinstructor extends Ambassador {
    const FILE_FILENAME  = 'drill';
    const FILE_EXTENSION = '';
    const CONFIG_YAML    = 'Drillinstructor';

    protected function generateLevel(Section $section): void {
        $this->generateIssuesEngine($section,
                                    $this->getIssuesFaceted(array('Level 1')));
    }

    protected function generateLevels(Section $section): void {
        $levels = '';

        foreach(range(1, 6) as $level) {
            $levelRows = '';
            $total = 0;
            $analyzers = $this->rulesets->getRulesetsAnalyzers(array('Level ' . $level));
            if (empty($analyzers)) {
                continue;
            }

            $res = $this->dump->fetchAnalysersCounts($analyzers);

            $colors = array('A' => '#00FF00',
                            'B' => '#32CC00',
                            'C' => '#669900',
                            'D' => '#996600',
                            'E' => '#CC3300',
                            'F' => '#FF0000',
                            );
            $count = 0;
            $countColors = count($colors);
            foreach($res->toArray() as $row) {
                $ini = $this->docs->getDocs($row['analyzer']);

#FF0000	Bad
#FFFF00	Bad-Average
#FFFF00	Average
#7FFF00	Average-Good
#00FF00	Good

                if ($row['count'] == 0) {
                    $row['grade'] = 'A';
                } else {
                    $grade = intval(min(ceil(log($row['count']) / log(count($colors))), count($colors) - 1));
                    $row['grade'] = chr(66 + $grade); // B to F
                }
                $row['color'] = $colors[$row['grade']];

                $total += $row['count'];
                $count += (int) $row['count'] === 0;

                $levelRows .= '<tr><td>' . $ini['name'] . "</td><td>$row[count]</td><td style=\"background-color: $row[color]\">$row[grade]</td></tr>\n";
            }

            if (count($analyzers) === 1) {
                $grade = 'A';
            } else {
                $grade = intval(floor($count / (count($analyzers) - 1) * ($countColors - 1)));
                $grade = chr(65 + $grade); // B to F
            }
            $color = $colors[$grade];

            $levels .= '<tr><td style="background-color: #bbbbbb">Level ' . $level . '</td>
                            <td style="background-color: #bbbbbb">' . $total . '</td></td>
                            <td style="background-color: ' . $color . '">' . $grade . '</td></tr>' . PHP_EOL .
                       $levelRows;
        }

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'LEVELS', $levels);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $this->putBasedPage($section->file, $html);
    }
}

?>