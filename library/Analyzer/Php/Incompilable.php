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


namespace Analyzer\Php;

use Analyzer;

class Incompilable extends Analyzer\Analyzer {
    private $versions = array('53', '54', '55', '56', '70');

    public function analyze() {
        // This is not actually done here....
        return true;
    }
    
    public function toArray() {
        $report = array();
        
        $config = \Config::factory();
        foreach($config->other_php_versions as $version) {
            $r = \Analyzer\Analyzer::$datastore->getRow('compilation'.$version);
            
            foreach($r as $l) {
                $l['version'] = $version;
                $report[] = $l;
            }
        }
        
        return $report;
    }

    public function hasResults() {
        $config = \Config::factory();
        foreach($config->other_php_versions as $version) {
            $r = \Analyzer\Analyzer::$datastore->getRow('compilation'.$version);
            
            if (count($r) > 0) { return true; }
        }
        return false;
    }
}

?>
