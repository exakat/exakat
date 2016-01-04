<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

    // @todo automate this : Each string must be found in Report/Content/Directives/*.php and vice-versa
    public static $directives = array('Standard', 'Bcmath', 'Date', 'Filesystem', 
                                      'FileUpload', 'Mail', 'Ob', 
                                      // standard extensions
                                      'Amqp', 'Apache', 'Assertion', 'Curl', 'Dba',
                                      'Filter', 'Image', 'Intl', 'Ldap',
                                      'Mbstring', 
                                      'Opcache', 'Pcre', 'Pdo', 'Pgsql',
                                      'Session', 'Sqlite', 'Sqlite3', 
                                      // pecl extensions
                                      'Com',
                                      'Geoip', 'Ibase', 
                                      'Imagick', 'Mailparse', 'Mongo', 
                                      'Trader', 'Wincache', 'Xcache'
                                       );
    public function collect() {

        ///////////////////////////////////////////////////////////////
        // extensions configurations
        ///////////////////////////////////////////////////////////////
        foreach(self::$directives as $directive) {
            $classname = "\\Report\\Content\\Directives\\$directive";
            $ext = new $classname(null);
            if ($ext->hasDirective()) {
                $this->array[$ext->name] = $ext;
            }
        }
    }
}

?>
