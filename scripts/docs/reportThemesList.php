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
$reports = glob('human/en/Reports/*.ini');
include './library/Exakat/Reports/Reports.php';
include './library/Exakat/Config.php';

foreach($reports as $report){
    $file = basename($report);
    $class = substr($file,0, -4);
    include "./library/Exakat/Reports/$class.php";
    
    $fullClass = "\Exakat\Reports\\$class";
    $theReport = new $fullClass(null, null);
    $themes = $theReport->dependsOnAnalysis();
    
    $ini = parse_ini_file($report);
    unset($ini['themes']);
    
    $iniFile = array();
    foreach($ini as $name => $value) {
        if (is_array($value)) {
            foreach($value as $v) {
                $value = str_replace('"', '\"', $v);
                $iniFile[] = "{$name}[] = \"$value\";";
            }
        } else {
            $value = str_replace('"', '\"', $value);
            $iniFile []= "$name = \"$value\";";
        }
    }

    if (empty($themes)) {
            $iniFile[] = "themes[] = \"\";";
    } else {
        foreach($themes as $t) {
            $value = str_replace('"', '\"', $t);
            $iniFile[] = "themes[] = \"$value\";";
        }
    }
    
    $iniFile = implode("\n", $iniFile)."\n";
    file_put_contents($report, $iniFile);
}

?>