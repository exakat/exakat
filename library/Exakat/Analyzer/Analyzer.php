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


namespace Exakat\Analyzer;

use Exakat\Description;
use Exakat\Datastore;
use Exakat\Config;
use Exakat\Tokenizer\Token;
use Exakat\Exceptions\GremlinException;

abstract class Analyzer {
    protected $code           = null;

    protected $description    = null;

    static public $datastore  = null;
    
    protected $rowCount       = 0; // Number of found values
    protected $processedCount = 0; // Number of initial values
    protected $queryCount     = 0; // Number of ran queries
    protected $rawQueryCount  = 0; // Number of ran queries

    private $queries          = array();
    private $queriesArguments = array();
    private $methods          = array();
    private $arguments        = array();
    
    public static $staticConfig         = null;
    public $config         = null;
    
    static public $analyzers  = array();
    private $analyzer         = '';       // Current class of the analyzer (called from below)
    protected $analyzerQuoted = '';
    protected $analyzerId     = 0;

    protected $phpVersion       = self::PHP_VERSION_ANY;
    protected $phpConfiguration = 'Any';
    
    private $path_tmp           = null;

    protected $severity = self::S_NONE; // Default to None.
    const S_CRITICAL = 'Critical';
    const S_MAJOR    = 'Major';
    const S_MINOR    = 'Minor';
    const S_NOTE     = 'Note';
    const S_NONE     = 'None';

    protected $timeToFix = self::T_NONE; // Default to no time (Should not display)
    const T_NONE    = 'None';    //'0';
    const T_INSTANT = 'Instant'; //'5';
    const T_QUICK   = 'Quick';   //30';
    const T_SLOW    = 'Slow';    //60';
    const T_LONG    = 'Long';    //360';
    
    const PHP_VERSION_ANY = 'Any';

    const COMPATIBLE                 =  0;
    const UNKNOWN_COMPATIBILITY      = -1;
    const VERSION_INCOMPATIBLE       = -2;
    const CONFIGURATION_INCOMPATIBLE = -3;
    
    const CASE_SENSITIVE   = true;
    const CASE_INSENSITIVE = false;

    static public $FUNCTION_METHOD  = array('Function', 'Closure', 'Method');
    static public $CONTAINERS       = array('Variable', 'Staticproperty', 'Member', 'Array');
    static public $LITERALS         = array('Integer', 'Real', 'Null', 'Boolean', 'String');
    static public $FUNCTIONS_TOKENS = array('T_STRING', 'T_NS_SEPARATOR', 'T_ARRAY', 'T_EVAL', 'T_ISSET', 'T_EXIT', 'T_UNSET', 'T_ECHO', 'T_OPEN_TAG_WITH_ECHO', 'T_PRINT', 'T_LIST', 'T_EMPTY', 'T_OPEN_BRACKET');
    static public $VARIABLES_ALL    = array('Variable', 'Variableobject', 'Variablearray', 'Globaldefinition', 'Staticdefinition', 'Propertydefinition');
    static public $FUNCTIONS_ALL    = array('Function', 'Closure', 'Method');
    static public $FUNCTIONS_NAMED  = array('Function', 'Method');
    static public $CLASSES_ALL      = array('Class', 'Classanonymous');
    static public $CLASSES_NAMED    = 'Class';
    
    
    const INCLUDE_SELF = false;
    const EXCLUDE_SELF = true;

    const CONTEXT_IN_CLOSURE = 1;
    const CONTEXT_OUTSIDE_CLOSURE = 2;
    
    const MAX_LOOPING = 15;
    
    static public $docs = null;

    protected $gremlin = null;
    
    protected $linksDown = '';

    public function __construct($gremlin = null, $config = null) {
        $this->gremlin = $gremlin;
        
        $this->analyzer = get_class($this);
        $this->analyzerQuoted = str_replace('\\', '/', str_replace('Exakat\\Analyzer\\', '', $this->analyzer));

        $this->code = $this->analyzer;
        
        self::initDocs();
        
        $this->_as('first');
        
        if($config === null) {
            print_r($this);
            debug_print_backtrace();
            die();
        }
        $this->config = $config;

        if (strpos($this->analyzer, '\\Common\\') === false) {
            $this->description = new Description($this->analyzer, $config);
        }
        
        if (!isset(self::$datastore)) {
            self::$datastore = new Datastore($this->config);
        }
        
        $this->linksDown = Token::linksAsList();
    }
    
    public function __destruct() {
        if ($this->path_tmp !== null) {
            unlink($this->path_tmp);
        }
    }
    
    public function setAnalyzer($analyzer) {
        $this->analyzer = self::getClass($analyzer);
        if ($this->analyzer === false) {
            throw new NoSuchAnalyzer($analyzer);
        }
        $this->analyzerQuoted = str_replace('\\', '/', str_replace('Exakat\\Analyzer\\', '', $this->analyzer));
    }
    
    public function getInBaseName() {
        return $this->analyzerQuoted;
    }
    
    static public function initDocs() {
        if (Analyzer::$docs === null) {
            $pathDocs = self::$staticConfig->dir_root.'/data/analyzers.sqlite';
            self::$docs = new Docs($pathDocs);
        }
    }
    
    public static function getName($classname) {
        return str_replace( array('Exakat\\Analyzer\\', '\\'), array('', '/'), $classname);
    }
    
    public static function getClass($name) {
        // accepted names :
        // PHP full name : Analyzer\\Type\\Class
        // PHP short name : Type\\Class
        // Human short name : Type/Class
        // Human shortcut : Class (must be unique among the classes)

        if (strpos($name, '\\') !== false) {
            if (substr($name, 0, 16) === 'Exakat\\Analyzer\\') {
                $class = $name;
            } else {
                $class = 'Exakat\\Analyzer\\'.$name;
            }
        } elseif (strpos($name, '/') !== false) {
            $class = 'Exakat\\Analyzer\\'.str_replace('/', '\\', $name);
        } elseif (strpos($name, '/') === false) {
            self::initDocs();
            $found = self::$docs->guessAnalyzer($name);
            if (count($found) == 0) {
                return false; // no class found
            } elseif (count($found) == 1) {
                $class = $found[0];
            } else {
                // too many options here...
                return false;
            }
        } else {
            $class = $name;
        }
        
        if (class_exists($class)) {
            $actualClassName = new \ReflectionClass($class);
            if ($class !== $actualClassName->getName()) {
                // problems with the case
                return false;
            } else {
                return $class;
            }
        } else {
            return false;
        }
    }
    
    public function getDump() {
        
        $query = <<<GREMLIN
g.V().hasLabel("Analysis").has("analyzer", "{$this->analyzerQuoted}").out('ANALYZED')
.sideEffect{ line = it.get().value('line');
             fullcode = it.get().value('fullcode');
             file='None'; 
             theFunction = ''; 
             theClass=''; 
             theNamespace=''; 
             }
.sideEffect{ line = it.get().value('line'); }
.until( hasLabel('File', 'Project') ).repeat( 
    __.in($this->linksDown)
      .sideEffect{ if (it.get().label() in ['Function', 'Method']) { theFunction = it.get().value('code')} }
      .sideEffect{ if (it.get().label() in ['Class', 'Trait']) { theClass = it.get().value('fullcode')} }
      .sideEffect{ if (it.get().label() == 'Namespace') { theNamespace = it.get().value('fullnspath')} }
       )
.sideEffect{  file = it.get().value('fullcode');}

.map{ ['fullcode':fullcode, 'file':file, 'line':line, 'namespace':theNamespace, 'class':theClass, 'function':theFunction ];}

GREMLIN;
        $res = $this->gremlin->query($query);
        if (isset($res->results)) {
            return $res->results;
        } elseif (is_array($res)) {
            return $res;
        } else {
            return array();
        }
    }

    public static function getSuggestionThema($thema) {
        self::initDocs();
        $list = self::$docs->listAllThemes();
        $r = array();
        foreach($list as $c) {
            $l = levenshtein($c, $thema);

            if ($l < 8) {
                $r[] = $c;
            }
        }
        
        return $r;
    }
    
    public static function getSuggestionClass($name) {
        self::initDocs();
        $list = self::$docs->listAllAnalyzer();
        $r = array();
        foreach($list as $c) {
            $l = levenshtein($c, $name);

            if ($l < 8) {
                $r[] = $c;
            }
        }
        
        return $r;
    }
    
    public static function getInstance($name, $gremlin = null, $config = null) {
        static $instanciated = array();
        
        if ($analyzer = static::getClass($name)) {
            if (!isset($instanciated[$analyzer])) {
                $instanciated[$analyzer] = new $analyzer($gremlin, $config);
            }
            return $instanciated[$analyzer];
        } else {
            display( 'No such class as "' . $name . '"'.PHP_EOL);
            return null;
        }
    }
    
    public function getDescription() {
        return $this->description;
    }

    static public function listAllThemes($theme = null) {
        self::initDocs();
        return Analyzer::$docs->listAllThemes($theme);
    }

    static public function getThemeAnalyzers($theme = null) {
        self::initDocs();
        return Analyzer::$docs->getThemeAnalyzers($theme);
    }

    public function getThemes() {
        self::initDocs();
        $analyzer = self::getName($this->analyzerQuoted);
        return Analyzer::$docs->getThemeForAnalyzer($analyzer);
    }

    static public function getAnalyzers($theme) {
        return Analyzer::$analyzers[$theme];
    }

    private function addMethod($method, $arguments = array()) {
        if ($arguments === array()) { // empty
            $this->methods[] = $method;
            return $this;
        }
        
        if (func_num_args() >= 2) {
            $arguments = func_get_args();
            array_shift($arguments);
            $argnames = array(str_replace('***', '%s', $method));
            foreach($arguments as $arg) {
                $argname = 'arg'.count($this->arguments);
                $this->arguments[$argname] = $arg;
                $argnames[] = $argname;
            }
            $this->methods[] = call_user_func_array('sprintf', $argnames);
            return $this;
        }

        // one argument
        $argname = 'arg'.count($this->arguments);
        $this->arguments[$argname] = $arguments;
        $this->methods[] = str_replace('***', $argname, $method);
        
        return $this;
    }
    
    public function init($analyzerId = null) {
        if ($analyzerId === null) {
            $query = 'g.V().hasLabel("Analysis").has("analyzer", "'.$this->analyzerQuoted.'")';
            $res = $this->query($query);

            if (isset($res[0])) {
                $res = $res[0];
            }

            if (isset($res->id)) {
                $this->analyzerId = $res->id;

                // Removing all edges
                $query = 'g.V().hasLabel("Analysis").has("analyzer", "'.$this->analyzerQuoted.'").outE("ANALYZED").drop()';
                $res = $this->query($query);
            } else {
                // Creating analysis vertex
                $query = 'g.addV("Analysis").property("analyzer", "'.$this->analyzerQuoted.'").property("atom", "Analysis")';
                $res = $this->query($query);
            
                if (!isset($res[0])) {
                    throw new GremlinException();
                }
                
                $this->analyzerId = $res[0]->id;
            }
        } else {
            $this->analyzerId = $analyzerId;
        }
        
        return $this->analyzerId;
    }

    public function checkphpConfiguration($Php) {
        // this handles Any version of PHP
        if ($this->phpConfiguration == 'Any') {
            return true;
        }
        
        foreach($this->phpConfiguration as $ini => $value) {
            if ($Php->getConfiguration($ini) != $value) {
                return false;
            }
        }
        
        return true;
    }
    
    public function checkPhpVersion($version) {
        // this handles Any version of PHP
        if ($this->phpVersion === self::PHP_VERSION_ANY) {
            return true;
        }

        // version and above
        if ((substr($this->phpVersion, -1) === '+') && version_compare($version, $this->phpVersion) >= 0) {
            return true;
        }

        // up to version
        if ((substr($this->phpVersion, -1) === '-') && version_compare($version, $this->phpVersion) < 0) {
            return true;
        }

        // version range 1.2.3-4.5.6
        if (strpos($this->phpVersion, '-') !== false) {
            list($lower, $upper) = explode('-', $this->phpVersion);
            return version_compare($version, $lower) >= 0 && version_compare($version, $upper) <= 0;
        }
        
        // One version only
        if (version_compare($version, $this->phpVersion) == 0) {
            return true;
        }
        
        // Default behavior if we don't understand :
        return false;
    }

    // @doc return the list of dependences that must be prepared before the execution of an analyzer
    // @doc by default, nothing.
    public function dependsOn() {
        return array();
    }
    
    public function query($queryString, $arguments = array()) {
        try {
            $result = $this->gremlin->query($queryString, $arguments);
        } catch (GremlinException $e) {
            display($e->getMessage().
                    $queryString);
            $result = new \StdClass();
            $result->processed = 0;
            $result->total = 0;
            return array($result);
        }

        if (is_array($result)) {
            return $result;
        }
        
        if (!isset($result->results)) {
            return array();
        }
        
        return (array) $result->results;
    }

    public function queryHash($queryString, $arguments = array()) {
        try {
            $result = $this->gremlin->query($queryString, $arguments);
        } catch (GremlinException $e) {
            display($e->getMessage().
                    $queryString);
            $result = new \StdClass();
            $result->processed = 0;
            $result->total = 0;
            return array($result);
        }

        if (!isset($result->results)) {
            return array();
        }
        
        $return = array();
        foreach($result->results as $row) {
            $return[$row->key] = $row->value;
        }
        return $return;
    }

    public function _as($name) {
        $this->methods[] = 'as("'.$name.'")';
        
        return $this;
    }

    public function back($name) {
        $this->methods[] = 'select(\''.$name.'\')';
        
        return $this;
    }
    
    public function ignore() {
        // used to execute some code but not collect any node
        $this->methods[] = 'filter{ 1 == 0; }';
    }

////////////////////////////////////////////////////////////////////////////////
// Common methods
////////////////////////////////////////////////////////////////////////////////

    protected function hasNoInstruction($atom = 'Function') {
        assert($this->assertAtom($atom));
        $this->addMethod('not( where( 
 __.repeat(__.inE().not(hasLabel("DEFINITION", "ANALYZED")).outV() ).until(hasLabel("File")).emit().hasLabel('.$this->SorA($atom).')
 ) )');
        
        return $this;
    }

    private function hasNoNamedInstruction($atom = 'Function', $name = null) {
        assert($this->assertAtom($atom));
        if ($name === null) {
            return $this->hasNoInstruction($atom);
        }

        $this->addMethod('not( where( 
__.repeat( __.inE().not(hasLabel("DEFINITION", "ANALYZED")).outV()).until(hasLabel("File")).hasLabel('.$this->SorA($atom).').has("code", "'.$name.'")
  ) )');
        
        return $this;
    }

    protected function hasInstruction($atom = 'Function') {
        assert($this->assertAtom($atom));
        $this->addMethod('where( 
__.repeat( __.inE().not(hasLabel("DEFINITION", "ANALYZED")).outV() ).until(hasLabel("File")).emit(hasLabel('.$this->SorA($atom).')).hasLabel('.$this->SorA($atom).')
    )');
        
        return $this;
    }

    protected function goToInstruction($atom = 'Namespace') {
        assert($this->assertAtom($atom));
        $this->addMethod('repeat( __.inE().not(hasLabel("DEFINITION", "ANALYZED")).outV()).until(hasLabel('.$this->SorA($atom).', "File") ).hasLabel('.$this->SorA($atom).')');
        
        return $this;
    }

    public function tokenIs($token) {
        assert($this->assertLink($token));
        $this->addMethod('has("token", within(***))', $token);
        
        return $this;
    }

    public function tokenIsNot($token) {
        assert(func_get_args() !== 1, "Too many arguments for ".__METHOD__);
        assert($this->assertToken($token));
        $this->addMethod('not(has("token", within(***)))', $token);
        
        return $this;
    }
    
    public function atomIs($atom) {
        assert(func_get_args() !== 1, "Too many arguments for ".__METHOD__);
        assert($this->assertAtom($atom));
        $this->addMethod('hasLabel('.$this->SorA($atom).')');
        
        return $this;
    }

    public function atomIsNot($atom) {
        assert(func_get_args() !== 1, "Too many arguments for ".__METHOD__);
        assert($this->assertAtom($atom));
        $this->addMethod('not(hasLabel('.$this->SorA($atom).'))');
        
        return $this;
    }

    public function atomFunctionIs($fullnspath) {
        assert(func_get_args() !== 1, "Too many arguments for ".__METHOD__);
        assert($fullnspath !== null, 'fullnspath can\'t be null in '.__METHOD__);
        $this->functioncallIs($fullnspath);

        return $this;
    }
    
    public function functioncallIs($fullnspath) {
        assert(func_get_args() !== 1, "Too many arguments for ".__METHOD__);
        assert($fullnspath !== null, 'fullnspath can\'t be null in '.__METHOD__);
        $this->atomIs('Functioncall')
             ->raw('has("fullnspath")')
             ->fullnspathIs($this->makeFullNsPath($fullnspath));

        return $this;
    }

    public function functioncallIsNot($fullnspath) {
        assert($fullnspath !== null, 'fullnspath can\'t be null in '.__METHOD__);
        $this->atomIs('Functioncall')
             ->raw('not( where( __.out("NAME").hasLabel("Array", "Variable")) )')
             ->tokenIs(self::$FUNCTIONS_TOKENS)
             ->fullnspathIsNot($this->makeFullNsPath($fullnspath));

        return $this;
    }

    public function hasAtomInside($atom) {
        assert($this->assertAtom($atom));
        $gremlin = 'where( __.emit( hasLabel('.$this->SorA($atom).')).repeat( out('.$this->linksDown.') ).times('.self::MAX_LOOPING.').hasLabel('.$this->SorA($atom).') )';
        $this->addMethod($gremlin);
        
        return $this;
    }
    
    public function atomInside($atom) {
        assert($this->assertAtom($atom));
        $gremlin = 'emit( hasLabel('.$this->SorA($atom).')).repeat( out('.$this->linksDown.') ).times('.self::MAX_LOOPING.').hasLabel('.$this->SorA($atom).')';
        $this->addMethod($gremlin);
        
        return $this;
    }

    public function atomInsideNoAnonymous($atom) {
        assert($this->assertAtom($atom));
        $gremlin = 'emit( hasLabel('.$this->SorA($atom).')).repeat( out('.$this->linksDown.').not(hasLabel("Closure", "Classanonymous")) ).times('.self::MAX_LOOPING.').hasLabel('.$this->SorA($atom).')';
        $this->addMethod($gremlin);
        
        return $this;
    }

    public function atomInsideNoDefinition($atom) {
        assert($this->assertAtom($atom));
        $gremlin = 'emit( ).repeat( __.out( ).not(hasLabel("Closure", "Classanonymous", "Function", "Class", "Trait")) ).times('.self::MAX_LOOPING.').hasLabel('.$this->SorA($atom).')';
        $this->addMethod($gremlin);
        
        return $this;
    }

    public function noAtomInside($atom) {
        assert($this->assertAtom($atom));
        // Cannot use not() here : 'This traverser does not support loops: org.apache.tinkerpop.gremlin.process.traversal.traverser.B_O_Traverser'.
//        $gremlin = 'not( where( __.emit( ).repeat( __.out() ).times('.self::MAX_LOOPING.').hasLabel('.$this->SorA($atom).') ) )';
        // Check with Structures/Unpreprocessed
        $gremlin = 'where( __.repeat( __.out().not(hasLabel("Closure", "Classanonymous")) ).emit()
                          .times('.self::MAX_LOOPING.').hasLabel('.$this->SorA($atom).').count().is(eq(0)) )';
        $this->addMethod($gremlin);
        
        return $this;
    }

    public function trim($property, $chars = '\'\"') {
        $this->addMethod('transform{it.'.$property.'.replaceFirst("^['.$chars.']?(.*?)['.$chars.']?\$", "\$1")}');
        
        return $this;
    }

    public function analyzerIs($analyzer) {
        if (is_array($analyzer)) {
            foreach($analyzer as &$a) {
                $a = self::getName($a);
            }
            unset($a);

            $this->addMethod('where( __.in("ANALYZED").has("analyzer", within(***)) )', $analyzer);
        } elseif (is_string($analyzer)) {
            if ($analyzer === 'self') {
                $analyzer = self::getName($this->analyzerQuoted);
            } else {
                $analyzer = self::getName($analyzer);
            }
            $this->addMethod('where( __.in("ANALYZED").has("analyzer", "'.$analyzer.'") )');
        }

        return $this;
    }

    public function analyzerIsNot($analyzer) {
        if (is_array($analyzer)) {
            foreach($analyzer as &$a) {
                $a = self::getName($a);
            }
            unset($a);

            $this->addMethod('not( where( __.in("ANALYZED").has("analyzer", within(***))) )', $analyzer);
        } else {
            if ($analyzer === 'self') {
                $analyzer = self::getName($this->analyzerQuoted);
            } else {
                $analyzer = self::getName($analyzer);
            }
            $this->addMethod('not( where( __.in("ANALYZED").has("analyzer", "'.$analyzer.'") ) )');
        }
        
        return $this;
    }

    public function has($property) {
        $this->addMethod('has("'.$property.'")');
        
        return $this;
    }
    
    public function is($property, $value = true) {
        if ($value === null) {
            $this->addMethod('has("'.$property.'", null)');
        } elseif ($value === true) {
            $this->addMethod('has("'.$property.'", true)');
        } elseif ($value === false) {
            $this->addMethod('has("'.$property.'", false)');
        } elseif (is_int($value)) {
            $this->addMethod('has("'.$property.'", '.$value.')');
        } else {
            // $value is an array
            $this->addMethod('has("'.$property.'", within(***))', $value);
        }

        return $this;
    }

    public function isHash($property, $hash, $index) {
        $this->addMethod('filter{ it.get().value("'.$property.'") in ***['.$index.']}', $hash);
        
        return $this;
    }

    public function isNotHash($property, $hash, $index) {
        $this->addMethod('filter{ !(it.get().value("'.$property.'") in ***['.$index.'])}', $hash);
        
        return $this;
    }

    public function isNot($property, $value = true) {
        if ($value === null) {
            $this->addMethod('not(has("'.$property.'", null))');
        } elseif ($value === true) {
            $this->addMethod('not(has("'.$property.'", true))');
        } elseif ($value === false) {
            $this->addMethod('not(has("'.$property.'", false))');
        } elseif (is_int($value)) {
            $this->addMethod('not(has("'.$property.'", '.$value.'))');
        } else {
            $this->addMethod('not(has("'.$property.'", within(***)))', $value);
        }
        
        return $this;
    }

    public function isMore($property, $value = 0) {
        if (is_int($value)) {
            $this->addMethod("filter{ it.get().value('$property').toLong() > $value}");
        } elseif (is_string($value)) {
            // this is a variable name
            $this->addMethod("filter{ it.get().value('$property').toLong() > $value;}", $value);
        } else {
            assert(false, '$value must be int or string in '.__METHOD__);
        }

        return $this;
    }

    public function isLess($property, $value = 0) {
        if (is_int($value)) {
            $this->addMethod('filter{ it.get().value("'.$property.'").toLong() < '.$value.'}');
        } elseif (is_string($value)) {
            // this is a variable name
            $this->addMethod("filter{ it.get().value('$property').toLong() < $value;}", $value);
        } else {
            assert(false, '$value must be int or string in '.__METHOD__);
        }

        return $this;
    }

    public function outWithRank($link = 'ARGUMENT', $rank = 0) {
        if ($rank === 'first') {
            // @note : can't use has() with integer!
            $this->addMethod('out("'.$link.'").has("rank", eq(0))');
        } elseif ($rank === 'last') {
            $this->addMethod('map( __.out("'.$link.'").order().by("rank").tail(1) )');
        } elseif ($rank === '2last') {
            $this->addMethod('map( __.out("'.$link.'").order().by("rank").tail(2) )');
        } else {
            $this->addMethod('out("'.$link.'").has("rank", eq('.abs((int) $rank).'))');
        }

        return $this;
    }

    public function outWithoutLastRank() {
        $this->addMethod('sideEffect{dernier = it.get().value("count") - 1;}.out("EXPRESSION").filter{ it.get().value("rank") < dernier}');

        return $this;
    }

    public function hasChildWithRank($edgeName, $rank = '0') {
        $this->addMethod('where( __.out('.$this->SorA($edgeName).').has("rank", '.abs((int) $rank).') )');

        return $this;
    }

    public function noChildWithRank($edgeName, $rank = '0') {
        if (is_int($rank)) {
            $this->addMethod('not( where( __.out('.$this->SorA($edgeName).').has("rank", '.abs((int) $rank).') ) )');
        } else {
            $this->addMethod('not( where( __.out('.$this->SorA($edgeName).').filter{it.get().value("rank") == '.$rank.'; } ) )');
        }

        return $this;
    }

    public function hasName() {
        $this->addMethod('not(where(__.out("NAME").hasLabel("Void")) )');

        return $this;
    }

    public function hasNoName() {
        $this->addMethod('where(__.out("NAME").hasLabel("Void"))');

        return $this;
    }

    public function codeIs($code, $caseSensitive = self::CASE_INSENSITIVE) {
        return $this->propertyIs('code', $code, $caseSensitive);
    }

    public function codeIsNot($code, $caseSensitive = self::CASE_INSENSITIVE) {
        return $this->propertyIsNot('code', $code, $caseSensitive);
    }

    public function noDelimiterIs($code, $caseSensitive = self::CASE_INSENSITIVE) {
        $this->addMethod('hasLabel("String")', $code);
        return $this->propertyIs('noDelimiter', $code, $caseSensitive);
    }

    public function noDelimiterIsNot($code, $caseSensitive = self::CASE_INSENSITIVE) {
        $this->addMethod('hasLabel("String")', $code);
        return $this->propertyIsNot('noDelimiter', $code, $caseSensitive);
    }

    public function fullnspathIs($code) {
        $this->addMethod('has("fullnspath")');
        return $this->propertyIs('fullnspath', $code, self::CASE_INSENSITIVE);
    }

    public function fullnspathIsNot($code) {
        return $this->propertyIsNot('fullnspath', $code, self::CASE_INSENSITIVE);
    }
    
    public function codeIsPositiveInteger() {
        $this->addMethod('filter{ if( it.code.isInteger()) { it.code > 0; } else { true; }}', null); // may be use toInteger() ?

        return $this;
    }

    public function samePropertyAs($property, $name, $caseSensitive = self::CASE_INSENSITIVE) {
        if ($caseSensitive === self::CASE_SENSITIVE || $property == 'line' || $property == 'rank') {
            $caseSensitive = '';
        } else {
            $caseSensitive = '.toLowerCase()';
        }

        if ($property === 'label') {
            $this->addMethod('filter{ it.get().label()'.$caseSensitive.' == '.$name.$caseSensitive.'}');
        } else {
            $this->addMethod('filter{ it.get().value("'.$property.'")'.$caseSensitive.' == '.$name.$caseSensitive.'}');
        }

        return $this;
    }

    public function notSamePropertyAs($property, $name, $caseSensitive = self::CASE_INSENSITIVE) {
        if ($caseSensitive === self::CASE_SENSITIVE || $property == 'line' || $property == 'rank') {
            $caseSensitive = '';
        } else {
            $caseSensitive = '.toLowerCase()';
        }
        
        if ($property === 'label') {
            $this->addMethod('filter{ it.get().label()'.$caseSensitive.' != '.$name.$caseSensitive.'}');
        } else {
            $this->addMethod('filter{ it.get().value("'.$property.'")'.$caseSensitive.' != '.$name.$caseSensitive.'}');
        }

        return $this;
    }

    public function saveOutAs($name, $out = "ARGUMENT", $sort = 'rank') {
        // Calculate the arglist, normalized it, then put it in a variable
        // This needs to be in Arguments, (both Functioncall or Function)
        if (empty($sort)) {
            $sortStep = '';
        } else {
            $sortStep = '.sort{it.value("'.$sort.'")}';
        }

        $this->addMethod(<<<GREMLIN
sideEffect{ 
    s = [];
    it.get().vertices(OUT, "$out")$sortStep.each{ 
        s.push(it.value('code'));
    };
    $name = s.join(', ');
    true;
}
GREMLIN
);

        return $this;
    }

    public function savePropertyAs($property, $name) {
        if ($property === 'label') {
            $this->addMethod('sideEffect{ '.$name.' = it.get().label(); }');
        } else {
            $this->addMethod('sideEffect{ '.$name.' = it.get().value("'.$property.'"); }');
        }

        return $this;
    }

    public function fullcodeIs($code, $caseSensitive = self::CASE_INSENSITIVE) {
        $this->propertyIs('fullcode', $code, $caseSensitive);
        
        return $this;
    }
    
    public function fullcodeIsNot($code, $caseSensitive = self::CASE_INSENSITIVE) {
        $this->propertyIsNot('fullcode', $code, $caseSensitive);
        
        return $this;
    }

    public function isUppercase($property = 'fullcode') {
        $this->addMethod('filter{it.get().value("'.$property.'") == it.get().value("'.$property.'").toUpperCase()}');

        return $this;
    }

    public function isLowercase($property = 'fullcode') {
        $this->addMethod('filter{it.get().value("'.$property.'") == it.get().value("'.$property.'").toLowerCase()}');

        return $this;
    }

    public function isNotLowercase($property = 'fullcode') {
        $this->addMethod('filter{it.get().value("'.$property.'") != it.get().value("'.$property.'").toLowerCase()}');

        return $this;
    }

    public function isNotUppercase($property = 'fullcode') {
        $this->addMethod('filter{it.get().value("'.$property.'") != it.get().value("'.$property.'").toUpperCase()}');

        return $this;
    }
    
    public function isNotMixedcase($property = 'fullcode') {
        $this->addMethod('filter{it.get().value("'.$property.'") == it.get().value("'.$property.'").toLowerCase() || it.get().value("'.$property.'") == it.get().value("'.$property.'").toUpperCase()}');

        return $this;
    }

    public function cleanAnalyzerName($gremlin) {
        $dependencies = $this->dependsOn();
        $fullNames = array_map('Exakat\\Analyzer\\Analyzer::makeBaseName', $dependencies);
        
        return str_replace($dependencies, $fullNames, $gremlin);
    }

    public function filter($filter, $arguments = array()) {
        $filter = $this->cleanAnalyzerName($filter);
        $this->addMethod("filter{ $filter }", $arguments);

        return $this;
    }

    public function codeLength($length = ' == 1 ') {
        // @todo add some tests ? Like Operator / value ?
        $this->addMethod('filter{it.get().value("code").length() '.$length.'}');

        return $this;
    }

    public function fullcodeLength($length = ' == 1 ') {
        // @todo add some tests ? Like Operator / value ?
        $this->addMethod('filter{it.get().value("fullcode").length() '.$length.'}');

        return $this;
    }

    public function groupCount($column) {
        $this->addMethod("groupCount(m){it.$column}");
        
        return $this;
    }

    public function regexIs($column, $regex) {
        $this->addMethod(<<<GREMLIN
filter{ (it.get().value('$column') =~ "$regex" ).getCount() != 0 }
GREMLIN
);

        return $this;
    }

    public function regexIsNot($column, $regex) {
        $this->addMethod(<<<GREMLIN
filter{ (it.get().value('$column') =~ "$regex" ).getCount() == 0 }
GREMLIN
);

        return $this;
    }

    protected function outIs($link) {
        assert(func_get_args() !== 1, "Too many arguments for ".__METHOD__);
        assert($this->assertLink($link));
        $this->addMethod('out('.$this->SorA($link).')');

        return $this;
    }

    // follows a link if it is there (and do nothing otherwise)
    protected function outIsIE($link) {
        assert(func_get_args() !== 1, "Too many arguments for ".__METHOD__);
        assert($this->assertLink($link));
        // alternative : coalesce(out('LEFT'),  __.filter{true} )
        $this->addMethod("until( __.not(outE(".$this->SorA($link).")) ).repeat(out(".$this->SorA($link)."))");
        
        return $this;
    }

    public function outIsNot($link) {
        assert(func_get_args() !== 1, "Too many arguments for ".__METHOD__);
        assert($this->assertLink($link));
        $this->addMethod('not( where( __.outE('.$this->SorA($link).') ) )');
        
        return $this;
    }

    public function nextSibling($link = 'EXPRESSION') {
        $this->hasIn($link);
        $this->addMethod('sideEffect{sibling = it.get().value("rank");}.in("'.$link.'").out("'.$link.'").filter{sibling + 1 == it.get().value("rank")}');

        return $this;
    }

    public function nextSiblings($link = 'EXPRESSION') {
        $this->hasIn($link);
        $this->addMethod('sideEffect{sibling = it.get().value("rank");}.in("'.$link.'").out("'.$link.'").filter{sibling + 1 <= it.get().value("rank") }');

        return $this;
    }

    public function previousSibling($link = 'EXPRESSION') {
        $this->hasIn($link);
        $this->addMethod('sideEffect{sibling = it.get().value("rank");}.in("'.$link.'").out("'.$link.'").filter{sibling - 1 == it.get().value("rank")}');

        return $this;
    }

    public function previousSiblings($link = 'EXPRESSION') {
        $this->hasIn($link);
        $this->addMethod('filter{it.get().value("rank") > 0}.sideEffect{sibling = it.get().value("rank");}.in("'.$link.'").out("'.$link.'").filter{sibling + 1 <= it.get().value("rank") }');

        return $this;
    }

    public function otherSiblings($link = 'EXPRESSION', $self = self::EXCLUDE_SELF) {
        static $sibling = 0; // This is for calling the method multiple times
        ++$sibling;
        
        if ($self === self::EXCLUDE_SELF) {
            $this->addMethod('as("sibling'.$sibling.'").in("'.$link.'").out("'.$link.'").where(neq("sibling'.$sibling.'"))');
        } else {
            $this->addMethod('in("'.$link.'").out("'.$link.'")');
        }

        return $this;
    }

    public function inIs($link) {
        assert($this->assertLink($link));
        assert(func_get_args() !== 1, "Too many arguments for ".__METHOD__);
        $this->addMethod('in('.$this->SorA($link).')');
        
        return $this;
    }

    // follows a link if it is there (and do nothing otherwise)
    protected function inIsIE($link) {
        assert($this->assertLink($link));
        $this->addMethod('until(__.inE('.$this->SorA($link).').count().is(eq(0))).repeat(__.in('.$this->SorA($link).'))');
        
        return $this;
    }

    public function inIsNot($link) {
        assert($this->assertLink($link));
        $this->addMethod('where( __.inE('.$this->SorA($link).').count().is(eq(0)))');
        
        return $this;
    }

    public function raw($query, $args = array()) {
        ++$this->rawQueryCount;
        $query = $this->cleanAnalyzerName($query);

        $this->addMethod($query, $args);
        
        return $this;
    }

    public function hasIn($link) {
        assert($this->assertLink($link));
        $this->addMethod('where( __.in('.$this->SorA($link).') )');
        
        return $this;
    }
    
    public function hasNoIn($link) {
        assert($this->assertLink($link));
        $this->addMethod('not( where( __.in('.$this->SorA($link).') ) )');
        
        return $this;
    }

    public function hasOut($link) {
        assert($this->assertLink($link));
        $this->addMethod('where( out('.$this->SorA($link).') )');
        
        return $this;
    }
    
    public function hasNoOut($link) {
        assert($this->assertLink($link));
        $this->addMethod('not(where( __.out('.$this->SorA($link).') ))');
        
        return $this;
    }

    public function isInCatchBlock() {
        $this->addMethod('filter{ it.in.loop(1){it.object.atom != "Catch"}{(it.object.atom == "Catch")}.any()');
        
        return $this;
    }

    public function hasNoCatchBlock() {
        return $this->hasNoInstruction('Catch');
    }

    public function hasParent($parentClass, $ins = array()) {
        if (empty($ins)) {
            $in = '.in';
        } else {
            $in = array();
            
            if (!is_array($ins)) {
                $ins = array($ins);
            }
            foreach($ins as $i) {
                $in[] = '.in('.$this->SorA($i).')';
            }
            
            $in = implode('', $in);
        }
        
        $this->addMethod('where( __'.$in.'.hasLabel('.$this->SorA($parentClass).'))');
        
        return $this;
    }

    public function hasNoParent($parentClass, $ins = array()) {
        if (empty($ins)) {
            $in = '.in()';
        } else {
            $in = array();
            
            if (!is_array($ins)) {
                $ins = array($ins);
            }
            foreach($ins as $i) {
                if (empty($i)) {
                    $in[] = '.in()';
                } else {
                    $in[] = ".in('$i')";
                }
            }
            
            $in = implode('', $in);
        }
        
        $this->addMethod('not( where( __'.$in.'.hasLabel('.$this->SorA($parentClass).') ) )');
        
        return $this;
    }

    public function hasChildren($childrenClass, $outs = array()) {
        if (empty($outs)) {
            $out = '.out()';
        } else {
            $out = array();
            
            if (!is_array($outs)) {
                $outs = array($outs);
            }
            foreach($outs as $o) {
                if (empty($o)) {
                    $out[] = '.out()';
                } else {
                    $out[] = ".out('$o')";
                }
            }
            
            $out = implode('', $out);
        }
        
        $this->addMethod('where( __'.$out.'.hasLabel('.$this->SorA($childrenClass).'))');
        
        return $this;
    }
        
    public function hasNoChildren($childrenClass, $outs = array()) {
        if (empty($outs)) {
            $out = '.out()';
        } else {
            $out = array();
            
            if (!is_array($outs)) {
                $outs = array($outs);
            }
            foreach($outs as $o) {
                if (empty($o)) {
                    $out[] = '.out()';
                } else {
                    $out[] = ".out('$o')";
                }
            }
            
            $out = implode('', $out);
        }
        
        $this->addMethod('where( __'.$out.'.not(hasLabel('.$this->SorA($childrenClass).')))');
        
        return $this;
    }

    public function hasConstantDefinition() {
        $this->addMethod('where( __.in("DEFINITION"))');
    
        return $this;
    }

    public function hasNoConstantDefinition() {
        $this->addMethod('where( __.in("DEFINITION").count().is(eq(0)))');
    
        return $this;
    }

    public function hasFunctionDefinition() {
        $this->addMethod('where( __.in("DEFINITION").hasLabel("Function", "Method").count().is(neq(0)))');
    
        return $this;
    }

    public function hasNoFunctionDefinition() {
        $this->addMethod('where( __.in("DEFINITION").hasLabel("Function", "Method").count().is(eq(0)))');
    
        return $this;
    }

    public function functionDefinition() {
        $this->addMethod('in("DEFINITION")');
    
        return $this;
    }

    public function goToArray() {
        $this->addMethod('emit().repeat( __.in("VARIABLE", "INDEX")).until( where(__.in("VARIABLE", "INDEX").hasLabel("Array").count().is(eq(0)) ) )');
        
        return $this;
    }
    
    public function goToCurrentScope() {
        $this->goToInstruction(array('Function', 'Phpcode'));
        
        return $this;
    }

    public function goToFunction($type = array('Function', 'Method', 'Closure')) {
        $this->addMethod('repeat(__.inE().not(hasLabel("DEFINITION", "ANALYZED")).outV()).until(hasLabel('.$this->SorA($type).') )');
        
        return $this;
    }

    public function hasNoFunction($type = array('Function', 'Closure', 'Method')) {
        return $this->hasNoInstruction($type);
    }

    public function hasNoNamedFunction($name) {
        $this->hasNoNamedInstruction('Function', $name);
        
        return $this;
    }
    
    public function goToFile() {
        $this->goToInstruction('File');
        
        return $this;
    }
    
    public function goToLoop() {
        $this->goToInstruction(array('For', 'Foreach', 'While', 'Dowhile'));
        
        return $this;
    }

    public function classDefinition() {
        $this->addMethod('in("DEFINITION")');
    
        return $this;
    }

    public function noClassDefinition($type = 'Class') {
        $this->addMethod('not(where(__.in("DEFINITION").hasLabel('.$this->SorA($type).') ) )');
    
        return $this;
    }

    public function hasClassDefinition($type = 'Class') {
        $this->addMethod('where(__.in("DEFINITION").hasLabel('.$this->SorA($type).') )');
    
        return $this;
    }

    public function noUseDefinition() {
        $this->addMethod('not( where(__.out("DEFINITION").in("USE").hasLabel("Use")) )');
    
        return $this;
    }

    public function interfaceDefinition() {
        $this->addMethod('in("DEFINITION")');
    
        return $this;
    }

    public function noInterfaceDefinition() {
        $this->addMethod('where(__.in("DEFINITION").hasLabel("Interface").count().is(eq(0)))');
    
        return $this;
    }

    public function hasInterfaceDefinition() {
        $this->addMethod('where(__.in("DEFINITION").hasLabel("Interface").count().is(eq(1)))');
    
        return $this;
    }

    public function hasTraitDefinition() {
        $this->addMethod('where(__.in("DEFINITION").hasLabel("Trait").count().is(eq(1)))');

        return $this;
    }

    public function noTraitDefinition() {
        $this->addMethod('where(__.in("DEFINITION").hasLabel("Trait").count().is(eq(0)))');
    
        return $this;
    }
    
    public function groupFilter($characteristic, $percentage) {
        if (substr(trim($characteristic), 0, 3) === 'it.') {
            $by = 'by{ '.$characteristic.' }';
        } else {
            $by = 'by( "'.$characteristic.'" )';
        }
        $this->addMethod('groupCount("gf").'.$by.'.cap("gf").sideEffect{ s = it.get().values().sum(); }.next().findAll{ it.value < s * '.$percentage.'; }.keySet()');

        return $this;
    }
    
    public function goToClass($type = 'Class') {
        $this->goToInstruction($type);
        
        return $this;
    }
    
    public function hasNoClass() {
        return $this->hasNoInstruction('Class');
    }

    public function hasClass() {
        $this->hasInstruction('Class');
        
        return $this;
    }

    public function goToInterface() {
        $this->goToInstruction('Interface');
        
        return $this;
    }

    public function hasNoInterface() {
        return $this->hasNoInstruction('Interface');
    }

    public function goToTrait() {
        $this->goToInstruction('Trait');
        
        return $this;
    }

    public function hasNoTrait() {
        return $this->hasNoInstruction('Trait');
    }

    public function goToClassTrait() {
        $this->goToInstruction(array('Trait', 'Class'));
        
        return $this;
    }

    public function hasNoClassTrait() {
        return $this->hasNoInstruction(array('Class', 'Trait'));
    }

    public function goToClassInterface() {
        $this->goToInstruction(array('Interface', 'Class'));
        
        return $this;
    }

    public function hasNoClassInterface() {
        return $this->hasNoInstruction(array('Class', 'Interface'));
    }

    public function goToClassInterfaceTrait() {
        $this->goToInstruction(array('Interface', 'Class', 'Trait'));
        
        return $this;
    }

    public function hasNoClassInterfaceTrait() {
        return $this->hasNoInstruction(array('Class', 'Interface', 'Trait'));
    }
    
    public function goToExtends() {
        $this->addMethod('out("EXTENDS").in("DEFINITION")');
        
        return $this;
    }

    public function goToImplements() {
        $this->addMethod('out("IMPLEMENTS").in("DEFINITION")');

        return $this;
    }

    public function goToParent() {
        $this->addMethod('out("EXTENDS").in("DEFINITION")');
        
        return $this;
    }

    public function goToAllParents($self = self::EXCLUDE_SELF) {
//        $this->addMethod('until(__.out("EXTENDS").in("DEFINITION").count().is(eq(0))).repeat( out("EXTENDS").in("DEFINITION") ).emit()');
        if ($self === self::EXCLUDE_SELF) {
            $this->addMethod('repeat( __.as("x").out("EXTENDS", "IMPLEMENTS").in("DEFINITION").where(neq("x")) ).emit().times('.self::MAX_LOOPING.')');
        } else {
            $this->addMethod('filter{true}.emit().repeat( __.as("x").out("EXTENDS", "IMPLEMENTS").in("DEFINITION").where(neq("x")) ).times('.self::MAX_LOOPING.')');
        }
        
//        $this->addMethod('repeat( out("EXTENDS").in("DEFINITION") ).times(4)');
//        $this->addMethod('sideEffect{ allParents = []; }.until(__.out("EXTENDS").in("DEFINITION").count().is(eq(0)) ).emit().repeat( sideEffect{allParents.push(it.get().id()); }.out("EXTENDS").in("DEFINITION").filter{ !(it.get().id() in allParents); } )');
//        $this->addMethod('sideEffect{ allParents = []; }.until(__.out("EXTENDS").in("DEFINITION").count().is(eq(0)) ).repeat( sideEffect{allParents.push(it.get().id()); }.out("EXTENDS").in("DEFINITION").filter{ !(it.get().id() in allParents); } ).emit()');

        return $this;
    }

    public function goToAllChildren($self = self::INCLUDE_SELF) {
        if ($self === self::INCLUDE_SELF) {
            $this->addMethod('repeat( __.out("DEFINITION").in("EXTENDS", "IMPLEMENTS") ).emit().times('.self::MAX_LOOPING.')');
        } else {
            $this->addMethod('filter{true}.emit().repeat( out("DEFINITION").in("EXTENDS", "IMPLEMENTS") ).times('.self::MAX_LOOPING.')');
        }
        
        return $this;
    }
    
    public function goToAllTraits($self = self::INCLUDE_SELF) {
        if ($self === self::INCLUDE_SELF) {
            $this->addMethod('repeat( out("USE").hasLabel("Use").out("USE").in("DEFINITION") ).emit(hasLabel("Trait")).times('.self::MAX_LOOPING.')');
        } else {
            $this->addMethod('emit(hasLabel("Trait")).repeat( out("USE").hasLabel("Use").out("USE").in("DEFINITION") ).times('.self::MAX_LOOPING.')');
        }
        
        return $this;
    }

    public function goToAllImplements() {
        $this->addMethod('out("IMPLEMENTS").in("DEFINITION").emit(hasLabel("Interface")).
                repeat( __.out("EXTENDS").in("DEFINITION") ).times('.self::MAX_LOOPING.')');
        
        return $this;
    }

    public function goToTraits() {
        $this->addMethod('repeat( __.out("USE").hasLabel("Use").out("USE").in("DEFINITION") ).emit().times('.self::MAX_LOOPING.') ');
        
        return $this;
    }

    public function hasFunction() {
        $this->hasInstruction(array('Function', 'Method', 'Closure'));
        
        return $this;
    }

    public function hasClassTrait() {
        $this->hasInstruction(array('Class', 'Trait'));
        
        return $this;
    }

    public function hasClassInterface() {
        $this->hasInstruction(array('Class', 'Interface'));
        
        return $this;
    }

    public function hasTrait() {
        $this->hasInstruction('Trait');
        
        return $this;
    }

    public function hasInterface() {
        $this->hasInstruction('Interface');
        
        return $this;
    }

    public function hasLoop() {
        $this->hasInstruction(array('For', 'Foreach', 'Dowhile', 'While'));
        
        return $this;
    }

    public function hasIfthen() {
        $this->hasInstruction('Ifthen');
        
        return $this;
    }

    public function hasNoIfthen() {
        return $this->hasNoInstruction('Ifthen');
    }

    public function hasNoComparison() {
        return $this->hasNoInstruction('Comparison');
    }

    public function hasTryCatch() {
        $this->hasInstruction('Try');
        
        return $this;
    }

    public function hasNoTryCatch() {
        return $this->hasNoInstruction('Try');
    }

    public function hasNoCatch() {
        return $this->hasNoInstruction('Catch');
    }

    public function isLocalClass() {
        $linksUp = Token::linksAsList();

        $this->addMethod(<<<GREMLIN
sideEffect{ inside = it.get().value("fullnspath"); }
.where(  __.repeat( __.in($linksUp) ).until( hasLabel("Class") ).filter{ it.get().value("fullnspath") == inside; }.count().is(eq(1)) )

GREMLIN
);
        
        return $this;
    }
    
    public function isNotLocalClass() {
        $linksUp = Token::linksAsList();

        $this->addMethod(<<<GREMLIN
sideEffect{ inside = it.get().value("fullnspath"); }
.where(  __.repeat( __.in($linksUp) ).until( hasLabel("Class") ).filter{ it.get().value("fullnspath") == inside; }.count().is(eq(0)) )

GREMLIN
);
        
        return $this;
    }

    public function goToNamespace() {
        $this->goToInstruction(array('Namespace', 'Php'));
        
        return $this;
    }

    public function isLiteral() {
        // Closures are literal if not using a variable from the context
        $this->addMethod(<<<GREMLIN
hasLabel("Integer", "Boolean", "Null", "Magicconstant", "Real", "String", "Heredoc", "Closure", "Arrayliteral").has("constant", true)

GREMLIN
);

        return $this;
    }
    
    public function isNotLiteral() {
        // Closures are literal if not using a variable from the context
        $this->addMethod(<<<GREMLIN
not( hasLabel("Integer", "Boolean", "Null", "Magicconstant", "Real", "String", "Heredoc", "Closure", "Arrayliteral").has("constant", true) )

GREMLIN
);

        return $this;
    }
    
    public function fetchContext($context = self::CONTEXT_OUTSIDE_CLOSURE) {
        $forClosure = "                    // This is make variables in USE available in the parent level
                    if (it.out('USE').out('ARGUMENT').retain([current]).any()) {
                        context[it.atom] = 'Global';
                    }
";
        if ($context == self::CONTEXT_IN_CLOSURE) {
            $forClosure = "";
        }
        
        $this->addMethod(<<<GREMLIN
as("context")
.sideEffect{ line = it.get().value('line');
             fullcode = it.get().value('fullcode');
             file='None'; 
             theFunction = 'None'; 
             theClass='None'; 
             theNamespace='\\\\'; 
             }
.sideEffect{ line = it.get().value('line'); }
.until( hasLabel('File') ).repeat( 
    __.in($this->linksDown)
      .sideEffect{ if (it.get().label() == 'Function') { theFunction = it.get().value('code')} }
      .sideEffect{ if (it.get().label() in ['Class']) { theClass = it.get().value('fullcode')} }
      .sideEffect{ if (it.get().label() in ['Namespace']) { theNamespace = it.get().vertices(OUT, 'NAME').next().value('fullcode')} }
       )
.sideEffect{  file = it.get().value('fullcode');}
.sideEffect{ context = ['line':line, 'file':file, 'fullcode':fullcode, 'function':theFunction, 'class':theClass, 'namespace':theNamespace]; }
.select("context")

GREMLIN

);
        
        return $this;
    }
    
    public function run() {
        $this->analyze();
        $this->prepareQuery();

        $this->execQuery();
        
        return $this->rowCount;
    }
    
    public function getRowCount() {
        return $this->rowCount;
    }

    public function getProcessedCount() {
        return $this->processedCount;
    }

    public function getRawQueryCount() {
        return $this->rawQueryCount;
    }

    public function getQueryCount() {
        return $this->queryCount;
    }

    public abstract function analyze();

    public function printQuery() {
        $this->prepareQuery();
        
        foreach($this->queries as $id => $query) {
            echo $id, ")", PHP_EOL, print_r($query, true), print_r($this->queriesArguments[$id], true), PHP_EOL;

            krsort($this->queriesArguments[$id]);
            
            foreach($this->queriesArguments[$id] as $name => $value) {
                if (is_array($value)) {
                    if (is_array($value[key($value)])) {
                        foreach($value as $k => &$v) {
                            $v = "'''".$k."''':['''".implode("''', '''", $v)."''']";
                            $v = str_replace('\\', '\\\\', $v);
                        }
                        unset($v);
                        $query = str_replace($name, "[".implode(", ", $value)."]", $query);
                    } else {
                        $query = str_replace($name, "['".implode("', '", $value)."']", $query);
                    }
                } elseif (is_string($value)) {
                    $query = str_replace($name, "'".str_replace('\\', '\\\\', $value)."'", $query);
                } elseif (is_int($value)) {
                    $query = str_replace($name, $value, $query);
                } else {
                    assert(false, 'Cannot process argument of type '.gettype($value).PHP_EOL.__METHOD__.PHP_EOL);
                }
            }
            
            echo $query, PHP_EOL, PHP_EOL;
        }
        die();
    }

    public function debugQuery() {
        $methods = $this->methods;
        $arguments = $this->arguments;

        $nb = count($methods);
        for($i = 2; $i < $nb; ++$i) {
            $this->methods = array_slice($methods, 0, $i);
            $this->arguments = array_slice($arguments, 0, $i);
            $this->prepareQuery();
            $this->execQuery();
            echo  $this->rowCount, PHP_EOL;
            $this->rowCount = 0;
        }

        die();
    }
    
    public function prepareQuery() {
        // @doc This is when the object is a placeholder for others.
        if (count($this->methods) <= 1) { return true; }

        if (substr($this->methods[1], 0, 9) == 'hasLabel(') {
            $first = $this->methods[1];
            array_splice($this->methods, 1,1);
            $query = implode('.', $this->methods);
            $query = 'g.V().'.$first.'.groupCount("processed").by(count()).'.$query;
        } elseif (substr($this->methods[1], 0, 39) == 'where( __.in("ANALYZED").has("analyzer"') {
            $first = array_shift($this->methods); // remove first
            $init = array_shift($this->methods); // remove first
            preg_match('#"([^"\/]+?/[^"]+?)"#', $init, $r);
            $query = implode('.', $this->methods);
            $query = 'g.V().hasLabel("Analysis").has("analyzer", "'.$r[1].'").out("ANALYZED").as("first").groupCount("processed").by(count()).'.$query;
            unset($this->methods[1]);
        } else {
            die('No optimization : gremlin query in analyzer should have use g.V. ! '.$this->methods[1]);
        }
        
        // search what ? All ?
        $query = <<<GREMLIN

{$query}

GREMLIN;
//        $query .= '.where( __.in("ANALYZED").has("analyzer", "'.$this->analyzerQuoted.'").count().is(eq(0)) ).groupCount("total").by(count()).addE("ANALYZED").from(g.V('.$this->analyzerId.')).cap("processed", "total")
        $query .= '.dedup().groupCount("total").by(count()).addE("ANALYZED").from(g.V('.$this->analyzerId.')).cap("processed", "total")

// Query (#'.(count($this->queries) + 1).') for '.$this->analyzerQuoted.'
// php '.$this->config->executable." analyze -p ".$this->config->project.' -P '.$this->analyzerQuoted." -v".PHP_EOL;

        $this->queries[] = $query;
        $this->queriesArguments[] = $this->arguments;

         // initializing a new query
        return $this->initNewQuery();
    }

    public function rawQuery() {
        // @doc This is when the object is a placeholder for others.
        if (count($this->methods) <= 1) { return true; }
        
        $query = implode('.', $this->methods);
        $query = 'g.V().'.
                 $query.
                 '
// Query (#'.(count($this->queries) + 1).') for '.$this->analyzerQuoted.'
// php '.$this->config->executable." analyze -p ".$this->config->project.' -P '.$this->analyzerQuoted." -v".PHP_EOL;

        $arguments = $this->arguments;

        $this->initNewQuery();
        
        return $this->query($query, $arguments);
    }
    
    private function initNewQuery() {
        $this->methods = array();
        $this->addMethod('as("first")');

        $this->arguments = array();
        
        return true;
    }
    
    public function execQuery() {
        if (empty($this->queries)) { return true; }

        // @todo add a test here ?
        foreach($this->queries as $id => $query) {
            $r = $this->query($query, $this->queriesArguments[$id]);
            ++$this->queryCount;
            
            if (isset($r[0]->processed->{1})) {
                $this->processedCount += $r[0]->processed->{1};
                $this->rowCount       += isset($r[0]->total->{1}) ? $r[0]->total->{1} : 0;
            }
        }

        // reset for the next
        $this->queries = array();
        $this->queriesArguments = array();
        
        // @todo multiple results ?
        // @todo store result in the object until reading.
        return $this->rowCount;
    }

    protected function loadIni($file, $index = null) {
        $fullpath = $this->config->dir_root.'/data/'.$file;
        
        assert(file_exists($fullpath), 'Ini file "'.$fullpath.'" doesn\'t exists.');

        $iniFile = parse_ini_file($fullpath);
        
        if ($index !== null && isset($iniFile[$index])) {
            return $iniFile[$index];
        }
        
        return $iniFile;
    }

    protected function loadJson($file) {
        $fullpath = $this->config->dir_root.'/data/'.$file;

        assert(file_exists($fullpath), 'JSON file "'.$fullpath.'" doesn\'t exists.');

        $jsonFile = json_decode(file_get_contents($fullpath));
        
        return $jsonFile;
    }
    
    public static function listAnalyzers() {
        self::initDocs();
        return self::$docs->listAllAnalyzer();
    }

    public function hasResults() {
        return $this->rowCount > 0;
    }

    public function getSeverity() {
        self::initDocs();
        return Analyzer::$docs->getSeverity($this->analyzer);
    }

    public function getTimeToFix() {
        self::initDocs();
        return Analyzer::$docs->getTimeToFix($this->analyzer);
    }

    public function getPhpversion() {
        return $this->phpVersion;
    }

    public function getphpConfiguration() {
        return $this->phpConfiguration;
    }
    
    public function makeFullNsPath($functions) {
        $cb = function ($x) {
            $r = mb_strtolower($x);
            if (isset($r[0]) && $r[0] != '\\') {
                $r = '\\' . $r;
            }
            return $r;
        };
        if (is_string($functions)) {
            return $cb($functions);
        } elseif (is_array($functions)) {
            $r = array_map($cb, $functions);
        } else {
            throw new \Exception('Function is of the wrong type : '.var_export($functions));
        }
        return $r;
    }
    
    private function tolowercase(&$code) {
        if (is_array($code)) {
            foreach($code as &$v) {
                $v = mb_strtolower($v);
            }
            unset($v);
        } else {
            $code = mb_strtolower($code);
        }
    }
    
    public static function makeBaseName($className) {
        // No Exakat, no Analyzer, using / instead of \
        return $className;
    }

    private function propertyIs($property, $code, $caseSensitive = self::CASE_INSENSITIVE) {
        if (is_array($code) && empty($code) ) {
            return $this;
        }

        if ($caseSensitive === self::CASE_SENSITIVE) {
            $caseSensitive = '';
        } else {
            $this->tolowercase($code);
            $caseSensitive = '.toLowerCase()';
        }
        
        if (is_array($code)) {
            $this->addMethod('filter{ it.get().value("'.$property.'")'.$caseSensitive.' in ***; }', $code);
        } else {
            $this->addMethod('filter{it.get().value("'.$property.'")'.$caseSensitive.' == ***}', $code);
        }
        
        return $this;
    }

    private function propertyIsNot($property, $code, $caseSensitive = self::CASE_INSENSITIVE) {
        if ($caseSensitive === self::CASE_SENSITIVE) {
            $caseSensitive = '';
        } else {
            $this->tolowercase($code);
            $caseSensitive = '.toLowerCase()';
        }
        
        if (is_array($code)) {
            $this->addMethod('filter{ !(it.get().value("'.$property.'")'.$caseSensitive.' in ***); }', $code);
        } else {
            $this->addMethod('filter{it.get().value("'.$property.'")'.$caseSensitive.' != ***}', $code);
        }
        
        return $this;
    }
    
    protected function SorA($v) {
        if (is_array($v)) {
            return makeList($v);
        } else {
            return '"'.$v.'"';
        }
    }

    private function assertLink($link) {
        if (is_string($link)) {
            assert(!in_array($link, array('KEY', 'ELEMENT', 'PROPERTY')), $link.' is no more');
            assert($link === strtoupper($link), 'Wrong format for LINK name : '.$link);
        } else {
            foreach($link as $l) {
                assert(!in_array($l, array('KEY', 'ELEMENT', 'PROPERTY')), $l.' is no more');
                assert($l === strtoupper($l), 'Wrong format for LINK name : '.$l);
            }
        }
        return true;
    }

    private function assertToken($token) {
        if (is_string($token)) {
            assert($token === strtoupper($token) && substr($token, 0, 2) === 'T_', 'Wrong token : '.$token);
        } else {
            foreach($token as $t) {
                assert($t === strtoupper($t) && substr($t, 0, 2) === 'T_', 'Wrong token : '.$t);
            }
        }
        return true;
    }
    
    private function assertAtom($atom) {
        if (is_string($atom)) {
            assert($atom !== 'Property', 'Property is no more');
            assert($atom === ucfirst(mb_strtolower($atom)), 'Wrong format for atom name : "'.$atom.'"');
        } else {
            foreach($atom as $a) {
                assert($a !== 'Property', 'Property is no more');
                assert($a === ucfirst(mb_strtolower($a)), 'Wrong format for atom name : '.$a);
            }
        }
        return true;
    }

}
?>
