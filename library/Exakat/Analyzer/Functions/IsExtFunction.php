<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class IsExtFunction extends Analyzer {

    public function analyze() {
        $exts = self::$docs->listAllAnalyzer('Extensions');
        $exts[] = 'php_functions';
        
        $f = array();
        foreach($exts as $ext) {
            $inifile = str_replace('Extensions\\Ext', '', $ext).'.ini';
            $ini = $this->loadIni($inifile, 'functions');
            
            if (!empty($ini[0])) {
                $f[] = $ini;
            }
        }
        $functions = call_user_func_array('array_merge', $f);
        
        $functions = array_keys(array_count_values($functions));
        $functions = $this->makeFullNsPath($functions);
        
        $this->atomFunctionIs($functions);
        $this->prepareQuery();

        $this->atomIs(array('Isset', 'Empty', 'Unset', 'Exit', 'Empty', 'Echo', 'Print'));
        $this->prepareQuery();
    }
}

?>
