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

class Wincache extends Directives {
    function __construct(Client $neo4j) {
        parent::__construct($neo4j);
        $this->name         = 'Wincache';
        
        if ($this->checkPresence('Extensions\\Extwincache')) {
            $this->directives[] = array('name' => 'wincache.ocenabled',
                                        'suggested' => 'true', 
                                        'documentation' => 'Enables or disables the wincache opcode cache functionality.');

            $this->directives[] = array('name' => 'wincache.ocachesize',
                                        'suggested' => '255', 
                                        'documentation' => 'Defines the maximum memory size (in megabytes) that is allocated for the opcode cache. Max value is 255 (Mb).');

            $this->directives[] = array('name' => 'wincache.ttlmax',
                                        'suggested' => '1200', 
                                        'documentation' => 'Defines the maximum time to live (in seconds) for a cached entry without being used. Setting it to 0 will disable the cache scavenger, so the cached entries will never be removed from the cache during the lifetime of the IIS worker process.');

            $this->directives[] = $this->extraConfiguration($this->name, 'wincache');
        }
    }
}

?>