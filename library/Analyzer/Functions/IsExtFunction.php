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


namespace Analyzer\Functions;

use Analyzer;

class IsExtFunction extends Analyzer\Analyzer {

    public function analyze() {
        $exts = glob('library/Analyzer/Extensions/*.php');
        $exts[] = 'php_functions.ini';
        
        $functions = array();
        foreach($exts as $ext) {
            $inifile = str_replace('library/Analyzer/Extensions/Ext', '', str_replace('.php', '.ini', $ext));
            if ($inifile == 'library/Analyzer/Extensions/Used.ini') { continue; }
            $ini = $this->loadIni($inifile);
            
            if (!isset($ini['functions']) || !is_array($ini['functions'])) {
                print "No functions defined in $inifile\n";
            } else {
                if (!empty($ini['functions'][0])) {
                    $functions = array_merge($functions, $ini['functions']);
                }
            }
        }
        
        $functions = array_keys(array_count_values($functions));
        $functions = $this->makeFullNsPath($functions);
        
        $this->atomIs('Functioncall')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->hasNoIn('METHOD')
             ->fullnspath($functions, true);
        $this->prepareQuery();
    }
}

?>
