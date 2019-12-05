<?php
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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


namespace Exakat\Reports\Helpers;

use Sqlite3;

class PhpCodeTree {
    private $sqlite = null;
    
    public $namespaces      = array();

    public $constants       = array();
    public $functions       = array();

    public $cits            = array();
    public $classconstants  = array();
    public $properties      = array();
    public $methods         = array();
    
    public function __construct(Sqlite3 $sqlite) {
        $this->sqlite = $sqlite;
    }
    
    public function load() {
        // collect namespaces
        $res = $this->sqlite->query(<<<SQL
SELECT namespace, id FROM namespaces
SQL
        );

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $row['cits']                  = &$this->cits;
            $row['functions']             = &$this->functions;
            $row['constants']             = &$this->constants;
            $row['map']                   = array();
            $row['reduced']               = '';
            array_collect_by($this->namespaces, 0, $row);
        }

        // collect constants
        $res = $this->sqlite->query(<<<SQL
SELECT * FROM constants
SQL
        );
        
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            array_collect_by($this->constants, $row['namespaceId'], $row);
        }

        // collect functions
        $res = $this->sqlite->query(<<<'SQL'
SELECT functions.*, 
GROUP_CONCAT((CASE arguments.typehint WHEN ' ' THEN '' ELSE arguments.typehint || ' ' END ) || 
              CASE arguments.reference WHEN 0 THEN '' ELSE '&' END || 
              CASE arguments.variadic WHEN 0 THEN '' ELSE '...' END  || arguments.name || 
              (CASE arguments.init WHEN ' ' THEN '' ELSE ' = ' || arguments.init END),
             ', ' ) AS signature

FROM functions

LEFT JOIN arguments
    ON functions.id = arguments.methodId AND
       arguments.citId = 0
GROUP BY functions.id

SQL
        );
        
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            array_collect_by($this->functions, $row['namespaceId'], $row);
        }

        // collect cit
        $res = $this->sqlite->query(<<<SQL
SELECT cit.*, 
       cit.type AS type, 

       ( SELECT GROUP_CONCAT(CASE WHEN cit5.id IS NULL THEN traits.implements ELSE cit5.name END, ',') FROM cit_implements AS traits
LEFT JOIN cit cit5
    ON traits.implements = cit5.id
    WHERE traits.implementing = cit.id AND
       traits.type = 'use') AS use,

       (SELECT GROUP_CONCAT(CASE WHEN cit4.id IS NULL THEN implements.implements ELSE cit4.name END, ',') FROM cit_implements AS implements
LEFT JOIN cit cit4
    ON implements.implements = cit4.id
    WHERE implements.implementing = cit.id AND
       implements.type = 'implements') AS implements,

        CASE WHEN cit2.extends IS NULL THEN cit.extends ELSE cit2.name END AS extends 
        
        FROM cit

LEFT JOIN cit cit2 
    ON cit.extends = cit2.id

LEFT JOIN cit_implements AS interfaces
    ON interfaces.implementing = cit.id AND
       interfaces.type = 'implements'
LEFT JOIN cit cit4
    ON interfaces.implements = cit4.id


GROUP BY cit.id
SQL
        );
        
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $row['methods']         = &$this->methods;
            $row['properties']      = &$this->properties;
            $row['classconstants']  = &$this->classconstants;
            
            array_collect_by($this->cits, $row['namespaceId'], $row);
        }

        // collect properties
        $res = $this->sqlite->query(<<<'SQL'
SELECT * FROM properties
SQL
        );
        
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            array_collect_by($this->properties, $row['citId'], $row);
        }

        // collect class constants
        $res = $this->sqlite->query(<<<'SQL'
SELECT * FROM classconstants
SQL
        );
        
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            array_collect_by($this->classconstants, $row['citId'], $row);
        }

        // collect methods
        $res = $this->sqlite->query(<<<'SQL'
SELECT methods.*, 
GROUP_CONCAT((CASE arguments.typehint WHEN ' ' THEN '' ELSE arguments.typehint || ' ' END ) || 
              CASE arguments.reference WHEN 0 THEN '' ELSE '&' END || 
              CASE arguments.variadic WHEN 0 THEN '' ELSE '...' END  || arguments.name || 
              (CASE arguments.init WHEN ' ' THEN '' ELSE ' = ' || arguments.init END),
             ', ' ) AS signature,
cit.type AS cit
FROM methods
LEFT JOIN arguments
    ON methods.id = arguments.methodId
JOIN cit
    ON methods.citId = cit.id
GROUP BY methods.id
SQL
        );
        
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            array_collect_by($this->methods, $row['citId'], $row);
        }
    }

    public function map(string $what, Callable $closure) {
        if (!property_exists($this, $what)) {
            return;
        }

        foreach($this->$what as $id => &$items) {
            $items['map'] = array_map($closure, $items);
        }
    }

    public function reduce(string $what, Callable $closure) {
        if (!property_exists($this, $what)) {
            return;
        }

        foreach($this->$what as $id => &$items) {
            $items['reduced'] = array_reduce($items['map'], $closure, '');
        }
    }

    public function get(string $what) {
        if (!property_exists($this, $what)) {
            return;
        }

        return $this->$what[0]['reduced'];
    }
}
