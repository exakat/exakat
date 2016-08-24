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

class LoadFinal extends Tasks {
    public function run(\Config $config) {
        
        $linksIn = \Tokenizer\Token::linksAsList();
        
        // processing '\parent' fullnspath
        $query = <<<GREMLIN
g.V().hasLabel("Identifier").filter{ it.get().value("fullnspath").toLowerCase() == "\\\\parent"}
.where( __.until( and( hasLabel("Class"), __.out("NAME").not(has("atom", "Void")) ) ).repeat(__.in($linksIn)).out("EXTENDS") )
.property('fullnspath', __.until( and( hasLabel("Class"), __.out("NAME").not(has("atom", "Void")) ) ).repeat(__.in($linksIn)).out("EXTENDS").values("fullnspath") )
.where( __.until( and( hasLabel("Class"), __.out("NAME").not(has("atom", "Void")) ) ).repeat(__.in($linksIn)).out("EXTENDS").in("DEFINITION") )
.addE('DEFINITION').from( __.until( and( hasLabel("Class"), __.out("NAME").not(has("atom", "Void")) ) ).repeat(__.in($linksIn)).out("EXTENDS").in("DEFINITION") )

GREMLIN;
        $this->gremlin->query($query);
        display("\\parent to fullnspath\n");

        // processing '\self' fullnspath
        $query = <<<GREMLIN
g.V().hasLabel("Identifier").filter{ it.get().value("fullnspath").toLowerCase() == "\\\\self"}
.where( __.until( and( hasLabel("Class", "Interface", "Trait"), __.out("NAME").not(has("atom", "Void")) ) ).repeat(__.in($linksIn)) )
.property('fullnspath', __.until( and( hasLabel("Class", "Interface", "Trait"), __.out("NAME").not(has("atom", "Void")) ) ).repeat(__.in($linksIn)).out("NAME").values("fullnspath") )
.addE('DEFINITION').from( __.until( and( hasLabel("Class", "Interface", "Trait"), __.out("NAME").not(has("atom", "Void")) ) ).repeat(__.in($linksIn)) )

GREMLIN;
        $this->gremlin->query($query);
        display('\\self to fullnspath');
        
        // processing '\static' fullnspath
        $query = <<<GREMLIN
g.V().hasLabel("Identifier").filter{ it.get().value("fullnspath").toLowerCase() == "\\\\static"}
.where( __.until( and( hasLabel("Class", "Trait"), __.out("NAME").not(has("atom", "Void")) ) ).repeat(__.in($linksIn)) )
.property('fullnspath', __.until( and( hasLabel("Class", "Trait"), __.out("NAME").not(has("atom", "Void")) ) ).repeat(__.in($linksIn)).out("NAME").values("fullnspath") )
.addE('DEFINITION').from( __.until( and( hasLabel("Class", "Trait"), __.out("NAME").not(has("atom", "Void")) ) ).repeat(__.in($linksIn)) )

GREMLIN;
        $this->gremlin->query($query);
        display('\\static to fullnspath');

        // Create propertyname for Property Definitions
        $query = <<<GREMLIN
g.V().hasLabel("Ppp", "Var").out("PPP").as("ppp")
.coalesce( out("LEFT"), __.filter{ true } )
.sideEffect{ propertyname = it.get().value('code').toString().substring(1, it.get().value('code').size()); }
.select("ppp")
.sideEffect{ it.get().property('propertyname', propertyname); }

GREMLIN;
        $this->gremlin->query($query);
        display('set propertyname');

        // update fullnspath with fallback for functions 
        $query = <<<GREMLIN
g.V().hasLabel("Functioncall").has("fullnspath", without(''))
                              .has('token', within('T_STRING', 'T_NS_SEPARATOR'))
                              .where( __.in("NEW", "METHOD", "DEFINITION").count().is(eq(0)))

.sideEffect{ 
    fullnspath = it.get().vertices(OUT, 'NAME').next().value("fullnspath").toString().toLowerCase();
    it.get().property("fullnspath", fullnspath ); 
}

GREMLIN;
        $this->gremlin->query($query);
        display('refine functioncall fullnspath');
        
        // fallback for PHP and ext, class, function, constant
        // update fullnspath with fallback for functions 
        $pathDocs = $config->dir_root.'/data/analyzers.sqlite';
        $docs = new \Analyzer\Docs($pathDocs);

        $exts = $docs->listAllAnalyzer('Extensions');
        $exts[] = 'php_constants';
        
        $c = array();
        $f = array();
        foreach($exts as $ext) {
            $inifile = str_replace('Extensions\Ext', '', $ext).'.ini';
            $fullpath = $config->dir_root.'/data/'.$inifile;

            $iniFile = parse_ini_file($fullpath);
            
            if (!empty($iniFile['constants'][0])) {
                $c[] = $iniFile['constants'];
            }

            if (!empty($iniFile['functions'][0])) {
                $f[] = $iniFile['functions'];
            }
        }
        $constants = call_user_func_array('array_merge', $c);
        $constants = array_filter($constants, function ($x) { return strpos($x, '\\') === false;});
        $constants = array_map('strtolower', $constants);

        $query = <<<GREMLIN
g.V().hasLabel("Identifier").where( __.in("DEFINITION", "NEW", "USE", "NAME", "EXTENDS", "IMPLEMENTS", "CLASS", "CONST", "TYPEHINT", "FUNCTION", "GROUPUSE").count().is(eq(0)) )  
                            .filter{ it.get().value("code").toLowerCase() in arg1 }
.sideEffect{ 
    fullnspath = "\\\\" + it.get().value("code").toLowerCase();
    it.get().property("fullnspath", fullnspath); 
}

GREMLIN;
        $this->gremlin->query($query, ['arg1' => $constants]);
        display('spot PHP / ext constants');

        $functions = call_user_func_array('array_merge', $f);
        $functions = array_filter($functions, function ($x) { return strpos($x, '\\') === false;});
        $functions = array_map('strtolower', $functions);

        $query = <<<GREMLIN
g.V().hasLabel("Functioncall").filter{ it.get().value("code").toLowerCase() in arg1 }
                              .where( __.in("DEFINITION").count().is(eq(0)) )
.sideEffect{
    fullnspath = "\\\\" + it.get().value("code").toLowerCase();
    it.get().property("fullnspath", fullnspath); 
}

GREMLIN;
        $this->gremlin->query($query, ['arg1' => $functions]);
    }
}

?>
