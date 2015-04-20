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


use Everyman\Neo4j\Client,
    Everyman\Neo4j\Gremlin;

class Stats {
    private $client = null;
    private $stats = array();
    private $file_filter = '';
    
    public function __construct(Client $client = null) {
        $this->client = $client;
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
        $this->stats['tokens_count']    = $this->queryOne("g.V.except([g.v(0)]){$this->file_filter}.count()");
        $this->stats['relations_count'] = $this->countRelations();
        $this->stats['atoms_count']     = $this->queryOne("g.V.except([g.v(0)]).hasNot('atom', 'null'){$this->file_filter}.count()");
        $this->stats['NEXT_count']      = $this->queryOne("g.E.has('label', 'NEXT').inV{$this->file_filter}.count()");
        $this->stats['INDEXED_count']   = $this->queryOne("g.E.has('label', 'INDEXED').outV.hasNot('index', true).count()");
        $this->stats['file_count']      = $this->queryOne("g.V.inE('FILE').file.count(); ");
        $this->stats['no_fullcode']     = $this->queryOne("g.V.except([g.v(0)]).has('fullcode', null).hasNot('index', true).filter{!(it.token in ['E_FILE', 'E_NAMESPACE', 'E_CLASS', 'E_FUNCTION'])}.count();");
        $this->stats['lone_token']      = $this->queryOne("g.V.hasNot('atom', null).hasNot('atom', 'File').hasNot('token', 'T_INDEX').filter{ it.in.count() == 0}.count()");
        $this->stats['isrm_variable']   = $this->queryOne("g.V.has('atom', 'Variable').filter{ it.in('ANALYZED').has('code', 'Analyzer\\\\Variables\\\\IsRead').any() == false}.filter{ it.in('ANALYZED').has('code', 'Analyzer\\\\Variables\\\\IsModified').any() == false}.count()");
        $this->stats['isrm_property']   = $this->queryOne("g.V.has('atom', 'Property').filter{ it.in('ANALYZED').has('code', 'Analyzer\\\\Classes\\\\IsRead').any() == false}.filter{ it.in('ANALYZED').has('code', 'Analyzer\\\\Classes\\\\IsModified').any() == false}.count()");
        $this->stats['isrm_staticproperty']   = $this->queryOne("g.V.has('atom', 'Staticproperty').filter{ it.in('ANALYZED').has('code', 'Analyzer\\\\Classes\\\\IsRead').any() == false}.filter{ it.in('ANALYZED').has('code', 'Analyzer\\\\Classes\\\\IsModified').any() == false}.count()");
        $this->stats['indexed']         = $this->queryOne("g.E.has('label', 'INDEXED').outV.out.inE.filter{!(it.label in ['ANALYZED', 'INDEXED'])}[0..100].label.unique().join(', ')");
    }
    
    public function countRelations() {
        return $this->queryOne("g.E.except([g.v(0)]){$this->file_filter}.count()");
    }
    
    private function queryOne($queryString) {
        $r = $this->query($queryString);
        return $r[0][0];
    }

    private function query($queryString) {
        $parameters = array('type' => 'IN');
        try {
            $query = new Gremlin\Query($this->client, $queryString, $parameters);
            return $query->getResultSet();
        } catch (Everyman\Neo4j\Exception $e) {
            print "Can't execute '$queryString'\n".$e->getMessage()."\n";
            return null;
        }
    }
}

?>
