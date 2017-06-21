<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Tasks;

use Exakat\Analyzer\Docs;
use Exakat\Config;
use Exakat\Data\Methods;
use Exakat\Tokenizer\Token;
use Exakat\Exceptions\GremlinException;

class LoadFinal extends Tasks {
    const CONCURENCE = self::ANYTIME;

    private $linksIn = '';

    private $PHPconstants = array();
    private $PHPfunctions = array();

    public function run() {
        $this->linksIn = Token::linksAsList();

        $this->logTime('Start');

        $this->init();

        $this->makeClassConstantDefinition();

        $this->fallbackToGlobalConstants();
        $this->findPHPNativeConstants();
        $this->fallbackToGlobalConstants2();

        $this->spotPHPNativeFunctions();

        $this->logTime('Final');
    }

    private function logTime($step) {
        static $log, $begin, $end, $start;

        if ($log === null) {
            $log = fopen($this->config->projects_root.'/projects/'.$this->config->project.'/log/loadfinal.timing.csv', 'w+');
            if ($log === false) {
                return;
            }
        }

        $end = microtime(true);
        if ($begin === null) {
            $begin = $end;
            $start = $end;
        }

        fwrite($log, $step."\t".($end - $begin)."\t".($end - $start)."\n");
        $begin = $end;
    }


    private function spotPHPNativeFunctions() {
        $title = 'mark PHP native functions call';
        $functions = call_user_func_array('array_merge', $this->PHPfunctions);
        $functions = array_filter($functions, function ($x) { return strpos($x, '\\') === false;});
        $functions = array_map('strtolower', $functions);

        $functions = array_slice($functions, 0, 14300);
        
        $query = <<<GREMLIN
g.V().hasLabel("Functioncall")
     .has("fullnspath")
     .not(where( __.in("DEFINITION")))
     .filter{ it.get().value("code").toLowerCase() in arg1 }
     .sideEffect{
         fullnspath = "\\\\" + it.get().value("code").toLowerCase();
         it.get().property("fullnspath", fullnspath); 
     }.count();

GREMLIN;

        $this->runQuery($query, $title, array('arg1' => $functions));
    }

    private function runQuery($query, $title, $args = array()) {
        display($title);

        $this->logTime($title);

        try {
            $res = $this->gremlin->query($query, $args);
        } catch (GremlinException $e) {
            // This should be handled nicely!!!
        }

        display('   /'.$title);
        $this->logTime('end '.$title);
    }

    private function spotFallbackConstants() {
        // Define-style constant definitions
        $query = <<<GREMLIN
g.V().hasLabel("Functioncall")
     .where( __.in("METHOD").count().is(eq(0)))
     .has('token', within('T_STRING', 'T_NS_SEPARATOR'))
     .has("fullnspath", "\\\\define")
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
     .where( __.in("NAME", "METHOD", "MEMBER", "CONSTANT").count().is(eq(0)) )
     .has("token", without("T_CONST", "T_FUNCTION"))
     .filter{ it.get().value("fullnspath") in arg1 }.sideEffect{name = it.get().value("fullnspath"); }
     .addE('DEFINITION')
     .from( 
        g.V().hasLabel("Functioncall")
              .where( __.in("METHOD").count().is(eq(0)))
              .has('token', within('T_STRING', 'T_NS_SEPARATOR'))
              .has("fullnspath", "\\\\define")
             .out("ARGUMENTS").as("a").out("ARGUMENT").has("rank", 0).hasLabel("String").has('fullnspath')
             .filter{ it.get().value("fullnspath") == name}.select('a')
         )

GREMLIN;
            $res = $this->gremlin->query($query, array('arg1' => $constants));

            // Second round, with fallback to global constants
            $query = <<<GREMLIN
g.V().hasLabel("Identifier", "Nsname")
     .where( __.in("NAME").count().is(eq(0)) )
     .where( __.in("DEFINITION").count().is(eq(0)) )
     .filter{ name = "\\\\" + it.get().value("fullcode").toString().toLowerCase(); name in arg1 }
     .addE('DEFINITION')
     .from( 
        g.V().hasLabel("Functioncall")
             .where( __.in("METHOD").count().is(eq(0)))
             .has('token', within('T_STRING', 'T_NS_SEPARATOR'))
             .has("fullnspath", "\\\\define")
             .out("ARGUMENTS").as("a").out("ARGUMENT").has("rank", 0).hasLabel("String").has('fullnspath')
             .filter{ it.get().value("fullnspath") == name}.select('a')
         )

GREMLIN;
            $res = $this->gremlin->query($query, array('arg1' => $constants));

            // TODO : handle case-insensitive
            $this->logTime('Constant definitions');

            display('Link constant definitions');
        } else {
            display('Link constant definitions : skipping.');
        }
    }

    private function makeClassConstantDefinition() {
        // Create link between Class constant and definition
        $query = <<<'GREMLIN'
        g.V().hasLabel('Staticconstant').as('first')
.out('CONSTANT').sideEffect{name = it.get().value("code");}.select('first')
.out('CLASS').hasLabel("Identifier", "Nsname").sideEffect{classe = it.get().value("fullnspath");}.in('DEFINITION')
.where( __.sideEffect{classes = [];}
          .emit(hasLabel("Class")).repeat( out("EXTENDS").in("DEFINITION") ).times(15)
          .out("CONST").hasLabel("Const").out("CONST").as('const')
          .out("NAME").filter{ it.get().value("code") == name; }.select('const')
          .sideEffect{classes.add(it.get()); }
          .fold()
)
.map{classes[0]}.as('theClass')
.addE('DEFINITION').to( 'first' )
GREMLIN;
        $this->gremlin->query($query);
        display('Create link between Class constant and definition');
        $this->logTime('Class::constant definition');
    }

    private function fallbackToGlobalConstants() {
        // update fullnspath with fallback for constants
        $query = <<<GREMLIN
g.V().hasLabel("Identifier", "Nsname").as("a")
     .has('token', within('T_STRING', 'T_NS_SEPARATOR'))
     .has("fullnspath", without(''))
     .where( __.in("NEW", "METHOD", "NAME").count().is(eq(0)))
     .sideEffect{ fullnspath = it.get().value("fullnspath")}
     .in('DEFINITION').not(hasLabel("As", "Class", "Interface", "Trait")).out("NAME")
     .filter{ it.get().value("fullnspath") != fullnspath}
     .sideEffect{ fullnspath = it.get().value("fullnspath")}
     .select("a")
     .sideEffect{ 
          it.get().property("fullnspath", fullnspath ); 
      }
      .count();

GREMLIN;
        $res = $this->gremlin->query($query);
        display('fallback for global constants : '.$res->results[0]);
        $this->logTime('fallback to global for constants : '.$res->results[0]);
    }

    private function findPHPNativeConstants() {
        $constants = call_user_func_array('array_merge', $this->PHPconstants);
        $constants = array_filter($constants, function ($x) { return strpos($x, '\\') === false;});
        $constants = array_map('strtolower', $constants);

        $query = <<<GREMLIN
g.V().hasLabel("Identifier")
     .where( __.in("ALIAS", "DEFINITION", "NEW", "USE", "NAME", "EXTENDS", "IMPLEMENTS", "CLASS", "CONST", "CONSTANT", "TYPEHINT", "FUNCTION", "GROUPUSE", "MEMBER").count().is(eq(0)) )  
     .filter{ it.get().value("code").toLowerCase() in arg1 }
     .sideEffect{ 
        fullnspath = "\\\\" + it.get().value("code").toLowerCase();
        it.get().property("fullnspath", fullnspath); 
      }

GREMLIN;
        $this->gremlin->query($query, array('arg1' => $constants));
        display('spot PHP / ext constants');
        $this->logTime('PHP Constants');
    }

    private function fallbackToGlobalConstants2() {
        $query = 'g.V().hasLabel("Const").out("CONST").out("NAME").filter{ (it.get().value("fullnspath") =~ "^\\\\\\\\[^\\\\\\\\]+\\$" ).getCount() > 0 }.values("code")';
        $constants = $this->gremlin->query($query);
        $constantsGlobal = $constants->results;

        $query = 'g.V().hasLabel("Const").out("CONST").out("NAME").filter{ (it.get().value("fullnspath") =~ "^\\\\\\\\[^\\\\\\\\]+\\$" ).getCount() == 0 }.values("fullnspath")';
        $constants = $this->gremlin->query($query);
        $constantsDefinitions = $constants->results;

        $query = <<<GREMLIN
g.V().hasLabel("Identifier")
     .where( __.in("ALIAS", "DEFINITION", "NEW", "USE", "NAME", "EXTENDS", "IMPLEMENTS", "CLASS", "CONST", "CONSTANT", "TYPEHINT", "FUNCTION", "GROUPUSE", "MEMBER").count().is(eq(0)) )  
     .filter{ it.get().value("code") in arg1 }
     .filter{ !(it.get().value("fullnspath").toLowerCase() in arg2) }
     .sideEffect{ name = it.get().value("code"); }
     .sideEffect{ 
         fullnspath = "\\\\" + it.get().value("code").toLowerCase();
         it.get().property("fullnspath", fullnspath); 
      }
      .addE("DEFINITION").from( g.V().hasLabel("Const").out("CONST").out("NAME").filter{ it.get().value("code") == name} )

GREMLIN;
        $this->gremlin->query($query, array('arg1' => $constantsGlobal,
                                            'arg2' => $constantsDefinitions));
        display('spot constants that falls back on global constants');
        $this->logTime('fallback to global for constants 2');
    }

    private function init() {
        // fallback for PHP and ext, class, function, constant
        // update fullnspath with fallback for functions
        $pathDocs = $this->config->dir_root.'/data/analyzers.sqlite';
        $docs = new Docs($pathDocs);

        $exts = $docs->listAllAnalyzer('Extensions');
        $exts[] = 'php_constants';
        $exts[] = 'php_functions';

        foreach($exts as $ext) {
            $inifile = str_replace('Extensions\Ext', '', $ext).'.ini';
            $fullpath = $this->config->dir_root.'/data/'.$inifile;

            $iniFile = parse_ini_file($fullpath);

            if (!empty($iniFile['constants'][0])) {
                $this->PHPconstants[] = $iniFile['constants'];
            }

            if (!empty($iniFile['functions'][0])) {
                $this->PHPfunctions[] = $iniFile['functions'];
            }
        }
    }
}

?>
