<?php declare(strict_types = 1);
/*
 * Copyright 2012-2019 Damien Seguy Ð Exakat SAS <contact(at)exakat.io>
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

use Exakat\GraphElements;
use Exakat\Graph\Graph;
use Exakat\Project;
use Exakat\Exceptions\InvalidPHPBinary;
use Exakat\Exceptions\LoadError;
use Exakat\Exceptions\MustBeAFile;
use Exakat\Exceptions\MustBeADir;
use Exakat\Exceptions\NoFileToProcess;
use Exakat\Exceptions\NoSuchLoader;
use Exakat\Exceptions\UnknownCase;
use Exakat\Tasks\LoadFinal\LoadFinal;
use Exakat\Tasks\Helpers\Fullnspaths;
use Exakat\Tasks\Helpers\AtomInterface;
use Exakat\Tasks\Helpers\AtomGroup;
use Exakat\Tasks\Helpers\Calls;
use Exakat\Tasks\Helpers\Context;
use Exakat\Tasks\Helpers\Intval;
use Exakat\Tasks\Helpers\Strval;
use Exakat\Tasks\Helpers\Boolval;
use Exakat\Tasks\Helpers\Nullval;
use Exakat\Tasks\Helpers\Constant;
use Exakat\Tasks\Helpers\Precedence;
use Exakat\Tasks\Helpers\IsPhp;
use Exakat\Tasks\Helpers\IsStub;
use Exakat\Tasks\Helpers\IsExt;
use Exakat\Tasks\Helpers\IsRead;
use Exakat\Tasks\Helpers\IsModified;
use Exakat\Tasks\Helpers\Php;
use Exakat\Tasks\Helpers\Sequences;
use Exakat\Tasks\Helpers\NestedCollector;
use ProgressBar\Manager as ProgressBar;
use Exakat\Loader\Collector;

class Load extends Tasks {
    const CONCURENCE = self::NONE;

    private $SCALAR_TYPE = array('int',
                                 'bool',
                                 'void',
                                 'float',
                                 'string',
                                 'array',
                                 'callable',
                                 'iterable',
                                 'object',
                                 'false',
                                 'null',
                                 );
    private $PHP_SUPERGLOBALS = array('$GLOBALS',
                                      '$_SERVER',
                                      '$_GET',
                                      '$_POST',
                                      '$_FILES',
                                      '$_REQUEST',
                                      '$_SESSION',
                                      '$_ENV',
                                      '$_COOKIE',
                                      '$php_errormsg',
                                      '$HTTP_RAW_POST_DATA',
                                      '$http_response_header',
                                      '$argc',
                                      '$argv',
                                      '$HTTP_POST_VARS',
                                      '$HTTP_GET_VARS',
                                      );

    private $assignations = array();

    private $php    = null;
    private $loader = null;
    private $loaderList = array('SplitGraphson',
                                'Collector',
                                'None',
                                );

    private $precedence   = null;
    private $phptokens    = null;

    private $atomGroup = null;
    private $calls = null;
    private $theGlobals = array();

    private $namespace = '\\';
    private $uses       = null;
    private $filename   = null;

    private $links   = array();
    private $relicat = array();
    private $minId   = \PHP_INT_MAX;

    private $logTimeFile   = null;

    private $sequences     = null;

    private $currentMethod           = array();
    private $currentFunction         = array();
    private $currentVariables        = array();
    private $currentReturn           = null;
    private $currentClassTrait       = array();
    private $currentProperties       = array();
    private $currentPropertiesCalls  = array();
    private $currentMethods          = array();
    private $currentMethodsCalls     = array();
    private $cases                   = null; // NestedCollector

    private $tokens = array();
    private $id     = 0;
    private $id0    = null;

    private $phpDocs    = array();
    private $attributes = array();

//    private $sqliteLocation = '/tmp/load.sqlite';
// for debug purpose
    private $sqliteLocation = ':memory:';

    const ALTERNATIVE_SYNTAX = true;
    const NORMAL_SYNTAX      = false;

    const FULLCODE_SEQUENCE = ' /**/ ';
    const FULLCODE_BLOCK    = ' { /**/ } ';
    const FULLCODE_VOID     = ' ';

    const ALIASED           = 1;
    const NOT_ALIASED       = '';

    const NO_LINE           = -1;

    const VARIADIC          = true;
    const NOT_VARIADIC      = false;

    const FLEXIBLE          = true;
    const NOT_FLEXIBLE      = false;

    const REFERENCE         = true;
    const NOT_REFERENCE     = false;

    const BRACKET          = true;
    const NOT_BRACKET      = false;

    const ENCLOSING        = true;
    const NO_ENCLOSING     = false;

    const ALTERNATIVE      = true;
    const NOT_ALTERNATIVE  = false;

    const TRAILING         = true;
    const NOT_TRAILING     = false;

    const NULLABLE         = true;
    const NOT_NULLABLE     = false;

    const ELLIPSIS         = true;
    const NOT_ELLIPSIS     = false;

    const CLOSING_TAG      = true;
    const NO_CLOSING_TAG   = false;

    const NO_VALUE          = -1;
    const NOT_BINARY        = ''; // other values b, B (binary)

    const ABSOLUTE     = true;
    const NOT_ABSOLUTE = false;

    const WITH_FULLNSPATH      = true;
    const WITHOUT_FULLNSPATH   = false;

    const CONSTANT_EXPRESSION       = true;
    const NOT_CONSTANT_EXPRESSION   = false;

    const FULLNSPATH_UNDEFINED = 'undefined';

    const WITHOUT_TYPEHINT_SUPPORT = false;
    const WITH_TYPEHINT_SUPPORT    = true;

    const STANDALONE_BLOCK         = true;
    const RELATED_BLOCK            = false;

    const NO_NAMESPACE = '';

    const CASE_SENSITIVE         = true;
    const CASE_INSENSITIVE       = false;

    const COMPILE_CHECK    = true;
    const COMPILE_NO_CHECK = false;

    const PROMOTED     = true;
    const PROMOTED_NOT = false;

    private $contexts              = null;

    private $expressions         = array();
    private $atoms               = array();
    private $argumentsId         = array();
    private $sequence            = null;
    private $callsDatabase       = null;

    private $processing = array();

    private $plugins = array();

    private $stats = array('loc'       => 0,
                           'totalLoc'  => 0,
                           'files'     => 0,
                           'tokens'    => 0,
                          );

    public function __construct(bool $subtask = self::IS_NOT_SUBTASK) {
        parent::__construct($subtask);

        $this->atomGroup = new AtomGroup();

        $this->contexts  = new Context();

        $this->php = exakat('php');
        if (!$this->php->isValid()) {
            throw new InvalidPHPBinary($this->php->getConfiguration('phpversion'));
        }
        $tokens = $this->php->getTokens();
        $this->phptokens  = Php::getInstance($tokens);

        $this->assignations = array($this->phptokens::T_EQUAL,
                                    $this->phptokens::T_PLUS_EQUAL,
                                    $this->phptokens::T_AND_EQUAL,
                                    $this->phptokens::T_CONCAT_EQUAL,
                                    $this->phptokens::T_DIV_EQUAL,
                                    $this->phptokens::T_MINUS_EQUAL,
                                    $this->phptokens::T_MOD_EQUAL,
                                    $this->phptokens::T_MUL_EQUAL,
                                    $this->phptokens::T_OR_EQUAL,
                                    $this->phptokens::T_POW_EQUAL,
                                    $this->phptokens::T_SL_EQUAL,
                                    $this->phptokens::T_SR_EQUAL,
                                    $this->phptokens::T_XOR_EQUAL,
                                    $this->phptokens::T_COALESCE_EQUAL,
                                   );

        // Init all plugins here
        $this->plugins[] = new Boolval();
        $this->plugins[] = new Intval();
        $this->plugins[] = new Strval();
        $this->plugins[] = new Nullval();
        $this->plugins[] = new Constant();
        $this->plugins[] = new IsRead();
        $this->plugins[] = new IsModified();
        $this->plugins[] = new IsPhp();
        $this->plugins[] = new IsExt();
        $this->plugins[] = new IsStub();

        $this->sequences = new Sequences();

        $this->precedence = new Precedence(get_class($this->phptokens));

        $this->processing = array(
            $this->phptokens::T_OPEN_TAG                 => 'processOpenTag',
            $this->phptokens::T_OPEN_TAG_WITH_ECHO       => 'processOpenTag',

            $this->phptokens::T_DOLLAR                   => 'processDollar',
            $this->phptokens::T_VARIABLE                 => 'processVariable',
            $this->phptokens::T_LNUMBER                  => 'processInteger',
            $this->phptokens::T_DNUMBER                  => 'processFloat',

            $this->phptokens::T_OPEN_PARENTHESIS         => 'processParenthesis',

            $this->phptokens::T_PLUS                     => 'processAddition',
            $this->phptokens::T_MINUS                    => 'processAddition',
            $this->phptokens::T_STAR                     => 'processMultiplication',
            $this->phptokens::T_SLASH                    => 'processMultiplication',
            $this->phptokens::T_PERCENTAGE               => 'processMultiplication',
            $this->phptokens::T_POW                      => 'processPower',
            $this->phptokens::T_INSTANCEOF               => 'processInstanceof',
            $this->phptokens::T_SL                       => 'processBitshift',
            $this->phptokens::T_SR                       => 'processBitshift',

            $this->phptokens::T_DOUBLE_COLON             => 'processDoubleColon',
            $this->phptokens::T_OBJECT_OPERATOR          => 'processObjectOperator',
            $this->phptokens::T_NULLSAFE_OBJECT_OPERATOR => 'processObjectOperator',
            $this->phptokens::T_NEW                      => 'processNew',

            $this->phptokens::T_DOT                      => 'processDot',
            $this->phptokens::T_OPEN_CURLY               => 'processBlock',

            $this->phptokens::T_IS_SMALLER_OR_EQUAL      => 'processComparison',
            $this->phptokens::T_IS_GREATER_OR_EQUAL      => 'processComparison',
            $this->phptokens::T_GREATER                  => 'processComparison',
            $this->phptokens::T_SMALLER                  => 'processComparison',

            $this->phptokens::T_IS_EQUAL                 => 'processComparison',
            $this->phptokens::T_IS_NOT_EQUAL             => 'processComparison',
            $this->phptokens::T_IS_IDENTICAL             => 'processComparison',
            $this->phptokens::T_IS_NOT_IDENTICAL         => 'processComparison',
            $this->phptokens::T_SPACESHIP                => 'processComparison',

            $this->phptokens::T_OPEN_BRACKET             => 'processArrayLiteral',
            $this->phptokens::T_ARRAY                    => 'processArrayLiteral',
            $this->phptokens::T_UNSET                    => 'processIsset',
            $this->phptokens::T_ISSET                    => 'processIsset',
            $this->phptokens::T_EMPTY                    => 'processIsset',
            $this->phptokens::T_LIST                     => 'processString', // Can't move to processEcho, because of omissions
            $this->phptokens::T_EVAL                     => 'processIsset',
            $this->phptokens::T_ECHO                     => 'processEcho',
            $this->phptokens::T_EXIT                     => 'processExit',
            $this->phptokens::T_DOUBLE_ARROW             => 'processKeyvalue',

            $this->phptokens::T_HALT_COMPILER            => 'processHalt',
            $this->phptokens::T_PRINT                    => 'processPrint',
            $this->phptokens::T_INCLUDE                  => 'processPrint',
            $this->phptokens::T_INCLUDE_ONCE             => 'processPrint',
            $this->phptokens::T_REQUIRE                  => 'processPrint',
            $this->phptokens::T_REQUIRE_ONCE             => 'processPrint',
            $this->phptokens::T_RETURN                   => 'processReturn',
            $this->phptokens::T_THROW                    => 'processThrow',
            $this->phptokens::T_YIELD                    => 'processYield',
            $this->phptokens::T_YIELD_FROM               => 'processYieldfrom',

            $this->phptokens::T_EQUAL                    => 'processAssignation',
            $this->phptokens::T_PLUS_EQUAL               => 'processAssignation',
            $this->phptokens::T_AND_EQUAL                => 'processAssignation',
            $this->phptokens::T_CONCAT_EQUAL             => 'processAssignation',
            $this->phptokens::T_DIV_EQUAL                => 'processAssignation',
            $this->phptokens::T_MINUS_EQUAL              => 'processAssignation',
            $this->phptokens::T_MOD_EQUAL                => 'processAssignation',
            $this->phptokens::T_MUL_EQUAL                => 'processAssignation',
            $this->phptokens::T_OR_EQUAL                 => 'processAssignation',
            $this->phptokens::T_POW_EQUAL                => 'processAssignation',
            $this->phptokens::T_SL_EQUAL                 => 'processAssignation',
            $this->phptokens::T_SR_EQUAL                 => 'processAssignation',
            $this->phptokens::T_XOR_EQUAL                => 'processAssignation',
            $this->phptokens::T_COALESCE_EQUAL           => 'processAssignation',

            $this->phptokens::T_CONTINUE                 => 'processBreak',
            $this->phptokens::T_BREAK                    => 'processBreak',

            $this->phptokens::T_LOGICAL_AND              => 'processLogical',
            $this->phptokens::T_LOGICAL_XOR              => 'processLogical',
            $this->phptokens::T_LOGICAL_OR               => 'processLogical',
            $this->phptokens::T_XOR                      => 'processBitoperation',
            $this->phptokens::T_OR                       => 'processBitoperation',
            $this->phptokens::T_AND                      => 'processAnd',

            $this->phptokens::T_BOOLEAN_AND              => 'processLogical',
            $this->phptokens::T_BOOLEAN_OR               => 'processLogical',

            $this->phptokens::T_QUESTION                 => 'processTernary',
            $this->phptokens::T_NS_SEPARATOR             => 'processNsname',
            $this->phptokens::T_COALESCE                 => 'processCoalesce',

            $this->phptokens::T_INLINE_HTML              => 'processInlinehtml',

            $this->phptokens::T_INC                      => 'processPrePlusplus',
            $this->phptokens::T_DEC                      => 'processPrePlusplus',

            $this->phptokens::T_WHILE                    => 'processWhile',
            $this->phptokens::T_DO                       => 'processDo',
            $this->phptokens::T_IF                       => 'processIfthen',
            $this->phptokens::T_FOREACH                  => 'processForeach',
            $this->phptokens::T_FOR                      => 'processFor',
            $this->phptokens::T_TRY                      => 'processTry',
            $this->phptokens::T_CONST                    => 'processConst',
            $this->phptokens::T_SWITCH                   => 'processSwitch',
            $this->phptokens::T_MATCH                    => 'processMatch',
            $this->phptokens::T_DEFAULT                  => 'processDefault',
            $this->phptokens::T_CASE                     => 'processCase',
            $this->phptokens::T_DECLARE                  => 'processDeclare',

            $this->phptokens::T_AT                       => 'processNoscream',
            $this->phptokens::T_CLONE                    => 'processClone',
            $this->phptokens::T_GOTO                     => 'processGoto',

            $this->phptokens::T_STRING                   => 'processString',
            $this->phptokens::T_NAME_QUALIFIED           => 'processString',
            $this->phptokens::T_NAME_RELATIVE            => 'processString',
            $this->phptokens::T_NAME_FULLY_QUALIFIED     => 'processString',
            $this->phptokens::T_STRING_VARNAME           => 'processString', // ${x} x is here
            $this->phptokens::T_CONSTANT_ENCAPSED_STRING => 'processLiteral',
            $this->phptokens::T_ENCAPSED_AND_WHITESPACE  => 'processLiteral',
            $this->phptokens::T_NUM_STRING               => 'processLiteral',

            $this->phptokens::T_ARRAY_CAST               => 'processCast',
            $this->phptokens::T_BOOL_CAST                => 'processCast',
            $this->phptokens::T_DOUBLE_CAST              => 'processCast',
            $this->phptokens::T_INT_CAST                 => 'processCast',
            $this->phptokens::T_OBJECT_CAST              => 'processCast',
            $this->phptokens::T_STRING_CAST              => 'processCast',
            $this->phptokens::T_UNSET_CAST               => 'processCast',

            $this->phptokens::T_FILE                     => 'processMagicConstant',
            $this->phptokens::T_CLASS_C                  => 'processMagicConstant',
            $this->phptokens::T_FUNC_C                   => 'processMagicConstant',
            $this->phptokens::T_LINE                     => 'processMagicConstant',
            $this->phptokens::T_DIR                      => 'processMagicConstant',
            $this->phptokens::T_METHOD_C                 => 'processMagicConstant',
            $this->phptokens::T_NS_C                     => 'processMagicConstant',
            $this->phptokens::T_TRAIT_C                  => 'processMagicConstant',

            $this->phptokens::T_BANG                     => 'processNot',
            $this->phptokens::T_TILDE                    => 'processNot',
            $this->phptokens::T_ELLIPSIS                 => 'processEllipsis',

            $this->phptokens::T_SEMICOLON                => 'processSemicolon',
            $this->phptokens::T_CLOSE_TAG                => 'processClosingTag',

            $this->phptokens::T_FUNCTION                 => 'processFunction',
            $this->phptokens::T_FN                       => 'processFn',
            $this->phptokens::T_CLASS                    => 'processClass',
            $this->phptokens::T_TRAIT                    => 'processTrait',
            $this->phptokens::T_INTERFACE                => 'processInterface',
            $this->phptokens::T_NAMESPACE                => 'processNamespace',
            $this->phptokens::T_USE                      => 'processUse',

            $this->phptokens::T_ABSTRACT                 => 'processAbstract',
            $this->phptokens::T_FINAL                    => 'processFinal',
            $this->phptokens::T_PRIVATE                  => 'processPPP',
            $this->phptokens::T_PROTECTED                => 'processPPP',
            $this->phptokens::T_PUBLIC                   => 'processPPP',
            $this->phptokens::T_VAR                      => 'processVar',

            $this->phptokens::T_QUOTE                    => 'processQuote',
            $this->phptokens::T_START_HEREDOC            => 'processQuote',
            $this->phptokens::T_BACKTICK                 => 'processQuote',
            $this->phptokens::T_DOLLAR_OPEN_CURLY_BRACES => 'processDollarCurly',
            $this->phptokens::T_STATIC                   => 'processStatic',
            $this->phptokens::T_GLOBAL                   => 'processGlobalVariable',

            $this->phptokens::T_DOC_COMMENT              => 'processPhpdoc',
            $this->phptokens::T_ATTRIBUTE                => 'processAttribute',
        );

        $this->cases = new NestedCollector();
     }

    public function __destruct() {
        $this->callsDatabase = null;
        $this->loader        = null;

        if (file_exists("{$this->config->projects_root}/projects/.exakat/calls.sqlite")) {
            unlink("{$this->config->projects_root}/projects/.exakat/calls.sqlite");
        }
    }

    public function runPlugins(AtomInterface $atom, array $linked = array()): void {
        foreach($this->plugins as $plugin) {
            try {
                $plugin->run($atom, $linked);
            } catch (\Throwable $t) {
                $this->log->log('Runplugin error : ' . $t->getMessage() . ' ' . $t->getFile() . ' ' . $t->getLine());
            }
        }
    }

    public function run(): void {
        $this->logTime('Start');
        // Clean tmp folder
        $files = glob("{$this->config->tmp_dir}/*.csv");

        foreach($files as $file) {
            unlink($file);
        }

        $this->checkTokenLimit();

        // Reset Atom.
        $this->id0 = $this->addAtom('Project');
        $this->id0->code      = 'Whole';
        $this->id0->atom      = 'Project';
        $this->id0->code      = (string) $this->config->project;
        $this->id0->fullcode  = $this->config->project_name;
        $this->id0->token     = 'T_WHOLE';
        $this->atoms          = array();
        $this->minId          = \PHP_INT_MAX;

        // Cleaning the databases
        $this->datastore->cleanTable('tokenCounts');
        $this->datastore->cleanTable('dictionary');
        $this->logTime('Init');

        if ($filename = $this->config->filename) {
            if (!is_file($filename)) {
                throw new MustBeAFile($filename);
            }

            try {
                $this->callsDatabase = new \Sqlite3($this->sqliteLocation);
                $this->calls = new Calls($this->callsDatabase);

                $clientClass = "\\Exakat\\Loader\\{$this->config->loader}";
                display("Loading with $clientClass\n");
                if (!class_exists($clientClass)) {
                    throw new NoSuchLoader($clientClass, $this->loaderList);
                }
                $this->loader = new $clientClass($this->callsDatabase, $this->id0);

                ++$this->stats['files'];
                if ($this->processFile($filename, '')) {
                    $this->loader->finalize($this->relicat);
                } else {
                    print "Error while loading the file.\n";
                }
            } catch (NoFileToProcess $e) {
                $this->datastore->ignoreFile($filename, $e->getMessage());
                $this->log->log('Process File error : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            }
        } elseif ($dirName = $this->config->dirname) {
            if (!is_dir($dirName)) {
                throw new MustBeADir($dirName);
            }
            $this->processDir($dirName);
        } elseif (($project = $this->config->project) !== 'default') {
            $this->processProject($project);
        } else {
            throw new NoFileToProcess($filename, 'non-existent');
        }

        $this->logTime('Load in graph');

        $stats = array(array('key' => 'loc',         'value' => $this->stats['loc']),
                       array('key' => 'locTotal',    'value' => $this->stats['totalLoc']),
                       array('key' => 'files',       'value' => $this->stats['files']),
                       array('key' => 'tokens',      'value' => $this->stats['tokens']),
                       );
        $this->datastore->addRow('hash', $stats);

        $this->datastore->addRow('hash', array('status' => 'Load'));

        $loadFinal = new LoadFinal();
        $this->logTime('LoadFinal new');
        $loadFinal->run();
        $this->logTime('The End');
    }

    private function processProject(Project $project): array {
        $files = $this->datastore->getCol('files', 'file');

        if (empty($files)) {
            throw new NoFileToProcess((string) $project, "No file to load.\n");
        }

        $stubs = $this->config->stubs;

        display('Sequential processing');
        $this->runCollector($stubs);

        $this->gremlin = Graph::getConnexion();

        $nbTokens = $this->runProjectCore($files);

        return array('files'  => count($files),
                     'tokens' => $nbTokens);
    }

    private function runProjectCore(array $files): int {
        $clientClass = "\\Exakat\\Loader\\{$this->config->loader}";
        display("Loading with $clientClass\n");
        if (!class_exists($clientClass)) {
            throw new NoSuchLoader($clientClass, $this->loaderList);
        }

        $this->callsDatabase = new \Sqlite3($this->sqliteLocation);
        $this->loader = new $clientClass($this->callsDatabase, $this->id0);
        $this->calls = new Calls($this->callsDatabase);

        $version = $this->php->getVersion();
        $this->datastore->addRow('hash', array('notCompilable' . $version[0] . $version[2] => 0));

        $nbTokens = 0;
        if ($this->config->verbose && !$this->config->quiet) {
           $progressBar = new Progressbar(0, count($files), $this->config->screen_cols);
        }

        foreach($files as $file) {
            try {
                ++$this->stats['files'];
                $r = $this->processFile($file, $this->config->code_dir);
                $nbTokens += $r;
                if (isset($progressBar)) {
                    echo $progressBar->advance();
                }
            } catch (NoFileToProcess $e) {
                $this->datastore->ignoreFile($file, $e->getMessage());
                if (isset($progressBar)) {
                    echo $progressBar->advance();
                }
            }
            // Reduce memory as Atoms are not kept between files.
            gc_collect_cycles();
        }
        $this->loader->finalize($this->relicat);

        return $nbTokens;
    }

    private function runCollector(array $omittedFiles): void {
        $this->callsDatabase = new \Sqlite3($this->sqliteLocation);
        $this->loader = new Collector($this->callsDatabase, $this->id0);
        $this->calls = new Calls($this->callsDatabase);

        $fileExtensions = $this->config->file_extensions;
        $atomGroup = clone $this->atomGroup;

        $stats = $this->stats;
        foreach($omittedFiles as $file) {
            try {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if (!in_array($ext, $fileExtensions, \STRICT_COMPARISON)) {
                    continue;
                }

                $this->processFile($file, $this->config->code_dir, self::COMPILE_NO_CHECK);
            } catch (NoFileToProcess $e2) {
                // Ignore
            }
        }
        $this->loader->finalize($this->relicat);
        $this->atomGroup = $atomGroup;

        $this->theGlobals = array();

        $this->stats = $stats;
    }

    private function processDir(string $dir): array {
        if (!file_exists($dir)) {
            return array('files'  => -1,
                         'tokens' => -1);
        }

        $files = array();
        $ignoredFiles = array();
        $dir = rtrim($dir, '/');
        Files::findFiles($dir, $files, $ignoredFiles, $this->config);

        $clientClass = "\\Exakat\\Loader\\{$this->config->loader}";
        display("Loading with $clientClass\n");
        if (!class_exists($clientClass)) {
            throw new NoSuchLoader($clientClass, $this->loaderList);
        }
        $this->callsDatabase = new \Sqlite3($this->sqliteLocation);
        $this->calls = new Calls($this->callsDatabase);
        $this->loader = new $clientClass($this->callsDatabase, $this->id0);

        $nbTokens = 0;
        foreach($files as $file) {
            try {
                ++$this->stats['files'];
                $r = $this->processFile($file, $dir);
                $nbTokens += $r;
            } catch (NoFileToProcess $e) {
                $this->datastore->ignoreFile($file, $e->getMessage());
            }
        }
        $this->loader->finalize($this->relicat);

        $this->loader = new Collector($this->callsDatabase, $this->id0);
        $stats = $this->stats;
        foreach($ignoredFiles as $file) {
            try {
                $this->processFile($file, $dir);
            } catch (NoFileToProcess $e) {
                $this->datastore->ignoreFile($file, $e->getMessage());
            }
        }
        $this->loader->finalize($this->relicat);
        $this->stats = $stats;

        return array('files'  => count($files),
                     'tokens' => $nbTokens);
    }

    private function reset(): void {
        $this->atoms   = array();
        $this->links   = array();
        $this->minId  = \PHP_INT_MAX;

        $this->contexts    = new Context();
        $this->expressions = array();
        $this->uses        = new Fullnspaths();

        $this->currentMethod           = array();
        $this->currentFunction         = array();
        $this->currentClassTrait       = array();
        $this->currentVariables        = array();

        $this->tokens                  = array();
        $this->phpDocs                 = array();
        $this->attributes              = array();
    }

    public function initDiff(): void {
        $clientClass = "\\Exakat\\Loader\\{$this->config->loader}";
        display("Loading with $clientClass\n");
        if (!class_exists($clientClass)) {
            throw new NoSuchLoader($clientClass, $this->loaderList);
        }

        $res = $this->gremlin->query('g.V().id().max()');
        $this->atomGroup = new AtomGroup($res->toInt() + 1);

        $this->id0 = $this->addAtom('Project');
        $this->id0->code      = 'Whole';
        $this->id0->atom      = 'Project';
        $this->id0->code      = (string) $this->config->project;
        $this->id0->fullcode  = $this->config->project_name;
        $this->id0->token     = 'T_WHOLE';
        $this->atoms          = array();
        $this->minId         = \PHP_INT_MAX;

        $this->loader = new $clientClass($this->callsDatabase, $this->id0);
    }

    public function finishDiff(): void {
        $this->loader->finalize(array());

        $loadFinal = new LoadFinal();
        $this->logTime('LoadFinal new');
        $loadFinal->run();
        $this->logTime('The End');

        $this->reset();
    }

    public function processDiffFile(string $filename, string $path): void {
        try {
            $this->processFile($filename, $path);
        } catch(NoFileToProcess $e ) {
            $this->datastore->ignoreFile($filename, $e->getMessage());
        }
    }

    private function processFile(string $filename, string $path, bool $compileCheck = self::COMPILE_CHECK): int {
        $begin = microtime(\TIME_AS_NUMBER);
        $fullpath = $path . $filename;

        $this->filename = $filename;

        $log = array();

        if (is_link($fullpath)) {
            return 0;
        }
        if (!file_exists($fullpath)) {
            throw new NoFileToProcess($filename, 'unreachable file');
        }

        if (filesize($fullpath) === 0) {
            throw new NoFileToProcess($filename, 'empty file');
        }

        if ($compileCheck === self::COMPILE_CHECK && !$this->php->compile($fullpath)) {
            $error = $this->php->getError();
            $error['file'] = $filename;

            $version = $this->php->getVersion();
            $this->datastore->addRow('compilation' . $version[0] . $version[2], array($error));

            $count = $this->datastore->gethash('notCompilable' . $version[0] . $version[2]);
            $this->datastore->addRow('hash', array('notCompilable' . $version[0] . $version[2] => intval($count) + 1));

            return 0;
        }

        $tokens = $this->php->getTokenFromFile($fullpath);
        $log['token_initial'] = count($tokens);

        if (count($tokens) < 3) {
            throw new NoFileToProcess($filename, 'Only ' . count($tokens) . ' tokens');
        }

        $comments     = 0;
        $this->tokens = array();
        $total        = 0;
        $line         = 0;
        foreach($tokens as $t) {
            if (is_array($t)) {
                switch($t[0]) {
                    case $this->phptokens::T_WHITESPACE:
                        $line += substr_count($t[1], "\n");
                        break;

                    case $this->phptokens::T_COMMENT :
                        $c = substr_count($t[1], "\n");
                        $line += $c;
                        $comments += $c;
                        break;

                    case $this->phptokens::T_BAD_CHARACTER :
                        // Ignore all
                        break;

                    case $this->phptokens::T_DOC_COMMENT:
                        $this->tokens[] = $t;
                        $comments += substr_count($t[1], "\n") + 1;
                        break;

                    default :
                        $line = $t[2];
                        $this->tokens[] = $t;
                        ++$total;
                    }
            } elseif (is_string($t)) {
                $this->tokens[] = array(0 => $this->phptokens::TOKENS[$t],
                                        1 => $t,
                                        2 => $line);
                ++$total;
            } else {
                assert(false, "$t is in a wrong token type : " . gettype($t));
            }
        }
        $this->stats['loc'] -= $comments;

        // Final token
        $this->tokens[] = array(0 => $this->phptokens::T_END,
                                1 => '/* END */',
                                2 => $line);
        $this->stats['tokens'] += count($tokens);
        unset($tokens);

        $this->uses   = new Fullnspaths();

        $id1 = $this->addAtom('File');
        $id1->code     = $filename;
        $id1->fullcode = $filename;
        $id1->token    = 'T_FILENAME';

        $this->currentMethod           = array($id1);
        $this->currentFunction         = array($id1);

        try {
            $n = count($this->tokens) - 2;
            $this->id = 0; // set to 0 so as to calculate line in the next call.
            $this->startSequence(); // At least, one sequence available
            $this->id = -1;
            do {
                $theExpression = $this->processNext();
                $this->addToSequence($theExpression);
            } while ($this->id < $n);

            $sequence = $this->sequence;

            $this->addLink($id1, $sequence, 'FILE');
        } catch (LoadError $e) {
            if ($compileCheck === self::COMPILE_CHECK) {
                $this->log->log('Can\'t process file \'' . $this->filename . '\' during load (\'' . $this->tokens[$this->id][0] . '\', line \'' . $this->tokens[$this->id][2] . '\'). Ignoring' . PHP_EOL . $e->getMessage() . PHP_EOL);
            }
            $this->reset();
            $this->calls->reset();
            throw new NoFileToProcess($filename, 'empty (1)', 0, $e);
        } finally {
            try {
                $this->checkTokens($filename);
                $this->calls->save();
            } catch (LoadError $e) {
                $this->log->log('Can\'t process file \'' . $this->filename . '\' during load (finally) (\'' . $this->tokens[$this->id][0] . '\', line \'' . $this->tokens[$this->id][2] . '\'). Ignoring' . PHP_EOL . $e->getMessage() . PHP_EOL);
                $this->reset();
                $this->calls->reset();
                throw new NoFileToProcess($filename, 'empty (2)', 0, $e);
            }

            $this->stats['totalLoc'] += $line;
            $this->stats['loc'] += $line;
        }

        $end = microtime(\TIME_AS_NUMBER);
        $load = ($end - $begin) * 1000;

        $atoms = count($this->atoms);
        $links = count($this->links);
        $begin = microtime(\TIME_AS_NUMBER);
        $this->saveFiles();
        $end = microtime(\TIME_AS_NUMBER);
        $save = ($end - $begin) * 1000;

        $this->log->log("$filename\t$load\t$save\t$log[token_initial]\t$atoms\t$links");

        return $log['token_initial'];
    }

    private function processNext(): AtomInterface {
        ++$this->id;

        if ($this->tokens[$this->id][0] === $this->phptokens::T_END ||
            !isset($this->processing[ $this->tokens[$this->id][0] ])) {
            display("Can't process file '$this->filename' during load ('{$this->tokens[$this->id][0]}', line {$this->tokens[$this->id][2]}). Ignoring\n");
            $this->log->log("Can't process file '$this->filename' during load ('{$this->tokens[$this->id][0]}', line {$this->tokens[$this->id][2]}). Ignoring\n");

            throw new LoadError('Processing error (processNext end)');
        }
        $method = $this->processing[ $this->tokens[$this->id][0] ];

//        print "  $method in".PHP_EOL;
        $atom = $this->$method();
//        print "  $method out ".PHP_EOL;

        return $atom;
    }

    private function processExpression(array $finals): AtomInterface {
        do {
           $expression = $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], $finals, \STRICT_COMPARISON));

        $this->popExpression();

        return $expression;
    }

    private function processColon(): AtomInterface {
        --$this->id;
        $tag = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);
        ++$this->id;

        $label = $this->addAtom('Gotolabel', $this->id);
        $this->addLink($label, $tag, 'GOTOLABEL');
        $label->fullcode = $tag->fullcode . ' :';

        if (empty($this->currentClassTrait)) {
            $class = '';
        } else {
            $class = end($this->currentClassTrait)->fullcode;
        }

        $method = empty($this->currentFunction) ? '' : end($this->currentFunction)->fullnspath;

        $this->calls->addDefinition('goto', "$class::$method..$tag->fullcode", $label);

        $this->addToSequence($label);

        return $label;
    }

    //////////////////////////////////////////////////////
    /// processing complex tokens
    //////////////////////////////////////////////////////
    private function processQuote(): AtomInterface {
        $current = $this->id;
        $fullcode = array();
        $rank = -1;
        $elements = array();

        if ($this->tokens[$current][0] === $this->phptokens::T_QUOTE) {
            $string = $this->addAtom('String', $current);
            $finalToken = $this->phptokens::T_QUOTE;
            $closeQuote = '"';
            $type = $this->phptokens::T_QUOTE;

            $openQuote = $this->tokens[$this->id][1];
            if ($this->tokens[$current][1][0] === 'b' || $this->tokens[$current][1][0] === 'B') {
                $string->binaryString = $openQuote[0];
                $openQuote = '"';
            }
        } elseif ($this->tokens[$current][0] === $this->phptokens::T_BACKTICK) {
            $string = $this->addAtom('Shell', $current);
            $finalToken = $this->phptokens::T_BACKTICK;
            $openQuote = '`';
            $closeQuote = '`';
            $type = $this->phptokens::T_BACKTICK;
        } elseif ($this->tokens[$current][0] === $this->phptokens::T_START_HEREDOC) {
            $string = $this->addAtom('Heredoc', $current);
            $finalToken = $this->phptokens::T_END_HEREDOC;
            $openQuote = $this->tokens[$this->id][1];
            if (strtolower($openQuote[0]) === 'b') {
                $string->binaryString = $openQuote[0];
                $openQuote = substr($openQuote, 1);
            }

            $closeQuote = $openQuote[3] === "'" ? substr($openQuote, 4, -2) : substr($openQuote, 3);

            $type = $this->phptokens::T_START_HEREDOC;
        } else {
            throw new LoadError(__METHOD__ . ' : unsupported type of open quote : ' . $this->tokens[$current][0]);
        }

        // Set default, in case the whole loop is skipped
        $string->noDelimiter = '';
        $string->delimiter   = '';

        while ($this->tokens[$this->id + 1][0] !== $finalToken) {
            $currentVariable = $this->id + 1;
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CURLY_OPEN) {
                $open = $this->id + 1;
                ++$this->id; // Skip {
                do {
                    $part = $this->processNext();
                } while ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_CLOSE_CURLY);
                ++$this->id; // Skip }

                $this->popExpression();

                $part->enclosing = self::ENCLOSING;
                $part->fullcode  = $this->tokens[$open][1] . $part->fullcode . '}';
                $part->token     = $this->getToken($this->tokens[$currentVariable][0]);

                $this->pushExpression($part);

                $elements[] = $part;
            } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_DOLLAR_OPEN_CURLY_BRACES) {
                $part = $this->processDollarCurly();

                $part->enclosing = self::ENCLOSING;
                $part->token     = $this->getToken($this->tokens[$currentVariable][0]);
                $this->pushExpression($part);

                $elements[] = $part;
            } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_VARIABLE) {
                if ($this->tokens[$this->id + 1][1] === '$this') {
                    $atom = 'This';
                } elseif (in_array($this->tokens[$this->id + 1][1], $this->PHP_SUPERGLOBALS, \STRICT_COMPARISON)) {
                    $atom = 'Phpvariable';
                } elseif (in_array($this->tokens[$this->id + 2][0], array($this->phptokens::T_OBJECT_OPERATOR,
                                                                          $this->phptokens::T_NULLSAFE_OBJECT_OPERATOR,
                                                                         ), \STRICT_COMPARISON)) {
                    $atom = 'Variableobject';
                } elseif ($this->tokens[$this->id + 2][0] === $this->phptokens::T_OPEN_BRACKET) {
                    $atom = 'Variablearray';
                } else {
                    $atom = 'Variable';
                }
                ++$this->id;
                $variable = $this->processSingle($atom);

                if ($atom === 'This' && ($class = end($this->currentClassTrait))) {
                    $variable->fullnspath = $class->fullnspath;
                    $this->calls->addCall('class', $class->fullnspath, $variable);
                }

                if (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_OBJECT_OPERATOR,
                                                                    $this->phptokens::T_NULLSAFE_OBJECT_OPERATOR,
                                                                    ), \STRICT_COMPARISON)) {
                    $property = $this->addAtom('Member', $this->id);

                    ++$this->id;
                    $propertyName = $this->processNextAsIdentifier();

                    $property->fullcode  = "{$variable->fullcode}->{$propertyName->fullcode}";
                    $property->enclosing = self::NO_ENCLOSING;

                    $this->addLink($property, $variable, 'OBJECT');
                    $this->addLink($property, $propertyName, 'MEMBER');
                    $this->runPlugins($property, array('OBJECT' => $variable,
                                                       'MEMBER' => $propertyName,
                                                       ));

                    if ($variable->atom === 'This' &&
                        $propertyName->token   === 'T_STRING') {
                        $this->calls->addCall('property', "{$variable->fullnspath}::{$propertyName->code}", $property);
                        array_collect_by($this->currentPropertiesCalls, $propertyName->code, $property);
                    }

                    $this->pushExpression($property);
                    $elements[] = $property;
                } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_BRACKET) {
                    ++$this->id; // Skip $a
                    $array = $this->addAtom('Array', $this->id);
                    ++$this->id; // Skip [

                    if ($this->tokens[$this->id][0] === $this->phptokens::T_NUM_STRING) {
                        $index = $this->processSingle('Integer');
                        $this->runPlugins($index);
                    } elseif ($this->tokens[$this->id][0] === $this->phptokens::T_MINUS) {
                        ++$this->id;
                        if ($this->tokens[$this->id][1][0] === '0') {
                            $index            = $this->processSingle('String');
                            $index->code      = "-{$index->code}";
                            $index->fullcode  = "-{$index->fullcode}";
                        } else {
                            $index            = $this->processSingle('Integer');
                            $index->code      = (string) (-1 * $index->code);
                            $index->fullcode  = (string) (-1 * $index->fullcode);
                        }
                    } elseif ($this->tokens[$this->id][0] === $this->phptokens::T_STRING) {
                        $index = $this->processSingle('String');
                    } elseif ($this->tokens[$this->id][0] === $this->phptokens::T_VARIABLE) {
                        $index = $this->processVariable();
                        $this->popExpression();
                    } else {
                        throw new UnknownCase('Couldn\'t read that token inside quotes : ' . $this->tokens[$this->id][0]);
                    }
                    ++$this->id; // Skip ]

                    $array->fullcode  = "{$variable->fullcode}[{$index->fullcode}]";
                    $array->enclosing = self::NO_ENCLOSING;

                    $this->addLink($array, $variable, 'VARIABLE');
                    $this->addLink($array, $index, 'INDEX');
                    $this->runPlugins($array, array('VARIABLE' => $variable,
                                                    'INDEX'    => $index,
                                                     ));

                    $this->pushExpression($array);
                    $elements[] = $array;
                } else {
                    $this->pushExpression($variable);
                }
            } else {
                $this->processNext();
            }

            $part = $this->popExpression();
            if ($part->atom === 'String') {
                $part->noDelimiter = $part->code;
                $part->delimiter   = '';
            } else {
                $part->noDelimiter = '';
                $part->delimiter   = '';
            }
            $part->rank = ++$rank;
            $fullcode[] = $part->fullcode;
            $elements[] = $part;

            $this->addLink($string, $part, 'CONCAT');
        }

        if ($type === $this->phptokens::T_START_HEREDOC) {
            if (!empty($elements)) {
                // This is the last part
                $part = array_pop($elements);
                $part->noDelimiter = rtrim($part->noDelimiter, "\n");
                $part->code        = rtrim($part->code,        "\n");
                $part->fullcode    = rtrim($part->fullcode,    "\n");
                $elements[]        = $part;
            }
            // Get the closing quote for flexibility
            $closeQuote = $this->tokens[$this->id + 1][1];
            if (trim($closeQuote) !== $closeQuote) {
                $string->flexible = self::FLEXIBLE;
            }
        }

        ++$this->id;
        $string->fullcode    = $string->binaryString . $openQuote . implode('', $fullcode) . $closeQuote;
        $string->count       = $rank + 1;

        if ($type === $this->phptokens::T_START_HEREDOC) {
            $string->delimiter = trim($closeQuote);
            $string->heredoc   = $openQuote[3] !== "'";
        }

        $this->runPlugins($string, $elements);
        $this->pushExpression($string);

        if ($type === $this->phptokens::T_QUOTE) {
            $string = $this->processFCOA($string);
        }

        $this->checkExpression();

        return $string;
    }

    private function processDollarCurly(): AtomInterface {
        $current = $this->id;
        $atom = ($this->tokens[$this->id - 1][0] === $this->phptokens::T_GLOBAL) ? 'Globaldefinition' : 'Variable';
        $variable = $this->addAtom($atom, $current);

        ++$this->id; // Skip ${
        do {
            $name = $this->processNext();
        } while ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_CLOSE_CURLY);
        ++$this->id; // Skip }

        $this->popExpression();
        $this->addLink($variable, $name, 'NAME');

        if ($atom === 'Identifier') {
            $this->getFullnspath($name, 'const', $name);
            $this->calls->addCall('const', $name->fullnspath, $name);
        }

        $variable->fullcode  = '${' . $name->fullcode . '}';
        $variable->enclosing = self::ENCLOSING;

        $this->runPlugins($variable, array('NAME' => $name));

        $this->checkExpression();

        return $variable;
    }

    private function processTry(): AtomInterface {
        $current = $this->id;
        $try = $this->addAtom('Try', $current);

        $block = $this->processFollowingBlock(array($this->phptokens::T_CLOSE_CURLY));
        $this->addLink($try, $block, 'BLOCK');
        $extras = array('BLOCK' => $block);

        $rank = 0;
        $fullcode = array();
        $this->checkPhpdoc();
        while ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CATCH) {
            $catchId = $this->id + 1;
            ++$this->id; // Skip catch
            ++$this->id; // Skip (

            $catch = $this->addAtom('Catch', $catchId);
            $catchFullcode = array();
            $extrasCatch = array();
            $rankCatch = -1;
            while ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_VARIABLE) {
                $class = $this->processOneNsname();
                $this->addLink($catch, $class, 'CLASS');
                $catch->rank = ++$rankCatch;

                $this->calls->addCall('class', $class->fullnspath, $class);
                $catchFullcode[] = $class->fullcode;
                $extrasCatch['CLASS' . $rankCatch] = $class;

                if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OR) {
                    ++$this->id; // Skip |
                }
            }
            $catch->count = $rankCatch + 1;
            $catchFullcode = implode(' | ', $catchFullcode);

            // Process variable
            $variable = $this->processNext();

            $this->popExpression();
            $this->addLink($catch, $variable, 'VARIABLE');
            $extrasCatch['VARIABLE'] = $variable;

            // Skip )
            ++$this->id;

            // Skip }
            $blockCatch = $this->processFollowingBlock(array($this->phptokens::T_CLOSE_CURLY));
            $this->addLink($catch, $blockCatch, 'BLOCK');
            $extrasCatch['BLOCK'] = $variable;

            $catch->fullcode = $this->tokens[$catchId][1] . ' (' . $catchFullcode . ' ' . $variable->fullcode . ')' . static::FULLCODE_BLOCK;
            $catch->rank     = ++$rank;

            $this->addLink($try, $catch, 'CATCH');
            $fullcode[] = $catch->fullcode;

            $extras['CATCH' . $rank] = $catch;
            $this->runPlugins($catch, $extrasCatch);
            $this->checkPhpdoc();
        }

        $this->checkPhpdoc();
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_FINALLY) {
            $finallyId = $this->id + 1;
            $finally = $this->addAtom('Finally', $finallyId);

            ++$this->id;
            $finallyBlock = $this->processFollowingBlock(array($this->phptokens::T_CLOSE_CURLY));
            $this->addLink($try, $finally, 'FINALLY');
            $this->addLink($finally, $finallyBlock, 'BLOCK');

            $finally->fullcode = $this->tokens[$finallyId][1] . static::FULLCODE_BLOCK;

            $extras['FINALLY'] = $finally;
            $this->runPlugins($finally, array('BLOCK' => $finallyBlock));
        }

        $try->fullcode = $this->tokens[$current][1] . static::FULLCODE_BLOCK . implode('', $fullcode) . ( isset($finally) ? $finally->fullcode : '');
        $try->count    = $rank;

        $this->addToSequence($try);

        $this->runPlugins($try, $extras);
        return $try;
    }

    private function processFn(): AtomInterface {
        $current = $this->id;

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_AND) {
            ++$this->id;
            $reference = self::REFERENCE;
        } else {
            $reference = self::NOT_REFERENCE;
        }

        ++$this->id;
        $atom     = 'Arrowfunction';

        // Keep a copy of the current variables, to remove the arguments when we are done
        $previousContextVariables = $this->currentVariables;

        $fn       = $this->processParameters($atom);
        $fn->reference = $reference;

        // Process return type
        $returnTypeFullcode = $this->processTypehint($fn);

        ++$this->id; // skip =>

        $this->contexts->nestContext(Context::CONTEXT_FUNCTION);
        $this->contexts->toggleContext(Context::CONTEXT_FUNCTION);

        // arrowfunction may be static
        if ($this->tokens[$current - 1][0] === $this->phptokens::T_STATIC) {
            $this->currentClassTrait[] = '';
        }

        $block = $this->processExpression(array($this->phptokens::T_COMMA,
                                                $this->phptokens::T_CLOSE_PARENTHESIS,
                                                $this->phptokens::T_CLOSE_CURLY,
                                                $this->phptokens::T_SEMICOLON,
                                                $this->phptokens::T_CLOSE_BRACKET,
                                                $this->phptokens::T_CLOSE_TAG,
                                                $this->phptokens::T_COLON,
                                                ));

       // arrowfunction may be static
       if ($this->tokens[$current - 1][0] === $this->phptokens::T_STATIC) {
           array_pop($this->currentClassTrait);
       }

        $this->contexts->exitContext(Context::CONTEXT_FUNCTION);

        $this->addLink($fn, $block, 'BLOCK');
        $this->addLink($fn, $block, 'RETURNED');
        $this->addLink($fn, $block, 'RETURN');
        $this->makeAttributes($fn);

        $fn->token    = $this->getToken($this->tokens[$current][0]);
        $fn->fullcode = $this->tokens[$current][1] . ' ' .
                        ($fn->reference ? '&' : '') .
                        '(' . $fn->fullcode . ')' .
                        $returnTypeFullcode .
                        ' => ' . $block->fullcode;
        $fn->fullnspath = $this->makeAnonymous('arrowfunction');

        $this->currentVariables = $previousContextVariables;

        $this->pushExpression($fn);
        $this->checkExpression();

        return $fn;
    }

    private function processFunction(): AtomInterface {
        $current = $this->id;

        if ( $this->contexts->isContext(Context::CONTEXT_CLASS) &&
             !$this->contexts->isContext(Context::CONTEXT_FUNCTION)) {

            if (in_array(mb_strtolower($this->tokens[$this->id + 1][1]),
                         array('__construct',
                               '__destruct',
                               '__call',
                               '__callstatic',
                               '__get',
                               '__set',
                               '__isset',
                               '__unset',
                               '__sleep',
                               '__wakeup',
                               '__tostring',
                               '__invoke',
                               '__set_state',
                               '__clone',
                               '__debuginfo',
                               '__serialize',
                               '__unserialize',
                               ),
                            \STRICT_COMPARISON)) {
                $atom = 'Magicmethod';

            } else {
                $atom = 'Method';
            }
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_PARENTHESIS) {
            $atom = 'Closure';
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_AND &&
                  $this->tokens[$this->id + 2][0] === $this->phptokens::T_OPEN_PARENTHESIS) {
            $atom = 'Closure';
        } else {
            $atom = 'Function';
        }

        $this->contexts->nestContext(Context::CONTEXT_CLASS);
        $this->contexts->nestContext(Context::CONTEXT_FUNCTION);
        $this->contexts->toggleContext(Context::CONTEXT_FUNCTION);

        $previousContextVariables = $this->currentVariables;
        $this->currentVariables = array();

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_AND) {
            ++$this->id;
            $reference = self::REFERENCE;
        } else {
            $reference = self::NOT_REFERENCE;
        }

        if ($atom !== 'Closure') {
            $name = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);
        }
        ++$this->id;

        $fullcode = array();

        // Process arguments
        $function       = $this->processParameters($atom);
        $function->code = $function->atom === 'Closure' ? 'function' : $name->fullcode;
        $this->makePhpdoc($function);
        $this->makeAttributes($function);

        if ($function->atom === 'Function') {
            $this->getFullnspath($name, 'function', $function);
            $this->calls->addDefinition('function', $function->fullnspath, $function);

            $this->addLink($function, $name, 'NAME');
        } elseif ($function->atom === 'Closure') {
            $function->fullnspath = $this->makeAnonymous('function');

            // closure may be static
            if ($this->tokens[$current - 1][0] === $this->phptokens::T_STATIC) {
                $this->currentClassTrait[] = '';
            }
        } elseif (in_array($function->atom, array('Method', 'Magicmethod'), \STRICT_COMPARISON)) {
            $function->fullnspath = end($this->currentClassTrait)->fullnspath . '::' . mb_strtolower($name->code);

            if (empty($function->visibility)) {
                $function->visibility = 'none';
            }

            $this->addLink($function, $name, 'NAME');
        } else {
            throw new LoadError(__METHOD__ . ' : wrong type of function ' . $function->atom);
        }

        $function->token      = $this->getToken($this->tokens[$current][0]);

        $argumentsFullcode = $function->fullcode;
        $function->reference = $reference;

        // Process use
        $useFullcode = array();
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_USE) {
            ++$this->id; // Skip use
            ++$this->id; // Skip (

            $rank = 0;
            $uses = array();
            do {
                ++$this->id; // Skip ( or ,

                if ($this->tokens[$this->id][0] === $this->phptokens::T_CLOSE_PARENTHESIS) {
                    $useFullcode[] = '';

                    continue;
                }

                if ($this->tokens[$this->id][0] === $this->phptokens::T_AND) {
                    ++$this->id;
                    $arg = $this->processSingle('Parameter');
                    $arg->reference = self::REFERENCE;
                    $arg->fullcode = "&$arg->fullcode";
                } else {
                    $arg = $this->processSingle('Parameter');
                }
                ++$this->id;

                $useFullcode[] = $arg->fullcode;
                $arg->rank = ++$rank;

                $this->addLink($function, $arg, 'USE');
                $this->currentVariables[$arg->code] = $arg;
                if (isset($previousContextVariables[$arg->code])) {
                    $this->addLink($previousContextVariables[$arg->code], $arg, 'DEFINITION');
                }
            } while ($this->tokens[$this->id][0] === $this->phptokens::T_COMMA);

            $this->runPlugins($function, $uses);
        }

        // Process return type
        $returnTypes = $this->processTypehint($function);

        // Process block
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_SEMICOLON) {
            $block = $this->addAtomVoid();
            $this->addLink($function, $block, 'BLOCK');
            ++$this->id; // skip the next ;
            $blockFullcode = ' ;';
            $this->runPlugins($block);
        } else {
            $block = $this->processFollowingBlock(array($this->phptokens::T_CLOSE_CURLY));
            $this->addLink($function, $block, 'BLOCK');
            $blockFullcode = self::FULLCODE_BLOCK;
        }

        $function->fullcode   = (empty($fullcode) ? '' : implode(' ', $fullcode) . ' ' ) .
                                $this->tokens[$current][1] . ' ' . ($function->reference ? '&' : '') .
                                ($function->atom === 'Closure' ? '' : $name->fullcode) . '(' . $argumentsFullcode . ')' .
                                (empty($useFullcode) ? '' : ' use (' . implode(', ', $useFullcode) . ')') . // No space before use
                                $returnTypes .
                                $blockFullcode;

       if ($function->atom === 'Closure' &&
           $this->tokens[$current - 1][0] === $this->phptokens::T_STATIC) {
           array_pop($this->currentClassTrait);
       }

        $this->contexts->exitContext(Context::CONTEXT_CLASS);
        $this->contexts->exitContext(Context::CONTEXT_FUNCTION);
        $this->runPlugins($function, array('BLOCK' => $block));

        array_pop($this->currentFunction);
        array_pop($this->currentMethod);
        $this->currentVariables = $previousContextVariables;

        $this->pushExpression($function);

        if ($function->atom === 'Function') {
            $this->processSemicolon();
        } elseif ($function->atom === 'Closure' &&
                  $this->tokens[$current  - 1][0] !== $this->phptokens::T_EQUAL          &&
                  $this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG) {
            $this->processSemicolon();
        } elseif ($function->atom === 'Method' && !empty(preg_grep('/^static$/i', $fullcode))) {
            $this->calls->addDefinition('staticmethod', $function->fullnspath, $function);
            $this->currentMethods[mb_strtolower($function->code)] = $function;
        } elseif ($function->atom === 'Method') {
            $this->calls->addDefinition('method', $function->fullnspath, $function);
            $this->currentMethods[mb_strtolower($function->code)] = $function;
            // double call for internal reference
            $this->calls->addDefinition('staticmethod', $function->fullnspath, $function);
        } elseif ($function->atom === 'Magicmethod') {
            if (mb_strtolower($this->tokens[$current + 1][1]) === '__construct' &&
                end($this->currentClassTrait)->atom === 'Classanonymous') {
                    $this->addLink(end($this->currentClassTrait), $function, 'DEFINITION');
            }
            $this->currentMethods[mb_strtolower($function->code)] = $function;
        }

        return $function;
    }

    private function processOneNsname(bool $getFullnspath = self::WITH_FULLNSPATH): AtomInterface {
        ++$this->id;
        if ($this->tokens[$this->id][0] === $this->phptokens::T_NAMESPACE) {
            ++$this->id;
        }
        $nsname = $this->makeNsname();

        if ($getFullnspath === self::WITH_FULLNSPATH) {
            $this->getFullnspath($nsname, 'class', $nsname);
            $this->calls->addCall('class', $nsname->fullnspath, $nsname);
        }

        return $nsname;
    }

    private function processTrait(): AtomInterface {
        $current = $this->id;
        $trait = $this->addAtom('Trait', $current);
        $this->currentClassTrait[] = $trait;
        $this->makePhpdoc($trait);
        $this->makeAttributes($trait);

        $this->contexts->nestContext(Context::CONTEXT_CLASS);
        $this->contexts->toggleContext(Context::CONTEXT_CLASS);

        $name = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);
        $this->addLink($trait, $name, 'NAME');

        $this->getFullnspath($name, 'class', $trait);
        $this->calls->addDefinition('class', $trait->fullnspath, $trait);

        // Process block
        $this->makeCitBody($trait);

        $trait->fullcode   = $this->tokens[$current][1] . ' ' . $name->fullcode . static::FULLCODE_BLOCK;

        $this->addToSequence($trait);

        $this->contexts->exitContext(Context::CONTEXT_CLASS);

        array_pop($this->currentClassTrait);

        return $trait;
    }

    private function processInterface(): AtomInterface {
        $current = $this->id;
        $interface = $this->addAtom('Interface', $current);
        $this->currentClassTrait[] = $interface;
        $this->makePhpdoc($interface);
        $this->makeAttributes($interface);

        $this->contexts->nestContext(Context::CONTEXT_CLASS);
        $this->contexts->toggleContext(Context::CONTEXT_CLASS);

        $name = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);
        $this->addLink($interface, $name, 'NAME');

        $this->getFullnspath($name, 'class', $interface);

        $this->calls->addDefinition('class', $interface->fullnspath, $interface);

        $this->checkPhpdoc();

        // Process extends
        $rank = 0;
        $fullcode= array();
        $extendsKeyword = '';
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_EXTENDS) {
            $extendsKeyword = $this->tokens[$this->id + 1][1];
            do {
                ++$this->id; // Skip extends or ,
                $this->checkPhpdoc();
                $extends = $this->processOneNsname(self::WITH_FULLNSPATH);
                $extends->rank = $rank;

                $this->addLink($interface, $extends, 'EXTENDS');
                $this->calls->addCall('class', $extends->fullnspath, $extends);

                $fullcode[] = $extends->fullcode;
            } while ($this->tokens[$this->id + 1][0] === $this->phptokens::T_COMMA);
        }

        $this->checkPhpdoc();

        // Process block
        $this->makeCitBody($interface);

        $interface->fullcode   = $this->tokens[$current][1] . ' ' . $name->fullcode . (empty($extendsKeyword) ? '' : ' ' . $extendsKeyword . ' ' . implode(', ', $fullcode)) . static::FULLCODE_BLOCK;

        $this->addToSequence($interface);

        $this->contexts->exitContext(Context::CONTEXT_CLASS);
        array_pop($this->currentClassTrait);

        return $interface;
    }

    private function makeCitBody(AtomInterface $class): void {
        ++$this->id;
        $rank = -1;

        $this->currentProperties      = array();
        $this->currentPropertiesCalls = array();
        $this->currentMethods         = array();
        $this->currentMethodsCalls    = array();

        $this->checkPhpdoc();
        while($this->tokens[$this->id + 1][0] !== $this->phptokens::T_CLOSE_CURLY) {
            $this->checkAttribute();
            $cpm = $this->processNext();

            $this->popExpression();

            switch ($cpm->atom) {
                case 'Usetrait':
                    $link = 'USE';
                    break;

                case 'Phpdoc':
                    // Skip everything for phpdocs
                    continue 2;
                    break;

                default:
                    $link = strtoupper($cpm->atom);
                    break;
            }
            $cpm->rank = ++$rank;

            if ($class->atom === 'Interface' && in_array($cpm->atom, array('Method', 'Magicethod'))) {
                $cpm->abstract = true;
            }

            $this->addLink($class, $cpm, $link);
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_SEMICOLON) {
                ++$this->id;
            }
            $this->checkPhpdoc();
        }

        $currentClass = $this->currentClassTrait[count($this->currentClassTrait) - 1];

        $diff = array_diff(array_keys($this->currentPropertiesCalls), array_keys($this->currentProperties));
        foreach($diff as $missing) {
            $ppp = $this->addAtom('Ppp');
            $ppp->fullcode     = 'public $' . $missing;
            $ppp->visibility   = 'none';
            $ppp->code         = $missing;
            $ppp->line         = -1;
            $this->addLink($currentClass, $ppp, 'PPP');

            $virtual = $this->addAtom('Virtualproperty');
            $virtual->fullcode     = '$' . $missing;
            $virtual->propertyname = $missing;
            $virtual->line         = -1;
            $this->addLink($ppp, $virtual, 'PPP');
            $this->addLink($virtual, $this->addAtomVoid(), 'DEFAULT');

            foreach($this->currentPropertiesCalls[$missing] as $member) {
                $this->addLink($virtual, $member, 'DEFINITION');
            }

            $this->currentProperties[$missing] = $virtual;
        }

        $diff = array_diff(array_keys($this->currentMethodsCalls), array_keys($this->currentMethods));
        foreach($diff as $missing) {
            $virtual = $this->addAtom('Virtualmethod');
            $virtual->fullcode     = 'function ' . $missing . ' ( ) { /**/ } ';
            $virtual->visibility   = 'none';
            $virtual->code         = mb_strtolower($missing);
            $virtual->line         = -1;
            $this->addLink($currentClass, $virtual, 'METHOD');
            // TODO : may be MAGICMETHOD ?

            foreach($this->currentMethodsCalls[$missing] as $member) {
                $this->addLink($virtual, $member, 'DEFINITION');
            }

            $this->currentMethods[$missing] = $virtual;
        }

        $this->currentProperties      = array();
        $this->currentPropertiesCalls = array();
        $this->currentMethods         = array();
        $this->currentMethodsCalls    = array();

        ++$this->id;
    }

    private function processClass(): AtomInterface {
        $current = $this->id;

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_STRING) {
            $class = $this->addAtom('Class', $current);

            $name = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);

            $this->getFullnspath($name, 'class', $class);

            $this->calls->addDefinition('class', $class->fullnspath, $class);
            $this->addLink($class, $name, 'NAME');
        } else {
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_PARENTHESIS) {
                // Process arguments
                ++$this->id; // Skip arguments
                $class = $this->processArguments('Classanonymous', array());
                $argumentsFullcode = $class->fullcode;
            } else {
                $class = $this->addAtom('Classanonymous', $current);
            }

            $class->fullnspath = $this->makeAnonymous();
            $this->calls->addDefinition('class', $class->fullnspath, $class);
        }
        $this->makePhpdoc($class);
        $this->makeAttributes($class);

        $this->currentClassTrait[] = $class;

        $this->contexts->nestContext(Context::CONTEXT_CLASS);
        $this->contexts->toggleContext(Context::CONTEXT_CLASS);
        $this->contexts->nestContext(Context::CONTEXT_NEW);
        $this->contexts->nestContext(Context::CONTEXT_FUNCTION);

        $previousContextVariables = $this->currentVariables;
        $this->currentVariables = array();

        $extras = array();
        // Process extends
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_EXTENDS) {
            $extendsKeyword = $this->tokens[$this->id + 1][1];
            ++$this->id; // Skip extends

            $this->checkPhpdoc();
            $extends = $this->processOneNsname(self::WITHOUT_FULLNSPATH);

            $this->addLink($class, $extends, 'EXTENDS');
            $this->getFullnspath($extends, 'class', $extends);
            $extras['EXTENDS'] = $extends;

            $this->calls->addCall('class', $extends->fullnspath, $extends);
        } else {
            $extends = '';
        }
        $this->checkPhpdoc();

        // Process implements
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_IMPLEMENTS) {
            $extras['IMPLEMENTS'] = array();

            $implementsKeyword = $this->tokens[$this->id + 1][1];
            $fullcodeImplements = array();
            do {
                ++$this->id; // Skip implements
                $this->checkPhpdoc();
                $implements = $this->processOneNsname(self::WITHOUT_FULLNSPATH);

                $this->addLink($class, $implements, 'IMPLEMENTS');
                $fullcodeImplements[] = $implements->fullcode;
                $extras['IMPLEMENTS'][] = $implements;

                $this->getFullnspath($implements, 'class', $implements);
                $this->calls->addCall('class', $implements->fullnspath, $implements);

            } while ($this->tokens[$this->id + 1][0] === $this->phptokens::T_COMMA);
        } else {
            $implements = '';
        }
        $this->checkPhpdoc();

        // Process block
        $this->makeCitBody($class);

        $this->runPlugins($class, $extras);

        $class->fullcode   = $this->tokens[$current][1] . ($class->atom === 'Classanonymous' ? '' : ' ' . $name->fullcode)
                             . (isset($argumentsFullcode) ? ' (' . $argumentsFullcode . ')' : '')
                             . (empty($extends) ? '' : ' ' . $extendsKeyword . ' ' . $extends->fullcode)
                             . (empty($implements) ? '' : ' ' . $implementsKeyword . ' ' . implode(', ', $fullcodeImplements))
                             . static::FULLCODE_BLOCK;

        $this->pushExpression($class, $extras);

        // Case of anonymous classes
        if ($this->tokens[$current - 1][0] !== $this->phptokens::T_NEW) {
            $this->processSemicolon();
        }

        $this->contexts->exitContext(Context::CONTEXT_CLASS);
        $this->contexts->exitContext(Context::CONTEXT_NEW);
        $this->contexts->exitContext(Context::CONTEXT_FUNCTION);

        array_pop($this->currentClassTrait);

        $this->currentVariables = $previousContextVariables;
        return $class;
    }

    private function processOpenTag(): AtomInterface {
        $current = $this->id;
        $phpcode = $this->addAtom('Php', $current);

        $this->startSequence();

        // Special case for pretty much empty script (<?php .... END)
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_END) {
            $void = $this->addAtomVoid();
            $this->addToSequence($void);

            $this->addLink($phpcode, $this->sequence, 'CODE');
            $this->endSequence();
            $closing = '';

            $phpcode->code       = $this->tokens[$current][1];
            $phpcode->close_tag  = self::NO_CLOSING_TAG;

            return $phpcode;
        }

        $n = count($this->tokens) - 2;
        if ($this->tokens[$n][0] === $this->phptokens::T_INLINE_HTML) {
            --$n;
        }

        while ($this->id < $n) {
            if ($this->tokens[$this->id][0] === $this->phptokens::T_OPEN_TAG_WITH_ECHO) {
                --$this->id;
                $echo = $this->processOpenWithEcho();
                /// processing the first expression as an echo
                $this->addToSequence($echo);
                if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_END) {
                    --$this->id;
                }
            } elseif ($this->tokens[$this->id][0] === $this->phptokens::T_CLOSE_TAG) {
                --$this->id;
            }
            $this->processNext();
        }

        if ($this->tokens[$this->id][0] === $this->phptokens::T_INLINE_HTML) {
            --$this->id;
        }

        if ($this->tokens[$this->id - 1][0] === $this->phptokens::T_CLOSE_TAG) {
            $closeTag = self::CLOSING_TAG;
            $closing = '?>';
        } elseif ($this->tokens[$this->id][0] === $this->phptokens::T_HALT_COMPILER) {
            $closeTag = self::NO_CLOSING_TAG;
            ++$this->id; // Go to HaltCompiler
            $this->processHalt();
            $closing = '';
        } else {
            $closeTag = self::NO_CLOSING_TAG;
            $closing = '';
        }

        if ($this->tokens[$this->id - 1][0] === $this->phptokens::T_OPEN_TAG) {
            $void = $this->addAtomVoid();
            $this->addToSequence($void);
        }
        $this->addLink($phpcode, $this->sequence, 'CODE');
        $this->endSequence();

        $phpcode->code         = $this->tokens[$current][1];
        $phpcode->fullcode     = '<?php ' . self::FULLCODE_SEQUENCE . ' ' . $closing;
        $phpcode->token        = $this->getToken($this->tokens[$current][0]);
        $phpcode->close_tag    = $closeTag;

        return $phpcode;
    }

    private function processSemicolon(): AtomInterface {
        $atom = $this->popExpression();
        $this->addToSequence($atom);

        return $atom;
    }

    private function processClosingTag(): AtomInterface {
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_INLINE_HTML &&
            in_array($this->tokens[$this->id + 2][0], array($this->phptokens::T_OPEN_TAG,
                                                            $this->phptokens::T_OPEN_TAG_WITH_ECHO,
                                                            $this->phptokens::T_INLINE_HTML,
                                                            ),
                     \STRICT_COMPARISON)) {

            // it is possible to have multiple INLINE_HTML in a row : <?php//b ? >
            do {
                ++$this->id;
                $return = $this->processInlinehtml();
                $this->addToSequence($return);
            } while( $this->tokens[$this->id + 1][0] === $this->phptokens::T_INLINE_HTML);

            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_TAG_WITH_ECHO) {
                $return = $this->processOpenWithEcho();
                if ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_SEMICOLON) {
                    $this->addToSequence($return);
                }
            } else {
                $return = $this->addAtomVoid();
                $this->addToSequence($return);

                ++$this->id; // set to opening tag
            }
        } elseif (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_OPEN_TAG,
                                                                  $this->phptokens::T_OPEN_TAG_WITH_ECHO,
                                                                  ),
                     \STRICT_COMPARISON)) {
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_TAG_WITH_ECHO) {

                $return = $this->processOpenWithEcho();
                if ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_SEMICOLON) {
                    $this->addToSequence($return);
                }
            } else {
                $return = $this->addAtomVoid();
                $this->addToSequence($return);

                ++$this->id; // set to opening tag
            }
        } else {
            ++$this->id;
            $return = $this->addAtomVoid();
        }

        return $return;
    }

    private function processOpenWithEcho(): AtomInterface {
        // Processing ECHO
        $echo = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);

        $noSequence = $this->contexts->isContext(Context::CONTEXT_NOSEQUENCE);
        if ($noSequence === false) {
            $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        }
        $functioncall = $this->processArguments('Echo',
                                                array($this->phptokens::T_SEMICOLON,
                                                      $this->phptokens::T_CLOSE_TAG,
                                                      $this->phptokens::T_END,
                                                      ));
        $argumentsFullcode = $functioncall->fullcode;

        if ($noSequence === false) {
            $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        }

        //processArguments goes too far, up to ;
        if ($this->tokens[$this->id][0] === $this->phptokens::T_CLOSE_TAG) {
            --$this->id;
        }

        $functioncall->code       = $echo->code;
        $functioncall->fullcode   = '<?= ' . $argumentsFullcode;
        $functioncall->token      = 'T_OPEN_TAG_WITH_ECHO';
        $functioncall->fullnspath = '\echo';

        $this->addLink($functioncall, $echo, 'NAME');

        return $functioncall;
    }

    private function makeNsname(): AtomInterface {
        if ($this->tokens[$this->id][0] === $this->phptokens::T_NAME_QUALIFIED) {
            $fullcode = array($this->tokens[$this->id][1]);
            $token = 'T_NAME_QUALIFIED';
            $absolute = self::NOT_ABSOLUTE;

            if ($this->contexts->isContext(Context::CONTEXT_NEW)) {
                $atom = 'Newcall';
            } else {
                $atom = 'Nsname';
            }
        } elseif ($this->tokens[$this->id][0] === $this->phptokens::T_NAME_FULLY_QUALIFIED) {
            $fullcode = array($this->tokens[$this->id][1]);
            $token = 'T_NAME_FULLY_QUALIFIED';
            $absolute = self::ABSOLUTE;

            if ($this->contexts->isContext(Context::CONTEXT_NEW)) {
                $atom = 'Newcall';
            } elseif (in_array(mb_strtolower($this->tokens[$this->id][1]), array('\\true', '\\false'), \STRICT_COMPARISON)) {
                $atom = 'Boolean';
            } elseif (in_array(mb_strtolower($this->tokens[$this->id][1]), array('\\null'), \STRICT_COMPARISON)) {
                $atom = 'Null';
            } else {
                $atom = 'Nsname';
            }
        } elseif ($this->tokens[$this->id][0] === $this->phptokens::T_NAME_RELATIVE) {
            $fullcode = array($this->tokens[$this->id][1]);
            $token = 'T_NAME_RELATIVE';
            $absolute = self::NOT_ABSOLUTE;

            if ($this->contexts->isContext(Context::CONTEXT_NEW)) {
                $atom = 'Newcall';
            } else {
                $atom = 'Nsname';
            }
        } else {
            $token = 'T_NS_SEPARATOR';

            if ($this->tokens[$this->id][0]     === $this->phptokens::T_NS_SEPARATOR                   &&
                $this->tokens[$this->id + 1][0] === $this->phptokens::T_STRING                         &&
                in_array(mb_strtolower($this->tokens[$this->id + 1][1]), array('true', 'false'), \STRICT_COMPARISON) &&
                $this->tokens[$this->id + 2][0] !== $this->phptokens::T_NS_SEPARATOR
                ) {
                $atom = 'Boolean';

            } elseif ($this->tokens[$this->id][0]     === $this->phptokens::T_NS_SEPARATOR &&
                      $this->tokens[$this->id + 1][0] === $this->phptokens::T_STRING       &&
                      mb_strtolower($this->tokens[$this->id + 1][1]) === 'null'            &&
                      $this->tokens[$this->id + 2][0] !== $this->phptokens::T_NS_SEPARATOR ) {

                $atom = 'Null';
            } elseif (mb_strtolower($this->tokens[$this->id][1]) === 'parent') {
                $atom = 'Parent';
            } elseif (mb_strtolower($this->tokens[$this->id][1]) === 'self') {
                $atom = 'Self';
            } elseif ($this->tokens[$this->id][0]     === $this->phptokens::T_NS_SEPARATOR &&
                      $this->tokens[$this->id + 1][0] === $this->phptokens::T_STRING       &&
                      mb_strtolower($this->tokens[$this->id + 1][1]) === 'self'            &&
                      $this->tokens[$this->id + 2][0] !== $this->phptokens::T_NS_SEPARATOR ) {

                $atom = 'Self';
            } elseif ($this->contexts->isContext(Context::CONTEXT_NEW)) {
                $atom = 'Newcall';
            } else {
                $atom = 'Nsname';
                $token = 'T_STRING';
            }

            $fullcode = array();

            if ($this->tokens[$this->id][0] === $this->phptokens::T_STRING) {
                $fullcode[] = $this->tokens[$this->id][1];
                ++$this->id;

                $absolute = self::NOT_ABSOLUTE;
            } elseif ($this->tokens[$this->id - 1][0] === $this->phptokens::T_NAMESPACE) {
                $fullcode[] = $this->tokens[$this->id - 1][1];

                $absolute = self::NOT_ABSOLUTE;
            } elseif ($this->tokens[$this->id][0] === $this->phptokens::T_NS_SEPARATOR) {
                $fullcode[] = '';

                $absolute = self::ABSOLUTE;
            } else {
                $fullcode[] = $this->tokens[$this->id][1];
                ++$this->id;

                $absolute = self::NOT_ABSOLUTE;
            }

            while ($this->tokens[$this->id][0]     === $this->phptokens::T_NS_SEPARATOR    &&
                   $this->tokens[$this->id + 1][0] !== $this->phptokens::T_OPEN_CURLY
                   ) {
                ++$this->id; // skip \
                $fullcode[] = $this->tokens[$this->id][1];

                // Go to next
                ++$this->id; // skip \
                $token = 'T_NS_SEPARATOR';
            }

            // Back up a bit
            --$this->id;
        }

        if ($this->contexts->isContext(Context::CONTEXT_NEW)) {
            if ($this->tokens[$this->id][0] === $this->phptokens::T_OPEN_PARENTHESIS) {
                $atom = 'Newcallname';
            } elseif ($this->tokens[$this->id][0] === $this->phptokens::T_DOUBLE_COLON) {
                // Finally, it is D::$D
                $atom = 'Identifier';
            }
        }

        $nsname = $this->addAtom($atom);
        $nsname->code     = implode('\\', $fullcode);
        $nsname->fullcode = $nsname->code;
        $nsname->token    = $token;
        $nsname->absolute = $absolute;
        $this->runPlugins($nsname);

        return $nsname;
    }

    private function processNsname(): AtomInterface {
        $nsname = $this->makeNsname();

        // Review this : most nsname will end up as constants!

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_DOUBLE_COLON ||
            $this->tokens[$this->id - 1][0] === $this->phptokens::T_INSTANCEOF   ||
            $this->tokens[$this->id + 1][0] === $this->phptokens::T_VARIABLE       ) {

            $this->getFullnspath($nsname, 'class', $nsname);

            $this->calls->addCall('class', $nsname->fullnspath, $nsname);
        } elseif ($this->contexts->isContext(Context::CONTEXT_NEW) &&
                  $this->tokens[$this->id + 1][0] !== $this->phptokens::T_OPEN_PARENTHESIS) {
            $this->getFullnspath($nsname, 'class', $nsname);
            $this->calls->addCall('class', $nsname->fullnspath, $nsname);

        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_PARENTHESIS) {
            // DO nothing

        } else {
            $this->calls->addCall('const', $nsname->fullnspath, $nsname);
        }

        $this->pushExpression($nsname);

        return $this->processFCOA($nsname);
    }

    private function processTypehint(AtomInterface $holder): string {
        $nonTypehintToken = array($this->phptokens::T_NS_SEPARATOR,
                                  $this->phptokens::T_STRING,
                                  $this->phptokens::T_NAMESPACE,
                                  $this->phptokens::T_ARRAY,
                                  $this->phptokens::T_CALLABLE,
                                  $this->phptokens::T_STATIC    ,
                                  $this->phptokens::T_QUESTION,
                                  $this->phptokens::T_NAME_QUALIFIED,
                                  $this->phptokens::T_NAME_RELATIVE,
                                  $this->phptokens::T_NAME_FULLY_QUALIFIED,
        );

        // return type allows for static. Not valid for arguments.
        if (in_array($holder->atom, array('Ppp', 'Parameter'), \STRICT_COMPARISON)) {
            $link = 'TYPEHINT';
        } else {
            $link = 'RETURNTYPE';
        }

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_COLON) {
            ++$this->id;
        }

        if (!in_array($this->tokens[$this->id + 1][0], $nonTypehintToken, \STRICT_COMPARISON)) {
            if ($this->tokens[$this->id + 1][0] === T_ELLIPSIS) {
                $typehint = $this->addAtom('Scalartypehint', $this->id + 1);
                $typehint->fullnspath = '\\array';
                $typehint->fullcode = '';
            } else {
                $typehint = $this->addAtomVoid();
            }

            $this->addLink($holder, $typehint, $link);
            return '';
        }

        $return = array();

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_QUESTION) {
            $null = $this->addAtom('Null');
            $null->code        = '?';
            $null->fullcode    = '?';
            $null->token       = $this->phptokens::T_STRING;
            $null->noDelimiter = '';
            $null->delimiter   = '';
            $null->fullnspath   = '\\null';

            $return[] = $null;
            ++$this->id;
        }

        --$this->id;
        do {
            ++$this->id;
            if (in_array(mb_strtolower($this->tokens[$this->id + 1][1]), $this->SCALAR_TYPE, \STRICT_COMPARISON) &&
                 $this->tokens[$this->id + 2][0] !== $this->phptokens::T_NS_SEPARATOR) {
                ++$this->id;
                $nsname = $this->processSingle('Scalartypehint');
                $nsname->fullnspath = '\\' . mb_strtolower($nsname->code);
            } elseif (mb_strtolower($this->tokens[$this->id + 1][1]) === 'null') {
                ++$this->id;
                $nsname = $this->processSingle('Null');
                $nsname->fullnspath = '\\null';
            } else {
                $nsname = $this->processOneNsname(self::WITHOUT_FULLNSPATH);
                $this->getFullnspath($nsname, 'class', $nsname);
                $this->calls->addCall('class', $nsname->fullnspath, $nsname);
                $this->runPlugins($nsname);
            }

            $return[] = $nsname;
        } while ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OR);

        if ($this->tokens[$this->id + 1][1] === ',') {
            ++$this->id;
        }

        $returnTypeFullcode = array();
        if ($return[0]->code === '?') {
            $this->addLink($holder, $return[0], $link);
            $this->addLink($holder, $return[1], $link);

            $return[0]->rank = 0;
            $return[1]->rank = 1;

            $returnTypeFullcode = '?' . $return[1]->fullcode;
        } else {
            $rank = -1;
            foreach($return as $returnType) {
                $this->addLink($holder, $returnType, $link);
                $returnType->rank = ++$rank;

                if (!$returnType->isA(array('Void'))) {
                    $returnTypeFullcode[] = $returnType->fullcode;
                } elseif ($returnType->code === '?') {
                    array_unshift('?', $returnTypeFullcode);
                    $returnTypeFullcode = array_values($returnTypeFullcode);
                }
            }
            $returnTypeFullcode = implode('|', $returnTypeFullcode);
        }

        switch($link) {
            case 'RETURNTYPE':
                $returnTypeFullcode = ' : ' . $returnTypeFullcode;
                break;

            case 'TYPEHINT':
                $returnTypeFullcode .= ' ';
                break;

            default:
                die(__METHOD__);
        }

        return $returnTypeFullcode;
    }

    private function processParameters(string $atom): AtomInterface {
        $current = $this->id;
        $arguments = $this->addAtom($atom, $current);
        $this->makeAttributes($arguments);

        $this->currentFunction[] = $arguments;
        $this->currentMethod[]   = $arguments;

        $argumentsList  = array();

        $this->checkAttribute();
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_PARENTHESIS) {
            $void = $this->addAtomVoid();
            $void->rank = 0;
            $this->addLink($arguments, $void, 'ARGUMENT');

            $arguments->code     = $this->tokens[$current][1];
            $arguments->fullcode = self::FULLCODE_VOID;
            $arguments->token    = $this->getToken($this->tokens[$current][0]);
            $arguments->args_max = 0;
            $arguments->args_min = 0;
            $arguments->count    = 0;

            $this->runPlugins($arguments, array($void));

            $argumentsList[] = $void;

            // Skip the )
            ++$this->id;
            return $arguments;
        }

        $fullcode       = array();
        $argsMax        = 0;
        $argsMin        = 0;
        $rank       = -1;
        $default    = 0;
        $variadic   = self::NOT_ELLIPSIS;

        do {
            do {
                $this->checkPhpdoc();
                $this->checkAttribute();

                // PHP 8.0's trailing comma in signature
                if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_PARENTHESIS) {
                    $fullcode[] = ' ';
                    ++$this->id;
                    break 1;
                }

                $this->checkAttribute();

                ++$argsMax;
                if (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_PUBLIC,
                                                                    $this->phptokens::T_PRIVATE,
                                                                    $this->phptokens::T_PROTECTED,
                    ), \STRICT_COMPARISON)
                ) {
                    ++$this->id;
                    $index = $this->processPPP(self::PROMOTED);

                    ++$this->id;

                    $this->addLink(end($this->currentClassTrait), $index, 'PPP');

                    $index->rank = ++$rank;
                    $this->popExpression();
                    $fullcode[] = $index->fullcode;
                    $this->addLink($arguments, $index, 'ARGUMENT');
                    $argumentsList[] = $index;

                    continue;
                }

                $index = $this->addAtom('Parameter');
                $variable = $this->addAtom('Parametername');
                $typehints = $this->processTypehint($index);
                $this->makeAttributes($index);
                $this->makePhpdoc($index);
                ++$this->id;

                if ($this->tokens[$this->id][0] === $this->phptokens::T_AND) {
                    $reference = self::REFERENCE;
                    ++$this->id;
                } else {
                    $reference = self::NOT_REFERENCE;
                }

                if ($this->tokens[$this->id][0] === $this->phptokens::T_ELLIPSIS) {
                    $variadic = self::ELLIPSIS;
                    ++$this->id;
                }

                $variable->code     = $this->tokens[$this->id][1];
                $variable->fullcode = $this->tokens[$this->id][1];
                $variable->token    = $this->getToken($this->tokens[$this->id][0]);
                $this->runPlugins($variable);

                $index->code     = $variable->fullcode;
                $index->fullcode = $variable->fullcode;
                $index->token    = 'T_VARIABLE';

                if ($variadic === self::ELLIPSIS) {
                    $index->fullcode  = '...' . $index->fullcode;
                    $index->variadic = self::ELLIPSIS;
                }

                if ($reference === self::REFERENCE) {
                    $index->fullcode  = '&' . $index->fullcode;
                    $index->reference = self::REFERENCE;
                }

                $this->addLink($index, $variable, 'NAME');
                $this->currentVariables[$variable->code] = $variable;

                if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_EQUAL) {
                    ++$this->id; // Skip =
                    $default = $this->processExpression(array($this->phptokens::T_COMMA,
                                                              $this->phptokens::T_CLOSE_PARENTHESIS,
                                                              $this->phptokens::T_CLOSE_CURLY,
                                                              $this->phptokens::T_SEMICOLON,
                                                              $this->phptokens::T_CLOSE_BRACKET,
                                                              $this->phptokens::T_CLOSE_TAG,
                                                              $this->phptokens::T_COLON,
                                                              ));
                } else {
                    if ($index->variadic === self::ELLIPSIS) {
                        $argsMax = \MAX_ARGS;
                    } else {
                        ++$argsMin;
                    }
                    $default = $this->addAtomVoid();
                }
                $this->addLink($index, $default, 'DEFAULT');
                if ($default->atom !== 'Void') {
                    $index->fullcode .= ' = ' . $default->fullcode;

                    // When Null is default, then typehint is also nullable
                    if ($default->atom === 'Null' &&
                        strpos($typehints, '?') === false &&
                        preg_match('/\bnull\b/i', $typehints) === 0
                        ) {
                        $this->addLink($index, $default, 'TYPEHINT');
                    }
                }

                $index->rank = ++$rank;

                $index->fullcode = $typehints . $index->fullcode;
                $fullcode[] = $index->fullcode;
                $this->addLink($arguments, $index, 'ARGUMENT');
                $argumentsList[] = $index;

                ++$this->id;
            } while ($this->tokens[$this->id][0] === $this->phptokens::T_COMMA);

            --$this->id;
        } while ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_CLOSE_PARENTHESIS);
        $arguments->count    = $rank + 1;

        // Skip the )
        ++$this->id;

        $arguments->fullcode = implode(', ', $fullcode);
        $arguments->token    = 'T_COMMA';
        $arguments->args_max = $argsMax;
        $arguments->args_min = $argsMin;
        $this->runPlugins($arguments, $argumentsList);

        return $arguments;
    }

    private function processArguments(string $atom,array $finals = array(), array &$argumentsList = array()): AtomInterface {
        if (empty($finals)) {
            $finals = array($this->phptokens::T_CLOSE_PARENTHESIS);
        }
        $current = $this->id;
        $arguments = $this->addAtom($atom, $current);
        $argumentsId = array();

        $this->contexts->nestContext(Context::CONTEXT_NEW);
        $this->contexts->nestContext(Context::CONTEXT_NOSEQUENCE);
        $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        $fullcode = array();

        if (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_PARENTHESIS,
                                                            $this->phptokens::T_CLOSE_BRACKET,
                                                            ),
                     \STRICT_COMPARISON)) {
            $void = $this->addAtomVoid();
            $void->rank = 0;
            $this->addLink($arguments, $void, 'ARGUMENT');

            $arguments->code     = $this->tokens[$current][1];
            $arguments->fullcode = self::FULLCODE_VOID;
            $arguments->token    = $this->getToken($this->tokens[$current][0]);
            $arguments->args_max = 0;
            $arguments->args_min = 0;
            $arguments->count    = 0;
            $argumentsId[]       = $void;

            $argumentsList = array($void);
            $this->runPlugins($arguments, $argumentsList);

            ++$this->id;
        } else {
            $index      = 0;
            $argsMax    = 0;
            $argsMin    = 0;
            $rank       = -1;
            $rankName  = '';
            $argumentsList  = array();

            while (!in_array($this->tokens[$this->id + 1][0], $finals, \STRICT_COMPARISON)) {
                $initialId = $this->id;
                ++$argsMax;

                // named parameters PHP 8.0
                if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_STRING &&
                    $this->tokens[$this->id + 2][0] === $this->phptokens::T_COLON ) {
                    ++$this->id;
                    $rankName = $this->tokens[$this->id][1];
                    ++$this->id; // skip :
                }

                while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_COMMA,
                                                                        $this->phptokens::T_CLOSE_PARENTHESIS,
                                                                        $this->phptokens::T_CLOSE_CURLY,
                                                                        $this->phptokens::T_SEMICOLON,
                                                                        $this->phptokens::T_CLOSE_BRACKET,
                                                                        $this->phptokens::T_CLOSE_TAG,
                                                                        $this->phptokens::T_COLON,
                                                                        ),
                                \STRICT_COMPARISON)) {
                    $index = $this->processNext();
                }
                $this->popExpression();
                if (!empty($rankName)) {
                    $index->rankName = '$' . $rankName;
                    $rank_name = '';
                    $index->fullcode = $rankName . ' : ' . $index->fullcode;
                }

                while ($this->tokens[$this->id + 1][0] === $this->phptokens::T_COMMA) {
                    if ($index === 0) {
                        $index = $this->addAtomVoid();
                    }

                    $index->rank = ++$rank;

                    $this->addLink($arguments, $index, 'ARGUMENT');
                    $argumentsId[] = $index;
                    // array($this, 'b'); for Callback syntax.
                    if ($index->atom === 'Variable' &&
                        $index->code === '$this'    &&
                        $index->rank === 0 ) {
                        $this->calls->addCall('class', end($this->currentClassTrait)->fullnspath, $index);
                    }

                    $fullcode[] = $index->fullcode;
                    $argumentsList[] = $index;

                    ++$this->id; // Skipping the comma ,
                    $index = 0;
                }

                if ($initialId === $this->id) {
                    throw new NoFileToProcess($this->filename, 'not processable with the current code');
                }
            }

            if ($index === 0) {
                if ($atom === 'List') {
                    $index = $this->addAtomVoid();

                    $index->rank = ++$rank;
                    $argumentsId[] = $index;
                    $this->argumentsId = $argumentsId; // This avoid overwriting when nesting functioncall

                    $this->addLink($arguments, $index, 'ARGUMENT');

                    $fullcode[] = $index->fullcode;
                    $argumentsList[] = $index;
                } else {
                    $fullcode[] = ' ';
                }
            } else {
                $index->rank = ++$rank;
                $argumentsId[] = $index;
                $this->argumentsId = $argumentsId; // This avoid overwriting when nesting functioncall

                $this->addLink($arguments, $index, 'ARGUMENT');

                $fullcode[] = $index->fullcode;
                $argumentsList[] = $index;
            }

            // Skip the )
            ++$this->id;

            $arguments->fullcode = implode(', ', $fullcode);
            $arguments->token    = 'T_COMMA';
            $arguments->count    = $rank + 1;
            $arguments->args_max = $argsMax;
            $arguments->args_min = $argsMin;
            $this->runPlugins($arguments, $argumentsList);
        }

        $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);
        $this->contexts->exitContext(Context::CONTEXT_NEW);

        return $arguments;
    }

    private function processNextAsIdentifier(bool $getFullnspath = self::WITH_FULLNSPATH): AtomInterface {
        ++$this->id;

        $identifier = $this->addAtom($getFullnspath === self::WITH_FULLNSPATH ? 'Identifier' : 'Name', $this->id);
        $identifier->fullcode   = $this->tokens[$this->id][1];

        if ($getFullnspath === self::WITH_FULLNSPATH) {
            $this->getFullnspath($identifier, 'const', $identifier);
        }
        $this->runPlugins($identifier);

        return $identifier;
    }

    private function processConst(): AtomInterface {
        $current = $this->id;
        $const = $this->addAtom('Const', $current);
        $this->makePhpdoc($const);
        $this->makeAttributes($const);

        $rank = -1;
        --$this->id; // back one step for the init in the next loop

        if (empty($const->visibility)) {
            $const->visibility = 'none';
        }

        $fullcode = array();
        do {
            ++$this->id;
            $constId = $this->id;
            $this->checkPhpdoc();
            $name = $this->processNextAsIdentifier();

            ++$this->id; // Skip =
            $value = $this->processExpression(array($this->phptokens::T_SEMICOLON,
                                                    $this->phptokens::T_COMMA,
                                                    $this->phptokens::T_DOC_COMMENT,
                                                    ));

            $def = $this->addAtom('Constant', $constId);
            $this->addLink($def, $name, 'NAME');
            $this->addLink($def, $value, 'VALUE');

            $def->fullcode = $name->fullcode . ' = ' . $value->fullcode;
            $def->rank     = ++$rank;

            $fullcode[] = $def->fullcode;
            $this->runPlugins($def, array('VALUE' => $value,
                                          'NAME'  => $name,
                                          ));

            $this->getFullnspath($name, 'const', $name);

            $this->addLink($const, $def, 'CONST');

            if ($this->contexts->isContext(Context::CONTEXT_CLASS)) {
                $this->calls->addDefinition('staticconstant',   end($this->currentClassTrait)->fullnspath . '::' . $name->fullcode, $def);
            } else {
                $this->calls->addDefinition('const', $name->fullnspath, $def);
            }
            $this->makePhpdoc($def);
            $this->checkPhpdoc();
        } while ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_SEMICOLON);

        $const->fullcode = $this->tokens[$current][1] . ' ' . implode(', ', $fullcode);
        $const->count    = $rank + 1;

        $this->pushExpression($const);

        return $this->processFCOA($const);
    }

    private function processAbstract(): AtomInterface {
        $abstract = $this->tokens[$this->id][1];

        $next = $this->processNext();

        $next->abstract = true;
        $next->fullcode = "$abstract $next->fullcode";
        $this->makePhpdoc($next);

        return $next;
    }

    private function processFinal(): AtomInterface {
        $final = $this->tokens[$this->id][1];

        $next = $this->processNext();

        $next->final    = true;
        $next->fullcode = "$final $next->fullcode";
        $this->makePhpdoc($next);

        return $next;
    }

    private function processVar(): AtomInterface {
        $current = $this->id;
        $visibility = $this->tokens[$this->id][1];
        $ppp = $this->addAtom('Ppp', $current);
        $returnTypes = $this->processTypehint($ppp);

        $this->processSGVariable($ppp);

        $ppp->visibility = 'none';
        $ppp->fullcode   = "$visibility {$returnTypes}$ppp->fullcode";
        $this->makePhpdoc($ppp);

        return $ppp;
    }

    private function processPPP(bool $promoted = self::PROMOTED_NOT): AtomInterface {
        $current = $this->id;
        $visibility = $this->tokens[$this->id][1];

        if (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_STATIC,
                                                            $this->phptokens::T_FUNCTION,
                                                            $this->phptokens::T_FINAL,
                                                            $this->phptokens::T_ABSTRACT,
                                                            $this->phptokens::T_CONST,
                                                           ),
                     \STRICT_COMPARISON)) {
            $ppp = $this->processNext();
            $this->makePhpdoc($ppp);
            $returnTypes = '';
        } else {
            $ppp = $this->addAtom('Ppp', $current);
            $this->makePhpdoc($ppp);
            $returnTypes = $this->processTypehint($ppp);

            $this->processSGVariable($ppp, $promoted);
        }

        $ppp->visibility = strtolower($visibility);
        $ppp->fullcode   = "$visibility {$returnTypes}$ppp->fullcode";
        $this->makeAttributes($ppp);

        return $ppp;
    }

    private function processDefineConstant(AtomInterface $namecall): AtomInterface {
        $namecall->atom = 'Defineconstant';
        $namecall->fullnspath = '\\define';
        $this->makePhpdoc($namecall);

        // Empty call
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_PARENTHESIS) {

            $namecall->fullcode   = $namecall->code . '( )';
            $this->pushExpression($namecall);

            $this->runPlugins($namecall, array());
            ++$this->id; // Skip )

            $this->checkExpression();
            return $namecall;
        }

        // First argument : constant name
        ++$this->id;
        if ($this->tokens[$this->id][0]     === $this->phptokens::T_CONSTANT_ENCAPSED_STRING &&
            $this->tokens[$this->id + 1][0] === $this->phptokens::T_COMMA
            ) {
            $name = $this->processSingle('Identifier');
            $this->runPlugins($name);
            $name->delimiter   = $name->code[0];
            if (strtolower($name->delimiter) === 'b') {
                $name->binaryString = $name->delimiter;
                $name->delimiter    = $name->code[1];
                $name->noDelimiter  = substr($name->code, 2, -1);
            } else {
                $name->noDelimiter = substr($name->code, 1, -1);
            }
            $this->getFullnspath($name, 'const', $name);

            if (function_exists('mb_detect_encoding')) {
                $name->encoding = mb_detect_encoding($name->noDelimiter);
                if ($name->encoding === 'UTF-8') {
                    $blocks = unicode_blocks($name->noDelimiter);
                    $name->block = array_keys($blocks)[0];
                }
                if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_BRACKET) {
                    $name = $this->processBracket();
                }
            }
        } else {
            // back one step
            --$this->id;
            $name = $this->processExpression(array($this->phptokens::T_COMMA,
                                                                       $this->phptokens::T_CLOSE_PARENTHESIS // In case of missing arguments...
                                                                      ));
        }
        $this->addLink($namecall, $name, 'NAME');

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_PARENTHESIS) {
            $namecall->fullcode   = "{$namecall->code}({$name->code})";
            $this->pushExpression($namecall);

            $this->runPlugins($namecall, array('NAME'  => $name, ));
            ++$this->id; // Skip )

            $this->checkExpression();
            return $namecall;
        }

        // Second argument constant value
        ++$this->id; // Skip ,
        $value = $this->processExpression(array($this->phptokens::T_COMMA,
                                                $this->phptokens::T_CLOSE_PARENTHESIS // In case of missing arguments...
                                               ));
        $this->addLink($namecall, $value, 'VALUE');

        // Most common point of exit
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_PARENTHESIS) {
            $namecall->fullcode   = "{$namecall->code}({$name->fullcode}, {$value->fullcode})";
            $this->pushExpression($namecall);

            $this->runPlugins($namecall, array('NAME'  => $name,
                                               'VALUE' => $value,
                                               ));
            ++$this->id; // Skip )

            $this->processDefineAsConstants($namecall, $name, self::CASE_INSENSITIVE);

            $this->checkExpression();
            return $namecall;
        }

        // Third argument : case sensitive
        ++$this->id; // Skip ,
        $case = $this->processExpression(array($this->phptokens::T_COMMA,
                                               $this->phptokens::T_CLOSE_PARENTHESIS // In case of missing arguments...
                                              ));
        $this->addLink($namecall, $case, 'CASE');

        $this->processDefineAsConstants($namecall, $name, (bool) $case->boolean);

        $namecall->fullcode   = $namecall->code . '(' . $name->fullcode . ', ' . $value->fullcode . ', ' . $case->fullcode . ')';
        $this->pushExpression($namecall);

        $this->runPlugins($namecall, array('NAME'  => $name,
                                           'VALUE' => $value,
                                           'CASE'  => $case,
                                           ));

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_PARENTHESIS) {
            ++$this->id; // Skip )

            $this->checkExpression();
            return $namecall;
        }

        // Ignore everything else
        $parenthese = 1;
        while ($parenthese > 0) {
            ++$this->id;

            if ($this->tokens[$this->id][0] === $this->phptokens::T_CLOSE_PARENTHESIS) {
                --$parenthese;
            } elseif ($this->tokens[$this->id][0] === $this->phptokens::T_OPEN_PARENTHESIS) {
                ++$parenthese;
            }
        }

        $this->checkExpression();
        return $namecall;
    }

    private function processFunctioncall(bool $getFullnspath = self::WITH_FULLNSPATH): AtomInterface {
        $name = $this->popExpression();
        ++$this->id; // Skipping the name, set on (

        if ($this->contexts->isContext(Context::CONTEXT_NEW)) {
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_DOUBLE_COLON) {
                $atom = 'Identifier';
            } else {
                $atom = 'Newcall';
            }
        } elseif ($getFullnspath === self::WITH_FULLNSPATH) {
            if (strtolower($name->code) === '\\define') {
                return $this->processDefineConstant($name);
            } elseif (strtolower($name->code) === 'define') {
                return $this->processDefineConstant($name);
            } elseif (strtolower($name->code) === '\\class_alias') {
                $atom = 'Classalias';
            } elseif (strtolower($name->code) === 'class_alias') {
                $atom = 'Classalias';
            } elseif ($name->fullnspath === '\\list') {
                $atom = 'List';
            } else {
                $atom = 'Functioncall';
            }
        } else {
            $atom = 'Methodcallname';
        }

        $argumentsList = array();
        $functioncall = $this->processArguments($atom, array($this->phptokens::T_CLOSE_PARENTHESIS), $argumentsList);
        $argumentsFullcode       = $functioncall->fullcode;

        $functioncall->code      = $name->code;
        $functioncall->fullcode  = "{$name->fullcode}({$argumentsFullcode})";
        $functioncall->token     = $name->token;

        if ($atom === 'Newcall') {
            $this->getFullnspath($name, 'class', $functioncall);

            $this->calls->addCall('class', $functioncall->fullnspath, $functioncall);
        } elseif ($atom === 'Classalias') {
            $functioncall->fullnspath = '\\classalias';

            $this->processDefineAsClassalias($argumentsList);
        } elseif (in_array($atom, array('Methodcallname', 'List'), \STRICT_COMPARISON)) {
            // literally, nothing
        } elseif (in_array(mb_strtolower($name->code), array('defined', 'constant'), \STRICT_COMPARISON)) {

            if ($argumentsList[0]->constant === true &&
                !empty($argumentsList[0]->noDelimiter   )) {

                $fullnspath = makeFullNsPath($argumentsList[0]->noDelimiter, \FNP_CONSTANT);
                if ($argumentsList[0]->noDelimiter[0] === '\\') {
                    $fullnspath = "\\$fullnspath";
                }
                $argumentsList[0]->fullnspath = $fullnspath;
                $this->calls->addCall(strpos($fullnspath, '::') === false ? 'const' : 'staticconstant', $fullnspath, $argumentsList[0]);
            }

            $functioncall->fullnspath = '\\' . mb_strtolower($name->code);

        } elseif ($getFullnspath === self::WITH_FULLNSPATH) { // A functioncall
            $this->getFullnspath($name, 'function', $functioncall);
            $functioncall->absolute   = $name->absolute;

            $this->calls->addCall('function', $functioncall->fullnspath, $functioncall);
        } else {
            throw new LoadError("Unprocessed atom in functioncall definition (its name) : $atom->atom : $this->filename : " . __LINE__);
        }

        $this->addLink($functioncall, $name, 'NAME');
        if ($name->atom === 'Name') {
            $this->runPlugins($name);
        }
        $this->pushExpression($functioncall);

        if ( $functioncall->atom === 'Methodcallname') {
            $argumentsList[] = $name;
            $this->runPlugins($functioncall, $argumentsList);
        } elseif ( !$this->contexts->isContext(Context::CONTEXT_NOSEQUENCE) &&
                   $this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG &&
                   $getFullnspath === self::WITH_FULLNSPATH ) {
             $this->processSemicolon();
        } else {
            $argumentsList[] = $name;
            $this->runPlugins($functioncall, $argumentsList);
            $functioncall = $this->processFCOA($functioncall);
        }

        return $functioncall;
    }

    private function processString(): AtomInterface {
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_NS_SEPARATOR ) {
            $nsname = $this->processNsname();
            $this->runPlugins($nsname);
            return $this->processFCOA($nsname);
        } elseif (in_array($this->tokens[$this->id][0], array($this->phptokens::T_NAME_QUALIFIED,
                                                              $this->phptokens::T_NAME_RELATIVE,
                                                              $this->phptokens::T_NAME_FULLY_QUALIFIED,
                                                              ), \STRICT_COMPARISON )) {
            $nsname = $this->processNsname();
            $this->runPlugins($nsname);
            return $this->processFCOA($nsname);
        } elseif (in_array($this->tokens[$this->id - 1][0], array($this->phptokens::T_SEMICOLON,
                                                                  $this->phptokens::T_OPEN_CURLY,
                                                                  $this->phptokens::T_CLOSE_CURLY,
                                                                  $this->phptokens::T_COLON,
                                                                  $this->phptokens::T_OPEN_TAG,
                                                                  $this->phptokens::T_DOC_COMMENT,
                                                                  ),
                    \STRICT_COMPARISON) &&
                   $this->tokens[$this->id + 1][0] === $this->phptokens::T_COLON       ) {
            return $this->processColon();
        } elseif (mb_strtolower($this->tokens[$this->id][1]) === 'self') {
            $string = $this->addAtom('Self', $this->id);
        } elseif (mb_strtolower($this->tokens[$this->id][1]) === 'parent') {
            $string = $this->addAtom('Parent', $this->id);
        } elseif (mb_strtolower($this->tokens[$this->id][1]) === 'list') {
            $string = $this->addAtom('Name', $this->id);
            $string->fullnspath = '\\list';
        } elseif ($this->contexts->isContext(Context::CONTEXT_NEW)) {
            // This catchs new A and new A()
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_PARENTHESIS ) {
                $string = $this->addAtom('Newcallname', $this->id);
            } else {
                $string = $this->addAtom('Newcall', $this->id);
            }
            $this->runPlugins($string);
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_PARENTHESIS ) {
            $string = $this->addAtom('Name', $this->id);
         } elseif (in_array(mb_strtolower($this->tokens[$this->id][1]), array('true', 'false'), \STRICT_COMPARISON)) {
            $string = $this->addAtom('Boolean', $this->id);

            $string->noDelimiter = mb_strtolower($string->code) === 'true' ? 1 : '';
            $string->fullnspath = '\\' . mb_strtolower($string->code);
        } elseif (mb_strtolower($this->tokens[$this->id][1]) === 'null') {
            $string = $this->addAtom('Null', $this->id);
            $string->fullnspath = '\\null';
        } else {
            $string = $this->addAtom('Identifier', $this->id);
        }

        $string->fullcode   = $this->tokens[$this->id][1];
        $string->absolute   = self::NOT_ABSOLUTE;

        $this->pushExpression($string);

        if ($string->isA(array('Parent', 'Self', 'Static', 'Newcall'))) {
            if ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_OPEN_PARENTHESIS) {
                $this->getFullnspath($string, 'class', $string);

                $this->calls->addCall('class', $string->fullnspath, $string);
            }

            if ($this->contexts->isContext(Context::CONTEXT_NEW)) {
                $string->count = 0;
            }
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_DOUBLE_COLON ||
                  $this->tokens[$this->id - 1][0] === $this->phptokens::T_INSTANCEOF   ||
                  $this->tokens[$this->id - 1][0] === $this->phptokens::T_NEW
            ) {
            if ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_OPEN_PARENTHESIS) {
                $this->calls->addCall('class', $string->fullnspath, $string);
            }
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_PARENTHESIS) {
            // Nothing to do
        } else {
            $this->calls->addCall('const', $string->fullnspath, $string);
        }

        $this->runPlugins($string);

        if ( !$this->contexts->isContext(Context::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG) {
            $this->processSemicolon();
        } else {
            $string = $this->processFCOA($string);
        }


        return $string;
    }

    private function processPostPlusplus(AtomInterface $previous): AtomInterface {
        ++$this->id;
        $this->popExpression();
        $plusplus = $this->addAtom('Postplusplus', $this->id);

        $this->addLink($plusplus, $previous, 'POSTPLUSPLUS');

        $plusplus->fullcode = $previous->fullcode . $this->tokens[$this->id][1];

        $this->pushExpression($plusplus);
        $this->runPlugins($plusplus, array('POSTPLUSPLUS' => $previous));

        $this->checkExpression();

        return $plusplus;
    }

    private function processPrePlusplus(): AtomInterface {
        $operator = $this->addAtom('Preplusplus', $this->id);
        $this->processSingleOperator($operator, $this->precedence->get($this->tokens[$this->id][0]), 'PREPLUSPLUS');
        $operator = $this->popExpression();
        $this->pushExpression($operator);

        $this->checkExpression();

        return $operator;
    }

    private function processStatic(): AtomInterface {
        $this->checkPhpdoc();
        $current = $this->id;
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_DOUBLE_COLON ||
            $this->tokens[$this->id - 1][0] === $this->phptokens::T_INSTANCEOF    ) {

            $identifier = $this->processSingle('Static');
            $this->pushExpression($identifier);
            $this->getFullnspath($identifier, 'class', $identifier);
            $this->calls->addCall('class', $identifier->fullnspath, $identifier);

            return $identifier;
        }

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_PARENTHESIS ) {
            $name = $this->addAtom('Static', $this->id);
            $name->fullcode   = $this->tokens[$this->id][1];

            $this->getFullnspath($name, 'class', $name);

            $this->pushExpression($name);

            return $this->processFunctioncall();
         }

         if (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_NS_SEPARATOR,
                                                             $this->phptokens::T_QUESTION,
                                                             $this->phptokens::T_STRING,
                                                             $this->phptokens::T_NAMESPACE,
                                                             $this->phptokens::T_ARRAY,
                                                             $this->phptokens::T_CALLABLE,
                                                             $this->phptokens::T_NAME_QUALIFIED,
                                                             $this->phptokens::T_NAME_RELATIVE,
                                                             $this->phptokens::T_NAME_FULLY_QUALIFIED,
                                                             ),
                            \STRICT_COMPARISON)) {
            $current = $this->id;
            $option = $this->tokens[$this->id][1];

            $ppp = $this->addAtom('Ppp', $current);
            $returnTypes = $this->processTypehint($ppp);

            $this->processSGVariable($ppp);

            $ppp->static = true;
            $ppp->visibility = 'none';
            $ppp->fullcode   = "$option {$returnTypes}$ppp->fullcode";
            $this->makePhpdoc($ppp);

            return $ppp;
        }

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_VARIABLE) {
            if ($this->contexts->isContext(Context::CONTEXT_CLASS) &&
                !$this->contexts->isContext(Context::CONTEXT_FUNCTION)) {

                // something like public static
                $option = $this->tokens[$this->id][1];

                $ppp = $this->addAtom('Ppp', $current);
                $this->processSGVariable($ppp);

                $void = $this->addAtomVoid();
                $this->addLink($ppp, $void, 'TYPEHINT');

                if (empty($ppp->visibility)) {
                    $ppp->visibility = 'none';
                }
                $this->popExpression();

                $ppp->static = true;
                $ppp->fullcode = "$option $ppp->fullcode";

                return $ppp;
            } else {
                $ppp = $this->processStaticVariable();
                $void = $this->addAtomVoid();
                $this->addLink($ppp, $void, 'TYPEHINT');

                return $ppp;
            }
        }

        if ($this->contexts->isContext(Context::CONTEXT_NEW)) {
            // new static;
            $name = $this->addAtom('Newcall', $this->id);
            $name->fullcode   = $this->tokens[$this->id][1];
            $name->count      = 0;

            $this->getFullnspath($name, 'class', $name);

            $this->calls->addCall('class', $name->fullnspath, $name);

            $this->pushExpression($name);
            return $name;
        }

        $static = $this->tokens[$this->id][1];

        $next = $this->processNext();

        $next->static   = true;
        $next->fullcode = "$static $next->fullcode";
        $this->makePhpdoc($next);
        return $next;
    }

    private function processSGVariable(AtomInterface $static, bool $promoted = self::PROMOTED_NOT): void {
        $current = $this->id;
        $rank = 0;

        $this->makePhpdoc($static);
        if (in_array($static->atom, array('Global', 'Static'), \STRICT_COMPARISON)) {
            $fullcodePrefix = $this->tokens[$this->id][1];
            $link = strtoupper($static->atom);
            $atom = $static->atom . 'definition';
        } else {
            $fullcodePrefix= array();
            $link = 'PPP';
            $atom = 'Propertydefinition';

            if (!isset($static->visibility)) {
                $static->visibility = 'none';
            }
            $fullcodePrefix = implode(' ', $fullcodePrefix);
        }

        if (!isset($fullcodePrefix)) {
            $fullcodePrefix = $this->tokens[$current][1];
        }

        $finals = array($this->phptokens::T_SEMICOLON,
                        $this->phptokens::T_CLOSE_TAG,
                        $this->phptokens::T_CLOSE_PARENTHESIS,
                        );
        // This is only for promoted properties. Only one definition per PPP
        if ($promoted === self::PROMOTED) {
            $finals[] = $this->phptokens::T_COMMA;
        }

        $fullcode = array();
        $extras = array();
        --$this->id;
        do {
            ++$this->id;
            $this->checkPhpdoc();
            if ($this->tokens[$this->id][0] === $this->phptokens::T_AND) {
                $reference = self::REFERENCE;
                ++$this->id;
            } else {
                $reference = self::NOT_REFERENCE;
            }

            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_VARIABLE) {
                ++$this->id;
                if (isset($this->currentVariables[$this->tokens[$this->id][1]])) {
                    $element = $this->currentVariables[$this->tokens[$this->id][1]];
                } else {
                    $element = $this->processSingle($atom);
                }
                $this->makePhpdoc($element);

                if ($element->isA(array('Globaldefinition', 'Staticdefinition', 'Variabledefinition')) &&
                    !isset($this->currentVariables[$element->code])) {
                    $this->addLink($this->currentMethod[count($this->currentMethod) - 1], $element, 'DEFINITION');
                    $this->currentVariables[$element->code] = $element;
                }

                if ($element->atom === 'Globaldefinition') {
                    $this->makeGlobal($element);

                    $this->calls->addGlobal($this->theGlobals[$element->code]->id, $element->id);
                }

                if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_EQUAL) {
                    ++$this->id;
                    $default = $this->processExpression(array($this->phptokens::T_SEMICOLON,
                                                              $this->phptokens::T_CLOSE_TAG,
                                                              $this->phptokens::T_COMMA,
                                                              $this->phptokens::T_CLOSE_PARENTHESIS,
                                                              $this->phptokens::T_DOC_COMMENT,
                                                              ));
                } else {
                    $default = $this->addAtomVoid();
                }
            } else {
                // global $a[2] = 2 ?
               $element = $this->processExpression(array($this->phptokens::T_SEMICOLON,
                                                         $this->phptokens::T_CLOSE_TAG,
                                                         $this->phptokens::T_COMMA,
                                                         $this->phptokens::T_DOC_COMMENT,
                                                         ));
                $this->makePhpdoc($element);
                $this->popExpression();
                $default = $this->addAtomVoid();
            }

            if ($reference === self::REFERENCE) {
                $element->fullcode  = '&' . $element->fullcode;
                $element->reference = self::REFERENCE;
            }

            $element->rank = ++$rank;
            $this->addLink($static, $element, $link);

            if ($atom === 'Propertydefinition') {
                // drop $
                $element->propertyname = ltrim($element->code, '$');
                $this->currentProperties[$element->propertyname] = $element;

                $currentFNP = $this->currentClassTrait[count($this->currentClassTrait) - 1]->fullnspath;
                $this->calls->addDefinition('staticproperty', $currentFNP . '::' . $element->code, $element);
                $this->calls->addDefinition('property', $currentFNP . '::' . ltrim($element->code, '$'), $element);
            }

            $this->addLink($element, $default, 'DEFAULT');
            if ($default->atom !== 'Void') {
                $element->fullcode .= " = {$default->fullcode}";
                $this->runPlugins($element, array('DEFAULT' => $default));
            } else {
                $this->runPlugins($element);
            }
            $fullcode[] = $element->fullcode;
            $extras[] = $element;
            $this->checkPhpdoc();
        }  while (!in_array($this->tokens[$this->id + 1][0], $finals, \STRICT_COMPARISON));

        $static->fullcode = (!empty($fullcodePrefix) ? $fullcodePrefix . ' ' : '') . implode(', ', $fullcode);
        $static->count    = $rank;
        $this->runPlugins($static, $extras);

        $this->pushExpression($static);

        $this->checkExpression();
    }

    private function processStaticVariable(): AtomInterface {
        $variable = $this->addAtom('Static');
        $this->processSGVariable($variable);

        return $variable;
    }

    private function processGlobalVariable(): AtomInterface {
        $variable = $this->addAtom('Global');
        $this->processSGVariable($variable);

        return $variable;
    }

    private function processBracket(): AtomInterface {
        $current = $this->id;
        $bracket = $this->addAtom('Array', $current);

        $variable = $this->popExpression();
        $this->addLink($bracket, $variable, 'VARIABLE');

        // Skip opening bracket
        $opening = $this->tokens[$this->id + 1][0];
        if ($opening === '{') {
            $closing = '}';
        } else {
            $closing = ']';
        }

        ++$this->id;
        $resetContext = false;
        if ($this->contexts->isContext(Context::CONTEXT_NEW)) {
            $resetContext = true;
            $this->contexts->toggleContext(Context::CONTEXT_NEW);
        }
        $index = $this->processExpression(array($this->phptokens::T_CLOSE_BRACKET,
                                                $this->phptokens::T_CLOSE_CURLY,
                                                ));

        if ($resetContext === true) {
            $this->contexts->toggleContext(Context::CONTEXT_NEW);
        }

        // Skip closing bracket
        ++$this->id;
        $this->addLink($bracket, $index, 'INDEX');

        if ($variable->code === '$GLOBALS' && !empty($index->noDelimiter)) {
            // Build the name of the global, dropping the fi
            $bracket->globalvar = '$' . $index->noDelimiter;

            $this->makeGlobal($index);
            $this->calls->addGlobal($this->theGlobals[$bracket->globalvar]->id, $bracket->id);
        }

        $bracket->fullcode  = $variable->fullcode . $opening . $index->fullcode . $closing ;
        $bracket->enclosing = self::NO_ENCLOSING;
        $this->pushExpression($bracket);
        $this->runPlugins($bracket, array('VARIABLE' => $variable,
                                          'INDEX'    => $index));

        $bracket = $this->processFCOA($bracket);
        $this->checkExpression();

        return $bracket;
    }

    private function processBlock(bool $standalone = self::STANDALONE_BLOCK): AtomInterface {
        $this->startSequence();

        // Case for {}
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_CURLY) {
            $void = $this->addAtomVoid();
            $this->addToSequence($void);
        } else {
            $this->contexts->nestContext(Context::CONTEXT_NOSEQUENCE);
            while ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_CLOSE_CURLY) {
                $this->processNext();
            }
            $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);

            $this->checkExpression();
        }

        $block = $this->sequence;
        $this->endSequence();

        $block->code     = '{}';
        $block->fullcode = static::FULLCODE_BLOCK;
        $block->token    = $this->getToken($this->tokens[$this->id][0]);
        $block->bracket  = self::BRACKET;

        ++$this->id; // skip }

        $this->pushExpression($block);
        if ($standalone === self::STANDALONE_BLOCK) {
            $this->processSemicolon();
        }

        return $block;
    }

    private function processForblock(array $finals = array()): AtomInterface {
        $this->startSequence();
        $block = $this->sequence;

        if (in_array($this->tokens[$this->id + 1][0], $finals, \STRICT_COMPARISON)) {
            $element = $this->addAtomVoid();
        } else {
            do {
                $element = $this->processNext();

                if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_COMMA) {
                    $element = $this->popExpression();
                    $this->addToSequence($element);

                    ++$this->id;
                }
            } while (!in_array($this->tokens[$this->id + 1][0], $finals, \STRICT_COMPARISON));
        }
        $this->popExpression();
        $this->addToSequence($element);

        ++$this->id;
        $current = $this->sequence;
        $this->endSequence();
        $block->code     = $current->code;
        $block->fullcode = self::FULLCODE_SEQUENCE;
        $block->token    = $this->getToken($this->tokens[$this->id][0]);

        if ($current->count === 1) {
            $block->fullcode = $element->fullcode;
        }

        return $block;
    }

    private function processFor(): AtomInterface {
        $current = $this->id;
        $for = $this->addAtom('For', $current);
        ++$this->id; // Skip for

        $init = $this->processForblock(array($this->phptokens::T_SEMICOLON));
        $this->addLink($for, $init, 'INIT');

        $final = $this->processForblock(array($this->phptokens::T_SEMICOLON));
        $this->addLink($for, $final, 'FINAL');

        $increment = $this->processForblock(array($this->phptokens::T_CLOSE_PARENTHESIS));
        $this->addLink($for, $increment, 'INCREMENT');

        $isColon = $this->whichSyntax($current, $this->id + 1);

        $block = $this->processFollowingBlock($isColon === self::ALTERNATIVE_SYNTAX ? array($this->phptokens::T_ENDFOR) : array());
        $this->addLink($for, $block, 'BLOCK');

        if ($isColon === self::ALTERNATIVE_SYNTAX) {
            $fullcode = $this->tokens[$current][1] . '(' . $init->fullcode . ' ; ' . $final->fullcode . ' ; ' . $increment->fullcode . ') : ' . self::FULLCODE_SEQUENCE . ' ' . $this->tokens[$this->id + 1][1];
        } else {
            $fullcode = $this->tokens[$current][1] . '(' . $init->fullcode . ' ; ' . $final->fullcode . ' ; ' . $increment->fullcode . ')' . ($block->bracket === self::BRACKET ? self::FULLCODE_BLOCK : self::FULLCODE_SEQUENCE);
        }

        $for->fullcode    = $fullcode;
        $for->alternative = $isColon;

        $this->runPlugins($for, array('INIT'      => $init,
                                      'FINAL'     => $final,
                                      'INCREMENT' => $increment,
                                      'BLOCK'     => $block));

        $this->pushExpression($for);
        $this->finishWithAlternative($isColon);

        return $for;
    }

    private function processForeach(): AtomInterface {
        $current = $this->id;
        $foreach = $this->addAtom('Foreach', $current);
        ++$this->id; // Skip foreach

        do {
            $source = $this->processNext();
        } while ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_AS);

        $this->popExpression();
        $this->addLink($foreach, $source, 'SOURCE');

        $as = $this->tokens[$this->id + 1][1];
        ++$this->id; // Skip as
        $variablesStart = max(array_keys($this->atoms));

        while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_PARENTHESIS,
                                                                $this->phptokens::T_DOUBLE_ARROW,
                                                                ),
                    \STRICT_COMPARISON)) {
            $value = $this->processNext();
        }
        $this->popExpression();
        $valueFullcode = $value->fullcode;

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_DOUBLE_ARROW) {
            $this->addLink($foreach, $value, 'INDEX');
            $variablesStart = max(array_keys($this->atoms));
            $index = $value;
            ++$this->id;
            while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_PARENTHESIS,
                                                                    ),
                        \STRICT_COMPARISON)) {
                $value = $this->processNext();
            }
            $this->popExpression();
            $valueFullcode .= " => {$value->fullcode}";
        }
        $this->addLink($foreach, $value, 'VALUE');

        // Warning : this is also connecting variables used for reading : foreach($a as [$b => $c]) { }
        $max = max(array_keys($this->atoms));
        $double = array($value->code => 1);
        for($i = $variablesStart + 1; $i < $max; ++$i) {
            if ($this->atoms[$i]->atom === 'Variable' && !isset($double[$this->atoms[$i]->code])) {
                $double[$this->atoms[$i]->code] = 1;
                $this->addLink($foreach, $this->atoms[$i], 'VALUE');
            }
        }
        unset($double);

        ++$this->id; // Skip )
        $isColon = $this->whichSyntax($current, $this->id + 1);

        $block = $this->processFollowingBlock($isColon === true ? array($this->phptokens::T_ENDFOREACH) : array());
        $this->addLink($foreach, $block, 'BLOCK');

        if ($isColon === self::ALTERNATIVE_SYNTAX) {
            $fullcode = $this->tokens[$current][1] . '(' . $source->fullcode . ' ' . $as . ' ' . $valueFullcode . ') : ' . self::FULLCODE_SEQUENCE . ' endforeach';
        } else {
            $fullcode = $this->tokens[$current][1] . '(' . $source->fullcode . ' ' . $as . ' ' . $valueFullcode . ')' . ($block->bracket === self::BRACKET ? self::FULLCODE_BLOCK : self::FULLCODE_SEQUENCE);
        }

        $foreach->fullcode    = $fullcode;
        $foreach->alternative = $isColon;

        $extras = array('SOURCE'    => $source,
                        'VALUE'     => $value,
                        'BLOCK'     => $block);
        if (isset($index)) {
            $extras['INDEX'] = $index;
        }
        $this->runPlugins($foreach, $extras);

        $this->pushExpression($foreach);
        $this->finishWithAlternative($isColon);

        return $foreach;
    }

    private function processFollowingBlock(array $finals = array()): AtomInterface {
        $this->checkPhpdoc();
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_CURLY) {
            ++$this->id;
            $block = $this->processBlock(self::RELATED_BLOCK);
            $block->bracket = self::BRACKET;
            $this->popExpression(); // drop it

        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_COLON) {
            $this->startSequence();
            $block = $this->sequence;
            ++$this->id; // skip :

            while (!in_array($this->tokens[$this->id + 1][0], $finals, \STRICT_COMPARISON)) {
                $this->processNext();
            }

            $this->endSequence();

        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_SEMICOLON) {
            // void; One epxression block, with ;
            $this->startSequence();
            $block = $this->sequence;

            $void = $this->addAtomVoid();
            $this->addToSequence($void);
            $this->endSequence();
            ++$this->id;

        } elseif (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_TAG,
                                                                  $this->phptokens::T_CLOSE_CURLY,
                                                                  $this->phptokens::T_CLOSE_PARENTHESIS,
                                                                  ),
                  \STRICT_COMPARISON)) {
            // Completely void (not even ;)
            $this->startSequence();
            $block = $this->sequence;

            $void = $this->addAtomVoid();
            $this->addToSequence($void);
            $this->endSequence();

        } else {
            // One expression only
            $this->startSequence();
            $block = $this->sequence;
            $current = $this->id;

            // This may include WHILE in the list of finals for do....while
            $finals = array_merge(array($this->phptokens::T_SEMICOLON,
                                        $this->phptokens::T_CLOSE_TAG,
                                        $this->phptokens::T_ELSE,
                                        $this->phptokens::T_END,
                                        $this->phptokens::T_CLOSE_CURLY,
                                        ), $finals);
            $specials = array($this->phptokens::T_IF,
                              $this->phptokens::T_FOREACH,
                              $this->phptokens::T_SWITCH,
                              $this->phptokens::T_FOR,
                              $this->phptokens::T_TRY,
                              $this->phptokens::T_WHILE,
                              );
            if (in_array($this->tokens[$this->id + 1][0], $specials, \STRICT_COMPARISON)) {
                $this->processNext();
            } else {
                do {
                    $expression = $this->processNext();
                } while (!in_array($this->tokens[$this->id + 1][0], $finals, \STRICT_COMPARISON));
                $this->popExpression();
                if ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_CLOSE_TAG) {
                    $this->addToSequence($expression);
                }
                $this->runPlugins($block, array($expression));
            }

            $this->endSequence();

            if (!in_array($this->tokens[$current + 1][0], $specials, \STRICT_COMPARISON)) {
                ++$this->id;
            }
        }

        return $block;
    }

    private function processDo(): AtomInterface {
        $current = $this->id;
        $dowhile = $this->addAtom('Dowhile', $this->id);

        $block = $this->processFollowingBlock(array($this->phptokens::T_WHILE));
        $this->addLink($dowhile, $block, 'BLOCK');

        $while = $this->tokens[$this->id + 1][1];
        ++$this->id; // Skip while
        ++$this->id; // Skip (

        while ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_CLOSE_PARENTHESIS) {
            $condition = $this->processNext();
        }
        ++$this->id; // skip )
        $this->popExpression();
        $this->addLink($dowhile, $condition, 'CONDITION');

        $dowhile->fullcode = $this->tokens[$current][1] . ( $block->bracket === self::BRACKET ? self::FULLCODE_BLOCK : self::FULLCODE_SEQUENCE) . $while . '(' . $condition->fullcode . ')';

        $this->runPlugins($dowhile, array('CONDITION' => $condition,
                                          'BLOCK'     => $block));
        $this->pushExpression($dowhile);

        $this->checkExpression();

        return $dowhile;
    }

    private function processWhile(): AtomInterface {
        $current = $this->id;
        $while = $this->addAtom('While', $current);

        ++$this->id; // Skip while

        do {
            $condition = $this->processNext();
        } while ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_CLOSE_PARENTHESIS);
        $this->popExpression();
        $this->addLink($while, $condition, 'CONDITION');

        ++$this->id; // Skip )
        $isColon = $this->whichSyntax($current, $this->id + 1);
        $block = $this->processFollowingBlock($isColon === self::ALTERNATIVE_SYNTAX ? array($this->phptokens::T_ENDWHILE) : array());
        $this->addLink($while, $block, 'BLOCK');

        if ($isColon === self::ALTERNATIVE_SYNTAX) {
            $fullcode = $this->tokens[$current][1] . ' (' . $condition->fullcode . ') : ' . self::FULLCODE_SEQUENCE . ' ' . $this->tokens[$this->id + 1][1];
        } else {
            $fullcode = $this->tokens[$current][1] . ' (' . $condition->fullcode . ')' . ($block->bracket === self::BRACKET ? self::FULLCODE_BLOCK : self::FULLCODE_SEQUENCE);
        }

        $while->fullcode    = $fullcode;
        $while->alternative = $isColon;

        $this->runPlugins($while, array('CONDITION' => $condition,
                                        'BLOCK'     => $block));

        $this->pushExpression($while);
        $this->finishWithAlternative($isColon);

        return $while;
    }

    private function processDeclare(): AtomInterface {
        $current = $this->id;
        $declare = $this->addAtom('Declare', $current);
        $fullcode = array();

        ++$this->id; // Skip declare
        $strictTypes = false;
        do {
            ++$this->id; // Skip ( or ,
            $name = $this->processSingle('Name');

            ++$this->id; // Skip =
            $config = $this->processNext();
            $this->popExpression();

            $declaredefinition = $this->addAtom('Declaredefinition');
            $this->addLink($declaredefinition, $name, 'NAME');
            $this->addLink($declaredefinition, $config, 'VALUE');

            $strictTypes |= strtolower($name->code) === 'strict_types';

            $this->addLink($declare, $declaredefinition, 'DECLARE');
            $declaredefinition->fullcode = $name->fullcode . ' = ' . $config->fullcode;
            $fullcode[] = $declaredefinition->fullcode;

            ++$this->id; // Skip value
        } while ($this->tokens[$this->id][0] === $this->phptokens::T_COMMA);

        if ($strictTypes === true) {
            $fullcode = $this->tokens[$current][1] . ' (' . implode(', ', $fullcode) . ') ';

            ++$this->id;
            $isColon = false;
        } else {
            $isColon = $this->whichSyntax($current, $this->id + 1);

            $block = $this->processFollowingBlock($isColon === self::ALTERNATIVE_SYNTAX ? array($this->phptokens::T_ENDDECLARE) : array());
            $this->addLink($declare, $block, 'BLOCK');

            if ($isColon === self::ALTERNATIVE_SYNTAX) {
                $fullcode = $this->tokens[$current][1] . ' (' . implode(', ', $fullcode) . ') : ' . self::FULLCODE_SEQUENCE . ' ' . $this->tokens[$this->id + 1][1];
            } else {
                $fullcode = $this->tokens[$current][1] . ' (' . implode(', ', $fullcode) . ') ' . self::FULLCODE_BLOCK;
            }
        }

        $declare->fullcode    = $fullcode;
        $declare->alternative = $isColon ;

        $this->pushExpression($declare);
        $this->finishWithAlternative($isColon);

        return $declare;
    }

    private function processDefault(): AtomInterface {
        $current = $this->id;
        $default = $this->addAtom('Default', $current);

        if  (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_COLON,
                                                             $this->phptokens::T_SEMICOLON,
                                                             ),
            \STRICT_COMPARISON)) {
            ++$this->id; // Skip :
        }

        $default->fullcode = $this->tokens[$current][1] . ' : ' . self::FULLCODE_SEQUENCE;

        if (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_CURLY,
                                                            $this->phptokens::T_CASE,
                                                            $this->phptokens::T_DEFAULT,
                                                            $this->phptokens::T_ENDSWITCH))) {
            $this->cases->add(array($default, null));

            return $default ;
        }

        $this->startSequence();
        while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_CURLY,
                                                                $this->phptokens::T_CASE,
                                                                $this->phptokens::T_DEFAULT,
                                                                $this->phptokens::T_ENDSWITCH),
                \STRICT_COMPARISON)) {
            $this->processNext();
        }
        $code = $this->sequence;
        $this->endSequence();

        foreach($this->cases->getAll() as $aCase) {
            $this->addLink($aCase[0], $code, 'CODE');

            if ($aCase[0]->atom === 'Default') {
                $this->runPlugins($aCase[0], array('CODE' => $code));
            } else {
                $this->runPlugins($aCase[0], array('CASE' => $aCase[1],
                                                   'CODE' => $code));
            }
        }

        $this->addLink($default, $code, 'CODE');
        $this->runPlugins($default, array('CODE' => $code));

        return $default;
    }

    // This process Case and Default inside a Match (also, trailing voids)
    private function processMatchCase(): AtomInterface {
        $current = $this->id;

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_CURLY) {
            return $this->addAtomVoid();
        }

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_DEFAULT) {
            $case = $this->addAtom('Default', $current);
            $item = null;
            ++$this->id; // Skip default
        } else {
            $case = $this->addAtom('Case', $current);

            $this->contexts->nestContext(Context::CONTEXT_NOSEQUENCE);
            do {
                $item = $this->processNext();
            } while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_DOUBLE_ARROW,
                                                                      $this->phptokens::T_COMMA,
                                                                    ),
                                \STRICT_COMPARISON));
            $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);

            $this->popExpression();
            $this->addLink($case, $item, 'CASE');
        }
        $this->cases->add(array($case, $item));

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_COMMA) {
            ++$this->id;
            if ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_DOUBLE_ARROW) {
                return $case;
            }
        }
        ++$this->id; // Skip => or ,

        $this->startSequence();
        do {
            $expression = $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_CURLY,
                                                                  $this->phptokens::T_COMMA,
                                                                ),
                \STRICT_COMPARISON));

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_COMMA) {
            ++$this->id;
        }
        $this->addToSequence($expression);
        $code = $this->sequence;
        $this->endSequence();

        foreach($this->cases->getAll() as $aCase) {
            $this->addLink($aCase[0], $code, 'CODE');

            if ($aCase[0]->atom === 'Default') {
                $this->runPlugins($aCase[0], array( 'CODE' => $code));
            } else {
                $this->runPlugins($aCase[0], array('CASE' => $aCase[1],
                                                   'CODE' => $code));
            }
        }

        $children = array('CODE' => $code);
        if ($case->atom === 'Case') {
            $children['CASE'] = $item;
        }
        $this->runPlugins($case, $children);

        return $case;
    }

    private function processCase(): AtomInterface {
        $current = $this->id;
        $case = $this->addAtom('Case', $current);

        $this->contexts->nestContext(Context::CONTEXT_NOSEQUENCE);
        while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_COLON,
                                                                $this->phptokens::T_SEMICOLON,
                                                                $this->phptokens::T_CLOSE_TAG,
                                                                ),
                \STRICT_COMPARISON)) {
            $item = $this->processNext();
        }
        $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);

        $this->popExpression();
        $this->addLink($case, $item, 'CASE');

        if  (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_COLON,
                                                             $this->phptokens::T_SEMICOLON,
                                                             ),
                \STRICT_COMPARISON)) {
            ++$this->id; // Skip :
        }

        $case->fullcode = $this->tokens[$current][1] . ' ' . $item->fullcode . ' : ' . self::FULLCODE_SEQUENCE . ' ';

        if (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_CURLY,
                                                            $this->phptokens::T_CASE,
                                                            $this->phptokens::T_DEFAULT,
                                                            $this->phptokens::T_ENDSWITCH))) {
            $this->cases->add(array($case, $item));

            return $case;
        }

        $this->startSequence();
        while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_CURLY,
                                                                $this->phptokens::T_CASE,
                                                                $this->phptokens::T_DEFAULT,
                                                                $this->phptokens::T_ENDSWITCH),
                \STRICT_COMPARISON)) {
            $this->processNext();
        }

        $code = $this->sequence;
        $this->endSequence();

        foreach($this->cases->getAll() as $aCase) {
            $this->addLink($aCase[0], $code, 'CODE');

            if ($aCase[0]->atom === 'Default') {
                $this->runPlugins($aCase[0], array( 'CODE' => $code));
            } else {
                $this->runPlugins($aCase[0], array('CASE' => $aCase[1],
                                                   'CODE' => $code));
            }
        }

        $this->addLink($case, $code, 'CODE');

        $this->runPlugins($case, array( 'CASE' => $item,
                                        'CODE' => $code));

        return $case;
    }

    private function processSwitch(): AtomInterface {
        $current = $this->id;
        $switch = $this->addAtom('Switch', $current);
        ++$this->id; // Skip (
        $this->cases->push();

        do {
            $name = $this->processNext();
        } while ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_CLOSE_PARENTHESIS);
        $this->popExpression();
        $this->addLink($switch, $name, 'CONDITION');

        $cases = $this->addAtom('Sequence', $current);
        $cases->code     = self::FULLCODE_SEQUENCE;
        $cases->fullcode = self::FULLCODE_SEQUENCE;
        $cases->bracket  = self::BRACKET;

        $this->addLink($switch, $cases, 'CASES');
        $extraCases = array();
        ++$this->id;

        $isColon = $this->whichSyntax($current, $this->id + 1);

        $rank = -1;
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_PARENTHESIS) {
            $void = $this->addAtomVoid();
            $this->addLink($cases, $void, 'EXPRESSION');
            $void->rank = $rank;
            $extraCases[] = $void;

            ++$this->id;
        } else {
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_CURLY) {
                ++$this->id;
                $finals = array($this->phptokens::T_CLOSE_CURLY);
            } else {
                ++$this->id; // skip :
                $finals = array($this->phptokens::T_ENDSWITCH);
            }
            while (!in_array($this->tokens[$this->id + 1][0], $finals, \STRICT_COMPARISON)) {
                $case = $this->processNext();

                $this->popExpression();
                $this->addLink($cases, $case, 'EXPRESSION');
                $case->rank = ++$rank;
                $extraCases[] = $case;
            }
        }
        ++$this->id;
        $cases->count = $rank + 1;

        if ($isColon === self::ALTERNATIVE_SYNTAX) {
            $fullcode = $this->tokens[$current][1] . ' (' . $name->fullcode . ') :' . self::FULLCODE_SEQUENCE . ' ' . $this->tokens[$this->id][1];
        } else {
            $fullcode = $this->tokens[$current][1] . ' (' . $name->fullcode . ')' . self::FULLCODE_BLOCK;
        }

        $switch->fullcode    = $fullcode;
        $switch->alternative = $isColon;

        $this->runPlugins($cases, $extraCases);

        $this->runPlugins($switch, array('CONDITION' => $name,
                                         'CASES'     => $cases, ));

        $this->pushExpression($switch);
        $this->finishWithAlternative($isColon);

        $this->cases->pop();

        return $switch;
    }

    private function processMatch(): AtomInterface {
        $current = $this->id;
        $switch = $this->addAtom('Match', $current);
        ++$this->id; // Skip (
        $this->cases->push();

        do {
            $name = $this->processNext();
        } while ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_CLOSE_PARENTHESIS);
        $this->popExpression();
        $this->addLink($switch, $name, 'CONDITION');

        $cases = $this->addAtom('Sequence', $current);
        $cases->code     = self::FULLCODE_SEQUENCE;
        $cases->fullcode = self::FULLCODE_SEQUENCE;
        $cases->bracket  = self::BRACKET;

        $this->addLink($switch, $cases, 'CASES');
        $extraCases = array();
        ++$this->id;

        $isColon = $this->whichSyntax($current, $this->id + 1);

        $rank = -1;
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_PARENTHESIS) {
            // case of an empty Match
            $void = $this->addAtomVoid();
            $this->addLink($cases, $void, 'EXPRESSION');
            $void->rank = $rank;
            $extraCases[] = $void;

            ++$this->id;
        } else {
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_CURLY) {
                ++$this->id;
                $finals = array($this->phptokens::T_CLOSE_CURLY);
            } else {
                ++$this->id; // skip :
                $finals = array($this->phptokens::T_ENDSWITCH);
            }
            do {
                $case = $this->processMatchCase();

                $this->popExpression();
                $this->addLink($cases, $case, 'EXPRESSION');
                $case->rank = ++$rank;
                $extraCases[] = $case;
            } while (!in_array($this->tokens[$this->id + 1][0], $finals, \STRICT_COMPARISON));
        }
        ++$this->id;
        $cases->count = $rank + 1;

        if ($isColon === self::ALTERNATIVE_SYNTAX) {
            $fullcode = $this->tokens[$current][1] . ' (' . $name->fullcode . ') :' . self::FULLCODE_SEQUENCE . ' ' . $this->tokens[$this->id][1];
        } else {
            $fullcode = $this->tokens[$current][1] . ' (' . $name->fullcode . ')' . self::FULLCODE_BLOCK;
        }

        $switch->fullcode    = $fullcode;
        $switch->alternative = $isColon;

        $this->runPlugins($cases, $extraCases);

        $this->runPlugins($switch, array('CONDITION' => $name,
                                         'CASES'     => $cases, ));

        $this->pushExpression($switch);
        $this->finishWithAlternative($isColon);

        $this->cases->pop();

        return $switch;
    }

    private function processIfthen(): AtomInterface {
        $current = $this->id;
        $ifthen = $this->addAtom('Ifthen', $current);
        ++$this->id; // Skip (

        do {
            $condition = $this->processNext();
        } while ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_CLOSE_PARENTHESIS);

        $this->popExpression();
        $this->addLink($ifthen, $condition, 'CONDITION');
        $extras = array('CONDITION' => $condition);

        ++$this->id; // Skip )
        $isInitialIf = $this->tokens[$current][0] === $this->phptokens::T_IF;
        $isColon = $this->whichSyntax($current, $this->id + 1);

        $then = $this->processFollowingBlock(array($this->phptokens::T_ENDIF,
                                                   $this->phptokens::T_ELSE,
                                                   $this->phptokens::T_ELSEIF,
                                                   ));
        $this->addLink($ifthen, $then, 'THEN');
        $extras['THEN'] = $then;

        $this->checkPhpdoc();
        // Managing else case
        if (in_array($this->tokens[$this->id][0], array($this->phptokens::T_END,
                                                        $this->phptokens::T_CLOSE_TAG),
            \STRICT_COMPARISON)) {
            $elseFullcode = '';
            // No else, end of a script
            --$this->id;
            // Back up one unit to allow later processing for sequence
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_ELSEIF){
            ++$this->id;

            $elseif = $this->processIfthen();
            $this->addLink($ifthen, $elseif, 'ELSE');
            $extras['ELSE'] = $elseif;

            $elseFullcode = $elseif->fullcode;

        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_ELSE){
            $elseFullcode = $this->tokens[$this->id + 1][1];
            ++$this->id; // Skip else

            $else = $this->processFollowingBlock(array($this->phptokens::T_ENDIF));
            $this->addLink($ifthen, $else, 'ELSE');
            $extras['ELSE'] = $else;

            if ($isColon === self::ALTERNATIVE_SYNTAX) {
                $elseFullcode .= ' :';
            }
            $elseFullcode .= $else->fullcode;
        } else {
            $elseFullcode = '';
        }

        if ($isInitialIf === true && $isColon === self::ALTERNATIVE_SYNTAX) {
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_SEMICOLON) {
                ++$this->id; // skip ;
            }
            ++$this->id; // skip ;
        }

        if ($isColon === self::ALTERNATIVE_SYNTAX) {
            $fullcode = $this->tokens[$current][1] . '(' . $condition->fullcode . ') : ' . $then->fullcode . $elseFullcode . ($isInitialIf === true ? ' endif' : '');
        } else {
            $fullcode = $this->tokens[$current][1] . '(' . $condition->fullcode . ')' . $then->fullcode . $elseFullcode;
        }

        $ifthen->fullcode    = $fullcode;
        $ifthen->alternative = $isColon;

        $this->runPlugins($ifthen, $extras);

        if ($this->tokens[$current][0] === $this->phptokens::T_IF) {
            $this->pushExpression($ifthen);
            $this->finishWithAlternative($isColon);
        }

        return $ifthen;
    }

    private function checkPhpdoc(): void {
        while($this->tokens[$this->id + 1][0] === $this->phptokens::T_DOC_COMMENT){
            ++$this->id;
            $this->processPhpdoc();
        }
    }

    private function checkAttribute(): void {
        while($this->tokens[$this->id + 1][0] === $this->phptokens::T_ATTRIBUTE){
            ++$this->id;
            $this->processAttribute();
        }
    }

    private function processParenthesis(): AtomInterface {
        $current = $this->id;
        $parenthese = $this->addAtom('Parenthesis', $current);

        while ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_CLOSE_PARENTHESIS) {
            $code = $this->processNext();
        }

        $this->popExpression();
        $this->addLink($parenthese, $code, 'CODE');

        $parenthese->fullcode    = '(' . $code->fullcode . ')';
        $parenthese->noDelimiter = $code->noDelimiter;
        $this->runPlugins($parenthese, array('CODE' => $code));

        $this->pushExpression($parenthese);
        ++$this->id; // Skipping the )

        if ( !$this->contexts->isContext(Context::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG) {
            $this->processSemicolon();
        } else {
            $parenthese = $this->processFCOA($parenthese);
        }

        return $parenthese;
    }

    private function processExit(): AtomInterface {
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_PARENTHESIS) {
            $current = $this->id;

            ++$this->id;

            $functioncall = $this->processArguments('Exit',
                                                    array($this->phptokens::T_SEMICOLON,
                                                          $this->phptokens::T_CLOSE_TAG,
                                                          $this->phptokens::T_CLOSE_PARENTHESIS,
                                                          $this->phptokens::T_CLOSE_BRACKET,
                                                          $this->phptokens::T_CLOSE_CURLY,
                                                          $this->phptokens::T_COLON,
                                                          $this->phptokens::T_END,
                                                          ));
            $argumentsFullcode = $functioncall->fullcode;
            $argumentsFullcode = "($argumentsFullcode)";

            $functioncall->code       = $this->tokens[$current][1];
            $functioncall->fullcode   = $this->tokens[$current][1] . $argumentsFullcode;
            $functioncall->fullnspath = '\\' . mb_strtolower($this->tokens[$current][1]);
            $this->pushExpression($functioncall);
            $this->runPlugins($functioncall);

            $this->checkExpression();

            return $functioncall;
        } else {
            $functioncall = $this->addAtom('Exit', $this->id);

            $functioncall->fullcode   = $this->tokens[$this->id][1] . ' ';
            $functioncall->count      = 0;
            $functioncall->fullnspath = '\\' . mb_strtolower($functioncall->code);

            $void = $this->addAtomVoid();
            $void->rank = 0;

            $this->addLink($functioncall, $void, 'ARGUMENT');

            if ( !$this->contexts->isContext(Context::CONTEXT_NOSEQUENCE) &&
                 in_array($this->tokens[$this->id + 1][0],
                         array($this->phptokens::T_CLOSE_TAG,
                               $this->phptokens::T_COMMA,
                              ), \STRICT_COMPARISON)
                ) {
                $this->processSemicolon();
            }

            $this->pushExpression($functioncall);
            $this->checkExpression();

            return $functioncall;
        }
    }

    private function processArrayLiteral(): AtomInterface {
        $current = $this->id;

        $argumentsList = array();
        if ($this->tokens[$current][0] === $this->phptokens::T_ARRAY) {
            ++$this->id; // Skipping the name, set on (
            $array = $this->processArguments('Arrayliteral', array(), $argumentsList);
            $argumentsFullcode = $array->fullcode;
            $array->token    = 'T_ARRAY';
            $array->fullcode = $this->tokens[$current][1] . '(' . $argumentsFullcode . ')';
        } else {
            $bracket = 1;
            $id = $this->id;
            while($bracket > 0) {
                ++$id;
                if ($this->tokens[$id][0] === $this->phptokens::T_CLOSE_BRACKET) {
                    --$bracket;
                } elseif ($this->tokens[$id][0] === $this->phptokens::T_OPEN_BRACKET) {
                    ++$bracket;
                }
            }

            if ($this->tokens[$id + 1][0] === $this->phptokens::T_EQUAL) {
                $array = $this->processArguments('List', array($this->phptokens::T_CLOSE_BRACKET), $argumentsList);
                $argumentsFullcode = $array->fullcode;

                // This is a T_LIST !
                $array->token      = 'T_OPEN_BRACKET';
                $array->fullnspath = '\list';
                $array->fullcode   = "[$argumentsFullcode]";
            } else {
                $array = $this->processArguments('Arrayliteral', array($this->phptokens::T_CLOSE_BRACKET), $argumentsList);
                $argumentsFullcode = $array->fullcode;

                $array->token     = 'T_OPEN_BRACKET';
                $array->fullcode  = "[$argumentsFullcode]";
            }
        }

        $array->code      = $this->tokens[$current][1];
        $this->runPlugins($array, $argumentsList);

        $this->pushExpression($array);

        if ( !$this->contexts->isContext(Context::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG) {
            $this->processSemicolon();
        } else {
            $array = $this->processFCOA($array);
        }

        return $array;
    }

    private function processTernary(): AtomInterface {
        $current = $this->id;
        $condition = $this->popExpression();
        $ternary = $this->addAtom('Ternary', $current);

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_STRING &&
            $this->tokens[$this->id + 2][0] === $this->phptokens::T_COLON) {
            if (in_array(mb_strtolower($this->tokens[$this->id + 1][1]), array('true', 'false'), \STRICT_COMPARISON)) {
                ++$this->id;
                $then = $this->processSingle('Boolean');
                $this->runPlugins($then);
            } elseif (mb_strtolower($this->tokens[$this->id + 1][1]) === 'null') {
                ++$this->id;
                $then = $this->processSingle('Null');
                $this->runPlugins($then);
            } else {
                $then = $this->processNextAsIdentifier();
            }
        } else {
            $this->contexts->nestContext(Context::CONTEXT_NOSEQUENCE);
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_COLON) {
                $then = $this->addAtomVoid();
            } else {
                do {
                    $then = $this->processNext();
                } while ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_COLON);
            }

            $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);
            $this->popExpression();
        }

        ++$this->id; // Skip colon

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_STRING &&
            $this->tokens[$this->id + 2][0] === $this->phptokens::T_COLON) {
            if (in_array(mb_strtolower($this->tokens[$this->id + 1][1]), array('true', 'false'), \STRICT_COMPARISON)) {
                ++$this->id;
                $else = $this->processSingle('Boolean');
                $this->runPlugins($else);
            } elseif (mb_strtolower($this->tokens[$this->id + 1][1]) === 'null') {
                ++$this->id;
                $else = $this->processSingle('Null');
                $this->runPlugins($else);
            } else {
                $else = $this->processNextAsIdentifier();
            }
        } else {
            $finals = $this->precedence->get($this->tokens[$this->id][0]);
            $finals[] = $this->phptokens::T_COLON; // Added from nested Ternary
            $finals[] = $this->phptokens::T_CLOSE_TAG;

            $this->contexts->nestContext(Context::CONTEXT_NOSEQUENCE);
            $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
            do {
                $else = $this->processNext();
            } while (!in_array($this->tokens[$this->id + 1][0], $finals, \STRICT_COMPARISON) );
            $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);

            $this->popExpression();
        }

        if ($then->isA(array('Identifier', 'Nsname'))) {
            $this->calls->addCall('const', $then->fullnspath, $then);
        }
        $this->addLink($ternary, $condition, 'CONDITION');
        $this->addLink($ternary, $then, 'THEN');
        $this->addLink($ternary, $else, 'ELSE');

        $ternary->fullcode = $condition->fullcode . ' ?' . ($then->atom === 'Void' ? '' : ' ' . $then->fullcode . ' ' ) . ': ' . $else->fullcode;
        $this->runPlugins($ternary, array('CONDITION' => $condition,
                                          'THEN'      => $then,
                                          'ELSE'      => $else,
                                          ));

        $this->pushExpression($ternary);

        $this->checkExpression();

        return $ternary;
    }

    //////////////////////////////////////////////////////
    /// processing single tokens
    //////////////////////////////////////////////////////
    private function processSingle(string $atomName): AtomInterface {
        $atom = $this->addAtom($atomName, $this->id);
        if (strlen($this->tokens[$this->id][1]) > 100000) {
            $this->tokens[$this->id][1] = substr($this->tokens[$this->id][1], 0, 100000) . PHP_EOL . '[.... 100000 / ' . strlen($this->tokens[$this->id][1]) . ']' . PHP_EOL;
        }
        $atom->fullcode = $this->tokens[$this->id][1];

        if ($atomName === 'Phpvariable' && in_array($atom->code, array('$GLOBALS', '$_SERVER', '$_REQUEST', '$_POST', '$_GET', '$_FILES', '$_ENV', '$_COOKIE', '$_SESSION'), \STRICT_COMPARISON)) {
            $this->makeGlobal($atom);
            $this->calls->addGlobal($this->theGlobals[$atom->code]->id, $atom->id);
        } elseif (!in_array($atomName, array('Parametername', 'Parameter', 'Staticpropertyname', 'Propertydefinition', 'Globaldefinition', 'Staticdefinition', 'This'), \STRICT_COMPARISON) &&
            $this->tokens[$this->id][0] === $this->phptokens::T_VARIABLE) {
            if (isset($this->currentVariables[$atom->code])) {
                $this->addLink($this->currentVariables[$atom->code], $atom, 'DEFINITION');
            } else {
                $definition = $this->addAtom('Variabledefinition');
                $definition->code = $atom->code;
                $definition->fullcode = $atom->fullcode;
                $this->addLink($this->currentMethod[count($this->currentMethod) - 1], $definition, 'DEFINITION');
                $this->currentVariables[$atom->code] = $definition;

                $this->addLink($definition, $atom, 'DEFINITION');

                if (!$this->contexts->isContext(Context::CONTEXT_FUNCTION)) {
                    $this->makeGlobal($definition);
                    $this->calls->addGlobal($this->theGlobals[$definition->code]->id, $definition->id);
                }
            }
        }

        return $atom;
    }

    private function processInlinehtml(): AtomInterface {
        $inlineHtml = $this->processSingle('Inlinehtml');
        return $inlineHtml;
    }

    private function processNamespaceBlock(): AtomInterface {
        $this->startSequence();

        while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_TAG,
                                                                $this->phptokens::T_NAMESPACE,
                                                                $this->phptokens::T_END,
                                                                ),
                \STRICT_COMPARISON)) {
            $this->processNext();

            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_NAMESPACE &&
                $this->tokens[$this->id + 2][0] === $this->phptokens::T_NS_SEPARATOR) {
                $this->processNext();
            }
        }
        $block = $this->sequence;
        $this->endSequence();

        $block->code     = ' ';
        $block->fullcode = ' ' . self::FULLCODE_SEQUENCE . ' ';
        $block->token    = $this->getToken($this->tokens[$this->id][0]);

        return $block;
    }

    private function processNamespace(): AtomInterface {
        $current = $this->id;

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_NS_SEPARATOR) {
            $nsname = $this->processOneNsname();

//            $this->getFullnspath($nsname, 'class', $nsname);
            $this->pushExpression($nsname);

            return $this->processFCOA($nsname);
        }

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_CURLY) {
            $name = $this->addAtomVoid();
        } else {
            $name = $this->processOneNsname();
        }

        $namespace = $this->addAtom('Namespace', $current);
        $this->makePhpdoc($namespace);
        $this->addLink($namespace, $name, 'NAME');
        $this->setNamespace($name->fullcode === ' ' ? self::NO_NAMESPACE : $name->fullcode);

        // Here, we make sure namespace is encompassing the next elements.
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_SEMICOLON) {
            // Process block

            ++$this->id; // Skip ; to start actual sequence
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_END) {
                $void = $this->addAtomVoid();
                $block = $this->addAtom('Sequence', $this->id);
                $block->code       = '{}';
                $block->fullcode   = self::FULLCODE_BLOCK;
                $block->bracket    = self::NOT_BRACKET;

                $this->addLink($block, $void, 'EXPRESSION');
            } else {
                $block = $this->processNamespaceBlock();
            }
            $this->addLink($namespace, $block, 'BLOCK');
            $this->addToSequence($namespace);
            $block = ';';
        } else {
            // Process block
            $block = $this->processFollowingBlock(array($this->phptokens::T_CLOSE_CURLY));
            $this->addLink($namespace, $block, 'BLOCK');

            $this->addToSequence($namespace);

            $block = self::FULLCODE_BLOCK;
        }
        $this->setNamespace(self::NO_NAMESPACE);

        $namespace->fullcode   = $this->tokens[$current][1] . ' ' . $name->fullcode . $block;
        $namespace->fullnspath = $name->atom === 'Void' ? '\\' : $name->fullnspath;

        return $namespace;
    }

    private function processAlias(string $useType): AtomInterface {
        $current = $this->id;
        $as = $this->addAtom('As', $current);

        $left = $this->popExpression();
        $this->addLink($as, $left, 'NAME');

        $right = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);
        $right->fullnspath = '\\' . mb_strtolower($right->code);
        $this->addLink($as, $right, 'AS');

        $as->fullcode = $left->fullcode . ' ' . $this->tokens[$this->id - 1][1] . ' ' . $right->fullcode;

        $this->addNamespaceUse($left, $as, $useType, $as);

        return $as;
    }

    private function processAsTrait(): AtomInterface {
        $current = $this->id;
        $as = $this->addAtom('As', $current);

        // special case for use t, t2 { as as yes; }
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_AS) {
            $left = $this->processNextAsIdentifier();
        } else {
            $left = $this->popExpression();
        }

        $this->getFullnspath($left, 'staticmethod', $left);
        $this->calls->addCall('staticmethod', $left->fullnspath, $left);

        $this->addLink($as, $left, 'NAME');
        $fullcode = array($left->fullcode, $this->tokens[$current][1]);

        if (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_PRIVATE,
                                                            $this->phptokens::T_PUBLIC,
                                                            $this->phptokens::T_PROTECTED,
                                                            ),
                \STRICT_COMPARISON)) {
            $fullcode[] = $this->tokens[$this->id + 1][1];
            $as->visibility = strtolower($this->tokens[$this->id + 1][1]);
            ++$this->id;
        }

        if ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_SEMICOLON) {
            $alias = $this->processNextAsIdentifier();
            $this->addLink($as, $alias, 'AS');
            $fullcode[] = $alias->fullcode;
        }

        $as->fullcode = implode(' ', $fullcode);

        $this->pushExpression($as);

        return $as;
    }

    private function processInsteadof(): AtomInterface {
        $insteadof = $this->processOperator('Insteadof', $this->precedence->get($this->tokens[$this->id][0]), array('NAME', 'INSTEADOF'));
        while ($this->tokens[$this->id + 1][0] === $this->phptokens::T_COMMA) {
            ++$this->id;
            $nsname = $this->processOneNsname();

            $this->addLink($insteadof, $nsname, 'INSTEADOF');
        }
        return $insteadof;
    }

    private function processUse(): AtomInterface {
        if (empty($this->currentClassTrait)) {
            return $this->processUseNamespace();
        } else {
            return $this->processUseTrait();
        }
    }

    private function processUseNamespace(): AtomInterface {
        $current = $this->id;
        $use = $this->addAtom('Usenamespace', $current);
        $useType = 'class';

        $fullcode = array();

        // use const
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CONST) {
            ++$this->id;

            $useType = 'const';
        }

        // use function
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_FUNCTION) {
            ++$this->id;

            $useType = 'function';
        }

        --$this->id;
        do {
            $prefix = '';
            ++$this->id;
            $this->checkPhpdoc();
            $namespace = $this->processOneNsname(self::WITHOUT_FULLNSPATH);
            // Default case : use A\B
            $alias = $namespace;
            $origin = $namespace;

            if ($useType === 'const') {
                $fullnspath = $namespace->fullcode;
            } else {
                $fullnspath = mb_strtolower($namespace->fullcode);
            }
            if ($fullnspath[0] !== '\\') {
                list($prefix) = explode('\\', $fullnspath, 1);
                $fullnspath = "\\$fullnspath";
            }

            if ($useType === 'class') {
                $this->calls->addCall('class', $fullnspath, $namespace);
            }

            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_AS) {
                // use A\B as C
                ++$this->id;

                $this->pushExpression($namespace);
                $as = $this->processAlias($useType);
                $as->fullnspath = makeFullNsPath($namespace->fullcode, $useType === 'const');
                $fullcode[] = $as->fullcode;
                $as->alias = mb_strtolower(substr($as->fullcode, strrpos($as->fullcode, ' as ') + 4));

                $alias = $this->addNamespaceUse($origin, $as, $useType, $as);

                if (($use2 = $this->uses->get('class', $prefix)) instanceof AtomInterface) {
                    $this->addLink($as, $use2, 'DEFINITION');
                }
                $this->addLink($use, $as, 'USE');

                $namespace = $as;
                $namespace->use = $useType;
            } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_NS_SEPARATOR) {
                //use A\B\ {}
                $this->addLink($use, $namespace, 'GROUPUSE');
                $prefix = makeFullNsPath($namespace->fullcode);
                if ($prefix[0] !== '\\') {
                    $prefix = "\\$prefix";
                }
                $prefix .= '\\';

                ++$this->id; // Skip \

                $useTypeGeneric = $useType;
                $useTypeAtom = 0;
                do {
                    ++$this->id; // Skip { or ,

                    // trailing comma
                    if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_CURLY) {
                        $use->trailing = self::TRAILING;
                        continue;
                    }

                    $useType = $useTypeGeneric;
                    $useTypeAtom = 0;
                    if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CONST) {
                        // use const
                        ++$this->id;

                        $useType = 'const';
                    }

                    if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_FUNCTION) {
                        // use function
                        ++$this->id;

                        $useType = 'function';
                    }

                    $nsname = $this->processOneNsname();

                    if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_AS) {
                        // A\B as C
                        ++$this->id;
                        $this->pushExpression($nsname);
                        $alias = $this->processAlias($useType);

                        if ($useType === 'const') {
                            $nsname->fullnspath = $prefix . $nsname->fullcode;
                            $nsname->origin     = $prefix . $nsname->fullcode;

                            $alias->fullnspath  = $nsname->fullnspath;
                            $alias->origin      = $nsname->origin;
                        } else {
                            $nsname->fullnspath = $prefix . mb_strtolower($nsname->fullcode);
                            $nsname->origin     = $prefix . mb_strtolower($nsname->fullcode);

                            $alias->fullnspath  = $nsname->fullnspath;
                            $alias->origin      = $nsname->origin;
                        }

                        $aliasName = $this->addNamespaceUse($nsname, $alias, $useType, $alias);
                        $alias->alias = $aliasName;
                        $this->addLink($use, $alias, 'USE');
                    } else {
                        $this->addLink($use, $nsname, 'USE');
                        if ($useType === 'const') {
                            $nsname->fullnspath = $prefix . $nsname->fullcode;
                            $nsname->origin     = $prefix . $nsname->fullcode;
                        } else {
                            $nsname->fullnspath = $prefix . mb_strtolower($nsname->fullcode);
                            $nsname->origin     = $prefix . mb_strtolower($nsname->fullcode);
                        }

                        $alias = $this->addNamespaceUse($nsname, $nsname, $useType, $nsname);

                        $nsname->alias = $alias;
                    }

                    $nsname->use = $useType;
                    $nsname->fullcode = ($useType !== 'class' ? $useType . ' ' : '') . $nsname->fullcode;

                } while ( $this->tokens[$this->id + 1][0] === $this->phptokens::T_COMMA);

                $fullcode[] = $namespace->fullcode . self::FULLCODE_BLOCK;

                ++$this->id; // Skip }
            } else {
                $this->addLink($use, $namespace, 'USE');
                $namespace->use = $useType;

                $fullnspath = makeFullNsPath($namespace->fullcode, $useType === 'const' ? \FNP_CONSTANT : \FNP_NOT_CONSTANT);
                $namespace->fullnspath = $fullnspath;
                $namespace->origin     = $fullnspath;

                if (($use2 = $this->uses->get('class', $prefix)) instanceof AtomInterface) {
                    $this->addLink($namespace, $use2, 'DEFINITION');
                }

                $namespace->fullnspath = $fullnspath;

                $alias = $this->addNamespaceUse($alias, $alias, $useType, $namespace);

                $namespace->alias = $alias;
                $origin->alias = $alias;

                $fullcode[] = $namespace->fullcode;
            }
            // No Else. Default will be dealt with by while() condition

        } while ($this->tokens[$this->id + 1][0] === $this->phptokens::T_COMMA);

        $use->fullcode = $this->tokens[$current][1] . ($useType !== 'class' ? ' ' . $useType : '') . ' ' . implode(', ', $fullcode);

        $this->pushExpression($use);

        $this->checkExpression();

        return $use;
    }

    private function processUseTrait(): AtomInterface {
        $current = $this->id;
        $use = $this->addAtom('Usetrait', $current);

        $fullcode = array();

        --$this->id;
        $extras = array();
        do {
            ++$this->id;
            $namespace = $this->processOneNsname(self::WITHOUT_FULLNSPATH);

            $fullcode[] = $namespace->fullcode;

            $this->getFullnspath($namespace, 'class', $namespace);

            $this->calls->addCall('class', $namespace->fullnspath, $namespace);

            $this->addLink($use, $namespace, 'USE');
            $extras[] = $namespace;
        } while ($this->tokens[$this->id + 1][0] === $this->phptokens::T_COMMA);
        $fullcode = implode(', ', $fullcode);
        $this->runPlugins($use, $extras);

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_CURLY) {
            //use A\B{} // Group
            $block = $this->processUseBlock();

            $this->addLink($use, $block, 'BLOCK');
            $fullcode .= ' ' . $block->fullcode;

            // Several namespaces ? This has to be recalculated inside the block!!
            $namespace->fullnspath = makeFullNsPath($namespace->fullcode);

            // No ; at the end
            $this->processSemicolon();
        }

        $use->fullcode = $this->tokens[$current][1] . ' ' . $fullcode;
        $this->pushExpression($use);

        return $use;
    }

    private function processUseBlock(): AtomInterface {
        $this->startSequence();

        // Case for {}
        ++$this->id;
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_CURLY) {
            $void = $this->addAtomVoid();
            $this->addToSequence($void);

            ++$this->id; // skip }
        } else {
            $this->contexts->nestContext(Context::CONTEXT_NOSEQUENCE);
            do {
                $origin = $this->processOneNsname();
                $this->checkPhpdoc();
                if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_DOUBLE_COLON) {
                    ++$this->id; // skip ::
                    $this->checkPhpdoc();
                    $method =  $this->processNextAsIdentifier();

                    $class = $origin;
                    $this->getFullnspath($class, 'class', $class);
                    $this->calls->addCall('class', $class->fullnspath, $class);

                    $origin = $this->addAtom('Staticmethod', $this->id);
                    $this->addLink($origin, $class, 'CLASS');
                    $this->addLink($origin, $method, 'METHOD');

                    $origin->fullcode = "{$class->fullcode}::{$method->fullcode}";
                }
                $this->pushExpression($origin);

                $this->checkPhpdoc();
                ++$this->id;

                if ($this->tokens[$this->id][0] === $this->phptokens::T_AS) {
                    $this->processAsTrait();
                } elseif ($this->tokens[$this->id][0] === $this->phptokens::T_INSTEADOF) {
                    $this->processInsteadof();
                } else {
                    throw new UnknownCase('Usetrait without as or insteadof : ' . $this->tokens[$this->id + 1][1]);
                }

                $this->processSemicolon(); // ;
                ++$this->id;
                $this->checkPhpdoc();
            } while ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_CLOSE_CURLY);
            $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);
            ++$this->id;
        }

        $this->checkExpression();

        $block = $this->sequence;
        $this->endSequence();

        $block->code     = '{}';
        $block->fullcode = static::FULLCODE_BLOCK;
        $block->bracket  = self::BRACKET;

        return $block;
    }

    private function processVariable(): AtomInterface {
        if ($this->tokens[$this->id][1] === '$this') {
            $atom = 'This';
        } elseif (in_array($this->tokens[$this->id][1], $this->PHP_SUPERGLOBALS,
                \STRICT_COMPARISON)) {
            $atom = 'Phpvariable';
        } elseif (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_OBJECT_OPERATOR,
                                                                  $this->phptokens::T_NULLSAFE_OBJECT_OPERATOR,
                                                                 ), \STRICT_COMPARISON)) {
            $atom = 'Variableobject';
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_BRACKET) {
            $atom = 'Variablearray';
        } else {
            $atom = 'Variable';
        }
        $variable = $this->processSingle($atom);
        $this->pushExpression($variable);

        if ($atom === 'This' && ($class = end($this->currentClassTrait))) {
            $variable->fullnspath = $class->fullnspath;
            $this->calls->addCall('class', $class->fullnspath, $variable);
        }
        $this->runPlugins($variable);

        if (in_array($atom, array('Variable', 'Variableobject', 'Variablearray'), \STRICT_COMPARISON) &&
            $this->currentReturn !== null) {
            $this->addLink($this->currentReturn, $variable, 'RETURNED');
        }

        if ( !$this->contexts->isContext(Context::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG) {
            $this->processSemicolon();
        } else {
             $variable = $this->processFCOA($variable);
        }

        return $variable;
    }

    private function processFCOA(AtomInterface $nsname): AtomInterface {
        // for functions
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_PARENTHESIS) {
            return $this->processFunctioncall();
        }

        // for $a++
        if (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_INC,
                                                            $this->phptokens::T_DEC,
                                                            ),
                                \STRICT_COMPARISON)) {
            return $this->processPostPlusplus($nsname);
        }

        // for array appends
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_BRACKET &&
            $this->tokens[$this->id + 2][0] === $this->phptokens::T_CLOSE_BRACKET) {
            return $this->processAppend();
        }

        // for arrays
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_BRACKET ||
            $this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_CURLY) {

            if ($nsname->isA(array('Nsname', 'Identifier'))) {
                $type = $this->contexts->isContext(Context::CONTEXT_NEW) ? 'class' : 'const';
                if ($type === 'const') {
                    $this->getFullnspath($nsname, $type, $nsname);
                    $this->runPlugins($nsname);
                    $this->calls->addCall('const', $nsname->fullnspath, $nsname);
                }
            }

            return $this->processBracket();
        }

        // for simple identifiers
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_DOUBLE_COLON ||
            $this->tokens[$this->id + 1][0] === $this->phptokens::T_NS_SEPARATOR ||
            $this->tokens[$this->id - 1][0] === $this->phptokens::T_INSTANCEOF   ||
            $this->tokens[$this->id - 1][0] === $this->phptokens::T_AS) {
            return $nsname;
        }

        if ($nsname->atom === 'Newcall') {
            // New call, but no () : it still requires an argument count
            $nsname->count = $nsname->count ?? 0 ;

            return $nsname;
        }

        if ($nsname->isA(array('Nsname', 'Identifier'))) {
            $type = $this->contexts->isContext(Context::CONTEXT_NEW) ? 'class' : 'const';
            $this->getFullnspath($nsname, $type, $nsname);

            if ($type === 'const') {
                $this->runPlugins($nsname);
                $this->calls->addCall('const', $nsname->fullnspath, $nsname);
            }
        }

        return $nsname;
    }

    private function processAppend(): AtomInterface {
        $current = $this->id;
        $append = $this->addAtom('Arrayappend', $current);

        $left = $this->popExpression();
        $this->addLink($append, $left, 'APPEND');

        $append->fullcode = $left->fullcode . '[]';

        $this->pushExpression($append);
        $this->runPlugins($append, array('APPEND' => $left));

        ++$this->id;
        ++$this->id;

        if ( !$this->contexts->isContext(Context::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG) {
            $this->processSemicolon();
        } else {
            // Mostly for arrays
            $append = $this->processFCOA($append);
        }

        return $append;
    }

    private function processInteger(): AtomInterface {
        $integer = $this->addAtom('Integer', $this->id);

        $integer->fullcode = $this->tokens[$this->id][1];

        $this->pushExpression($integer);
        $this->runPlugins($integer);
        $this->checkExpression();

        return $integer;
    }

    private function processFloat(): AtomInterface {
        $float = $this->addAtom('Float', $this->id);

        $float->fullcode = $this->tokens[$this->id][1];

        $this->pushExpression($float);
        // (int) is for loading into the database
        $this->runPlugins($float);

        $this->checkExpression();

        return $float;
    }

    private function processLiteral(): AtomInterface {
        $literal = $this->processSingle('String');
        $this->pushExpression($literal);

        if ($this->tokens[$this->id][0] === $this->phptokens::T_CONSTANT_ENCAPSED_STRING) {
            $literal->delimiter   = $literal->code[0];
            if ($literal->delimiter === 'b' || $literal->delimiter === 'B') {
                $literal->binaryString = $literal->delimiter;
                $literal->delimiter    = $literal->code[1];
                $literal->noDelimiter  = substr($literal->code, 2, -1);
            } else {
                $literal->noDelimiter = substr($literal->code, 1, -1);
            }

            if (in_array(mb_strtolower($literal->noDelimiter),  array('parent', 'self', 'static'), \STRICT_COMPARISON)) {
                $this->getFullnspath($literal, 'class', $literal);

                $this->calls->addCall('class', $literal->fullnspath, $literal);
            } else {
                $this->calls->addNoDelimiterCall($literal);
            }
        } elseif ($this->tokens[$this->id][0] === $this->phptokens::T_NUM_STRING) {
            $literal->delimiter   = '';
            $literal->noDelimiter = $literal->code;

            $this->calls->addNoDelimiterCall($literal);
        } else {
            $literal->delimiter   = '';
            $literal->noDelimiter = '';
        }
        $this->runPlugins($literal);

        if (function_exists('mb_detect_encoding')) {
            $literal->encoding = mb_detect_encoding($literal->noDelimiter);
            if ($literal->encoding === 'UTF-8') {
                $blocks = unicode_blocks($literal->noDelimiter);
                $literal->block = array_keys($blocks)[0] ?? '';
            }
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_BRACKET) {
                $literal = $this->processBracket();
            }
        }

        if ( !$this->contexts->isContext(Context::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG) {
            $this->processSemicolon();
        } else {
            $literal = $this->processFCOA($literal);
        }

        return $literal;
    }

    private function processMagicConstant(): AtomInterface {
        $constant = $this->processSingle('Magicconstant');
        $this->pushExpression($constant);

        if (mb_strtolower($constant->fullcode) === '__dir__') {
            $path = dirname($this->filename);
            $constant->noDelimiter = $path === '/' ? '' : $path;
        } elseif (mb_strtolower($constant->fullcode) === '__file__') {
            $constant->noDelimiter = $this->filename;
        } elseif (mb_strtolower($constant->fullcode) === '__function__') {
            if (empty($this->currentFunction)) {
                $constant->noDelimiter = '';
            } else {
                $constant->noDelimiter = $this->currentFunction[count($this->currentFunction) - 1]->code;
            }
        } elseif (mb_strtolower($constant->fullcode) === '__class__') {
            if (empty($this->currentClassTrait)) {
                $constant->noDelimiter = '';
            } elseif ($this->currentClassTrait[count($this->currentClassTrait) - 1]->atom === 'Class') {
                $constant->noDelimiter = $this->currentClassTrait[count($this->currentClassTrait) - 1]->fullnspath;
            } else {
                $constant->noDelimiter = '';
            }
        } elseif (mb_strtolower($constant->fullcode) === '__trait__') {
            if (empty($this->currentClassTrait)) {
                $constant->noDelimiter = '';
            } elseif ($this->currentClassTrait[count($this->currentClassTrait) - 1]->atom === 'Trait') {
                $constant->noDelimiter = $this->currentClassTrait[count($this->currentClassTrait) - 1]->fullnspath;
            } else {
                $constant->noDelimiter = '';
            }
        } elseif (mb_strtolower($constant->fullcode) === '__line__') {
            $constant->noDelimiter = $this->tokens[$this->id][2];
        } elseif (mb_strtolower($constant->fullcode) === '__method__') {
            if (empty($this->currentClassTrait)) {
                if (count($this->currentMethod) === 1) {
                    $constant->noDelimiter = '';
                } else {
                    $constant->noDelimiter = $this->currentMethod[count($this->currentMethod) - 1]->code;
                }
            } elseif (count($this->currentMethod) === 1) {
                $constant->noDelimiter = '';
            } else {
                $constant->noDelimiter = $this->currentClassTrait[count($this->currentClassTrait) - 1]->fullnspath .
                                         '::' .
                                         $this->currentMethod[count($this->currentMethod) - 1]->code;
            }
        }

        $constant->intval  = (int) $constant->noDelimiter;
        $constant->boolean = (bool) $constant->intval;
        $this->runPlugins($constant);

        $constant = $this->processFCOA($constant);

        return $constant;
    }

    //////////////////////////////////////////////////////
    /// processing single operators
    //////////////////////////////////////////////////////
    private function processSingleOperator(AtomInterface $operator, array $finals = array(), string $link = '', string $separator = ''): AtomInterface {
        assert($link !== '', 'Link cannot be empty');

        $current = $this->id;

        $this->contexts->nestContext(Context::CONTEXT_NOSEQUENCE);
        $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        // Do while, so that AT least one loop is done.
        do {
            $operand = $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], $finals, \STRICT_COMPARISON));
        $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);

        $this->popExpression();
        $this->addLink($operator, $operand, $link);

        $operator->fullcode = $this->tokens[$current][1] . $separator . $operand->fullcode;

        $this->runPlugins($operator, array($link => $operand));
        $this->pushExpression($operator);

        $this->checkExpression();

        return $operand;
    }

    private function processCast(): AtomInterface {
        $operator = $this->addAtom('Cast', $this->id);
        $this->processSingleOperator($operator, $this->precedence->get($this->tokens[$this->id][0]), 'CAST', ' ');
        $this->popExpression();
        if (strtolower($operator->code) === '(binary)') {
            $operator->binaryString = $operator->code[1];
        }
        $this->pushExpression($operator);

        return $operator;
    }

    private function processReturn(): AtomInterface {
        $current = $this->id;
        // Case of return ;
        $return = $this->addAtom('Return', $current);

        if (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_TAG,
                                                            $this->phptokens::T_SEMICOLON,
                                                            ),
                \STRICT_COMPARISON)) {


            $returnArg = $this->addAtomVoid();
            $this->addLink($return, $returnArg, 'RETURN');

            $return->fullcode = $this->tokens[$current][1] . ' ;';

            $this->runPlugins($return, array('RETURN' => $returnArg) );

            $this->pushExpression($return);
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG) {
                $this->processSemicolon();
            }

            if (!empty($this->currentMethod) !== null) {
                $this->addLink($this->currentMethod[count($this->currentMethod) - 1], $returnArg, 'RETURNED');
            }

            return $return;
        }

        if (!empty($this->currentMethod)) {
            $this->currentReturn = $this->currentMethod[count($this->currentMethod) - 1];
        }

        $return = $this->addAtom('Return', $current);

        $this->contexts->nestContext(Context::CONTEXT_NOSEQUENCE);
        $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        $finals =  $this->precedence->get($this->tokens[$this->id][0]);
        do {
            $returned = $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], $finals, \STRICT_COMPARISON));
        $this->popExpression();

        $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);

        $this->addLink($return, $returned, 'RETURN');

        $return->fullcode = $this->tokens[$current][1] . ' ' . $returned->fullcode;

        // raw variables are done
        if (!$returned->isA(array('Variable', 'Variableobject', 'Variablearray')) &&
            $this->currentReturn !== null) {
            $this->addLink($this->currentReturn, $returned, 'RETURNED');
       }
        $this->currentReturn = null;

       $this->runPlugins($return, array('RETURN' => $returned) );

       $this->pushExpression($return);
       $this->checkExpression();

        return $return;
    }

    private function processThrow(): AtomInterface {
        $operator = $this->addAtom('Throw', $this->id);
        $this->processSingleOperator($operator, $this->precedence->get($this->tokens[$this->id][0]), 'THROW', ' ');
        $operator = $this->popExpression();
        $this->pushExpression($operator);

        $this->checkExpression();

        return $operator;
    }

    private function makeAttributes(AtomInterface $node): void {
        foreach($this->attributes as $attribute) {
            $this->addLink($node, $attribute, 'ATTRIBUTE');
        }

        $this->attributes = array();
    }

    private function makePhpdoc(AtomInterface $node): void {
        foreach($this->phpDocs as $phpdoc) {
            $this->addLink($node, $phpdoc, 'PHPDOC');
        }

        $this->phpDocs = array();
    }

    private function processYield(): AtomInterface {
        if (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_PARENTHESIS,
                                                            $this->phptokens::T_CLOSE_BRACKET,
                                                            $this->phptokens::T_COMMA,
                                                            $this->phptokens::T_SEMICOLON,
                                                            $this->phptokens::T_CLOSE_TAG,
                                   ),
                    \STRICT_COMPARISON)) {
            $current = $this->id;

            // Case of return ;
            $yieldArg = $this->addAtomVoid();
            $yield = $this->addAtom('Yield', $current);

            $this->addLink($yield, $yieldArg, 'YIELD');

            $yield->fullcode = $this->tokens[$current][1] . ' ;';

            $this->pushExpression($yield);
            $this->runPlugins($yield, array('YIELD' => $yieldArg) );

            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG) {
                $this->processSemicolon();
            }

            return $yield;
        } else {
            // => is actually a lower priority
            $finals = $this->precedence->get($this->tokens[$this->id][0]);
            $id = array_search($this->phptokens::T_DOUBLE_ARROW, $finals);
            unset($finals[$id]);
            $operator = $this->addAtom('Yield', $this->id);
            $this->processSingleOperator($operator, $finals, 'YIELD', ' ');

            return $operator;
        }
    }

    private function processYieldfrom(): AtomInterface {
        $operator = $this->addAtom('Yieldfrom', $this->id);
        $this->processSingleOperator($operator, $this->precedence->get($this->tokens[$this->id][0]), 'YIELD', ' ');

        $this->checkExpression();

        return $operator;
    }

    private function processNot(): AtomInterface {
        $finals = array_diff($this->precedence->get($this->tokens[$this->id][0]),
                             $this->assignations
                             );
        $operator = $this->addAtom('Not', $this->id);
        $this->processSingleOperator($operator, $finals, 'NOT');

        $this->checkExpression();

        return $operator;
    }

    private function processCurlyExpression(): AtomInterface {
        ++$this->id;
        while ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_CLOSE_CURLY) {
            $code = $this->processNext();
        }

        $this->popExpression();
        $block = $this->addAtom('Block', $this->id);
        $block->code     = '{}';
        $block->fullcode = '{' . $code->fullcode . '}';

        $this->addLink($block, $code, 'CODE');

        $this->runPlugins($block, array('CODE' => $code));

        ++$this->id; // Skip }

        return $block;
    }

    private function processDollar(): AtomInterface {
        $this->contexts->nestContext(Context::CONTEXT_NOSEQUENCE);
        $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_CURLY) {
            $current = $this->id;

            $variable = $this->addAtom('Variable', $current);

            ++$this->id;
            while ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_CLOSE_CURLY) {
                $this->processNext();
            }

            // Skip }
            ++$this->id;

            $expression = $this->popExpression();
            $this->addLink($variable, $expression, 'NAME');

            $variable->fullcode = $this->tokens[$current][1] . '{' . $expression->fullcode . '}';
            $this->runPlugins($variable, array('NAME' => $expression));
            $this->pushExpression($variable);

            if ( !$this->contexts->isContext(Context::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG) {
                $this->processSemicolon();
            } elseif (!in_array($this->tokens[$current - 1][0], array($this->phptokens::T_OBJECT_OPERATOR,
                                                                      $this->phptokens::T_NULLSAFE_OBJECT_OPERATOR,
                                                                      $this->phptokens::T_DOUBLE_COLON,
                                                                      ),
                        \STRICT_COMPARISON)) {
                $variable = $this->processFCOA($variable);
            }
        } else {
            $operator = $this->addAtom('Variable', $this->id);
            $this->processSingleOperator($operator, $this->precedence->get($this->tokens[$this->id][0]), 'NAME');
            $variable = $this->popExpression();

            $this->pushExpression($variable);
        }

        $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);
        $this->checkExpression();

        return $variable;
    }

    private function processClone(): AtomInterface {
        $operator = $this->addAtom('Clone', $this->id);
        $this->processSingleOperator($operator, $this->precedence->get($this->tokens[$this->id][0]), 'CLONE', ' ' );
        $operatorId = $this->popExpression();
        $this->pushExpression($operatorId);

        return $operatorId;
    }

    private function processGoto(): AtomInterface {
        $current = $this->id;

        $label = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);

        $goto = $this->addAtom('Goto', $current);
        $goto->fullcode  = $this->tokens[$current][1] . ' ' . $label->fullcode;

        $this->addLink($goto, $label, 'GOTO');

        if (empty($this->currentClassTrait)) {
            $class = '';
        } else {
            $class = end($this->currentClassTrait)->fullcode;
        }

        if (empty($this->currentFunction)) {
            $method = '';
        } else {
            $method = end($this->currentFunction)->fullnspath;
        }

        $this->runPlugins($goto, array('GOTO' => $label));
        $this->calls->addCall('goto', $class . '::' . $method . '..' . $this->tokens[$this->id][1], $goto);
        $this->pushExpression($goto);

        return $goto;
    }

    private function processNoscream(): AtomInterface {
        $atom = $this->processNext();
        $atom->noscream = true;
        $atom->fullcode = "@$atom->fullcode";

        return $atom;
    }

    private function processNew(): AtomInterface {
        $current = $this->id;

        $this->checkAttribute();

        $this->contexts->toggleContext(Context::CONTEXT_NEW);
        $noSequence = $this->contexts->isContext(Context::CONTEXT_NOSEQUENCE);
        if ($noSequence === false) {
            $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        }

        $operator = $this->addAtom('New', $current);
        $operator->fullcode = $this->tokens[$current][1];
        $newcall = $this->processSingleOperator($operator, $this->precedence->get($this->tokens[$current][0]), 'NEW', ' ');

        $this->contexts->toggleContext(Context::CONTEXT_NEW);
        if ($noSequence === false) {
            $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        }

        $operatorId = $this->popExpression();
        $this->pushExpression($operatorId);

        $this->checkExpression();

        return $operatorId;
    }

    //////////////////////////////////////////////////////
    /// processing binary operators
    //////////////////////////////////////////////////////
    private function processSign(): AtomInterface {
        $current = $this->id;
        $signExpression = $this->tokens[$this->id][1];
        $code = $signExpression . '1';
        while (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_PLUS,
                                                               $this->phptokens::T_MINUS,
                                                              ),
                    \STRICT_COMPARISON)) {
            ++$this->id;
            $signExpression = $this->tokens[$this->id][1] . $signExpression;
            $code *= $this->tokens[$this->id][1] . '1';
        }

        if (($this->tokens[$this->id + 1][0] === $this->phptokens::T_LNUMBER ||
             $this->tokens[$this->id + 1][0] === $this->phptokens::T_DNUMBER) &&
             $this->tokens[$this->id + 2][0] !== $this->phptokens::T_POW) {
            $operand = $this->processNext();

            $operand->code     = $signExpression . $operand->code;
            $operand->fullcode = $signExpression . $operand->fullcode;
            $operand->token    = $this->getToken($this->tokens[$this->id][0]);
            $this->runPlugins($operand);

            return $operand;
        }

        $finals = $this->precedence->get($this->tokens[$this->id][0]);
        $finals[] = '-';
        $finals[] = '+';

        $noSequence = $this->contexts->isContext(Context::CONTEXT_NOSEQUENCE);
        if ($noSequence === false) {
            $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        }
        do {
            $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], $finals, \STRICT_COMPARISON));
        if ($noSequence === false) {
            $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        }
        $signed = $this->popExpression();
        $firstSigned = $signed;

        for($i = strlen($signExpression) - 1; $i >= 0; --$i) {
            $sign = $this->addAtom('Sign', $current);
            $this->addLink($sign, $signed, 'SIGN');

            $sign->code     = $signExpression[$i];
            $sign->fullcode = $signExpression[$i] . $signed->fullcode;

            $signed = $sign;
        }
        $this->runPlugins($sign, array('SIGN' => $firstSigned));

        $this->pushExpression($signed);

        $this->checkExpression();
        return $signed;
    }

    private function processAddition(): AtomInterface {
        if (!$this->hasExpression() ||
            $this->tokens[$this->id - 1][0] === $this->phptokens::T_DOT
            ) {
            return $this->processSign();
        }

        $finals = $this->precedence->get($this->tokens[$this->id][0], Precedence::WITH_SELF);
        $finals = array_diff($finals, $this->assignations);
        $finals = array_unique($finals);

        return $this->processOperator('Addition', $finals, array('LEFT', 'RIGHT'));
    }

    private function processBreak(): AtomInterface {
        $current = $this->id;
        $break = $this->addAtom($this->tokens[$this->id][0] === $this->phptokens::T_BREAK ? 'Break' : 'Continue', $current);

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_LNUMBER) {
            $noSequence = $this->contexts->isContext(Context::CONTEXT_NOSEQUENCE);
            if ($noSequence === false) {
                $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
            }

            ++$this->id;
            $breakLevel = $this->processInteger();
            $this->popExpression();

            if ($noSequence === false) {
                $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
            }

        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_PARENTHESIS) {
            ++$this->id; // skip (
            $this->processNext();
            ++$this->id; // skip )

            $breakLevel = $this->popExpression();
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG ||
                  $this->tokens[$this->id + 1][0] === $this->phptokens::T_SEMICOLON ) {
            $breakLevel = $this->addAtomVoid();
        } else {
            $this->processNext();

            $breakLevel = $this->popExpression();
        }

        $link = $this->tokens[$current][0] === $this->phptokens::T_BREAK ? 'BREAK' : 'CONTINUE';
        $this->addLink($break, $breakLevel, $link);
        $break->fullcode = $this->tokens[$current][1] . ( $breakLevel->atom !== 'Void' ? ' ' . $breakLevel->fullcode : '');

        $this->runPlugins($break, array($link => $breakLevel));
        $this->pushExpression($break);

        $this->checkExpression();

        return $break;
    }

    private function processDoubleColon(): AtomInterface {
        $current = $this->id;

        $left = $this->popExpression();

        $this->contexts->nestContext(Context::CONTEXT_NEW);
        $this->contexts->nestContext(Context::CONTEXT_NOSEQUENCE);
        $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_CURLY) {
            $right = $this->processCurlyExpression();
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_DOLLAR) {
            ++$this->id; // Skip ::
            $right = $this->processDollar();
            $this->popExpression();
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLASS) {
            if ($this->tokens[$this->id + 2][0] === $this->phptokens::T_OPEN_PARENTHESIS) {
                ++$this->id;
                $right = $this->processSingle('Name');
            } else {
                $right = $this->tokens[$this->id + 1][1];
                ++$this->id; // Skip ::
            }
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_VARIABLE) {
            ++$this->id;
            $right = $this->processSingle('Staticpropertyname');
        } else {
            $right = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);
        }

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_PARENTHESIS) {
            $this->pushExpression($right);
            $right = $this->processFunctioncall(self::WITHOUT_FULLNSPATH);
            $this->popExpression();
        }

        $this->contexts->exitContext(Context::CONTEXT_NEW);
        $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);

        if (is_string($right) && mb_strtolower($right) === 'class') {
            $static = $this->addAtom('Staticclass', $current);
            $fullcode = "$left->fullcode::$right";
            if (!$left->isA(array('Functioncall', 'Methodcall', 'Staticmethodcall'))) {
                $this->getFullnspath($left, 'class', $left);
                $this->calls->addCall('class', $left->fullnspath, $left);
            }
            // We are not sending $left, as it has no impact
            $this->runPlugins($left);
            $this->runPlugins($static, array('CLASS' => $left));
            // This should actually be the value of any USE statement
            if (($use = $this->uses->get('class', mb_strtolower($left->fullcode))) instanceof AtomInterface) {
                $noDelimiter = $use->fullcode;
                if (($length = strpos($noDelimiter, ' ')) !== false) {
                    $noDelimiter = substr($noDelimiter, 0, $length);
                }
                $static->noDelimiter = $noDelimiter;
            } else {
                $static->noDelimiter = $left->fullcode;
            }
        } elseif ($right->atom === 'Name') {
            $static = $this->addAtom('Staticconstant', $current);
            $this->addLink($static, $right, 'CONSTANT');
            $fullcode = "{$left->fullcode}::{$right->fullcode}";

            if (!$left->isA(array('Functioncall', 'Methodcall', 'Staticmethodcall'))) {
                $this->getFullnspath($left, 'class', $left);
                $this->calls->addCall('class', $left->fullnspath, $left);
            }
            $static->fullnspath = "{$left->fullnspath}::{$right->fullcode}";
            $this->runPlugins($static, array('CLASS'    => $left,
                                             'CONSTANT' => $right));
        } elseif ($right->isA(array('Variable',
                                    'Array',
                                    'Arrayappend',
                                    'MagicConstant',
                                    'Concatenation',
                                    'Block',
                                    'Boolean',
                                    'Null',
                                    'Staticpropertyname',
                                    ))) {
            $static = $this->addAtom('Staticproperty', $current);

            if (!$left->isA(array('Functioncall', 'Methodcall', 'Staticmethodcall'))) {
                $this->getFullnspath($left, 'class', $left);
                $this->calls->addCall('class', $left->fullnspath, $left);
            }
            $this->addLink($static, $right, 'MEMBER');
            $fullcode = "{$left->fullcode}::{$right->fullcode}";
            $this->runPlugins($static, array('CLASS'  => $left,
                                             'MEMBER' => $right));
        } elseif ($right->atom === 'Methodcallname') {
            $static = $this->addAtom('Staticmethodcall', $current);
            $this->addLink($static, $right, 'METHOD');

            if (!$left->isA(array('Functioncall', 'Methodcall', 'Staticmethodcall'))) {
                $this->getFullnspath($left, 'class', $left);
                $this->calls->addCall('class', $left->fullnspath, $left);
            }
            $fullcode = "{$left->fullcode}::{$right->fullcode}";
            $this->runPlugins($static, array('CLASS'  => $left,
                                             'METHOD' => $right));
        } else {
            throw new LoadError('Unprocessed atom in static call (right) : ' . $right->atom . ':' . $this->filename . ':' . __LINE__);
        }

        $this->addLink($static, $left, 'CLASS');
        if ($static->atom  === 'Staticproperty'                                       &&
            in_array($left->token, array('T_STRING', 'T_STATIC'), \STRICT_COMPARISON) &&
            !empty($this->currentClassTrait)                                          &&
            !empty($this->currentClassTrait[count($this->currentClassTrait) - 1])     &&
            $left->fullnspath === $this->currentClassTrait[count($this->currentClassTrait) - 1]->fullnspath) {

            $name = ltrim($right->code, '$');
            if (!empty($name)) {
                array_collect_by($this->currentPropertiesCalls, $name, $static);
            }
        }

        if ($static->atom  === 'Staticmethodcall'                                     &&
            in_array($left->token, array('T_STRING', 'T_STATIC'), \STRICT_COMPARISON) &&
            !empty($this->currentClassTrait)                                          &&
            !empty($this->currentClassTrait[count($this->currentClassTrait) - 1])     &&
            $left->fullnspath === $this->currentClassTrait[count($this->currentClassTrait) - 1]->fullnspath) {
                array_collect_by($this->currentMethodsCalls, mb_strtolower($right->code), $static);
        }

        $static->fullcode = $fullcode;

        if (!empty($left->fullnspath)){
            if ($static->isA(array('Staticmethodcall', 'Staticmethod'))) {
                $name = mb_strtolower($right->code);
                $this->calls->addCall('staticmethod',  "$left->fullnspath::$name", $static);
            } elseif ($static->atom === 'Staticconstant') {
                $this->calls->addCall('staticconstant',  "$left->fullnspath::$right->code", $static);
            } elseif ($static->atom === 'Staticproperty' && ($right->token === 'T_VARIABLE')) {
                $this->calls->addCall('staticproperty', "$left->fullnspath::$right->code", $static);
            }
        }

        $this->pushExpression($static);

        if ( !$this->contexts->isContext(Context::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG) {
            $this->processSemicolon();
        } else {
            $static = $this->processFCOA($static);
        }

        return $static;
    }

    private function processOperator(string $atom, array $finals, array $links = array('LEFT', 'RIGHT')): AtomInterface {
        $current = $this->id;
        $operator = $this->addAtom($atom, $current);

        $left = $this->popExpression();
        $this->addLink($operator, $left, $links[0]);

        $this->contexts->nestContext(Context::CONTEXT_NOSEQUENCE);
        $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        do {
            $right = $this->processNext();

            if (in_array($this->tokens[$this->id + 1][0], $this->assignations, \STRICT_COMPARISON)) {
                $right = $this->processNext();
            }
        } while (!in_array($this->tokens[$this->id + 1][0], $finals, \STRICT_COMPARISON) );

        $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);
        $this->popExpression();

        $this->addLink($operator, $right, $links[1]);

        $operator->fullcode  = $left->fullcode . ' ' . $this->tokens[$current][1] . ' ' . $right->fullcode;

        $extras = array($links[0] => $left, $links[1] => $right);
        $this->runPlugins($operator, $extras);

        $this->pushExpression($operator);
        $this->checkExpression();

        return $operator;
    }

    private function processObjectOperator(): AtomInterface {
        $current = $this->id;

        $left = $this->popExpression();

        $this->contexts->nestContext(Context::CONTEXT_NEW);
        $this->contexts->nestContext(Context::CONTEXT_NOSEQUENCE);
        $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_CURLY) {
            $right = $this->processCurlyExpression();
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_VARIABLE) {
            ++$this->id;
            $right = $this->processSingle('Variable');
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_DOLLAR) {
            ++$this->id;
            $right = $this->processDollar();
            $this->popExpression();
        } else {
            $right = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);
        }

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_PARENTHESIS) {
            $this->pushExpression($right);
            $right = $this->processFunctioncall(self::WITHOUT_FULLNSPATH);
            $this->popExpression();
        }

        $this->contexts->exitContext(Context::CONTEXT_NEW);
        $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);

        if ($right->isA(array('Variable',
                              'Array',
                              'Name',
                              'Concatenation',
                              'Arrayappend',
                              'Member',
                              'MagicConstant',
                              'Block',
                              'Boolean',
                              'Null',
                              ))) {
            $static = $this->addAtom('Member', $current);
            $links = 'MEMBER';
            $static->enclosing = self::NO_ENCLOSING;
        } elseif ($right->isA(array('Methodcallname', 'Methodcall'))) {
            $static = $this->addAtom('Methodcall', $current);
            $links = 'METHOD';
        } else {
            throw new LoadError('Unprocessed atom in object call (right) : ' . $right->atom . ':' . $this->filename . ':' . __LINE__);
        }

        $this->addLink($static, $left, 'OBJECT');
        $this->addLink($static, $right, $links);

        $static->fullcode  = $left->fullcode . $this->tokens[$current][1] . $right->fullcode;

        if ($left->atom === 'This' ){
            if ($static->atom === 'Methodcall') {
                $this->calls->addCall('method', $left->fullnspath . '::' . mb_strtolower($right->code), $static);
                array_collect_by($this->currentMethodsCalls, mb_strtolower($right->code), $static);
            } elseif ($static->atom  === 'Member'   &&
                      $right->token  === 'T_STRING') {

                $this->calls->addCall('property', "{$left->fullnspath}::{$right->code}", $static);
                array_collect_by($this->currentPropertiesCalls, $right->code, $static);
            }
        }
        $this->runPlugins($static, array('OBJECT' => $left,
                                         $links   => $right,
                                         ));
        $this->pushExpression($static);

        if ( !$this->contexts->isContext(Context::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG) {
            $this->processSemicolon();
        } else {
            $static = $this->processFCOA($static);
        }

        return $static;
    }

    private function processAssignation(): AtomInterface {
        $finals = $this->precedence->get($this->tokens[$this->id][0]);
        $finals = array_merge($finals, $this->assignations);

        return $this->processOperator('Assignation', $finals);
    }

    private function processCoalesce(): AtomInterface {
        return $this->processOperator('Coalesce', $this->precedence->get($this->tokens[$this->id][0], Precedence::WITH_SELF));
    }

    private function processEllipsis(): AtomInterface {
        // Simply skipping the ...
        $finals = $this->precedence->get($this->phptokens::T_ELLIPSIS);
        while (!in_array($this->tokens[$this->id + 1][0], $finals, \STRICT_COMPARISON)) {
            $operand = $this->processNext();
        }

        $this->popExpression();
        $operand->fullcode  = '...' . $operand->fullcode;
        $operand->variadic  = self::VARIADIC;

        $this->pushExpression($operand);

        return $operand;
    }

    private function processAnd(): AtomInterface {
        if ($this->hasExpression()) {
            return $this->processOperator('Bitoperation', $this->precedence->get($this->tokens[$this->id][0]));
        }

        // Simply skipping the &
        $this->processNext();

        $operand = $this->popExpression();
        $operand->fullcode  = '&' . $operand->fullcode;
        $operand->reference = self::REFERENCE;

        $this->pushExpression($operand);

        return $operand;
    }

    private function processLogical(): AtomInterface {
        return $this->processOperator('Logical', $this->precedence->get($this->tokens[$this->id][0]));
    }

    private function processBitoperation(): AtomInterface {
        return $this->processOperator('Bitoperation', $this->precedence->get($this->tokens[$this->id][0]));
    }

    private function processMultiplication(): AtomInterface {
        return $this->processOperator('Multiplication', $this->precedence->get($this->tokens[$this->id][0], Precedence::WITH_SELF));
    }

    private function processPower(): AtomInterface {
        return $this->processOperator('Power', $this->precedence->get($this->tokens[$this->id][0], Precedence::WITH_SELF));
    }

    private function processComparison(): AtomInterface {
        return $this->processOperator('Comparison', $this->precedence->get($this->tokens[$this->id][0]));
    }

    private function processDot(): AtomInterface {
        $concatenation = $this->addAtom('Concatenation', $this->id);
        $fullcode      = array();
        $concat        = array();
        $noDelimiter   = '';
        $rank          = -1;

        $contains       = $this->popExpression();
        $contains->rank = ++$rank;
        $fullcode[]     = $contains->fullcode;
        $concat[]       = $contains;
        $noDelimiter   .= $contains->noDelimiter;
        $this->addLink($concatenation, $contains, 'CONCAT');

        $this->contexts->nestContext(Context::CONTEXT_NOSEQUENCE);
        $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);

        $finals = $this->precedence->get($this->tokens[$this->id][0]);
        $finals = array_diff($finals, array($this->phptokens::T_REQUIRE,
                                            $this->phptokens::T_REQUIRE_ONCE,
                                            $this->phptokens::T_INCLUDE,
                                            $this->phptokens::T_INCLUDE_ONCE,
                                            $this->phptokens::T_PRINT,
                                            $this->phptokens::T_ECHO,
                                            // This is for 'a' . -$y
                                            $this->phptokens::T_PLUS,
                                            $this->phptokens::T_MINUS,
                                            ));

        while (!in_array($this->tokens[$this->id + 1][0], $finals, \STRICT_COMPARISON)) {
            $contains = $this->processNext();

            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_DOT) {
                $this->popExpression();
                $this->addLink($concatenation, $contains, 'CONCAT');
                $fullcode[]     = $contains->fullcode;
                $concat[]       = $contains;
                $noDelimiter   .= $contains->noDelimiter;
                $contains->rank = ++$rank;

                ++$this->id;
            }
        }

        $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);

        $this->popExpression();
        $this->addLink($concatenation, $contains, 'CONCAT');
        $fullcode[]     = $contains->fullcode;
        $concat[]       = $contains;
        $noDelimiter   .= $contains->noDelimiter;
        $contains->rank = ++$rank;

        $concatenation->fullcode    = implode(' . ', $fullcode);
        $concatenation->noDelimiter = $noDelimiter;
        $concatenation->count       = $rank + 1;

        $this->pushExpression($concatenation);
        $this->runPlugins($concatenation, $concat);
        $this->calls->addNoDelimiterCall($concatenation);

        $this->checkExpression();

        return $concatenation;
    }

    private function processInstanceof(): AtomInterface {
        $current = $this->id;
        $instanceof = $this->addAtom('Instanceof', $current);

        $left = $this->popExpression();
        $this->addLink($instanceof, $left, 'VARIABLE');

        $finals = $this->precedence->get($this->tokens[$this->id][0]);
        while (!in_array($this->tokens[$this->id + 1][0], $finals, \STRICT_COMPARISON)) {
            $this->processNext();
        }
        $right = $this->popExpression();

        $this->addLink($instanceof, $right, 'CLASS');

        $this->getFullnspath($right, 'class', $right);
        $this->calls->addCall('class', $right->fullnspath, $right);

        $instanceof->fullcode = $left->fullcode . ' ' . $this->tokens[$current][1] . ' ' . $right->fullcode;

        $this->runPlugins($instanceof, array('VARIABLE' => $left,
                                             'CLASS'    => $right));
        $this->pushExpression($instanceof);

        return $instanceof;
    }

    private function processKeyvalue(): AtomInterface {
        return $this->processOperator('Keyvalue', $this->precedence->get($this->tokens[$this->id][0]), array('INDEX', 'VALUE'));
    }

    private function processPhpdoc(): AtomInterface {
        if (isset($this->phpDocs[0])) {
            $phpDoc = $this->phpDocs[0];
            $phpDoc->fullcode = $this->tokens[$this->id][1];
        } else {
            $phpDoc = $this->addAtom('Phpdoc', $this->id);
            $phpDoc->fullcode = $this->tokens[$this->id][1];

            $this->phpDocs[0] = $phpDoc;
        }

        return $phpDoc;
    }

    private function processAttribute(): AtomInterface {
        do {
            $attribute = $this->processNext();

            $this->popExpression();
            $attribute->fullcode = '#[ ' . $attribute->fullcode . ' ]';

            $this->attributes[] = $attribute;
            ++$this->id; // skip ]
        } while($this->tokens[$this->id][0] === $this->phptokens::T_COMMA);

        return $attribute;
    }

    private function processBitshift(): AtomInterface {
        // Classic bitshift expression
        return $this->processOperator('Bitshift', $this->precedence->get($this->tokens[$this->id][0]));
    }

    private function processIsset(): AtomInterface {
        $current = $this->id;

        $atom = ucfirst(mb_strtolower($this->tokens[$current][1]));
        ++$this->id;
        $argumentsList = array();
        $functioncall = $this->processArguments($atom, array(), $argumentsList);

        $argumentsFullcode = $functioncall->fullcode;

        $functioncall->code       = $this->tokens[$current][1];
        $functioncall->fullcode   = $this->tokens[$current][1] . '(' . $argumentsFullcode . ')';
        $functioncall->token      = $this->getToken($this->tokens[$current][0]);
        $functioncall->fullnspath = '\\' . mb_strtolower($this->tokens[$current][1]);

        $this->runPlugins($functioncall, $argumentsList);

        $this->pushExpression($functioncall);

        $this->checkExpression();

        return $functioncall;
    }

    private function processEcho(): AtomInterface {
        $current = $this->id;

        $argumentsList = array();
        $functioncall = $this->processArguments('Echo',
                                                array($this->phptokens::T_SEMICOLON,
                                                      $this->phptokens::T_CLOSE_TAG,
                                                      $this->phptokens::T_END,
                                                     ),
                                                $argumentsList);
        $argumentsFullcode = $functioncall->fullcode;

        $functioncall->code       = $this->tokens[$current][1];
        $functioncall->fullcode   = $this->tokens[$current][1] . ' ' . $argumentsFullcode;
        $functioncall->token      = $this->getToken($this->tokens[$current][0]);
        $functioncall->fullnspath = '\\' . mb_strtolower($this->tokens[$current][1]);

        $this->pushExpression($functioncall);

        $this->runPlugins($functioncall, $argumentsList);

        // processArguments goes too far, up to ;
        --$this->id;

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $functioncall;
    }

    private function processHalt(): AtomInterface {
        $halt = $this->addAtom('Halt', $this->id);
        $halt->fullcode = $this->tokens[$this->id][1];

        ++$this->id; // skip halt
        ++$this->id; // skip (
        // Skipping all arguments. This is not a function!

        $this->pushExpression($halt);
        ++$this->id; // skip (
        $this->processSemicolon();

        return $halt;
    }

    private function processPrint(): AtomInterface {
        $current = $this->id;

        $noSequence = $this->contexts->isContext(Context::CONTEXT_NOSEQUENCE);
        if ($noSequence === false) {
            $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        }

        $finals = $this->precedence->get($this->tokens[$this->id][0]);
        while (!in_array($this->tokens[$this->id + 1][0], $finals, \STRICT_COMPARISON)) {
            $this->processNext();
        }
        if ($noSequence === false) {
            $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        }

        if (in_array($this->tokens[$current][0], array($this->phptokens::T_INCLUDE,
                                                       $this->phptokens::T_INCLUDE_ONCE,
                                                       $this->phptokens::T_REQUIRE,
                                                       $this->phptokens::T_REQUIRE_ONCE,
                                                       ),
                \STRICT_COMPARISON)) {
            $functioncall = $this->addAtom('Include', $current);
        } else {
            $functioncall = $this->addAtom('Print', $current);
        }
        $index = $this->popExpression();
        $index->rank = 0;
        $this->addLink($functioncall, $index, 'ARGUMENT');

        $functioncall->fullcode   = $this->tokens[$current][1] . ' ' . $index->fullcode;
        $functioncall->count      = 1; // Only one argument for print
        $functioncall->fullnspath = '\\' . mb_strtolower($this->tokens[$current][1]);

        $this->pushExpression($functioncall);
        $this->runPlugins($functioncall, array('ARGUMENT' => $index));

        $this->checkExpression();

        return $functioncall;
    }

    //////////////////////////////////////////////////////
    /// generic methods
    //////////////////////////////////////////////////////
    private function addAtom(string $atomName, int $id = null): AtomInterface {
        if (!in_array($atomName, GraphElements::$ATOMS, \STRICT_COMPARISON)) {
            throw new LoadError('Undefined atom ' . $atomName . ':' . $this->filename . ':' . __LINE__);
        }

        $line = $this->tokens[$this->id][2] ?? $this->tokens[$this->id - 1][2] ?? $this->tokens[$this->id - 2][2] ?? -1;
        $atom = $this->atomGroup->factory($atomName, $line);

        if ($id !== null) {
            $atom->code  = $this->tokens[$id][1];
            $atom->token = $this->getToken($this->tokens[$id][0]);
        }

        $this->atoms[$atom->id] = $atom;
        if ($atom->id < $this->minId) {
            $this->minId = $atom->id;
        }

        return $atom;
    }

    private function addAtomVoid(): AtomInterface {
        $void = $this->addAtom('Void');
        $void->code        = 'Void';
        $void->fullcode    = self::FULLCODE_VOID;
        $void->token       = $this->phptokens::T_VOID;

        $this->runPlugins($void);

        return $void;
    }

    private function addLink(AtomInterface $origin, AtomInterface $destination, string $label): void {
        if (!in_array($label, array_merge(GraphElements::$LINKS, GraphElements::$LINKS_EXAKAT), \STRICT_COMPARISON)) {
            throw new LoadError('Undefined link ' . $label . ' for atom ' . $origin->atom . ' : ' . $this->filename . ':' . $origin->line);
        }

        if ($origin->id < $this->minId) {
            $this->relicat[] = array($origin->id, $destination->id);
        } elseif ($destination->id < $this->minId) {
            $this->relicat[] = array($origin->id, $destination->id);
        } else {
            $this->links[] = array($label, $origin->id, $destination->id);
        }
    }

    private function pushExpression(AtomInterface $atom): void {
        $this->expressions[] = $atom;
    }

    private function hasExpression(): bool {
        return !empty($this->expressions);
    }

    private function popExpression(): AtomInterface {
        if (empty($this->expressions)) {
            $id = $this->addAtomVoid();
        } else {
            $id = array_pop($this->expressions);
        }

        return $id;
    }

    private function checkTokens(string $filename): void {
        if (!empty($this->expressions)) {
            throw new LoadError( "Warning : expression is not empty in $filename : " . count($this->expressions));
        }

        if (!empty($this->options)) {
            throw new LoadError( "Warning : options is not empty in $filename : " . count($this->options));
        }

        if (($count = $this->contexts->getCount(Context::CONTEXT_NOSEQUENCE)) !== false) {
            throw new LoadError( "Warning : context for sequence is not back to 0 in $filename : it is $count\n");
        }

        if (($count = $this->contexts->getCount(Context::CONTEXT_NEW)) !== false) {
            throw new LoadError( "Warning : context for new is not back to 0 in $filename : it is $count\n");
        }

        if (($count = $this->contexts->getCount(Context::CONTEXT_FUNCTION)) !== false) {
            throw new LoadError( "Warning : context for function is not back to 0 in $filename : it is $count\n");
        }

        if (($count = $this->contexts->getCount(Context::CONTEXT_CLASS)) !== false) {
            throw new LoadError( "Warning : context for class is not back to 0 in $filename : it is $count\n");
        }

/*
        // All node has one incoming or one outgoing link (outgoing or incoming).
        // Except Variabledefinition
        $D = array();
        foreach($this->links as $label => $origins) {
            if ($label === 'DEFINITION') {
                continue;
            }
            foreach($origins as $destinations) {
                foreach($destinations as $links) {
                    $D[] = array_column($links, 'destination');
                }
            }
        }

        $D = array_merge(...$D);
        $D = array_count_values($D);

        foreach($this->atoms as $id => $atom) {
            if ($id === 1) { continue; }
            if ($atom->atom === 'Variabledefinition') { continue; }

            if (!isset($D[$id]) && $atom->atom !== 'File' && $atom->atom !== 'Virtualglobal') {
                throw new LoadError("Warning : forgotten atom $id in $this->filename : $atom->atom");
            }

            if (!isset($atom->line)) {
                throw new LoadError("Warning : missing line atom $id  in $this->filename");
            }

            if (!isset($atom->code)) {
                throw new LoadError("Warning : missing code atom $id  in $this->filename");
            }

            if (!isset($atom->token)) {
                throw new LoadError("Warning : missing token atom $id  in $this->filename");
            }
        }
        */
    }

    private function processDefineAsClassalias(array $argumentsId): void {
        if (empty($this->argumentsId[0]->noDelimiter) ||
            empty($this->argumentsId[1]->noDelimiter)   ) {
            $this->argumentsId[0]->fullnspath = '\\'; // cancels it all
            $this->argumentsId[1]->fullnspath = '\\';
            return;
        }

        if (preg_match('/[$ #?;%^\*\'\"\. <>~&,|\(\){}\[\]\/\s=+!`@\-]/is', $this->argumentsId[0]->noDelimiter)) {
            $this->argumentsId[0]->fullnspath = '\\'; // cancels it all
            $this->argumentsId[1]->fullnspath = '\\';
            return; // Can't be a class anyway.
        }

        if (preg_match('/[$ #?;%^\*\'\"\. <>~&,|\(\){}\[\]\/\s=+!`@\-]/is', $this->argumentsId[1]->noDelimiter)) {
            $this->argumentsId[0]->fullnspath = '\\'; // cancels it all
            $this->argumentsId[1]->fullnspath = '\\';
            return; // Can't be a class anyway.
        }

        $fullnspathClass = makeFullNsPath($this->argumentsId[0]->noDelimiter, \FNP_NOT_CONSTANT);
        $this->argumentsId[0]->fullnspath = $fullnspathClass;

        $fullnspathAlias = makeFullNsPath($this->argumentsId[1]->noDelimiter, \FNP_NOT_CONSTANT);
        $this->argumentsId[1]->fullnspath = $fullnspathAlias;

        $this->calls->addCall('class', $fullnspathClass, $argumentsId[0]);
        $this->calls->addDefinition('class', $fullnspathAlias, $argumentsId[1]);
    }

    private function processDefineAsConstants(AtomInterface $const, AtomInterface $name, bool $caseInsensitive = self::CASE_INSENSITIVE): void {
        if (empty($name->noDelimiter)) {
            $name->fullnspath = '\\';
            return;
        }

        if (preg_match('/[$ #?;%^\*\'\"\. <>~&,|\(\){}\[\]\/\s=+!`@\-]/is', $name->noDelimiter)) {
            return; // Can't be a constant anyway.
        }

        $fullnspath = makeFullNsPath($name->noDelimiter, \FNP_CONSTANT);
        if ($name->noDelimiter[0] === '\\') {
            // Added a second \\ when the string already has one. Actual PHP behavior
            $fullnspath = "\\$fullnspath";
        }

        $this->calls->addDefinition('const', $fullnspath, $const);
        $name->fullnspath = $fullnspath;

        if ($caseInsensitive === true) {
            $this->calls->addDefinition('const', mb_strtolower($fullnspath), $const);
        }
    }

    private function saveFiles(): void {
        $this->loader->saveFiles($this->config->tmp_dir, $this->atoms, $this->links);
        $this->reset();
    }

    private function startSequence(): void {
        $this->sequence = $this->addAtom('Sequence');
        $this->sequence->code      = ';';
        $this->sequence->fullcode  = ' ' . self::FULLCODE_SEQUENCE . ' ';
        $this->sequence->token     = 'T_SEMICOLON';
        $this->sequence->bracket   = self::NOT_BRACKET;

        $this->sequences->start($this->sequence);
    }

    private function addToSequence(AtomInterface $element): void {
        $this->addLink($this->sequence, $element, 'EXPRESSION');

        $this->sequences->add($element);
    }

    private function endSequence(): void {
        $elements = $this->sequences->getElements();
        $this->runPlugins($this->sequence, $elements);

        $this->sequence = $this->sequences->end();
    }

    // token may be string or int
    private function getToken($token): string {
        return $this->php->getTokenName($token);
    }

    private function getFullnspath(AtomInterface $name, string $type = 'class', AtomInterface $apply = null): void {
        assert($apply !== null, "\$apply can't be null in " . __METHOD__);

        // Handle static, self, parent and PHP natives function
        if (isset($name->absolute) && ($name->absolute === self::ABSOLUTE)) {
            if ($type === 'const') {
                if (($use = $this->uses->get('class', mb_strtolower($name->fullnspath))) instanceof AtomInterface) {
                    $apply->fullnspath = mb_strtolower($name->fullnspath);
                    return;
                }
                $fullnspath = preg_replace_callback('/^(.*)\\\\([^\\\\]+)$/', function (array $r): string {
                    return mb_strtolower($r[1]) . '\\' . $r[2];
                }, $name->fullcode);
                $apply->fullnspath = $fullnspath;
                return;
            }
            $apply->fullnspath = mb_strtolower($name->fullcode);

            return;
        }

        if (!$name->isA(array('Nsname', 'Identifier', 'Name', 'String', 'Null', 'Boolean', 'Static', 'Parent', 'Self', 'Newcall', 'Newcallname', 'This'))) {
            // No fullnamespace for non literal namespaces
            $apply->fullnspath = '';
            return;
        } elseif (in_array($name->token, array('T_ARRAY', 'T_EVAL', 'T_ISSET', 'T_EXIT', 'T_UNSET', 'T_ECHO', 'T_PRINT', 'T_LIST', 'T_EMPTY', ), \STRICT_COMPARISON)) {
            // For language structures, it is always in global space, like eval or list
            $apply->fullnspath = '\\' . mb_strtolower($name->code);
            return;
        } elseif (mb_strtolower(substr($name->fullcode, 0, 10)) === 'namespace\\') {

            $details = explode('\\', $name->fullcode);
            if ($type === 'const') {
                array_shift($details); // namespace
                $const = array_pop($details);
                $fullnspath = mb_strtolower(implode('\\', $details)) . '\\' . $const;
            } else {
                array_shift($details); // namespace
                $fullnspath = '\\' . mb_strtolower(implode('\\', $details));
            }

            $apply->fullnspath = substr($this->namespace, 0, -1) . $fullnspath;
            return;
        } elseif ($name->isA(array('Static', 'Self', 'This'))) {
            if (empty($this->currentClassTrait) || empty($this->currentClassTrait[count($this->currentClassTrait) - 1])) {
                $apply->fullnspath = self::FULLNSPATH_UNDEFINED;
                return;
            } else {
                $apply->fullnspath = $this->currentClassTrait[count($this->currentClassTrait) - 1]->fullnspath;
                    return;
            }
        } elseif ($name->atom === 'Newcall' && mb_strtolower($name->code) === 'static') {
            if (empty($this->currentClassTrait)) {
                $apply->fullnspath = self::FULLNSPATH_UNDEFINED;
                    return;
            } else {
                $apply->fullnspath = $this->currentClassTrait[count($this->currentClassTrait) - 1]->fullnspath;
                    return;
            }
        } elseif ($name->atom === 'Parent') {
            $apply->fullnspath = '\\parent';
            return;
        } elseif ($name->isA(array('Boolean', 'Null'))) {
            $apply->fullnspath = '\\' . mb_strtolower($name->fullcode);
                    return;
        } elseif ($name->isA(array('Identifier', 'Name', 'Newcall'))) {
            if ($name->isA(array('Newcall', 'Name'))) {
               $fnp = mb_strtolower($name->code);
            } else {
               $fnp = $name->code;
            }

            if (($offset = strpos($fnp, '\\')) === false) {
                $prefix = $fnp;
            } else {
                $prefix = substr($fnp, 0, $offset);
            }

            // This is an identifier, self or parent
            if ($type === 'class' && ($use = $this->uses->get('class',mb_strtolower($fnp) )) instanceof AtomInterface) {
                $this->addLink($name, $use, 'USED');
                $apply->fullnspath = $use->fullnspath;
                return;

            } elseif ($type === 'class' && ($use = $this->uses->get('class', $prefix)) instanceof AtomInterface) {
                $this->addLink($name, $use, 'USED');
                $apply->fullnspath = $use->fullnspath . '\\' . preg_replace('/^' . $prefix . '\\\\/', '', $fnp);
                    return;

            } elseif ($type === 'const') {
                if (($use = $this->uses->get('const', $name->code)) instanceof AtomInterface) {
                    $this->addLink($use, $name, 'USED');
                    $apply->fullnspath = $use->fullnspath;
                    return;
                } elseif (($use = $this->uses->get('class', mb_strtolower($name->fullnspath))) instanceof AtomInterface) {
                    $apply->fullnspath = mb_strtolower($name->fullnspath);
                    return;
                } else {
                    $apply->fullnspath = $this->namespace . $name->fullcode;
                    return;
                }

            } elseif ($type === 'function' && ($use = $this->uses->get('function', $prefix)) instanceof AtomInterface) {
                $this->addLink($use, $name, 'USED');
                $apply->fullnspath = $use->fullnspath;
                return;

            } else {
                $apply->fullnspath = $this->namespace . mb_strtolower($name->fullcode);
                return;
            }

        } elseif ($name->atom === 'String' && isset($name->noDelimiter)) {
            if (in_array(mb_strtolower($name->noDelimiter), array('self', 'static'), \STRICT_COMPARISON)) {
                if (empty($this->currentClassTrait)) {
                    $apply->fullnspath = self::FULLNSPATH_UNDEFINED;
                    return;
                } elseif ($this->currentClassTrait[count($this->currentClassTrait) - 1] instanceof AtomInterface) {
                    $apply->fullnspath = $this->currentClassTrait[count($this->currentClassTrait) - 1]->fullnspath;
                    return;
                } else {
                    // inside a closure
                    $apply->fullnspath = self::FULLNSPATH_UNDEFINED;
                    return;
                }
            }

            $prefix =  str_replace('\\\\', '\\', mb_strtolower($name->noDelimiter));
            $prefix = "\\$prefix";

            // define doesn't care about use...
            $apply->fullnspath = $prefix;
            return;
        } else {
            // Finally, the case for a nsname
            $prefix = mb_strtolower( substr($name->code, 0, strpos($name->code . '\\', '\\')) );

            if (($use = $this->uses->get($type, $prefix)) instanceof AtomInterface) {
                $this->addLink( $name, $use, 'USED');
                $apply->fullnspath = $use->fullnspath . mb_strtolower( substr($name->fullcode, strlen($prefix)) ) ;
                    return;
            } elseif ($type === 'const') {
                $parts = explode('\\', $name->fullcode);
                $last = array_pop($parts);
                $fullnspath = $this->namespace . mb_strtolower(implode('\\', $parts)) . '\\' . $last;
                $apply->fullnspath = $fullnspath;
                    return;
            } else {
                $apply->fullnspath = $this->namespace . mb_strtolower($name->fullcode);
                    return;
            }
        }
    }

    private function setNamespace(string $namespace = self::NO_NAMESPACE): void {
        if ($namespace === self::NO_NAMESPACE) {
            $this->namespace = '\\';
            $this->uses = new Fullnspaths();
        } else {
            $this->namespace = mb_strtolower($namespace) . '\\';
            if ($this->namespace[0] !== '\\') {
                $this->namespace = '\\' . $this->namespace;
            }
        }
    }

    private function addNamespaceUse(AtomInterface $origin, AtomInterface $alias, string $useType, AtomInterface $use): string {
        if ($origin !== $alias) { // Case of A as B
            // Alias is the 'As' expression.
            $offset = strrpos($alias->fullcode, ' as ');
            if ($useType === 'const') {
                $alias = substr($alias->fullcode, $offset + 4);
            } else {
                $alias = mb_strtolower(substr($alias->fullcode, $offset + 4));
            }
        } elseif (($offset = strrpos($alias->code, '\\')) !== false) {
            // namespace with \
            $alias = substr($alias->code, $offset + 1);
        } else {
            // namespace without \
            $alias = $alias->code;
        }

        if ($useType !== 'const') {
            $alias = mb_strtolower($alias);
        }

        $this->uses->set($useType, $alias, $use);

        return $alias;
    }

    private function logTime(string $step): void {
        static $begin, $end, $start;

        if ($this->logTimeFile === null) {
            $this->logTimeFile = fopen("{$this->config->log_dir}/load.timing.csv", 'w+');
        }

        $end = microtime(\TIME_AS_NUMBER);
        if ($begin === null) {
            $begin = $end;
            $start = $end;
        }

        fwrite($this->logTimeFile, $step . "\t" . ($end - $begin) . "\t" . ($end - $start) . PHP_EOL);
        $begin = $end;
    }

    private function makeAnonymous(string $type = 'class'): string {
        static $anonymous = 'a';

        if (!in_array($type, array('class', 'function', 'arrowfunction'), \STRICT_COMPARISON)) {
            throw new LoadError('Classes, Functions and ArrowFunctions are the only anonymous');
        }

        ++$anonymous;
        return "$type@$anonymous";
    }

    private function finishWithAlternative(bool $isColon): void {
        if ($isColon === self::ALTERNATIVE_SYNTAX) {
            ++$this->id; // Skip endforeach
            if ($this->tokens[$this->id][0] === $this->phptokens::T_CLOSE_TAG) {
                --$this->id;
            }
            $this->processSemicolon();
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_SEMICOLON) {
                ++$this->id;
            }
        } else {
            if ($this->tokens[$this->id][0] === $this->phptokens::T_CLOSE_TAG) {
                --$this->id;
            }
            $this->processSemicolon();
        }
    }

    private function checkExpression(): void {
        if ( !$this->contexts->isContext(Context::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG) {
            $this->processSemicolon();
        }
    }

    private function whichSyntax(int $current, int $colon): bool {
        return in_array($this->tokens[$current][0], array($this->phptokens::T_FOR,
                                                          $this->phptokens::T_FOREACH,
                                                          $this->phptokens::T_WHILE,
                                                          $this->phptokens::T_DO,
                                                          $this->phptokens::T_DECLARE,
                                                          $this->phptokens::T_SWITCH,
                                                          $this->phptokens::T_IF,
                                                          $this->phptokens::T_ELSEIF,
                                                         ), \STRICT_COMPARISON) &&
               ($this->tokens[$colon][0] === $this->phptokens::T_COLON) ?
                self::ALTERNATIVE_SYNTAX :
                self::NORMAL_SYNTAX;
    }

    private function makeGlobal(AtomInterface $element): void {
        if ($element->atom === 'Globaldefinition') {
            $name = $element->code;
        } elseif ($element->atom === 'Variabledefinition') {
            $name = $element->code;
        } elseif ($element->atom === 'Phpvariable') {
            $name = $element->code;
        } elseif (!empty($element->noDelimiter)) {
            $name = '$' . $element->noDelimiter;
        } else {
            return;
        }

        if (!isset($this->theGlobals[$name])) {
            $this->theGlobals[$name] = $this->addAtom('Virtualglobal');
            $this->theGlobals[$name]->fullcode = "[global {$element->code}]";
            $this->theGlobals[$name]->code = $element->code;
            $this->theGlobals[$name]->lccode = $element->code;
            $this->theGlobals[$name]->line = -1;
            $this->theGlobals[$name]->globalvar = ltrim($name, '$');
        }
    }
}

?>