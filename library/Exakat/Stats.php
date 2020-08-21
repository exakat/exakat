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

namespace Exakat;


class Stats {
    private $stats       = array();
    private $file_filter = '';
    private $gremlin     = null;

    public function __construct() {
        $this->gremlin = exakat('graphdb');
    }

    public function toArray(): array {
        return $this->stats;
    }

    public function setFileFilter(string $file): bool {
        $this->file_filter = ".has('file', '$file')";

        return true;
    }

    public function __get($name) {
        if (isset($this->stats[$name])) {
            return $this->stats[$name];
        } else {
            return null;
        }
    }

    public function collect(): void {
        $this->stats['tokens_count']        = $this->gremlin->queryOne('g.V().has(id, neq(0))' . $this->file_filter . '.count()');//'.has("atom",not(within("Index")))
        $this->stats['relations_count']     = $this->gremlin->queryOne('g.E().has(id, neq(0))' . $this->file_filter . '.count()');
        $this->stats['atoms_count']         = $this->gremlin->queryOne('g.V().label().unique()' . $this->file_filter . '.size()');
        $this->stats['LINK_count']          = $this->gremlin->queryOne('g.E().label().unique()' . $this->file_filter . '.size()');
        $this->stats['file_count']          = $this->gremlin->queryOne('g.V().inE("FILE").count(); ');
    }
}

?>
