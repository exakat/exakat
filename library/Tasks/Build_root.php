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


namespace Tasks;

class Build_root extends Tasks {
    private $project_dir = '.';
    private $config      = null;
    
    public function run(\Config $config) {
        $this->checkTokenLimit();
        
        $this->config = $config;
        $this->project_dir = $config->projects_root.'/projects/'.$config->project;

        $this->logTime('Start');

//        $result = $this->gremlin->query('g.idx("racines")');

        if (!isset($result->results)) {
//            $this->gremlin->query("g.createIndex('racines', Vertex)");
        }
        $this->logTime('Created racines');

//        $this->logTime('g.idx("racines")');
//        $this->gremlin->query('g.dropIndex("atoms");');
//        $this->gremlin->query('g.createIndex("atoms", Vertex)');

        $this->logTime('g.idx("atoms")');

        // separate processing for T_STRING
        $query = <<<GREMLIN
g.V().has("token", "T_STRING").has("atom", null).each{
    it.property("fullcode", it.value("code"));
    it.property("atom", "Identifier");
//    g.idx("atoms").put("atom", it.atom, it);
}
GREMLIN;
        $this->gremlin->query($query);
//        $this->logTime('g.idx("atoms") : T_STRING');

        // separate processing for T_VARIABLE
        $query = <<<GREMLIN
g.V().has("token", "T_VARIABLE").each{
    it.property("atom", "Variable");
    it.property("fullcode", it.value("code"));
//    g.idx("atoms").put("atom", it.atom, it);
}
GREMLIN;
        $this->gremlin->query($query);
        $this->logTime('g.idx("atoms") : T_VARIABLE');

        $query = <<<GREMLIN
g.V().has("token", "T_STRING_VARNAME").each{
    it.property("fullcode", it.values("code"));
    it.property("atom", "Variable");
//    g.idx("atoms").put("atom", it.atom, it);
}
GREMLIN;
        $this->gremlin->query($query);
//        $this->logTime('g.idx("atoms") : T_STRING_VARNAME');

//    g.idx("atoms").put("atom", it.atom, it);
//g.V().has('atom', within("Integer", "String",  "Magicconstant", "Null",
//                       "RawString", "Float", "Boolean", "Void", "File", "Nsname")).each{
// Those were indexed 

        $query = <<<GREMLIN
g.V().has('atom', 'Integer').each{
    if (it.value('code').length() == 1) { // number
        it.property('intval', Integer.parseInt(it.value('code')));
    } else if (it.value('code').substring(0, 2) == '0b') { // binary
        it.property('intval', new BigInteger(it.value('code').substring(2), 2).toLong());
    } else if (it.value('code').substring(0, 2) == '0B') { // binary
        it.property('intval', new BigInteger(it.value('code').substring(2), 2).toLong());
    } else if (it.value('code').substring(0, 2) == '0x') { // hexadecimal
        it.property('intval', new BigInteger(it.value('code').substring(2), 16).toLong());
    } else if (it.value('code').substring(0, 2) == '0X') { // hexadecimal
        it.property('intval', new BigInteger(it.value('code').substring(2), 16).toLong());
    } else if (it.value('code').substring(0, 1) == '0') { // octal
        // Calculating PHP 5 style. In case of problem (presence of 9), PHP 7 will just stop.
        c = it.value('code');
        
        nine = c.indexOf('9');
        if (nine == -1) {
            eight = c.indexOf('8');
        } else {
            c = c[0..nine - 1];
            eight = c.indexOf('8');
        }

        eight = c.indexOf('8');
        if (eight != -1) {
            c = c[0..eight - 1];
        }
        
        if (c.size() == 1) {
            it.property('intval', 0);
        } else {
            it.property('intval', new BigInteger(c.substring(1), 8).toLong());
        }
    } else {
        it.property('intval', new BigInteger(it.value('code')).toLong());
    }
}
GREMLIN;
        $this->gremlin->query($query);
        $this->logTime('g.idx("atom")[["atom":"******"]] : filling');

        // creating the index
        // @todo check this index
//        $this->gremlin->query("g.V().has('root', true).each{ g.idx('racines').put('token', 'ROOT', it); };");
//        $this->logTime('g.idx("ROOT")');

        // special case for the initial Rawstring.
//        $this->gremlin->query("g.idx('racines')[['token':'ROOT']].has('atom','Sequence').each{ g.idx('atoms').put('atom', 'Sequence', it); };");
//        $this->logTime('g.idx("racines") ROOT special');

        // creating the neo4j Index
//        $this->gremlin->query("g.V().has('index', true).each{ g.idx('racines').put('token', it.token, it); };");
//        $this->logTime('g.idx("racines")[[token:***]] indexing');
        
//        $this->gremlin->query("g.idx('racines')[['token':'Sequence']].out('INDEXED').has('in_for', true).inE('INDEXED').each{ g.removeEdge(it); }");
        // At least one index for sequence (might be needed during processing, even if empty initially)
//        $this->gremlin->query("sequences = g.addVertex(null, [token:'T_SEMICOLON', code:'Index for Sequence', index:true]); g.idx('racines').put('token', 'Sequence', sequences);");
        $this->gremlin->query("sequences = graph.addVertex('token', 'T_SEMICOLON', 'code', 'Index for Sequence', 'index', true);");

        $this->logTime('Indexing racines done');

        // calculating the Unicode blocks
        $this->gremlin->query("g.V().has('atom', within('String', 'Rawstring')).filter{ it.get().value('code').replaceAll(/^['\"]/, '').size() > 0}.each{ it.property('unicode_block', it.value('code').replaceAll(/^['\"]/, '').toList().groupBy{ Character.UnicodeBlock.of( it as char ).toString() }.sort{-it.value.size}.find{true}.key.toString()); }");
        $this->logTime('Unicodes block');

        $this->gremlin->query(<<<GREMLIN
g.V().has("atom", "String").has("token", "T_CONSTANT_ENCAPSED_STRING").not(hasKey("noDelimiter")).has('code', within('""', "''")).property("noDelimiter", "");

GREMLIN
);
        $this->logTime('Unicodes block');

        // resolving the constants
        $extra_indices = array('constants', 'classes', 'interfaces', 'traits', 'functions', 'namespaces', 'files');
        foreach($extra_indices as $indice) {
//            $this->gremlin->query("g.dropIndex('$indice');");
//            $this->gremlin->query("g.createIndex('$indice', Vertex)");
        }
//        $this->logTime('g.idx("last index")');

    // TODO : check for unique Project node. Store it somewhere we can find again? 
        // Creating Project Node
        $this->gremlin->query(<<<GREMLIN
project = g.addV("token", "T_PROJECT", "atom", "Project", "code", "Project", "fullcode", "Whole", "line", 0, "index", true); 
//g.idx("atoms").put("atom", "Project", project);
//g.idx("racines")[["token":"ROOT"]].in("FILE").each{ g.addEdge(project, it, "PROJECT"); };

GREMLIN
);
        $this->logTime('Project node');
        $this->datastore->addRow('hash', array('status' => 'Build_Root'));
    }

    private function logTime($step) {
        static $begin, $end, $start;
    
        $end = microtime(true);
        if ($begin === null) {
            $begin = $end;
            $start = $end;
        }
    
        $this->log->log($step."\t".($end - $begin)."\t".($end - $start)."\n");
        $begin = $end;
    }
}

?>
