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

namespace Exakat\Tasks\LoadFinal;

use Exakat\Query\Query;
use Exakat\Exceptions\GremlinException;
use Exakat\Log;

class LoadFinal {
    protected $gremlin    = null;
    protected $config     = null;
    protected $datastore  = null;

    private $PHPconstants = array();
    private $PHPfunctions = array();

    protected $log        = null;

    public function __construct() {
        $this->gremlin    = exakat('graphdb');
        $this->config     = exakat('config');
        $this->datastore  = exakat('datastore');
        $this->datastore->reuse();

        $this->log = new Log(strtolower(substr(static::class, strrpos(self::class, '\\') + 1)),
                             "{$this->config->projects_root}/projects/{$this->config->project}");
    }

    protected function newQuery(string $title): Query {
        return new Query(0,
                         $this->config->project,
                         $title,
                         $this->config->executable
                         );
    }

    public function run(): void {
        $this->log('Start');
        display('Start load final');

        $this->init();

        $this->removeInterfaceToClassExtends();
        $this->log('removeInterfaceToClassExtends');

        $this->fixFullnspathFunctions();
        $this->log('fixFullnspathFunctions');

        $task = new SpotExtensionNativeFunctions();
        $task->run();
        $this->log('Spot Extensions Native Functions');

        // stats calculation : it will fill the functioncall list
        $query = <<<'GREMLIN'
g.V().hasLabel("Functioncall")
     .has("fullnspath")
     .groupCount("m")
     .by("fullnspath")
     .cap("m")
GREMLIN;
        $fixed = $this->gremlin->query($query)->toArray();
        if (!empty($fixed)) {
            $this->datastore->addRow('functioncalls', $fixed[0]);
        }

        // This is needed AFTER functionnames are found
        $this->spotFallbackConstants();
        $this->log('spotFallbackConstants');
        $task = new FixFullnspathConstants();
        $task->run();
        $this->log('FixFullnspathConstants');

        $task = new FinishIsModified();
        $task->run();
        $this->log('FinishIsModified');

        $task = new IsInIgnoredDir();
        $task->run();
        $this->log('IsInIgnoredDir');

        display('End load final');
        $this->logTime('Final');
    }

    private function log(string $step): void {
        $this->logTime($step);
        $this->log->log($step);
    }

    private function logTime(string $step): void {
        static $log, $begin, $end, $start;

        if ($log === null) {
            $log = fopen("{$this->config->log_dir}/loadfinal.timing.csv", 'w+');
            if ($log === false) {
                return;
            }
        }

        $end = microtime(true);
        if ($begin === null) {
            $begin = $end;
            $start = $end;
        }

        fwrite($log, $step . "\t" . ($end - $begin) . "\t" . ($end - $start) . "\n");
        $begin = $end;
    }

    private function removeInterfaceToClassExtends(): void {
        display('fixing Definitions for traits and interfaces');

        $query = <<<'GREMLIN'
g.V().hasLabel("Interface")
     .out("EXTENDS")
     .inE()
     .hasLabel("DEFINITION")
     .where(__.outV().hasLabel("Class", "Trait", "Classanonymous"))
     .drop()
     .count();
GREMLIN;
        $result = $this->gremlin->query($query);

        display($result->toInt() . ' removed interface extends link');
        $this->log->log(__METHOD__);

        $query = <<<'GREMLIN'
g.V().hasLabel("Class")
     .out("EXTENDS")
     .inE()
     .hasLabel("DEFINITION")
     .where(__.outV().hasLabel("Interface", "Trait"))
     .drop()
     .count();
GREMLIN;
        $result = $this->gremlin->query($query);

        display($result->toInt() . ' removed class extends link');
        $this->log->log(__METHOD__);

        $query = <<<'GREMLIN'
g.V().hasLabel("Class")
     .out("IMPLEMENTS")
     .inE()
     .hasLabel("DEFINITION")
     .where(__.outV().hasLabel("Class", "Trait", "Classanonymous"))
     .drop()
     .count();
GREMLIN;
        $result = $this->gremlin->query($query);

        display($result->toInt() . ' removed class implements link');
        $this->log->log(__METHOD__);

        $query = <<<'GREMLIN'
g.V().hasLabel("Usetrait")
     .out("USE")
     .inE()
     .hasLabel("DEFINITION")
     .where(__.outV().hasLabel("Class", "Interface", "Classanonymous"))
     .drop()
     .count();
GREMLIN;
        $result = $this->gremlin->query($query);

        display($result->toInt() . ' removed class implements link');
        $this->log->log(__METHOD__);
    }

    // Can't move this to Query, because atoms and functioncall dictionaries are still unloaded
    private function fixFullnspathFunctions(): void {
        display('fixing Fullnspath for Functions');
        // Fix fullnspath with definition local to namespace when there is a definition
        // namesapce x { function substr() ; substr(); }

        $query = <<<'GREMLIN'
g.V().hasLabel("Functioncall", "Identifier", "Nsname")
     .not(__.in("NAME").not(hasLabel("Functioncall")))
     .not(has("absolute", true))
     .not(has("token", 'T_NAME_QUALIFIED'))
     .has("fullnspath")
     .as("identifier")
     .sideEffect{ cc = it.get().value("fullnspath");}
     .or(
         __.where(__.in("DEFINITION")
                    .hasLabel("Function", "Constant")
                    .optional(
                        __.hasLabel("Constant").out("NAME")
                    )
                    .filter{ actual = it.get().value("fullnspath"); cc != actual;}
                 ),
         __.has("isPhp", true).not(__.where(__.in('DEFINITION'))).sideEffect{ actual = '\\' + it.get().value("fullnspath").tokenize('\\\\').last();},
         __.has("isExt", true).not(__.where(__.in('DEFINITION'))).sideEffect{ actual = '\\' + it.get().value("fullnspath").tokenize('\\\\').last();},
      )
     .select("identifier")
     .sideEffect{ 
        it.get().property("fullnspath", actual);
    }
     .count();
GREMLIN;
        $result = $this->gremlin->query($query);

        display($result->toInt() . ' fixed Fullnspath for Functions and Constants');
        $this->log->log(__METHOD__);
    }

    private function runQuery(string $query, string $title, array $args = array(), string $method = __METHOD__): void {
        display($title);

        $this->logTime($title);

        try {
            $this->gremlin->query($query, $args);
        } catch (GremlinException $e) {
            // This should be handled nicely!!!
        }

        display('   /' . $title);
        $this->logTime('end ' . $title);
        $this->log->log($method);
    }

    private function spotFallbackConstants(): void {
        $this->logTime('spotFallbackConstants');
        display("spotFallbackConstants\n");

        // Define-style constant definitions
        $query = <<<'GREMLIN'
g.V().hasLabel("Defineconstant")
     .out("NAME")
     .hasLabel("String").has("noDelimiter").not( has("noDelimiter", '') )
     .filter{ (it.get().value("noDelimiter") =~ "(\\\\\\\\)\$").getCount() == 0 }
     .has('fullnspath')
     .values('fullnspath').unique();
GREMLIN;
        $defineConstants = $this->gremlin->query($query)
                                         ->toArray();

        $query = <<<'GREMLIN'
g.V().hasLabel("Const")
     .not( where( __.in("CONST") ) )  // Not a class or an interface
     .out("CONST")
     .out("NAME")
     .filter{ (it.get().value("fullnspath") =~ "^\\\\\\\\[^\\\\\\\\]+\$").getCount() == 1 }
     .values('fullnspath').unique();

GREMLIN;
        $constConstants = $this->gremlin->query($query)
                                        ->toArray();

        $constants = array_merge($constConstants, $defineConstants);
        $this->logTime('constants : ' . count($constants));

        if (empty($constants)) {
            display('Link constant definitions : skipping.');
            return;
        }
        if (!empty($defineConstants)) {
            // This only works with define() and case sensitivity
            $query = <<<'GREMLIN'
g.V().hasLabel("Identifier", "Nsname")
     .not( where( __.in("NAME", "METHOD", "MEMBER", "EXTENDS", "IMPLEMENTS", "CONSTANT", "AS", "CLASS", "DEFINITION", "GROUPUSE") ) )
     .has("token", without("T_CONST", "T_FUNCTION"))
     .has('fullnspath')
     .sideEffect{name = it.get().value("fullnspath"); }
     .filter{ name in arg1 }
     .addE("DEFINITION")
     .from( 
        __.V().hasLabel("Defineconstant")
             .as("a").out("NAME").hasLabel("String")
             .has("fullnspath")
             .filter{ it.get().value("fullnspath") == name}.select("a")
      ).count();

GREMLIN;
            $this->gremlin->query($query, array('arg1' => $defineConstants));

            // Second round, with fallback to global constants
            // Based on define() definitions
            $this->logTime('constants define : ' . count($defineConstants));

            $query = <<<'GREMLIN'
g.V().hasLabel("Identifier", "Nsname")
     .not( where( __.in("NAME", "METHOD", "MEMBER", "EXTENDS", "IMPLEMENTS", "CONSTANT", "AS", "CLASS", "DEFINITION", "GROUPUSE") ) )
     .filter{ name = "\\\\" + it.get().value("fullcode"); name in arg1 }
     .has('fullnspath')
     .sideEffect{
        fullnspath = "\\\\" + it.get().value("code");
        it.get().property("fullnspath", fullnspath); 
     }
     .addE('DEFINITION')
     .from( 
        __.V().hasLabel("Defineconstant")
             .as("a").out("NAME").hasLabel("String").has('fullnspath')
             .filter{ it.get().value("fullnspath") == name}.select('a')
      ).count()

GREMLIN;
            $this->gremlin->query($query, array('arg1' => $defineConstants));
        }

        $this->logTime('constants const : ' . count($constConstants));
        if (!empty($constConstants)) {
            // Based on const definitions
            $query = <<<'GREMLIN'
g.V().hasLabel("Identifier", "Nsname")
     .not( where( __.in("NAME", "DEFINITION", "EXTENDS", "IMPLEMENTS") ) )
     .filter{ name = "\\\\" + it.get().value("fullcode"); 
              name in arg1; }
     .sideEffect{
         it.get().property("fullnspath", name); 
     }
     .addE('DEFINITION')
     .from( 
        __.V().hasLabel("Const")
             .not( where( __.in("CONST") ) ) 
             .out("CONST")
             .out("NAME")
             .filter{ (it.get().value("fullnspath") =~ "^\\\\\\\\[^\\\\\\\\]+\$").getCount() == 1 }
       )
       .count()

GREMLIN;
            $this->gremlin->query($query, array('arg1' => $constConstants));
        }

        // TODO : handle case-insensitive
        $this->logTime('Constant definitions');
        display('Link constant definitions');
        $this->log->log(__METHOD__);
    }

    private function init(): void {
        // fallback for PHP and ext, class, function, constant
        // update fullnspath with fallback for functions

        $themes = exakat('rulesets');

        $exts = $themes->listAllAnalyzer('Extensions');
        $exts[] = 'php_constants';
        $exts[] = 'php_functions';

        foreach($exts as $ext) {
            $inifile = str_replace('Extensions\Ext', '', $ext) . '.ini';
            $fullpath = "{$this->config->dir_root}/data/$inifile";

            if (file_exists($fullpath)) {
                $iniFile = parse_ini_file($fullpath);
            } else {
                $inifile = str_replace('Extensions\Ext', '', $ext) . '.json';
                $fullpath = "{$this->config->dir_root}/data/$inifile";
                if (file_exists($fullpath)) {
                    $jsonFile = file_get_contents($fullpath);
                    $iniFile = json_decode($jsonFile, \JSON_ASSOCIATIVE);
                } else {
                    continue;
                }
            }

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