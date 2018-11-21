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


namespace Exakat\Analyzer\Traits;

use Exakat\Analyzer\Analyzer;

class IsExtTrait extends Analyzer {

    public function dependsOn() {
        return array('Traits/TraitUsage',
                    );
    }
    
    public function analyze() {
        $exts = $this->themes->listAllAnalyzer('Extensions');
        $exts[] = 'php_traits';
        
        $t = array();
        foreach($exts as $ext) {
            $inifile = str_replace('Extensions\Ext', '', $ext).'.ini';
            $ini = $this->loadIni($inifile);
            
            if (!empty($ini['traits'][0])) {
                $t[] = $ini['traits'];
            }
        }
        if (empty($t)) {
            return ;
        }
        $traits = call_user_func_array('array_merge', $t);

        // no need to process anything!
        if (empty($traits)) { return true; }
        $traits = makeFullNsPath($traits);
        
        $this->analyzerIs('Traits/TraitUsage')
             ->fullnspathIs($traits);
        $this->prepareQuery();
    }
}

?>
