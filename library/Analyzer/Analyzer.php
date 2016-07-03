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


namespace Analyzer;

abstract class Analyzer {
    protected $neo4j          = null;
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
    
    protected $config         = null;
    
    static public $analyzers  = array();
    private $analyzer         = '';       // Current class of the analyzer (called from below)
    
    protected $apply          = null;

    protected $phpVersion       = 'Any';
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

    private $isCompatible            = self::UNKNOWN_COMPATIBILITY;
    const COMPATIBLE                 =  0;
    const UNKNOWN_COMPATIBILITY      = -1;
    const VERSION_INCOMPATIBLE       = -2;
    const CONFIGURATION_INCOMPATIBLE = -3;

    const CONTEXT_IN_CLOSURE = 1;
    const CONTEXT_OUTSIDE_CLOSURE = 2;
    
    static public $docs = null;

    private $gremlin = null;
    public static $gremlinStatic = null;

    public function __construct($gremlin) {
        $this->gremlin = $gremlin;
        self::$gremlinStatic = $gremlin;
        
        $this->analyzer = get_class($this);
        $this->analyzerQuoted = str_replace('\\', '\\\\', $this->analyzer);
        $this->analyzerInBase = str_replace('\\', '/', str_replace('Analyzer\\', '', $this->analyzer));

        $this->code = $this->analyzer;
        
        self::initDocs();
        
        $this->apply = new AnalyzerApply();
        $this->apply->setAnalyzer($this->analyzer);
        
        $this->description = new \Description($this->analyzer);
        
        $this->_as('first');
    }
    
    public function __destruct() {
        if ($this->path_tmp !== null) {
            unlink($this->path_tmp);
        }
    }
    
    public function setConfig($config) {
        $this->config = $config;
    }

    public function getInBaseName() {
        return $this->analyzerInBase;
    }
    
    static public function initDocs() {
        if (Analyzer::$docs === null) {
            $config = \Config::factory();
            
            $pathDocs = $config->dir_root.'/data/analyzers.sqlite';
            self::$docs = new Docs($pathDocs);
        }
    }
    
    public static function getClass($name) {
        // accepted names :
        // PHP full name : Analyzer\\Type\\Class
        // PHP short name : Type\\Class
        // Human short name : Type/Class
        // Human shortcut : Class (must be unique among the classes)

        if (strpos($name, '\\') !== false) {
            if (substr($name, 0, 9) == 'Analyzer\\') {
                $class = $name;
            } else {
                $class = 'Analyzer\\'.$name;
            }
        } elseif (strpos($name, '/') !== false) {
            $class = 'Analyzer\\'.str_replace('/', '\\', $name);
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
    
    public static function getInstance($name) {
        static $instanciated = array();
        
        if ($analyzer = static::getClass($name)) {
            if (!isset($instanciated[$analyzer])) {
                $instanciated[$analyzer] = new $analyzer(self::$gremlinStatic);
            }
            return $instanciated[$analyzer];
        } else {
            display( "No such class as '" . $name . "'\n");
            return null;
        }
    }
    
    public function getDescription() {
        return $this->description;
    }

    static public function getThemeAnalyzers($theme) {
        self::initDocs();
        return Analyzer::$docs->getThemeAnalyzers($theme);
    }

    public function getThemes() {
        $analyzer = str_replace('\\', '/', substr(get_class($this), 9));
        return Analyzer::$docs->getThemeForAnalyzer($analyzer);
    }

    public function getAppinfoHeader($lang = 'en') {
        if ($this->appinfo === null) {
            $this->getDescription();
        }

        return $this->appinfo;
    }
    
    static public function getAnalyzers($theme) {
        return Analyzer::$analyzers[$theme];
    }

    private function addMethod($method, $arguments = null) {
        if ($arguments === null) { // empty
            $this->methods[] = $method;
        } elseif (func_num_args() >= 2) {
            $arguments = func_get_args();
            array_shift($arguments);
            $argnames = array(str_replace('***', '%s', $method));
            foreach($arguments as $arg) {
                $argname = 'arg'.(count($this->arguments));
                $this->arguments[$argname] = $arg;
                $argnames[] = $argname;
            }
            $this->methods[] = call_user_func_array('sprintf', $argnames);
        } else { // one argument
            $argname = 'arg'.count($this->arguments);
            $this->arguments[$argname] = $arguments;
            $this->methods[] = str_replace('***', $argname, $method);
        }

        return $this;
    }
    
    public function init() {
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
            $query = "g.addV('Analysis').property('analyzer','{$this->analyzerQuoted}')";
            $res = $this->query($query);
            
            $this->analyzerId = $res[0]->id;
        }
    }

    public function isDone() {
        $result = $this->query("g.getRawGraph().index().existsForNodes('analyzers');");
        if ($result[0] == 0) {
            $this->query("g.createIndex('analyzers', Vertex)");

            return false;
        }
        
        $query = "g.idx('analyzers')[['analyzer':'{$this->analyzerQuoted}']].count() == 1";
        $res = $this->query($query);
        return (bool) $res[0][0];
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
        if ($this->phpVersion === 'Any') {
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
            if (version_compare($version, $lower) >= 0 && version_compare($version, $upper) <= 0) {
                return true;
            } else {
                return false;
            }
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
    
    public function setApplyBelow($applyBelow = true) {
        $this->apply->setApplyBelow($applyBelow);
        
        $this->addMethod('sideEffect{ applyBelowRoot = it }');
        
        return $this;
    }

    public function query($queryString, $arguments = null) {
        try {
            $result = $this->gremlin->query($queryString, $arguments);
        } catch (\Exceptions\GremlinException $e) {
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
        
        return $result->results;
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

    private function hasNoInstruction($atom = 'Function') {
        $linksDown = \Tokenizer\Token::linksAsList();
        $this->addMethod('where( 
repeat(__.in('.$linksDown.'))
.until(hasLabel("File")).emit().hasLabel('.$this->SorA($atom).').count().is(eq(0)))');
        
        return $this;
    }

    private function hasInstruction($atom = 'Function') {
        $linksDown = \Tokenizer\Token::linksAsList();
        $this->addMethod('where( 
repeat(__.in('.$linksDown.'))
.until(hasLabel("File")).emit().hasLabel('.$this->SorA($atom).').count().is(neq(0)))');
        
        return $this;
    }

    private function goToInstruction($atom = 'Namespace') {
        $linksDown = \Tokenizer\Token::linksAsList();
        $this->addMethod('repeat( __.in(
'.$linksDown.'
        )).until(hasLabel('.$this->SorA($atom).', "File") )');
    }

    public function tokenIs($atom) {
        $this->addMethod('has("token", within('.$this->SorA($atom).'))');
        
        return $this;
    }

    public function tokenIsNot($atom) {
        $this->addMethod('not(has("token", within('.$this->SorA($atom).')))');
        
        return $this;
    }
    
    public function atomIs($atom) {
        $this->addMethod('hasLabel('.$this->SorA($atom).')');
        
        return $this;
    }

    public function atomIsNot($atom) {
        $this->addMethod('not(hasLabel('.$this->SorA($atom).'))');
        
        return $this;
    }

    public function atomFunctionIs($atom) {
        $this->atomIs('Functioncall');
        $this->functioncallIs($atom);

        return $this;
    }
    
    public function functioncallIs($fullnspath) {
        $this->atomIs('Functioncall')
             ->hasNoIn(array('METHOD', 'NEW'))
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR', 'T_ARRAY', 'T_EVAL', 'T_ISSET', 'T_EXIT', 'T_UNSET', 'T_ECHO', 'T_PRINT', 'T_LIST', 'T_EMPTY', 'T_OPEN_BRACKET'))
             ->fullnspathIs($this->makeFullNsPath($fullnspath));

        return $this;
    }

    public function classIs($class) {
        if (is_array($class)) {
            $this->addMethod('as("classIs").in.loop(1){!(it.object.token in ["T_CLASS", "T_FILENAME"])}.filter{it.token != "T_CLASS" || it.out("NAME").next().code in ***}.back("classIs")', $class);
        } elseif ($class == 'Global') {
            $this->addMethod('as("classIs").in.loop(1){!(it.object.token in ["T_CLASS", "T_FILENAME"])}.filter{it.token != "T_CLASS"}.back("classIs")');
        } else {
            $this->addMethod('as("classIs").in.loop(1){!(it.object.token in ["T_CLASS", "T_FILENAME"])}.filter{it.token != "T_CLASS" || it.out("NAME").next().code != ***}.back("classIs")', $class);
        }
        
        return $this;
    }

    public function classIsNot($class) {
        if (is_array($class)) {
            $this->addMethod('as("classIsNot").in.loop(1){!(it.object.token in ["T_CLASS", "T_FILENAME"])}.filter{it.token == "T_FILENAME" || !(it.out("NAME").next().code in ***)}.back("classIsNot")', $class);
        } elseif ($class == 'Global') {
            $this->addMethod('as("classIsNot").in.loop(1){!(it.object.token in ["T_CLASS"])}.back("classIsNot")');
        } else {
            $this->addMethod('as("classIsNot").in.loop(1){!(it.object.token in ["T_CLASS", "T_FILENAME"])}.filter{it.token == "T_FILENAME" || it.out("NAME").next().code != ***}.back("classIsNot")', $class);
        }
        
        return $this;
    }

    public function traitIs($trait) {
        if (is_array($trait)) {
            $this->addMethod('as("traitIs").in.loop(1){!(it.object.token in ["T_TRAIT", "T_FILENAME"])}.filter{it.token != "T_TRAIT" || !(it.out("NAME").next().code in ***)}.back("traitIs")', $trait);
        } elseif ($trait == 'Global') {
            $this->addMethod('as("traitIs").in.loop(1){!(it.object.token in ["T_TRAIT", "T_FILENAME"])}.filter{it.token != "T_TRAIT"}.back("traitIs")');
        } else {
            $this->addMethod('as("traitIs").in.loop(1){!(it.object.token in ["T_TRAIT", "T_FILENAME"])}.filter{it.token != "T_TRAIT" || it.out("NAME").next().code != ***}.back("traitIs")', $trait);
        }
        
        return $this;
    }

    public function traitIsNot($trait) {
        if (is_array($trait)) {
            $this->addMethod('as("traitIsNot").in.loop(1){!(it.object.token in ["T_TRAIT", "T_FILENAME"])}.filter{it.token == "T_FILENAME" || !(it.out("NAME").next().code in ***)}.back("traitIsNot")', $trait);
        } elseif ($trait == 'Global') {
            $this->addMethod('as("traitIsNot").in.loop(1){!(it.object.token in ["T_TRAIT"])}.back("traitIsNot")');
        } else {
            $this->addMethod('as("traitIsNot").in.loop(1){!(it.object.token in ["T_TRAIT", "T_FILENAME"])}.filter{it.token == "T_FILENAME" || it.out("NAME").next().code != ***}.back("traitIsNot")', $trait);
        }
        
        return $this;
    }
    
    public function functionIs($function) {
        if (is_array($function)) {
            $this->addMethod('as("functionIs").in.loop(1){!(it.object.token in ["T_FUNCTION", "T_FILENAME"])}.filter{it.token != "T_FILENAME" || it.out("NAME").next().code in ***}.back("functionIs")', $function);
        } elseif ($function == 'Global') {
            $this->addMethod('as("functionIs").in.loop(1){!(it.object.token in ["T_FUNCTION", "T_FILENAME"])}.filter{it.token != "T_FUNCTION"}.back("functionIs")');
        } else {
            $this->addMethod('as("functionIs").in.loop(1){!(it.object.token in ["T_FUNCTION", "T_FILENAME"])}.filter{it.token != "T_FILENAME" || it.out("NAME").next().code != ***}.back("functionIs")', $function);
        }
        
        return $this;
    }

    public function functionIsNot($function) {
        if (is_array($function)) {
                $this->addMethod('as("functionIsNot").in.loop(1){!(it.object.token in ["T_FUNCTION", "T_FILENAME"])}.filter{it.token == "T_FILENAME" || !(it.out("NAME").next().code in ***)}.back("functionIsNot")', $function);
        } elseif ($function == 'Global') {
            $this->addMethod('as("functionIsNot").in.loop(1){!(it.object.token in ["T_FUNCTION"])}.back("functionIsNot")');
        } else {
            $this->addMethod('as("functionIsNot").in.loop(1){!(it.object.token in ["T_FUNCTION", "T_FILENAME"])}.filter{it.token == "T_FILENAME" || it.out("NAME").next().code != ***}.back("functionIsNot")', $function);
        }
        
        return $this;
    }

    public function namespaceIs($namespace) {
        if (is_array($namespace)) {
            $this->addMethod('as("namespaceIs").in.loop(1){!(it.object.token in ["T_NAMESPACE", "T_FILENAME"])}.filter{ it.token == "T_NAMESPACE" && it.code in *** }.back("namespaceIs")', $namespace);
        } elseif ($namespace == 'Global') {
            $this->addMethod('as("namespaceIs").in.loop(1){!(it.object.token in ["T_NAMESPACE", "T_FILENAME"])}.filter{ it.token == "T_FILENAME" || it.out("NAMESPACE").next().code == "Global" }.back("namespaceIs")');
        } else {
            $this->addMethod('as("namespaceIs").in.loop(1){!(it.object.token in ["T_NAMESPACE", "T_FILENAME"])}.filter{ it.token == "T_NAMESPACE" && it.code == *** }.back("namespaceIs")', $namespace);
        }
        
        return $this;
    }

    public function atomInside($atom) {
        if (is_array($atom)) {
            $atom = join('", "', $atom);
            $gremlin = <<<GREMLIN
repeat( out() ).times(15).emit( hasLabel("$atom") )
GREMLIN;
        } else {
            $gremlin = <<<GREMLIN
repeat( out() ).times(15).emit( hasLabel("$atom") )
GREMLIN;
        }
        $this->addMethod($gremlin, $atom);
        
        return $this;
    }

    public function noAtomInside($atom) {
        if (is_array($atom)) {
            $atom = join('", "', $atom);
            $gremlin = <<<GREMLIN
where( repeat( out() ).times(15).emit( hasLabel("$atom") ).count().is(eq(0)) )
GREMLIN;
        } else {
            $gremlin = <<<GREMLIN
where( repeat( out() ).times(15).emit( hasLabel("$atom") ).count().is(eq(0)) )
GREMLIN;
        }
        $this->addMethod($gremlin, $atom);
        
        return $this;
    }

    public function atomAboveIs($atom) {
        if (is_array($atom)) {
            $this->addMethod('in().loop(1){!(it.object.atom in ***)}{it.object.atom in ***}', $atom, $atom);
        } else {
            $this->addMethod('in().loop(1){it.object.atom != ***}{it.object.atom == ***}', $atom, $atom);
        }
        
        return $this;
    }
    
    public function trim($property, $chars = '\'\"') {
        $this->addMethod('transform{it.'.$property.'.replaceFirst("^['.$chars.']?(.*?)['.$chars.']?\$", "\$1")}');
        
        return $this;
    }

    public function analyzerIs($analyzer) {
        if (is_array($analyzer)) {
            foreach($analyzer as &$a) {
                $a = str_replace('\\', '\\\\', self::getClass($analyzer));
            }
            unset($a);
        } elseif ($analyzer == 'self') {
            $analyzer = str_replace('\\', '\\\\', $this->analyzer);
        } else {
            $analyzer = str_replace('\\', '\\\\', self::getClass($analyzer));
        }
        $this->addMethod('where( __.in("ANALYZED").has("analyzer", '.$this->SorA($analyzer).').count().is(neq(0)) )');

        return $this;
    }

    public function analyzerIsNot($analyzer) {
        if (is_array($analyzer)) {
            foreach($analyzer as &$a) {
                $a = str_replace('\\', '\\\\', self::getClass($analyzer));
            }
            unset($a);
        } elseif ($analyzer == 'self') {
            $analyzer = str_replace('\\', '\\\\', $this->analyzer);
        } else {
            $analyzer = str_replace('\\', '\\\\', self::getClass($analyzer));
        }
        $this->addMethod('where( __.in("ANALYZED").has("analyzer", '.$this->SorA($analyzer).').count().is(eq(0)) )');

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
            $this->addMethod('not(has("'.$property.'", within('.$this->SorA($value).')))');
        }
        
        return $this;
    }

    public function isMore($property, $value = 0) {
        if (is_int($value)) {
            $this->addMethod("filter{ it.get().value('$property').toLong() > $value}");
        } else {
            // this is a variable name
            $this->addMethod("filter{ it.get().value('$property').toLong() > $value;}", $value);
        }

        return $this;
    }

    public function isLess($property, $value = 0) {
        if (is_int($value)) {
            $this->addMethod('filter{ it.get().value("'.$property.'").toLong() < '.$value.'}');
        } else {
            // this is a variable name
            $this->addMethod("filter{ it.get().value('$property').toLong() < $value;}", $value);
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
            $this->addMethod('out("'.$link.'").has("rank", eq('.abs(intval($rank)).'))');
        }

        return $this;
    }

    public function noChildWithRank($edgeName, $rank = '0') {
        $this->addMethod('where( __.out('.$this->SorA($edgeName).').has("rank", '.abs(intval($rank)).').count().is(eq(0)) )');

        return $this;
    }

    public function codeIs($code, $caseSensitive = false) {
        return $this->propertyIs('code', $code, $caseSensitive);
    }

    public function codeIsNot($code, $caseSensitive = false) {
        return $this->propertyIsNot('code', $code, $caseSensitive);
    }

    public function noDelimiterIs($code, $caseSensitive = false) {
        $this->addMethod('hasLabel("String")', $code);
        return $this->propertyIs('noDelimiter', $code, $caseSensitive);
    }

    public function noDelimiterIsNot($code, $caseSensitive = false) {
        $this->addMethod('hasLabel("String")', $code);
        return $this->propertyIsNot('noDelimiter', $code, $caseSensitive);
    }

    public function fullnspathIs($code, $caseSensitive = false) {
        return $this->propertyIs('fullnspath', $code, $caseSensitive);
    }

    public function fullnspathIsNot($code, $caseSensitive = false) {
        return $this->propertyIsNot('fullnspath', $code, $caseSensitive);
    }
    
    public function codeIsPositiveInteger() {
        $this->addMethod('filter{ if( it.code.isInteger()) { it.code > 0; } else { true; }}', null); // may be use toInteger() ?

        return $this;
    }

    public function samePropertyAs($property, $name, $caseSensitive = false) {
        if ($caseSensitive === true || $property == 'line' || $property == 'rank') {
            $caseSensitive = '';
        } else {
            $caseSensitive = '.toLowerCase()';
        }
        $this->addMethod('filter{ it.get().value("'.$property.'")'.$caseSensitive.' == '.$name.$caseSensitive.'}');

        return $this;
    }

    public function notSamePropertyAs($property, $name, $caseSensitive = false) {
        if ($caseSensitive === true || $property == 'line' || $property == 'rank') {
            $caseSensitive = '';
        } else {
            $caseSensitive = '.toLowerCase()';
        }
        $this->addMethod('filter{ it.get().value("'.$property.'")'.$caseSensitive.' != '.$name.$caseSensitive.'}');

        return $this;
    }

    public function isPropertyIn($property, $name, $caseSensitive = false) {
        if ($caseSensitive === true || $property === 'line' || $property === 'rank') {
            $caseSensitive = '';
        } else {
            $caseSensitive = '.toLowerCase()';
        }
        
        if (is_array($name)) {
            $this->addMethod('filter{ it.'.$property.$caseSensitive.' in *** }', $name);
        } else {
            $this->addMethod('filter{ it.'.$property.$caseSensitive.' != *** }', $name);
        }
    
        return $this;
    }

    public function isPropertyNotIn($property, $name, $caseSensitive = false) {
        if ($caseSensitive === true || $property == 'line' || $property == 'rank') {
            $caseSensitive = '';
        } else {
            $caseSensitive = '.toLowerCase()';
        }

        // Array, is a list of literal
        if (is_array($name)) {
            $this->addMethod('filter{ !(it.'.$property.$caseSensitive.' in *** )}', $name);
        } else {
        // String, is a variable name
            $this->addMethod('filter{ !(it.'.$property.$caseSensitive.' in '.$name.' )}');
        }
    
        return $this;
    }

    public function sameContextAs($storage = 'context', $context = array('Namespace', 'Class', 'Function')) {
        foreach($context as &$c) {
            $c = $storage.'["'.$c.'"] == '.$context.'["'.$c.'"] ';
        }
        unset($c);
        $context = join(' && ', $context);
        
        $this->addMethod('filter{ '.$context.' }');

        return $this;
    }
    
    public function saveArglistAs($name) {
        // Calculate the arglist, normalized it, then put it in a variable
        // This needs to be in Arguments, (both Functioncall or Function)
        $this->addMethod(<<<GREMLIN
sideEffect{ 
    s = [];
    it.get().vertices(OUT, 'ARGUMENT').sort{it.value('rank')}.each{ 
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
        if ($property == 'arglist') {
        } else {
            $this->addMethod("sideEffect{ $name = it.get().value('$property'); }");
        }

        return $this;
    }

    public function isGrandParent() {
        $this->addMethod('filter{ fns = it.fullnspath; it.in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}.out("EXTENDS")
                         .filter{ g.idx("classes").get("path", it.fullnspath).any(); }
                         .transform{ g.idx("classes")[["path":it.fullnspath]].next(); }.loop(2){true}{it.object.fullnspath == fns}.any() }');

        return $this;
    }

    public function isNotGrandParent() {
        $this->addMethod('filter{ fns = it.fullnspath; it.in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}.out("EXTENDS")
                         .filter{ g.idx("classes").get("path", it.fullnspath).any(); }
                         .transform{ g.idx("classes")[["path":it.fullnspath]].next(); }.loop(2){true}{it.object.fullnspath == fns}.any() == false}');

        return $this;
    }

    public function fullcodeTrimmed($code, $trim = "\"'", $caseSensitive = false) {
        if ($caseSensitive === true) {
            $caseSensitive = '';
        } else {
            $this->tolowercase($code);
            $caseSensitive = '.toLowerCase()';
        }
        
        $trim = addslashes($trim);
        if (is_array($code)) {
            $this->addMethod("filter{it.fullcode$caseSensitive.replaceFirst(\"^[$trim]?(.*?)[$trim]?\\\$\", \"\\\$1\") in ***}", $code);
        } else {
            $this->addMethod("filter{it.fullcode$caseSensitive.replaceFirst(\"^[$trim]?(.*?)[$trim]?\\\$\", \"\\\$1\") == ***}", $code);
        }
        
        return $this;
    }
    
    public function fullcodeIs($code, $caseSensitive = false) {
        $this->propertyIs('fullcode', $code, $caseSensitive);
        
        return $this;
    }
    
    public function fullcodeIsNot($code, $caseSensitive = false) {
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

    public function cleanAnalyzerName($gremlin) {
        $dependencies = $this->dependsOn();
        $fullNames = array_map('\Analyzer\Analyzer::makeBaseName', $dependencies);
        
        return str_replace($dependencies, $fullNames, $gremlin);
    }

    public function filter($filter, $arguments = null) {
        $filter = $this->cleanAnalyzerName($filter);
        $this->addMethod("filter{ $filter }", $arguments);

        return $this;
    }

    public function codeLength($length = ' == 1 ') {
        // @todo add some tests ? Like Operator / value ?
        $this->addMethod("filter{it.code.length() $length}");

        return $this;
    }

    public function fullcodeLength($length = ' == 1 ') {
        // @todo add some tests ? Like Operator / value ?
        $this->addMethod("filter{it.fullcode.length() $length}");

        return $this;
    }

    public function groupCount($column) {
        $this->addMethod("groupCount(m){it.$column}");
        
        return $this;
    }

    public function eachCounted($variable, $times, $comp = '==') {
        $this->addMethod(<<<GREMLIN
//groupCount('counts').by(label).cap('a').map{ it.get().findAll{ it.value > 2}; }
groupCount('counts').by(

{{$variable}}{it}.iterate();

// This is plugged into each{}
m.findAll{ it.value.size() $comp $times}.values().flatten().each{ n.add(it); }
GREMLIN
);

        return $this;
    }

    public function regexIs($column, $regex) {
        $this->addMethod(<<<GREMLIN
filter{ (it.get().value('$column') =~ "$regex" ).getCount() > 0 }
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

    protected function outIs($edgeName) {
        $this->addMethod('out('.$this->SorA($edgeName).')');

        return $this;
    }

    // follows a link if it is there (and do nothing otherwise)
    protected function outIsIE($edgeName) {
        // alternative : coalesce(out('LEFT'),  __.filter{true} )
        $this->addMethod("until(__.outE(".$this->SorA($edgeName).").count().is(eq(0))).repeat(out(".$this->SorA($edgeName)."))");
        
        return $this;
    }

    public function outIsNot($edgeName) {
        $this->addMethod('where( __.outE('.$this->SorA($edgeName).').count().is(eq(0)))');
        
        return $this;
    }

    public function nextSibling($link = 'ELEMENT') {
        $this->addMethod('sideEffect{sibling = it.get().values("rank").next();}.in("'.$link.'").out("'.$link.'").filter{sibling + 1 == it.get().values("rank").next()}');

        return $this;
    }

    public function nextSiblings($link = 'ELEMENT') {
        $this->addMethod('sideEffect{sibling = it.get().values("rank").next();}.in("'.$link.'").out("'.$link.'").filter{sibling + 1 <= it.get().values("rank").next() }');

        return $this;
    }

    public function previousSibling($link = 'ELEMENT') {
        $this->addMethod('sideEffect{sibling = it.get().values("rank").next();}.in("'.$link.'").out("'.$link.'").filter{sibling - 1 == it.get().values("rank").next()}');

        return $this;
    }

    public function previousSiblings($link = 'ELEMENT') {
        $this->addMethod('filter{it.get().values("rank").next() > 0}.sideEffect{sibling = it.get().values("rank").next();}.in("'.$link.'").out("'.$link.'").filter{sibling + 1 <= it.get().values("rank").next() }');

        return $this;
    }

    public function nextVariable($code) {
        $this->addMethod(<<<GREMLIN
sideEffect{ init = it;}
.filter{ nextVariable = []; it
.in.loop(1){it.object.atom != "Function"}{(it.object.atom == "Function") && (it.object.out("NAME").hasNot("code", "").any())}
.out('BLOCK').out.loop(1){true}{it.object.atom == 'Variable' && it.object.line > init.line && it.object.code == init.code}
.fill(nextVariable);
nextVariable.sort{ it.line}.size() > 0;
}
.transform{ nextVariable[0]}

GREMLIN
);

        return $this;
    }

    public function inIs($edgeName) {
        $this->addMethod('in('.$this->SorA($edgeName).')');
        
        return $this;
    }

    // follows a link if it is there (and do nothing otherwise)
    protected function inIsIE($edgeName) {
        $this->addMethod('until(__.inE('.$this->SorA($edgeName).').count().is(eq(0))).repeat(__.in('.$this->SorA($edgeName).'))');
        
        return $this;
    }

    public function inIsNot($edgeName) {
        $this->addMethod('where( __.inE('.$this->SorA($edgeName).').count().is(eq(0)))');
        
        return $this;
    }

    public function raw($query) {
        ++$this->rawQueryCount;
        $query = $this->cleanAnalyzerName($query);

        $this->addMethod($query);
        
        return $this;
    }

    public function hasIn($edgeName) {
        $this->addMethod('where( __.in('.$this->SorA($edgeName).').count().is(neq(0)) )');
        
        return $this;
    }
    
    public function hasNoIn($edgeName) {
        $this->addMethod('where( __.in('.$this->SorA($edgeName).').count().is(eq(0)) )');
        
        return $this;
    }

    public function hasOut($edgeName) {
        $this->addMethod('where( out('.$this->SorA($edgeName).').count().is(neq(0)) )');
        
        return $this;
    }
    
    public function hasNoOut($edgeName) {
        $this->addMethod('where( out('.$this->SorA($edgeName).').count().is(eq(0)) )');
        
        return $this;
    }

    public function isInCatchBlock() {
        $this->addMethod('filter{ it.in.loop(1){it.object.atom != "Catch"}{(it.object.atom == "Catch")}.any()');
        
        return $this;
    }

    public function hasNoCatchBlock() {
        $this->hasNoInstruction('Catch');
        
        return $this;
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
        
        $this->addMethod('where( __'.$in.'.hasLabel('.$this->SorA($parentClass).').count().is(eq(0)) )');
        
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

    public function isLambda() {
        $this->hasChildren('Void', 'NAME');
        
        return $this;
    }
    
    public function isNotLambda() {
        $this->hasNoChildren('Void', 'NAME');
        
        return $this;
    }
        
    public function hasConstantDefinition() {
        $this->addMethod("filter{ g.idx('constants')[['path':it.fullnspath]].any()}");
    
        return $this;
    }

    public function hasNoConstantDefinition() {
        $this->addMethod("filter{ g.idx('constants')[['path':it.fullnspath]].any() == false}");
    
        return $this;
    }

    public function hasFunctionDefinition() {
        $this->addMethod('where( __.in("DEFINITION").hasLabel("Function").count().is(eq(1)))');
    
        return $this;
    }

    public function hasNoFunctionDefinition() {
        $this->addMethod('where( __.in("DEFINITION").hasLabel("Function").count().is(eq(0)))');
    
        return $this;
    }

    public function functionDefinition() {
        $this->addMethod('in("DEFINITION")');
    
        return $this;
    }

    public function goToCurrentScope() {
        $this->addMethod('in.loop(1){!(it.object.atom in ["Function", "Phpcode"])}{(it.object.atom in ["Function", "Phpcode"])}');
        
        return $this;
    }
    
    public function goToFunction() {
        $linksDown = \Tokenizer\Token::linksAsList();
        $this->addMethod('repeat(__.in(
'.$linksDown.'
)).until(and(hasLabel("Function"), where(__.out("NAME").not(has("atom", "Void")) )))');
        
        return $this;
    }

    public function hasNoFunction() {
        $this->hasNoInstruction('Function');
        
        return $this;
    }
    
    public function goToFile() {
        $this->goToInstruction('File');
        
        return $this;
    }

    public function noNamespaceDefinition() {
        $this->addMethod("hasNot('fullnspath', null).filter{ g.idx('namespaces')[['path':it.fullnspath]].any() == false }");
    
        return $this;
    }

    public function classDefinition() {
        $this->addMethod('in("DEFINITION")');
    
        return $this;
    }

    public function noClassDefinition() {
        $this->addMethod('where(__.in("DEFINITION").count().is(eq(0)))');
    
        return $this;
    }

    public function hasClassDefinition() {
        $this->addMethod("filter{ g.idx('classes')[['path':it.fullnspath]].any()}");
    
        return $this;
    }

    public function hasNoClassDefinition() {
        $this->addMethod("filter{ g.idx('classes')[['path':it.fullnspath]].any() == false}");
    
        return $this;
    }

    public function interfaceDefinition() {
        $this->addMethod('hasNot("fullnspath", null)
                         .filter{ g.idx("interfaces").get("path", it.fullnspath).any(); }
                         .transform{ g.idx("interfaces")[["path":it.fullnspath]].next(); }');
    
        return $this;
    }

    public function noInterfaceDefinition() {
        $this->addMethod('where(__.in("DEFINITION").count().is(eq(0)))');
//        $this->addMethod("hasNot('fullnspath', null).filter{ g.idx('interfaces')[['path':it.fullnspath]].any() == false }");
    
        return $this;
    }

    public function hasInterfaceDefinition() {
        $this->addMethod("filter{ g.idx('interfaces')[['path':it.fullnspath]].any()}");
    
        return $this;
    }

    public function hasNoInterfaceDefinition() {
        $this->addMethod("filter{ g.idx('interfaces')[['path':it.fullnspath]].any() == false}");
    
        return $this;
    }

    public function traitDefinition() {
        $this->addMethod('hasNot("fullnspath", null)
                         .filter{ g.idx("traits").get("path", it.fullnspath).any(); }
                         .transform{ g.idx("traits")[["path":it.fullnspath]].next(); }');
    
        return $this;
    }

    public function noTraitDefinition() {
        $this->addMethod('where(__.in("DEFINITION").count().is(eq(0)))');
//        $this->addMethod("hasNot('fullnspath', null).filter{ g.idx('traits')[['path':it.fullnspath]].any() == false }");
    
        return $this;
    }
    
    public function groupFilter($characteristic, $percentage) {
        $this->addMethod('sideEffect{'.$characteristic.'}.groupCount(gf){x2}.aggregate().sideEffect{'.$characteristic.'}.filter{gf[x2] < '.$percentage.' * gf.values().sum()}');

        return $this;
    }
    
    public function goToClass() {
        $this->goToInstruction('Class');
        
        return $this;
    }
    
    public function hasNoClass() {
        $this->hasNoInstruction('Class');
        
        return $this;
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
        $this->hasNoInstruction('Interface');
        
        return $this;
    }

    public function goToTrait() {
        $this->goToInstruction('Trait');
        
        return $this;
    }

    public function hasNoTrait() {
        $this->hasNoInstruction('Trait');
        
        return $this;
    }

    public function goToClassTrait() {
        $this->goToInstruction(['Trait', 'Class']);
        
        return $this;
    }

    public function hasNoClassTrait() {
        $this->hasNoInstruction(array('Class', 'Trait'));
        
        return $this;
    }

    public function goToClassInterface() {
        $this->goToInstruction(['Interface', 'Class']);
        
        return $this;
    }

    public function hasNoClassInterface() {
        $this->hasNoInstruction(['Class', 'Interface']);
        
        return $this;
    }

    public function goToClassInterfaceTrait() {
        $this->goToInstruction(['Interface', 'Class', 'Trait']);
        
        return $this;
    }

    public function hasNoClassInterfaceTrait() {
        $this->hasNoInstruction(['Class', 'Interface', 'Trait']);
        
        return $this;
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

    public function goToAllParents() {
//        $this->addMethod('until(__.out("EXTENDS").in("DEFINITION").count().is(eq(0))).repeat( out("EXTENDS").in("DEFINITION") ).emit()');
        $this->addMethod('repeat( out("EXTENDS").in("DEFINITION") ).emit().times(4)');
        
//        $this->addMethod('repeat( out("EXTENDS").in("DEFINITION") ).times(4)');
//        $this->addMethod('sideEffect{ allParents = []; }.until(__.out("EXTENDS").in("DEFINITION").count().is(eq(0)) ).emit().repeat( sideEffect{allParents.push(it.get().id()); }.out("EXTENDS").in("DEFINITION").filter{ !(it.get().id() in allParents); } )');
//        $this->addMethod('sideEffect{ allParents = []; }.until(__.out("EXTENDS").in("DEFINITION").count().is(eq(0)) ).repeat( sideEffect{allParents.push(it.get().id()); }.out("EXTENDS").in("DEFINITION").filter{ !(it.get().id() in allParents); } ).emit()');
        
        return $this;
    }

    public function goToAllChildren() {
        $this->addMethod('out("DEFINITION")');
        
        return $this;
    }

    public function goToTraits() {
        $this->addMethod('out("BLOCK").out("ELEMENT").has("atom", "Use").out("USE").in("DEFINITION")');
        
        return $this;
    }

    public function hasFunction() {
        $this->hasInstruction('Function');
        
        return $this;
    }

    public function hasClassTrait() {
        $this->hasInstruction(['Class', 'Trait']);
        
        return $this;
    }

    public function hasClassInterface() {
        $this->hasInstruction(['Class', 'Interface']);
        
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

    public function hasIfthen() {
        $this->hasInstruction('Ifthen');
        
        return $this;
    }

    public function hasTryCatch() {
        $this->hasInstruction('Try');
        
        return $this;
    }

    public function hasNotTryCatch() {
        $this->hasNoInstruction('Try');
        
        return $this;
    }

    public function isLocalClass() {
        $linksUp = \Tokenizer\Token::linksAsList();

        $this->addMethod(<<<GREMLIN
sideEffect{ inside = it.get().value("fullnspath"); }
.where(  __.repeat( __.in($linksUp) ).until( hasLabel("Class") ).filter{ it.get().value("fullnspath") == inside; }.count().is(eq(1)) )

GREMLIN
);
        
        return $this;
    }
    
    public function isNotLocalClass() {
        $linksUp = \Tokenizer\Token::linksAsList();

        $this->addMethod(<<<GREMLIN
sideEffect{ inside = it.get().value("fullnspath"); }
.where(  __.repeat( __.in($linksUp) ).until( hasLabel("Class") ).filter{ it.get().value("fullnspath") == inside; }.count().is(eq(0)) )

GREMLIN
);
        
        return $this;
    }

    public function goToMethodDefinition() {
        // starting with a staticmethodcall , no support for static, self, parent
        $this->addMethod('sideEffect{methodname = it.out("METHOD").next().code.toLowerCase();}
                .out("CLASS").transform{
                    if (it.code.toLowerCase() == "self") {
                        init = it.in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}.next();
                    } else if (it.code.toLowerCase() == "static") {
                        init = it.in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}.next();
                    } else  if (it.code.toLowerCase() == "parent") {
                        init = it.in.loop(1){it.object.atom != "Class"}{it.object.atom == "Class"}.next().out("EXTENDS")
                                 .filter{ g.idx("classes").get("path", it.fullnspath).any(); }
                                 .transform{ g.idx("classes")[["path":it.fullnspath]].next(); }.next();
                    } else {
                        init = g.idx("classes")[["path":it.fullnspath]].next();
                    };

                    find = null;
                    if (init.out("BLOCK").out("ELEMENT").has("atom", "Function").out("NAME").filter{ it.code.toLowerCase() == methodname }.any()) {
                        found = init.out("BLOCK").out("ELEMENT").has("atom", "Function").filter{ it.out("NAME").next().code.toLowerCase() == methodname }.next();
                    } else if (init.out("EXTENDS").any() == false) {
                        found = it;
                    } else {
                        found = init.out("EXTENDS")
                            .filter{ g.idx("classes").get("path", it.fullnspath).any(); }
                            .transform{ g.idx("classes")[["path":it.fullnspath]].next(); }
                            .loop(2){ it.object.out("BLOCK").out("ELEMENT").has("atom", "Function").out("NAME").filter{ it.code.toLowerCase() == methodname }.any() == false}
                                    { it.object.out("BLOCK").out("ELEMENT").has("atom", "Function").out("NAME").filter{ it.code.toLowerCase() == methodname }.any()}
                        .next();
                        
                        if (found == null) { found = it; } else {
                            found = found.out("BLOCK").out("ELEMENT").has("atom", "Function").filter{ it.out("NAME").next().code.toLowerCase() == methodname }.next();
                        }
                    };
                    found;
                }.has("atom", "Function")');
        
        return $this;
    }
    
    public function goToNamespace() {
        $this->goToInstruction('Namespace');
        
        return $this;
    }

    public function isLiteral() {
        // Closures are literal if not using a variable from the context
        $this->addMethod(<<<GREMLIN
filter{ (it.atom in ["Integer", "Boolean", "Magicconstant", "Real", "String", "Heredoc", "Function"]) ||
        (it.atom == 'Functioncall' && it.constante == true && it.token in ['T_ARRAY', 'T_OPEN_BRACKET'])
}

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
        
        $linksDown = \Tokenizer\Token::linksAsList();
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
    __.in($linksDown)
      .sideEffect{ if (it.get().label() == 'Function') { theFunction = it.get().value('code')} }
      .sideEffect{ if (it.get().label() in ['Class']) { theClass = it.get().value('fullcode')} }
      .sideEffect{ if (it.get().label() in ['Namespace']) { theNamespace = it.get().vertices(OUT, 'NAME').next().value('fullcode')} }
       )
.sideEffect{  file = it.get().value('fullcode');}
.sideEffect{ context = ['line':line, 'file':file, 'fullcode':fullcode, 'function':theFunction, 'class':theClass, 'namespace':theNamespace]; }
.select("context")

GREMLIN

/*

sideEffect{ 
    current = it;
    context = ["Namespace":"Global", "Function":"Global", "Class":"Global"]; 
    it.in.loop(1){true}{it.object.atom in ["Namespace", "Function", "Class"]}
         .each{ 
            if (it.atom == "Namespace") { 
                context[it.atom] = it.out("NAMESPACE").next().fullcode; 
            } else if (context[it.atom] == "Global") { 
                context[it.atom] = it.out("NAME").next().code; 

                // In case of closure, we use the id number to differentiate them 
                if (context[it.atom] == '') {
                    context[it.atom] = it.out("NAME").next().id; 
                    
                    $forClosure
                }
            } 
        }
    } 

*/
);
        
        return $this;
    }
    
    public function followConnexion( $iterations = 3) {
        //it.rank in x[-2] should be better than !x[-2].intersect([it.rank]).isEmpty() but this isn't working!!
        $this->addMethod(
        <<<GREMLIN

// Loop init
sideEffect{ loops = 1;}

//// LOOP ////
.as('connexion')
.transform{
    if (it.in('METHOD').any() == false) {
        if (g.idx('functions')[['path':it.fullnspath]].any()) {
            g.idx('functions')[['path':it.fullnspath]].next();
        } else {
            it;
        }
    } else if (it.in('METHOD').any()) {  // case of Staticmethodcall or Propertycall
                name = it.code.toLowerCase();
                g.idx('atoms')[['atom':'Class']].out('BLOCK').out('ELEMENT')
                                    .has('atom', 'Function').out('NAME').filter{it.code.toLowerCase() == name }.next();
    } else { it; }
}.in('NAME')
// calculating the path AND obtaining the arguments list
.sideEffect{ while(x.last() >= loops) { x.pop(); x.pop();}; y = it.out('ARGUMENTS').out('ARGUMENT').filter{!x[-2].intersect([it.rank]).isEmpty() }.code.toList(); x += [y];  x += loops;}
// find outgoing function
.out('BLOCK').out.loop(1){true}{it.object.atom in ['Functioncall', 'Staticmethodcall', 'Methodcall'] && (it.object.in('METHOD').any() == false)}
.transform{ if (it.out('METHOD').any()) { it.out('METHOD').next(); } else { it; }}

// filter with arguments that are relayed
.filter{ it.out('ARGUMENTS').out('ARGUMENT').filter{ it.code in x[-2]}.any() }
.sideEffect{ y=[]; it.out('ARGUMENTS').out('ARGUMENT').filter{ it.code in x[-2]}.rank.fill(y); x += [y]; x += loops}

.loop('connexion'){ loops = it.loops; it.loops < $iterations; }{true}
//// LOOP ////

GREMLIN
                        );
        
        return $this;
    }
    
    public function makeVariable($variable) {
        $this->addMethod('sideEffect{ '.$variable.' = "\$" + '.$variable.' }');
        
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
            echo $id, ")\n", print_r($query, true), print_r($this->queriesArguments[$id], true), "\n";

            krsort($this->queriesArguments[$id]);
            
            foreach($this->queriesArguments[$id] as $name => $value) {
                if (is_array($value)) {
                    $query = str_replace($name, "['".implode("', '", $value)."']", $query);
                } elseif (is_string($value)) {
                    $query = str_replace($name, "'".str_replace('\\', '\\\\', $value)."'", $query);
                } elseif (is_int($value)) {
                    $query = str_replace($name, $value, $query);
                } else {
                    die( 'Cannot process argument of type '.gettype($value)."\n".__METHOD__."\n");
                }
            }
            
            echo $query, "\n\n";
        }
        die();
    }

    public function prepareQuery() {
        // @doc This is when the object is a placeholder for others.
        if (count($this->methods) <= 1) { return true; }
        
        if (substr($this->methods[1], 0, 9) == 'hasLabel(') {
            $first = array_shift($this->methods);
            $query = implode('.', $this->methods);
            $query = 'g.V().'.$first.'.groupCount("processed").by(count()).'.$query;
            unset($this->methods[1]);
        } elseif (substr($this->methods[1], 0, 39) == 'where( __.in("ANALYZED").has("analyzer"') {
            $first = array_shift($this->methods); // remove first 
            $init = array_shift($this->methods); // remove first 
            preg_match('/"(Analyzer\\\\.*?)"/', $init, $r);
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
        
        $query .= '.groupCount("total").by(count()).addE("ANALYZED").from(g.V('.$this->analyzerId.')).cap("processed", "total")

// Query for '.$this->analyzerQuoted;

    // initializing a new query
        $this->queries[] = $query;
        $this->queriesArguments[] = $this->arguments;

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
                $this->rowCount += $r[0]->total->{1} ?? 0;
            } 
        }

        // reset for the next
        $this->queries = array();
        $this->queriesArguments = array();
        
        // @todo multiple results ?
        // @todo store result in the object until reading.
        return $this->rowCount;
    }

    public function toCount() {
        return count($this->toArray());
    }
    
    public function toArray() {
        $queryTemplate = "g.idx('analyzers')[['analyzer':'{$this->analyzerQuoted}']].out";
        $vertices = $this->query($queryTemplate);

        $report = array();
        if (count($vertices) > 0) {
            foreach($vertices as $v) {
                $report[] = $v->fullcode;
            }
        }
        
        return $report;
    }

    public function getArray() {
        $analyzer = str_replace('\\', '\\\\', $this->analyzer);
        if (substr($analyzer, 0, 5) === 'Analyzer\\Files\\') {
            $query = <<<GREMLIN
g.idx('analyzers')[['analyzer':'$analyzer']].out
.has('notCompatibleWithPhpVersion', null)
.has('notCompatibleWithPhpConfiguration', null)
.as('fullcode').as('line').as('filename').select{it.fullcode}{it.line}{it.fullcode}
GREMLIN;
        } else {
            $query = <<<GREMLIN
g.idx('analyzers')[['analyzer':'$analyzer']].out
.has('notCompatibleWithPhpVersion', null)
.has('notCompatibleWithPhpConfiguration', null)
.as('fullcode').in.loop(1){ it.object.token != 'T_FILENAME'}.as('file').back('fullcode').as('line').select{it.fullcode}{it.line}{it.fullcode}
GREMLIN;
        }
        $vertices = $this->query($query);

        $analyzer = $this->analyzer;
        $report = array();
        if (count($vertices) > 0) {
            foreach($vertices as $v) {
                if (!isset($v->file)) {
                    echo "Error in getArray() : Couldn't find the file\n$query\n",
                         get_class($this),
                         "\n",
                         print_r($v, true);
                    die();
                }
                $report[] = array('code' => $v->fullcode,
                                  'file' => $v->file,
                                  'line' => $v->line,
                                  'desc' => $this->description->getName(),
                                  'clearphp' => $this->description->getClearPHP(),
                                  );
            }
        }
        
        return $report;
    }

    public function toCountedArray($load = 'it.fullcode') {
        $analyzer = str_replace('\\', '\\\\', $this->analyzer);
        $queryTemplate = "m = [:]; g.idx('analyzers')[['analyzer':'".$analyzer."']].out.groupCount(m){".$load.'}.cap';
        $vertices = $this->query($queryTemplate);

        $report = array();
        if (count($vertices) > 0) {
            foreach($vertices[0][0] as $k => $v) {
                $report[$k] = $v;
            }
        }
        
        return $report;
    }
    
    protected function loadIni($file, $index = null) {
        $config = \Config::factory();
        $fullpath = $config->dir_root.'/data/'.$file;
        
        if (!file_exists($fullpath)) {
            return null;
        }

        $iniFile = parse_ini_file($fullpath);
        
        if ($index != null && isset($iniFile[$index])) {
            return $iniFile[$index];
        }
        
        return $iniFile;
    }

    protected function loadJson($file) {
        $config = \Config::factory();
        $fullpath = $config->dir_root.'/data/'.$file;

        if (!file_exists($fullpath)) {
            return null;
        }

        $jsonFile = json_decode(file_get_contents($fullpath));
        
        return $jsonFile;
    }
    
    public static function listAnalyzers() {
        self::initDocs();
        return self::$docs->listAllAnalyzer();
    }

    public function isRun() {
        $analyzer = str_replace('\\', '\\\\', $this->analyzer);
        $queryTemplate = "g.idx('analyzers')[['analyzer':'".$analyzer."']].any()";
        $vertices = $this->query($queryTemplate);

        return $vertices[0][0] == 1;
    }
    
    public function hasResults() {
        return (bool) ($this->getResultsCount() > 0);
    }

    public function getResultsCount() {
        $analyzer = str_replace('\\', '\\\\', $this->analyzer);
        $queryTemplate = <<<GREMLIN
g.idx('analyzers')[['analyzer':'$analyzer']].out
.has('notCompatibleWithPhpVersion', null)
.has('notCompatibleWithPhpConfiguration', null)
.count();

GREMLIN;
        $vertices = $this->query($queryTemplate);
        
        $return = (int) $vertices[0];
        if ($return > 0) {
            return $return;
        }
        
        $queryTemplate = <<<GREMLIN
g.idx('analyzers')[['analyzer':'$analyzer']].out
.transform{ ['versionCompatible':it.notCompatibleWithPhpVersion,
             'configurationCompatible': it.notCompatibleWithPhpConfiguration]};

GREMLIN;
        $vertices = $this->query($queryTemplate);
        
        if (empty($vertices[0])) { // really no results
            return 0;
        } elseif (isset($vertices[0]->versionCompatible)) {
            return self::VERSION_INCOMPATIBLE;
        } elseif (isset($vertices[0]->configurationCompatible)) {
            return self::CONFIGURATION_INCOMPATIBLE;
        } else {
            // Error
            return 0;
        }
    }
    
    public function getSeverity() {
        if (Analyzer::$docs === null) {
            $config = \Config::factory();
            
            Analyzer::$docs = new Docs($config->dir_root.'/data/analyzers.sqlite');
        }
        
        return Analyzer::$docs->getSeverity($this->analyzer);
    }

    public function getFileList() {
        $analyzer = str_replace('\\', '\\\\', $this->analyzer);
        $query = "m=[:]; g.idx('analyzers')[['analyzer':'".$analyzer."']].out('ANALYZED').in.loop(1){true}{it.object.atom == 'File'}.groupCount(m){it.fullcode}.iterate(); m;";
        $vertices = $this->query($query);
        
        $return = array();
        foreach($vertices as $k => $v) {
            $return[$k] = (array) $v;
        }
        
        return $return;
    }

    public function getVendors() {
        if (Analyzer::$docs === null) {
            $config = \Config::factory();
            
            Analyzer::$docs = new Docs($config->dir_root.'/data/analyzers.sqlite');
        }
        
        return Analyzer::$docs->getVendors();
    }

    public function getTimeToFix() {
        if (Analyzer::$docs === null) {
            $config = \Config::factory();
            
            Analyzer::$docs = new Docs($config->dir_root.'/data/analyzers.sqlite');
        }
        
        return Analyzer::$docs->getTimeToFix($this->analyzer);
    }

    public function getPhpversion() {
        return $this->phpVersion;
    }

    public function getphpConfiguration() {
        return $this->phpConfiguration;
    }
    
    public function makeFullNsPath($functions) {
        if (is_string($functions)) {
            $r = strtolower($functions);
            if (isset($r[0]) && $r[0] != "\\") {
                $r = "\\". $r;
            }
        } else {
            $r = array_map(function ($x) {
                $r = strtolower($x);
                if (isset($r[0]) && $r[0] != "\\") {
                    $r = "\\". $r;
                }
                return $r;
            },  $functions);
        }
        return $r;
    }
    
    private function tolowercase(&$code) {
        if (is_array($code)) {
            foreach($code as $k => &$v) {
                $v = strtolower($v);
            }
            unset($v);
        } else {
            $code = strtolower($code);
        }
    }
    
    public static function makeBaseName($className) {
        // A/B to Analyzer\\\\A\\\\B
        return 'Analyzer\\\\'.str_replace('/', '\\\\', $className);
    }

    private function propertyIs($property, $code, $caseSensitive = false) {
        if ($caseSensitive === true) {
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

    private function propertyIsNot($property, $code, $caseSensitive = false) {
            if ($caseSensitive === true) {
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
    
    private function SorA($v) {
        if (is_array($v)) {
            return '"'.implode('", "', $v).'"';
        } else {
            return '"'.$v.'"';
        }
    }

}
?>
