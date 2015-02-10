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

class Infobox extends \Report\Content {
    public $severities = array();
    
    public function setSeverities($array) {
        $this->severities = $array;
    }
    
    public function collect() {
        $queryTemplate = "g.V.has('token', 'T_FILENAME').count()";
        $params = array('type' => 'IN');
        $query = new \Everyman\Neo4j\Gremlin\Query($this->neo4j, $queryTemplate, $params);
        $vertices = $query->getResultSet();

        $this->array[] = array('icon'    => 'ok',
                               'number'  => $vertices[0][0],
                               'content' => 'PHP files');
        
        $queryTemplate = "g.V.has('token', 'T_FILENAME').out('FILE').transform{ x = it.out.loop(1){true}{true}.line.unique().count()}.sum()";
        $params = array('type' => 'IN');
        $query = new \Everyman\Neo4j\Gremlin\Query($this->neo4j, $queryTemplate, $params);
        $vertices = $query->getResultSet();
        
        $this->array[] = array('icon'    => 'leaf',
                               'number'  => $vertices[0][0],
                               'content' => 'Lines of code');

        $this->array[] = array('icon'    => 'wrench',
                               'number'  => $this->severities['Critical'],
                               'content' => 'Critical');

        $this->array[] = array('icon'    => 'beaker',
                               'number'  => $this->severities['Major'],
                               'content' => 'Major');

    }
}

?>
