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

use Exakat\Analyzer\Themes;
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
    private $dictCode = null;
    
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

        $this->logTime('Start');
        display('Start load final');

        $this->init();

        $this->fixFullnspathFunctions();
        $this->log->log('fixFullnspathFunctions');
        $this->spotPHPNativeFunctions(); // This one saves SQL table functioncalls
        $this->log->log('spotPHPNativeFunctions');

        // This is needed AFTER functionnames are found
        $this->spotFallbackConstants();
        $this->log->log('spotFallbackConstants');
        $task = new FixFullnspathConstants($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log->log('FixFullnspathConstants');
        $this->spotPHPNativeConstants();
        $this->log->log('spotPHPNativeConstants');

        $this->setParentDefinition();
        $this->log->log('setParentDefinition');
        $this->setClassAliasDefinition();
        $this->log->log('setClassAliasDefinition');
        $this->makeClassConstantDefinition();
        $this->log->log('makeClassConstantDefinition');
        $task = new MakeClassMethodDefinition($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log->log('MakeClassMethodDefinition');
        
        $this->setConstantDefinition();
        $this->log->log('setConstantDefinition');

        $task = new SetClassRemoteDefinitionWithInjection($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log->log('SetClassRemoteDefinitionWithInjection');

        $this->defaultIdentifiers();
        $this->log->log('defaultIdentifiers');
        $this->propagateConstants();
        $this->log->log('propagateConstants');

        $task = new SetClassPropertyRemoteDefinition($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log->log('SetClassPropertyRemoteDefinition');
        $task = new SetClassMethodRemoteDefinition($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log->log('SetClassMethodRemoteDefinition');
        $task = new SetClassRemoteDefinitionWithTypehint($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log->log('SetClassRemoteDefinitionWithTypehint');
        $this->setClassRemoteDefinitionWithReturnTypehint();
        $this->log->log('setClassRemoteDefinitionWithReturnTypehint');

        $task = new SetCloneLink($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log->log('SetCloneLink');

        $task = new SetClassRemoteDefinitionWithLocalNew($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log->log('SetClassRemoteDefinitionWithLocalNew');
        $task = new SetClassRemoteDefinitionWithParenthesis($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log->log('SetClassRemoteDefinitionWithParenthesis');
        $this->setClassPropertyDefinitionWithTypehint();
        $this->log->log('setClassPropertyDefinitionWithTypehint');
        $task = new SetArrayClassDefinition($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log->log('setArrayClassDefinition');
        $task = new SetStringMethodDefinition($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log->log('SetStringMethodDefinition');

        $task = new SetClassPropertyDefinitionWithFluentInterface($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log->log('SetClassPropertyDefinitionWithFluentInterface');

        $task = new OverwrittenMethods($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log->log('OverwrittenMethods');
        $task = new OverwrittenProperties($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log->log('OverwrittenProperties');
        $task = new OverwrittenConstants($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log->log('OverwrittenConstants');

        $task = new SolveTraitMethods($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log->log('SolveTraitMethods');

        $task = new FollowClosureDefinition($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log->log('FollowClosureDefinition');
        
        $task = new CreateVirtualProperty($this->gremlin, $this->config, $this->datastore);
        $task->run();
        $this->log->log('CreateVirtualProperty');

        display('End load final');
        $this->logTime('Final');
    }

    private function logTime($step) {
        static $log, $begin, $end, $start;

        if ($log === null) {
            $log = fopen("{$this->config->projects_root}/projects/{$this->config->project}/log/loadfinal.timing.csv", 'w+');
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

    // Can't move this to Query, because atoms and functioncall dictionaries are still unloaded
    private function fixFullnspathFunctions() {
        display("fixing Fullnspath for Functions");

        $query = <<<GREMLIN
g.V().hasLabel("Functioncall")
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

        display($result->toInt().' fixed Fullnspath for Functions');
        $this->log->log(__METHOD__);
    }
    
    private function spotPHPNativeConstants() {
        $title = 'mark PHP native constants call';
        $constants = call_user_func_array('array_merge', $this->PHPconstants);
        $constants = array_filter($constants, function ($x) { return strpos($x, '\\') === false;});
        // May be array_keys
        $constantsPHP = array_values($constants);

        $query = new Query(0, $this->config->project, 'spotPHPNativeConstants', null, $this->datastore);
        $query->atomIs('Identifier', Analyzer::WITHOUT_CONSTANTS)
              ->has('fullnspath')
              ->values('code')
              ->unique();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());

        $constants = array_values(array_intersect($result->toArray(), $this->dictCode->translate($constantsPHP) ));

        if (empty($constants)) {
            display('No PHP Constants');
        } else {
            $query = <<<GREMLIN
g.V().hasLabel("Identifier")
     .has("fullnspath")
     .not(where( __.in("DEFINITION")))
     .not(where( __.in("NAME").hasLabel("Defineconstant")))
     .filter{ it.get().value("code") in arg1 }
     .sideEffect{
         tokens = it.get().value("fullnspath").tokenize('\\\\');
         fullnspath = "\\\\" + tokens.last();
         it.get().property("fullnspath", fullnspath); 
     }.count();

GREMLIN;

            $this->runQuery($query, $title, array('arg1' => $constants), __METHOD__);
        }

        $this->log->log(__METHOD__);
    }
    
    private function spotPHPNativeFunctions() {
        $title = 'mark PHP native functions call';

        $query = <<<GREMLIN
g.V().hasLabel("Functioncall")
     .has("fullnspath")
     .has("token", "T_STRING")
     .not(where( __.in("DEFINITION")))
     .filter{ parts = it.get().value('fullnspath').tokenize('\\\\'); parts.size() > 1 }
     .map{ name = parts.last().toLowerCase();}
     .unique()
GREMLIN;
        $fallingback = $this->gremlin->query($query)->toArray();

        if (!empty($fallingback)) {
            $phpfunctions = array_merge(...$this->PHPfunctions);
            $phpfunctions = array_map('strtolower', $phpfunctions);
            $phpfunctions = array_values($phpfunctions);

            $diff = array_values(array_intersect($fallingback, $phpfunctions));

            $query = <<<GREMLIN
g.V().hasLabel("Functioncall")
     .has("fullnspath")
     .has("token", "T_STRING")
     .not(where( __.in("DEFINITION")))
     .filter{ parts = it.get().value('fullnspath').tokenize('\\\\'); parts.size() > 1 }
     .filter{ name = parts.last().toLowerCase(); name in arg1 }
     .sideEffect{
         fullnspath = "\\\\" + name;
         it.get().property("fullnspath", fullnspath); 
     }.count();

GREMLIN;
            $this->runQuery($query, $title, array('arg1' => $diff), __METHOD__);
        }

        $query = <<<GREMLIN
g.V().hasLabel("Functioncall")
     .has("fullnspath")
     .groupCount('m')
     .by("fullnspath")
     .cap('m')
GREMLIN;
        $fixed = $this->gremlin->query($query)->toArray();
        if (!empty($fixed)) {
            $this->datastore->addRow('functioncalls', $fixed[0]);
        }

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

        display('   /'.$title);
        $this->logTime('end '.$title);
        $this->log->log($method);
    }

    private function spotFallbackConstants() {
        $this->logTime('spotFallbackConstants');
        display("spotFallbackConstants\n");
        
        // Define-style constant definitions
        $query = <<<GREMLIN
g.V().hasLabel("Defineconstant")
     .out("NAME")
     .hasLabel("String").has("noDelimiter").not( has("noDelimiter", '') )
     .filter{ (it.get().value("noDelimiter") =~ "(\\\\\\\\)\\$").getCount() == 0 }
     .values('fullnspath').unique();
GREMLIN;
        $defineConstants = $this->gremlin->query($query)
                                         ->toArray();

        $query = <<<GREMLIN
g.V().hasLabel("Const")
     .not( where( __.in("CONST") ) )  // Not a class or an interface
     .out("CONST")
     .out("NAME")
     .filter{ (it.get().value("fullnspath") =~ "^\\\\\\\\[^\\\\\\\\]+\\$").getCount() == 1 }
     .values('fullnspath').unique();

GREMLIN;
        $constConstants = $this->gremlin->query($query)
                                        ->toArray();

        $constants = array_merge($constConstants, $defineConstants);
        $this->logTime('constants : '.count($constants));

        if (empty($constants)) {
            display('Link constant definitions : skipping.');
            return;
        }
        if (!empty($defineConstants)) {
            // This only works with define() and case sensitivity
            $query = <<<GREMLIN
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
            $res = $this->gremlin->query($query, array('arg1' => $defineConstants));

            // Second round, with fallback to global constants
            // Based on define() definitions
            $this->logTime('constants define : '.count($defineConstants));

            $query = <<<GREMLIN
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
            $res = $this->gremlin->query($query, array('arg1' => $defineConstants));
        }

        $this->logTime('constants const : '.count($constConstants));
        if (!empty($constConstants)) {
            // Based on const definitions
            $query = <<<GREMLIN
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
             .filter{ (it.get().value("fullnspath") =~ "^\\\\\\\\[^\\\\\\\\]+\\$").getCount() == 1 }
       )
       .count()

GREMLIN;
            $res = $this->gremlin->query($query, array('arg1' => $constConstants));
        }
            
        // TODO : handle case-insensitive
        $this->logTime('Constant definitions');
        display('Link constant definitions');
        $this->log->log(__METHOD__);
    }

    private function setClassRemoteDefinitionWithReturnTypehint() {
        $query = new Query(0, $this->config->project, 'linkMethodcall', null, $this->datastore);
        $query->atomIs('Methodcall', Analyzer::WITHOUT_CONSTANTS)
              ->_as('method')
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('OBJECT')
              ->inIs('DEFINITION')
              ->atomIs('Propertydefinition', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('DEFINITION')
              ->inIs('LEFT')
              ->atomIs('Assignation', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('RIGHT') 
              ->atomIs(Analyzer::$FUNCTIONS_CALLS, Analyzer::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->outIs('RETURNTYPE')
              ->inIs('DEFINITION')
              ->atomIs('Class', Analyzer::WITHOUT_CONSTANTS)
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs('METHOD')
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'method')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countM = $result->toInt();

        $query = new Query(0, $this->config->project, 'linkMember', null, $this->datastore);
        $query->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
              ->_as('member')
              ->hasNoIn('DEFINITION')
              ->outIs('MEMBER')
              ->atomIs('Name', Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('code', 'name')
              ->inIs('MEMBER')
              ->outIs('OBJECT')
              ->inIs('DEFINITION')
              ->atomIs('Propertydefinition', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('DEFINITION')
              ->inIs('LEFT')
              ->atomIs('Assignation', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('RIGHT') 
              ->atomIs(Analyzer::$FUNCTIONS_CALLS, Analyzer::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->outIs('RETURNTYPE')
              ->inIs('DEFINITION')
              ->atomIs('Class', Analyzer::WITHOUT_CONSTANTS)
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs('PPP')
              ->outIs('PPP')
              ->samePropertyAs('propertyname', 'name', Analyzer::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'member')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countP = $result->toInt();

        $query = new Query(0, $this->config->project, 'linkMember', null, $this->datastore);
        $query->atomIs('Staticconstant', Analyzer::WITHOUT_CONSTANTS)
              ->_as('constante')
              ->hasNoIn('DEFINITION')
              ->outIs('CONSTANT')
              ->atomIs('Name', Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('code', 'name')
              ->inIs('CONSTANT')
              ->outIs('CLASS')
              ->inIs('DEFINITION')
              ->atomIs('Propertydefinition', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('DEFINITION')
              ->inIs('LEFT')
              ->atomIs('Assignation', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('RIGHT') 
              ->atomIs(Analyzer::$FUNCTIONS_CALLS, Analyzer::WITHOUT_CONSTANTS)
              ->inIs('DEFINITION')
              ->outIs('RETURNTYPE')
              ->inIs('DEFINITION')
              ->atomIs('Class', Analyzer::WITHOUT_CONSTANTS)
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs('CONST')
              ->outIs('CONST')
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', Analyzer::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'constante')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countC = $result->toInt();

        display("Set ".($countP + $countM + $countC)." method, constants and properties remote with return typehint");
    }

    private function setClassPropertyDefinitionWithTypehint() {
        $query = new Query(0, $this->config->project, 'linkTypedProperty', null, $this->datastore);
        $query->atomIs('Propertydefinition', Analyzer::WITHOUT_CONSTANTS)
              ->_as('property')
              ->outIs('DEFINITION')
              ->inIs('OBJECT')
              ->atomIs('Methodcall', Analyzer::WITHOUT_CONSTANTS)
              ->_as('call')
              ->outIs('METHOD')
              ->atomIs('Methodcallname', Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->back('first')
              ->inIs('PPP')
              ->outIs('TYPEHINT')
              ->inIs('DEFINITION')
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'call')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countM = $result->toInt();

        $query = new Query(0, $this->config->project, 'linkTypedProperty', null, $this->datastore);
        $query->atomIs('Propertydefinition', Analyzer::WITHOUT_CONSTANTS)
              ->_as('property')
              ->outIs('DEFINITION')
              ->inIs('OBJECT')
              ->atomIs('Member', Analyzer::WITHOUT_CONSTANTS)
              ->_as('call')
              ->outIs('MEMBER')
              ->atomIs('Name', Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->back('first')
              ->inIs('PPP')
              ->outIs('TYPEHINT')
              ->inIs('DEFINITION')
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs('PPP')
              ->outIs('PPP')
              ->samePropertyAs('propertyname', 'name', Analyzer::CASE_INSENSITIVE)
              ->addETo('DEFINITION', 'call')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countP = $result->toInt();

        $query = new Query(0, $this->config->project, 'linkMember', null, $this->datastore);
        $query->atomIs('Propertydefinition', Analyzer::WITHOUT_CONSTANTS)
              ->_as('property')
              ->outIs('DEFINITION')
              ->inIs('CLASS')
              ->atomIs('Staticconstant', Analyzer::WITHOUT_CONSTANTS)
              ->_as('call')
              ->outIs('CONSTANT')
              ->atomIs('Name', Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('lccode', 'name')
              ->back('first')
              ->inIs('PPP')
              ->outIs('TYPEHINT')
              ->inIs('DEFINITION')
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs('CONST')
              ->outIs('CONST')
              ->samePropertyAs('code', 'name', Analyzer::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'call')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countC = $result->toInt();

        $count = $countP + $countM;
        display("Set $count method, class and properties with typehinted properties");
        $this->log->log(__METHOD__);
    }

    private function setClassAliasDefinition() {
        $query = new Query(0, $this->config->project, 'setClassAliasDefinition', null, $this->datastore);
        $query->atomIs(array('Class', 'Interface', 'Trait'), Analyzer::WITHOUT_CONSTANTS)
              ->_as('method')
              ->savePropertyAs('fullnspath', 'fnp')
              ->outIs('DEFINITION')
              ->is('rank', 0)
              ->inIs('ARGUMENT')
              ->atomIs('Classalias', Analyzer::WITHOUT_CONSTANTS)
              ->outWithRank('ARGUMENT', 1)
              ->outIs('DEFINITION')
              ->atomIs(array('Identifier', 'Nsname', 'Newcall', 'Name'), Analyzer::WITHOUT_CONSTANTS)
              ->dedup('')
              ->property('fullnspath', 'fnp')
              ->addEFrom('DEFINITION', 'method')
              ->returnCount();
        $query->prepareRawQuery();   
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count = $result->toInt();

        display("Set $count class alias definitions");
        $this->log->log(__METHOD__);
    }

    private function setParentDefinition() {
        $query = new Query(0, $this->config->project, 'setParentDefinition 1', null, $this->datastore);
        $query->atomIs('Parent', Analyzer::WITHOUT_CONSTANTS)
              ->_as('parent')
              ->goToClass()
              ->outIs('EXTENDS')
              ->inIs('DEFINITION')
              ->addETo('DEFINITION', 'parent')
              ->returnCount();
        $query->prepareRawQuery();   
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count1 = $result->toInt();

        $query = new Query(0, $this->config->project, 'setParentDefinition 2', null, $this->datastore);
        $query->atomIs('String', Analyzer::WITHOUT_CONSTANTS)
              ->fullnspathIs('\\\\parent', Analyzer::CASE_SENSITIVE)
              ->_as('parent')
              ->goToClass()
              ->outIs('EXTENDS')
              ->inIs('DEFINITION')
              ->addETo('DEFINITION', 'parent')
              ->returnCount();
        $query->prepareRawQuery();   
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count2 = $result->toInt();
        
        $count = $count1 + $count2;
        display("Set $count parent definitions");
    }
    
    private function setConstantDefinition() {
        $query = <<<'GREMLIN'
g.V().hasLabel("Identifier", "Nsname")
     .where(__.sideEffect{ constante = it.get();}.in("DEFINITION").coalesce( __.hasLabel("Constant").out("VALUE"),
                                                                             __.hasLabel("Defineconstant").out("VALUE"))
     .sideEffect{ 
        if ("intval" in it.get().keys()) {
            constante.property("intval", it.get().value("intval")); 
        }
        if ("boolean" in it.get().keys()) {
            constante.property("boolean", it.get().value("boolean")); 
        }
        if ("noDelimiter" in it.get().keys()) {
            constante.property("noDelimiter", it.get().value("noDelimiter")); 
        }
        if ("isNull" in it.get().keys()) {
            constante.property("isNull", it.get().value("isNull")); 
        }
     }
).count()
GREMLIN;
        $res = $this->gremlin->query($query);
        $count = $res->toInt();
        display("Set $count constant definitions");
    }

    private function makeClassConstantDefinition() {
        // Create link between Class constant and definition
        $query = new Query(0, $this->config->project, 'fixFullnspathConstants', null, $this->datastore);
        $query->atomIs('Staticconstant', Analyzer::WITHOUT_CONSTANTS)
              ->hasNoIn('DEFINITION')
              ->outIs('CONSTANT')
              ->savePropertyAs('code', 'name')
              ->back('first')
              ->outIs('CLASS')
              ->atomIs(array('Identifier', 'Nsname', 'Self', 'Static', 'Parent'), Analyzer::WITHOUT_CONSTANTS)
              ->savePropertyAs('fullnspath', 'classe')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Classanonymous', 'Interface'), Analyzer::WITHOUT_CONSTANTS)
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs('CONST')
              ->atomIs('Const', Analyzer::WITHOUT_CONSTANTS)
              ->outIs('CONST')
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', Analyzer::CASE_SENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        
        $count = $result->toInt();

        display("Create $count link between Class constant and definition");
        $this->logTime('Class::constant definition');
    }

    private function defaultIdentifiers() {
        display("defaulting Identifiers and Nsname");
        // fix path for constants with Const
        // noDelimiter is set at the same moment as boolean and intval. Any of them is the same
        $query = <<<GREMLIN
g.V().hasLabel("Identifier")
     .not(has("noDelimiter"))
     .sideEffect{ 
        it.get().property("noDelimiter", it.get().value("fullcode"));
        it.get().property("intval",      0);
        it.get().property("boolean",     true);
        it.get().property("isNull",      false);
      }
     .count();
GREMLIN;
        $res = $this->gremlin->query($query)->toInt();
        display("defaulting $res Identifiers");

        // noDelimiter is set at the same moment as boolean and intval. Any of them is the same
        $query = <<<GREMLIN
g.V().hasLabel("Nsname")
     .not(has("noDelimiter"))
     .sideEffect{ 
        it.get().property("noDelimiter", '');
        it.get().property("intval",      0);
        it.get().property("boolean",     false);
        it.get().property("isNull",      true);
      }
     .count();
GREMLIN;

        $res = $this->gremlin->query($query)->toInt();
        display("defaulting $res Nsname");
        $this->log->log(__METHOD__);
    }

    private function propagateConstants($level = 0) {
        $total = 0;
        
        //Currently handles + - * / % . << >> ** ()
        //Currently handles intval, boolean, noDelimiter (String)
        
        //Needs realval, nullval, arrayval

        display("propagating Constant value in Const");
        // fix path for constants with Const
        // noDelimiter is set at the same moment as boolean and intval. Any of them is the same
        $query = <<<GREMLIN
g.V().hasLabel("Identifier", "Nsname", "Staticconstant").not(has("noDelimiter")).as("init")
     .in("DEFINITION").out('VALUE').has("noDelimiter").sideEffect{ x = it.get(); }
     .select('init').sideEffect{ 
        it.get().property("noDelimiter", x.value("noDelimiter"));
        it.get().property("intval",      x.value("intval"));
        it.get().property("boolean",     x.value("boolean"));
      }
     .count();
GREMLIN;

        $res = $this->gremlin->query($query)->toInt();
        $total += $res;
        display("propagating $res constants");

        display("propagating Constant value in Concatenations");
        // fix path for constants with Const
        $query = <<<GREMLIN
g.V().hasLabel("Concatenation").not(has("noDelimiter"))
     .sideEffect{ x = []; }
     .where( __.out("CONCAT").hasLabel("Identifier", "Nsname", "Staticconstant") )
     .not(where( __.out("CONCAT").not(has("noDelimiter")) ) )
     .where( __.out("CONCAT").order().by("rank").sideEffect{ x.add( it.get().value("noDelimiter") ) }.count() )
     .sideEffect{ 
        s = x.join("");
        it.get().property("noDelimiter", s);
        // Warning : PHP doesn't handle error that same way
        if (s.isInteger()) {
            it.get().property("intval", s.toInteger());
        } else {
            it.get().property("intval", 0);
        }
        it.get().property("boolean", it.get().property("intval") != 0);
        
        x = null;
      }
     .count();
GREMLIN;
        $res = $this->gremlin->query($query)->toInt();
        $total += $res;
        display("propagating $res Concatenations with constants");

        display("propagating Constant value in Addition");
        // fix path for constants with Const
        $query = <<<GREMLIN
g.V().hasLabel("Addition").not(has("intval"))
     .sideEffect{ x = []; }
     .where( __.out("LEFT", "RIGHT").hasLabel("Identifier", "Nsname") )
     .not(where( __.out("LEFT", "RIGHT").not(has("intval")) ) )
     .where( __.out("LEFT", "RIGHT").sideEffect{ x.add( it.get().value("intval") ) }.count() )
     .sideEffect{ 
        if (it.get().value("token") == 'T_PLUS') {
          i = x[0] + x[1];
        } else if (it.get().value("token") == 'T_MINUS') {
          i = x[0] - x[1];
        }
        it.get().property("intval", i); 
        it.get().property("boolean", it.get().property("intval") != 0);
        it.get().property("noDelimiter", i.toString()); 

        i = null;
     }
     .count();
GREMLIN;
        $res = $this->gremlin->query($query)->toInt();
        $total += $res;
        display("propagating $res Addition with constants");

        display("propagating Constant value in Power");
        // fix path for constants with Const
        $query = <<<GREMLIN
g.V().hasLabel("Power").not(has("intval"))
     .sideEffect{ x = []; }
     .where( __.out("LEFT", "RIGHT").hasLabel("Identifier", "Nsname") )
     .not(where( __.out("LEFT", "RIGHT").not(has("intval")) ) )
     .where( __.out("LEFT").sideEffect{ x.add( it.get().value("intval") ) }.count() )
     .where( __.out("RIGHT").sideEffect{ x.add( it.get().value("intval") ) }.count() )
     .sideEffect{ 
        i = x[0] ** x[1];
        it.get().property("intval", i); 
        it.get().property("boolean", it.get().property("intval") != 0);
        it.get().property("noDelimiter", i.toString()); 
        
        i = null;
     }
     .count();
GREMLIN;
        $res = $this->gremlin->query($query)->toInt();
        $total += $res;
        display("propagating $res Power with constants");
        
        display("propagating Constant value in Comparison");
        // fix path for constants with Const
        $query = <<<GREMLIN
g.V().hasLabel("Comparison").has("constant", true).not(has("boolean"))
     .sideEffect{ x = []; }
     .where( __.out("LEFT", "RIGHT").hasLabel("Identifier", "Nsname") )
     .not(where( __.out("LEFT", "RIGHT").not(has("boolean")) ) )
     .where( __.out("LEFT").sideEffect{ x.add( it.get().value("intval") ) }.count() )
     .where( __.out("RIGHT").sideEffect{ x.add( it.get().value("intval") ) }.count() )
     .sideEffect{ 
        if (it.get().value("token") == 'T_GREATER') {
          i = x[0] > x[1];
        } else if (it.get().value("token") == 'T_SMALLER') {
          i = x[0] < x[1];
        } else if (it.get().value("token") == 'T_IS_GREATER_OR_EQUAL') {
          i = x[0] >= x[1];
        } else if (it.get().value("token") == 'T_IS_SMALLER_OR_EQUAL') {
          i = x[0] <= x[1];
        } else if (it.get().value("token") == 'T_IS_EQUAL' ||
                   it.get().value("token") == 'T_IS_IDENTICAL') {
          i = x[0] == x[1];
        } else if (it.get().value("token") == 'T_IS_NOT_EQUAL'||
                   it.get().value("token") == 'T_IS_NOT_IDENTICAL') {
          i = x[0] != x[1];
        } else if (it.get().value("token") == 'T_SPACESHIP') {
          i = x[0] <=> x[1];
        }
        it.get().property("intval", i); 
        it.get().property("boolean", it.get().property("intval") != 0);
        it.get().property("noDelimiter", i.toString()); 
        
        i = null;
     }
     .count();
GREMLIN;
        $res = $this->gremlin->query($query)->toInt();
        $total += $res;
        display("propagating $res Comparison with constants");


        display("propagating Constant value in Logical");
        // fix path for constants with Const
        $query = <<<GREMLIN
g.V().hasLabel("Logical").has("constant", true).not(has("boolean"))
     .sideEffect{ x = []; }
     .where( __.out("LEFT", "RIGHT").hasLabel("Identifier", "Nsname") )
     .not(where( __.out("LEFT", "RIGHT").not(has("boolean")) ) )
     .where( __.out("LEFT").sideEffect{ x.add( it.get().value("intval") ) }.count() )
     .where( __.out("RIGHT").sideEffect{ x.add( it.get().value("intval") ) }.count() )
     .sideEffect{ 
        if (it.get().value("token") == 'T_BOOLEAN_AND' ||
            it.get().value("token") == 'T_LOGICAL_AND') {
          i = x[0] && x[1];
        } else if (it.get().value("token") == 'T_BOOLEAN_OR' ||
                   it.get().value("token") == 'T_LOGICAL_OR') {
          i = x[0] || x[1];
        } else if (it.get().value("token") == 'T_LOGICAL_XOR') {
          i = x[0] ^ x[1];
        } else if (it.get().value("token") == 'T_AND') {
          i = x[0] & x[1];
        } else if (it.get().value("token") == 'T_XOR') {
          i = x[0] ^ x[1];
        } else if (it.get().value("token") == 'T_OR') {
          i = x[0] | x[1];
        } 
        it.get().property("intval", i); 
        it.get().property("boolean", it.get().property("intval") != 0);
        it.get().property("noDelimiter", i.toString()); 
        
        i = null;
     }
     .count();
GREMLIN;
        $res = $this->gremlin->query($query)->toInt();
        $total += $res;
        display("propagating $res Logical with constants");

        display("propagating Constant value in Parenthesis");
        // fix path for constants with Const
        $query = <<<GREMLIN
g.V().hasLabel("Parenthesis").not(has("intval"))
     .where( __.out("CODE").has("intval"))
     .where( __.out("CODE").sideEffect{ x = it.get(); }.count())
     .sideEffect{ 
        it.get().property("intval", x.value("intval")); 
        it.get().property("boolean", x.value("boolean"));
        it.get().property("noDelimiter", x.value("noDelimiter")); 
        
        x = null;
     }
     .count();
GREMLIN;

        $res = $this->gremlin->query($query)->toInt();
        $total += $res;
        display("propagating $res Parenthesis with constants");

        display("propagating Constant value in Not");
        // fix path for constants with Const
        $query = <<<GREMLIN
g.V().hasLabel("Not").not(has("intval"))
     .has("token", within("T_BANG", "T_TILDE"))
     .where( __.out("NOT").has("intval"))
     .where( __.out("NOT").sideEffect{ x = it.get(); }.count())
     .sideEffect{ 
        if (it.get().value("token") == 'T_BANG') {
          i = !x.value("intval");
        } else if (it.get().value("token") == 'T_TILDE') { 
          i = ~x.value("intval");
        }
        it.get().property("intval", i); 
        it.get().property("boolean", i);
        it.get().property("noDelimiter", i); 
        
        i = null;
     }
     .count();
GREMLIN;

        $res = $this->gremlin->query($query)->toInt();
        $total += $res;
        display("propagating $res Not with constants");

        display("propagating Constant value in Coalesce");
        // fix path for constants with Const
        $query = <<<GREMLIN
g.V().hasLabel("Coalesce").not(has("intval"))
     .sideEffect{ x = []; }
     .where( __.out("LEFT", "RIGHT").hasLabel("Identifier", "Nsname") )
     .not(where( __.out("LEFT", "RIGHT").not(has("intval")) ) )
     .where( __.out("LEFT").sideEffect{ x.add( it.get() ) }.count() )
     .where( __.out("RIGHT").sideEffect{ x.add( it.get() ) }.count() )
     .sideEffect{ 
        if (x[0].value('noDelimiter') == '') {
          i = x[1];
        } else {
          i = x[0];
        }
        it.get().property("intval", i.value("intval")); 
        it.get().property("boolean", i.value("boolean"));
        it.get().property("noDelimiter", i.value("noDelimiter")); 
        
        i = null;
     }
     .count();
GREMLIN;

        $res = $this->gremlin->query($query)->toInt();
        $total += $res;
        display("propagating $res Coalesce with constants");

        display("propagating Constant value in Ternary");
        // fix path for constants with Const
        $query = <<<GREMLIN
g.V().hasLabel("Ternary").has("constant", true).not(has("intval"))
     .sideEffect{ x = []; }
     .where( __.out("CONDITION", "THEN", "ELSE").hasLabel("Identifier", "Nsname") )
     .not(where( __.out("CONDITION", "THEN", "ELSE").not(has("intval")) ) )
     .where( __.out("CONDITION").sideEffect{ x.add( it.get() ) }.count() )
     .where( __.out("THEN").sideEffect{ x.add( it.get() ) }.count() )
     .where( __.out("ELSE").sideEffect{ x.add( it.get() ) }.count() )
     .sideEffect{ 
        if (x[0].value("boolean") == true) {
          if (x[1].label() == 'Void') {
              i = x[0];
          } else {
              i = x[1];
          }
        } else {
          i = x[2];
        }
        it.get().property("intval", i.value("intval")); 
        it.get().property("boolean", i.value("boolean"));
        it.get().property("noDelimiter", i.value("noDelimiter")); 
        
        i = null;
     }
     .count();
GREMLIN;

        $res = $this->gremlin->query($query)->toInt();
        $total += $res;
        display("propagating $res Ternary with constants");

        display("propagating Constant value in Bitshift");
        // fix path for constants with Const
        $query = <<<GREMLIN
g.V().hasLabel("Bitshift").not(has("intval"))
     .sideEffect{ x = []; }
     .where( __.out("LEFT", "RIGHT").hasLabel("Identifier", "Nsname") )
     .not(where( __.out("LEFT", "RIGHT").not(has("intval")) ) )
     .where( __.out("LEFT").sideEffect{ x.add( it.get().value("intval") ) }.count() )
     .where( __.out("RIGHT").sideEffect{ x.add( it.get().value("intval") ) }.count() )
     .sideEffect{ 
        if (it.get().value("token") == 'T_SL') {
          i = x[0] << x[1];
        } else if (it.get().value("token") == 'T_SR') {
          i = x[0] >> x[1];
        }
        it.get().property("intval", i); 
        it.get().property("boolean", it.get().property("intval") != 0);
        it.get().property("noDelimiter", i.toString()); 
        
        i = null;
     }
     .count();
GREMLIN;

        $res = $this->gremlin->query($query)->toInt();
        $total += $res;
        display("propagating $res Bitshift with constants");

        display("propagating Constant value in Multiplication");
        // fix path for constants with Const
        $query = <<<GREMLIN
g.V().hasLabel("Multiplication").not(has("intval"))
     .sideEffect{ x = []; }
     .where( __.out("LEFT", "RIGHT").hasLabel("Identifier", "Nsname") )
     .not(where( __.out("LEFT", "RIGHT").not(has("intval")) ) )
     .where( __.out("LEFT", "RIGHT").sideEffect{ x.add( it.get().value("intval") ) }.count() )
     .sideEffect{ 
        if (it.get().value("token") == 'T_STAR') {
          i = x[0] * x[1];
        } else if (it.get().value("token") == 'T_SLASH') {
          if (x[1] != 0) {
              i = x[0] / x[1];
              i = i.setScale(0, BigDecimal.ROUND_HALF_DOWN).toInteger();
          } else {
              i = 0;
          }
        } else if (it.get().value("token") == 'T_PERCENTAGE') {
          if (x[0] != 0) {
              i = x[1] % x[0];
          } else {
              i = 0;
          }
        } // Final else is an error!
        it.get().property("intval", i); 
        it.get().property("boolean", it.get().property("intval") != 0);
        it.get().property("noDelimiter", i.toString()); 
        
        i = null;
     }
     .count();
GREMLIN;

        $res = $this->gremlin->query($query)->toInt();
        $total += $res;
        display("propagating $res Multiplication with constants");

        if ($total > 0 && $level < 5) {
            $this->propagateConstants();
        }
        $this->log->log(__METHOD__);
    }

    private function init() {
        // fallback for PHP and ext, class, function, constant
        // update fullnspath with fallback for functions

        $themes = new Themes("{$this->config->dir_root}/data/analyzers.sqlite",
                             $this->config->ext,
                             $this->config->themas
                             );

        $exts = $themes->listAllAnalyzer('Extensions');
        $exts[] = 'php_constants';
        $exts[] = 'php_functions';

        foreach($exts as $ext) {
            $inifile = str_replace('Extensions\Ext', '', $ext).'.ini';
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
