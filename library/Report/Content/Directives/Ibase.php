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


namespace Report\Content\Directives;

class Ibase extends Directives {
    public function __construct() {
        $this->name         = 'Ibase';
        
        if ($this->checkPresence('Extensions\\Extibase')) {
            $this->directives[] = array('name'          => 'ibase.default_db',
                                        'suggested'     => '<Your default database>',
                                        'documentation' => 'The default database to connect to when ibase_[p]connect() is called without specifying a database name.');

            $this->directives[] = array('name'          => 'ibase.default_user',
                                        'suggested'     => '',
                                        'documentation' => 'The user name to use when connecting to a database if no user name is specified.');

            $this->directives[] = array('name'          => 'ibase.default_password',
                                        'suggested'     => '',
                                        'documentation' => 'The password to use when connecting to a database if no password is specified.');

            $this->directives[] = array('name'          => 'ibase.default_charset',
                                        'suggested'     => '',
                                        'documentation' => 'The character set to use when connecting to a database if no character set is specified.');

            $this->directives[] = $this->extraConfiguration($this->name, 'ibase');
        }
    }
}

?>