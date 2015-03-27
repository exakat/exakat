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


namespace Report\Content\Directives;

use Everyman\Neo4j\Client;

class Xcache extends Directives {
    public function __construct(Client $neo4j) {
        parent::__construct($neo4j);
        $this->name         = 'Xcache';
        
        if ($this->checkPresence('Extensions\\Extxcache')) {
            $this->directives[] = array('name' => 'xcache.cacher',
                                       'suggested' => 'true', 
                                       'documentation' => 'Enable or disable opcode cacher. Not available if xcache.size is 0.');

            $this->directives[] = array('name' => 'xcache.size',
                                       'suggested' => '1024K', 
                                       'documentation' => 'Total amount of memory used for opcode (*.php) caching. If set to 0 - opcode caching is disabled. K M G modifiers can be used, i.e. 1G 512M 1024K');

            $this->directives[] = array('name' => 'xcache.admin.enable_auth',
                                       'suggested' => 'on', 
                                       'documentation' => 'Disable XCache builtin http authentication if you plan on handling authentication yourself. Be aware that any vhost users can set up admin page, if builtin http auth is disabled, they can access the page with out any authentication. So it is suggested that you disable mod_auth for XCache admin pages instead of disabling XCache builtin auth.');

            $this->directives[] = array('name' => 'xcache.admin.user',
                                       'suggested' => '1024K', 
                                       'documentation' => 'Authentification name.');

            $this->directives[] = array('name' => 'xcache.admin.pass',
                                       'suggested' => '<md5(your_password)>', 
                                       'documentation' => 'Should be md5($your_password), or empty to disable administration.');

            $this->directives[] = array('name' => 'xcache.optimizer',
                                       'suggested' => 'true', 
                                       'documentation' => 'Enable xcache optimizer.');

            $this->directives[] = array('name' => 'xcache.coverager',
                                       'suggested' => 'false', 
                                       'documentation' => 'Enable xcache scavenger.');

            $this->directives[] = $this->extraConfiguration($this->name, 'xcache');
        }
    }
}

?>