<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Traits;

use Analyzer;

class IsExtTrait extends Analyzer\Analyzer {

    public function dependsOn() {
        return array("Analyzer\\Traits\\TraitUsage");
    }
    
    public function analyze() {
        $exts = glob('library/Analyzer/Extensions/*.php');
        $exts[] = 'php_traits.ini';
        
        $traits = array();
        foreach($exts as $ext) {
            $inifile = str_replace('library/Analyzer/Extensions/Ext', '', str_replace('.php', '.ini', $ext));
            if ($inifile == 'library/Analyzer/Extensions/Used.ini') { continue; }
            $ini = $this->loadIni($inifile);
            
            if (!isset($ini['traits']) || !is_array($ini['traits'])) {
//                print "No trait defined in $inifile\n";
            } else {
                if (!empty($ini['traits'][0])) {
                    $traits = array_merge($traits, $ini['traits']);
                }
            }
        }

        // no need to process anything!
        if (empty($traits)) { return true; } 
        $traits = $this->makeFullNsPath($traits);
        
        $this->analyzerIs("Analyzer\\Traits\\TraitUsage")
             ->fullnspath($traits);
        $this->prepareQuery();
    }
}

?>
