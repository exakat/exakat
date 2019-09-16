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

class PhpCodeTree {
    private $sqlite = null;
    
    public $namespaces      = array();

    public $constants       = array();
    public $functions       = array();

    public $cits            = array();
    public $classconstants  = array();
    public $properties      = array();
    public $methods         = array();
    
    private $default = array('map'     => array(),
                             'reduced' => '',
                            );
    
    public function __construct($sqlite) {
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
        $res = $this->sqlite->query(<<<SQL
SELECT * FROM functions
SQL
        );
        
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            array_collect_by($this->functions, $row['namespaceId'], $row);
        }

        // collect cit
        $res = $this->sqlite->query(<<<SQL
SELECT cit.*, 
       cit.type AS type, 
       GROUP_CONCAT(traits.implements, ',') AS use, 
       GROUP_CONCAT(CASE WHEN cit4.id IS NULL THEN interfaces.implements ELSE cit4.name END, ',') AS implements,

        CASE WHEN cit2.extends IS NULL THEN cit.extends ELSE cit2.name END AS extends FROM cit
LEFT JOIN cit cit2 
    ON cit.extends = cit2.id


LEFT JOIN cit_implements AS traits
    ON traits.implementing = cit.id AND
       traits.type = 'use'



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
        $res = $this->sqlite->query(<<<SQL
SELECT * FROM properties
SQL
        );
        
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            array_collect_by($this->properties, $row['citId'], $row);
        }

        // collect class constants
        $res = $this->sqlite->query(<<<SQL
SELECT * FROM classconstants
SQL
        );
        
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            array_collect_by($this->classconstants, $row['citId'], $row);
        }

        // collect methods
        $res = $this->sqlite->query(<<<SQL
SELECT * FROM methods
SQL
        );
        
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            array_collect_by($this->methods, $row['citId'], $row);
        }
    }

    public function map($what, $closure) {
        foreach($this->$what as $id => &$items) {
            $items['map'] = array_map($closure, $items);
        }
    }

    public function reduce($what, $closure) {
        foreach($this->$what as $id => &$items) {
            $items['reduced'] = array_reduce($items['map'], $closure, '');
        }
    }

    public function get($what) {
        return $this->$what[0]['reduced'];
    }
}
