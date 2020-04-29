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

declare(strict_types = 1);

namespace Exakat\Analyzer;

use Exakat\GraphElements;
use Exakat\Exceptions\GremlinException;
use Exakat\Exceptions\NoSuchAnalyzer;
use Exakat\Exceptions\UnknownDsl;
use Exakat\Query\Query;
use Exakat\Project;
use Exakat\Graph\Helpers\GraphResults;

abstract class Analyzer {
    // Query types
    const QUERY_DEFAULT       = 1;   // For compatibility purposes
    const QUERY_ANALYZER      = 2;   // same as above, but explicit
    const QUERY_VALUE         = 3;   // returns a single value
    const QUERY_RAW           = 4;   // returns data, no storage
    const QUERY_HASH          = 5;   // returns a list of values
    const QUERY_MULTIPLE      = 6;   // returns several links at the same time (TBD)
    const QUERY_ARRAYS        = 7;   // arrays of array
    const QUERY_TABLE         = 8;   // to specific table
    const QUERY_MISSING       = 9;   // store values that are not in the graph
    const QUERY_PHP_ARRAYS    = 10;  // store a PHP array of values into hashResults
    const QUERY_PHP_HASH      = 11;  // store a PHP array of values into hashResults
    const QUERY_NO_ANALYZED   = 12;  // store links, but not the ANALYZED one
    const QUERY_RESULTS       = 13;  // store results directly to dump, no ANALYZED
    const QUERY_HASH_ANALYZER = 14;  // store results directly to hashAnalyzer

    protected $datastore  = null;

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

    private static $jsonCache = array();
    private static $iniCache  = array();

    private $analyzer         = '';       // Current class of the analyzer (called from below)
    protected $analyzerTitle    = '';       // Name use when storing in the dump.sqlites
    protected $shortAnalyzer    = '';
    protected $analyzerQuoted   = '';
    protected $analyzerId       = 0;
    protected $queryId          = 0;

    protected $analyzerName      = 'no analyzer name';
    protected $analyzerTable     = 'no analyzer table name';
    private $lastAnalyzerTable = 'none';
    protected $analyzerSQLTable = 'no analyzer sql creation';
    protected $missingQueries   = array();
    protected $analyzedValues   = array();
    protected $storageType      = self::QUERY_DEFAULT;

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

    const WITH_CONSTANTS    = 1;
    const WITHOUT_CONSTANTS = false;

    const WITH_VARIABLES    = 2;
    const WITHOUT_VARIABLES = false;

    const TRANSLATE    = true;
    const NO_TRANSLATE = false;

    public const CONTAINERS       = array('Variable', 'Staticproperty', 'Member', 'Array');
    public const VARIABLES_USER   = array('Variable', 'Variableobject', 'Variablearray');
    public const CONTAINERS_PHP   = array('Variable', 'Staticproperty', 'Member', 'Array', 'Phpvariable');
    public const CONTAINERS_ROOTS = array('Variable', 'Staticproperty', 'Member', 'Array', 'Variableobject', 'Variablearray');
    public const VARIABLES_SCALAR = array('Variable', 'Variableobject', 'Variablearray', 'Globaldefinition', 'Staticdefinition', 'Phpvariable', 'Parametername');
    public const VARIABLES_ALL    = array('Variable', 'Variableobject', 'Variablearray', 'Globaldefinition', 'Staticdefinition', 'Propertydefinition', 'Phpvariable', 'Parametername');

    public const LITERALS         = array('Integer', 'Float', 'Null', 'Boolean', 'String', 'Heredoc');
    public const LOOPS_ALL        = array('For' , 'Foreach', 'While', 'Dowhile');

    public const FUNCTIONS_TOKENS = array('T_STRING', 'T_NS_SEPARATOR', 'T_ARRAY', 'T_EVAL', 'T_ISSET', 'T_EXIT', 'T_UNSET', 'T_ECHO', 'T_OPEN_TAG_WITH_ECHO', 'T_PRINT', 'T_LIST', 'T_EMPTY', 'T_OPEN_BRACKET');
    public const FUNCTIONS_ALL    = array('Function', 'Closure', 'Method', 'Magicmethod', 'Arrowfunction');

    public const FUNCTIONS_NAMED  = array('Function', 'Method', 'Magicmethod');
    public const FUNCTIONS        = array('Function', 'Closure', 'Arrowfunction');
    public const FUNCTIONS_METHOD = array('Method', 'Magicmethod');

    public const CIT              = array('Class', 'Classanonymous', 'Interface', 'Trait');
    public const CLASSES_ALL      = array('Class', 'Classanonymous');
    public const CLASSES_TRAITS   = array('Class', 'Classanonymous', 'Trait');
    public const RELATIVE_CLASS   = array('Parent', 'Static', 'Self');
    public const STATIC_NAMES     = array('Nsname', 'Identifier');
    public const STATICCALL_TOKEN = array('T_STRING', 'T_STATIC', 'T_NS_SEPARATOR');
    public const CLASS_ELEMENTS   = array('METHOD', 'MAGICMETHOD', 'PPP', 'CONST', 'USE');
    public const CLASS_METHODS    = array('METHOD', 'MAGICMETHOD');

    public const FUNCTIONS_CALLS  = array('Functioncall' , 'Newcall', 'Methodcall', 'Staticmethodcall');
    public const CALLS            = array('Functioncall', 'Methodcall', 'Staticmethodcall' );
    public const FUNCTIONS_USAGE  = array('Functioncall', 'Methodcall', 'Staticmethodcall', 'Eval', 'Echo', 'Print', 'Unset' );

    public const STRINGS_ALL      = array('Concatenation', 'Heredoc', 'String', 'Identifier', 'Nsname');
    public const STRINGS_LITERALS  = array('Concatenation', 'Heredoc', 'String');

    public const CONSTANTS_ALL    = array('Identifier', 'Nsname');

    public const EXPRESSION_ATOMS = array('Addition', 'Multiplication', 'Power', 'Ternary', 'Noscream', 'Not', 'Parenthesis', 'Functioncall' );
    public const BREAKS           = array('Goto', 'Return', 'Break', 'Continue');

    const INCLUDE_SELF = false;
    const EXCLUDE_SELF = true;

    const CONTEXT_IN_CLOSURE = 1;
    const CONTEXT_OUTSIDE_CLOSURE = 2;

    const MAX_LOOPING = 15;

    private static $rulesId         = null;

    protected $rulesets  = null;

    protected $methods = null;
    protected $gremlin = null;
    protected $dictCode = null;

    protected $linksDown = '';
    protected $dumpQueries = array();

    public function __construct() {
        assert(func_num_args() === 0, 'Too many arguments for ' . __CLASS__);
        $this->analyzer       = get_class($this);
        $this->analyzerQuoted = $this->getName($this->analyzer);
        $this->shortAnalyzer  = str_replace('\\', '/', substr($this->analyzer, 16));

        $this->config    = exakat('config');
        $this->rulesets  = exakat('rulesets');
        $this->gremlin   = exakat('graphdb');
        $this->datastore = exakat('datastore');
        $this->datastore->reuse();

        $this->dictCode  = exakat('dictionary');
        $docs            = exakat('docs');

        if (strpos($this->analyzer, '\\Common\\') === false) {
            $parameters = $docs->getDocs($this->shortAnalyzer)['parameter'];
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

        $this->linksDown = GraphElements::linksAsList();

        if (empty(self::$availableAtoms) && $this->gremlin !== null) {
            $data = $this->datastore->getCol('TokenCounts', 'token');

            self::$availableAtoms = GraphElements::$ATOMS_VIRTUAL;
            self::$availableLinks = GraphElements::$LINKS_VIRTUAL;

            foreach($data as $token){
                if ($token === strtoupper($token)) {
                    self::$availableLinks[] = $token;
                } else {
                    self::$availableAtoms[] = $token;
                }
            }

            self::$availableFunctioncalls = $this->datastore->getCol('functioncalls', 'functioncall');
        }

        $this->initNewQuery();

        $this->methods = exakat('methods');
    }

    public function init(int $analyzerId = null): int {
        // always reload list of analysis from the database
        $query = <<<'GREMLIN'
g.V().hasLabel("Analysis").as("analyzer", "id").select("analyzer", "id").by("analyzer").by(id);
GREMLIN;
        $res = $this->gremlin->query($query);

        // Double is a safe guard, in case analysis were created twice
        $double = array();
        foreach($res as list('analyzer' => $analyzer, 'id' => $id)) {
            if (isset(self::$rulesId[$analyzer]) && self::$rulesId[$analyzer] !== $id) {
                $double[] = $id;
            } else {
                self::$rulesId[$analyzer] = $id;
            }
        }

        if (!empty($double)) {
            $chunks = array_chunk($double, 200);
            foreach($chunks as $list) {
                $list = makeList($list);
                $query = <<<GREMLIN
g.V({$list}).drop()
GREMLIN;
               $this->gremlin->query($query);
           }
       }

        if ($analyzerId === null) {
            if (isset(self::$rulesId[$this->shortAnalyzer])) {
                // Removing all edges
                $this->analyzerId = self::$rulesId[$this->shortAnalyzer];
                $query = <<<GREMLIN
g.V({$this->analyzerId}).property("count", -2).outE("ANALYZED").drop()
GREMLIN;
                $this->gremlin->query($query);
            } else {
                $resId = $this->gremlin->getId();

                $query = <<<GREMLIN
g.addV().property(T.id, $resId)
        .property(T.label, "Analysis")
        .property("analyzer", "{$this->analyzerQuoted}")
        .property("count", -1)
        .id()
GREMLIN;
                $res = $this->gremlin->query($query);
                $this->analyzerId = $res->toInt();
                self::$rulesId[$this->shortAnalyzer] = $this->analyzerId;
            }
        } else {
            $this->analyzerId = $analyzerId;
        }

        assert($this->analyzerId != 0, self::class . ' was inited with Id 0. Can\'t save with that!');

        return $this->analyzerId;
    }

    public function __destruct() {
        if ($this->path_tmp !== null) {
            unlink($this->path_tmp);
        }
    }

    public function setAnalyzer(string $analyzer): void {
        $this->analyzer = $this->rulesets->getClass($analyzer);
        if ($this->analyzer === false) {
            throw new NoSuchAnalyzer($analyzer, $this->rulesets);
        }
        $this->analyzerQuoted = $this->getName($this->analyzer);
        $this->shortAnalyzer  = str_replace('\\', '/', substr($this->analyzer, 16));
    }

    public function getInBaseName(): string {
        return $this->analyzerQuoted;
    }

    public function getName(string $classname): string {
        return str_replace( array('Exakat\\Analyzer\\', '\\'), array('', '/'), $classname);
    }

    public function getDump(): array {
        $this->atomIs('Analysis')
             ->is('analyzer', array($this->shortAnalyzer))
             ->savePropertyAs('analyzer', 'analyzer')
             ->outIs('ANALYZED')
             ->raw(<<<GREMLIN
 sideEffect{ line = it.get().value("line");
             fullcode = it.get().value("fullcode");
             file="None"; 
             theFunction = ""; 
             theClass=""; 
             theNamespace=""; 
             }
.where( __.until( hasLabel("Project") ).repeat( 
    __.in($this->linksDown)
      .sideEffect{ if (theFunction == "" && it.get().label() in ["Function", "Closure", "Arrayfunction", "Magicmethod", "Method"]) { theFunction = it.get().value("fullcode")} }
      .sideEffect{ if (theClass == ""    && it.get().label() in ["Class", "Trait", "Interface", "Classanonymous"]                ) { theClass = it.get().value("fullcode")   } }
      .sideEffect{ if (it.get().label() == "File") { file = it.get().value("fullcode")} }
       ).fold()
)
.map{ ["fullcode":fullcode, 
       "file":file, 
       "line":line, 
       "namespace":theNamespace, 
       "class":theClass, 
       "function":theFunction,
       "analyzer":analyzer];
}
GREMLIN
);

        return $this->rawQuery()->toArray();
    }

    public function getRulesets(): array {
        $analyzer = $this->getName($this->analyzerQuoted);
        return $this->rulesets->getRulesetForAnalyzer($analyzer);
    }

    public function getPhpVersion(): string {
        return $this->phpVersion;
    }

    public function checkPhpConfiguration($Php): bool {
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

    public function getCalledClasses(): array {
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

    public function getCalledInterfaces(): array {
        if (self::$calledInterfaces === null) {
            self::$calledInterfaces = $this->query('g.V().hasLabel("Analysis").has("analyzer", "Interfaces/InterfaceUsage").out("ANALYZED").values("fullnspath")')
                                           ->toArray();
        }

        return self::$calledInterfaces;
    }

    public function getCalledTraits(): array {
        if (self::$calledTraits === null) {
            $query = <<<'GREMLIN'
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

    public function getCalledNamespaces(): array {
        if (self::$calledNamespaces === null) {
            $query = <<<'GREMLIN'
g.V().hasLabel("Namespace")
     .values("fullnspath")
     .unique()
GREMLIN;
            self::$calledNamespaces = $this->query($query)
                                           ->toArray();
        }

        return self::$calledNamespaces;
    }

    public function getCalledDirectives(): array {
        if (self::$calledDirectives === null) {
            $query = <<<'GREMLIN'
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

    public function checkPhpVersion(string $version): bool {
        // this handles Any version of PHP
        if ($this->phpVersion === self::PHP_VERSION_ANY) {
            return true;
        }

        // version and above
        if (($this->phpVersion[-1] === '+') && version_compare($version, $this->phpVersion) >= 0) {
            return true;
        }

        // up to version
        if (($this->phpVersion[-1] === '-') && version_compare($version, $this->phpVersion) < 0) {
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
    public function dependsOn(): array {
        return array();
    }

    public function query(string $queryString, array $arguments = array()) {
        try {
            $result = $this->gremlin->query($queryString, $arguments);
        } catch (GremlinException $e) {
            display($e->getMessage() . $queryString);
            $result = new \StdClass();
            $result->processed = 0;
            $result->total = 0;
            return array($result);
        }

        return $result;
    }

    public function side(): self {
        $this->query->side();

        return $this;
    }

    public function prepareSide() {
        return $this->query->prepareSide();
    }

    public function as(string $name): self {
        $this->query->_as($name);

        return $this;
    }

    public function _as(string $name): self {
        return $this->as($name);
    }

    public function back(string $name = 'first'): self {
        $this->query->back($name);

        return $this;
    }

    public function ignore(): self {
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

    protected function hasNoCountedInstruction($atom = 'Function', int $count = 0): self {
        $this->query->hasNoCountedInstruction($atom, $count);

        return $this;
    }

    protected function countBy(string $link = 'EXPRESSION',string $property = 'fullcode', string $variable = 'v'): self {
        $this->query->countBy($link, $property, $variable);

        return $this;
    }

    protected function hasInstruction($atom = 'Function'): self {
        $this->query->hasInstruction($atom);

        return $this;
    }

    protected function goToInstruction($atom = 'Namespace'): self {
        $this->query->goToInstruction($atom);

        return $this;
    }

    protected function goToAllElse() {
        $this->query->GoToAllElse();

        return $this;
    }

    protected function goToAllDefinitions() {
        $this->query->GoToAllDefinitions();

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

    public function isNotIgnored() {
        $this->query->isNotIgnored();

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

    public function is($property, $value = true) {
        $this->query->is($property, $value);

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

    public function IsPropertyDefined() {
        $this->query->isPropertyDefined();

        return $this;
    }

    public function IsNotPropertyDefined() {
        $this->query->isNotPropertyDefined();

        return $this;
    }

    public function isNotHash($property, $hash, $index) {
        $this->query->isNotHash($property, $hash, $index);

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
        assert(func_num_args() <= 2, 'Wrong number of arguments for ' . __METHOD__);
        $this->query->noDelimiterIs($code, $caseSensitive);

        return $this;
    }

    public function noDelimiterIsNot($code, $caseSensitive = self::CASE_INSENSITIVE) {
        assert(func_num_args() <= 2, 'Wrong number of arguments for ' . __METHOD__);
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

    public function values($property) {
        $this->query->values($property);

        return $this;
    }

    public function addETo($edgeName, $from) {
        $this->query->addETo($edgeName, $from);

        return $this;
    }

    public function addEFrom($edgeName, $from) {
        $this->query->addEFrom($edgeName, $from);

        return $this;
    }

    public function saveOutAs($name, $out = 'ARGUMENT', $sort = 'rank') {
        $this->query->saveOutAs($name, $out, $sort);

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
        $this->query->propertyIsNot('fullcode', $code, $caseSensitive);

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

    public function not(self $filter, $args = array()) {
        // use func_get_args here
        $filterClean = $filter->prepareSide();
        $this->query->not($filterClean, $args);

        return $this;
    }

    public function filter($filter, array $args = array()) {
        if (is_string($filter)) {
            $filterClean = $this->cleanAnalyzerName($filter);
        } elseif ($filter instanceof self) {
            $filterClean = $filter->prepareSide();
        } else {
            assert(false, 'Wrong type for filter : ' . gettype($filter));
        }
        $this->query->filter($filterClean, $args);

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
        $this->goToInstruction(self::LOOPS_ALL);

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
        return $this->hasNoInstruction(self::CLASSES_ALL);
    }

    public function hasClass() {
        $this->hasInstruction(self::CLASSES_ALL);

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

    public function goToClassTrait($classes = self::CLASSES_TRAITS) {
        $this->goToInstruction($classes);

        return $this;
    }

    public function hasNoClassTrait() {
        // Method are a valid sub-part of class or traits.
        return $this->hasNoInstruction(array('Class', 'Classanonymous', 'Trait', 'Method', 'Magicmethod'));
    }

    public function goToClassInterface() {
        $this->goToInstruction(array('Interface', 'Class', 'Classanonymous'));

        return $this;
    }

    public function hasNoClassInterface() {
        return $this->hasNoInstruction(array('Class', 'Classanonymous', 'Interface'));
    }

    public function goToClassInterfaceTrait() {
        $this->goToInstruction(self::CIT);

        return $this;
    }

    public function hasNoClassInterfaceTrait() {
        return $this->hasNoInstruction(self::CIT);
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
        $this->hasInstruction(self::LOOPS_ALL);

        return $this;
    }

    public function hasNoLoop() {
        $this->hasNoInstruction(self::LOOPS_ALL);

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

    public function collectTraits($variable = 'classes') {
        $this->query->collectTraits($variable);

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

    public function run(): int {
        $this->analyze();

        $this->execQuery();

        return $this->rowCount;
    }

    public function getRowCount(): int {
        return $this->rowCount;
    }

    public function getProcessedCount(): int {
        return $this->processedCount;
    }

    public function getRawQueryCount(): int {
        return $this->rawQueryCount;
    }

    public function getQueryCount(): int {
        return $this->queryCount;
    }

    abstract public function analyze();

    public function printQuery() {
        $this->query->printQuery();
    }

    public function prepareQuery(): void {
        switch($this->storageType) {
            case self::QUERY_MISSING:
                $this->storeMissing();
                break;

            case self::QUERY_NO_ANALYZED:
                $this->storeToGraph(false);
                break;

            case self::QUERY_DEFAULT:
            default:
                $this->storeToGraph(true);
                break;
        }

         // initializing a new query
        $this->initNewQuery();
    }

    public function storeMissing() {
        foreach($this->missingQueries as $m) {
            $query = <<<GREMLIN
g.addV().{$m->toAddV()}
        .addE('ANALYZED')
        .from(g.V({$this->analyzerId}))
GREMLIN;

            $this->gremlin->query($query, array());

            ++$this->processedCount;
            ++$this->rowCount;
        }
    }

    public function storeError(string $error = 'An error happened', int $error_type = self::UNKNOWN_COMPATIBILITY) {
        $query = <<<GREMLIN
g.addV('Noresult').property('code',                              0)
                  .property('fullcode',                          '$error')
                  .property('virtual',                            true)
                  .property('line',                               $error_type)
                  .addE('ANALYZED')
                  .from(g.V($this->analyzerId));
GREMLIN;

        $this->gremlin->query($query);

        $this->datastore->addRow('analyzed', array($this->shortAnalyzer => -1 ) );
    }

    private function storeToGraph(bool $analyzed = true): void {
        if ($this->query->canSkip()) {
            return;
        }
        ++$this->queryId;

        if ($analyzed === true) {
            $analyzed = ".addE(\"ANALYZED\").from(g.V({$this->analyzerId}))";
        } else {
            $analyzed = '.property("complete", "' . $this->shortAnalyzer . '")';
        }

        $this->raw(<<<GREMLIN
dedup().sack{m,v -> ++m["total"]; m;}
        $analyzed
       .sideEffect( g.V({$this->analyzerId}).property("count", -1))
       .count()
       .sack()

// Query (#{$this->queryId}) for {$this->analyzer}
// php {$this->config->php} analyze -p {$this->config->project} -P {$this->analyzer} -v

GREMLIN
);
        $this->query->prepareQuery();
        $this->queries[] = $this->query;
    }

    public function queryDefinition($query) {
        return $this->gremlin->query($query);
    }

    public function rawQuery() {
        $this->query->prepareRawQuery();
        if ($this->query->canSkip()) {
            $result = new GraphResults();
        } else {
            $result = $this->gremlin->query($this->query->getQuery(), $this->query->getArguments());
        }

        $this->initNewQuery();

        return $result;
    }

    public function printRawQuery() {
        $this->query->prepareRawQuery();
        print $this->query->getQuery();

        print_r($this->query->getArguments());

        die(__METHOD__);
    }

    private function initNewQuery(): void {
        $this->query = new Query((count($this->queries) + 1),
                                  new Project('test'),
                                  $this->analyzerQuoted,
                                  $this->config->executable);
    }

    public function execQuery(): int {
        if (empty($this->queries)) {
            $this->gremlin->query("g.V({$this->analyzerId}).property(\"count\", g.V({$this->analyzerId}).out(\"ANALYZED\").count())", array());
            return 0;
        }

        // @todo add a test here ?
        foreach($this->queries as $query) {
            if ($query->canSkip()) {
                continue;
            }

            $r = $this->gremlin->query($query->getQuery(), $query->getArguments());
            ++$this->queryCount;

            $this->processedCount += $r[0]['processed'];
            $this->rowCount       += $r[0]['total'];
        }

        // count the number of results
        $this->gremlin->query("g.V({$this->analyzerId}).property(\"count\", g.V({$this->analyzerId}).out(\"ANALYZED\").count())", array());

        // reset for the next
        $this->queries = array();

        // @todo multiple results ?
        // @todo store result in the object until reading.
        return $this->rowCount;
    }

    protected function loadIni(string $file, string $index = null) {
        $fullpath = "{$this->config->dir_root}/data/$file";

        if (isset(self::$iniCache[$fullpath]->$index)) {
            if ($index === null) {
                return self::$iniCache[$fullpath];
            } else {
                return self::$iniCache[$fullpath]->$index;
            }
        }

        if (file_exists($fullpath)) {
            $ini = (object) parse_ini_file($fullpath, \INI_PROCESS_SECTIONS);
        } elseif (($this->config->ext !== null) && ($iniString = $this->config->ext->loadData("data/$file")) != '') {
            $ini = (object) parse_ini_string($iniString, \INI_PROCESS_SECTIONS);
        } elseif (($this->config->extension_dev !== null) &&
                  file_exists("{$this->config->extension_dev}/data/$file")) {
            $ini = (object) parse_ini_file("{$this->config->extension_dev}/data/$file", \INI_PROCESS_SECTIONS);
        } else {
            assert(false, "No INI for '$file'.");
        }

        if (!isset(self::$iniCache[$fullpath])) {
            self::$iniCache[$fullpath] = $ini;
        }

        if ($index !== null && isset(self::$iniCache[$fullpath]->$index)) {
            return self::$iniCache[$fullpath]->$index;
        }

        return self::$iniCache[$fullpath];
    }

    protected function loadJson($file, $property = null) {
        $fullpath = "{$this->config->dir_root}/data/$file";

        if (!isset(self::$jsonCache[$fullpath])) {
            if (file_exists($fullpath)) {
                $json = json_decode(file_get_contents($fullpath), \JSON_OBJECT);
            } elseif ((!$this->config->ext !== null) && !empty($jsonString = $this->config->ext->loadData("data/$file"))) {
                $json = json_decode($jsonString, \JSON_OBJECT);
            } elseif (($this->config->extension_dev !== null) && !empty($jsonString = $this->config->dev->loadData("data/$file"))) {
                $json = json_decode($jsonString, \JSON_OBJECT);
            } else {
                assert(false, "No JSON for '$file'.");
            }

            self::$jsonCache[$fullpath] = $json;
        }

        if ($property !== null && isset(self::$jsonCache[$fullpath]->$property)) {
            return self::$jsonCache[$fullpath]->$property;
        }

        return self::$jsonCache[$fullpath];
    }

    protected function load(string $file, $property = null) {
        $inifile = "{$this->config->dir_root}/data/$file.ini";
        if (file_exists($inifile)) {
            $ini = $this->loadIni("$file.ini", $property);
        } else {
            $inifile = "{$this->config->dir_root}/data/$file.json";
            if (file_exists($inifile)) {
                $ini = $this->loadJson("$file.json", $property);
            } else {
                $ini = array();
            }
        }

        return $ini;
    }

    public function hasResults(): bool {
        return $this->rowCount > 0;
    }

    public static function makeBaseName($className): string {
        // No Exakat, no Analyzer, using / instead of \
        return $className;
    }

    protected function loadCode(string $path): string {
        if (file_exists($this->config->code_dir . $path)) {
            return (string) file_get_contents($this->config->code_dir . $path);
        } else {
            return '';
        }
    }

    public function __call($name, $args) {
        try {
            $this->query->$name(...$args);
        } catch (UnknownDsl $e) {
            $this->query->StopQuery();
            $rank = $this->queryId + 1;
            display("Found an unknown DSL '$name', in {$this->shortAnalyzer}#{$rank}. Aborting query\n");
            // This needs to be logged!
        }

        return $this;
    }

    public function prepareForDump(array $dumpQueries): void {
        if (empty($dumpQueries)) {
            return;
        }
        $id = dechex(random_int(0, \PHP_INT_MAX));

        file_put_contents($this->config->tmp_dir . '/dump-' . $id . '.php', '<?php $queries = ' . var_export($dumpQueries, true) . '; ?>');
    }
}
?>
