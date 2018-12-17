<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

use Exakat\Analyzer\Themes;
use Exakat\Analyzer\Analyzer;
use Exakat\Config;
use Exakat\Data\Methods;
use Exakat\Query\Query;
use Exakat\Query\DSL\DSL;
use Exakat\Data\Dictionary;
use Exakat\GraphElements;
use Exakat\Exceptions\GremlinException;

class LoadFinal extends Tasks {
    const CONCURENCE = self::ANYTIME;

    private $linksIn = '';

    private $PHPconstants = array();
    private $PHPfunctions = array();
    private $dictCode = null;

    public function run() {
        $this->linksIn = GraphElements::linksAsList();

        $this->dictCode = Dictionary::factory($this->datastore);

        $this->logTime('Start');
        display('Start load final');

        $this->init();

        $this->fixFullnspathFunctions();
        $this->spotPHPNativeFunctions(); // This one saves SQL table functioncalls

        // This is needed AFTER functionnames are found
        $this->spotFallbackConstants();
        $this->fixFullnspathConstants();
        $this->spotPHPNativeConstants();

        $this->setParentDefinition();
        $this->setClassAliasDefinition();
        $this->makeClassConstantDefinition();
        $this->makeClassMethodDefinition();
        
        $this->setConstantDefinition();

        $this->defaultIdentifiers();
        $this->propagateConstants();

        $this->setClassPropertyRemoteDefinition();
        $this->setClassMethodRemoteDefinition();
        $this->setClassRemoteDefinitionWithTypehint();
        $this->setArrayClassDefinition();
//        $this->setStringMethodDefinition();

        $this->overwrittenMethods();
        $this->overwrittenProperties();
        $this->overwrittenConstants();

        $this->solveTraitMethods();

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

    private function fixFullnspathFunctions() {
        display("fixing Fullnspath for Functions");

        // Can't move this to Query, because atoms and functioncall dictionaries are still unloaded
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
    
    private function fixFullnspathConstants() {
        display("fixing Fullnspath for Constants");
        // fix path for constants with Const

        $query = new Query(0, $this->config->project, 'fixFullnspathConstants', null, $this->datastore);
        $query->atomIs(array('Identifier', 'Nsname'))
              ->has('fullnspath')
              ->_as('identifier')
              ->savePropertyAs('fullnspath', 'cc')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Trait', 'Interface', 'Constant', 'Defineconstant'))
              ->raw(<<<GREMLIN
coalesce( __.out("ARGUMENT").has("rank", 0), 
          __.hasLabel("Constant").out('NAME'), 
          filter{ true; })
GREMLIN
, array(), array())
              ->savePropertyAs('fullnspath', 'actual')
              ->filter('actual != cc', array())
              ->back('identifier')
              ->setProperty('fullnspath', 'actual')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        
        display("Fixed Fullnspath for Constants");
        $this->log->log(__METHOD__);
    }

    private function spotPHPNativeConstants() {
        $title = 'mark PHP native constants call';
        $constants = call_user_func_array('array_merge', $this->PHPconstants);
        $constants = array_filter($constants, function ($x) { return strpos($x, '\\') === false;});
        // May be array_keys
        $constantsPHP = array_values($constants);

        $query = new Query(0, $this->config->project, 'spotPHPNativeConstants', null, $this->datastore);
        $query->atomIs('Identifier')
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
     .not(where( __.in("ARGUMENT").hasLabel("Defineconstant")))
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
        $fixed = $this->gremlin->query($query)->toArray()[0];
        if (!empty($fixed)) {
            $this->datastore->addRow('functioncalls', $fixed);
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

    private function overwrittenMethods() {
        $this->logTime('overwrittenMethods');
        
        $query = new Query(0, $this->config->project, 'overwrittenMethods', null, $this->datastore);
        $query->atomIs(array('Method', 'Magicmethod'))
              ->outIs('NAME')
              ->savePropertyAs('lccode', 'name')
              ->goToClass()
              ->goToAllImplements(Analyzer::EXCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('code', 'name',  Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addEFrom('OVERWRITE', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());

        $this->logTime($result->toInt().' overwrittenMethods end');
        display($result->toInt().' overwrittenMethods');
        $this->log->log(__METHOD__);
    }

    private function overwrittenProperties() {
        $this->logTime('overwrittenProperties');
        
        $query = new Query(0, $this->config->project, 'overwrittenProperties', null, $this->datastore);
        $query->atomIs('Propertydefinition')
              ->savePropertyAs('propertyname', 'name')
              ->goToClass()
              ->goToAllImplements(Analyzer::EXCLUDE_SELF)
              ->outIs('PPP')
              ->outIs('PPP')
              ->samePropertyAs('propertyname', 'name',  Analyzer::CASE_SENSITIVE)
              ->addEFrom('OVERWRITE', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());

        $this->logTime($result->toInt().' overwrittenProperties end');
        display($result->toInt().' overwrittenProperties');
        $this->log->log(__METHOD__);
    }

    private function overwrittenConstants() {
        $this->logTime('overwrittenConstants');
        
        $query = new Query(0, $this->config->project, 'overwrittenConstants', null, $this->datastore);
        $query->atomIs('Constant')
              ->outIs('NAME')
              ->savePropertyAs('code', 'name')
              ->goToClass()
              ->goToAllImplements(Analyzer::EXCLUDE_SELF)
              ->outIs('CONST')
              ->outIs('CONST')
              ->atomIs('Constant')
              ->outIs('NAME')
              ->samePropertyAs('code', 'name',  Analyzer::CASE_SENSITIVE)
              ->inIs('NAME')
              ->addEFrom('OVERWRITE', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());

        $this->logTime($result->toInt().' overwrittenConstant end');
        display($result->toInt().' overwrittenConstants');
        $this->log->log(__METHOD__);
    }

    private function setStringMethodDefinition() {
        $this->logTime('setStringMethodDefinition');
        display('setStringMethodDefinition');
        
        //$id, $project, $analyzer, $php
        $query = new Query(0, $this->config->project, 'setStringMethodDefinition', null, $this->datastore);
        $query->atomIs('String')
              ->has('fullnspath')
              ->is('count', 2)
              ->outWithRank('ARGUMENT', 1)
              ->atomIs('String')
              ->has('noDelimiter')
              ->savePropertyAs('noDelimiter', 'method')
              ->back('first')
              ->outWithRank('ARGUMENT', 0)
              ->atomIs('String')
              ->inIs('DEFINITION')
              ->outIs(array('MAGICMETHOD', 'METHOD'))
              ->outIs('NAME')
              ->samePropertyAs('fullcode', 'method', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addEto('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());

        $this->logTime('setStringMethodDefinition');
        $this->log->log(__METHOD__);
    }
    
    private function setArrayClassDefinition() {
        $this->logTime('setArrayClassDefinition');
        display('setArrayClassDefinition');
        
        //$id, $project, $analyzer, $php
        $query = new Query(0, $this->config->project, 'setArrayClassDefinition', null, $this->datastore);
        $query->atomIs('Arrayliteral')
              ->is('count', 2)
              ->outWithRank('ARGUMENT', 1)
              ->atomIs('String')
              ->has('noDelimiter')
              ->savePropertyAs('noDelimiter', 'method')
              ->back('first')
              ->outWithRank('ARGUMENT', 0)
              ->atomIs('String')
              ->inIs('DEFINITION')
              ->outIs(array('MAGICMETHOD', 'METHOD'))
              ->outIs('NAME')
              ->samePropertyAs('fullcode', 'method', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addEto('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());

        $this->logTime('setArrayClassDefinition end');
        $this->log->log(__METHOD__);
    }

    private function spotFallbackConstants() {
        $this->logTime('spotFallbackConstants');
        display("spotFallbackConstants\n");
        
        // Define-style constant definitions
        $query = <<<GREMLIN
g.V().hasLabel("Defineconstant")
     .out("ARGUMENT").has("rank", 0)
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
        g.V().hasLabel("Defineconstant")
             .as("a").out("ARGUMENT").has("rank", 0).hasLabel("String")
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
        g.V().hasLabel("Defineconstant")
             .as("a").out("ARGUMENT").has("rank", 0).hasLabel("String").has('fullnspath')
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
        g.V().hasLabel("Const")
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

    private function setClassPropertyRemoteDefinition() {
        display('Set class property remote definitions');

        // For static method calls, in traits
        $query = new Query(0, $this->config->project, 'linkStaticMethodCall', null, $this->datastore);
        $query->atomIs('Staticproperty')
              ->_as('property')
              ->hasNoIn('DEFINITION')
              ->outIs('MEMBER')
              ->atomIs('Staticpropertyname')
              ->savePropertyAs('code', 'name')
              ->inIs('MEMBER')
              ->outIs('CLASS')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Classanonymous', 'Trait'))
              ->GoToAllParentsTraits(Analyzer::INCLUDE_SELF)
              ->outIs('PPP')
              ->outIs('PPP')
              ->samePropertyAs('code', 'name', Analyzer::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'property')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count = $result->toInt();

        // For normal method calls, in traits
        $query = new Query(0, $this->config->project, 'linkStaticMethodCall', null, $this->datastore);
        $query->atomIs('Member')
              ->_as('property')
              ->hasNoIn('DEFINITION')
              ->outIs('MEMBER')
              ->savePropertyAs('code', 'name')
              ->inIs('MEMBER')
              ->outIs('OBJECT')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Classanonymous', 'Trait'))
              ->GoToAllParentsTraits(Analyzer::INCLUDE_SELF)
              ->outIs('PPP')
              ->outIs('PPP')
              ->samePropertyAs('propertyname', 'name', Analyzer::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'property')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count += $result->toInt();

        display("Set $count property remote definitions");
        $this->log->log(__METHOD__);
    }

    private function setClassRemoteDefinitionWithTypehint() {
        display('Set class method remote definitions with typehint');

        $query = new Query(0, $this->config->project, 'linkStaticMethodCall', null, $this->datastore);
        $query->atomIs('Methodcall')
              ->_as('method')
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->atomIs('Methodcallname')
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('OBJECT')
              ->inIs('DEFINITION')
              ->inIs('NAME')
              ->atomIs('Parameter')
              ->outIs('TYPEHINT')
              ->inIs('DEFINITION')
              ->atomIs('Class')
              ->outIs('METHOD')
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'method')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countM = $result->toInt();

        $query = new Query(0, $this->config->project, 'linkStaticMethodCall', null, $this->datastore);
        $query->atomIs('Member')
              ->_as('member')
              ->hasNoIn('DEFINITION')
              ->outIs('MEMBER')
              ->atomIs('Name')
              ->savePropertyAs('code', 'name')
              ->inIs('MEMBER')
              ->outIs('OBJECT')
              ->inIs('DEFINITION')
              ->inIs('NAME')
              ->atomIs('Parameter')
              ->outIs('TYPEHINT')
              ->inIs('DEFINITION')
              ->atomIs('Class')
              ->outIs('PPP')
              ->outIs('PPP')
              ->samePropertyAs('propertyname', 'name', Analyzer::CASE_SENSITIVE)
              ->addETo('DEFINITION', 'member')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $countP = $result->toInt();

        display("Set ".($countP + $countM)." method and properties remote with typehint");
        $this->log->log(__METHOD__);
    }

    private function setClassMethodRemoteDefinition() {
        display('Set class method remote definitions');

        // For static method calls, in traits
        $query = new Query(0, $this->config->project, 'linkStaticMethodCall', null, $this->datastore);
        $query->atomIs('Staticmethodcall')
              ->_as('method')
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->atomIs('Methodcallname')
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('CLASS')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Classanonymous', 'Trait'))
              ->GoToAllParentsTraits(Analyzer::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'name', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'method')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count = $result->toInt();

        display("Set $count method remote definitions");
        $this->log->log(__METHOD__);
    }

    private function setClassAliasDefinition() {
        display('Set class alias definitions');

        $query = new Query(0, $this->config->project, 'setClassAliasDefinition', null, $this->datastore);
        $query->atomIs(array('Class', 'Interface', 'Trait'))
              ->_as('method')
              ->outIs('DEFINITION')
              ->inIs('ARGUMENT')
              ->atomIs('Classalias')
              ->outWithRank('ARGUMENT', 1)
              ->outIs('DEFINITION')
              ->addEFrom('DEFINITION', 'method')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        $count = $result->toInt();

        display("Set ".$count." class alias definitions");
        $this->log->log(__METHOD__);    
    }

    private function setParentDefinition() {
        display('Set parent definitions');

        $query = <<<GREMLIN
g.V().hasLabel("Parent").as('parent')
     .repeat( __.in($this->linksIn) ).emit().until(hasLabel("Class", "Classanonymous")).hasLabel("Class", "Classanonymous")
     .out("EXTENDS").in("DEFINITION")
     .addE("DEFINITION")
     .to("parent")
     .count()
GREMLIN;
        $res = $this->gremlin->query($query);
        $count1 = $res->toInt();

        $query = <<<GREMLIN
g.V().hasLabel("String").has('fullnspath', '\\\\parent').as('parent')
     .repeat( __.in($this->linksIn) ).emit().until(hasLabel("Class", "Classanonymous")).hasLabel("Class", "Classanonymous")
     .out("EXTENDS").in("DEFINITION")
     .addE("DEFINITION")
     .to("parent")
     .count()
GREMLIN;
        $res = $this->gremlin->query($query);
        $count2 = $res->toInt();


        display("Set ".($count1 + $count2)." parent definitions");
        $this->log->log(__METHOD__);
    }
    
    private function setConstantDefinition() {
        display('Set constant definitions');

        $query = <<<'GREMLIN'
g.V().hasLabel("Identifier", "Nsname")
     .where(__.sideEffect{ constante = it.get();}.in("DEFINITION").coalesce( __.hasLabel("Constant").out("VALUE"),
                                                                             __.hasLabel("Defineconstant").out("ARGUMENT").has("rank", 1))
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
        $this->log->log(__METHOD__);
    }

    private function makeClassConstantDefinition() {
        display('Creating link between Class constant and definition');
        // Create link between Class constant and definition
        $query = new Query(0, $this->config->project, 'fixFullnspathConstants', null, $this->datastore);
        $query->atomIs('Staticconstant')
              ->hasNoIn('DEFINITION')
              ->outIs('CONSTANT')
              ->savePropertyAs('code', 'name')
              ->back('first')
              ->outIs('CLASS')
              ->atomIs(array('Identifier', 'Nsname', 'Self', 'Static', 'Parent'))
              ->savePropertyAs('fullnspath', 'classe')
              ->inIs('DEFINITION')
              ->atomIs(array('Class', 'Classanonymous', 'Interface'))
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs('CONST')
              ->atomIs('Const')
              ->outIs('CONST')
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', Analyzer::CASE_SENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());

        display('Create '.($result->toInt()).' link between Class constant and definition');
        $this->logTime('Class::constant definition');
        $this->log->log(__METHOD__);
    }

    private function makeClassMethodDefinition() {
        // Warning : no support for overwritten methods : ALL methods are linked

        // Create link between static Class method and its definition
        // This works outside a class too, for static.
        $query = new Query(0, $this->config->project, 'fixClassMethodDefinition', null, $this->datastore);
        $query->atomIs('Staticmethodcall')
              ->hasNoIn('DEFINITION')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'name')
              ->inIs('METHOD')
              ->outIs('CLASS')
              ->atomIs(array('Identifier', 'Nsname', 'Self', 'Static', 'Parent'))
              ->savePropertyAs('fullnspath', 'classe')
              ->inIs('DEFINITION')
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->is('static', true)
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());

        // Create link between Class method and definition
        // This works only for $this
        $query = new Query(0, $this->config->project, 'fixClassMethodDefinition', null, $this->datastore);
        $query->atomIs('Methodcall')
              ->hasNoIn('DEFINITION')
              ->outIs('OBJECT')
              ->atomIs('This')
              ->inIs('OBJECT')
              ->outIs('METHOD')
              ->savePropertyAs('lccode', 'name')
              ->back('first')
              ->goToInstruction(array('Class', 'Classanonymous', 'Trait'))
              ->goToAllParents(Analyzer::INCLUDE_SELF)
              ->raw(<<<GREMLIN
where(
    __.sideEffect{aliases = [:]; insteadofs = [:]; }
      .out('USE').out('BLOCK').out('EXPRESSION')
      .sideEffect{
        if (it.get().label() == 'Insteadof') {
            method = it.get().vertices(OUT, "NAME").next().vertices(OUT, "METHOD").next().property('lccode').value();
            theTrait = it.get().vertices(OUT, "INSTEADOF").next().property('fullnspath').value();
            if (insteadofs[method] == null) {
                insteadofs[method] = [theTrait];
            } else {
                insteadofs[method].add(theTrait);
            }
        }

        if (it.get().label() == 'As') {
            method = it.get().vertices(OUT, "NAME").next().property('lccode').value();
            alias = it.get().vertices(OUT, "AS").next().property('lccode').value();
            aliases[alias] = method;
        }
      }
      .fold()
    )
.sideEffect{ if (aliases[name] != null) { name = aliases[name]; } }
GREMLIN
, array(), array())
              ->goToAllTraits(Analyzer::INCLUDE_SELF)
              ->raw(<<<GREMLIN
filter{ insteadofs[name] == null || !(it.get().value('fullnspath') in insteadofs[name]); }
GREMLIN
,array(), array())
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('code', 'name', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'first')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        display('Create '.($result->toInt()).' link between $this->methodcall() and definition');

        // Create link between constructor and new call
        $__construct = $this->dictCode->translate('__construct');
        if (!empty($__construct)) {
            $query = new Query(0, $this->config->project, 'fixClassMethodDefinition', null, $this->datastore);
            $query->atomIs('New')
                  ->outIs('NEW')
                  ->atomIs('Newcall')
                  ->has('fullnspath')
                  ->inIs('DEFINITION')
                  ->outIs('MAGICMETHOD')
                  ->codeIs($__construct, Analyzer::NO_TRANSLATE, Analyzer::CASE_INSENSITIVE)
                  ->addETo('DEFINITION', 'first')
                  ->returnCount();
            $query->prepareRawQuery();
            $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        }

        display('Create '.($result->toInt()).' link between new class and definition');
        $this->logTime('Class::method() definition');
        $this->log->log(__METHOD__);
    }

    private function solveTraitMethods() {
        $query = new Query(0, $this->config->project, 'solveTraitMethods', null, $this->datastore);
        $query->atomIs('Usetrait')
              ->outIs('BLOCK')
              ->outIs('EXPRESSION')
              ->atomIs('As')
              ->outIs('NAME')
              ->atomIs('Staticmethod')
              ->_as('results')
              ->tokenIs('T_STRING')
              ->savePropertyAs('lccode', 'methode')
              ->back('first')
              ->outIs('USE')
              ->inIs('DEFINITION')
              ->outIs(array('METHOD', 'MAGICMETHOD'))
              ->outIs('NAME')
              ->samePropertyAs('lccode', 'methode', Analyzer::CASE_INSENSITIVE)
              ->inIs('NAME')
              ->addETo('DEFINITION', 'results')
              ->returnCount();
        $query->prepareRawQuery();
        $result = $this->gremlin->query($query->getQuery(), $query->getArguments());
        display('Created '.($result->toInt()).' links for use in traits');
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
