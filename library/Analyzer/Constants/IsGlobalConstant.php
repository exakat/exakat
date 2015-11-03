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


namespace Analyzer\Constants;

use Analyzer;

class IsGlobalConstant extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Constants/ConstantUsage');
    }
    
    public function analyze() {
        $exts = self::$docs->listAllAnalyzer('Extensions');
        $exts[] = 'php_constants';
        
        $c = array();
        foreach($exts as $ext) {
            $inifile = str_replace('Extensions\Ext', '', $ext).'.ini';
            $ini = $this->loadIni($inifile);
            
            if (!empty($ini['constants'][0])) {
                $c[] = $ini['constants'];
            }
        }
        $constants = call_user_func_array('array_merge', $c);
        
        $constantsFullNs = $this->makeFullNsPath($constants);
        $constantsFullNsChunks = array_chunk($constantsFullNs, 500);

        foreach($constantsFullNsChunks as $chunk) {
            $chunk = array_map(function ($x) { return str_replace('\\', '\\\\', $x);}, $chunk);
            $this->analyzerIs('Constants/ConstantUsage')
                 ->analyzerIsNot('self')
                 ->tokenIs('T_STRING')  // No namespace
                 ->regex('fullnspath', '\\\\\\\\.+\\\\\\\\.+')
                 // is the constant defined where it should ?
                 ->filter("g.idx('constants')[['path':it.fullnspath]].any() == false")

                 // is the constant defined in the global
                 ->filter("g.idx('constants')[['path':'\\\\' + it.code.toLowerCase()]].any() ||
                           '\\\\' + it.code.toLowerCase() in ['".join("', '", $chunk)."']")
                 ->back('first');
            $this->prepareQuery();
        }

        $this->analyzerIs('Constants/ConstantUsage')
             ->analyzerIsNot('self')
             ->tokenIs('T_STRING')  // No namespace
             ->regex('fullnspath', '^\\\\\\\\[^\\\\\\\\]+\\$')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
