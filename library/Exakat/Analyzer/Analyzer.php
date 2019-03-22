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


namespace Exakat\Analyzer;

use Exakat\Datastore;
use Exakat\Data\Dictionary;
use Exakat\Data\Methods;
use Exakat\Config;
use Exakat\GraphElements;
use Exakat\Exceptions\GremlinException;
use Exakat\Exceptions\NoSuchAnalyzer;
use Exakat\Graph\Helpers\GraphResults;
use Exakat\Reports\Helpers\Docs;
use Exakat\Query\Query;
use Exakat\Tasks\Helpers\Atom;
use Exakat\Query\DSL\DSL;
use Exakat\Query\DSL\IsNotIgnored;

abstract class Analyzer {
    static public $datastore  = null;
    
    protected $rowCount       = 0; // Number of found values
    protected $processedCount = 0; // Number of initial values
    protected $queryCount     = 0; // Number of ran queries
    protected $rawQueryCount  = 0; // Number of ran queries

    private $queries          = array();
    private $query            = null;
    
    public $config         = null;

    public static $availableAtoms         = array();
    public static $availableLinks         = array();
    public static $availableFunctioncalls = array();
    private static $calledClasses         = null;
    private static $calledInterfaces      = null;
    private static $calledTraits          = null;
    private static $calledNamespaces      = null;
    private static $calledDirectives      = null;

    private $analyzer         = '';       // Current class of the analyzer (called from below)
    protected $analyzerQuoted = '';
    protected $analyzerId     = 0;

    protected $phpVersion       = self::PHP_VERSION_ANY;
    protected $phpConfiguration = 'Any';
    
    private $path_tmp           = null;

    const S_CRITICAL = 'Critical';
    const S_MAJOR    = 'Major';
    const S_MINOR    = 'Minor';
    const S_NOTE     = 'Note';
    const S_NONE     = 'None';

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

    const WITH_CONSTANTS    = true;
    const WITHOUT_CONSTANTS = false;

    const TRANSLATE    = true;
    const NO_TRANSLATE = false;

    static public $CONTAINERS       = array('Variable', 'Staticproperty', 'Member', 'Array');
    static public $LITERALS         = array('Integer', 'Real', 'Null', 'Boolean', 'String');
    static public $FUNCTIONS_TOKENS = array('T_STRING', 'T_NS_SEPARATOR', 'T_ARRAY', 'T_EVAL', 'T_ISSET', 'T_EXIT', 'T_UNSET', 'T_ECHO', 'T_OPEN_TAG_WITH_ECHO', 'T_PRINT', 'T_LIST', 'T_EMPTY', 'T_OPEN_BRACKET');
    static public $VARIABLES_ALL    = array('Variable', 'Variableobject', 'Variablearray', 'Globaldefinition', 'Staticdefinition', 'Propertydefinition', 'Phpvariable', 'Parametername');
    static public $VARIABLES_SCALAR = array('Variable', 'Variableobject', 'Variablearray', 'Globaldefinition', 'Staticdefinition', 'Phpvariable', 'Parametername');
    static public $VARIABLES_USER   = array('Variable', 'Variableobject', 'Variablearray',);
    static public $FUNCTIONS_ALL    = array('Function', 'Closure', 'Method', 'Magicmethod');
    static public $FUNCTIONS_NAMED  = array('Function', 'Method', 'Magicmethod');
    static public $FUNCTIONS_METHOD = array('Method', 'Magicmethod');
    static public $CLASSES_ALL      = array('Class', 'Classanonymous');
    static public $STATIC_NAMES     = array('Nsname', 'Identifier');
    static public $CLASSES_NAMED    = 'Class';
    static public $STATICCALL_TOKEN = array('T_STRING', 'T_STATIC', 'T_NS_SEPARATOR');
    static public $LOOPS_ALL        = array('For' ,'Foreach', 'While', 'Dowhile');
    static public $FUNCTIONS_CALLS  = array('Functioncall' ,'Newcall', 'Methodcall', 'Staticmethodcall');
    static public $RELATIVE_CLASS   = array('Parent', 'Static', 'Self');
    static public $CLASS_ELEMENTS   = array('METHOD', 'MAGICMETHOD', 'PPP', 'CONST', 'USE');
    static public $CIT              = array('Class', 'Classanonymous', 'Interface', 'Trait');
    static public $EXPRESSION_ATOMS = array('Addition', 'Multiplication', 'Power', 'Ternary', 'Noscream', 'Not', 'Parenthesis', 'Functioncall' );
    static public $CALLS            = array('Functioncall', 'Methodcall', 'Staticmethodcall' );
    
    const INCLUDE_SELF = false;
    const EXCLUDE_SELF = true;

    const CONTEXT_IN_CLOSURE = 1;
    const CONTEXT_OUTSIDE_CLOSURE = 2;
    
    const MAX_LOOPING = 15;
    
    protected $themes  = null;
    protected static $methods = null;
    protected $gremlin = null;
    protected $dictCode = null;
    
    protected $linksDown = '';
    

    public function __construct($gremlin = null, $config = null) {
        $this->gremlin = $gremlin;
        
        $this->analyzer       = get_class($this);
        $this->analyzerQuoted = $this->getName($this->analyzer);
        $this->shortAnalyzer  = str_replace('\\', '/', substr($this->analyzer, 16));

        assert($config !== null, 'Can\'t call Analyzer without a config');
        $this->themes = new Themes("{$config->dir_root}/data/analyzers.sqlite",
                                   $config->ext,
                                   $config->themas);
        $this->config = $config;

        if (strpos($this->analyzer, '\\Common\\') === false) {
            $description = new Docs($config->dir_root, $config->ext);
            $parameters = $description->getDocs($this->shortAnalyzer)['parameter'];
            foreach($parameters as $parameter) {
                assert(isset($this->{$parameter['name']}), "Missing definition for library/Exakat/Analyzer/$this->analyzerQuoted.php :\nprotected \$$parameter[name] = '$parameter[default]';\n");
 
                if (isset($this->config->{$this->analyzerQuoted}[$parameter['name']])) {
                    $this->{$parameter['name']} = $this->config->{$this->analyzerQuoted}[$parameter['name']];
                } else {
                    $this->{$parameter['name']} = $parameter['default'];
                }
                
                if ($parameter['type'] === 'integer') {
                    $this->{$parameter['name']} = (int) $this->{$parameter['name']};
                }
            }
        }
        
        if (!isset(self::$datastore)) {
            self::$datastore = new Datastore($this->config);
        }
        
        $this->dictCode = Dictionary::factory(self::$datastore);
        
        $this->linksDown = GraphElements::linksAsList();

        if (empty(self::$availableAtoms) && $this->gremlin !== null) {
            $data = self::$datastore->getCol('TokenCounts', 'token');
            
            self::$availableAtoms = array('Project', 'File', 'Virtualproperty');
            self::$availableLinks = array('DEFINITION', 'ANALYZED', 'PROJECT', 'FILE', 'OVERWRITE');

            foreach($data as $token){
                if ($token === strtoupper($token)) {
                    self::$availableLinks[] = $token;
                } else {
                    self::$availableAtoms[] = $token;
                }
            }

            self::$availableFunctioncalls = self::$datastore->getCol('functioncalls', 'functioncall');
        }
        
        $this->query = new Query((count($this->queries) + 1), $this->config->project, $this->analyzerQuoted, $this->config->executable, self::$datastore);
        
        self::$methods = new Methods($this->config);
    }
    
    public function __destruct() {
        if ($this->path_tmp !== null) {
            unlink($this->path_tmp);
        }
    }
    
    public function setAnalyzer($analyzer) {
        $this->analyzer = $this->themes->getClass($analyzer);
        if ($this->analyzer === false) {
            throw new NoSuchAnalyzer($analyzer, $this->themes);
        }
        $this->analyzerQuoted = $this->getName($this->analyzer);
    }
    
    public function getInBaseName() {
        return $this->analyzerQuoted;
    }
    
    public function getName($classname) {
        return str_replace( array('Exakat\\Analyzer\\', '\\'), array('', '/'), $classname);
    }
    
    
    public function getDump() {
        $query = <<<GREMLIN
g.V().hasLabel("Analysis").has("analyzer", "{$this->analyzerQuoted}").out("ANALYZED")
.sideEffect{ line = it.get().value('line');
             fullcode = it.get().value('fullcode');
             file='None'; 
             theFunction = ''; 
             theClass=''; 
             theNamespace=''; 
             }
.where( __.until( hasLabel('Project') ).repeat( 
    __.in($this->linksDown)
      .sideEffect{ if (it.get().label() in ['Function', 'Closure', 'Magicmethod', 'Method']) { theFunction = it.get().value('code')} }
      .sideEffect{ if (it.get().label() in ['Class', 'Trait', 'Interface', 'Classanonymous']) { theClass = it.get().value('fullcode')} }
      .sideEffect{ if (it.get().label() == 'File') { file = it.get().value('fullcode')} }
       )
)
.map{ ['fullcode':fullcode, 
       'file':file, 
       'line':line, 
       'namespace':theNamespace, 
       'class':theClass, 
       'function':theFunction ];}

GREMLIN;
        return $this->gremlin->query($query)
                             ->toArray();
    }

    public function getThemes() {
        $analyzer = $this->getName($this->analyzerQuoted);
        return $this->themes->getThemeForAnalyzer($analyzer);
    }

    public function init($analyzerId = null) {
        if ($analyzerId === null) {
            $query = <<<GREMLIN
g.V().hasLabel("Analysis").has("analyzer", "$this->analyzerQuoted").id();
GREMLIN;
            $res = $this->gremlin->query($query);
            
            if ($res->isType(GraphResults::EMPTY)) {
                // Creating analysis vertex
                $resId = $this->gremlin->getId();
                
                $query = 'g.addV().property(T.id, '.$resId.').property(T.label, "Analysis").property("analyzer", "'.$this->analyzerQuoted.'").property("atom", "Analysis").id()';
                $res = $this->gremlin->query($query);
                $this->analyzerId = $res->toString();
            } else {
                $this->analyzerId = $res->toString();
                if ($this->analyzerId == 0) {
                    // Creating analysis vertex
                    $resId = $this->gremlin->getId();

                    $query = 'g.addV().property(T.id, '.$resId.').property(T.label, "Analysis").property("analyzer", "'.$this->analyzerQuoted.'").property("atom", "Analysis").id()';
                    $res = $this->gremlin->query($query);
                    $this->analyzerId = $res->toString();
                } else {
                    // Removing all edges
                    $query = <<<GREMLIN
g.V().hasLabel("Analysis").has("analyzer", "$this->analyzerQuoted").outE("ANALYZED").drop()
GREMLIN;
                    $res = $this->gremlin->query($query);
                }
            }
        } else {
            $this->analyzerId = $analyzerId;
        }

        assert($this->analyzerId != 0, self::class.' was inited with Id 0. Can\'t save with that!');
        return $this->analyzerId;
    }

    public function getPhpVersion() {
        return $this->phpVersion;
    }
    
    public function checkPhpConfiguration($Php) {
        // this handles Any version of PHP
        if ($this->phpConfiguration === 'Any') {
            return true;
        }
        
        foreach($this->phpConfiguration as $ini => $value) {
            if ($Php->getConfiguration($ini) != $value) {
                return false;
            }
        }
        
        return true;
    }
    
    public function getCalledClasses() {
        if (self::$calledClasses === null) {
            $news = $this->query('g.V().hasLabel("New").out("NEW").not(where( __.in("DEFINITION"))).values("fullnspath")')
                         ->toArray();
            $staticcalls = $this->query('g.V().hasLabel("Staticconstant", "Staticmethodcall", "Staticproperty", "Instanceof", "Catch").out("CLASS").not(where( __.in("DEFINITION"))).values("fullnspath")')
                                ->toArray();
            $typehints   = $this->query('g.V().hasLabel("Method", "Magicmethod", "Closure", "Function").out("ARGUMENT").out("TYPEHINT").not(where( __.in("DEFINITION"))).values("fullnspath")')
                                ->toArray();
            $returntype  = $this->query('g.V().hasLabel("Method", "Magicmethod", "Closure", "Function").out("RETURNTYPE").not(where( __.in("DEFINITION"))).values("fullnspath")')
                                ->toArray();
            self::$calledClasses = array_unique(array_merge($staticcalls,
                                                            $news,
                                                            $typehints,
                                                            $returntype));
        }
        
        return self::$calledClasses;
    }
    
    public function getCalledInterfaces() {
        if (self::$calledInterfaces === null) {
            self::$calledInterfaces = $this->query('g.V().hasLabel("Analysis").has("analyzer", "Interfaces/InterfaceUsage").out("ANALYZED").values("fullnspath")')
                                           ->toArray();
        }
        
        return self::$calledInterfaces;
    }

    public function getCalledTraits() {
        if (self::$calledTraits === null) {
            $query = <<<GREMLIN
g.V().hasLabel("Analyzer")
     .has("analyzer", "Traits/TraitUsage")
     .out("ANALYZED")
     .values("fullnspath")
GREMLIN;
            self::$calledTraits = $this->query($query)
                                       ->toArray();
        }
        
        return self::$calledTraits;
    }

    public function getCalledNamespaces() {
        if (self::$calledNamespaces === null) {
            $query = <<<GREMLIN
g.V().hasLabel("Namespace")
     .values("fullnspath")
     .unique()
GREMLIN;
            self::$calledNamespaces = $this->query($query)
                                           ->toArray();
        }
        
        return self::$calledNamespaces;
    }

    public function getCalledDirectives() {
        if (self::$calledDirectives === null) {
            $query = <<<GREMLIN
g.V().hasLabel("Analysis")
     .has("analyzer", "Php/DirectivesUsage")
     .out("ANALYZED")
     .out("ARGUMENT")
     .has("rank", 0)
     .hasLabel("String")
     .has("noDelimiter")
     .values("noDelimiter")
     .unique()
GREMLIN;
            self::$calledDirectives = $this->query($query)
                                           ->toArray();
        }
        
        return self::$calledDirectives;
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
            display($e->getMessage().$queryString);
            $result = new \StdClass();
            $result->processed = 0;
            $result->total = 0;
            return array($result);
        }

        return $result;
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
        
        $return = array();
        foreach($result as $row) {
            $return[$row['key']] = $row['value'];
        }
        return $return;
    }

    public function side() {
        $this->query->side();
        
        return $this;
    }

    public function prepareSide() {
        return $this->query->prepareSide();
    }

    public function _as($name) {
        $this->query->_as($name);
        
        return $this;
    }

    public function back($name = 'first') {
        $this->query->back($name);
        
        return $this;
    }
    
    public function ignore() {
        $this->query->ignore();
        
        return $this;
    }

////////////////////////////////////////////////////////////////////////////////
// Common methods
////////////////////////////////////////////////////////////////////////////////

    protected function hasNoInstruction($atom = 'Function') {
        $this->query->hasNoInstruction($atom);
        
        return $this;
    }

    protected function hasNoCountedInstruction($atom = 'Function', $count = 0) {
        $this->query->hasNoCountedInstruction($atom, $count);
        
        return $this;
    }

    protected function countBy($link = 'EXPRESSION', $property = 'fullcode', $variable = 'v') {
        $this->query->countBy($link, $property, $variable);
        
        return $this;
    }

    protected function hasInstruction($atom = 'Function') {
        $this->query->hasInstruction($atom);

        return $this;
    }

    protected function goToInstruction($atom = 'Namespace') {
        $this->query->goToInstruction($atom);
        
        return $this;
    }

    protected function goToAllElse() {
        $this->query->GoToAllElse();
        
        return $this;
    }

    public function tokenIs($token) {
        $this->query->tokenIs($token);
        
        return $this;
    }

    public function tokenIsNot($token) {
        $this->query->tokenIsNot($token);
        
        return $this;
    }

    public function isNotEmptyArray() {
        $this->query->isNotEmptyArray();
        
        return $this;
    }
    
    public function atomIs($atom, $flag = self::WITHOUT_CONSTANTS) {
        $this->query->atomIs($atom, $flag);

        return $this;
    }

    public function atomIsNot($atom, $flag = self::WITHOUT_CONSTANTS) {
        $this->query->atomIsNot($atom, $flag);
        
        return $this;
    }

    public function atomFunctionIs($fullnspath) {
        $this->query->atomFunctionIs($fullnspath);
        
        return $this;
    }

    public function isNotIgnored($type = IsNotIgnored::IGNORED_CLASSES) {
        $this->query->isNotIgnored($type);
        
        return $this;
    }
    
    public function functioncallIs($fullnspath) {
        $this->query->functioncallIs($fullnspath);

        return $this;
    }

    public function functioncallIsNot($fullnspath) {
        $this->query->functioncallIsNot($fullnspath);

        return $this;
    }

    public function hasAtomInside($atom) {
        $this->query->HasAtomInside($atom);
        
        return $this;
    }
    
    public function analyzerInsideMoreThan($analyzer, $atom, $count = 1) {
        $this->query->AnalyzerInsideMoreThan($analyzer, $atom, $count);
        
        return $this;
    }

    public function hasPropertyInside($property, $values) {
        $this->query->hasPropertyInside($property, $values);

        return $this;
    }
    
    public function atomInside($atom) {
        $this->query->atomInside($atom);
        
        return $this;
    }
    
    public function atomInsideExpression($atom) {
        $this->query->atomInsideExpression($atom);
        
        return $this;
    }

    public function fullcodeInside($fullcode) {
        $this->query->fullcodeInside($fullcode);

        return $this;
    }

    public function noFullcodeInside($fullcode) {
        $this->query->noFullcodeInside($fullcode);

        return $this;
    }

    public function functionInside($fullnspath) {
        $this->query->functionInside($fullnspath);
        
        return $this;
    }

    public function noFunctionInside($fullnspath) {
        $this->query->noFunctionInside($fullnspath);

        return $this;
    }

    public function atomInsideNoBlock($atom) {
        $this->query->atomInsideNoBlock($atom);
        
        return $this;
    }

    public function atomInsideNoAnonymous($atom) {
        $this->query->atomInsideNoAnonymous($atom);
        
        return $this;
    }

    public function atomInsideNoDefinition($atom) {
        $this->query->atomInsideNoDefinition($atom);
        
        return $this;
    }

    public function atomInsideWithCall($atom) {
        $this->query->atomInsideWithCall($atom);
        
        return $this;
    }
    
    public function atomInsideMoreThan($atom, $times = 1) {
        $this->query->atomInsideMoreThan($atom, $times);
        
        return $this;
    }
    
    public function noAtomInside($atom) {
        $this->query->noAtomInside($atom);
        
        return $this;
    }

    public function noCodeInside($atom, $values) {
        $this->query->noCodeInside($atom, $values);
        
        return $this;
    }

    public function noPropertyInside($atom, $property, $values) {
        $this->query->noPropertyInside($atom, $property, $values);
        
        return $this;
    }
    
    public function noAtomWithoutPropertyInside($atom, $property, $values) {
        $this->query->NoAtomWithoutPropertyInside($atom, $property, $values);
        
        return $this;
    }
    
    public function noAnalyzerInside($atoms, $analyzer) {
        $this->query->NoAnalyzerInside($atoms, $analyzer);
        
        return $this;
    }

    public function noAnalyzerInsideWithProperty($atoms, $analyzer, $property, $value) {
        $this->query->NoAnalyzerInsideWithProperty($atoms, $analyzer, $property, $value);
        
        return $this;
    }

    public function noAtomPropertyInside($atom, $property, $values) {
        $this->query->noAtomPropertyInside($atom, $property, $values);
        
        return $this;
    }

    public function trim($variable, $chars = '\'\"') {
        $this->query->trim($variable, $chars);
        
        return $this;
    }

    public function analyzerIs($analyzer) {
        $analyzer = makeArray($analyzer);

        if (($id = array_search('self', $analyzer)) !== false) {
            $analyzer[$id] = $this->analyzerQuoted;
        }
        $analyzer = array_map('self::getName', $analyzer);

        $this->query->analyzerIs($analyzer);

        return $this;
    }

    public function analyzerIsNot($analyzer) {
        $analyzer = makeArray($analyzer);

        if (($id = array_search('self', $analyzer)) !== false) {
            $analyzer[$id] = $this->analyzerQuoted;
        }
        $analyzer = array_map('self::getName', $analyzer);

        $this->query->analyzerIsNot($analyzer);

        return $this;
    }

    public function has($property) {
        $this->query->has($property);
        
        return $this;
    }

    public function hasNo($property) {
        $this->query->hasNo($property);
        
        return $this;
    }
    
    public function is($property, $value = true) {
        $this->query->is($property, $value);
        
        return $this;
    }

    public function isHash($property, $hash, $index) {
        $this->query->isHash($property, $hash, $index);

        return $this;
    }
    
    public function isGlobalCode() {
        $this->query->IsGlobalCode();

        return $this;
    }
    
    public function isReassigned($name) {
        $this->query->IsReassigned($name);

        return $this;
    }

    public function isUsed($times = 1) {
        $this->query->isUsed($times);

        return $this;
    }

    public function isComplexExpression($threshold = 30) {
        $this->query->isComplexExpression($threshold);

        return $this;
    }

    public function isNotHash($property, $hash, $index) {
        $this->query->isNotHash($property, $hash, $index);
        
        return $this;
    }

    public function isNot($property, $value = true) {
        $this->query->isNot($property, $value);
        
        return $this;
    }

    public function isNotInheritedMethod() {
        $this->query->IsNotInheritedMethod();
        
        return $this;
    }

    public function isNotEmptyBody() {
        $this->query->IsNotEmptyBody();
        
        return $this;
    }
    
    public function isNotExtendingComposer() {
        $this->query->IsNotExtendingComposer();
        
        return $this;
    }

    public function isArgument() {
        $this->query->isArgument();
        
        return $this;
    }

    public function isNotArgument() {
        $this->query->isNotArgument();
        
        return $this;
    }

    public function isMore($property, $value = 0) {
        $this->query->isMore($property, $value);

        return $this;
    }

    public function isLess($property, $value = 0) {
        $this->query->isLess($property, $value);

        return $this;
    }

    public function isEqual($value = 0) {
        $this->query->isEqual((int) $value);

        return $this;
    }

    public function count() {
        $this->query->count();

        return $this;
    }

    public function outWithRank($link = 'ARGUMENT', $rank = 0) {
        $this->query->outWithRank($link, $rank);

        return $this;
    }

    public function outWithoutLastRank() {
        $this->query->outWithoutLastRank();

        return $this;
    }

    public function hasChildWithRank($edgeName, $rank = 0) {
        $this->query->hasChildWithRank($edgeName, $rank);

        return $this;
    }

    public function noChildWithRank($edgeName, $rank = 0) {
        $this->query->noChildWithRank($edgeName, $rank);

        return $this;
    }

    public function codeIs($code, $translate = self::TRANSLATE, $caseSensitive = self::CASE_INSENSITIVE) {
        $this->query->codeIs($code, $translate, $caseSensitive);
        
        return $this;
    }

    public function codeIsNot($code, $translate = self::TRANSLATE, $caseSensitive = self::CASE_INSENSITIVE) {
        $this->query->codeIsNot($code, $translate, $caseSensitive);

        return $this;
    }

    public function noDelimiterIs($code, $caseSensitive = self::CASE_INSENSITIVE) {
        assert(func_num_args() <= 2, 'Wrong number of arguments for '.__METHOD__);
        $this->query->noDelimiterIs($code, $caseSensitive);
        
        return $this;
    }

    public function noDelimiterIsNot($code, $caseSensitive = self::CASE_INSENSITIVE) {
        assert(func_num_args() <= 2, 'Wrong number of arguments for '.__METHOD__);
        $this->query->noDelimiterIsNot($code, $caseSensitive);
        
        return $this;
    }

    public function fullnspathIs($code, $caseSensitive = self::CASE_INSENSITIVE) {
        $this->query->fullnspathIs($code, $caseSensitive);

        return $this;
    }

    public function fullnspathIsNot($code, $caseSensitive = self::CASE_INSENSITIVE) {
        $this->query->fullnspathIsNot($code, $caseSensitive);

        return $this;
    }
    
    public function codeIsPositiveInteger() {
        $this->query->codeIsPositiveInteger();

        return $this;
    }

    public function samePropertyAs($property, $name, $caseSensitive = self::CASE_INSENSITIVE) {
        $this->query->samePropertyAs($property, $name, $caseSensitive);

        return $this;
    }

    public function samePropertyAsArray($property, $name, $caseSensitive = self::CASE_INSENSITIVE) {
        $this->query->samePropertyAsArray($property, $name, $caseSensitive);

        return $this;
    }

    public function notSamePropertyAs($property, $name, $caseSensitive = self::CASE_INSENSITIVE) {
        $this->query->notSamePropertyAs($property, $name, $caseSensitive);

        return $this;
    }

    public function followExpression() {
        $this->query->FollowExpression();
        
        return $this;
    }
    
    public function followCalls() {
        $this->query->FollowCalls();
        
        return $this;
    }

    public function values($property) {
        $this->query->values($property);
        
        return $this;
    }

    public function unique() {
        $this->query->unique();
        
        return $this;
    }

    public function saveOutAs($name, $out = 'ARGUMENT', $sort = 'rank') {
        $this->query->saveOutAs($name, $out, $sort);

        return $this;
    }

    public function savePropertyAs($property, $name) {
        $this->query->savePropertyAs($property, $name);

        return $this;
    }
    
    public function InitVariable($name, $value = '[]') {
        $this->query->initVariable($name, $value);

        return $this;
    }

    public function dedup($by = '') {
        $this->query->dedup($by);

        return $this;
    }

    public function saveMethodNameAs($name) {
        $this->query->saveMethodNameAs($name);
        
        return $this;
    }

    public function fullcodeIs($code, $caseSensitive = self::CASE_INSENSITIVE) {
        $this->query->propertyIs('fullcode', $code, $caseSensitive);
        
        return $this;
    }

    public function fullcodeVariableIs($variable) {
        $this->query->fullcodeVariableIs($variable);
        
        return $this;
    }

    public function variableIsAssigned($times) {
        $this->query->variableIsAssigned($times);
        
        return $this;
    }
    
    public function fullcodeIsNot($code, $caseSensitive = self::CASE_INSENSITIVE) {
        $this->propertyIsNot('fullcode', $code, $caseSensitive);
        
        return $this;
    }

    public function isUppercase($property = 'fullcode') {
        $this->query->isUppercase($property);

        return $this;
    }

    public function isLowercase($property = 'fullcode') {
        $this->query->isLowercase($property);

        return $this;
    }

    public function isNotUppercase($property = 'fullcode') {
        $this->query->isNotUppercase($property);

        return $this;
    }

    public function isNotLowercase($property = 'fullcode') {
        $this->query->isNotLowercase($property);

        return $this;
    }
    
    public function isNotMixedcase($property = 'fullcode') {
        $this->query->IsNotMixedcase($property);

        return $this;
    }

    private function cleanAnalyzerName($gremlin) {
        $dependencies = $this->dependsOn();
        $fullNames = array_map(array($this, 'makeBaseName'), $dependencies);
        
        return str_replace($dependencies, $fullNames, $gremlin);
    }

    public function not($filter) {
        // use func_get_args here
        if ($filter instanceof self) {
            $filterClean = $filter->prepareSide();
        } else {
            assert(false, "Wrong type for not : ".get_type($filter));
        }
        $this->query->not($filterClean, array());

        return $this;
    }

    public function filter($filter) {
        // use func_get_args here
        if (is_string($filter)) {
            $filterClean = $this->cleanAnalyzerName($filter);
        } elseif ($filter instanceof self) {
            $filterClean = $filter->prepareSide();
        } else {
            assert(false, "Wrong type for filter : ".get_type($filter));
        }
        $this->query->filter($filterClean, array());

        return $this;
    }

    public function codeLength($length = ' == 1 ') {
        $values = $this->dictCode->length($length);
        $this->query->codeLength($values);

        return $this;
    }

    public function fullcodeLength($length = ' == 1 ') {
        // @todo add some tests ? Like Operator / value ?
        $this->query->fullcodeLength($length);

        return $this;
    }
    
    public function groupCount($column) {
        $this->query->groupCount($column);
        
        return $this;
    }

    public function regexIs($column, $regex) {
        $this->query->regexIs($column, $regex);

        return $this;
    }

    public function regexIsNot($column, $regex) {
        $this->query->regexIsNot($column, $regex);
        
        return $this;
    }

    protected function outIs($link = array()) {
        $this->query->outIs($link);
        
        return $this;
    }

    // follows a link if it is there (and do nothing otherwise)
    protected function outIsIE($link = array()) {
        $this->query->outIsIE($link);

        return $this;
    }

    public function outIsNot($link) {
        $this->query->outIsNot($link);
        
        return $this;
    }

    public function hasNextSibling($link = 'EXPRESSION') {
        $this->query->hasNextSibling($link);

        return $this;
    }

    public function hasNoNextSibling($link = 'EXPRESSION') {
        $this->query->hasNoNextSibling($link);

        return $this;
    }

    public function nextSibling($link = 'EXPRESSION') {
        $this->query->nextSibling($link);

        return $this;
    }

    public function nextSiblings($link = 'EXPRESSION') {
        $this->query->nextSiblings($link);

        return $this;
    }

    public function previousSibling($link = 'EXPRESSION') {
        $this->query->previousSibling($link);

        return $this;
    }

    public function previousSiblings($link = 'EXPRESSION') {
        $this->query->previousSiblings($link);

        return $this;
    }

    public function otherSiblings($link = 'EXPRESSION', $self = self::EXCLUDE_SELF) {
        $this->query->otherSiblings($link, $self);

        return $this;
    }

    public function inIs($link = array()) {
        $this->query->inIs($link);

        return $this;
    }

    // follows a link if it is there (and do nothing otherwise)
    protected function inIsIE($link = array()) {
        $this->query->inIsIE($link);

        return $this;
    }

    public function inIsNot($link) {
        $this->query->inIsNot($link);

        return $this;
    }

    public function raw($query, ...$args) {
        ++$this->rawQueryCount;
        
        $this->query->raw($query, $this->dependsOn(), $args);
        
        return $this;
    }

    public function hasIn($link) {
        $this->query->hasIn($link);

        return $this;
    }
    
    public function hasNoIn($link) {
        $this->query->hasNoIn($link);
        
        return $this;
    }

    public function hasOut($link) {
        $this->query->hasOut($link);

        return $this;
    }
    
    public function hasNoOut($link) {
        $this->query->hasNoOut($link);

        return $this;
    }

    public function hasNoDefinition() {
        $this->query->hasNoDefinition();

        return $this;
    }

    public function isInCatchBlock() {
        $this->query->isInCatchBlock();
        
        return $this;
    }

    public function hasNoCatchBlock() {
        return $this->hasNoInstruction('Catch');
    }

    public function hasParent($parentClass, $ins = array()) {
        $this->query->hasParent($parentClass, $ins);

        return $this;
    }

    public function hasNoParent($parentClass, $ins = array()) {
        $this->query->hasNoParent($parentClass, $ins);

        return $this;
    }

    public function hasChildren($childrenClass, $outs = array()) {
        $this->query->hasChildren($childrenClass, $outs);

        return $this;
    }

    public function hasNoChildren($childrenClass, $outs = array()) {
        $this->query->hasNoChildren($childrenClass, $outs);
        
        return $this;
    }

    public function hasConstantDefinition() {
        $this->query->hasConstantDefinition();
    
        return $this;
    }

    public function hasNoConstantDefinition() {
        $this->query->hasNoConstantDefinition();
    
        return $this;
    }

    protected function hasFunctionDefinition() {
        $this->query->hasFunctionDefinition();
    
        return $this;
    }

    protected function hasNoFunctionDefinition() {
        $this->query->hasNoFunctionDefinition();

        return $this;
    }

    protected function functionDefinition() {
        $this->query->FunctionDefinition();
    
        return $this;
    }

    protected function goToArray() {
        $this->query->goToArray();
        
        return $this;
    }

    protected function goToExpression() {
        $this->query->goToExpression();
        
        return $this;
    }
    
    protected function goToFirstExpression() {
        $this->query->goToFirstExpression();
        
        return $this;
    }
    
    protected function goToCurrentScope() {
        $this->query->goToCurrentScope();
        
        return $this;
    }

    protected function goToFunction($type = array('Function', 'Closure', 'Method', 'Magicmethod')) {
        $this->query->goToFunction($type);
        
        return $this;
    }

    protected function hasNoFunction($type = array('Function', 'Closure', 'Method', 'Magicmethod')) {
        $this->query->hasNoFunction($type);
        
        return $this;
    }

    protected function goToFile() {
        $this->goToInstruction('File');
        
        return $this;
    }
    
    protected function goToLoop() {
        $this->goToInstruction(self::$LOOPS_ALL);
        
        return $this;
    }

    protected function classDefinition() {
        $this->query->classDefinition();
    
        return $this;
    }

    protected function noClassDefinition($type = 'Class') {
        $this->query->noClassDefinition($type);
    
        return $this;
    }

    protected function hasClassDefinition($type = 'Class') {
        $this->query->hasClassDefinition($type);
    
        return $this;
    }

    public function noUseDefinition() {
        $this->query->noUseDefinition();
    
        return $this;
    }

    public function interfaceDefinition() {
        $this->query->interfaceDefinition();
    
        return $this;
    }

    public function noInterfaceDefinition() {
        $this->query->noInterfaceDefinition();
    
        return $this;
    }

    public function hasInterfaceDefinition() {
        $this->query->hasInterfaceDefinition();
    
        return $this;
    }

    public function hasTraitDefinition() {
        $this->query->hasTraitDefinition();

        return $this;
    }

    public function noTraitDefinition() {
        $this->query->noTraitDefinition();
    
        return $this;
    }
    
    public function groupFilter($characteristic, $percentage) {
        $this->query->groupFilter($characteristic, $percentage);

        return $this;
    }
    
    public function goToClass() {
        $this->query->goToClass();
        
        return $this;
    }
    
    public function hasNoClass() {
        return $this->hasNoInstruction(self::$CLASSES_ALL);
    }

    public function hasClass() {
        $this->hasInstruction(self::$CLASSES_ALL);
        
        return $this;
    }

    public function goToInterface() {
        $this->query->goToInstruction('Interface');
        
        return $this;
    }

    public function hasNoInterface() {
        return $this->hasNoInstruction('Interface');
    }

    public function goToTrait() {
        $this->query->goToTrait();
        
        return $this;
    }

    public function hasNoTrait() {
        return $this->hasNoInstruction('Trait');
    }

    public function goToClassTrait($classes = array('Trait', 'Class', 'Classanonymous')) {
        $this->goToInstruction($classes);
        
        return $this;
    }

    public function hasNoClassTrait() {
        // Method are a valid sub-part of class or traits.
        return $this->hasNoInstruction(array('Class', 'Classanonymous', 'Trait', 'Method'));
    }

    public function goToClassInterface() {
        $this->goToInstruction(array('Interface', 'Class', 'Classanonymous'));
        
        return $this;
    }

    public function hasNoClassInterface() {
        return $this->hasNoInstruction(array('Class', 'Classanonymous', 'Interface'));
    }

    public function goToClassInterfaceTrait() {
        $this->goToInstruction(self::$CIT);
        
        return $this;
    }

    public function hasNoClassInterfaceTrait() {
        return $this->hasNoInstruction(self::$CIT);
    }
    
    public function goToExtends() {
        $this->query->goToExtends();
        
        return $this;
    }

    public function goToImplements() {
        $this->query->goToExtends();

        return $this;
    }

    public function goToParent() {
        $this->query->goToParent();
        
        return $this;
    }

    public function goToAllParents($self = self::EXCLUDE_SELF) {
        $this->query->goToAllParents($self);

        return $this;
    }

    public function goToAllParentsTraits($self = self::EXCLUDE_SELF) {
        $this->query->goToAllParentsTraits($self);

        return $this;
    }

    public function goToAllChildren($self = self::INCLUDE_SELF) {
        $this->query->goToAllChildren($self);

        return $this;
    }
    
    public function goToAllTraits($self = self::INCLUDE_SELF) {
        $this->query->goToAllTraits($self);
        
        return $this;
    }

    public function goToAllImplements($self = self::INCLUDE_SELF) {
        $this->query->goToAllImplements($self);
        
        return $this;
    }

    public function goToTraits($self = self::INCLUDE_SELF) {
        $this->query->goToTraits($self);
        
        return $this;
    }

    public function hasFunction() {
        $this->query->HasFunction();
        
        return $this;
    }

    public function hasClassTrait() {
        $this->query->hasClassTrait();
        
        return $this;
    }

    public function hasClassInterface() {
        $this->query->hasClassInterface();
        
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
        $this->hasInstruction(self::$LOOPS_ALL);
        
        return $this;
    }
    
    public function hasNoLoop() {
        $this->hasNoInstruction(self::$LOOPS_ALL);

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
        $this->query->hasNoComparison();
        
        return $this;
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
        $this->query->isLocalClass();
        
        return $this;
    }
    
    public function isNotLocalClass() {
        $this->query->isNotLocalClass();
        
        return $this;
    }

    public function goToNamespace() {
        $this->query->goToNamespace();
        
        return $this;
    }

    public function isLiteral() {
        $this->query->isLiteral();

        return $this;
    }
    
    public function isNotLiteral() {
        $this->query->isNotLiteral();

        return $this;
    }
    
    public function implementing($fullnspath) {
        $this->query->implementing(array($fullnspath));

        return $this;
    }

    public function notImplementing($fullnspath) {
        $this->query->notImplementing(array($fullnspath));

        return $this;
    }
    
    public function extending($fullnspath) {
        $this->query->extending(array($fullnspath));

        return $this;
    }

    public function notExtending($fullnspath) {
        $this->query->notExtending(array($fullnspath));

        return $this;
    }

    public function hasNoVariadicArgument() {
        $this->query->hasNoVariadicArgument();

        return $this;
    }

    public function hasVariadicArgument() {
        $this->query->hasVariadicArgument();

        return $this;
    }
    
    public function getNameInFNP($variable) {
        $this->query->getNameInFNP($variable);
        
        return $this;
    }

    public function makeVariableName($variable) {
        $this->query->makeVariableName($variable);
        
        return $this;
    }
    
    public function goToLiteralValue() {
        $this->query->goToLiteralValue();
        
        return $this;
    }
    
    public function collectContainers($containers = 'containers') {
        $this->query->collectContainers($containers);
        
        return $this;
    }

    public function collectVariables($variables = 'variables', $type = 'fullcode') {
        $this->query->collectVariables($variables, $type);
        
        return $this;
    }
    
    public function collectImplements($variable = 'interfaces') {
        $this->query->collectImplements($variable);
        
        return $this;
    }

    public function collectExtends($variable = 'classes') {
        $this->query->collectExtends($variable);
        
        return $this;
    }
    
    // Calculate The lenght of a string in a property, and report it in the named string
    public function getStringLength($property = 'noDelimiter', $variable = 'l') {
        $this->query->getStringLength($property, $variable);

        return $this;
    }
    
    public function isReferencedArgument($variable = 'variable') {
        $this->query->isReferencedArgument($variable);

        return $this;
    }

    public function hasNoUsage() {
        $this->query->hasNoUsage();

        return $this;
    }

    public function variableIsRead($times = 1) {
        $this->query->VariableIsRead($times);

        return $this;
    }

    public function run() {
        $this->analyze();

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
        $this->query->printQuery();
    }
    
    public function prepareQuery() {
        $this->query->prepareQuery($this->analyzerId);

        $this->queries[] = $this->query;
        $this->query = null;

         // initializing a new query
        $this->initNewQuery();
    }

    public function queryDefinition($query) {
        return $this->gremlin->query($query);
    }

    public function rawQuery() {
        $this->query->prepareRawQuery();
        $result = $this->gremlin->query($this->query->getQuery(), $this->query->getArguments());

        $this->initNewQuery();
        
        return $result;
    }

    public function printRawQuery() {
        $this->query->prepareRawQuery();
        print $this->query->getQuery();
        
        print_r($this->query->getArguments());

        die(__METHOD__);
    }
    
    private function initNewQuery() {
        $this->query = new Query((count($this->queries) + 1), $this->config->project, $this->analyzerQuoted, $this->config->executable, self::$datastore);
    }
    
    public function execQuery() {
        if (empty($this->queries)) {
            return true;
        }

        // @todo add a test here ?
        foreach($this->queries as $query) {
            $r = $this->gremlin->query($query->getQuery(), $query->getArguments());
            ++$this->queryCount;
            
            $this->processedCount += $r['processed'];
            $this->rowCount       += $r['total'];
        }

        // reset for the next
        $this->queries = array();
        
        // @todo multiple results ?
        // @todo store result in the object until reading.
        return $this->rowCount;
    }

    protected function loadIni($file, $index = null) {
        $fullpath = "{$this->config->dir_root}/data/$file";

        if (file_exists($fullpath)) {
            $ini = parse_ini_file($fullpath, INI_PROCESS_SECTIONS);
        } elseif ((!is_null($this->config->ext)) && ($iniString = $this->config->ext->loadData("data/$file")) !== null) {
            $ini = parse_ini_string($iniString, INI_PROCESS_SECTIONS);
        } else {
            assert(false, "No INI for '$file'.");
        }
        
        static $cache;

        if (!isset($cache[$fullpath])) {
            foreach($ini as &$values) {
                if (isset($values[0]) && empty($values[0])) {
                    $values = '';
                }
            }
            unset($values);
            $cache[$fullpath] = $ini;
        }
        
        if ($index !== null && isset($cache[$fullpath][$index])) {
            return $cache[$fullpath][$index];
        }
        
        return $cache[$fullpath];
    }

    protected function loadJson($file, $property = null) {
        $fullpath = "{$this->config->dir_root}/data/$file";

        if (file_exists($fullpath)) {
            $json = json_decode(file_get_contents($fullpath));
        } elseif ((!is_null($this->config->ext)) && ($jsonString = $this->config->ext->loadData("data/$file")) !== null) {
            $json = json_decode($jsonString);
        } else {
            assert(false, "No JSON for '$file'.");
        }

        static $cache;
        if (!isset($cache[$fullpath])) {
            $cache[$fullpath] = $json;
        }
        
        if ($property !== null && isset($cache[$fullpath]->$property)) {
            return $cache[$fullpath]->$property;
        }
        
        return $cache[$fullpath];
    }
    
    public function hasResults() {
        return $this->rowCount > 0;
    }

    public static function makeBaseName($className) {
        // No Exakat, no Analyzer, using / instead of \
        return $className;
    }

}
?>
