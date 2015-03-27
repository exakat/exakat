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

class Standard extends Directives {
    public function __construct(Client $neo4j) {
        parent::__construct($neo4j);
        $this->name         = 'Standard';
        $this->hasDirective = self::ON;

        $this->directives[] = array('name' => 'memory_limit',
                                    'suggested' => '120',
                                    'documentation' => 'This sets the maximum amount of memory in bytes that a script is allowed to allocate. This helps prevent poorly written scripts for eating up all available memory on a server. It is recommended to set this as low as possible and avoid removing the limit.');

        $this->directives[] = array('name' => 'expose_php',
                                    'suggested' => 'Off',
                                    'documentation' => 'Exposes to the world that PHP is installed on the server. For security reasons, it is better to keep this hidden.');

        $this->directives[] = $this->extraConfiguration($this->name, 'info');
    }
}

?>