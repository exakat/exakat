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


class Clustergrammer extends Reports {
    const FILE_EXTENSION = 'txt';
    const FILE_FILENAME  = 'clustergrammer';

    public function generate(string $folder, string $name = self::FILE_FILENAME): string {
        $analyzers = $this->rulesets->getRulesetsAnalyzers($this->themesToShow);
        display( count($analyzers) . " analyzers\n");

        $res = $this->dump->fetchAnalysers($analyzers);
        $byAnalyzer = $res->toArray();
        usort($byAnalyzer, function (array $a, array $b): bool { return $a['analyzer'] <=> $b['analyzer']; } );
        $skeleton = array();
        foreach($byAnalyzer as $row) {
            $skeleton[$row['analyzer']] = 0;
        }
        display( count($skeleton) . " distinct analyzers\n");

        $titles = array();
        foreach(array_keys($skeleton) as $analyzer) {
            if ($analyzer == 'total') { continue; }
            $ini = $this->docs->getDocs($analyzer);
            $titles[$analyzer] = '"' . $ini['name'] . '"';
        }

        $all = array();
        $byFile = $res->toArray();
        usort($byFile, function (array $a, array $b): bool { return $a['file'] <=> $b['file']; } );
        $total = 0;
        foreach($byFile as $row) {
            if (!isset($all[$row['file']])) {
                $all[$row['file']] = $skeleton;
            }
            ++$all[$row['file']][$row['analyzer']];
            ++$total;
        }
        display( $total . " issues read\n");

        $txt = " \t" . implode("\t", array_values($titles)) . "\n";
        foreach($all as $file => $values) {
            $txt .= "$file\t" . implode("\t", array_values($values)) . "\n";
        }

        if ($name === self::STDOUT) {
            return $txt;
        } else {
            file_put_contents($folder . '/' . $name . '.' . self::FILE_EXTENSION, $txt);

            display( count($all) . " issues reported\n");
            print 'Upload ' . $name . '.' . self::FILE_EXTENSION . " on http://amp.pharm.mssm.edu/clustergrammer/\n";
            return '';
        }
    }
}


?>