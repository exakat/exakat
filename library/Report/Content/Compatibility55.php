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


namespace Report\Content;

class Compatibility55 extends \Report\Content {
    public static $deprecatedExtensions = array('apc', 'mysql');
    
    public function collect() {
        $list = \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP55');
        
        foreach($list as $l) {
            $analyzer = \Analyzer\Analyzer::getInstance($l, $this->neo4j);
            $this->array[ $analyzer->getDescription()->getName()] = array('id'     => 1, 
                                                        'result' => $analyzer->toCount());
        }

        $deprecatedExtensions = array_merge( self::$deprecatedExtensions,
                                             \Report\Content\Compatibility54::$deprecatedExtensions,
                                             \Report\Content\Compatibility53::$deprecatedExtensions);
        foreach($deprecatedExtensions as $extension) {
            $analyzer = \Analyzer\Analyzer::getInstance('Analyzer\\Extensions\\Ext'.$extension, $this->neo4j);
            $this->array[ $analyzer->getDescription()->getName()] = array('id'     => 1, 
                                                        'result' => $analyzer->toCount());
        }
        
        return true;
    }
}

?>
