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

class Filesystem extends Directives {
    public function __construct(Client $neo4j) {
        parent::__construct($neo4j);
        $this->name         = 'File System';

        
        if ($this->checkPresence('Php\\FileUsage')) {
            $this->directives[] = array('name' => 'allow_url_fopen',
                                        'suggested' => 'Off',
                                        'documentation' => 'Unless you need to access remote files, it is better to be safe and forbid this feature');

            $this->directives[] = $this->extraConfiguration($this->name, 'filesystem');
        }
    }
}

?>