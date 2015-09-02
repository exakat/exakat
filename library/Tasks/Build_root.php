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


namespace Tasks;

class Build_root extends Tasks {
    private $project_dir = '.';
    private $config      = null;
    
    public function run(\Config $config) {
        $project = $config->project;
        $this->config = $config;
        $this->project_dir = $config->projects_root.'/projects/'.$config->project;

        display( "Starting\n");

        $this->logTime('Start');

        $result = gremlin_query("g.idx('racines')");
        display("Got racines\n");

        if (!isset($result->results)) {
            display("Create racines\n");
            gremlin_query("g.createIndex('racines', Vertex)");
        }
        display("Created racines index\n");

        $this->logTime('g.idx("racines")');
        gremlin_query("g.dropIndex('atoms');");
        gremlin_query("g.createIndex('atoms', Vertex)");

        $this->logTime('g.idx("atoms")');
        display( "g.idx('atoms') : filling\n");

        // separate processing for T_STRING 
        $query = "g.V.has('token', 'T_STRING').has('atom', null).each{
            it.setProperty('fullcode', it.getProperty('code'));
            it.setProperty('atom', 'Identifier');
            g.idx('atoms').put('atom', it.atom, it); 
        }";
        gremlin_query($query);
        display( "g.idx('atoms') : T_STRING\n");

        // separate processing for T_VARIABLE 
        $query = "g.V.has('token', 'T_VARIABLE').each{
            it.setProperty('fullcode', it.getProperty('code'));
            it.setProperty('atom', 'Variable');
            g.idx('atoms').put('atom', it.atom, it); 
        }";
        gremlin_query($query);
        display( "g.idx('atoms') : T_VARIABLE\n");

        $query = "g.V.has('token', 'T_STRING_VARNAME').each{
            it.setProperty('fullcode', it.getProperty('code'));
            it.setProperty('atom', 'Variable');
            g.idx('atoms').put('atom', it.atom, it); 
        }";
        gremlin_query($query);
        display( "g.idx('atoms') : T_VARIABLE\n");

        $query = "g.V.filter{it.atom in ['Integer', 'String',  'Magicconstant', 'Null',
                                         'Rawstring', 'Float', 'Boolean', 'Void', 'File']}.each{
                                         g.idx('atoms').put('atom', it.atom, it); 
        }";
        gremlin_query($query);
        display( "g.idx('atoms') : filled\n" );
        $this->logTime('g.idx("atom")[["atom":"******"]] : filling');

        // creating the neo4j Index
        // @todo check this index
        gremlin_query("g.V.has('root', true).each{ g.idx('racines').put('token', 'ROOT', it); };");
        $this->logTime('g.idx("ROOT")');

        display("Indexing root done\n");

        // special case for the initial Rawstring.
        gremlin_query("g.idx('racines')[['token':'ROOT']].has('atom','Sequence').each{ g.idx('atoms').put('atom', 'Sequence', it); };");
        $this->logTime('g.idx("racines") ROOT special');

        display("Creating index done\n");

        // creating the neo4j Index
        gremlin_query("g.V.has('index', true).each{ g.idx('racines').put('token', it.token, it); };");
        $this->logTime('g.idx("racines")[[token:***]] indexing');
        gremlin_query("g.idx('racines')[['token':'Sequence']].out('INDEXED').has('in_for', true).inE('INDEXED').each{ g.removeEdge(it); }");
        // At least one index for sequence (might be needed during processing, even if empty initially)
        gremlin_query("sequences = g.addVertex(null, [token:'T_SEMICOLON', code:'Index for Sequence', index:true]); g.idx('racines').put('token', 'Sequence', sequences);");

        display("Indexing racines done\n");

        // calculating the Unicode blocks
        gremlin_query("g.idx('atoms')[['atom':'String']].filter{it.code.replaceAll(/^['\"]/, '').size() > 0}.each{ it.setProperty('unicode_block', it.code.replaceAll(/^['\"]/, '').toList().groupBy{ Character.UnicodeBlock.of( it as char ).toString() }.sort{-it.value.size}.find{true}.key.toString()); };");
        gremlin_query("g.idx('atoms')[['token':'Rawstring']].filter{it.code.replaceAll(/^['\"]/, '').size() > 0}.each{ it.setProperty('unicode_block', it.code.replaceAll(/^['\"]/, '').toList().groupBy{ Character.UnicodeBlock.of( it as char ).toString() }.sort{-it.value.size}.find{true}.key.toString()); };");
        $this->logTime('Unicodes block');

        display("String unicode done\n");

        gremlin_query("g.idx('atoms')[['token':'String']].has('noDelimiter', null).filter{ it.code in ['\"\"', \"''\"]}.each{ it.setProperty('noDelimiter', ''); };");
        $this->logTime('Unicodes block');

        display("Check for empty strings\n");

        // resolving the constants
        $extra_indices = array('constants', 'classes', 'interfaces', 'traits', 'functions', 'namespaces', 'files');
        foreach($extra_indices as $indice) {
            gremlin_query("g.dropIndex('$indice');");
            gremlin_query("g.createIndex('$indice', Vertex)");
        }
        $this->logTime('g.idx("last index")');

        display("Creating index for constant, function and classes resolution.\n");
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
