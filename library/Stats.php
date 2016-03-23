<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class Stats {
    private $stats = array();
    private $file_filter = '';
    private $gremlin = '';
    
    public function __construct($gremlin) {
        $this->gremlin = $gremlin;
    }

    public function toArray() {
        return $this->stats;
    }

    public function setFileFilter($file) {
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

    public function collect() {
        $this->stats['tokens_count']        = $this->gremlin->queryOne('g.V().has(id, neq(0))'.$this->file_filter.'.has("atom",not(within("Index"))).count()');
        $this->stats['relations_count']     = $this->countRelations();
        $this->stats['atoms_count']         = $this->gremlin->queryOne('g.V().has(id, neq(0)).has("atom", neq("null"))'.$this->file_filter.'.count()');
        $this->stats['NEXT_count']          = $this->gremlin->queryOne('g.E().has(label, "NEXT").inV()'.$this->file_filter.'.count()');
        $this->stats['INDEXED_count']       = $this->gremlin->queryOne('g.E().has("label", "INDEXED").outV().has("index", neq(true)).count()');
        $this->stats['file_count']          = $this->gremlin->queryOne('g.V().inE("FILE").count(); ');
        $this->stats['no_fullcode']         = $this->gremlin->queryOne('g.V().has(id, neq(0)).has("fullcode", null).has("index", neq(true)).has("token", not(within("E_FILE", "E_NAMESPACE", "E_CLASS", "E_FUNCTION"))).count();');
        $this->stats['lone_token']          = $this->gremlin->queryOne('g.V().has("atom", not(within(null, "File"))).has("token", neq("T_INDEX")).filter( eq(__.in().count()) ).count()');
        $this->stats['isrm_variable']       = $this->gremlin->queryOne('g.V().has("atom", "Variable").where( __.in("ANALYZED").has("code", "Analyzer\\\\Variables\\\\IsRead").count().is(neq(0)) ).where( __.in("ANALYZED").has("code", "Analyzer\\\\Variables\\\\IsModified").count().is(neq(0))).count()');
        $this->stats['isrm_property']       = $this->gremlin->queryOne('g.V().has("atom", "Property").where( __.in("ANALYZED").has("code", "Analyzer\\\\Variables\\\\IsRead").count().is(neq(0)) ).where( __.in("ANALYZED").has("code", "Analyzer\\\\Variables\\\\IsModified").count().is(neq(0))).count()');
        $this->stats['isrm_array']          = $this->gremlin->queryOne('g.V().has("atom", "Array").where( __.in("ANALYZED").has("code", "Analyzer\\\\Variables\\\\IsRead").count().is(neq(0)) ).where( __.in("ANALYZED").has("code", "Analyzer\\\\Variables\\\\IsModified").count().is(neq(0))).count()');
        $this->stats['isrm_staticproperty'] = $this->gremlin->queryOne('g.V().has("atom", "Staticproperty").where( __.in("ANALYZED").has("code", "Analyzer\\\\Classes\\\\IsRead").count().is(neq(0))).where( __.in("ANALYZED").has("code", "Analyzer\\\\Classes\\\\IsModified").count().is(neq(0))).count()');
        $this->stats['indexed']             = $this->gremlin->queryOne('g.E().has(label, "INDEXED").outV().out().inE().has(label, not(within("ANALYZED", "INDEXED"))).as("a").range(0,100).select("a").by(label).unique().join(", ")');
    }
    
    public function countRelations() {
        return $this->gremlin->queryOne('g.E().has(id, neq(0))'.$this->file_filter.'.count()');
    }
}

?>
