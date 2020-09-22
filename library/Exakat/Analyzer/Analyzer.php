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
use Exakat\Query\QueryDoc;
use Exakat\Project;
use Exakat\Graph\Helpers\GraphResults;
use Exakat\Query\DSL\Command;
use Exakat\Phpexec;

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
    private $queryDoc         = null;

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

    private $analyzer           = '';       // Current class of the analyzer (called from below)
    protected $analyzerTitle    = '';       // Name use when storing in the dump.sqlites
    protected $shortAnalyzer    = '';
    protected $analyzerQuoted   = '';
    protected $analyzerId       = 0;
    protected $queryId          = 0;

    protected $analyzerName      = 'no analyzer name';
    protected $analyzerTable     = 'no analyzer table name';
    protected $analyzerSQLTable  = 'no analyzer sql creation';
    protected $missingQueries    = array();
    protected $analyzerValues    = array();
    protected $storageType       = self::QUERY_DEFAULT;

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

    const P_VERY_HIGH = 'very-high';
    const P_HIGH      = 'high';
    const P_MEDIUM    = 'medium';
    const P_LOW       = 'Low';
    const P_NONE      = 'Unknown';

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
    public const ARGUMENTS        = array('Ppp', 'Parameter');

    public const LITERALS         = array('Integer', 'Float', 'Null', 'Boolean', 'String', 'Heredoc');
    public const LOOPS_ALL        = array('For' , 'Foreach', 'While', 'Dowhile');
    public const SWITCH_ALL       = array('Switch' , 'Match');

    public const FUNCTIONS_TOKENS = array('T_STRING', 'T_NS_SEPARATOR', 'T_ARRAY', 'T_EVAL', 'T_ISSET', 'T_EXIT', 'T_UNSET', 'T_ECHO', 'T_OPEN_TAG_WITH_ECHO', 'T_PRINT', 'T_LIST', 'T_EMPTY', 'T_OPEN_BRACKET', 'T_NAME_FULLY_QUALIFIED', 'T_NAME_RELATIVE', 'T_NAME_QUALIFIED');
    public const FUNCTIONS_ALL    = array('Function', 'Closure', 'Method', 'Magicmethod', 'Arrowfunction');

    public const FUNCTIONS_NAMED  = array('Function', 'Method', 'Magicmethod');
    public const FUNCTIONS        = array('Function', 'Closure', 'Arrowfunction');
    public const FUNCTIONS_METHOD = array('Method', 'Magicmethod');

    public const CIT              = array('Class', 'Classanonymous', 'Interface', 'Trait');
    public const CLASSES_ALL      = array('Class', 'Classanonymous');
    public const CLASSES_TRAITS   = array('Class', 'Classanonymous', 'Trait');
    public const RELATIVE_CLASS   = array('Parent', 'Static', 'Self');
    public const STATIC_NAMES     = array('Nsname', 'Identifier');
    public const STATICCALL_TOKEN = array('T_STRING', 'T_STATIC', 'T_NS_SEPARATOR', 'T_NAME_FULLY_QUALIFIED', 'T_NAME_RELATIVE', 'T_NAME_QUALIFIED');
    public const CLASS_ELEMENTS   = array('METHOD', 'MAGICMETHOD', 'PPP', 'CONST', 'USE');
    public const CLASS_METHODS    = array('METHOD', 'MAGICMETHOD');

    public const FUNCTIONS_CALLS  = array('Functioncall' , 'Newcall', 'Methodcall', 'Staticmethodcall');
    public const CALLS            = array('Functioncall', 'Methodcall', 'Staticmethodcall' );
    public const FUNCTIONS_USAGE  = array('Functioncall', 'Methodcall', 'Staticmethodcall', 'Eval', 'Echo', 'Print', 'Unset' );

    public const STRINGS_ALL      = array('Concatenation', 'Heredoc', 'String', 'Identifier', 'Nsname', 'Staticclass', 'Magicconstant');
    public const STRINGS_LITERALS  = array('Concatenation', 'Heredoc', 'String', 'Magicconstant');

    public const CONSTANTS_ALL    = array('Identifier', 'Nsname');

    public const EXPRESSION_ATOMS = array('Addition', 'Multiplication', 'Power', 'Ternary', 'Not', 'Parenthesis', 'Functioncall' );
    public const TYPE_ATOMS       = array('Integer', 'String', 'Arrayliteral', 'Float', 'Boolean', 'Null', 'Closure', 'Concatenation', 'Magicconstant', 'Heredoc', 'Power' , 'Staticclass', 'Comparison', 'Not', 'Addition', 'Multiplication', 'Bitshift', 'Bitoperation', 'Logical');
    public const BREAKS           = array('Goto', 'Return', 'Break', 'Continue');

    const INCLUDE_SELF = false;
    const EXCLUDE_SELF = true;

    const CONTEXT_IN_CLOSURE = 1;
    const CONTEXT_OUTSIDE_CLOSURE = 2;

    const MAX_LOOPING   = 15;    // hard limit for do...while when navigating the tree
    const MAX_SEARCHING = 8;     // hard limit for searching the tree (failing the rest is not bad)
    const TIME_LIMIT    = 1000;  // 1s, used with timelimit() from gremlin.

    private static $rulesId         = array();

    protected $rulesets  = null;

    protected $methods = null;
    protected $gremlin = null;
    protected $dictCode = null;

    protected $linksDown = '';

    public function __construct() {
        assert(func_num_args() === 0, 'Too many arguments for ' . static::class);
        $this->analyzer       = get_class($this);
        $this->analyzerQuoted = self::getName($this->analyzer);
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
                assert(isset($this->{$parameter['name']}), "Missing definition for library/Exakat/Analyzer/$this->analyzerQuoted.php :\nprotected \$$parameter[name] = '" . ($parameter['default'] ?? '') . "';\n");

                if (isset($this->config->{$this->analyzerQuoted}[$parameter['name']])) {
                    $this->{$parameter['name']} = $this->config->{$this->analyzerQuoted}[$parameter['name']];

                    if (!isset($parameter['default'])) {
                        continue;
                    }
                } elseif (isset($parameter['default'])) {
                    $this->{$parameter['name']} = $parameter['default'];
                } else {
                    // Else, we reuse the default values in the code
                    continue;
                }

                switch($parameter['type']) {
                    case 'integer':
                        $this->{$parameter['name']} = (int) $this->{$parameter['name']};
                        break;

                    case 'data':
                        if (is_string($this->{$parameter['name']})) {
                            $dataFile = $this->{$parameter['name']};
                            if (substr($dataFile, -4) === 'json') {
                                $this->{$parameter['name']} = $this->loadJson($dataFile);
                            } elseif (substr($dataFile, -3) === 'ini') {
                                $this->{$parameter['name']} = $this->loadIni($dataFile);
                            }
                        }
                        break;

                    case 'ini_hash':
                        $this->{$parameter['name']} = parse_ini_string($this->{$parameter['name']})[$parameter['name']] ?? array();
                        break;

                    case 'json':
                        $this->{$parameter['name']} = json_decode($this->{$parameter['name']});
                        break;

                    default :
                        // Nothing, really
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
        $this->methods = exakat('methods');

        $this->initNewQuery();
    }

    public function init(int $analyzerId = null) {
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

        assert(!empty($this->analyzerId), self::class . ' was inited with Id ' . var_export($this->analyzerId, true) . '. Can\'t save with that!');

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
        $this->analyzerQuoted = self::getName($this->analyzer);
        $this->shortAnalyzer  = str_replace('\\', '/', substr($this->analyzer, 16));
    }

    public function getInBaseName(): string {
        return $this->analyzerQuoted;
    }

    public static function getName(string $classname): string {
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
        $analyzer = self::getName($this->analyzerQuoted);
        return $this->rulesets->getRulesetForAnalyzer($analyzer);
    }

    public function getPhpVersion(): string {
        return $this->phpVersion;
    }

    public function checkPhpConfiguration(Phpexec $php): bool {
        // this handles Any version of PHP
        if ($this->phpConfiguration === 'Any') {
            return true;
        }

        foreach($this->phpConfiguration as $ini => $value) {
            if ($php->getConfiguration($ini) != $value) {
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

    public function prepareSide(): Command {
        return $this->query->prepareSide();
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
                                  $this->config->executable,
                                  $this->dependsOn()
                                  );
/*
        if ($this->queryDoc !== null) {
            $this->queryDoc->display();
        }*/
        $this->queryDoc = new QueryDoc();
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
            $ini = array();
        }

        if (!isset(self::$iniCache[$fullpath])) {
            self::$iniCache[$fullpath] = $ini;
        }

        if ($index !== null && isset(self::$iniCache[$fullpath]->$index)) {
            return self::$iniCache[$fullpath]->$index;
        }

        return self::$iniCache[$fullpath];
    }

    protected function loadJson(string $file, string $property = null) {
        $fullpath = "{$this->config->dir_root}/data/$file";

        if (!isset(self::$jsonCache[$fullpath])) {
            if (file_exists($fullpath)) {
                $json = json_decode(file_get_contents($fullpath), \JSON_OBJECT);
            } elseif (($this->config->ext !== null) && !empty($jsonString = $this->config->ext->loadData("data/$file"))) {
                $json = json_decode($jsonString, \JSON_OBJECT);
            } elseif (($this->config->extension_dev !== null) && !empty($jsonString = $this->config->dev->loadData("data/$file"))) {
                $json = json_decode($jsonString, \JSON_OBJECT);
            } else {
                assert(false, "No JSON for '$file'.");
                $json = (object) array();
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
//        $this->queryDoc->$name(...$args);
        if ($this->query->canSkip()) {
            return $this;
        }

        $name = $name === 'as' ? '_as' : $name;

        try {
            $this->query->$name(...$args);
//            $this->queryDoc->$name(...$args);
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
        $export = '<?php $queries = ' . var_export($dumpQueries, true) . '; ?>';
        $id = crc32($export);

        file_put_contents($this->config->tmp_dir . '/dump-' . $id . '.php', $export);
    }
}
?>
