<?php declare(strict_types = 1);
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

use Exakat\Dump\Dump;

class PhpCodeTree {
    private $dump           = null;

    public $namespaces      = array();

    public $constants       = array();
    public $functions       = array();

    public $cits            = array();
    public $classconstants  = array();
    public $properties      = array();
    public $methods         = array();

    public function __construct(Dump $dump) {
        $this->dump = $dump;
    }

    public function load(): void {
        // collect namespaces
        $res = $this->dump->fetchTable('namespaces');
        foreach($res->toArray() as $row) {
            $row['cits']                  = &$this->cits;
            $row['functions']             = &$this->functions;
            $row['constants']             = &$this->constants;
            $row['map']                   = array();
            $row['reduced']               = '';
            array_collect_by($this->namespaces, 0, $row);
        }

        // collect constants
        $res = $this->dump->fetchTable('constants');
        foreach($res->toArray() as $row) {
            array_collect_by($this->constants, $row['namespaceId'], $row);
        }

        // collect functions
        $res = $this->dump->fetchTableFunctions();
        foreach($res->toArray() as $row) {
            array_collect_by($this->functions, $row['namespaceId'], $row);
        }

        // collect cit
        $res = $this->dump->fetchTableCit();
        foreach($res->toArray() as $row) {
            $row['methods']         = &$this->methods;
            $row['properties']      = &$this->properties;
            $row['classconstants']  = &$this->classconstants;

            array_collect_by($this->cits, $row['namespaceId'], $row);
        }

        // collect properties
        $res = $this->dump->fetchTable('properties');
        foreach($res->toArray() as $row) {
            array_collect_by($this->properties, $row['citId'], $row);
        }

        // collect class constants
        $res = $this->dump->fetchTable('classconstants');
        foreach($res->toArray() as $row) {
            array_collect_by($this->classconstants, $row['citId'], $row);
        }

        // collect methods
        $res = $this->dump->fetchTableMethods();
        foreach($res->toArray() as $row) {
            array_collect_by($this->methods, $row['citId'], $row);
        }
    }

    public function map(string $what, Callable $closure) {
        if (!property_exists($this, $what)) {
            return;
        }

        foreach($this->$what as &$items) {
            $items['map'] = array_map($closure, $items);
        }
    }

    public function reduce(string $what, Callable $closure) {
        if (!property_exists($this, $what)) {
            return;
        }

        foreach($this->$what as &$items) {
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
