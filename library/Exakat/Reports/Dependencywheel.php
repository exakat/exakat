<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

class Dependencywheel extends Reports {
    const FILE_EXTENSION = '';
    const FILE_FILENAME  = 'wheel';
    
    private $tmpName      = '';
    private $finalName    = '';
    private $packagenames = '';
    private $matrix       = '';

    public function generate($folder, $name= 'wheel') {
        if ($name === self::STDOUT) {
            print "Can't produce Dependency Wheel format to stdout\n";
            return false;
        }

        $this->finalName = "$folder/$name";
        $this->tmpName   = "$folder/.$name";

        $this->initFolder();
        
        $this->makeWheel();
        
        $this->cleanFolder();
    }

    private function makeWheel() {
        $packagenames = array('Main');
        
        $res = $this->sqlite->query('SELECT * FROM cit');
        
        $ids = array();
        $extends = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $packagenames[] = $row['name'];

            if (($row['extends'] !== '') &&
                ((int) $row['extends'] == 0) &&
                !in_array($row['extends'], $packagenames)) {

                $packagenames[] = $row['extends'];
                $ids[$row['extends']] = $row['extends'];
            }
            $ids[$row['id']] = $row['name'];
            
            if (isset($extends[$row['name'] ])) {
                $extends[$row['name'] ][] = $row['extends'];
            } else {
                $extends[$row['name'] ] = array($row['extends']);
            }
            
            $this->count();
        }

        $res = $this->sqlite->query('SELECT * FROM cit_implements');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (($row['implements'] !== '') &&
                ((int) $row['implements'] === 0) &&
                (!in_array($row['implements'], $packagenames)) ) {

                $packagenames[] = $row['implements'];
                $ids[$row['implements']] = $row['implements'];
            }
        }
        
        $results = array();
        $n = count($packagenames);
        $results = array_pad(array(), $n, array_pad( array(), $n, 0));
        $dict = array_flip($packagenames);

        foreach($extends as $name => $extend) {
            foreach($extend as $ext) {
                if ($ext === '') {
                    continue;
                } elseif ((int) $ext == 0) {
                    $e = $dict[$ext];
                } elseif ((int) $ext > 0) {
                    $e = $dict[$ids[$ext]];
                } else {
                    assert(false, "\$ext is not a string nor an integer.");
                }
                
                $results[$dict[$name]][$e] = 1;
            }
        }
        
        $res = $this->sqlite->query('SELECT * FROM cit_implements');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (!isset($ids[$row['implements']])) {
                continue;
            }
            $I = $ids[$row['implements']];
            $i = $dict[$I];
    
            $E = $ids[$row['implementing']];
            $e = $dict[$E];

            $results[$e][$i] = 1;
        }

        // Default to link to main.
        // This is done before reporting implements and use
        foreach($results as &$result) {
            if (array_sum($result) === 0) {
                $result[0] = 1;
            }
        }
        unset($result);

        $this->matrix       = json_encode($results);
        $this->packagenames = json_encode($packagenames);
    }
    
    private function initFolder() {
        if ($this->finalName === 'stdout') {
            return "Can't produce Dependencywheel format to stdout";
        }

        // Clean temporary destination
        if (file_exists($this->tmpName)) {
            rmdirRecursive($this->tmpName);
        }

        // Copy template
        copyDir($this->config->dir_root.'/media/dependencywheel', $this->tmpName );
    }

    private function cleanFolder() {
        $html = file_get_contents($this->tmpName.'/index.html');

        $html = str_replace(array('<MATRIX>',    '<PROJECT>',            '<PACKAGENAMES>'),
                            array($this->matrix, $this->config->project, $this->packagenames),
                            $html);

        file_put_contents($this->tmpName.'/index.html', $html);

        if (file_exists($this->finalName)) {
            rename($this->finalName, $this->tmpName.'2');
        }

        rename($this->tmpName, $this->finalName);

        if (file_exists($this->tmpName.'2')) {
            rmdirRecursive($this->tmpName.'2');
        }
    }

}

?>