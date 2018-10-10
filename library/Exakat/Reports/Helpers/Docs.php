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

namespace Exakat\Reports\Helpers;

class Docs {
    private $pathToIni = null;
    private $ext = null;
    
    private static $docs = null;
    
    public function __construct($pathToIni, \AutoloadExt $ext = null) {
        $this->pathToIni = $pathToIni;
        
        $this->ext = $ext;
    }

    public function getDocs($analyzer) {
        if (isset(self::$docs[$analyzer])) {
            return self::$docs[$analyzer];
        }
        
        if (file_exists("{$this->pathToIni}/human/en/$analyzer.ini")) {
            $ini = parse_ini_file("{$this->pathToIni}/human/en/$analyzer.ini", INI_PROCESS_SECTIONS);
        } elseif ((!is_null($this->ext)) && ($iniString = $this->ext->loadData("$analyzer.ini")) !== null) {
            $ini = parse_ini_string($iniString, INI_PROCESS_SECTIONS);
        } else {
            assert(file_exists("{$this->pathToIni}/human/en/$analyzer.ini"), "No documentation for '$analyzer'.");
        }
        assert($ini !== null, "No readable documentation for '$analyzer'.");

        $ini['parameter'] = array();
        $ranks = array_intersect(array_keys($ini), array('parameter1', 'parameter2', 'parameter3'));
        foreach($ranks as $rank) {
            $ini['parameter'][] = $ini[$rank];
            unset($ini[$rank]);
        }
        
        self::$docs[$analyzer] = $ini;
        return $ini;
    }
}

?>