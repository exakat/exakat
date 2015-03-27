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

class Mongo extends Directives {
    public function __construct(Client $neo4j) {
        parent::__construct($neo4j);
        $this->name         = 'Mongo';
        
        if ($this->checkPresence('Extensions\\Extmongo')) {
            $this->directives[] = array('name' => 'mongo.default_host',
                                        'suggested' => 'localhost',
                                        'documentation' => 'The default Mongo host to connect to.');
            $this->directives[] = array('name' => 'mongo.default_port',
                                        'suggested' => '27017',
                                        'documentation' => 'The default Mongo port to connect to.');
            $this->directives[] = array('name' => 'mongo.native_long',
                                        'suggested' => '1',
                                        'documentation' => 'Mongo handles integers as 64bits on plat-forms that actually handles them. If not, it will be handled as 32 bits.');
            $this->directives[] = array('name' => 'mongo.long_as_object',
                                        'suggested' => '1',
                                        'documentation' => 'Return a BSON_LONG as an instance of MongoInt64 (instead of a primitive type).');
            $this->directives[] = array('name' => 'mongo.utf8',
                                        'suggested' => '1',
                                        'documentation' => 'Ensure that Mongo handles UTF-8 correctly. ');

            $this->directives[] = $this->extraConfiguration($this->name, 'mongo');
        }
    }
}

?>