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
use Exakat\Tasks\Project;

class Clustergrammer extends Reports {
    const FILE_EXTENSION = 'txt';
    const FILE_FILENAME  = 'clustergrammer';

    public function generate($folder, $name = self::FILE_FILENAME) {
        $analyzers = $this->themes->getThemeAnalyzers($this->themesToShow);
        display( count($analyzers)." analyzers\n");

        $res = $this->sqlite->query('SELECT distinct analyzer FROM results WHERE analyzer IN ("'.implode('","', $analyzers).'") ORDER BY analyzer');
        $skeleton = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $skeleton[$row['analyzer']] = 0;
        }
        display( count($skeleton)." distinct analyzers\n");

        $titles = array();
        foreach($skeleton as $analyzer => $foo) {
            if ($analyzer == 'total') { continue; }
            $ini = $this->getDocs($analyzer);
            $titles[$analyzer] = '"'.$ini['name'].'"';
        }

        $all = array();
        $res = $this->sqlite->query('SELECT * FROM results WHERE analyzer IN ("'.implode('","', $analyzers).'") ORDER BY file');
        $total = 0;
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (!isset($all[$row['file']])) {
                $all[$row['file']] = $skeleton;
            }
            ++$all[$row['file']][$row['analyzer']];
            ++$total;
        }
        display( $total." issues read\n");

        $txt = " \t".implode("\t", array_values($titles))."\n";
        foreach($all as $file => $values) {
            $txt .= "$file\t".implode("\t", array_values($values))."\n";
        }
        
        if ($name === self::STDOUT) {
            echo $txt;
        } else {
            file_put_contents($folder.'/'.$name.'.'.self::FILE_EXTENSION, $txt);

            display( count($all)." issues reported\n");
            print 'Upload '.$name.'.'.self::FILE_EXTENSION." on http://amp.pharm.mssm.edu/clustergrammer/\n";
        }
    }
}


?>