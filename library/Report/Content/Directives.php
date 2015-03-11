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

class Directives extends \Report\Content {
    protected $name = 'Directives';
    
    public function collect() {

        ///////////////////////////////////////////////////////////////
        // extensions configurations
        ///////////////////////////////////////////////////////////////

        $directives = array('Standard', 'Filesystem', 'FileUpload', 
                            // standard extensions
                            'Apache', 'Assertion', 'Curl', 
                            'Filter', 'Image', 'Intl',
                            'Mbstring', 'Opcache', 'Pcre', 'Pdo',
                            'Session', 'Sqlite', 'Sqlite',
                            // pecl extensions
                            'Imagick', 'Mongo', 
                            'Wincache', 'Xcache'
                             );
        
        foreach($directives as $directive) {
            $classname = "\\Report\\Content\\Directives\\$directive";
            $ext = new $classname($this->neo4j);
            if ($ext->hasDirective()) {
                $this->array[$ext->name] = $ext;
            }
        }
    }
}

?>
