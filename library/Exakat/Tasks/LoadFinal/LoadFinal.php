<?php
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

use Exakat\Analyzer\Rulesets;
use Exakat\Analyzer\Analyzer;
use Exakat\Graph\Graph;
use Exakat\Config;
use Exakat\Data\Methods;
use Exakat\Query\Query;
use Exakat\Query\DSL\DSL;
use Exakat\Data\Dictionary;
use Exakat\GraphElements;
use Exakat\Exceptions\GremlinException;
use Exakat\Datastore;
use Exakat\Log;

class LoadFinal {
    private $PHPconstants = array();
    private $PHPfunctions = array();
    private $dictCode     = null;
    
    protected $gremlin   = null;
    protected $config    = null;
    protected $datastore = null;
    protected $log       = null;

    public function __construct(Graph $gremlin, Config $config, Datastore $datastore) {
        $this->gremlin   = $gremlin;
        $this->config    = $config;
        $this->datastore = $datastore;

        $a = get_class($this);
        $this->logname = strtolower(substr($a, strrpos($a, '\\') + 1));
        $this->log = new Log($this->logname,
                             "{$this->config->projects_root}/projects/{$this->config->project}");
    }

    protected function newQuery($title) : Query {
        return new Query(0, $this->config->project, $title, null, $this->datastore);
    }

    public function run() {

        $this->dictCode = Dictionary::factory($this->datastore);

        $this->log('Start');
        display('Start load final');

        $this->init();

        $this->removeInterfaceToClassExtends();
        $this->log('removeInterfaceToClassExtends');

        $this->fixFullnspathFunctions();
        $this->log('fixFullnspathFunctions');

        $task = new SpotPHPNativeFunctions($this->gremlin, $this->config, $this->datastore);
        $task->setPHPfunctions($this->PHPfunctions);
        $task->run();
        $this->log('SpotPHPNativeFunctions');

        $task = new SpotExtensionNativeFunctions($this->gremlin, $this->config, $this->datastore);
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
        $task = new FixFullnspathConstants($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('FixFullnspathConstants');

        $task = new SpotPHPNativeConstants($this->gremlin, $this->config, $this->datastore);
        $task->setPHPconstants($this->PHPconstants);
        $task->run();
        $this->log('spotPHPNativeConstants');

        $task = new SetParentDefinition($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('SetParentDefinition');
        $task = new SetClassAliasDefinition($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('SetClassAliasDefinition');
        $task = new MakeClassConstantDefinition($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('MakeClassConstantDefinition');
        $task = new MakeClassMethodDefinition($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('MakeClassMethodDefinition');

        $task = new OverwrittenProperties($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('OverwrittenProperties');

        $task = new CreateDefaultValues($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('CreateDefaultValues');

        $task = new CreateMagicProperty($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('CreateMagicProperty');

        $task = new SetClassRemoteDefinitionWithInjection($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('SetClassRemoteDefinitionWithInjection');

        $task = new SetClassMethodRemoteDefinition($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('SetClassMethodRemoteDefinition');

        $task = new SetClassRemoteDefinitionWithTypehint($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('SetClassRemoteDefinitionWithTypehint');
        
        // This one is doubled below.
        $task = new SetClassRemoteDefinitionWithLocalNew($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('SetClassRemoteDefinitionWithLocalNew');

        $task = new SetClassRemoteDefinitionWithGlobal($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('SetClassRemoteDefinitionWithGlobal');

        $task = new SetClassRemoteDefinitionWithReturnTypehint($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('setClassRemoteDefinitionWithReturnTypehint');

        $task = new SetCloneLink($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('SetCloneLink');

        $task = new SetClassRemoteDefinitionWithLocalNew($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('SetClassRemoteDefinitionWithLocalNew');
        $task = new SetClassRemoteDefinitionWithParenthesis($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('SetClassRemoteDefinitionWithParenthesis');
        $task = new SetClassPropertyDefinitionWithTypehint($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('setClassPropertyDefinitionWithTypehint');
        $task = new SetArrayClassDefinition($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('setArrayClassDefinition');
        $task = new SetStringMethodDefinition($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('SetStringMethodDefinition');

        $task = new SetClassPropertyDefinitionWithFluentInterface($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('SetClassPropertyDefinitionWithFluentInterface');

        $task = new OverwrittenMethods($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('OverwrittenMethods');
        $task = new OverwrittenConstants($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('OverwrittenConstants');

        $task = new SolveTraitMethods($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('SolveTraitMethods');

        $task = new FollowClosureDefinition($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('FollowClosureDefinition');

        $task = new FinishIsModified($this->gremlin, $this->config, $this->datastore);
        $task->setMethods(new Methods($this->config));
        $task->run();
        $this->log('FinishIsModified');

        $task = new IsInIgnoredDir($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('IsInIgnoredDir');
/*
        $task = new CreateCompactVariables($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log('CreateCompactVariables');
*/
        display('End load final');
        $this->logTime('Final');
    }
    
    private function log($step) {
        $this->logTime($step);
        $this->log->log($step);
    }

    private function logTime($step) {
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

    private function removeInterfaceToClassExtends() {
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
    private function fixFullnspathFunctions() {
        display('fixing Fullnspath for Functions');

        $query = <<<'GREMLIN'
g.V().hasLabel("Functioncall")
     .not(has('absolute', true))
     .has('token', 'T_STRING')
     .has("fullnspath")
     .as("identifier")
     .sideEffect{ cc = it.get().value("fullnspath");}
     .in("DEFINITION")
     .hasLabel("Function")
     .sideEffect{ actual = it.get().value("fullnspath");}
     .filter{ actual != cc; }
     .select("identifier")
     .sideEffect{ it.get().property("fullnspath", actual); }
     .count();
GREMLIN;
        $result = $this->gremlin->query($query);

        display($result->toInt() . ' fixed Fullnspath for Functions');
        $this->log->log(__METHOD__);
    }
    
    private function runQuery($query, $title, $args = array(), $method = __METHOD__) {
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

    private function spotFallbackConstants() {
        $this->logTime('spotFallbackConstants');
        display("spotFallbackConstants\n");
        
        // Define-style constant definitions
        $query = <<<'GREMLIN'
g.V().hasLabel("Defineconstant")
     .out("NAME")
     .hasLabel("String").has("noDelimiter").not( has("noDelimiter", '') )
     .filter{ (it.get().value("noDelimiter") =~ "(\\\\\\\\)\$").getCount() == 0 }
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

    private function init() {
        // fallback for PHP and ext, class, function, constant
        // update fullnspath with fallback for functions

        $themes = new Rulesets("{$this->config->dir_root}/data/analyzers.sqlite",
                               $this->config->ext,
                               $this->config->dev,
                               $this->config->rulesets
                               );

        $exts = $themes->listAllAnalyzer('Extensions');
        $exts[] = 'php_constants';
        $exts[] = 'php_functions';

        foreach($exts as $ext) {
            $inifile = str_replace('Extensions\Ext', '', $ext) . '.ini';
            $fullpath = "{$this->config->dir_root}/data/$inifile";

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