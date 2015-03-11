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

class Opcache extends Directives {
    function __construct(Client $neo4j) {
        parent::__construct($neo4j);
        $this->name         = 'Opcache';

        if ($this->checkPresence('Extensions\\Extopcache')) {
            $this->directives[] = array('name' => 'opcache.enable',
                                        'suggested' => 'On',
                                        'documentation' => 'By putting aliases of URI in the php.ini, you won\'t hardcode the DSN in your code.');

            $this->directives[] = array('name' => 'opcache.memory_consumption',
                                        'suggested' => '128',
                                        'documentation' => 'This directive set the amount of opcode cache. The more the better, as long as it doesn\'t swap.');

            $this->directives[] = array('name' => 'opcache.memory_consumption',
                                        'suggested' => '4000',
                                        'documentation' => 'The maximum number of files OPcache will cache. Estimate 32kb a file.');

            $this->directives[] = $this->extraConfiguration($this->name, 'opcache');
        }
    }
}

?>