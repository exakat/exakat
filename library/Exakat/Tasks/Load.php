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

namespace Exakat\Tasks;

use Exakat\Config;
use Exakat\GraphElements;
use Exakat\Exceptions\InvalidPHPBinary;
use Exakat\Exceptions\LoadError;
use Exakat\Exceptions\MustBeAFile;
use Exakat\Exceptions\MustBeADir;
use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\NoFileToProcess;
use Exakat\Exceptions\NoSuchFile;
use Exakat\Exceptions\NoSuchLoader;
use Exakat\Phpexec;
use Exakat\Tasks\LoadFinal;
use Exakat\Tasks\Helpers\Atom;
use Exakat\Tasks\Helpers\AtomGroup;
use Exakat\Tasks\Helpers\Calls;
use Exakat\Tasks\Helpers\Context;
use Exakat\Tasks\Helpers\Intval;
use Exakat\Tasks\Helpers\Strval;
use Exakat\Tasks\Helpers\Boolval;
use Exakat\Tasks\Helpers\Nullval;
use Exakat\Tasks\Helpers\Constant;
use Exakat\Tasks\Helpers\Precedence;
use Exakat\Tasks\Helpers\CloneType1;
use Exakat\Tasks\Helpers\Php;
use ProgressBar\Manager as ProgressBar;

class Load extends Tasks {
    const CONCURENCE = self::NONE;
    
    private $assignations = array();

    private $php    = null;
    private $loader = null;
    private $loaderList = array('SplitGraphson',
                                'None',
                                );

    private $precedence   = null;
    private $phptokens    = null;

    private $atomGroup = null;
    private $calls = null;

    private $namespace = '\\';
    private $uses   = array('function'       => array(),
                            'staticmethod'   => array(),
                            'method'         => array(),  // @todo : handling of parents ? of multiple definition?
                            'staticconstant' => array(),
                            'property'       => array(),
                            'staticproperty' => array(),
                            'const'          => array(),
                            'define'         => array(),
                            'class'          => array(),
                            );
    private $filename   = null;
    private $line       = 0;

    private $links = array();
    
    private $logTimeFile   = null;

    private $sequences = array();

    private $currentMethod           = array();
    private $currentFunction         = array();
    private $currentVariables        = array();
    private $currentReturn           = null;
    private $currentClassTrait       = array();
    private $currentParentClassTrait = array();

    private $tokens = array();
    private $id     = 0;
    private $id0    = null;

    const ALTERNATIVE_SYNTAX = true;
    const NORMAL_SYNTAX      = false;

    const FULLCODE_SEQUENCE = ' /**/ ';
    const FULLCODE_BLOCK    = ' { /**/ } ';
    const FULLCODE_VOID     = ' ';

    const ALIASED           = 1;
    const NOT_ALIASED       = '';
    
    const NO_LINE           = -1;

    const VARIADIC          = 1;
    const NOT_VARIADIC      = '';

    const FLEXIBLE          = 1;
    const NOT_FLEXIBLE      = false;

    const REFERENCE         = 1;
    const NOT_REFERENCE     = '';

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

    private $contexts              = null;

    private $optionsTokens       = array();
    private $expressions         = array();
    private $atoms               = array();
    private $argumentsId         = array();
    private $sequence            = array();
    private $sequenceCurrentRank = 0;
    private $sequenceRank        = array();
    
    private $processing = array();
    
    private $plugins = array();

    private $stats = array('loc'       => 0,
                           'totalLoc'  => 0,
                           'files'     => 0,
                           'tokens'    => 0);

    public function __construct($gremlin, $config, $subtask = Tasks::IS_NOT_SUBTASK) {
        parent::__construct($gremlin, $config, $subtask);

        $this->atomGroup = new AtomGroup();

        $this->contexts    = new Context();

        $this->php = new Phpexec($this->config->phpversion, $this->config->{'php'.str_replace('.', '', $this->config->phpversion)});
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
        $this->plugins[] = new Constant($this->config);
        $this->plugins[] = new CloneType1();

        $this->precedence = new Precedence(get_class($this->phptokens));

        $this->processing = array(
            $this->phptokens::T_OPEN_TAG                 => 'processOpenTag',
            $this->phptokens::T_OPEN_TAG_WITH_ECHO       => 'processOpenTag',
    
            $this->phptokens::T_DOLLAR                   => 'processDollar',
            $this->phptokens::T_VARIABLE                 => 'processVariable',
            $this->phptokens::T_LNUMBER                  => 'processInteger',
            $this->phptokens::T_DNUMBER                  => 'processReal',
    
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
            $this->phptokens::T_LIST                     => 'processArray', // Can't move to processEcho, because of omissions
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
            $this->phptokens::T_XOR                      => 'processLogical',
            $this->phptokens::T_OR                       => 'processLogical',
            $this->phptokens::T_AND                      => 'processAnd',
    
            $this->phptokens::T_BOOLEAN_AND              => 'processLogical',
            $this->phptokens::T_BOOLEAN_OR               => 'processLogical',
    
            $this->phptokens::T_QUESTION                 => 'processTernary',
            $this->phptokens::T_NS_SEPARATOR             => 'processNsname',
            $this->phptokens::T_COALESCE                 => 'processCoalesce',
    
            $this->phptokens::T_INLINE_HTML              => 'processInlinehtml',
    
            $this->phptokens::T_INC                      => 'processPlusplus',
            $this->phptokens::T_DEC                      => 'processPlusplus',
    
            $this->phptokens::T_WHILE                    => 'processWhile',
            $this->phptokens::T_DO                       => 'processDo',
            $this->phptokens::T_IF                       => 'processIfthen',
            $this->phptokens::T_FOREACH                  => 'processForeach',
            $this->phptokens::T_FOR                      => 'processFor',
            $this->phptokens::T_TRY                      => 'processTry',
            $this->phptokens::T_CONST                    => 'processConst',
            $this->phptokens::T_SWITCH                   => 'processSwitch',
            $this->phptokens::T_DEFAULT                  => 'processDefault',
            $this->phptokens::T_CASE                     => 'processCase',
            $this->phptokens::T_DECLARE                  => 'processDeclare',
    
            $this->phptokens::T_AT                       => 'processNoscream',
            $this->phptokens::T_CLONE                    => 'processClone',
            $this->phptokens::T_GOTO                     => 'processGoto',
    
            $this->phptokens::T_STRING                   => 'processString',
            $this->phptokens::T_CONSTANT_ENCAPSED_STRING => 'processLiteral',
            $this->phptokens::T_ENCAPSED_AND_WHITESPACE  => 'processLiteral',
            $this->phptokens::T_NUM_STRING               => 'processLiteral',
            $this->phptokens::T_STRING_VARNAME           => 'processVariable',
    
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
            $this->phptokens::T_CLASS                    => 'processClass',
            $this->phptokens::T_TRAIT                    => 'processTrait',
            $this->phptokens::T_INTERFACE                => 'processInterface',
            $this->phptokens::T_NAMESPACE                => 'processNamespace',
            $this->phptokens::T_USE                      => 'processUse',
            $this->phptokens::T_AS                       => 'processAs',
            $this->phptokens::T_INSTEADOF                => 'processInsteadof',
    
            $this->phptokens::T_ABSTRACT                 => 'processAbstract',
            $this->phptokens::T_FINAL                    => 'processFinal',
            $this->phptokens::T_PRIVATE                  => 'processPrivate',
            $this->phptokens::T_PROTECTED                => 'processProtected',
            $this->phptokens::T_PUBLIC                   => 'processPublic',
            $this->phptokens::T_VAR                      => 'processVar',
    
            $this->phptokens::T_QUOTE                    => 'processQuote',
            $this->phptokens::T_START_HEREDOC            => 'processQuote',
            $this->phptokens::T_BACKTICK                 => 'processQuote',
            $this->phptokens::T_DOLLAR_OPEN_CURLY_BRACES => 'processDollarCurly',
            $this->phptokens::T_STATIC                   => 'processStatic',
            $this->phptokens::T_GLOBAL                   => 'processGlobalVariable',
        );

        $this->callsDatabase = new \Sqlite3(':memory:');

        $this->calls = new Calls($this->config->projects_root, $this->callsDatabase);
    }
    
    function __destruct() {
        unset($this->callsDatabase);
        unset($this->loader);

        if (file_exists("{$this->config->projects_root}/projects/.exakat/calls.sqlite")) {
            unlink("{$this->config->projects_root}/projects/.exakat/calls.sqlite");
        }
    }

    public function runPlugins($atom, $linked = array()) {
        foreach($this->plugins as $plugin) {
            $plugin->run($atom, $linked);
        }
    }
    
    public function run() {
        $this->logTime('Start');
        $files = glob("{$this->exakatDir}/*.csv");

        foreach($files as $file) {
            unlink($file);
        }

        $this->checkTokenLimit();
        
        // Reset Atom.
        $this->id0 = $this->addAtom('Project');
        $this->id0->code      = 'Whole';
        $this->id0->atom      = 'Project';
        $this->id0->code      = $this->config->project;
        $this->id0->fullcode  = $this->config->project_name;
        $this->id0->token     = 'T_WHOLE';

        // Restart the connexion each time
        $clientClass = "\\Exakat\\Loader\\{$this->config->loader}";
        display("Loading with $clientClass\n");
        if (!class_exists($clientClass)) {
            throw new NoSuchLoader($clientClass, $this->loaderList);
        }
        $this->loader = new $clientClass($this->gremlin, $this->config, $this->callsDatabase);

        // Cleaning the databases
        $this->datastore->cleanTable('tokenCounts');
        $this->datastore->cleanTable('dictionary');
        $this->logTime('Init');

        if ($filename = $this->config->filename) {
            if (!is_file($filename)) {
                throw new MustBeAFile($filename);
            }
            $this->processFile($filename, '');
            $files = 1;
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

        $this->loader->finalize();
        $this->datastore->addRow('hash', array('status' => 'Load'));

        $loadFinal = new LoadFinal($this->gremlin, $this->config, self::IS_SUBTASK);
        $this->logTime('LoadFinal new');
        $loadFinal->run();
        $this->logTime('The End');
    }

    private function processProject($project) {
        $files = $this->datastore->getCol('files', 'file');
        if (empty($files)) {
            throw new NoFileToProcess($project, 'empty');
        }

        $nbTokens = 0;
        $path = "{$this->config->projects_root}/projects/{$project}/code";
        if ($this->config->verbose && !$this->config->quiet) {
           $progressBar = new Progressbar(0, count($files) + 1, exec('tput cols'));
        }
        foreach($files as $file) {
            try {
                $r = $this->processFile($file, $path);
                $nbTokens += $r;
                if ($this->config->verbose && !$this->config->quiet) {
                    echo $progressBar->advance();
                }
            } catch (NoFileToProcess $e) {
                $this->datastore->ignoreFile($file, $e->getMessage());
            }
        }

        if ($this->config->verbose && !$this->config->quiet) {
            echo $progressBar->advance();
        }

        return array('files'  => count($files),
                     'tokens' => $nbTokens);
    }

    private function processDir($dir) {
        if (!file_exists($dir)) {
            return array('files'  => -1,
                         'tokens' => -1);
        }

        $files = array();
        $ignoredFiles = array();
        if (substr($dir, -1) === '/') {
            $dir = substr($dir, 0, -1);
        }
        Files::findFiles($dir, $files, $ignoredFiles, $this->config);

        $this->reset();

        $nbTokens = 0;
        foreach($files as $file) {
            try {
                $r = $this->processFile($file, $dir);
                $nbTokens += $r;
            } catch (NoFileToProcess $e) {
                $this->datastore->ignoreFile($file, $e->getMessage());
            }
        }

        return array('files'  => count($files),
                     'tokens' => $nbTokens);
    }

    private function reset() {
        $this->atoms = array($this->id0->id => $this->id0);
        $this->links = array();

        $this->contexts    = new Context();
        $this->expressions = array();
        $this->uses        = array('function'       => array(),
                                   'staticmethod'   => array(),
                                   'method'         => array(),  // @todo : handling of parents ? of multiple definition?
                                   'staticconstant' => array(),
                                   'property'       => array(),
                                   'staticproperty' => array(),
                                   'const'          => array(),
                                   'define'         => array(),
                                   'class'          => array(),
                                   );

        $this->currentMethod           = array();
        $this->currentFunction         = array();
        $this->currentClassTrait       = array();
        $this->currentParentClassTrait = array();
        $this->currentVariables        = array();

        $this->tokens                  = array();
    }

    private function processFile($filename, $path) {
        $begin = microtime(true);
        $fullpath = $path.$filename;
        
        $this->filename = $filename;

        ++$this->stats['files'];

        $this->line = 0;
        $log = array();

        if (is_link($fullpath)) {
            return true;
        }
        if (!file_exists($fullpath)) {
            throw new NoFileToProcess($filename, 'unreachable file');
        }

        if (filesize($fullpath) === 0) {
            return false;
        }

        if (!$this->php->compile($fullpath)) {
            throw new NoFileToProcess($filename, 'won\'t compile');
        }

        $tokens = $this->php->getTokenFromFile($fullpath);
        $log['token_initial'] = count($tokens);

        if (count($tokens) < 3) {
            throw new NoFileToProcess($filename, 'Only '.count($tokens).' tokens');
        }

        $line = 0;
        $comments = 0;
        $this->tokens = array();
        foreach($tokens as $t) {
            if (is_array($t)) {
                if ($t[0] === $this->phptokens::T_WHITESPACE) {
                    $line += substr_count($t[1], "\n");
                } elseif ($t[0] === $this->phptokens::T_COMMENT ||
                          $t[0] === $this->phptokens::T_DOC_COMMENT) {
                    $line += substr_count($t[1], "\n");
                    $comments += substr_count($t[1], "\n");
                    continue;
                } else {
                    $line = $t[2];
                    $this->tokens[] = $t;
                }
            } else {
                $this->tokens[] = array(0 => $this->phptokens::TOKENS[$t],
                                        1 => $t,
                                        2 => $line);
            }
        }
        $this->stats['loc'] -= $comments;

        // Final token
        $this->tokens[] = array(0 => $this->phptokens::T_END,
                                1 => '/* END */',
                                2 => $line);
        $this->stats['tokens'] += count($tokens);
        unset($tokens);

        $this->uses   = array('function'       => array(),
                              'staticmethod'   => array(),
                              'method'         => array(),  // @todo : handling of parents ? of multiple definition?
                              'staticconstant' => array(),
                              'property'       => array(),
                              'staticproperty' => array(),
                              'const'          => array(),
                              'define'         => array(),
                              'class'          => array(),
                              );

        $id1 = $this->addAtom('File');
        $id1->code     = $filename;
        $id1->fullcode = $filename;
        $id1->token    = 'T_FILENAME';

        $this->addLink($this->id0, $id1, 'PROJECT');

        $this->currentMethod           = array($id1);
        $this->currentFunction         = array($id1);

        try {
            $n = count($this->tokens) - 2;
            $this->id = 0; // set to 0 so as to calculate line in the next call.
            $this->startSequence(); // At least, one sequence available
            $this->id = -1;
            do {
                $theExpression = $this->processNext();

                if ($theExpression instanceof Atom) {
                    $this->addToSequence($theExpression);
                }
            } while ($this->id < $n);

            $sequence = $this->sequence;
            $this->endSequence();

            $this->addLink($id1, $sequence, 'FILE');
            $sequence->root = true;
        } catch (LoadError $e) {
            print 'LoadError : '.$e->getMessage().PHP_EOL;
//            print_r($this->expressions[0]);
            $this->log->log('Can\'t process file \''.$this->filename.'\' during load (\''.$this->tokens[$this->id][0].'\', line \''.$this->tokens[$this->id][2].'\'). Ignoring'.PHP_EOL.$e->getMessage().PHP_EOL);
            $this->reset();
            throw new NoFileToProcess($filename, 'empty', 0, $e);
        } finally {
            $this->checkTokens($filename);
            $this->calls->save();

            $this->stats['totalLoc'] += $line;
            $this->stats['loc'] += $line;
        }

        $end = microtime(true);
        $load = ($end - $begin) * 1000;
        
        $atoms = count($this->atoms);
        $links = count($this->links);
        $begin = microtime(true);
        $this->saveFiles();
        $end = microtime(true);
        $save = ($end - $begin) * 1000;
        
        $this->log->log("$filename\t$load\t$save\t$log[token_initial]\t$atoms\t$links");
        
        return $log['token_initial'];
    }

    private function processNext() {
        ++$this->id;

        if ($this->tokens[$this->id][0] === $this->phptokens::T_END ||
            !isset($this->processing[ $this->tokens[$this->id][0] ])) {
            display("Can't process file '$this->filename' during load ('{$this->tokens[$this->id][0]}', line {$this->tokens[$this->id][2]}). Ignoring".PHP_EOL);
            $this->log->log("Can't process file '$this->filename' during load ('{$this->tokens[$this->id][0]}', line {$this->tokens[$this->id][2]}). Ignoring".PHP_EOL);

            throw new LoadError('Processing error (processNext end)');
        }
        $method = $this->processing[ $this->tokens[$this->id][0] ];
        
//        print "  $method in".PHP_EOL;
        $id = $this->$method();
//        print "  $method out ".PHP_EOL;
        
        return $id;
    }
    
    private function processColon() {
        --$this->id;
        $tag = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);
        ++$this->id;

        $label = $this->addAtom('Gotolabel');

        $this->addLink($label, $tag, 'GOTOLABEL');
        $label->code     = ':';
        $label->fullcode = $tag->fullcode.' :';
        $label->line     = $this->tokens[$this->id][2];
        $label->token    = $this->getToken($this->tokens[$this->id][0]);

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
        $this->calls->addDefinition('goto', "$class::$method..$tag->fullcode", $label);

        $this->pushExpression($label);
        $this->processSemicolon();

        return $label;
    }

    //////////////////////////////////////////////////////
    /// processing complex tokens
    //////////////////////////////////////////////////////
    private function processQuote() {
        $current = $this->id;
        $fullcode = array();
        $rank = -1;
        $elements = array();

        if ($this->tokens[$current][0] === $this->phptokens::T_QUOTE) {
            $string = $this->addAtom('String');
            $finalToken = $this->phptokens::T_QUOTE;
            $openQuote = '"';
            $closeQuote = '"';
            $type = $this->phptokens::T_QUOTE;

            $openQuote = $this->tokens[$this->id][1];
            if ($this->tokens[$current][1][0] === 'b' || $this->tokens[$current][1][0] === 'B') {
                $string->binaryString = $openQuote[0];
                $openQuote = '"';
            }
        } elseif ($this->tokens[$current][0] === $this->phptokens::T_BACKTICK) {
            $string = $this->addAtom('Shell');
            $finalToken = $this->phptokens::T_BACKTICK;
            $openQuote = '`';
            $closeQuote = '`';
            $type = $this->phptokens::T_BACKTICK;
        } elseif ($this->tokens[$current][0] === $this->phptokens::T_START_HEREDOC) {
            $string = $this->addAtom('Heredoc');
            $finalToken = $this->phptokens::T_END_HEREDOC;
            $openQuote = $this->tokens[$this->id][1];
            if ($openQuote[0] === 'b' || $openQuote[0] === 'B') {
                $string->binaryString = $openQuote[0];
                $openQuote = substr($openQuote, 1);
            }
            if ($openQuote[3] === "'") {
                $closeQuote = substr($openQuote, 4, -2);
            } else {
                $closeQuote = substr($openQuote, 3);
            }
            $type = $this->phptokens::T_START_HEREDOC;
        }
        
        // Set default, in case the whole loop is skipped
        $string->noDelimiter = '';
        $string->delimiter   = '';

        while ($this->tokens[$this->id + 1][0] !== $finalToken) {
            $currentVariable = $this->id + 1;
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CURLY_OPEN) {
                $open = $this->id + 1;
                ++$this->id; // Skip {
                while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_CURLY))) {
                    $part = $this->processNext();
                }
                ++$this->id; // Skip }
                
                $this->popExpression();
                
                $part->enclosing = self::ENCLOSING;
                $part->fullcode  = $this->tokens[$open][1].$part->fullcode.'}';
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
                } elseif (in_array($this->tokens[$this->id + 1][1], array('$GLOBALS',
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
                                                                        ))) {
                            $atom = 'Phpvariable';
                } elseif ($this->tokens[$this->id + 2][0] === $this->phptokens::T_OBJECT_OPERATOR) {
                    $atom = 'Variableobject';
                } elseif ($this->tokens[$this->id + 2][0] === $this->phptokens::T_OPEN_BRACKET) {
                    $atom = 'Variablearray';
                } else {
                    $atom = 'Variable';
                }
                ++$this->id;
                $variable = $this->processSingle($atom);

                if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OBJECT_OPERATOR) {
                    ++$this->id;

                    $propertyName = $this->processNextAsIdentifier();

                    $property = $this->addAtom('Member');
                    $property->code      = $this->tokens[$current][1];
                    $property->fullcode  = "{$variable->fullcode}->{$propertyName->fullcode}";
                    $property->line      = $this->tokens[$current][2];
                    $property->token     = $this->getToken($this->tokens[$current][0]);
                    $property->enclosing = self::NO_ENCLOSING;

                    $this->addLink($property, $variable, 'OBJECT');
                    $this->addLink($property, $propertyName, 'MEMBER');

                    $this->pushExpression($property);
                    $elements[] = $property;
                } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_BRACKET) {
                    ++$this->id; // Skip $a
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
                            $index->code     *= -1;
                            $index->fullcode *= -1;
                        }
                    } elseif ($this->tokens[$this->id][0] === $this->phptokens::T_STRING) {
                        $index = $this->processSingle('String');
                    } elseif ($this->tokens[$this->id][0] === $this->phptokens::T_VARIABLE) {
                        $index = $this->processVariable();
                        $this->popExpression();
                    } else {
                        assert(false, 'Couldn\'t read that token inside quotes : '.$this->tokens[$this->id][0]);
                    }
                    ++$this->id; // Skip ]

                    $array = $this->addAtom('Array');
                    $array->code      = $this->tokens[$current][1];
                    $array->fullcode  = "{$variable->fullcode}[{$index->fullcode}]";
                    $array->line      = $this->tokens[$current][2];
                    $array->token     = $this->getToken($this->tokens[$current][0]);
                    $array->enclosing = self::NO_ENCLOSING;

                    $this->addLink($array, $variable, 'VARIABLE');
                    $this->addLink($array, $index, 'INDEX');

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
                $elements[] = $part;
            }
            // Get the closing quote for flexibility
            $closeQuote = $this->tokens[$this->id + 1][1];
            if (trim($closeQuote) !== $closeQuote) {
                $string->flexible = 1;
            }
        }
        
        ++$this->id;
        $string->code        = $this->tokens[$current][1];
        $string->fullcode    = $string->binaryString.$openQuote.implode('', $fullcode).$closeQuote;
        $string->line        = $this->tokens[$current][2];
        $string->token       = $this->getToken($this->tokens[$current][0]);
        $string->count       = $rank + 1;

        if ($type === $this->phptokens::T_START_HEREDOC) {
            $string->delimiter = trim($closeQuote);
            $string->heredoc   = $openQuote[3] !== "'";
        }

        $this->runPlugins($string, $elements);

        $this->pushExpression($string);
        $this->checkExpression();
        
        return $string;
    }

    private function processDollarCurly() {
        $current = $this->id;
        $atom = ($this->tokens[$this->id - 1][0] === $this->phptokens::T_GLOBAL) ? 'Globaldefinition' : 'Variable';
        $variable = $this->addAtom($atom);

        ++$this->id; // Skip ${
        while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_CURLY))) {
            $this->processNext();
        }
        ++$this->id; // Skip }

        $name = $this->popExpression();
        $this->addLink($variable, $name, 'NAME');

        $variable->code      = $this->tokens[$current][1];
        $variable->fullcode  = '${'.$name->fullcode.'}';
        $variable->line      = $this->tokens[$current][2];
        $variable->token     = $this->getToken($this->tokens[$current][0]);
        $variable->enclosing = self::ENCLOSING;

        $this->checkExpression();

        return $variable;
    }

    private function processTry() {
        $current = $this->id;
        $try = $this->addAtom('Try');

        $block = $this->processFollowingBlock(array($this->phptokens::T_CLOSE_CURLY));
        $this->popExpression();
        $this->addLink($try, $block, 'BLOCK');
        $extras = array('BLOCK' => $block);

        $rank = 0;
        $fullcode = array();
        while ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CATCH) {
            $catchId = $this->id + 1;
            ++$this->id; // Skip catch
            ++$this->id; // Skip (

            $catch = $this->addAtom('Catch');
            $catchFullcode = array();
            $extrasCatch = array();
            $rankCatch = -1;
            while ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_VARIABLE) {
                $class = $this->processOneNsname();
                $this->addLink($catch, $class, 'CLASS');
                $catch->rank = ++$rankCatch;

                $this->calls->addCall('class', $class->fullnspath, $class);
                $catchFullcode[] = $class->fullcode;
                $extrasCatch['CLASS'.$rankCatch] = $class;

                if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OR) {
                    ++$this->id; // Skip |
                }
            }
            $catch->count = $rankCatch + 1;
            $catchFullcode = implode(' | ', $catchFullcode);

            // Process variable
            $this->processNext();

            $variable = $this->popExpression();
            $this->addLink($catch, $variable, 'VARIABLE');
            $extrasCatch['VARIABLE'] = $variable;

            // Skip )
            ++$this->id;

            // Skip }
            $blockCatch = $this->processFollowingBlock(array($this->phptokens::T_CLOSE_CURLY));
            $this->popExpression();
            $this->addLink($catch, $blockCatch, 'BLOCK');
            $extrasCatch['BLOCK'] = $variable;

            $catch->code     = $this->tokens[$catchId][1];
            $catch->fullcode = $this->tokens[$catchId][1].' ('.$catchFullcode.' '.$variable->fullcode.')'.static::FULLCODE_BLOCK;
            $catch->line     = $this->tokens[$catchId][2];
            $catch->token    = $this->getToken($this->tokens[$current][0]);
            $catch->rank     = ++$rank;

            $this->addLink($try, $catch, 'CATCH');
            $fullcode[] = $catch->fullcode;

            $extras['CATCH'.$rank] = $catch;
            $this->runPlugins($catch, $extrasCatch);
        }

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_FINALLY) {
            $finallyId = $this->id + 1;
            $finally = $this->addAtom('Finally');

            ++$this->id;
            $finallyBlock = $this->processFollowingBlock(array($this->phptokens::T_CLOSE_CURLY));
            $this->popExpression();
            $this->addLink($try, $finally, 'FINALLY');
            $this->addLink($finally, $finallyBlock, 'BLOCK');

            $finally->code     = $this->tokens[$finallyId][1];
            $finally->fullcode = $this->tokens[$finallyId][1].static::FULLCODE_BLOCK;
            $finally->line     = $this->tokens[$finallyId][2];
            $finally->token    = $this->getToken($this->tokens[$current][0]);

            $extras['FINALLY'] = $finally;
            $this->runPlugins($finally, array('BLOCK' => $finallyBlock));
        }

        $try->code     = $this->tokens[$current][1];
        $try->fullcode = $this->tokens[$current][1].static::FULLCODE_BLOCK.implode('', $fullcode).( isset($finallyId) ? $finally->fullcode : '');
        $try->line     = $this->tokens[$current][2];
        $try->token    = $this->getToken($this->tokens[$current][0]);
        $try->count    = $rank;

        $this->pushExpression($try);
        $this->processSemicolon();
        
        $this->runPlugins($try, $extras);
        return $try;
    }

    private function processFunction() {
        $current = $this->id;
        
        if (($this->contexts->isContext(Context::CONTEXT_CLASS) ||
             $this->contexts->isContext(Context::CONTEXT_TRAIT) ||
             $this->contexts->isContext(Context::CONTEXT_INTERFACE)) &&
             
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
                               '__debuginfo'))) {
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
        
        if ( $function->atom === 'Function') {
            list($fullnspath, $aliased) = $this->getFullnspath($name);
            $this->calls->addDefinition('function', $fullnspath, $function);
        } elseif ( $function->atom === 'Closure') {
            $fullnspath = $this->makeAnonymous('function');
            $aliased    = self::NOT_ALIASED;
            // closure may be static
            $fullcode = $this->setOptions($function);
        } elseif ( $function->atom === 'Method' || $function->atom === 'Magicmethod') {
            $fullnspath = end($this->currentClassTrait)->fullnspath.'::'.mb_strtolower($name->code);
            $aliased    = self::NOT_ALIASED;
            $fullcode = $this->setOptions($function);
            if (empty($function->visibility)) {
                $function->visibility = 'none';
            }
        } else {
            throw new LoadError(__METHOD__.' : wrong type of function '.$function->atom);
        }

        $function->line       = $this->tokens[$current][2];
        $function->token      = $this->getToken($this->tokens[$current][0]);
        $function->fullnspath = $fullnspath;
        $function->aliased    = $aliased;

        $argumentsFullcode = $function->fullcode;
        $function->reference = $reference;
        if (isset($name)) {
            $this->addLink($function, $name, 'NAME');
        }

        // Process use
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_USE) {
            ++$this->id; // Skip use
            ++$this->id; // Skip (

            $rank = 0;
            $useFullcode = array();
            do {
                ++$this->id; // Skip ( or ,
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
        }

        // Process return type
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_COLON) {
            ++$this->id;
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_QUESTION) {
                ++$this->id; // Skip NULLABLE
                $function->nullable = self::NULLABLE;
            }

            $returnType = $this->processOneNsname();
            $this->addLink($function, $returnType, 'RETURNTYPE');
        }
        
        // Process block
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_SEMICOLON) {
            $block = $this->addAtomVoid();
            $this->addLink($function, $block, 'BLOCK');
            ++$this->id; // skip the next ;
            $blockFullcode = ' ;';
            $this->runPlugins($block);
        } else {
            $block = $this->processFollowingBlock(array($this->phptokens::T_CLOSE_CURLY));
            $this->popExpression();
            $this->addLink($function, $block, 'BLOCK');
            $blockFullcode = self::FULLCODE_BLOCK;
        }

        $function->fullcode   = ($fullcode ? implode(' ', $fullcode).' ' : '').
                                $this->tokens[$current][1].' '.($function->reference ? '&' : '').
                                ($function->atom === 'Closure' ? '' : $name->fullcode).'('.$argumentsFullcode.')'.
                                (isset($useFullcode) ? ' use ('.implode(', ', $useFullcode).')' : '').// No space before use
                                (isset($returnType) ? ' : '.($function->nullable ? '?' : '').$returnType->fullcode : '').
                                $blockFullcode;

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
        } elseif ($function->atom === 'Method') {
            $this->calls->addDefinition('method', $function->fullnspath, $function);
            // double call for internal reference
            $this->calls->addDefinition('staticmethod', $function->fullnspath, $function);
        }

        return $function;
    }

    private function processOneNsname($getFullnspath = self::WITH_FULLNSPATH) {
        ++$this->id;
        if ($this->tokens[$this->id][0] === $this->phptokens::T_NAMESPACE) {
            ++$this->id;
        }
        $nsname = $this->makeNsname();
 
        if ($getFullnspath === self::WITH_FULLNSPATH) {
            list($fullnspath, $aliased) = $this->getFullnspath($nsname, 'class');
            $this->calls->addCall('class', $nsname->fullnspath, $nsname);
            $nsname->fullnspath = $fullnspath;
            $nsname->aliased    = $aliased;
        }

        return $nsname;
    }

    private function processTrait() {
        $current = $this->id;
        $trait = $this->addAtom('Trait');
        $this->currentClassTrait[] = $trait;

        $this->contexts->nestContext(Context::CONTEXT_TRAIT);
        $this->contexts->toggleContext(Context::CONTEXT_TRAIT);

        $name = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);
        $this->addLink($trait, $name, 'NAME');

        list($fullnspath, $aliased) = $this->getFullnspath($name, 'class');
        $trait->fullnspath = $fullnspath;
        $trait->aliased    = $aliased;
        $this->calls->addDefinition('class', $trait->fullnspath, $trait);

        // Process block
        $this->makeCitBody($trait);

        list($fullnspath, $aliased) = $this->getFullnspath($name);
        $trait->code       = $this->tokens[$current][1];
        $trait->fullcode   = $this->tokens[$current][1].' '.$name->fullcode.static::FULLCODE_BLOCK;
        $trait->line       = $this->tokens[$current][2];
        $trait->token      = $this->getToken($this->tokens[$current][0]);

        $this->pushExpression($trait);
        $this->processSemicolon();

        $this->contexts->exitContext(Context::CONTEXT_TRAIT);

        array_pop($this->currentClassTrait);

        return $trait;
    }

    private function processInterface() {
        $current = $this->id;
        $interface = $this->addAtom('Interface');
        $this->currentClassTrait[] = $interface;

        $this->contexts->nestContext(Context::CONTEXT_INTERFACE);
        $this->contexts->toggleContext(Context::CONTEXT_INTERFACE);

        $name = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);
        $this->addLink($interface, $name, 'NAME');

        list($fullnspath, $aliased) = $this->getFullnspath($name, 'class');
        $interface->fullnspath = $fullnspath;
        $interface->aliased    = $aliased;

        $this->calls->addDefinition('class', $fullnspath, $interface);

        // Process extends
        $rank = 0;
        $fullcode= array();
        $extends = $this->id + 1;
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_EXTENDS) {
            $extendsKeyword = $this->tokens[$this->id + 1][1];
            do {
                ++$this->id; // Skip extends or ,
                $extends = $this->processOneNsname(self::WITH_FULLNSPATH);
                $extends->rank = $rank;

                $this->addLink($interface, $extends, 'EXTENDS');
                $this->calls->addCall('class', $extends->fullnspath, $extends);

                $fullcode[] = $extends->fullcode;
            } while ($this->tokens[$this->id + 1][0] === $this->phptokens::T_COMMA);
        }

        // Process block
        $block = $this->makeCitBody($interface);

        $interface->code       = $this->tokens[$current][1];
        $interface->fullcode   = $this->tokens[$current][1].' '.$name->fullcode.(isset($extendsKeyword) ? ' '.$extendsKeyword.' '.implode(', ', $fullcode) : '').static::FULLCODE_BLOCK;
        $interface->line       = $this->tokens[$current][2];
        $interface->token      = $this->getToken($this->tokens[$current][0]);

        $this->pushExpression($interface);
        $this->processSemicolon();

        $this->contexts->exitContext(Context::CONTEXT_INTERFACE);
        array_pop($this->currentClassTrait);

        return $interface;
    }

    private function makeCitBody($class) {
        ++$this->id;
        $rank = -1;
        while($this->tokens[$this->id + 1][0] !== $this->phptokens::T_CLOSE_CURLY) {
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_SEMICOLON) {
                ++$this->id;
                continue;
            }
            
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_PRIVATE) {
                ++$this->id;
                $cpm = $this->processPrivate();

                if ($cpm instanceof Atom && $cpm->atom === 'Ppp'){
                    $cpm->rank = ++$rank;
                    $this->addLink($class, $cpm, 'PPP');
                }

                continue;
            }

            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_PUBLIC) {
                ++$this->id;
                $cpm = $this->processPublic();

                if ($cpm instanceof Atom && $cpm->atom === 'Ppp'){
                    $cpm->rank = ++$rank;
                    $this->addLink($class, $cpm, 'PPP');
                }

                continue;
            }

            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_PROTECTED) {
                ++$this->id;
                $cpm = $this->processProtected();

                if ($cpm instanceof Atom && $cpm->atom === 'Ppp'){
                    $cpm->rank = ++$rank;
                    $this->addLink($class, $cpm, 'PPP');
                }

                continue;
            }

            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_FINAL) {
                ++$this->id;
                $cpm = $this->processFinal();
                continue;
            }

            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_ABSTRACT) {
                ++$this->id;
                $cpm = $this->processAbstract();
                continue;
            }

            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_STATIC) {
                ++$this->id;
                $cpm = $this->processStatic();
                ++$this->id;

                if ($cpm instanceof Atom && $cpm->atom === 'Ppp'){
                    $cpm->rank = ++$rank;
                    $this->addLink($class, $cpm, 'PPP');
                } else {
                    --$this->id;
                }
                continue;
            }
            
            $cpm = $this->processNext();
            $this->popExpression();

            $cpm->rank = ++$rank;
            if ($cpm->atom === 'Usenamespace' ||
                $cpm->atom === 'Usetrait') {
                $link = 'USE';
            } else {
                $link = strtoupper($cpm->atom);
            }
                
            $this->addLink($class, $cpm, $link);
        }
        
        ++$this->id;
    }
    
    private function processClass() {
        $current = $this->id;
        
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_STRING) {
            $class = $this->addAtom('Class');

            $name = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);
            
            list($fullnspath, $aliased) = $this->getFullnspath($name, 'class');
            $class->fullnspath = $fullnspath;
            $class->aliased    = $aliased;

            $this->calls->addDefinition('class', $class->fullnspath, $class);
            $this->addLink($class, $name, 'NAME');
        } else {
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_PARENTHESIS) {
                // Process arguments
                ++$this->id; // Skip arguments
                $class = $this->processArguments('Classanonymous', array(), $argumentsList);
                $argumentsFullcode = $class->fullcode;
            } else {
                $class = $this->addAtom('Classanonymous');
            }

            $class->fullnspath = $this->makeAnonymous();
            $class->aliased    = self::NOT_ALIASED;
            $this->calls->addDefinition('class', $class->fullnspath, $class);
        }

        // Should work on Abstract and Final only
        $fullcode = $this->setOptions($class);

        $this->currentClassTrait[] = $class;

        $this->contexts->nestContext(Context::CONTEXT_CLASS);
        $this->contexts->toggleContext(Context::CONTEXT_CLASS);
        $this->contexts->nestContext(Context::CONTEXT_NEW);
        $this->contexts->nestContext(Context::CONTEXT_FUNCTION);

        $previousContextVariables = $this->currentVariables;
        $this->currentVariables = array();

        // Process extends
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_EXTENDS) {
            $extendsKeyword = $this->tokens[$this->id + 1][1];
            ++$this->id; // Skip extends

            $extends = $this->processOneNsname(self::WITH_FULLNSPATH);

            $this->addLink($class, $extends, 'EXTENDS');
            list($fullnspath, $aliased) = $this->getFullnspath($extends, 'class');
            $this->calls->addCall('class', $extends->fullnspath, $extends);

            $this->currentParentClassTrait[] = $extends;
            $isExtended = true;
        }

        // Process implements
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_IMPLEMENTS) {
            $implementsKeyword = $this->tokens[$this->id + 1][1];
            $fullcodeImplements = array();
            do {
                ++$this->id; // Skip implements
                $implements = $this->processOneNsname();
                $this->addLink($class, $implements, 'IMPLEMENTS');
                $fullcodeImplements[] = $implements->fullcode;

                list($fullnspath, $aliased) = $this->getFullnspath($implements);
                $this->calls->addCall('class', $fullnspath, $implements);
            } while ($this->tokens[$this->id + 1][0] === $this->phptokens::T_COMMA);
        }

        // Process block
        $this->makeCitBody($class);
        
        $class->code       = $this->tokens[$current][1];
        $class->fullcode   = (!empty($fullcode) ? implode(' ', $fullcode).' ' : '').$this->tokens[$current][1].($class->atom === 'Classanonymous' ? '' : ' '.$name->fullcode)
                             .(isset($argumentsFullcode) ? ' ('.$argumentsFullcode.')' : '')
                             .(isset($extends) ? ' '.$extendsKeyword.' '.$extends->fullcode : '')
                             .(isset($implements) ? ' '.$implementsKeyword.' '.implode(', ', $fullcodeImplements) : '')
                             .static::FULLCODE_BLOCK;
        $class->line       = $this->tokens[$current][2];
        $class->token      = $this->getToken($this->tokens[$current][0]) ;

        $this->pushExpression($class);

        // Case of anonymous classes
        if ($this->tokens[$current - 1][0] !== $this->phptokens::T_NEW) {
            $this->processSemicolon();
        }

        $this->contexts->exitContext(Context::CONTEXT_CLASS);
        $this->contexts->exitContext(Context::CONTEXT_NEW);
        $this->contexts->exitContext(Context::CONTEXT_FUNCTION);

        array_pop($this->currentClassTrait);
        if (isset($isExtended)) {
            array_pop($this->currentParentClassTrait);
        }

        $this->currentVariables = $previousContextVariables;
        return $class;
    }

    private function processOpenTag() {
        $phpcode = $this->addAtom('Php');
        $current = $this->id;

        $this->startSequence();

        // Special case for pretty much empty script (<?php .... END)
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_END) {
            $void = $this->addAtomVoid();
            $this->addToSequence($void);

            $this->addLink($phpcode, $this->sequence, 'CODE');
            $this->endSequence();
            $closing = '';

            $phpcode->code       = $this->tokens[$current][1];
            $phpcode->fullcode   = '<?php '.self::FULLCODE_SEQUENCE.' '.$closing;
            $phpcode->line       = $this->tokens[$current][2];
            $phpcode->close_tag  = self::NO_CLOSING_TAG;
            $phpcode->token      = $this->getToken($this->tokens[$current][0]);

            return $phpcode;
        }

        $n = count($this->tokens) - 2;
        if ($this->tokens[$n][0] === $this->phptokens::T_INLINE_HTML) {
            --$n;
        }

        while ($this->id < $n) {
            if ($this->tokens[$this->id][0] === $this->phptokens::T_OPEN_TAG_WITH_ECHO) {
                --$this->id;
                $this->processOpenWithEcho();
                /// processing the first expression as an echo
                $this->processSemicolon();
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
            $close_tag = self::CLOSING_TAG;
            $closing = '?>';
        } elseif ($this->tokens[$this->id][0] === $this->phptokens::T_HALT_COMPILER) {
            $close_tag = self::NO_CLOSING_TAG;
            ++$this->id; // Go to HaltCompiler
            $this->processHalt();
            $closing = '';
        } else {
            $close_tag = self::NO_CLOSING_TAG;
            $closing = '';
        }

        if ($this->tokens[$this->id - 1][0] === $this->phptokens::T_OPEN_TAG) {
            $void = $this->addAtomVoid();
            $this->addToSequence($void);
        }
        $this->addLink($phpcode, $this->sequence, 'CODE');
        $this->endSequence();

        $phpcode->code         = $this->tokens[$current][1];
        $phpcode->fullcode     = '<?php '.self::FULLCODE_SEQUENCE.' '.$closing;
        $phpcode->line         = $this->tokens[$current][2];
        $phpcode->token        = $this->getToken($this->tokens[$current][0]);
        $phpcode->close_tag    = $close_tag;

        return $phpcode;
    }

    private function processSemicolon() {
        $this->addToSequence($this->popExpression());
    }

    private function processClosingTag() {
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_INLINE_HTML &&
            in_array($this->tokens[$this->id + 2][0], array($this->phptokens::T_OPEN_TAG,
                                                            $this->phptokens::T_OPEN_TAG_WITH_ECHO,
                                                            $this->phptokens::T_INLINE_HTML,
                                                            ))) {

            // it is possible to have multiple INLINE_HTML in a row : <?php//b ? >
            do {
                ++$this->id;
                $this->processInlinehtml();
            } while( $this->tokens[$this->id + 1][0] === $this->phptokens::T_INLINE_HTML);

            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_TAG_WITH_ECHO) {
                $this->processOpenWithEcho();
                if ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_SEMICOLON) {
                    $this->processSemicolon();
                }
            } else {
                ++$this->id; // set to opening tag
            }
        } elseif (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_OPEN_TAG,
                                                                  $this->phptokens::T_OPEN_TAG_WITH_ECHO,
                                                                  ))) {
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_TAG_WITH_ECHO) {

                $this->processOpenWithEcho();
                if ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_SEMICOLON) {
                    $this->processSemicolon();
                }
            } else {
                ++$this->id; // set to opening tag
            }
        } else {
            if ($this->tokens[$this->id - 1][0] === $this->phptokens::T_OPEN_TAG) {
                $void = $this->addAtomVoid();
                $this->addToSequence($void);
            }
            ++$this->id;
        }
    }

    private function processOpenWithEcho() {
        // Processing ECHO
        $echo = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);
        $current = $this->id;

        $noSequence = $this->contexts->isContext(Context::CONTEXT_NOSEQUENCE);
        if ($noSequence === false) {
            $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        }
        $functioncall = $this->processArguments('Echo',
                                                array($this->phptokens::T_SEMICOLON,
                                                      $this->phptokens::T_CLOSE_TAG,
                                                      $this->phptokens::T_END,
                                                      ),
                                                $argumentsList);
        $argumentsFullcode = $functioncall->fullcode;

        if ($noSequence === false) {
            $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        }

        //processArguments goes too far, up to ;
        if ($this->tokens[$this->id][0] === $this->phptokens::T_CLOSE_TAG) {
            --$this->id;
        }

        $functioncall->code       = $echo->code;
        $functioncall->fullcode   = '<?= '.$argumentsFullcode;
        $functioncall->line       = $this->tokens[$current === self::NO_VALUE ? 0 : $current][2];
        $functioncall->token      = 'T_OPEN_TAG_WITH_ECHO';
        $functioncall->fullnspath = '\echo';

        $this->addLink($functioncall, $echo, 'NAME');

        $this->pushExpression($functioncall);
    }

    private function makeNsname() {
        $current = $this->id;
        
        if ($this->tokens[$this->id][0]     === $this->phptokens::T_NS_SEPARATOR             &&
            $this->tokens[$this->id + 1][0] === $this->phptokens::T_STRING                   &&
            in_array(mb_strtolower($this->tokens[$this->id + 1][1]), array('true', 'false')) &&
            $this->tokens[$this->id + 2][0] !== $this->phptokens::T_NS_SEPARATOR
            ) {

            $nsname = $this->addAtom('Boolean');

        } elseif ($this->tokens[$this->id][0]     === $this->phptokens::T_NS_SEPARATOR &&
                  $this->tokens[$this->id + 1][0] === $this->phptokens::T_STRING       &&
                  mb_strtolower($this->tokens[$this->id + 1][1]) === 'null'            &&
                  $this->tokens[$this->id + 2][0] !== $this->phptokens::T_NS_SEPARATOR ) {

            $nsname = $this->addAtom('Null');

            $nsname->noDelimiter = '';
        } elseif (mb_strtolower($this->tokens[$this->id][1]) === 'parent') {
            $nsname = $this->addAtom('Parent');
            $nsname->fullnspath = '\\parent';
        } elseif (mb_strtolower($this->tokens[$this->id][1]) === 'self') {
            $nsname = $this->addAtom('Self');
            $nsname->fullnspath = '\\self';
        } elseif ($this->tokens[$this->id][0]     === $this->phptokens::T_NS_SEPARATOR &&
                  $this->tokens[$this->id + 1][0] === $this->phptokens::T_STRING       &&
                  mb_strtolower($this->tokens[$this->id + 1][1]) === 'self'            &&
                  $this->tokens[$this->id + 2][0] !== $this->phptokens::T_NS_SEPARATOR ) {

            $nsname = $this->addAtom('Self');

            $nsname->noDelimiter = '';
        } elseif ($this->tokens[$this->id][0] === $this->phptokens::T_CALLABLE) {
            $nsname = $this->addAtom('Nsname');
            $nsname->token      = 'T_CALLABLE';
            $nsname->fullnspath = '\\callable';
        } elseif ($this->tokens[$this->id][0] === $this->phptokens::T_ARRAY) {
            $nsname = $this->addAtom('Nsname');
            $nsname->token      = 'T_ARRAY';
            $nsname->fullnspath = '\\array';
        } elseif ($this->contexts->isContext(Context::CONTEXT_NEW)) {
            $nsname = $this->addAtom('Newcall');
            $nsname->token     = 'T_STRING';
        } else {
            $nsname = $this->addAtom('Nsname');
            $nsname->token     = 'T_STRING';
        }
        
        $fullcode = array();

        if ($this->tokens[$this->id][0] === $this->phptokens::T_STRING) {
            $fullcode[] = $this->tokens[$this->id][1];
            ++$this->id;

            $nsname->absolute = self::NOT_ABSOLUTE;
        } elseif ($this->tokens[$this->id][0] === $this->phptokens::T_ARRAY    ||
                  $this->tokens[$this->id][0] === $this->phptokens::T_CALLABLE ) {
            $fullcode[] = $this->tokens[$this->id][1];

            ++$this->id;

            $nsname->absolute = self::ABSOLUTE;
        } elseif ($this->tokens[$this->id - 1][0] === $this->phptokens::T_NAMESPACE) {
            $fullcode[] = $this->tokens[$this->id - 1][1];

            $nsname->absolute = self::ABSOLUTE;
        } else {
            $fullcode[] = '';

            $nsname->absolute = self::ABSOLUTE;
        }

        while ($this->tokens[$this->id][0]     === $this->phptokens::T_NS_SEPARATOR    &&
               $this->tokens[$this->id + 1][0] !== $this->phptokens::T_OPEN_CURLY
               ) {
            ++$this->id; // skip \
            $fullcode[] = $this->tokens[$this->id][1];

            // Go to next
            ++$this->id; // skip \
            $nsname->token    = 'T_NS_SEPARATOR';
        }
        // Back up a bit
        --$this->id;

        $nsname->code     = implode('\\', $fullcode);
        $nsname->fullcode = $nsname->code;
        $nsname->line     = $this->tokens[$current][2];
        $this->runPlugins($nsname);
        
        return $nsname;
    }

    private function processNsname() {
        $current = $this->id;
        $nsname = $this->makeNsname();
        
        // Review this : most nsname will end up as constants!

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_DOUBLE_COLON ||
            $this->tokens[$this->id - 2][0] === $this->phptokens::T_INSTANCEOF) {

            list($fullnspath, $aliased) = $this->getFullnspath($nsname, 'class');
            $nsname->fullnspath = $fullnspath;
            $nsname->aliased    = $aliased;

            $this->calls->addCall('class', $fullnspath, $nsname);
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_VARIABLE ||
            (isset($this->tokens[$current - 2]) && $this->tokens[$current - 2][0] === $this->phptokens::T_INSTANCEOF)
            ) {

            list($fullnspath, $aliased) = $this->getFullnspath($nsname, 'class');
            $nsname->fullnspath = $fullnspath;
            $nsname->aliased    = $aliased;

            $this->calls->addCall('class', $fullnspath, $nsname);
        } elseif ($this->contexts->isContext(Context::CONTEXT_NEW)) {
            list($fullnspath, $aliased) = $this->getFullnspath($nsname, 'class');
            $nsname->fullnspath = $fullnspath;
            $nsname->aliased    = $aliased;

            $this->calls->addCall('class', $fullnspath, $nsname);
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_PARENTHESIS) {
            // DO nothing

        } else {
            list($fullnspath, $aliased) = $this->getFullnspath($nsname, 'const');

            $nsname->fullnspath = $fullnspath;
            $nsname->aliased    = $aliased;

            $this->calls->addCall('const', $fullnspath, $nsname);
        }
        
        $this->pushExpression($nsname);

        return $this->processFCOA($nsname);
    }

    private function processTypehint() {
        if (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_ARRAY,
                                                            $this->phptokens::T_CALLABLE,
                                                            $this->phptokens::T_STATIC))) {
            $nsname = $this->processNextAsIdentifier();
            $nsname->fullnspath = '\\'.mb_strtolower($nsname->code);

            return $nsname;
        }
        
        if (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_NS_SEPARATOR,
                                                            $this->phptokens::T_STRING,
                                                            $this->phptokens::T_NAMESPACE))) {
            $nsname = $this->processOneNsname(self::WITHOUT_FULLNSPATH);
            
            if ($this->tokens[$this->id + 1][1] === ',') {
                ++$this->id;
            }
            
            if (in_array(mb_strtolower($nsname->code), array('int', 'bool', 'void', 'float', 'string'))) {
                $nsname->fullnspath = '\\'.mb_strtolower($nsname->code);
            } else {
                list($fullnspath, $aliased) = $this->getFullnspath($nsname, 'class');

                $nsname->fullnspath = $fullnspath;
                $nsname->aliased    = $aliased;
                
                $this->calls->addCall('class', $fullnspath, $nsname);
            }
            
            return $nsname;
        }
        
        // Nothing to do, return 0 for the calling method
        return 0;
    }

    private function processParameters($atom) {
        $arguments = $this->addAtom($atom);

        $this->currentFunction[] = $arguments;
        $this->currentMethod[]   = $arguments;

        $current = $this->id;
        $argumentsId = array();

        $fullcode       = array();
        $rank           = 0;
        $args_max       = 0;
        $args_min       = 0;
        $argumentsList  = array();

        if (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_PARENTHESIS))) {
            $void = $this->addAtomVoid();
            $void->rank = 0;
            $this->addLink($arguments, $void, 'ARGUMENT');

            $arguments->code     = $this->tokens[$current][1];
            $arguments->fullcode = self::FULLCODE_VOID;
            $arguments->line     = $this->tokens[$current][2];
            $arguments->token    = $this->getToken($this->tokens[$current][0]);
            $arguments->args_max = 0;
            $arguments->args_min = 0;
            $arguments->count    = 0;
            $argumentsId[]       = $void;

            $this->runPlugins($arguments, array($void));

            $fullcode[] = $void->fullcode;

            $argumentsList[] = $void;
        } else {
            $rank       = -1;
            $default    = 0;
            $typehint   = 0;
            $nullable   = self::NOT_NULLABLE;
            $reference  = self::NOT_REFERENCE;
            $variadic   = self::NOT_ELLIPSIS;

            while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_PARENTHESIS))) {
                do {
                    ++$args_max;
                    if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_QUESTION) {
                        ++$this->id;
                        $nullable = self::NULLABLE;
                    } else {
                        $nullable = self::NOT_NULLABLE;
                    }

                    $typehint = $this->processTypehint();

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

                    $variable = $this->processSingle('Parametername');

                    $index = $this->addAtom('Parameter');
                    $index->code     = $variable->fullcode;
                    $index->fullcode = $variable->fullcode;
                    $index->line     = $this->tokens[$current][2];
                    $index->token    = 'T_VARIABLE';

                    if ($variadic === self::ELLIPSIS) {
                        $index->fullcode  = '...'.$index->fullcode;
                        $index->variadic = self::ELLIPSIS;
                    }

                    if ($reference === self::REFERENCE) {
                        $index->fullcode  = '&'.$index->fullcode;
                        $index->reference = self::REFERENCE;
                    }

                    $this->addLink($index, $variable, 'NAME');
                    $this->currentVariables[$variable->code] = $variable;

                    if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_EQUAL) {
                        ++$this->id; // Skip =
                        while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_COMMA,
                                                                                $this->phptokens::T_CLOSE_PARENTHESIS,
                                                                                $this->phptokens::T_CLOSE_CURLY,
                                                                                $this->phptokens::T_SEMICOLON,
                                                                                $this->phptokens::T_CLOSE_BRACKET,
                                                                                $this->phptokens::T_CLOSE_TAG,
                                                                                $this->phptokens::T_COLON,
                                                                                ))) {
                            $this->processNext();
                        }
                        $default = $this->popExpression();
                    } else {
                        ++$args_min;
                        $default = 0;
                    }

                    $index->rank = ++$rank;

                    if ($nullable === self::NULLABLE) {
                        $index->nullable = self::NULLABLE;
                        $this->addLink($index, $typehint, 'TYPEHINT');
                        $index->fullcode = "?$typehint->fullcode $index->fullcode";
                    } elseif ($typehint !== 0) {
                        $this->addLink($index, $typehint, 'TYPEHINT');
                        $index->fullcode = $typehint->fullcode.' '.$index->fullcode;
                    }

                    if ($default !== 0) {
                        $this->addLink($index, $default, 'DEFAULT');
                        $index->fullcode .= ' = '.$default->fullcode;
                        $default = 0;
                    }

                    $this->addLink($arguments, $index, 'ARGUMENT');
                    $argumentsId[] = $index;
                    
                    $fullcode[] = $index->fullcode;
                    $argumentsList[] = $index;

                    ++$this->id;
                } while ($this->tokens[$this->id][0] === $this->phptokens::T_COMMA);
                
                --$this->id;
            }
            $arguments->count    = $rank + 1;
        }

        // Skip the )
        ++$this->id;

        $arguments->code     = $this->tokens[$current][1];
        $arguments->fullcode = implode(', ', $fullcode);
        $arguments->line     = $this->tokens[$current][2];
        $arguments->token    = 'T_COMMA';
        $arguments->args_max = $args_max;
        $arguments->args_min = $args_min;
        $this->runPlugins($arguments, $argumentsList);
        
        return $arguments;
    }

    private function processArguments($atom, $finals = array(), &$argumentsList) {
        if (empty($finals)) {
            $finals = array($this->phptokens::T_CLOSE_PARENTHESIS);
        }
        $arguments = $this->addAtom($atom);
        $current = $this->id;
        $argumentsId = array();

        $this->contexts->nestContext(Context::CONTEXT_NEW);
        $this->contexts->nestContext(Context::CONTEXT_NOSEQUENCE);
        $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        $fullcode = array();

        if (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_PARENTHESIS,
                                                            $this->phptokens::T_CLOSE_BRACKET,
                                                            ))) {
            $void = $this->addAtomVoid();
            $void->rank = 0;
            $this->addLink($arguments, $void, 'ARGUMENT');

            $arguments->code     = $this->tokens[$current][1];
            $arguments->fullcode = self::FULLCODE_VOID;
            $arguments->line     = $this->tokens[$current][2];
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
            $args_max   = 0;
            $args_min   = 0;
            $rank       = -1;
            $argumentsList  = array();

            while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
                $initialId = $this->id;
                ++$args_max;

                while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_COMMA,
                                                                        $this->phptokens::T_CLOSE_PARENTHESIS,
                                                                        $this->phptokens::T_CLOSE_CURLY,
                                                                        $this->phptokens::T_SEMICOLON,
                                                                        $this->phptokens::T_CLOSE_BRACKET,
                                                                        $this->phptokens::T_CLOSE_TAG,
                                                                        $this->phptokens::T_COLON,
                                                                        ))) {
                    $this->processNext();
                }
                $index = $this->popExpression();
                
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
                $fullcode[] = ' ';
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

            $arguments->code     = $this->tokens[$current][1];
            $arguments->fullcode = implode(', ', $fullcode);
            $arguments->line     = $this->tokens[$current][2];
            $arguments->token    = 'T_COMMA';
            $arguments->count    = $rank + 1;
            $arguments->args_max = $args_max;
            $arguments->args_min = $args_min;
            $this->runPlugins($arguments, $argumentsList);
        }

        $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);
        $this->contexts->exitContext(Context::CONTEXT_NEW);

        return $arguments;
    }

    private function processNextAsIdentifier($getFullnspath = self::WITH_FULLNSPATH) {
        ++$this->id;

        $identifier = $this->addAtom($getFullnspath === self::WITH_FULLNSPATH ? 'Identifier' : 'Name');
        $identifier->code       = $this->tokens[$this->id][1];
        $identifier->fullcode   = $this->tokens[$this->id][1];
        $identifier->line       = $this->tokens[$this->id][2];
        $identifier->token      = $this->getToken($this->tokens[$this->id][0]);
        
        if ($getFullnspath === self::WITH_FULLNSPATH) {
            list($fullnspath, $aliased) = $this->getFullnspath($identifier, 'const');
            $identifier->fullnspath = $fullnspath;
            $identifier->aliased    = $aliased;
        }
        $this->runPlugins($identifier);

        return $identifier;
    }

    private function processConst() {
        $const = $this->addAtom('Const');
        $current = $this->id;
        $rank = -1;
        --$this->id; // back one step for the init in the next loop

        $options = $this->setOptions($const);
        if (empty($const->visibility)) {
            $const->visibility = 'none';
        }

        $fullcode = array();
        do {
            ++$this->id;
            $constId = $this->id;
            $name = $this->processNextAsIdentifier();

            ++$this->id; // Skip =
            while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_SEMICOLON,
                                                                    $this->phptokens::T_COMMA,
                                                                    ))) {
                $this->processNext();
            }
            $value = $this->popExpression();

            $def = $this->addAtom('Constant');
            $this->addLink($def, $name, 'NAME');
            $this->addLink($def, $value, 'VALUE');

            $def->code     = $this->tokens[$constId][1];
            $def->fullcode = $name->fullcode.' = '.$value->fullcode;
            $def->line     = $this->tokens[$constId][2];
            $def->token    = $this->getToken($this->tokens[$constId][0]);
            $def->rank     = ++$rank;

            $fullcode[] = $def->fullcode;
            $this->runPlugins($def, array('VALUE' => $value,
                                          'NAME'  => $name,
                                          ));

            list($fullnspath, $aliased) = $this->getFullnspath($name, 'const');
            $name->fullnspath = $fullnspath;
            $name->aliased    = $aliased;

            $this->addLink($const, $def, 'CONST');

            if ($this->contexts->isContext(Context::CONTEXT_CLASS) ||
                $this->contexts->isContext(Context::CONTEXT_INTERFACE)   ) {
                $this->calls->addDefinition('staticconstant',   end($this->currentClassTrait)->fullnspath.'::'.$name->fullcode, $def);
            } else {
                $this->calls->addDefinition('const', $fullnspath, $def);
            }

        } while ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_SEMICOLON);

        $const->code     = $this->tokens[$current][1];
        $const->fullcode = (empty($options) ? '' : implode(' ', $options).' ').$this->tokens[$current][1].' '.implode(', ', $fullcode);
        $const->line     = $this->tokens[$current][2];
        $const->token    = $this->getToken($this->tokens[$current][0]);
        $const->count    = $rank + 1;
        
        $this->pushExpression($const);

        return $this->processFCOA($const);
    }

    private function processOptions($atom) {
        $this->optionsTokens[$atom] = $this->tokens[$this->id][1];
        return $this->optionsTokens[$atom];
    }

    private function processAbstract() {
        return $this->processOptions('Abstract');
    }

    private function processFinal() {
        return $this->processOptions('Final');
    }

    private function processVar() {
        $this->optionsTokens['Var'] = $this->tokens[$this->id][1];

        $typehint = $this->processPropertyTypehint();

        $ppp = $this->processSGVariable('Ppp');

        if (isset($typehint)) {
            $this->addLink($ppp, $typehint, 'TYPEHINT');
        }

        $ppp->visibility = 'none';
        return $ppp;
    }

    private function processPublic() {
        $public = $this->processOptions('Public');

        $typehint = $this->processPropertyTypehint();

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_VARIABLE) {
            $ppp = $this->processSGVariable('Ppp');

            if (isset($typehint)) {
                $this->addLink($ppp, $typehint, 'TYPEHINT');
            }

            $this->popExpression();
            return $ppp;
        } else {
            return $public;
        }
    }

    private function processProtected() {
        $protected = $this->processOptions('Protected');

        $typehint = $this->processPropertyTypehint();

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_VARIABLE) {
            $ppp = $this->processSGVariable('Ppp');

            if (isset($typehint)) {
                $this->addLink($ppp, $typehint, 'TYPEHINT');
            }

            $this->popExpression();
            return $ppp;
        } else {
            return $protected;
        }
    }

    private function processPrivate() {
        $private = $this->processOptions('Private');

        $typehint = $this->processPropertyTypehint();
        
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_VARIABLE) {
            $ppp = $this->processSGVariable('Ppp');
            $this->popExpression();
            
            if (isset($typehint)) {
                $this->addLink($ppp, $typehint, 'TYPEHINT');
            }
            return $ppp;
        } else {
            return $private;
        }
    }
    
    private function processPropertyTypehint() {
        // PHP 7.4 typehint
        if (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_NS_SEPARATOR,
                                                            $this->phptokens::T_STRING,
                                                            $this->phptokens::T_ARRAY,
                                                            $this->phptokens::T_NAMESPACE))) {
            $typehint = $this->processOneNsname(self::WITHOUT_FULLNSPATH);
            
            if (in_array(mb_strtolower($typehint->code), array('int', 'bool', 'void', 'float', 'string', 'array'))) {
                $typehint->fullnspath = '\\'.mb_strtolower($typehint->code);
            } else {
                list($fullnspath, $aliased) = $this->getFullnspath($typehint, 'class');

                $typehint->fullnspath = $fullnspath;
                $typehint->aliased    = $aliased;
                
                $this->calls->addCall('class', $fullnspath, $typehint);
            }

            $this->optionsTokens['Typehint'] = $typehint->fullcode;
            return $typehint;
        }
        
        return null;
    }

    private function processDefineConstant($namecall) {
        $namecall->atom = 'Defineconstant';
        $namecall->fullnspath = '\\define';
        $namecall->aliased    = self::NOT_ALIASED;

        // Empty call
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_PARENTHESIS) {
            $namecall->fullcode   = $namecall->code.'( )';
            $this->pushExpression($namecall);

            $this->runPlugins($namecall, array());
            ++$this->id; // Skip )

            return $namecall;
        }

        // First argument : constant name
        ++$this->id;
        if ($this->tokens[$this->id][0] === $this->phptokens::T_CONSTANT_ENCAPSED_STRING) {
            $name = $this->processSingle('String');
            $name->delimiter   = $name->code[0];
            if ($name->delimiter === 'b' || $name->delimiter === 'B') {
                $name->binaryString = $name->delimiter;
                $name->delimiter    = $name->code[1];
                $name->noDelimiter  = substr($name->code, 2, -1);
            } else {
                $name->noDelimiter = substr($name->code, 1, -1);
            }

            $this->runPlugins($name);

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
            while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_COMMA,
                                                                    $this->phptokens::T_CLOSE_PARENTHESIS // In case of missing arguments...
                                                                    ))) {
                $this->processNext();
            }
            $name = $this->popExpression();
        }
        $this->addLink($namecall, $name, 'NAME');

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_PARENTHESIS) {
            $namecall->fullcode   = $namecall->code.'('.$name->code.')';
            $this->pushExpression($namecall);

            $this->runPlugins($namecall, array('NAME'  => $name,));
            ++$this->id; // Skip )

            return $namecall;
        }

        // Second argument constant value
        ++$this->id; // Skip ,
        while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_COMMA,
                                                                $this->phptokens::T_CLOSE_PARENTHESIS // In case of missing arguments...
                                                                ))) {
            $this->processNext();
        }
        $value = $this->popExpression();
        $this->addLink($namecall, $value, 'VALUE');

        // Most common point of exit
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_PARENTHESIS) {
            $namecall->fullcode   = $namecall->code.'('.$name->fullcode.', '.$value->fullcode.')';
            $this->pushExpression($namecall);

            $this->runPlugins($namecall, array('NAME'  => $name,
                                               'VALUE' => $value,
                                               ));
            ++$this->id; // Skip )

            $this->processDefineAsConstants($namecall, $name, false);

            return $namecall;
        }

        // Third argument : case sensitive
        ++$this->id; // Skip ,
        while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_COMMA,
                                                                $this->phptokens::T_CLOSE_PARENTHESIS // In case of missing arguments...
                                                                ))) {
            $this->processNext();
        }
        $case = $this->popExpression();
        $this->addLink($namecall, $case, 'CASE');

        $this->processDefineAsConstants($namecall, $name, (bool) $case->boolean);


        $namecall->fullcode   = $namecall->code.'('.$name->fullcode.', '.$value->fullcode.', '.$case->fullcode.')';
        $this->pushExpression($namecall);

        $this->runPlugins($namecall, array('NAME'  => $name,
                                           'VALUE' => $value,
                                           'CASE'  => $case,
                                           ));

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_PARENTHESIS) {
            ++$this->id; // Skip )

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
        
        return $namecall;
    }

    private function processFunctioncall($getFullnspath = self::WITH_FULLNSPATH) {
        $name = $this->popExpression();
        ++$this->id; // Skipping the name, set on (
        $current = $this->id;

        if ($this->contexts->isContext(Context::CONTEXT_NEW)) {
            $atom = 'Newcall';
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

        $functioncall = $this->processArguments($atom, array($this->phptokens::T_CLOSE_PARENTHESIS), $argumentsList);
        $argumentsFullcode       = $functioncall->fullcode;
        $arguments               = $functioncall;

        $functioncall->code      = $name->code;
        $functioncall->fullcode  = "{$name->fullcode}({$argumentsFullcode})";
        $functioncall->line      = $this->tokens[$current][2];
        $functioncall->token     = $name->token;
        
        if ($atom === 'Newcall') {
            list($fullnspath, $aliased) = $this->getFullnspath($name, 'class');
            $functioncall->fullnspath = $fullnspath;
            $functioncall->aliased    = $aliased;

            $this->calls->addCall('class', $fullnspath, $functioncall);
        } elseif ($atom === 'Classalias') {
            $functioncall->fullnspath = '\\classalias';
            $functioncall->aliased    = self::NOT_ALIASED;

            $this->processDefineAsClassalias($argumentsList);
        } elseif ($atom === 'Methodcallname' || $atom === 'List') {
            // literally, nothing
        } elseif (in_array(mb_strtolower($name->code), array('defined', 'constant'))) {
            if ($argumentsList[0]->constant === true &&
                !empty($argumentsList[0]->noDelimiter   )) {

                $fullnspath = makeFullNsPath($argumentsList[0]->noDelimiter, true);
                if ($argumentsList[0]->noDelimiter[0] === '\\') {
                    $fullnspath = "\\$fullnspath";
                }
                $argumentsList[0]->fullnspath = $fullnspath;
                $this->calls->addCall('const', $fullnspath, $argumentsList[0]);
            }

            $functioncall->fullnspath = '\\'.mb_strtolower($name->code);
            $functioncall->aliased    = self::NOT_ALIASED;

        } elseif ($getFullnspath === self::WITH_FULLNSPATH) { // A functioncall
            list($fullnspath, $aliased) = $this->getFullnspath($name, 'function');
            $functioncall->fullnspath = $fullnspath;
            $functioncall->aliased    = $aliased;

            $name->fullnspath = $fullnspath;
            $name->aliased    = $aliased;

            $this->calls->addCall('function', $fullnspath, $functioncall);
        } else {
            throw new LoadError("Unprocessed atom in functioncall definition (its name) : $atom->atom : $this->filename : ".__LINE__);
        }

        $this->addLink($functioncall, $name, 'NAME');
        $this->pushExpression($functioncall);

        if ( $functioncall->atom === 'Methodcallname') {
            // Nothing, really. in case of A::b()()
        } elseif ( !$this->contexts->isContext(Context::CONTEXT_NOSEQUENCE) &&
                   $this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG &&
                   $getFullnspath === self::WITH_FULLNSPATH ) {
             $this->processSemicolon();
        } else {
            $this->runPlugins($functioncall, $argumentsList);
            $functioncall = $this->processFCOA($functioncall);
        }

        return $functioncall;
    }

    private function processString() {
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_NS_SEPARATOR ) {
            return $this->processNsname();
        } elseif (in_array($this->tokens[$this->id - 1][0], array($this->phptokens::T_SEMICOLON,
                                                                  $this->phptokens::T_OPEN_CURLY,
                                                                  $this->phptokens::T_CLOSE_CURLY,
                                                                  $this->phptokens::T_COLON,
                                                                  $this->phptokens::T_OPEN_TAG,
                                                                  )) &&
                   $this->tokens[$this->id + 1][0] === $this->phptokens::T_COLON       ) {
            return $this->processColon();
        } elseif (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_AS,
                                                                  $this->phptokens::T_INSTEADOF,
                                                                  ))) {
            $string = $this->addAtom('Staticmethod'); // This is for USE with traits
        } elseif (mb_strtolower($this->tokens[$this->id][1]) === 'self') {
            $string = $this->addAtom('Self');
        } elseif (mb_strtolower($this->tokens[$this->id][1]) === 'parent') {
            $string = $this->addAtom('Parent');
        } elseif (mb_strtolower($this->tokens[$this->id][1]) === 'list') {
            $string = $this->addAtom('Name');
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_PARENTHESIS ) {
            $string = $this->addAtom('Name');
         } elseif (in_array(mb_strtolower($this->tokens[$this->id][1]), array('true', 'false'))) {
            $string = $this->addAtom('Boolean');

            $string->noDelimiter = mb_strtolower($string->code) === 'true' ? 1 : '';
        } elseif (mb_strtolower($this->tokens[$this->id][1]) === 'null') {
            $string = $this->addAtom('Null');
            $string->noDelimiter = '';
        } elseif ($this->contexts->isContext(Context::CONTEXT_NEW)) {
            $string = $this->addAtom('Newcall');
        } else {
            $string = $this->addAtom('Identifier');
        }

        $string->code       = $this->tokens[$this->id][1];
        $string->fullcode   = $this->tokens[$this->id][1];
        $string->line       = $this->tokens[$this->id][2];
        $string->token      = $this->getToken($this->tokens[$this->id][0]);
        $string->absolute   = self::NOT_ABSOLUTE;
        $this->runPlugins($string);

        $this->pushExpression($string);
        
        if (in_array($string->atom, array('Parent', 'Self', 'Static', 'Newcall'))) {
            if ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_OPEN_PARENTHESIS) {
                list($fullnspath, $aliased) = $this->getFullnspath($string, 'class');
                $string->fullnspath = $fullnspath;
                $string->aliased    = $aliased;

                $this->calls->addCall('class', $fullnspath, $string);
            }
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_DOUBLE_COLON ||
                  $this->tokens[$this->id - 1][0] === $this->phptokens::T_INSTANCEOF   ||
                  $this->tokens[$this->id - 1][0] === $this->phptokens::T_NEW
            ) {
            list($fullnspath, $aliased) = $this->getFullnspath($string, 'class');
            $string->fullnspath = $fullnspath;
            $string->aliased    = $aliased;

            $this->calls->addCall('class', $fullnspath, $string);
        } else {
            list($fullnspath, $aliased) = $this->getFullnspath($string, 'const');
            $string->fullnspath = $fullnspath;
            $string->aliased    = $aliased;

            $this->calls->addCall('const', $string->fullnspath, $string);
        }

        if ( !$this->contexts->isContext(Context::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG) {
            $this->processSemicolon();
        } else {
            $string = $this->processFCOA($string);
        }

        return $string;
    }

    private function processPlusplus() {
        if ($this->hasExpression()) {
            $previous = $this->popExpression();
            // postplusplus
            $plusplus = $this->addAtom('Postplusplus');

            $this->addLink($plusplus, $previous, 'POSTPLUSPLUS');

            $plusplus->code     = $this->tokens[$this->id][1];
            $plusplus->fullcode = $previous->fullcode.$this->tokens[$this->id][1];
            $plusplus->line     = $this->tokens[$this->id][2];
            $plusplus->token    = $this->getToken($this->tokens[$this->id][0]);

            $this->pushExpression($plusplus);
            $this->runPlugins($plusplus, array('POSTPLUSPLUS' => $previous));

            $this->checkExpression();
            
            return $plusplus;
        } else {
            // preplusplus
            $this->processSingleOperator('Preplusplus', $this->precedence->get($this->tokens[$this->id][0]), 'PREPLUSPLUS');
            $operator = $this->popExpression();
            $this->pushExpression($operator);

            $this->checkExpression();

            return $operator;
        }
    }

    private function processStatic() {
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_DOUBLE_COLON ||
            $this->tokens[$this->id - 1][0] === $this->phptokens::T_INSTANCEOF    ) {

            $identifier = $this->processSingle('Static');
            $this->pushExpression($identifier);
            list($fullnspath, $aliased) = $this->getFullnspath($identifier, 'class');
            $identifier->fullnspath = $fullnspath;
            $this->calls->addCall('class', $fullnspath, $identifier);

            return $identifier;
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_PARENTHESIS ) {
            $name = $this->addAtom('Static');
            $name->code       = $this->tokens[$this->id][1];
            $name->fullcode   = $this->tokens[$this->id][1];
            $name->line       = $this->tokens[$this->id][2];
            $name->token      = $this->getToken($this->tokens[$this->id][0]);

            list($fullnspath, $aliased) = $this->getFullnspath($name);
            $name->fullnspath = $fullnspath;
            $name->aliased    = $aliased;

            $this->pushExpression($name);

            return $this->processFunctioncall();
         } elseif (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_NS_SEPARATOR,
                                                                   $this->phptokens::T_STRING,
                                                                   $this->phptokens::T_NAMESPACE))) {
            $this->optionsTokens['Static'] = $this->tokens[$this->id][1];

            $typehint = $this->processOneNsname(self::WITHOUT_FULLNSPATH);
            $this->optionsTokens['Typehint'] = $typehint->fullcode;
            
            if (in_array(mb_strtolower($typehint->code), array('int', 'bool', 'void', 'float', 'string'))) {
                $typehint->fullnspath = '\\'.mb_strtolower($typehint->code);
            } else {
                list($fullnspath, $aliased) = $this->getFullnspath($typehint, 'class');

                $typehint->fullnspath = $fullnspath;
                $typehint->aliased    = $aliased;
                
                $this->calls->addCall('class', $fullnspath, $typehint);
            }

            $static = $this->processSGVariable('Ppp');
            $this->popExpression();

            if (empty($static->visibility)) {
                $static->visibility = 'none';
            }

            $this->addLink($static, $typehint, 'TYPEHINT');

            return $static;
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_VARIABLE) {
            if (($this->contexts->isContext(Context::CONTEXT_CLASS) ||
                 $this->contexts->isContext(Context::CONTEXT_TRAIT)   ) &&
                !$this->contexts->isContext(Context::CONTEXT_FUNCTION)) {
                // something like public static
                $this->optionsTokens['Static'] = $this->tokens[$this->id][1];

                $ppp = $this->processSGVariable('Ppp');

                if (empty($ppp->visibility)) {
                    $ppp->visibility = 'none';
                }
                $this->popExpression();

                return $ppp;
            } else {
                return $this->processStaticVariable();
            }
        } elseif ($this->contexts->isContext(Context::CONTEXT_NEW)) {
            // new static;
            $name = $this->addAtom('Newcall');
            $name->code       = $this->tokens[$this->id][1];
            $name->fullcode   = $this->tokens[$this->id][1];
            $name->line       = $this->tokens[$this->id][2];
            $name->token      = $this->getToken($this->tokens[$this->id][0]);
            
            list($fullnspath, $aliased) = $this->getFullnspath($name);
            $name->fullnspath = $fullnspath;
            $name->aliased    = $aliased;

            $this->calls->addCall('class', $fullnspath, $name);

            $this->pushExpression($name);
            return $name;
        } else {
            $r = $this->processOptions('Static');
            return $r;
        }
    }

    private function processSGVariable($atom) {
        $current = $this->id;
        $static = $this->addAtom($atom);
        $rank = 0;

        if (in_array($atom, array('Global', 'Static'))) {
            $fullcodePrefix = $this->tokens[$this->id][1];
            $link = strtoupper($atom);
            $atom .= 'definition';
        } else {
            $fullcodePrefix= array();
            $link = 'PPP';
            $atom = 'Propertydefinition';

            $fullcodePrefix = $this->setOptions($static);
            if (!isset($static->visibility)) {
                $static->visibility = 'none';
            }
            $fullcodePrefix = implode(' ', $fullcodePrefix);
        }

        if (!isset($fullcodePrefix)) {
            $fullcodePrefix = $this->tokens[$current][1];
        }

        $fullcode = array();
        $extras = array();
        --$this->id;
        do {
            ++$this->id;
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_VARIABLE) {
                ++$this->id;
                if (isset($this->currentVariables[$this->tokens[$this->id][1]])) {
                    $element = $this->currentVariables[$this->tokens[$this->id][1]];
                } else {
                    $element = $this->processSingle($atom);
                }
                
                if (in_array($element->atom, array('Globaldefinition', 'Staticdefinition', 'Variabledefinition'))) {
                    $this->addLink($this->currentMethod[count($this->currentMethod) - 1], $element, 'DEFINITION');
                    $this->currentVariables[$element->code] = $element;
                }

                if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_EQUAL) {
                    ++$this->id;
                    while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_SEMICOLON,
                                                                            $this->phptokens::T_COMMA,
                                                                            ))) {
                        $this->processNext();
                    }
                    $default = $this->popExpression();
                }
            } else {
                // global $a[2] = 2 ?
                $this->processNext();
                $element = $this->popExpression();
            }
            
            $element->rank = ++$rank;
            $this->addLink($static, $element, $link);
            
            if ($atom === 'Propertydefinition') {
                // drop $
                $element->propertyname = substr($element->code, 1);
                $type = ($static->static === 1 ? 'static' : '').'property';
                $this->calls->addDefinition($type,  end($this->currentClassTrait)->fullnspath.'::'.$element->code, $element);
            }

            if (isset($default)) {
                $this->addLink($element, $default, 'DEFAULT');
                $element->fullcode .= " = {$default->fullcode}";
                $this->runPlugins($element, array('DEFAULT' => $default));
                unset($default);
            } else {
                $this->runPlugins($element);
            }
            $fullcode[] = $element->fullcode;
            $extras[] = $element;
        }  while ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_SEMICOLON &&
                  $this->tokens[$this->id + 1][0] !== $this->phptokens::T_CLOSE_TAG);

        $static->code     = $this->tokens[$current][1];
        $static->fullcode = $fullcodePrefix.' '.implode(', ', $fullcode);
        $static->line     = $this->tokens[$current][2];
        $static->token    = $this->getToken($this->tokens[$current][0]);
        $static->count    = $rank;
        $this->runPlugins($static, $extras);
        
        $this->pushExpression($static);

        $this->checkExpression();

        return $static;
    }

    private function processStaticVariable() {
        return $this->processSGVariable('Static');
    }

    private function processGlobalVariable() {
        return $this->processSGVariable('Global');
    }

    private function processBracket() {
        $bracket = $this->addAtom('Array');
        $current = $this->id;

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
        if ($this->contexts->isContext(Context::CONTEXT_NEW)) {
            $resetContext = true;
            $this->contexts->toggleContext(Context::CONTEXT_NEW);
        }
        do {
            $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_BRACKET,
                                                                  $this->phptokens::T_CLOSE_CURLY,
                                                                  ))) ;
        if (isset($resetContext)) {
            $this->contexts->toggleContext(Context::CONTEXT_NEW);
        }

        // Skip closing bracket
        ++$this->id;

        $index = $this->popExpression();
        $this->addLink($bracket, $index, 'INDEX');

        if ($variable->code === '$GLOBALS' && !empty($index->noDelimiter)) {
            // Build the name of the global, dropping the fi
            $bracket->globalvar = '$'.$index->noDelimiter;
        }

        $bracket->code      = $opening;
        $bracket->fullcode  = $variable->fullcode.$opening.$index->fullcode.$closing ;
        $bracket->line      = $this->tokens[$current][2];
        $bracket->token     = $this->getToken($this->tokens[$current][0]);
        $bracket->enclosing = self::NO_ENCLOSING;
        $this->pushExpression($bracket);
        $this->runPlugins($bracket, array('VARIABLE' => $variable,
                                          'INDEX'    => $index));

        $bracket = $this->processFCOA($bracket);
        $this->checkExpression();

        return $bracket;
    }

    private function processBlock($standalone = self::STANDALONE_BLOCK) {
        $this->startSequence();

        // Case for {}
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_CURLY) {
            $void = $this->addAtomVoid();
            $this->addToSequence($void);
        } else {
            $this->contexts->nestContext(Context::CONTEXT_NOSEQUENCE);
            while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_CURLY))) {
                $this->processNext();
            }
            $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);

            $this->checkExpression();
        }

        $block = $this->sequence;
        $this->endSequence();

        $block->code     = '{}';
        $block->fullcode = static::FULLCODE_BLOCK;
        $block->line     = $this->tokens[$this->id][2];
        $block->token    = $this->getToken($this->tokens[$this->id][0]);
        $block->bracket  = self::BRACKET;

        ++$this->id; // skip }

        $this->pushExpression($block);
        if ($standalone === self::STANDALONE_BLOCK) {
            $this->processSemicolon();
        }

        return $block;
    }

    private function processForblock($finals) {
        $this->startSequence();
        $block = $this->sequence;

        while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
            $element = $this->processNext();
            
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_COMMA) {
                $element = $this->popExpression();
                $this->addToSequence($element);

                ++$this->id;
            }
        }
        $element = $this->popExpression();
        $this->addToSequence($element);

        ++$this->id;
        $current = $this->sequence;
        $this->endSequence();
        $block->code     = $current->code;
        $block->fullcode = self::FULLCODE_SEQUENCE;
        $block->line     = $this->tokens[$this->id][2];
        $block->token    = $this->getToken($this->tokens[$this->id][0]);

        if ($current->count === 1) {
            $block->fullcode = $element->fullcode;
        }
        $this->pushExpression($block);

        return $block;
    }

    private function processFor() {
        $for = $this->addAtom('For');
        $current = $this->id;
        ++$this->id; // Skip for

        $this->processForblock(array($this->phptokens::T_SEMICOLON));
        $init = $this->popExpression();
        $this->addLink($for, $init, 'INIT');

        $this->processForblock(array($this->phptokens::T_SEMICOLON));
        $final = $this->popExpression();
        $this->addLink($for, $final, 'FINAL');

        $this->processForblock(array($this->phptokens::T_CLOSE_PARENTHESIS));
        $increment = $this->popExpression();
        $this->addLink($for, $increment, 'INCREMENT');

        $isColon = $this->whichSyntax($current, $this->id + 1);

        $block = $this->processFollowingBlock($isColon === self::ALTERNATIVE_SYNTAX ? array($this->phptokens::T_ENDFOR) : array());
        $this->popExpression();
        $this->addLink($for, $block, 'BLOCK');

        $code = $this->tokens[$current][1];
        if ($isColon === self::ALTERNATIVE_SYNTAX) {
            $fullcode = $this->tokens[$current][1].'('.$init->fullcode.' ; '.$final->fullcode.' ; '.$increment->fullcode.') : '.self::FULLCODE_SEQUENCE.' '.$this->tokens[$this->id + 1][1];
        } else {
            $fullcode = $this->tokens[$current][1].'('.$init->fullcode.' ; '.$final->fullcode.' ; '.$increment->fullcode.')'.($block->bracket === self::BRACKET ? self::FULLCODE_BLOCK : self::FULLCODE_SEQUENCE);
        }

        $for->code        = $code;
        $for->fullcode    = $fullcode;
        $for->line        = $this->tokens[$current][2];
        $for->token       = $this->getToken($this->tokens[$this->id][0]);
        $for->alternative = $isColon;

        $this->runPlugins($for, array('INIT'      => $init,
                                      'FINAL'     => $final,
                                      'INCREMENT' => $increment,
                                      'BLOCK'     => $block));

        $this->pushExpression($for);
        $this->finishWithAlternative($isColon);
        
        return $for;
    }

    private function processForeach() {
        $foreach = $this->addAtom('Foreach');
        $current = $this->id;
        ++$this->id; // Skip foreach

        while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_AS))) {
            $this->processNext();
        }

        $source = $this->popExpression();
        $this->addLink($foreach, $source, 'SOURCE');

        $as = $this->tokens[$this->id + 1][1];
        ++$this->id; // Skip as

        while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_PARENTHESIS, $this->phptokens::T_DOUBLE_ARROW))) {
            $this->processNext();
        }

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_DOUBLE_ARROW) {
            $this->processNext();
        }

        $value = $this->popExpression();
        $this->addLink($foreach, $value, 'VALUE');

        ++$this->id; // Skip )
        $isColon = $this->whichSyntax($current, $this->id + 1);

        $block = $this->processFollowingBlock($isColon === true ? array($this->phptokens::T_ENDFOREACH) : array());

        $this->popExpression();
        $this->addLink($foreach, $block, 'BLOCK');

        if ($isColon === self::ALTERNATIVE_SYNTAX) {
            $fullcode = $this->tokens[$current][1].'('.$source->fullcode.' '.$as.' '.$value->fullcode.') : '.self::FULLCODE_SEQUENCE.' endforeach';
        } else {
            $fullcode = $this->tokens[$current][1].'('.$source->fullcode.' '.$as.' '.$value->fullcode.')'.($block->bracket === self::BRACKET ? self::FULLCODE_BLOCK : self::FULLCODE_SEQUENCE);
        }

        $foreach->code        = $this->tokens[$current][1];
        $foreach->fullcode    = $fullcode;
        $foreach->line        = $this->tokens[$current][2];
        $foreach->token       = $this->getToken($this->tokens[$current][0]);
        $foreach->alternative = $isColon;

        $this->runPlugins($foreach, array('SOURCE'    => $source,
                                          'VALUE'     => $value,
                                          'BLOCK'     => $block));

        $this->pushExpression($foreach);
        $this->finishWithAlternative($isColon);

        return $foreach;
    }

    private function processFollowingBlock(array $finals = array()) {
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_CURLY) {
            ++$this->id;
            $block = $this->processBlock(self::RELATED_BLOCK);
            $block->bracket = self::BRACKET;

        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_COLON) {
            $this->startSequence();
            $block = $this->sequence;
            ++$this->id; // skip :

            while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
                $this->processNext();
            }

            $this->endSequence();
            $this->pushExpression($this->sequence);
            
        } elseif (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_SEMICOLON))) {
            // void; One epxression block, with ;
            $this->startSequence();
            $block = $this->sequence;

            $void = $this->addAtomVoid();
            $this->addToSequence($void);
            $this->endSequence();
            $this->pushExpression($block);
            ++$this->id;

        } elseif (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_TAG,
                                                                  $this->phptokens::T_CLOSE_CURLY,
                                                                  $this->phptokens::T_CLOSE_PARENTHESIS,
                                                                  ))) {
            // Completely void (not even ;)
            $this->startSequence();
            $block = $this->sequence;

            $void = $this->addAtomVoid();
            $this->addToSequence($void);
            $this->endSequence();

            $this->pushExpression($block);

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
//, $this->phptokens::T_EXIT
            if (in_array($this->tokens[$this->id + 1][0], $specials)) {
                $this->processNext();
            } else {
                while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
                    $this->processNext();
                }
                $expression = $this->popExpression();
                $this->addToSequence($expression);
                $this->runPlugins($block, array($expression));
            }

            $this->endSequence();

            if (!in_array($this->tokens[$current + 1][0], $specials)) {
                ++$this->id;
            }

            $this->pushExpression($block);
        }
        
        return $block;
    }

    private function processDo() {
        $dowhile = $this->addAtom('Dowhile');
        $current = $this->id;

        $block = $this->processFollowingBlock(array($this->phptokens::T_WHILE));
        $this->popExpression();
        $this->addLink($dowhile, $block, 'BLOCK');

        $while = $this->tokens[$this->id + 1][1];
        ++$this->id; // Skip while
        ++$this->id; // Skip (

        while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_PARENTHESIS))) {
            $this->processNext();
        }
        ++$this->id; // skip )
        $condition = $this->popExpression();
        $this->addLink($dowhile, $condition, 'CONDITION');

        $dowhile->code     = $this->tokens[$current][1];
        $dowhile->fullcode = $this->tokens[$current][1].( $block->bracket === self::BRACKET ? self::FULLCODE_BLOCK : self::FULLCODE_SEQUENCE).$while.'('.$condition->fullcode.')';
        $dowhile->line     = $this->tokens[$current][2];
        $dowhile->token    = $this->getToken($this->tokens[$current][0]);

        $this->runPlugins($dowhile, array('CONDITION' => $condition,
                                          'BLOCK'     => $block));
        $this->pushExpression($dowhile);

        $this->checkExpression();

        return $dowhile;
    }

    private function processWhile() {
        $while = $this->addAtom('While');
        $current = $this->id;

        ++$this->id; // Skip while

        while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_PARENTHESIS))) {
            $this->processNext();
        }
        $condition = $this->popExpression();
        $this->addLink($while, $condition, 'CONDITION');

        ++$this->id; // Skip )
        $isColon = $this->whichSyntax($current, $this->id + 1);
        $block = $this->processFollowingBlock($isColon === self::ALTERNATIVE_SYNTAX ? array($this->phptokens::T_ENDWHILE) : array());
        $this->popExpression();

        $this->addLink($while, $block, 'BLOCK');

        if ($isColon === self::ALTERNATIVE_SYNTAX) {
            $fullcode = $this->tokens[$current][1].' ('.$condition->fullcode.') : '.self::FULLCODE_SEQUENCE.' '.$this->tokens[$this->id + 1][1];
        } else {
            $fullcode = $this->tokens[$current][1].' ('.$condition->fullcode.')'.($block->bracket === self::BRACKET ? self::FULLCODE_BLOCK : self::FULLCODE_SEQUENCE);
        }

        $while->code        = $this->tokens[$current][1];
        $while->fullcode    = $fullcode;
        $while->line        = $this->tokens[$current][2];
        $while->token       = $this->getToken($this->tokens[$current][0]);
        $while->alternative = $isColon;

        $this->runPlugins($while, array('CONDITION' => $condition,
                                        'BLOCK'     => $block));

        $this->pushExpression($while);
        $this->finishWithAlternative($isColon);
        
        return $while;
    }

    private function processDeclare() {
        $current = $this->id;
        $declare = $this->addAtom('Declare');
        $fullcode = array();

        ++$this->id; // Skip declare
        do {
            ++$this->id; // Skip ( or ,
            $name = $this->processSingle('Name');

            ++$this->id; // Skip =
            $this->processNext();
            $config = $this->popExpression();
            
            $declaredefinition = $this->addAtom('Declaredefinition');
            $this->addLink($declaredefinition, $name, 'NAME');
            $this->addLink($declaredefinition, $config, 'VALUE');

            $this->addLink($declare, $declaredefinition, 'DECLARE');
            $declaredefinition->fullcode = $name->fullcode . ' = ' . $config->fullcode;
            $declaredefinition->line     = $name->line;
            $fullcode[] = $declaredefinition->fullcode;
            
            ++$this->id; // Skip value
        }  while ($this->tokens[$this->id][0] === $this->phptokens::T_COMMA);

        $isColon = $this->whichSyntax($current, $this->id + 1);

        $block = $this->processFollowingBlock($isColon === self::ALTERNATIVE_SYNTAX ? array($this->phptokens::T_ENDDECLARE) : array());

        $this->popExpression();
        $this->addLink($declare, $block, 'BLOCK');

        if ($isColon === self::ALTERNATIVE_SYNTAX) {
            $fullcode = $this->tokens[$current][1].' ('.implode(', ', $fullcode).') : '.self::FULLCODE_SEQUENCE.' '.$this->tokens[$this->id + 1][1];
        } else {
            $fullcode = $this->tokens[$current][1].' ('.implode(', ', $fullcode).') '.self::FULLCODE_BLOCK;
        }

        $declare->code        = $this->tokens[$current][1];
        $declare->fullcode    = $fullcode;
        $declare->line        = $this->tokens[$current][2];
        $declare->token       = $this->getToken($this->tokens[$current][0]);
        $declare->alternative = $isColon ;

        $this->pushExpression($declare);
        $this->finishWithAlternative($isColon);

        return $declare;
    }

    private function processDefault() {
        $default = $this->addAtom('Default');
        $current = $this->id;

        if  (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_COLON,
                                                             $this->phptokens::T_SEMICOLON,
                                                             ))) {
            ++$this->id; // Skip :
        }

        $this->startSequence();
        while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_CURLY,
                                                                $this->phptokens::T_CASE,
                                                                $this->phptokens::T_DEFAULT,
                                                                $this->phptokens::T_ENDSWITCH))) {
            $this->processNext();
        }
        $code = $this->sequence;
        $this->addLink($default, $code, 'CODE');
        $this->endSequence();

        $default->code     = $this->tokens[$current][1];
        $default->fullcode = $this->tokens[$current][1].' : '.self::FULLCODE_SEQUENCE;
        $default->line     = $this->tokens[$current][2];
        $default->token    = $this->getToken($this->tokens[$current][0]);
        $this->runPlugins($default, array( 'CODE' => $code));

        $this->pushExpression($default);

        return $default;
    }

    private function processCase() {
        $case = $this->addAtom('Case');
        $current = $this->id;

        $this->contexts->nestContext(Context::CONTEXT_NOSEQUENCE);
        while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_COLON,
                                                                $this->phptokens::T_SEMICOLON,
                                                                $this->phptokens::T_CLOSE_TAG,
                                                                ))) {
            $this->processNext();
        }
        $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);

        $item = $this->popExpression();
        $this->addLink($case, $item, 'CASE');

        if  (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_COLON,
                                                             $this->phptokens::T_SEMICOLON,
                                                             ))) {
            ++$this->id; // Skip :
        }

        $this->startSequence();
        while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_CURLY,
                                                                $this->phptokens::T_CASE,
                                                                $this->phptokens::T_DEFAULT,
                                                                $this->phptokens::T_ENDSWITCH))) {
            $this->processNext();
        }
        $code = $this->sequence;
        $this->addLink($case, $code, 'CODE');
        $this->endSequence();

        $case->code     = $this->tokens[$current][1].' '.$item->fullcode.' : '.self::FULLCODE_SEQUENCE.' ';
        $case->fullcode = $this->tokens[$current][1].' '.$item->fullcode.' : '.self::FULLCODE_SEQUENCE.' ';
        $case->line     = $this->tokens[$current][2];
        $case->token    = $this->getToken($this->tokens[$current][0]);
        
        $this->runPlugins($case, array( 'CASE' => $item,
                                        'CODE' => $code));
        $this->pushExpression($case);

        return $case;
    }

    private function processSwitch() {
        $switch = $this->addAtom('Switch');
        $current = $this->id;
        ++$this->id; // Skip (

        while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_PARENTHESIS))) {
            $this->processNext();
        }
        $name = $this->popExpression();
        $this->addLink($switch, $name, 'CONDITION');

        $cases = $this->addAtom('Sequence');
        $cases->code     = self::FULLCODE_SEQUENCE;
        $cases->fullcode = self::FULLCODE_SEQUENCE;
        $cases->line     = $this->tokens[$current][2];
        $cases->token    = $this->getToken($this->tokens[$current][0]);
        $cases->bracket  = self::BRACKET;

        $this->addLink($switch, $cases, 'CASES');
        $extraCases = array();
        ++$this->id;

        $isColon = $this->whichSyntax($current, $this->id + 1);

        $rank = 0;
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
            while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
                $this->processNext();

                $case = $this->popExpression();
                $this->addLink($cases, $case, 'EXPRESSION');
                $case->rank = ++$rank;
                $extraCases[] = $case;
            }
        }
        ++$this->id;
        $cases->count = $rank;

        if ($isColon === self::ALTERNATIVE_SYNTAX) {
            $fullcode = $this->tokens[$current][1].' ('.$name->fullcode.') :'.self::FULLCODE_SEQUENCE.' '.$this->tokens[$this->id][1];
        } else {
            $fullcode = $this->tokens[$current][1].' ('.$name->fullcode.')'.self::FULLCODE_BLOCK;
        }

        $switch->code        = $this->tokens[$current][1];
        $switch->fullcode    = $fullcode;
        $switch->line        = $this->tokens[$current][2];
        $switch->token       = $this->getToken($this->tokens[$current][0]);
        $switch->alternative = $isColon;

        $this->runPlugins($cases, $extraCases);
        
        $this->runPlugins($switch, array('CONDITION' => $name,
                                         'CASES'     => $cases,));

        $this->pushExpression($switch);
        $this->finishWithAlternative($isColon);

        return $switch;
    }

    private function processIfthen() {
        $ifthen = $this->addAtom('Ifthen');
        $current = $this->id;
        ++$this->id; // Skip (

        while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_PARENTHESIS))) {
            $this->processNext();
        }
        $condition = $this->popExpression();
        $this->addLink($ifthen, $condition, 'CONDITION');
        $extras = array('CONDITION' => $condition);

        ++$this->id; // Skip )
        $isInitialIf = $this->tokens[$current][0] === $this->phptokens::T_IF;
        $isColon = $this->whichSyntax($current, $this->id + 1);

        $then = $this->processFollowingBlock(array($this->phptokens::T_ENDIF, $this->phptokens::T_ELSE, $this->phptokens::T_ELSEIF));
        $this->popExpression();
        $this->addLink($ifthen, $then, 'THEN');
        $extras['THEN'] = $then;

        // Managing else case
        if (in_array($this->tokens[$this->id][0], array($this->phptokens::T_END, $this->phptokens::T_CLOSE_TAG))) {
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
            $this->popExpression();
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
            $fullcode = $this->tokens[$current][1].'('.$condition->fullcode.') : '.$then->fullcode.$elseFullcode.($isInitialIf === true ? ' endif' : '');
        } else {
            $fullcode = $this->tokens[$current][1].'('.$condition->fullcode.')'.$then->fullcode.$elseFullcode;
        }

        $ifthen->code        = $this->tokens[$current][1];
        $ifthen->fullcode    = $fullcode;
        $ifthen->line        = $this->tokens[$current][2];
        $ifthen->token       = $this->getToken($this->tokens[$current][0]);
        $ifthen->alternative = $isColon;
        
        $this->runPlugins($ifthen, $extras);

        if ($this->tokens[$current][0] === $this->phptokens::T_IF) {
            if ($this->tokens[$this->id][0] === $this->phptokens::T_ENDIF) {
                --$this->id; // otherwise, ifthen : endif doesn't end on endif.
            }
            $this->pushExpression($ifthen);
            $this->finishWithAlternative($isColon);
        }

        return $ifthen;
    }

    private function processParenthesis() {
        $parenthese = $this->addAtom('Parenthesis');

        while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_PARENTHESIS))) {
            $this->processNext();
        }

        $code = $this->popExpression();
        $this->addLink($parenthese, $code, 'CODE');

        $parenthese->code        = '(';
        $parenthese->fullcode    = '('.$code->fullcode.')';
        $parenthese->line        = $this->tokens[$this->id][2];
        $parenthese->token       = 'T_OPEN_PARENTHESIS';
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

    private function processExit() {
        if (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_PARENTHESIS,
                                                            $this->phptokens::T_SEMICOLON,
                                                            $this->phptokens::T_CLOSE_TAG,
                                                            $this->phptokens::T_CLOSE_CURLY,
                                                            $this->phptokens::T_CLOSE_BRACKET,
                                                            $this->phptokens::T_COMMA,
                                                            $this->phptokens::T_COLON))) {
            $functioncall = $this->addAtom('Exit');

            $functioncall->code       = $this->tokens[$this->id][1];
            $functioncall->fullcode   = $this->tokens[$this->id][1].' ';
            $functioncall->line       = $this->tokens[$this->id][2];
            $functioncall->token      = $this->getToken($this->tokens[$this->id][0]);
            $functioncall->count      = 0;
            $functioncall->fullnspath = '\\'.mb_strtolower($functioncall->code);

            $void = $this->addAtomVoid();
            $void->rank = 0;

            $this->addLink($functioncall, $void, 'ARGUMENT');

            $this->pushExpression($functioncall);

            if ( !$this->contexts->isContext(Context::CONTEXT_NOSEQUENCE) && in_array($this->tokens[$this->id + 1][0],
                                                                         array($this->phptokens::T_CLOSE_TAG,
                                                                               $this->phptokens::T_COMMA,
                                                                              ))
                ) {
                $this->processSemicolon();
            }
            $this->checkExpression();

            return $functioncall;
        } else {
            $current = $this->id;

            if (mb_strtolower($this->tokens[$this->id][1]) === 'die' &&
                $this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_PARENTHESIS) {
                // Skip the ( for die only
                ++$this->id;
            }
        
            $functioncall = $this->processArguments('Exit',
                                                    array($this->phptokens::T_SEMICOLON,
                                                          $this->phptokens::T_CLOSE_TAG,
                                                          $this->phptokens::T_CLOSE_PARENTHESIS,
                                                          $this->phptokens::T_CLOSE_BRACKET,
                                                          $this->phptokens::T_CLOSE_CURLY,
                                                          $this->phptokens::T_COLON,
                                                          $this->phptokens::T_END,
                                                          ),
                                                    $argumentsList);
            $argumentsFullcode = $functioncall->fullcode;
            if (mb_strtolower($this->tokens[$current][1]) === 'die') {
                $argumentsFullcode = "($argumentsFullcode)";
            } else {
                --$this->id;
            }

            $functioncall->code       = $this->tokens[$current][1];
            $functioncall->fullcode   = $this->tokens[$current][1].$argumentsFullcode;
            $functioncall->fullnspath = '\\'.mb_strtolower($this->tokens[$current][1]);
            $this->pushExpression($functioncall);
            $this->runPlugins($functioncall);

            $this->checkExpression();

            return $functioncall;
        }
    }

    private function processArrayLiteral() {
        $current = $this->id;

        if ($this->tokens[$current][0] === $this->phptokens::T_ARRAY) {
            ++$this->id; // Skipping the name, set on (
            $array = $this->processArguments('Arrayliteral', array(), $argumentsList);
            $argumentsFullcode = $array->fullcode;
            $array->token    = 'T_ARRAY';
            $array->fullcode = $this->tokens[$current][1].'('.$argumentsFullcode.')';
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
        $array->line      = $this->tokens[$current][2];
        $this->runPlugins($array, $argumentsList);

        $this->pushExpression($array);
        
        if ( !$this->contexts->isContext(Context::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG) {
            $this->processSemicolon();
        } else {
            $array = $this->processFCOA($array);
        }

        return $array;
    }

    private function processArray() {
        return $this->processString();
    }

    private function processTernary() {
        $current = $this->id;

        $condition = $this->popExpression();
        $ternary = $this->addAtom('Ternary');

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_STRING &&
            $this->tokens[$this->id + 2][0] === $this->phptokens::T_COLON) {
            if (in_array(mb_strtolower($this->tokens[$this->id + 1][1]), array('true', 'false'))) {
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
            while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_COLON)) ) {
                $this->processNext();
            }
            $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);
            $then = $this->popExpression();
        }

        ++$this->id; // Skip colon

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_STRING &&
            $this->tokens[$this->id + 2][0] === $this->phptokens::T_COLON) {
            if (in_array(mb_strtolower($this->tokens[$this->id + 1][1]), array('true', 'false'))) {
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
                $this->processNext();
            } while (!in_array($this->tokens[$this->id + 1][0], $finals) );
            $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);
            
            $else = $this->popExpression();
        }
        
        $this->addLink($ternary, $condition, 'CONDITION');
        $this->addLink($ternary, $then, 'THEN');
        $this->addLink($ternary, $else, 'ELSE');

        $ternary->code     = '?';
        $ternary->fullcode = $condition->fullcode.' ?'.($then->atom === 'Void' ? '' : ' '.$then->fullcode.' ' ).': '.$else->fullcode;
        $ternary->line     = $this->tokens[$current][2];
        $ternary->token    = 'T_QUESTION';
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
    private function processSingle($atomName) {
        $atom = $this->addAtom($atomName);
        if (strlen($this->tokens[$this->id][1]) > 100000) {
            $this->tokens[$this->id][1] = substr($this->tokens[$this->id][1], 0, 100000).PHP_EOL."[.... 100000 / ".strlen($this->tokens[$this->id][1])."]".PHP_EOL;
        }
        $atom->code     = $this->tokens[$this->id][1];
        $atom->fullcode = $this->tokens[$this->id][1];
        $atom->line     = $this->tokens[$this->id][2];
        $atom->token    = $this->getToken($this->tokens[$this->id][0]);

        if (!in_array($atomName, array('Parametername', 'Parameter', 'Propertydefinition', 'Globaldefinition', 'Staticdefinition')) &&
            $this->tokens[$this->id][0] === $this->phptokens::T_VARIABLE) {
            if (isset($this->currentVariables[$atom->code])) {
                $this->addLink($this->currentVariables[$atom->code], $atom, 'DEFINITION');
            } else {
                $definition = $this->addAtom('Variabledefinition');
                $definition->fullcode = $atom->fullcode;
                $definition->line     = $atom->line;
                $this->addLink($this->currentMethod[count($this->currentMethod) - 1], $definition, 'DEFINITION');
                $this->currentVariables[$atom->code] = $definition;
                
                $this->addLink($definition, $atom, 'DEFINITION');
            }
        }

        return $atom;
    }

    private function processInlinehtml() {
        $inlineHtml = $this->processSingle('Inlinehtml');
        $this->pushExpression($inlineHtml);
        $this->processSemicolon();
    }

    private function processNamespaceBlock() {
        $this->startSequence();

        while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_TAG,
                                                                $this->phptokens::T_NAMESPACE,
                                                                $this->phptokens::T_END,
                                                                ))) {
            $this->processNext();

            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_NAMESPACE &&
                $this->tokens[$this->id + 2][0] === $this->phptokens::T_NS_SEPARATOR) {
                $this->processNext();
            }
        }
        $block = $this->sequence;
        $this->endSequence();

        $block->code     = ' ';
        $block->fullcode = ' '.self::FULLCODE_SEQUENCE.' ';
        $block->line     = $this->tokens[$this->id][2];
        $block->token    = $this->getToken($this->tokens[$this->id][0]);

        return $block;
    }

    private function processNamespace() {
        $current = $this->id;

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_NS_SEPARATOR) {
            $nsname = $this->processOneNsname();

            list($fullnspath, $aliased) = $this->getFullnspath($nsname);
            $nsname->fullnspath = $fullnspath;
            $nsname->aliased    = $aliased;
            $this->pushExpression($nsname);

            return $this->processFCOA($nsname);
        }
        
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_CURLY) {
            $name = $this->addAtomVoid();
        } else {
            $name = $this->processOneNsname();
        }

        $namespace = $this->addAtom('Namespace');
        $this->addLink($namespace, $name, 'NAME');
        $this->setNamespace($name);

        // Here, we make sure namespace is encompassing the next elements.
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_SEMICOLON) {
            // Process block
            
            ++$this->id; // Skip ; to start actual sequence
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_END) {
                $void = $this->addAtomVoid();
                $block = $this->addAtom('Sequence');
                $block->code       = '{}';
                $block->fullcode   = self::FULLCODE_BLOCK;
                $block->line       = $this->tokens[$this->id][2];
                $block->token      = $this->getToken($this->tokens[$this->id][0]);
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
            $this->processFollowingBlock(array($this->phptokens::T_CLOSE_CURLY));
            $block = $this->popExpression();
            $this->addLink($namespace, $block, 'BLOCK');

            $this->addToSequence($namespace);

            $block = self::FULLCODE_BLOCK;
        }
        $this->setNamespace(0);

        $namespace->code       = $this->tokens[$current][1];
        $namespace->fullcode   = $this->tokens[$current][1].' '.$name->fullcode.$block;
        $namespace->line       = $this->tokens[$current][2];
        $namespace->token      = $this->getToken($this->tokens[$current][0]);
        $namespace->fullnspath = $name->atom === 'Void' ? '\\' : $name->fullnspath;

        return $namespace;
    }

    private function processAs() {
        $current = $this->id;
        $as = $this->addAtom('As');

        // special case for use t, t2 { as as yes; }
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_AS) {
            $left = $this->processNextAsIdentifier();
        } else {
            $left = $this->popExpression();
        }
        list($fullnspath, $aliased) = $this->getFullnspath($left, 'staticmethod');
    
        $left->fullnspath = $fullnspath;
        $left->aliased    = $aliased;
        $this->calls->addCall('staticmethod', $left->fullnspath, $left);

        $this->addLink($as, $left, 'NAME');
        $fullcode = array($left->fullcode, $this->tokens[$current][1]);

        if (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_PRIVATE,
                                                            $this->phptokens::T_PUBLIC,
                                                            $this->phptokens::T_PROTECTED,
                                                            ))) {
            $fullcode[] = $this->tokens[$this->id + 1][1];
            $as->visibility = strtolower($this->tokens[$this->id + 1][1]);
            ++$this->id;
        }

        if ($this->tokens[$this->id + 1][0] !== $this->phptokens::T_SEMICOLON) {
            $alias = $this->processNextAsIdentifier();
            $this->addLink($as, $alias, 'AS');
            $fullcode[] = $alias->fullcode;
        }

        $as->code     = $this->tokens[$current][1];
        $as->fullcode = join(' ', $fullcode);
        $as->line     = $this->tokens[$current][2];
        $as->token    = $this->getToken($this->tokens[$current][0]);

        $this->pushExpression($as);

        return $as;
    }

    private function processInsteadof() {
        $insteadof = $this->processOperator('Insteadof', $this->precedence->get($this->tokens[$this->id][0]), array('NAME', 'INSTEADOF'));
        while ($this->tokens[$this->id + 1][0] === $this->phptokens::T_COMMA) {
            ++$this->id;
            $nsname = $this->processOneNsname();

            $this->addLink($insteadof, $nsname, 'INSTEADOF');
        }
        return $insteadof;
    }

    private function processUse() {
        if (empty($this->currentClassTrait)) {
            return $this->processUseNamespace();
        } else {
            return $this->processUseTrait();
        }
    }

    private function processUseNamespace() {
        $use = $this->addAtom('Usenamespace');
        $current = $this->id;
        $useType = 'class';

        $fullcode = array();

        // use const
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CONST) {
            ++$this->id;

            $const = $this->processSingle('Identifier');
            $this->addLink($use, $const, 'CONST');
            $useType = 'const';
        }

        // use function
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_FUNCTION) {
            ++$this->id;

            $const = $this->processSingle('Identifier');
            $this->addLink($use, $const, 'FUNCTION');
            $useType = 'function';
        }

        --$this->id;
        do {
            $prefix = '';
            ++$this->id;
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
                list($prefix, ) = explode('\\', $fullnspath);
                $fullnspath = "\\$fullnspath";
            }

            $this->calls->addCall('class', $fullnspath, $namespace);
            
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_AS) {
                // use A\B as C
                ++$this->id;
                $this->pushExpression($namespace);
                $this->processAs();
                $as = $this->popExpression();
                $as->fullnspath = makeFullNsPath($namespace->fullcode, $useType === 'const');
                $fullcode[] = $as->fullcode;
                $as->alias = mb_strtolower(substr($as->fullcode, strrpos($as->fullcode, ' as ') + 4));

                if (isset($this->uses['class'][$prefix])) {
                    $this->addLink($as, $this->uses['class'][$prefix], 'DEFINITION');
                }
                $this->addLink($use, $as, 'USE');

                if (!$this->contexts->isContext(Context::CONTEXT_CLASS) &&
                    !$this->contexts->isContext(Context::CONTEXT_TRAIT) ) {
                    $alias = $this->addNamespaceUse($origin, $as, $useType, $as);
                }

                $namespace = $as;
            } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_NS_SEPARATOR) {
                //use A\B\ {} // Prefixes, within a Class/Trait
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
                    ++$this->id; // Skip {

                    $useType = $useTypeGeneric;
                    $useTypeAtom = 0;
                    if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CONST) {
                        // use const
                        ++$this->id;

                        $useTypeAtom = $this->processSingle('Identifier');
                        $useType = 'const';
                    }

                    if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_FUNCTION) {
                        // use function
                        ++$this->id;

                        $useTypeAtom = $this->processSingle('Identifier');
                        $useType = 'function';
                    }

                    if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_CURLY) {
                        $use->trailing = self::TRAILING;
                    } else {
                        $nsname = $this->processOneNsname();

                        if ($useTypeAtom !== 0) {
                            $this->addLink($nsname, $useTypeAtom, strtoupper($useType));
                        }
    
                        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_AS) {
                            // A\B as C
                            ++$this->id;
                            $this->pushExpression($nsname);
                            $this->processAs();
                            $alias = $this->popExpression();
    
                            $nsname->fullnspath = $prefix.mb_strtolower($nsname->fullcode);
                            $nsname->origin     = $prefix.mb_strtolower($nsname->fullcode);
    
                            $alias->fullnspath  = $prefix.mb_strtolower($nsname->fullcode);
                            $alias->origin      = $prefix.mb_strtolower($nsname->fullcode);
    
                            $aliasName = $this->addNamespaceUse($nsname, $alias, $useType, $alias);
                            $alias->alias = $aliasName;
                            $this->addLink($use, $alias, 'USE');
                        } else {
                            $this->addLink($use, $nsname, 'USE');
                            $nsname->fullnspath = $prefix.mb_strtolower($nsname->fullcode);
                            $nsname->origin     = $prefix.mb_strtolower($nsname->fullcode);
    
                            $alias = $this->addNamespaceUse($nsname, $nsname, $useType, $nsname);
                            $nsname->alias = $alias;
                        }
                    }
                } while (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_COMMA)));

                $fullcode[] = $namespace->fullcode.self::FULLCODE_BLOCK;

                ++$this->id; // Skip }
            } else {
                $this->addLink($use, $namespace, 'USE');

                if (!$this->contexts->isContext(Context::CONTEXT_CLASS) &&
                    !$this->contexts->isContext(Context::CONTEXT_TRAIT) ) {

                    $fullnspath = makeFullNsPath($namespace->fullcode);
                    $namespace->fullnspath = $fullnspath;
                    $namespace->origin     = $fullnspath;

                    if (isset($this->uses['class'][$prefix])) {
                        $this->addLink($namespace, $this->uses['class'][$prefix], 'DEFINITION');
                    }

                    $namespace->fullnspath = $fullnspath;

                    $alias = $this->addNamespaceUse($alias, $alias, $useType, $namespace);

                    $namespace->alias = $alias;
                    $origin->alias = $alias;
 
                } elseif (isset($this->uses['class'][$prefix])) {
                    $this->addLink($namespace, $this->uses['class'][$prefix], 'DEFINITION');
                    $namespace->fullnspath = $this->uses['class'][$prefix]->fullnspath;
    
                    $this->calls->addCall('class', $namespace->fullnspath, $namespace);
                } else {
                    list($fullnspath, $aliased) = $this->getFullnspath($namespace, 'class');
    
                    $namespace->fullnspath = $fullnspath;
                    $namespace->aliased    = $aliased;
                    $this->calls->addCall('class', $namespace->fullnspath, $namespace);
                }

                $fullcode[] = $namespace->fullcode;
            }
            // No Else. Default will be dealt with by while() condition

        } while ($this->tokens[$this->id + 1][0] === $this->phptokens::T_COMMA);

        $use->code     = $this->tokens[$current][1];
        $use->fullcode = $this->tokens[$current][1].(isset($const) ? ' '.$const->code : '').' '.implode(", ", $fullcode);
        $use->line     = $this->tokens[$current][2];
        $use->token    = $this->getToken($this->tokens[$current][0]);

        $this->pushExpression($use);

        $this->checkExpression();

        return $use;
    }

    private function processUseTrait() {
        $use = $this->addAtom('Usetrait');
        $current = $this->id;
        $useType = 'class';

        $fullcode = array();

        --$this->id;
        do {
            $prefix = '';
            ++$this->id;
            $namespace = $this->processOneNsname(self::WITHOUT_FULLNSPATH);

            $fullcode[] = $namespace->fullcode;

            list($fullnspath, $aliased) = $this->getFullnspath($namespace, 'class');
    
            $namespace->fullnspath = $fullnspath;
            $namespace->aliased    = $aliased;

            $this->calls->addCall('class', $namespace->fullnspath, $namespace);

            $this->addLink($use, $namespace, 'USE');
        } while ($this->tokens[$this->id + 1][0] === $this->phptokens::T_COMMA);
        
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_CURLY) {
            //use A\B{} // Group
            $block = $this->processFollowingBlock(array($this->phptokens::T_CLOSE_CURLY));

            $this->popExpression();
            $this->addLink($use, $block, 'BLOCK');
            $fullcode[] = $namespace->fullcode.' '.$block->fullcode;

            // Several namespaces ? This has to be recalculated inside the block!!
            $namespace->fullnspath = makeFullNsPath($namespace->fullcode);
         }

        $use->code     = $this->tokens[$current][1];
        $use->fullcode = $this->tokens[$current][1].(isset($const) ? ' '.$const->code : '').' '.implode(", ", $fullcode);
        $use->line     = $this->tokens[$current][2];
        $use->token    = $this->getToken($this->tokens[$current][0]);
        $this->pushExpression($use);

        return $use;
    }

    private function processVariable() {
        if ($this->tokens[$this->id][1] === '$this') {
            $atom = 'This';
        } elseif (in_array($this->tokens[$this->id][1], array('$GLOBALS',
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
                                                              ))) {
            $atom = 'Phpvariable';
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OBJECT_OPERATOR) {
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

        if (in_array($atom, array('Variable', 'Variableobject', 'Variablearray')) ) {
            if ($this->currentReturn !== null) {
                $this->addLink($this->currentReturn, $variable, 'RETURNED');
            }
        }

        if ( !$this->contexts->isContext(Context::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG) {
            $this->processSemicolon();
        } else {
             $variable = $this->processFCOA($variable);
        }

        return $variable;
    }

    private function processFCOA($nsname) {
        // For functions and constants
        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_PARENTHESIS) {
            return $this->processFunctioncall();
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_BRACKET &&
                  $this->tokens[$this->id + 2][0] === $this->phptokens::T_CLOSE_BRACKET) {
            return $this->processAppend();
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_BRACKET ||
                  $this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_CURLY) {
            return $this->processBracket();
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_DOUBLE_COLON ||
                  $this->tokens[$this->id + 1][0] === $this->phptokens::T_NS_SEPARATOR ||
                  $this->tokens[$this->id - 1][0] === $this->phptokens::T_INSTANCEOF   ||
                  $this->tokens[$this->id - 1][0] === $this->phptokens::T_AS) {
            return $nsname;
        } elseif (in_array($nsname->atom, array('Nsname', 'Identifier'))) {

            $type = $this->contexts->isContext(Context::CONTEXT_NEW) ? 'class' : 'const';
            
            list($fullnspath, $aliased) = $this->getFullnspath($nsname, $type);
            $nsname->fullnspath = $fullnspath;
            $nsname->aliased    = $aliased;

            if ($type === 'const') {
                $this->calls->addCall('const', $fullnspath, $nsname);
            }

            return $nsname;
        } else {
            return $nsname;
        }
    }

    private function processAppend() {
        $current = $this->id;
        $append = $this->addAtom('Arrayappend');

        $left = $this->popExpression();
        $this->addLink($append, $left, 'APPEND');

        $append->code     = $this->tokens[$current][1];
        $append->fullcode = $left->fullcode.'[]';
        $append->line     = $this->tokens[$current][2];
        $append->token    = $this->getToken($this->tokens[$current][0]);

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

    private function processInteger() {
        $integer = $this->processSingle('Integer');
        $this->pushExpression($integer);
        $this->runPlugins($integer);

        $this->checkExpression();
        
        return $integer;
    }

    private function processReal() {
        $real = $this->processSingle('Real');
        $this->pushExpression($real);
        // (int) is for loading into the database
        $this->runPlugins($real);

        $this->checkExpression();

        return $real;
    }

    private function processLiteral() {
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

            if (in_array(mb_strtolower($literal->noDelimiter),  array('parent', 'self', 'static'))) {
                list($fullnspath, $aliased) = $this->getFullnspath($literal, 'class');
                $literal->fullnspath = $fullnspath;
                $literal->aliased    = $aliased;

                $this->calls->addCall('class', $fullnspath, $literal);
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
                $literal->block = array_keys($blocks)[0];
            }
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_BRACKET) {
                $literal = $this->processBracket();
            }
        }

        if ( !$this->contexts->isContext(Context::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG) {
            $this->processSemicolon();
        } elseif ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_PARENTHESIS) {
            $literal = $this->processFCOA($literal);
        }
        
        return $literal;
    }

    private function processMagicConstant() {
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
        $constant->boolean = (int) (bool) $constant->intval;
        $this->runPlugins($constant);
        
        return $constant;
    }

    //////////////////////////////////////////////////////
    /// processing single operators
    //////////////////////////////////////////////////////
    private function processSingleOperator($atom, array $finals = array(), $link, $separator = '') {
        $current = $this->id;

        $operator = $this->addAtom($atom);
        $this->contexts->nestContext(Context::CONTEXT_NOSEQUENCE);
        $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        // Do while, so that AT least one loop is done.
        do {
            $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], $finals));
        $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);

        $operand = $this->popExpression();
        $this->addLink($operator, $operand, $link);

        $operator->code      = $this->tokens[$current][1];
        $operator->fullcode  = $this->tokens[$current][1] .$separator.$operand->fullcode;
        $operator->line      = $this->tokens[$current][2];
        $operator->token     = $this->getToken($this->tokens[$current][0]);

        $this->runPlugins($operator, array($link => $operand));
        $this->pushExpression($operator);

        $this->checkExpression();

        return $operand;
    }

    private function processCast() {
        $this->processSingleOperator('Cast', $this->precedence->get($this->tokens[$this->id][0]), 'CAST', ' ');
        $operator = $this->popExpression();
        if (strtolower($operator->code) === '(binary)') {
            $operator->binaryString = $operator->code[1];
        }
        $this->pushExpression($operator);
        return $operator;
    }

    private function processReturn() {
        if (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_TAG,
                                                            $this->phptokens::T_SEMICOLON,
                                                            ))) {
            $current = $this->id;

            // Case of return ;
            $return = $this->addAtom('Return');

            $returnArg = $this->addAtomVoid();
            $this->addLink($return, $returnArg, 'RETURN');

            $return->code     = $this->tokens[$current][1];
            $return->fullcode = $this->tokens[$current][1].' ;';
            $return->line     = $this->tokens[$current][2];
            $return->token    = $this->getToken($this->tokens[$current][0]);
            
            $this->runPlugins($return, array('RETURN' => $returnArg) );

            $this->pushExpression($return);
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG) {
                $this->processSemicolon();
            }

            return $return;
        } else {
            if (count($this->currentMethod) > 1) {
                $this->currentReturn = $this->currentMethod[count($this->currentMethod) - 1];
            }

            $this->contexts->nestContext(Context::CONTEXT_NOSEQUENCE);
            $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);

            $return = $this->processSingleOperator('Return', $this->precedence->get($this->tokens[$this->id][0]), 'RETURN', ' ');

            $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);

            $operator = $this->popExpression();
            $this->pushExpression($operator);

            $this->currentReturn = null;

            $this->runPlugins($operator, array('RETURN' => $return) );

            $this->checkExpression();

            return $operator;
        }
    }

    private function processThrow() {
        $this->processSingleOperator('Throw', $this->precedence->get($this->tokens[$this->id][0]), 'THROW', ' ');
        $operator = $this->popExpression();
        $this->pushExpression($operator);

        $this->checkExpression();

        return $operator;
    }

    private function processYield() {
        if (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_PARENTHESIS,
                                                            $this->phptokens::T_CLOSE_BRACKET,
                                                            $this->phptokens::T_COMMA,
                                                            $this->phptokens::T_SEMICOLON,
                                                            $this->phptokens::T_CLOSE_TAG,
                                   ))) {
            $current = $this->id;

            // Case of return ;
            $yieldArg = $this->addAtomVoid();
            $yield = $this->addAtom('Yield');

            $this->addLink($yield, $yieldArg, 'YIELD');

            $yield->code     = $this->tokens[$current][1];
            $yield->fullcode = $this->tokens[$current][1].' ;';
            $yield->line     = $this->tokens[$current][2];
            $yield->token    = $this->getToken($this->tokens[$current][0]);

            $this->pushExpression($yield);
            $this->runPlugins($yield, array('YIELD' => $yieldArg) );

            return $yield;
        } else {
            // => is actually a lower priority
            $finals = $this->precedence->get($this->tokens[$this->id][0]);
            $id = array_search($this->phptokens::T_DOUBLE_ARROW, $finals);
            unset($finals[$id]);
            $operand = $this->processSingleOperator('Yield', $finals, 'YIELD', ' ');
            $yield = $this->popExpression();
            $this->pushExpression($yield);

            $this->runPlugins($yield, array('YIELD' => $operand) );
            
            return $yield;
        }
    }

    private function processYieldfrom() {
        $yieldfrom = $this->processSingleOperator('Yieldfrom', $this->precedence->get($this->tokens[$this->id][0]), 'YIELD', ' ');
        $operator = $this->popExpression();
        $this->pushExpression($operator);

        $this->runPlugins($operator, array('YIELD' => $yieldfrom) );

        $this->checkExpression();

        return $operator;
    }

    private function processNot() {
        $this->processSingleOperator('Not', $this->precedence->get($this->tokens[$this->id][0]), 'NOT');
        
        $not = $this->popExpression();
        $this->pushExpression($not);
        
        $this->runPlugins($not, array('NOT' => $not));

        $this->checkExpression();
        
        return $not;
    }

    private function processCurlyExpression() {
        ++$this->id;
        while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_CURLY))) {
            $this->processNext();
        }

        $code = $this->popExpression();
        $block = $this->addAtom('Block');
        $block->code     = '{}';
        $block->fullcode = '{'.$code->fullcode.'}';
        $block->line     = $this->tokens[$this->id][2];
        $block->token    = $this->getToken($this->tokens[$this->id][0]);

        $this->addLink($block, $code, 'CODE');

        ++$this->id; // Skip }

        return $block;
    }

    private function processDollar() {
        $this->contexts->nestContext(Context::CONTEXT_NOSEQUENCE);
        $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_CURLY) {
            $current = $this->id;

            $variable = $this->addAtom('Variable');

            ++$this->id;
            while (!in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_CLOSE_CURLY)) ) {
                $this->processNext();
            }

            // Skip }
            ++$this->id;

            $expression = $this->popExpression();
            $this->addLink($variable, $expression, 'NAME');

            $variable->code     = $this->tokens[$current][1];
            $variable->fullcode = $this->tokens[$current][1].'{'.$expression->fullcode.'}';
            $variable->line     = $this->tokens[$current][2];
            $variable->token    = $this->getToken($this->tokens[$current][0]);
            $this->pushExpression($variable);

            if ( !$this->contexts->isContext(Context::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG) {
                $this->processSemicolon();
            } elseif (!in_array($this->tokens[$current - 1][0], array($this->phptokens::T_OBJECT_OPERATOR,
                                                                       $this->phptokens::T_DOUBLE_COLON,
                                                                       ))) {
                $variable = $this->processFCOA($variable);
            }
        } else {
            $this->processSingleOperator('Variable', $this->precedence->get($this->tokens[$this->id][0]), 'NAME');
            $variable = $this->popExpression();

            $this->pushExpression($variable);
        }

        $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);
        $this->checkExpression();
        
        return $variable;
    }

    private function processClone() {
        $this->processSingleOperator('Clone', $this->precedence->get($this->tokens[$this->id][0]), 'CLONE', ' ' );
        $operatorId = $this->popExpression();
        $this->pushExpression($operatorId);
        return $operatorId;
    }

    private function processGoto() {
        $current = $this->id;

        $label = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);
        
        $goto = $this->addAtom('Goto');
        $goto->code      = $this->tokens[$current][1];
        $goto->fullcode  = $this->tokens[$current][1].' '.$label->fullcode;
        $goto->line      = $this->tokens[$current][2];
        $goto->token     = $this->getToken($this->tokens[$current][0]);

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
        $this->calls->addCall('goto', $class.'::'.$method.'..'.$this->tokens[$this->id][1], $goto);
        $this->pushExpression($goto);

        return $goto;
    }

    private function processNoscream() {
        $atom = $this->processNext();
        $atom->noscream = 1;
        $atom->fullcode = "@$atom->fullcode";
        
        return $atom;
    }

    private function processNew() {
        $this->contexts->toggleContext(Context::CONTEXT_NEW);
        $noSequence = $this->contexts->isContext(Context::CONTEXT_NOSEQUENCE);
        if ($noSequence === false) {
            $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        }

        $this->processSingleOperator('New', $this->precedence->get($this->tokens[$this->id][0]), 'NEW', ' ');

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
    private function processSign() {
        $signExpression = $this->tokens[$this->id][1];
        $code = $signExpression.'1';
        while (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_PLUS,
                                                               $this->phptokens::T_MINUS,
                                                              ))) {
            ++$this->id;
            $signExpression = $this->tokens[$this->id][1].$signExpression;
            $code *= $this->tokens[$this->id][1].'1';
        }
        
        if (($this->tokens[$this->id + 1][0] === $this->phptokens::T_LNUMBER ||
             $this->tokens[$this->id + 1][0] === $this->phptokens::T_DNUMBER) &&
             $this->tokens[$this->id + 2][0] !== $this->phptokens::T_POW) {
            $operand = $this->processNext();

            $operand->code     = $signExpression.$operand->code;
            $operand->fullcode = $signExpression.$operand->fullcode;
            $operand->line     = $this->tokens[$this->id][2];
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
        } while (!in_array($this->tokens[$this->id + 1][0], $finals));
        if ($noSequence === false) {
            $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        }
        $signed = $this->popExpression();

        for($i = strlen($signExpression) - 1; $i >= 0; --$i) {
            $sign = $this->addAtom('Sign');
            $this->addLink($sign, $signed, 'SIGN');

            $sign->code     = $signExpression[$i];
            $sign->fullcode = $signExpression[$i].$signed->fullcode;
            $sign->line     = $this->tokens[$this->id][2];
            $sign->token    = $this->getToken($this->tokens[$this->id][0]);

            $signed = $sign;
        }
        $this->runPlugins($sign, array('SIGN' => $signed));

        $this->pushExpression($signed);

        $this->checkExpression();
        return $signed;
    }

    private function processAddition() {
        if (!$this->hasExpression() ||
            $this->tokens[$this->id - 1][0] === $this->phptokens::T_DOT ||
            $this->tokens[$this->id - 1][0] === $this->phptokens::T_EXIT
            ) {
            return $this->processSign();
        }

        $finals = $this->precedence->get($this->tokens[$this->id][0]);
        $finals = array_diff($finals, $this->assignations);
        $finals = array_unique($finals);
        $finals = array_slice($finals, 1);
        return $this->processOperator('Addition', $finals, array('LEFT', 'RIGHT'));
    }

    private function processBreak() {
        $current = $this->id;
        $break = $this->addAtom($this->tokens[$this->id][0] === $this->phptokens::T_BREAK ? 'Break' : 'Continue');

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
        } else {
            $breakLevel = $this->addAtomVoid();
        }

        $link = $this->tokens[$current][0] === $this->phptokens::T_BREAK ? 'BREAK' : 'CONTINUE';
        $this->addLink($break, $breakLevel, $link);
        $break->code     = $this->tokens[$current][1];
        $break->fullcode = $this->tokens[$current][1].( $breakLevel->atom !== 'Void' ?  ' '.$breakLevel->fullcode : '');
        $break->line     = $this->tokens[$current][2];
        $break->token    = $this->getToken($this->tokens[$current][0]);

        $this->runPlugins($break, array($link => $breakLevel));
        $this->pushExpression($break);

        $this->checkExpression();

        return $break;
    }

    private function processDoubleColon() {
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
        } else {
            if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_VARIABLE) {
                ++$this->id;
                $right = $this->processSingle('Staticpropertyname');
            } else {
                $right = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);
            }
        }

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_OPEN_PARENTHESIS) {
            $this->pushExpression($right);
            $right = $this->processFunctioncall(self::WITHOUT_FULLNSPATH);
            $this->popExpression();
        }

        $this->contexts->exitContext(Context::CONTEXT_NEW);
        $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);

        if (is_string($right) && mb_strtolower($right) === 'class') {
            $static = $this->addAtom('Staticclass');
            $links = 'CLASS';
            $fullcode = "$left->fullcode::$right";
            // We are not sending $left, as it has no impact
            $this->runPlugins($left);
            $this->runPlugins($static, array('CLASS' => $left));
            // This should actually be the value of any USE statement
            if (isset($this->uses['class'][mb_strtolower($left->fullcode)])) {
                $noDelimiter = $this->uses['class'][mb_strtolower($left->fullcode)]->fullcode;
                if (($length = strpos($noDelimiter, ' ')) !== false) {
                    $noDelimiter = substr($noDelimiter, 0, $length);
                }
                $static->noDelimiter = $noDelimiter;
            } else {
                $static->noDelimiter = $left->fullcode;
            }
        } elseif ($right->atom === 'Name') {
            if (in_array($this->tokens[$this->id + 1][0], array($this->phptokens::T_INSTEADOF,
                                                                $this->phptokens::T_AS))) {

                list($fullnspath, $aliased) = $this->getFullnspath($left);
                $this->calls->addCall('class', $fullnspath, $left);
                $left->fullnspath = $fullnspath;
                $left->aliased    = $aliased;

                $static = $this->addAtom('Staticmethod');
                $this->addLink($static, $right, 'METHOD');
                $fullcode = "{$left->fullcode}::{$right->fullcode}";
                $static->fullnspath = "{$left->fullnspath}::".mb_strtolower($right->fullcode);

                // No need for runplugin
            } else {
                $static = $this->addAtom('Staticconstant');
                $this->addLink($static, $right, 'CONSTANT');
                $fullcode = "{$left->fullcode}::{$right->fullcode}";
                $this->runPlugins($static, array('CLASS'    => $left,
                                                 'CONSTANT' => $right));
            }
        } elseif (in_array($right->atom, array('Variable', 'Array', 'Arrayappend', 'MagicConstant', 'Concatenation', 'Block', 'Boolean', 'Null', 'Staticpropertyname'))) {
            $static = $this->addAtom('Staticproperty');
            $this->addLink($static, $right, 'MEMBER');
            $fullcode = $left->fullcode.'::'.$right->fullcode;
            $this->runPlugins($static, array('CLASS'  => $left,
                                             'MEMBER' => $right));
        } elseif ($right->atom === 'Methodcallname') {
            $static = $this->addAtom('Staticmethodcall');
            $this->addLink($static, $right, 'METHOD');
            $fullcode = $left->fullcode.'::'.$right->fullcode;
            $this->runPlugins($static, array('CLASS'  => $left,
                                             'METHOD' => $right));
        } else {
            throw new LoadError("Unprocessed atom in static call (right) : ".$right->atom.':'.$this->filename.':'.__LINE__);
        }

        $this->addLink($static, $left, 'CLASS');

        $static->code     = $this->tokens[$current][1];
        $static->fullcode = $fullcode;
        $static->line     = $this->tokens[$current][2];
        $static->token    = $this->getToken($this->tokens[$current][0]);

        if (!empty($left->fullnspath)){
            if (in_array($static->atom, array('Staticmethodcall', 'Staticmethod',))) {
                $name = mb_strtolower($right->code);
                $this->calls->addCall('staticmethod',  "$left->fullnspath::$name", $static);
            } elseif ($static->atom === 'Staticconstant') {
                $this->calls->addCall('staticconstant',  "$left->fullnspath::$right->code", $static);
            } elseif ($static->atom === 'Staticproperty') {
                $this->calls->addCall('staticproperty',  "$left->fullnspath::$right->code", $static);
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

    private function processOperator($atom, $finals, $links = array('LEFT', 'RIGHT')) {
        $current = $this->id;
        $operator = $this->addAtom($atom);

        $left = $this->popExpression();
        $this->addLink($operator, $left, $links[0]);

        $this->contexts->nestContext(Context::CONTEXT_NOSEQUENCE);
        $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        do {
            $right = $this->processNext();

            if (in_array($this->tokens[$this->id + 1][0], $this->assignations)) {
                $right = $this->processNext();
            }
        } while (!in_array($this->tokens[$this->id + 1][0], $finals) );

        $this->contexts->exitContext(Context::CONTEXT_NOSEQUENCE);
        $this->popExpression();

        $this->addLink($operator, $right, $links[1]);
        
        $operator->code      = $this->tokens[$current][1];
        $operator->fullcode  = $left->fullcode.' '.$this->tokens[$current][1].' '.$right->fullcode;
        $operator->line      = $this->tokens[$current][2];
        $operator->token     = $this->getToken($this->tokens[$current][0]);
        
        $extras = array($links[0] => $left, $links[1] => $right);
        $this->runPlugins($operator, $extras);

        $this->pushExpression($operator);
        $this->checkExpression();

        return $operator;
    }

    private function processObjectOperator() {
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

        if (in_array($right->atom, array('Variable',
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
            $static = $this->addAtom('Member');
            $links = 'MEMBER';
            $static->enclosing = self::NO_ENCLOSING;
        } elseif (in_array($right->atom, array('Methodcallname', 'Methodcall'))) {
            $static = $this->addAtom('Methodcall');
            $links = 'METHOD';
        } else {
            throw new LoadError("Unprocessed atom in object call (right) : ".$right->atom.':'.$this->filename.':'.__LINE__);
        }

        $this->addLink($static, $left, 'OBJECT');
        $this->addLink($static, $right, $links);

        $static->code      = $this->tokens[$current][1];
        $static->fullcode  = $left->fullcode.'->'.$right->fullcode;
        $static->line      = $this->tokens[$current][2];
        $static->token     = $this->getToken($this->tokens[$current][0]);

        if ($left->atom   === 'This' ){
            if ($static->atom === 'Methodcall') {
                $this->calls->addCall('method', $left->fullnspath.'::'.mb_strtolower($right->code), $static);
            } elseif ($static->atom === 'Member') {
                $this->calls->addCall('property',  $left->fullnspath.'::$'.$right->code, $static);
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

    private function processAssignation() {
        $finals = $this->precedence->get($this->tokens[$this->id][0]);
        $finals = array_merge($finals, $this->assignations);
        
        return $this->processOperator('Assignation', $finals);
    }

    private function processCoalesce() {
        return $this->processOperator('Coalesce', $this->precedence->get($this->tokens[$this->id][0]));
    }

    private function processEllipsis() {
        // Simply skipping the ...
        $finals = $this->precedence->get($this->phptokens::T_ELLIPSIS);
        while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
            $this->processNext();
        }

        $operand = $this->popExpression();
        $operand->fullcode  = '...'.$operand->fullcode;
        $operand->variadic  = self::VARIADIC;

        $this->pushExpression($operand);

        return $operand;
    }

    private function processAnd() {
        if ($this->hasExpression()) {
            return $this->processOperator('Logical', $this->precedence->get($this->tokens[$this->id][0]));
        } else {
            // Simply skipping the &
            $this->processNext();

            $operand = $this->popExpression();
            $operand->fullcode  = '&'.$operand->fullcode;
            $operand->reference = self::REFERENCE;

            $this->pushExpression($operand);

            return $operand;
        }
    }

    private function processLogical() {
        return $this->processOperator('Logical', $this->precedence->get($this->tokens[$this->id][0]));
    }

    private function processMultiplication() {
        $finals = $this->precedence->get($this->tokens[$this->id][0]);
        $finals[] = $this->phptokens::T_STAR;
        $finals[] = $this->phptokens::T_SLASH;
        $finals[] = $this->phptokens::T_PERCENTAGE;
        return $this->processOperator('Multiplication', $finals);
    }

    private function processPower() {
        $finals = $this->precedence->get($this->tokens[$this->id][0]);
        $finals[] = $this->phptokens::T_POWER;
        return $this->processOperator('Power', $finals);
    }

    private function processComparison() {
        return $this->processOperator('Comparison', $this->precedence->get($this->tokens[$this->id][0]));
    }

    private function processDot() {
        $current = $this->id;
        $concatenation = $this->addAtom('Concatenation');
        $fullcode= array();
        $concat = array();
        $noDelimiter = '';
        $rank = -1;

        $contains = $this->popExpression();
        $this->addLink($concatenation, $contains, 'CONCAT');
        $contains->rank = ++$rank;
        $fullcode[] = $contains->fullcode;
        $concat[] = $contains;
        $noDelimiter .= $contains->noDelimiter;

        $this->contexts->nestContext(Context::CONTEXT_NOSEQUENCE);
        $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);

        $finals = $this->precedence->get($this->tokens[$this->id][0]);
        $finals = array_diff($finals, array($this->phptokens::T_REQUIRE,
                                            $this->phptokens::T_REQUIRE_ONCE,
                                            $this->phptokens::T_INCLUDE,
                                            $this->phptokens::T_INCLUDE_ONCE,
                                            $this->phptokens::T_PLUS,
                                            $this->phptokens::T_MINUS,
                                            $this->phptokens::T_PRINT,
                                            $this->phptokens::T_ECHO,
                                            ));

        while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
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

        $concatenation->code        = $this->tokens[$current][1];
        $concatenation->fullcode    = implode(' . ', $fullcode);
        $concatenation->noDelimiter = $noDelimiter;
        $concatenation->line        = $this->tokens[$current][2];
        $concatenation->token       = $this->getToken($this->tokens[$current][0]);
        $concatenation->count       = $rank + 1;
        
        $this->pushExpression($concatenation);
        $this->runPlugins($concatenation, $concat);

        $this->checkExpression();

        return $concatenation;
    }

    private function processInstanceof() {
        $current = $this->id;
        $instanceof = $this->addAtom('Instanceof');

        $left = $this->popExpression();
        $this->addLink($instanceof, $left, 'VARIABLE');

        $finals = $this->precedence->get($this->tokens[$this->id][0]);
        while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
            $this->processNext();
        }
        $right = $this->popExpression();

        $this->addLink($instanceof, $right, 'CLASS');
        
        list($fullnspath, $aliased) = $this->getFullnspath($right);
        $this->calls->addCall('class', $fullnspath, $right);
        $right->fullnspath = $fullnspath;
        $right->aliased = $aliased;

        $instanceof->code     = $this->tokens[$current][1];
        $instanceof->fullcode = $left->fullcode.' '.$this->tokens[$current][1].' '.$right->fullcode;
        $instanceof->line     = $this->tokens[$current][2];
        $instanceof->token    = $this->getToken($this->tokens[$current][0]);

        $this->runPlugins($instanceof, array('VARIABLE' => $left,
                                             'CLASS'    => $right));
        $this->pushExpression($instanceof);

        return $instanceof;
    }

    private function processKeyvalue() {
        return $this->processOperator('Keyvalue', $this->precedence->get($this->tokens[$this->id][0]), array('INDEX', 'VALUE'));
    }

    private function processBitshift() {
        return $this->processOperator('Bitshift', $this->precedence->get($this->tokens[$this->id][0]));
    }

    private function processIsset() {
        $current = $this->id;
        
        $atom = ucfirst(mb_strtolower($this->tokens[$current][1]));
        ++$this->id;
        $functioncall = $this->processArguments($atom, array(), $argumentsList);

        $argumentsFullcode = $functioncall->fullcode;
        
        $functioncall->code       = $this->tokens[$current][1];
        $functioncall->fullcode   = $this->tokens[$current][1].'('.$argumentsFullcode.')';
        $functioncall->line       = $this->tokens[$current][2];
        $functioncall->token      = $this->getToken($this->tokens[$current][0]);
        $functioncall->fullnspath = '\\'.mb_strtolower($this->tokens[$current][1]);
        $functioncall->aliased    = self::NOT_ALIASED;

        $this->pushExpression($functioncall);

        $this->checkExpression();

        return $functioncall;
    }
    
    private function processEcho() {
        $current = $this->id;
        
        $functioncall = $this->processArguments('Echo',
                                                array($this->phptokens::T_SEMICOLON,
                                                      $this->phptokens::T_CLOSE_TAG,
                                                      $this->phptokens::T_END,
                                                     ),
                                                $argumentsList);
        $argumentsFullcode = $functioncall->fullcode;
        
        $functioncall->code       = $this->tokens[$current][1];
        $functioncall->fullcode   = $this->tokens[$current][1].' '.$argumentsFullcode;
        $functioncall->line       = $this->tokens[$current][2];
        $functioncall->token      = $this->getToken($this->tokens[$current][0]);
        $functioncall->fullnspath = '\\'.mb_strtolower($this->tokens[$current][1]);
        $functioncall->aliased    = self::NOT_ALIASED;

        $this->pushExpression($functioncall);

        // processArguments goes too far, up to ;
        --$this->id;

        if ($this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $functioncall;
    }

    private function processHalt() {
        $halt = $this->addAtom('Halt');
        $halt->code     = $this->tokens[$this->id][1];
        $halt->fullcode = $this->tokens[$this->id][1];
        $halt->line     = $this->tokens[$this->id][2];
        $halt->token    = $this->getToken($this->tokens[$this->id][0]) ;

        ++$this->id; // skip halt
        ++$this->id; // skip (
        // Skipping all arguments. This is not a function!

        $this->pushExpression($halt);
        ++$this->id; // skip (
        $this->processSemicolon();

        return $halt;
    }

    private function processPrint() {
        $current = $this->id;

        $noSequence = $this->contexts->isContext(Context::CONTEXT_NOSEQUENCE);
        if ($noSequence === false) {
            $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        }

        $fullcode = array();
        $finals = $this->precedence->get($this->tokens[$this->id][0]);
        while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
            $this->processNext();
        }
        if ($noSequence === false) {
            $this->contexts->toggleContext(Context::CONTEXT_NOSEQUENCE);
        }

        if (in_array($this->tokens[$current][0], array($this->phptokens::T_INCLUDE, 
                                                       $this->phptokens::T_INCLUDE_ONCE, 
                                                       $this->phptokens::T_REQUIRE, 
                                                       $this->phptokens::T_REQUIRE_ONCE,
                                                       ))) {
            $functioncall = $this->addAtom('Include');
        } else {
            $functioncall = $this->addAtom('Print');
        }
        $index = $this->popExpression();
        $index->rank = 0;
        $this->addLink($functioncall, $index, 'ARGUMENT');
        $fullcode[] = $index->fullcode;

        $functioncall->code       = $this->tokens[$current][1];
        $functioncall->fullcode   = $this->tokens[$current][1].' '.$index->fullcode;
        $functioncall->line       = $this->tokens[$current][2];
        $functioncall->token      = $this->getToken($this->tokens[$current][0]);
        $functioncall->count      = 1; // Only one argument for print
        $functioncall->fullnspath = '\\'.mb_strtolower($this->tokens[$current][1]);

        $this->pushExpression($functioncall);
        $this->runPlugins($functioncall, array('ARGUMENT' => $index));

        $this->checkExpression();

        return $functioncall;
    }

    //////////////////////////////////////////////////////
    /// generic methods
    //////////////////////////////////////////////////////
    private function addAtom($atom) {
        if (!in_array($atom, GraphElements::$ATOMS)) {
            throw new LoadError('Undefined atom '.$atom.':'.$this->filename.':'.__LINE__);
        }
        $a = $this->atomGroup->factory($atom);
        $this->atoms[$a->id] = $a;
        
        return $a;
    }

    private function addAtomVoid() {
        $void = $this->addAtom('Void');
        $void->code        = 'Void';
        $void->fullcode    = self::FULLCODE_VOID;
        $void->line        = $this->tokens[$this->id][2];
        $void->token       = $this->phptokens::T_VOID;
        $void->noDelimiter = '';
        $void->delimiter   = '';
        
        $this->runPlugins($void);

        return $void;
    }

    private function addLink(Atom $origin, Atom $destination, $label) {
        if (!in_array($label, array_merge(GraphElements::$LINKS, GraphElements::$LINKS_EXAKAT))) {
            throw new LoadError('Undefined link '.$label.' for atom '.$origin->atom.' : '.$this->filename.':'.$origin->line);
        }
        $o = $origin->atom;
        $d = $destination->atom;

        if (!isset($this->links[$label]))         { $this->links[$label]= array(); }
        if (!isset($this->links[$label][$o]))     { $this->links[$label][$o]= array(); }
        if (!isset($this->links[$label][$o][$d])) { $this->links[$label][$o][$d]= array(); }

        $this->links[$label][$o][$d][$origin->id.'-'.$destination->id] = array('origin'      => $origin->id,
                                                                               'destination' => $destination->id);
        return true;
    }

    private function pushExpression($id) {
        $this->expressions[] = $id;
    }

    private function hasExpression() {
        return !empty($this->expressions);
    }

    private function popExpression() {
        if (empty($this->expressions)) {
            $id = $this->addAtomVoid();
        } else {
            $id = array_pop($this->expressions);
        }
        return $id;
    }

    private function checkTokens($filename) {
        if (!empty($this->expressions)) {
            var_dump($this->expressions);
            throw new LoadError( "Warning : expression is not empty in $filename : ".count($this->expressions));
        }

        if (($count = $this->contexts->getCount(Context::CONTEXT_NOSEQUENCE)) !== false) {
            throw new LoadError( "Warning : context for sequence is not back to 0 in $filename : it is ".$count.PHP_EOL);
        }

        if (($count = $this->contexts->getCount(Context::CONTEXT_NEW)) !== false) {
            throw new LoadError( "Warning : context for new is not back to 0 in $filename : it is ".$count.PHP_EOL);
        }

        if (($count = $this->contexts->getCount(Context::CONTEXT_FUNCTION)) !== false) {
            throw new LoadError( "Warning : context for function is not back to 0 in $filename : it is ".$count.PHP_EOL);
        }

        if (($count = $this->contexts->getCount(Context::CONTEXT_CLASS)) !== false) {
            throw new LoadError( "Warning : context for class is not back to 0 in $filename : it is ".$count.PHP_EOL);
        }

        if (($count = $this->contexts->getCount(Context::CONTEXT_TRAIT)) !== false) {
            throw new LoadError( "Warning : context for trait is not back to 0 in $filename : it is ".$count.PHP_EOL);
        }

        if (($count = $this->contexts->getCount(Context::CONTEXT_INTERFACE)) !== false) {
            throw new LoadError( "Warning : context for interface is not back to 0 in $filename : it is ".$count.PHP_EOL);
        }

        // All node has one incoming or one outgoing link (outgoing or incoming).
        // Except Variabledefinition
        $O = array();
        $D = array();
        foreach($this->links as $label => $origins) {
            if ($label === 'DEFINITION') {
                continue;
            }
            foreach($origins as $destinations) {
                foreach($destinations as $links) {
                    foreach($links as $link) {
                        $O[] = $link['origin'];
                        $D[] = $link['destination'];
                    }
                }
            }
        }

        $O = array_count_values($O);
        $D = array_count_values($D);

        foreach($this->atoms as $id => $atom) {
            if ($id === 1) { continue; }
            if ($atom->atom === 'Variabledefinition') { continue; }

            if (!isset($D[$id])) {
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
    }

    private function processDefineAsClassalias($argumentsId) {
        if (empty($this->argumentsId[0]->noDelimiter) ||
            empty($this->argumentsId[1]->noDelimiter)   ) {
            $this->argumentsId[0]->fullnspath = '\\'; // cancels it all
            $this->argumentsId[1]->fullnspath = '\\';
            return;
        }

        if (preg_match('/[$ #?;%^\*\'\"\. <>~&,|\(\){}\[\]\/\s=+!`@\-]/is', $this->argumentsId[0]->noDelimiter)) {
            $this->argumentsId[0]->fullnspath = '\\'; // cancels it all
            $this->argumentsId[1]->fullnspath = '\\';
            return; // Can't be a constant anyway.
        }

        if (preg_match('/[$ #?;%^\*\'\"\. <>~&,|\(\){}\[\]\/\s=+!`@\-]/is', $this->argumentsId[1]->noDelimiter)) {
            $this->argumentsId[0]->fullnspath = '\\'; // cancels it all
            $this->argumentsId[1]->fullnspath = '\\';
            return; // Can't be a constant anyway.
        }

        $fullnspathClass = makeFullNsPath($this->argumentsId[0]->noDelimiter, false);
        if ($this->argumentsId[0]->noDelimiter[0] === '\\') {
            // Added a second \\ when the string already has one. Actual PHP behavior
            $fullnspathClass = "\\$fullnspathClass";
        }
        $this->argumentsId[0]->fullnspath = $fullnspathClass;

        $fullnspathAlias = makeFullNsPath($this->argumentsId[1]->noDelimiter, false);
        if ($this->argumentsId[1]->noDelimiter[0] === '\\') {
            // Added a second \\ when the string already has one. Actual PHP behavior
            $fullnspathAlias = "\\$fullnspathAlias";
        }
        $this->argumentsId[1]->fullnspath = $fullnspathAlias;

        $this->calls->addCall('class', $fullnspathClass, $argumentsId[0]);
        $this->calls->addDefinition('class', $fullnspathAlias, $argumentsId[1]);
    }

    private function processDefineAsConstants($const, $name, $case_insensitive = false) {
        if (empty($name->noDelimiter)) {
            $name->fullnspath = '\\';
            return;
        }

        if (preg_match('/[$ #?;%^\*\'\"\. <>~&,|\(\){}\[\]\/\s=+!`@\-]/is', $name->noDelimiter)) {
            return; // Can't be a constant anyway.
        }
        
        $fullnspath = makeFullNsPath($name->noDelimiter, true);
        if ($name->noDelimiter[0] === '\\') {
            // Added a second \\ when the string already has one. Actual PHP behavior
            $fullnspath = "\\$fullnspath";
        }

        $this->calls->addDefinition('const', $fullnspath, $const);
        $name->fullnspath = $fullnspath;

        if ($case_insensitive === true) {
            $this->calls->addDefinition('const', mb_strtolower($fullnspath), $const);
        }
    }

    private function saveFiles() {
        $this->loader->saveFiles($this->exakatDir, $this->atoms, $this->links, $this->id0);
        $this->reset();
    }

    private function startSequence() {
        $this->sequence = $this->addAtom('Sequence');
        $this->sequence->code      = ';';
        $this->sequence->fullcode  = ' '.self::FULLCODE_SEQUENCE.' ';
        $this->sequence->line      = $this->tokens[$this->id][2];
        $this->sequence->token     = 'T_SEMICOLON';
        $this->sequence->bracket   = self::NOT_BRACKET;
        $this->sequence->elements  = array();

        $this->sequences[]    = $this->sequence;
        $this->sequenceRank[] = -1;
        $this->sequenceCurrentRank = count($this->sequenceRank) - 1;
    }

    private function addToSequence($id) {
        $this->addLink($this->sequence, $id, 'EXPRESSION');
        $id->rank = ++$this->sequenceRank[$this->sequenceCurrentRank];
        $this->sequence->elements[]  = $id;
    }

    private function endSequence() {
        $this->sequence->count = $this->sequenceRank[$this->sequenceCurrentRank] + 1;
        
        $this->runPlugins($this->sequence, $this->sequence->elements);
        unset($this->sequence->elements);

        array_pop($this->sequences);
        array_pop($this->sequenceRank);
        $this->sequenceCurrentRank = count($this->sequenceRank) - 1;

        if (empty($this->sequences)) {
            $this->sequence = null;
        } else {
            $this->sequence = $this->sequences[count($this->sequences) - 1];
        }
    }

    private function getToken($token) {
        return $this->php->getTokenName($token);
    }

    private function getFullnspath($name, $type = 'class') {
        // Handle static, self, parent and PHP natives function
        if (isset($name->absolute) && ($name->absolute === self::ABSOLUTE)) {
            if ($type === 'const') {
                if (isset($this->uses['define'][mb_strtolower($name->fullnspath)])) {
//                    $this->addLink($this->uses['define'][mb_strtolower($name->fullnspath)], $name, 'DEFINITION');
                    return array(mb_strtolower($name->fullnspath), self::NOT_ALIASED);
                } else {
                    $fullnspath = preg_replace_callback('/^(.*)\\\\([^\\\\]+)$/', function ($r) {
                        return mb_strtolower($r[1]).'\\'.$r[2];
                    }, $name->fullcode);
                    return array($fullnspath, self::NOT_ALIASED);
                }
            } else {
                return array(mb_strtolower($name->fullcode), self::NOT_ALIASED);
            }
        } elseif (!in_array($name->atom, array('Nsname', 'Identifier', 'Name', 'String', 'Null', 'Boolean', 'Static', 'Parent', 'Self', 'Newcall'))) {
            // No fullnamespace for non literal namespaces
            return array('', self::NOT_ALIASED);
        } elseif (in_array($name->token, array('T_ARRAY', 'T_EVAL', 'T_ISSET', 'T_EXIT', 'T_UNSET', 'T_ECHO', 'T_PRINT', 'T_LIST', 'T_EMPTY'))) {
            // For language structures, it is always in global space, like eval or list
            return array('\\'.mb_strtolower($name->code), self::NOT_ALIASED);
        } elseif (mb_strtolower(substr($name->fullcode, 0, 10)) === 'namespace\\') {
            // namespace\A\B
            return array(substr($this->namespace, 0, -1).mb_strtolower(substr($name->fullcode, 9)), self::NOT_ALIASED);
        } elseif (in_array($name->atom, array('Static', 'Self'))) {
            if (empty($this->currentClassTrait)) {
                return array(self::FULLNSPATH_UNDEFINED, self::NOT_ALIASED);
            } else {
                return array($this->currentClassTrait[count($this->currentClassTrait) - 1]->fullnspath, self::NOT_ALIASED);
            }
        } elseif ($name->atom === 'Newcall' && mb_strtolower($name->code) === 'static') {
            if (empty($this->currentClassTrait)) {
                return array(self::FULLNSPATH_UNDEFINED, self::NOT_ALIASED);
            } else {
                return array($this->currentClassTrait[count($this->currentClassTrait) - 1]->fullnspath, self::NOT_ALIASED);
            }
        } elseif (in_array($name->atom, array('Parent'))) {
            return array('\\parent', self::NOT_ALIASED);
        } elseif (in_array($name->atom, array('Boolean', 'Null'))) {
            return array('\\'.mb_strtolower($name->fullcode), self::NOT_ALIASED);
        } elseif (in_array($name->atom, array('Identifier', 'Name', 'Newcall'))) {
            $fnp = mb_strtolower($name->code);

            if (($offset = strpos($fnp, '\\')) === false) {
                $prefix = $fnp;
            } else {
                $prefix = substr($fnp, 0, $offset);
            }
            
            // This is an identifier, self or parent
            if ($type === 'class' && isset($this->uses['class'][$fnp])) {
                $this->addLink($name, $this->uses['class'][$fnp], 'DEFINITION');
                return array($this->uses['class'][$fnp]->fullnspath, self::ALIASED);

            } elseif ($type === 'class' && isset($this->uses['class'][$prefix])) {
                $this->addLink($name, $this->uses['class'][$prefix], 'DEFINITION');
                return array($this->uses['class'][$prefix]->fullnspath.str_replace($prefix, '', $fnp), self::ALIASED);

            } elseif ($type === 'const') {
                if (isset($this->uses['const'][$name->code])) {
//                    $this->addLink($this->uses['const'][$name->code], $name, 'DEFINITION');
                    return array($this->uses['const'][$name->code]->fullnspath, self::ALIASED);
                } elseif (isset($this->uses['define'][mb_strtolower($name->fullnspath)])) {
//                    $this->addLink($this->uses['define'][mb_strtolower($name->fullnspath)], $name, 'DEFINITION');
                    return array(mb_strtolower($name->fullnspath), self::NOT_ALIASED);
                } else {
                    return array($this->namespace.$name->fullcode, self::NOT_ALIASED);
                }

            } elseif ($type === 'function' && isset($this->uses['function'][$prefix])) {

                $this->addLink($this->uses['function'][$prefix], $name, 'DEFINITION');
                return array($this->uses['function'][$prefix]->fullnspath, self::ALIASED);

            } else {
                return array($this->namespace.mb_strtolower($name->fullcode), self::NOT_ALIASED);
            }
        } elseif ($name->atom === 'String' && isset($name->noDelimiter)) {
            if (in_array(mb_strtolower($name->noDelimiter), array('self', 'static'))) {
                if (empty($this->currentClassTrait)) {
                    return array(self::FULLNSPATH_UNDEFINED, self::NOT_ALIASED);
                } else {
                    return array($this->currentClassTrait[count($this->currentClassTrait) - 1]->fullnspath, self::NOT_ALIASED);
                }
            }
            
            $prefix =  str_replace('\\\\', '\\', mb_strtolower($name->noDelimiter));
            $prefix = "\\$prefix";

            // define doesn't care about use...
            return array($prefix, self::NOT_ALIASED);
        } else {
            // Finally, the case for a nsname
            $prefix = mb_strtolower( substr($name->code, 0, strpos($name->code.'\\', '\\')) );

            if (isset($this->uses[$type][$prefix])) {
                $this->addLink( $name, $this->uses[$type][$prefix], 'DEFINITION');
                return array($this->uses[$type][$prefix]->fullnspath.mb_strtolower( substr($name->fullcode, strlen($prefix)) ) , 0);
            } elseif ($type === 'const') {
                $parts = explode('\\', $name->fullcode);
                $last = array_pop($parts);
                $fullnspath = $this->namespace.mb_strtolower(implode('\\', $parts)).'\\'.$last;
                return array($fullnspath, 0);
            } else {
                return array($this->namespace.mb_strtolower($name->fullcode), 0);
            }
        }
    }

    private function setNamespace($namespace = 0) {
        if ($namespace === 0) {
            $this->namespace = '\\';
            $this->uses = array('function' => array(),
                                'const'    => array(),
                                'class'    => array());
        } elseif ($namespace->atom === 'Void') {
            $this->namespace = '\\';
        } else {
            $this->namespace = mb_strtolower($namespace->fullcode).'\\';
            if ($this->namespace[0] !== '\\') {
                $this->namespace = '\\'.$this->namespace;
            }
        }
    }

    private function addNamespaceUse($origin, $alias, $useType, Atom $use) {
        if ($origin !== $alias) { // Case of A as B
            // Alias is the 'As' expression.
            $offset = strrpos($alias->fullcode, ' as ');
            if ($useType === 'const') {
                $alias = substr($alias->fullcode, $offset + 4);
            } else {
                $alias = mb_strtolower(substr($alias->fullcode, $offset + 4));
            }
        } elseif (($offset = strrpos($alias->code, '\\')) === false) {
            // namespace without \
            $alias = $alias->code;
        } else {
            // namespace with \
            $alias = substr($alias->code, $offset + 1);
        }
        
        if ($useType !== 'const') {
            $alias = mb_strtolower($alias);
        }

        $this->uses[$useType][$alias] = $use;

        return $alias;
    }

    private function logTime($step) {
        static $begin, $end, $start;

        if ($this->logTimeFile === null) {
            $this->logTimeFile = fopen($this->config->projects_root.'/projects/'.$this->config->project.'/log/load.timing.csv', 'w+');
        }

        $end = microtime(true);
        if ($begin === null) {
            $begin = $end;
            $start = $end;
        }

        fwrite($this->logTimeFile, $step."\t".($end - $begin)."\t".($end - $start).PHP_EOL);
        $begin = $end;
    }
    
    private function makeAnonymous($type = 'class') {
        static $anonymous = 'a';

        if (!in_array($type, array('class', 'function'))) {
            throw new LoadError('Classes and Functions are the only anonymous');
        }

        ++$anonymous;
        return "$type@$anonymous";
    }
    
    private function setOptions($atom) {
        $fullcode = array();

        foreach($this->optionsTokens as $name => $option) {
            $fullcode[] = $option;
            if (in_array($name, array('Public', 'Protected', 'Private', 'Var'))) {
                $atom->visibility = strtolower($option);
            } elseif (in_array($name, array('Final', 'Static', 'Abstract'))) {
                $atom->{strtolower($name)} = 1;
            } elseif ($name === 'Typehint') {
                //Nothing, just 
            } else {
                throw new LoadError("\nUnknown option name : $name\n");
            }
        }
        $this->optionsTokens = array();
        
        return $fullcode;
    }
    
    private function finishWithAlternative($isColon) {
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

    private function checkExpression() {
        if ( !$this->contexts->isContext(Context::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === $this->phptokens::T_CLOSE_TAG) {
            $this->processSemicolon();
        }
    }
    
    private function whichSyntax($current, $colon) {
        return in_array($this->tokens[$current][0], array($this->phptokens::T_FOR,
                                                          $this->phptokens::T_FOREACH,
                                                          $this->phptokens::T_WHILE,
                                                          $this->phptokens::T_DO,
                                                          $this->phptokens::T_DECLARE,
                                                          $this->phptokens::T_SWITCH,
                                                          $this->phptokens::T_IF,
                                                          $this->phptokens::T_ELSEIF,
                                                         )) && 
               ($this->tokens[$colon][0] === $this->phptokens::T_COLON) ?
                self::ALTERNATIVE_SYNTAX : 
                self::NORMAL_SYNTAX;
    }

}

?>