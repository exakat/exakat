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
g.V().hasLabel("Functioncall").as("a")
                              .has("fullnspath", without(''))
                              .has('token', within('T_STRING', 'T_NS_SEPARATOR'))
                              .where( __.in("NEW", "METHOD").count().is(eq(0)))
                              .sideEffect{ fullnspath = it.get().value("fullnspath")}
                              .in('DEFINITION')
                              .filter{ it.get().value("fullnspath") != fullnspath}
                              .sideEffect{ fullnspath = it.get().value("fullnspath")}
                              .select("a")
                              .sideEffect{ 
                                   it.get().property("fullnspath", fullnspath ); 
                               }

GREMLIN;
        $this->gremlin->query($query);
        display('fallback for global functioncall');
        
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
        
        // update fullnspath with fallback for functions 
        $query = <<<GREMLIN
g.V().hasLabel("Identifier", "Nsname").as("a")
                              .has("fullnspath", without(''))
                              .has('token', within('T_STRING', 'T_NS_SEPARATOR'))
                              .where( __.in("NEW", "METHOD", "NAME", "SUBNAME").count().is(eq(0)))
                              .sideEffect{ fullnspath = it.get().value("fullnspath")}
                              .in('DEFINITION').out("NAME")
                              .filter{ it.get().value("fullnspath") != fullnspath}
                              .sideEffect{ fullnspath = it.get().value("fullnspath")}
                              .select("a")
                              .sideEffect{ 
                                   it.get().property("fullnspath", fullnspath ); 
                               }

GREMLIN;
        $this->gremlin->query($query);
        display('fallback for global constants');
        
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
g.V().hasLabel("Identifier").where( __.in("DEFINITION", "NEW", "USE", "NAME", "EXTENDS", "IMPLEMENTS", "CLASS", "CONST", "TYPEHINT", "FUNCTION", "GROUPUSE", "SUBNAME").count().is(eq(0)) )  
                            .filter{ it.get().value("code").toLowerCase() in arg1 }
.sideEffect{ 
    fullnspath = "\\\\" + it.get().value("code").toLowerCase();
    it.get().property("fullnspath", fullnspath); 
}

GREMLIN;
        $this->gremlin->query($query, ['arg1' => $constants]);
        display('spot PHP / ext constants');

        $query = 'g.V().hasLabel("Const").out("CONST").out("NAME").filter{ (it.get().value("fullnspath") =~ "^\\\\\\\\[^\\\\\\\\]+\\$" ).getCount() > 0 }.values("code")';
        $constants = $this->gremlin->query($query);
        $constantsGlobal = $constants->results;

        $query = 'g.V().hasLabel("Const").out("CONST").out("NAME").filter{ (it.get().value("fullnspath") =~ "^\\\\\\\\[^\\\\\\\\]+\\$" ).getCount() == 0 }.values("fullnspath")';
        $constants = $this->gremlin->query($query);
        $constantsDefinitions = $constants->results;

        $query = <<<GREMLIN
g.V().hasLabel("Identifier").where( __.in("DEFINITION", "NEW", "USE", "NAME", "EXTENDS", "IMPLEMENTS", "CLASS", "CONST", "TYPEHINT", "FUNCTION", "GROUPUSE", "SUBNAME").count().is(eq(0)) )  
                            .filter{ it.get().value("code") in arg1 }
                            .filter{ !(it.get().value("fullnspath").toLowerCase() in arg2) }
                            .sideEffect{ name = it.get().value("code"); }
.sideEffect{ 
    fullnspath = "\\\\" + it.get().value("code").toLowerCase();
    it.get().property("fullnspath", fullnspath); 
}.addE("DEFINITION").from( g.V().hasLabel("Const").out("CONST").out("NAME").filter{ it.get().value("code") == name} )

GREMLIN;
        $this->gremlin->query($query, ['arg1' => $constantsGlobal, 'arg2' => $constantsDefinitions]);
        display('spot constants that falls back on global constants');

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
        display('mark PHP native functions call');

        // Define-style constant definitions
        $query = <<<GREMLIN
g.V().hasLabel("Functioncall").has("fullnspath", "\\\\define")
     .out("ARGUMENTS").out("ARGUMENT").has("rank", 0)
     .hasLabel("String").has("noDelimiter")
     .map{ s = it.get().value("noDelimiter").toString().toLowerCase();
           if ( s.substring(0,1) != "\\\\") {
               s = "\\\\" + s;
           }
           it.get().property("fullnspath", s);
           s;
         }.unique();
GREMLIN;

        $constants = $this->gremlin->query($query);
        $constants = $constants->results;
        
        if (!empty($constants)) {
            // First round, with full ns path
            $query = <<<GREMLIN
g.V().hasLabel("Identifier", "Nsname")
     .where( __.in("NAME", "SUBNAME").count().is(eq(0)) )
     .filter{ it.get().value("fullnspath") in arg1 }.sideEffect{name = it.get().value("fullnspath"); }
     .addE('DEFINITION2')
     .from( 
        g.V().hasLabel("Functioncall").has("fullnspath", "\\\\define")
             .out("ARGUMENTS").as("a").out("ARGUMENT").has("rank", 0).hasLabel("String")
             .filter{ it.get().value("fullnspath") == name}.select('a')
         )

GREMLIN;
            $res = $this->gremlin->query($query, ['arg1' => $constants]);

            // Second round, with fallback to global constants
            $query = <<<GREMLIN
g.V().hasLabel("Identifier", "Nsname")
     .where( __.in("NAME", "SUBNAME").count().is(eq(0)) )
     .where( __.in("DEFINITION").count().is(eq(0)) )
     .filter{ name = "\\\\" + it.get().value("fullcode").toString().toLowerCase(); name in arg1 }
     .addE('DEFINITION')
     .from( 
        g.V().hasLabel("Functioncall").has("fullnspath", "\\\\define")
             .out("ARGUMENTS").as("a").out("ARGUMENT").has("rank", 0).hasLabel("String")
             .filter{ it.get().value("fullnspath") == name}.select('a')
         )

GREMLIN;
            $res = $this->gremlin->query($query, ['arg1' => $constants]);
            
            // TODO : handle case-insensitive
            
            display('Link constant definitions');
        } else {
            display('Link constant definitions : skipping.');
        }
        
        //display('Mark constants expressions');
        $query = <<<GREMLIN
g.V().hasLabel("Integer", "Boolean", "Float", "Null", "Void", "RawString", "Magicconstant", "Staticconstant", "Void")
     .sideEffect{ it.get().property("constant", true); }

GREMLIN;
        $this->gremlin->query($query);

        $query = <<<GREMLIN
g.V().hasLabel("String").where( __.out("CONCAT").count().is(eq(0)))
     .sideEffect{ it.get().property("constant", true); }

GREMLIN;
        $this->gremlin->query($query);

        $query = <<<GREMLIN
g.V().hasLabel("Identifier",  "Nsname").not(hasLabel("Functioncall"))
     .sideEffect{ it.get().property("constant", true); }

GREMLIN;
        $this->gremlin->query($query);

        $data = new \Data\Methods();
        $deterministFunctions = $data->getDeterministFunctions();
        $deterministFunctions = array_map(function ($x) { return '\\'.$x['name'];}, $deterministFunctions);

        for ($i =0; $i < 3; ++$i) {
        // Cases for Structures (all sub element are constante => structure is constante)
        $structures = array('Addition'         => array('LEFT', 'RIGHT'),
                            'Multiplication'   => array('LEFT', 'RIGHT'),
                            'Bitshift'         => array('LEFT', 'RIGHT'),
                            'Logical'          => array('LEFT', 'RIGHT'),
                            'Power'            => array('LEFT', 'RIGHT'),
                            'Keyvalue'         => array('KEY',  'VALUE'),
                            'Arguments'        => array('ARGUMENT'),
//                            'Functioncall'     => array('ARGUMENTS'), // Warning : Some function are not deterministic. Needs to mark them as such (custom or internals...)
//                            'Methodcall'       => array('METHOD'),
//                            'Staticmethodcall' => array('METHOD'),

//                            'Function'         => array('BLOCK'),  // Block but one should look for return 
                            'Sequence'         => array('ELEMENT'),
                            'Break'            => array('BREAK'),
                            'Continue'         => array('CONTINUE'),
                            'Return'           => array('RETURN'),
                            'Ternary'          => array('CONDITION', 'THEN', 'ELSE'),
                            'Comparison'       => array('LEFT', 'RIGHT'),
                            'Noscream'         => array('AT'),
                            'Not'              => array('NOT'),
                            'Parenthesis'      => array('CODE'),
                            'Concatenation'    => array('CONCAT'),
                            'String'           => array('CONCAT')
                            );
        
            foreach($structures as $atom => $links) {
                $linksList = "'".implode("', '", $links)."'";

                $query = <<<GREMLIN
g.V().hasLabel("$atom").where( __.out($linksList).not(has("constant", true)).count().is(eq(0)) )
    .sideEffect{ it.get().property("constant", true);}
GREMLIN;
                $this->gremlin->query($query);
            }
        
            $query = <<<GREMLIN
g.V().hasLabel("Functioncall").filter{ it.get().value("fullnspath") in arg1}
     .where( __.out("ARGUMENTS").out("ARGUMENT").not(has("constant", true)).count().is(eq(0)) )
    .sideEffect{ it.get().property("constant", true);}
GREMLIN;
            $this->gremlin->query($query, ['arg1' => $deterministFunctions]);
        }
        display('Mark constants expressions');

        $query = <<<GREMLIN
g.V().hasLabel("Variable").has("code", "\\\$GLOBALS").in("VARIABLE").hasLabel("Array").as("var")
     .out("INDEX").hasLabel("String")
     .sideEffect{ varname = '\$' + it.get().value('noDelimiter');
                  it.get().property("globalvar", varname);}


GREMLIN;
        $this->gremlin->query($query);
        display('Mark constants expressions');

    }
}

?>
