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

namespace Exakat\Tasks;

use Exakat\Config;
use Exakat\Exceptions\InvalidPHPBinary;
use Exakat\Exceptions\LoadError;
use Exakat\Exceptions\MustBeAFile;
use Exakat\Exceptions\MustBeADir;
use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\NoFileToProcess;
use Exakat\Exceptions\NoSuchFile;
use Exakat\Exceptions\NoSuchLoader;
use Exakat\Loader\CypherG3;
use Exakat\Loader\Neo4jImport;
use Exakat\Loader\GremlinServerNeo4j;
use Exakat\Phpexec;
use Exakat\Tasks\LoadFinal;
use Exakat\Tasks\Precedence;

const T_BANG                         = '!';
const T_CLOSE_BRACKET                = ']';
const T_CLOSE_PARENTHESIS            = ')';
const T_CLOSE_CURLY                  = '}';
const T_COMMA                        = ',';
const T_DOT                          = '.';
const T_EQUAL                        = '=';
const T_MINUS                        = '-';
const T_AT                           = '@';
const T_OPEN_BRACKET                 = '[';
const T_OPEN_CURLY                   = '{';
const T_OPEN_PARENTHESIS             = '(';
const T_PERCENTAGE                   = '%';
const T_PLUS                         = '+';
const T_QUESTION                     = '?';
const T_COLON                        = ':';
const T_SEMICOLON                    = ';';
const T_SLASH                        = '/';
const T_STAR                         = '*';
const T_SMALLER                      = '<';
const T_GREATER                      = '>';
const T_TILDE                        = '~';
const T_QUOTE                        = '"';
const T_DOLLAR                       = '$';
const T_AND                          = '&';
const T_PIPE                         = '|';
const T_CARET                        = '^';
const T_BACKTICK                     = '`';

const T_END                          = 'The End';
const T_REFERENCE                    = 'r';
const T_VOID                         = 'v';

class Load extends Tasks {
    const CONCURENCE = self::NONE;

    private $php    = null;
    private static $client = null;

    private $precedence;

    private $calls = array();

    private $namespace = '\\';
    private $uses   = array('function' => array(),
                            'const'    => array(),
                            'class'    => array());
    private $usesId = array('function' => array(),
                            'const'    => array(),
                            'class'    => array());

    private $filename   = null;
    private $line       = 0;

    private $links = array();

    private $sequences = array();

    private $currentClassTrait = array();

    private $tokens = array();
    private $id = 0;
    private $id0 = 0;

    const FULLCODE_SEQUENCE = ' /**/ ';
    const FULLCODE_BLOCK    = ' { /**/ } ';
    const FULLCODE_VOID     = ' ';

    const ALIASED           = 1;
    const NOT_ALIASED       = 0;

    const NO_VALUE          = -1;

    const CONTEXT_CLASS      = 1;
    const CONTEXT_INTERFACE  = 2;
    const CONTEXT_TRAIT      = 3;
    const CONTEXT_FUNCTION   = 4;
    const CONTEXT_NEW        = 5;
    const CONTEXT_NOSEQUENCE = 6;
    private $contexts = array(self::CONTEXT_CLASS      => false,
                              self::CONTEXT_INTERFACE  => false,
                              self::CONTEXT_TRAIT      => false,
                              self::CONTEXT_FUNCTION   => false,
                              self::CONTEXT_NEW        => false,
                              self::CONTEXT_NOSEQUENCE => 0,
                         );

    private $optionsTokens = array();

    static public $PROP_ALTERNATIVE = array('Declare', 'Ifthen', 'For', 'Foreach', 'Switch', 'While');
    static public $PROP_REFERENCE   = array('Variable', 'Property', 'Array', 'Function', 'Functioncall', 'Methodcall');
    static public $PROP_VARIADIC    = array('Variable', 'Array', 'Property', 'Staticproperty', 'Staticconstant', 'Methodcall', 'Staticmethodcall', 'Functioncall', 'Identifier', 'Nsname');
    static public $PROP_DELIMITER   = array('String', 'Heredoc');
    static public $PROP_NODELIMITER = array('String', 'Variable');
    static public $PROP_HEREDOC     = array('Heredoc');
    static public $PROP_COUNT       = array('Sequence', 'Arguments', 'Heredoc', 'Shell', 'String', 'Try', 'Catch', 'Const', 'Ppp', 'Global', 'Static');
    static public $PROP_FNSNAME     = array('Functioncall', 'Function', 'Class', 'Trait', 'Interface', 'Identifier', 'Nsname', 'As', 'Void', 'Static', 'Namespace');
    static public $PROP_ABSOLUTE    = array('Nsname');
    static public $PROP_ALIAS       = array('Nsname', 'Identifier', 'As');
    static public $PROP_ORIGIN      = array('Nsname', 'Identifier', 'As');
    static public $PROP_ENCODING    = array('String');
    static public $PROP_INTVAL      = array('Integer');
    static public $PROP_STRVAL      = array('String');
    static public $PROP_ENCLOSING   = array('Variable', 'Array', 'Property');
    static public $PROP_ARGS_MAX    = array('Arguments');
    static public $PROP_ARGS_MIN    = array('Arguments');
    static public $PROP_BRACKET     = array('Sequence');
    static public $PROP_CLOSETAG    = array('Php');
    static public $PROP_ALIASED     = array('Function', 'Interface', 'Trait', 'Class');
    static public $PROP_BOOLEAN     = array('Boolean', 'Null', 'Integer', 'String', 'Functioncall', 'Real');

    static public $PROP_OPTIONS = array();

    static public $TOKENS = array(
                     ';'  => \Exakat\Tasks\T_SEMICOLON,
                     '+'  => \Exakat\Tasks\T_PLUS,
                     '-'  => \Exakat\Tasks\T_MINUS,
                     '/'  => \Exakat\Tasks\T_SLASH,
                     '*'  => \Exakat\Tasks\T_STAR,
                     '.'  => \Exakat\Tasks\T_DOT,
                     '['  => \Exakat\Tasks\T_OPEN_BRACKET,
                     ']'  => \Exakat\Tasks\T_CLOSE_BRACKET,
                     '('  => \Exakat\Tasks\T_OPEN_PARENTHESIS,
                     ')'  => \Exakat\Tasks\T_CLOSE_PARENTHESIS,
                     '{'  => \Exakat\Tasks\T_OPEN_CURLY,
                     '}'  => \Exakat\Tasks\T_CLOSE_CURLY,
                     '='  => \Exakat\Tasks\T_EQUAL,
                     ','  => \Exakat\Tasks\T_COMMA,
                     '!'  => \Exakat\Tasks\T_BANG,
                     '~'  => \Exakat\Tasks\T_TILDE,
                     '@'  => \Exakat\Tasks\T_AT,
                     '?'  => \Exakat\Tasks\T_QUESTION,
                     ':'  => \Exakat\Tasks\T_COLON,
                     '<' => \Exakat\Tasks\T_SMALLER,
                     '>' => \Exakat\Tasks\T_GREATER,
                     '%' => \Exakat\Tasks\T_PERCENTAGE,
                     '"' => \Exakat\Tasks\T_QUOTE,
                     '$' => \Exakat\Tasks\T_DOLLAR,
                     '&' => \Exakat\Tasks\T_AND,
                     '|' => \Exakat\Tasks\T_PIPE,
                     '^' => \Exakat\Tasks\T_CARET,
                     '`' => \Exakat\Tasks\T_BACKTICK,
                   );

    static public $TOKENNAMES = array(
                         ';'  => 'T_SEMICOLON',
                         '+'  => 'T_PLUS',
                         '-'  => 'T_MINUS',
                         '/'  => 'T_SLASH',
                         '*'  => 'T_STAR',
                         '.'  => 'T_DOT',
                         '['  => 'T_OPEN_BRACKET',
                         ']'  => 'T_CLOSE_BRACKET',
                         '('  => 'T_OPEN_PARENTHESIS',
                         ')'  => 'T_CLOSE_PARENTHESIS',
                         '{'  => 'T_OPEN_CURLY',
                         '}'  => 'T_CLOSE_CURLY',
                         '='  => 'T_EQUAL',
                         ','  => 'T_COMMA',
                         '!'  => 'T_BANG',
                         '~'  => 'T_TILDE',
                         '@'  => 'T_AT',
                         '?'  => 'T_QUESTION',
                         ':'  => 'T_COLON',
                         '<'  => 'T_SMALLER',
                         '>'  => 'T_GREATER',
                         '%'  => 'T_PERCENTAGE',
                         '"'  => 'T_QUOTE',
                         '$'  => 'T_DOLLAR',
                         '&'  => 'T_AND',
                         '|'  => 'T_PIPE',
                         '^'  => 'T_CARET',
                         '`'  => 'T_BACKTICK',
                   );
    private $expressions = array();
    private $atoms = array();
    private $atomCount = 0;
    private $argumentsId = array();
    private $path;
    private $sequence = array();
    private $sequenceCurrentRank = 0;
    private $sequenceRank = array();

    private $loaderList = array('CypherG3', 'Neo4jImport');

    private $processing = array();

    private $stats = array('loc'       => 0,
                           'totalLoc'  => 0,
                           'files'     => 0,
                           'tokens'    => 0);

    public function __construct($gremlin, $config, $subtask = Tasks::IS_NOT_SUBTASK) {
        parent::__construct($gremlin, $config, $subtask);

        $this->php = new Phpexec();
        if (!$this->php->isValid()) {
            throw new InvalidPHPBinary($this->php->getVersion());
        }

        $this->precedence = new Precedence($this->config->phpversion);

        $this->processing = array(
                            \Exakat\Tasks\T_OPEN_TAG                 => 'processOpenTag',
                            \Exakat\Tasks\T_OPEN_TAG_WITH_ECHO       => 'processOpenTag',

                            \Exakat\Tasks\T_DOLLAR                   => 'processDollar',
                            \Exakat\Tasks\T_VARIABLE                 => 'processVariable',
                            \Exakat\Tasks\T_LNUMBER                  => 'processInteger',
                            \Exakat\Tasks\T_DNUMBER                  => 'processReal',

                            \Exakat\Tasks\T_OPEN_PARENTHESIS         => 'processParenthesis',

                            \Exakat\Tasks\T_PLUS                     => 'processAddition',
                            \Exakat\Tasks\T_MINUS                    => 'processAddition',
                            \Exakat\Tasks\T_STAR                     => 'processMultiplication',
                            \Exakat\Tasks\T_SLASH                    => 'processMultiplication',
                            \Exakat\Tasks\T_PERCENTAGE               => 'processMultiplication',
                            \Exakat\Tasks\T_POW                      => 'processPower',
                            \Exakat\Tasks\T_INSTANCEOF               => 'processInstanceof',
                            \Exakat\Tasks\T_SL                       => 'processBitshift',
                            \Exakat\Tasks\T_SR                       => 'processBitshift',

                            \Exakat\Tasks\T_DOUBLE_COLON             => 'processDoubleColon',
                            \Exakat\Tasks\T_OBJECT_OPERATOR          => 'processObjectOperator',
                            \Exakat\Tasks\T_NEW                      => 'processNew',

                            \Exakat\Tasks\T_DOT                      => 'processDot',
                            \Exakat\Tasks\T_OPEN_CURLY               => 'processBlock',

                            \Exakat\Tasks\T_IS_SMALLER_OR_EQUAL      => 'processComparison',
                            \Exakat\Tasks\T_IS_GREATER_OR_EQUAL      => 'processComparison',
                            \Exakat\Tasks\T_GREATER                  => 'processComparison',
                            \Exakat\Tasks\T_SMALLER                  => 'processComparison',

                            \Exakat\Tasks\T_IS_EQUAL                 => 'processComparison',
                            \Exakat\Tasks\T_IS_NOT_EQUAL             => 'processComparison',
                            \Exakat\Tasks\T_IS_IDENTICAL             => 'processComparison',
                            \Exakat\Tasks\T_IS_NOT_IDENTICAL         => 'processComparison',
                            \Exakat\Tasks\T_SPACESHIP                => 'processComparison',

                            \Exakat\Tasks\T_OPEN_BRACKET             => 'processArrayBracket',
                            \Exakat\Tasks\T_ARRAY                    => 'processArray',
                            \Exakat\Tasks\T_EMPTY                    => 'processArray',
                            \Exakat\Tasks\T_LIST                     => 'processArray',
                            \Exakat\Tasks\T_EVAL                     => 'processArray',
                            \Exakat\Tasks\T_UNSET                    => 'processArray',
                            \Exakat\Tasks\T_ISSET                    => 'processArray',
                            \Exakat\Tasks\T_EXIT                     => 'processExit',
                            \Exakat\Tasks\T_DOUBLE_ARROW             => 'processKeyvalue',
                            \Exakat\Tasks\T_ECHO                     => 'processEcho',

                            \Exakat\Tasks\T_HALT_COMPILER            => 'processHalt',
                            \Exakat\Tasks\T_PRINT                    => 'processPrint',
                            \Exakat\Tasks\T_INCLUDE                  => 'processPrint',
                            \Exakat\Tasks\T_INCLUDE_ONCE             => 'processPrint',
                            \Exakat\Tasks\T_REQUIRE                  => 'processPrint',
                            \Exakat\Tasks\T_REQUIRE_ONCE             => 'processPrint',
                            \Exakat\Tasks\T_RETURN                   => 'processReturn',
                            \Exakat\Tasks\T_THROW                    => 'processThrow',
                            \Exakat\Tasks\T_YIELD                    => 'processYield',
                            \Exakat\Tasks\T_YIELD_FROM               => 'processYieldfrom',

                            \Exakat\Tasks\T_COLON                    => 'processColon',

                            \Exakat\Tasks\T_EQUAL                    => 'processAssignation',
                            \Exakat\Tasks\T_PLUS_EQUAL               => 'processAssignation',
                            \Exakat\Tasks\T_AND_EQUAL                => 'processAssignation',
                            \Exakat\Tasks\T_CONCAT_EQUAL             => 'processAssignation',
                            \Exakat\Tasks\T_DIV_EQUAL                => 'processAssignation',
                            \Exakat\Tasks\T_MINUS_EQUAL              => 'processAssignation',
                            \Exakat\Tasks\T_MOD_EQUAL                => 'processAssignation',
                            \Exakat\Tasks\T_MUL_EQUAL                => 'processAssignation',
                            \Exakat\Tasks\T_OR_EQUAL                 => 'processAssignation',
                            \Exakat\Tasks\T_POW_EQUAL                => 'processAssignation',
                            \Exakat\Tasks\T_SL_EQUAL                 => 'processAssignation',
                            \Exakat\Tasks\T_SR_EQUAL                 => 'processAssignation',
                            \Exakat\Tasks\T_XOR_EQUAL                => 'processAssignation',

                            \Exakat\Tasks\T_CONTINUE                 => 'processBreak',
                            \Exakat\Tasks\T_BREAK                    => 'processBreak',

                            \Exakat\Tasks\T_LOGICAL_AND              => 'processLogical',
                            \Exakat\Tasks\T_LOGICAL_XOR              => 'processLogical',
                            \Exakat\Tasks\T_LOGICAL_OR               => 'processLogical',
                            \Exakat\Tasks\T_PIPE                     => 'processLogical',
                            \Exakat\Tasks\T_CARET                    => 'processLogical',
                            \Exakat\Tasks\T_AND                      => 'processAnd',

                            \Exakat\Tasks\T_BOOLEAN_AND              => 'processLogical',
                            \Exakat\Tasks\T_BOOLEAN_OR               => 'processLogical',

                            \Exakat\Tasks\T_QUESTION                 => 'processTernary',
                            \Exakat\Tasks\T_NS_SEPARATOR             => 'processNsnameAbsolute',
                            \Exakat\Tasks\T_COALESCE                 => 'processCoalesce',

                            \Exakat\Tasks\T_INLINE_HTML              => 'processInlinehtml',

                            \Exakat\Tasks\T_INC                      => 'processPlusplus',
                            \Exakat\Tasks\T_DEC                      => 'processPlusplus',

                            \Exakat\Tasks\T_WHILE                    => 'processWhile',
                            \Exakat\Tasks\T_DO                       => 'processDo',
                            \Exakat\Tasks\T_IF                       => 'processIfthen',
                            \Exakat\Tasks\T_FOREACH                  => 'processForeach',
                            \Exakat\Tasks\T_FOR                      => 'processFor',
                            \Exakat\Tasks\T_TRY                      => 'processTry',
                            \Exakat\Tasks\T_CONST                    => 'processConst',
                            \Exakat\Tasks\T_SWITCH                   => 'processSwitch',
                            \Exakat\Tasks\T_DEFAULT                  => 'processDefault',
                            \Exakat\Tasks\T_CASE                     => 'processCase',
                            \Exakat\Tasks\T_DECLARE                  => 'processDeclare',

                            \Exakat\Tasks\T_AT                       => 'processNoscream',
                            \Exakat\Tasks\T_CLONE                    => 'processClone',
                            \Exakat\Tasks\T_GOTO                     => 'processGoto',

                            \Exakat\Tasks\T_STRING                   => 'processString',
                            \Exakat\Tasks\T_CONSTANT_ENCAPSED_STRING => 'processLiteral',
                            \Exakat\Tasks\T_ENCAPSED_AND_WHITESPACE  => 'processLiteral',
                            \Exakat\Tasks\T_NUM_STRING               => 'processLiteral',
                            \Exakat\Tasks\T_STRING_VARNAME           => 'processVariable',

                            \Exakat\Tasks\T_ARRAY_CAST               => 'processCast',
                            \Exakat\Tasks\T_BOOL_CAST                => 'processCast',
                            \Exakat\Tasks\T_DOUBLE_CAST              => 'processCast',
                            \Exakat\Tasks\T_INT_CAST                 => 'processCast',
                            \Exakat\Tasks\T_OBJECT_CAST              => 'processCast',
                            \Exakat\Tasks\T_STRING_CAST              => 'processCast',
                            \Exakat\Tasks\T_UNSET_CAST               => 'processCast',

                            \Exakat\Tasks\T_FILE                     => 'processMagicConstant',
                            \Exakat\Tasks\T_CLASS_C                  => 'processMagicConstant',
                            \Exakat\Tasks\T_FUNC_C                   => 'processMagicConstant',
                            \Exakat\Tasks\T_LINE                     => 'processMagicConstant',
                            \Exakat\Tasks\T_DIR                      => 'processMagicConstant',
                            \Exakat\Tasks\T_METHOD_C                 => 'processMagicConstant',
                            \Exakat\Tasks\T_NS_C                     => 'processMagicConstant',
                            \Exakat\Tasks\T_TRAIT_C                  => 'processMagicConstant',

                            \Exakat\Tasks\T_BANG                     => 'processNot',
                            \Exakat\Tasks\T_TILDE                    => 'processNot',
                            \Exakat\Tasks\T_ELLIPSIS                 => 'processEllipsis',

                            \Exakat\Tasks\T_SEMICOLON                => 'processSemicolon',
                            \Exakat\Tasks\T_CLOSE_TAG                => 'processClosingTag',

                            \Exakat\Tasks\T_FUNCTION                 => 'processFunction',
                            \Exakat\Tasks\T_CLASS                    => 'processClass',
                            \Exakat\Tasks\T_TRAIT                    => 'processTrait',
                            \Exakat\Tasks\T_INTERFACE                => 'processInterface',
                            \Exakat\Tasks\T_NAMESPACE                => 'processNamespace',
                            \Exakat\Tasks\T_USE                      => 'processUse',
                            \Exakat\Tasks\T_AS                       => 'processAs',
                            \Exakat\Tasks\T_INSTEADOF                => 'processInsteadof',

                            \Exakat\Tasks\T_ABSTRACT                 => 'processAbstract',
                            \Exakat\Tasks\T_FINAL                    => 'processFinal',
                            \Exakat\Tasks\T_PRIVATE                  => 'processPrivate',
                            \Exakat\Tasks\T_PROTECTED                => 'processProtected',
                            \Exakat\Tasks\T_PUBLIC                   => 'processPublic',
                            \Exakat\Tasks\T_VAR                      => 'processVar',

                            \Exakat\Tasks\T_QUOTE                    => 'processQuote',
                            \Exakat\Tasks\T_START_HEREDOC            => 'processQuote',
                            \Exakat\Tasks\T_BACKTICK                 => 'processQuote',
                            \Exakat\Tasks\T_DOLLAR_OPEN_CURLY_BRACES => 'processDollarCurly',
                            \Exakat\Tasks\T_STATIC                   => 'processStatic',
                            \Exakat\Tasks\T_GLOBAL                   => 'processGlobalVariable',
                            );

        self::$PROP_OPTIONS = array(
                          'alternative' => self::$PROP_ALTERNATIVE,
                          'reference'   => self::$PROP_REFERENCE,
                          'heredoc'     => self::$PROP_HEREDOC,
                          'delimiter'   => self::$PROP_DELIMITER,
                          'noDelimiter' => self::$PROP_NODELIMITER,
                          'variadic'    => self::$PROP_VARIADIC,
                          'count'       => self::$PROP_COUNT,
                          'fullnspath'  => self::$PROP_FNSNAME,
                          'absolute'    => self::$PROP_ABSOLUTE,
                          'alias'       => self::$PROP_ALIAS,
                          'origin'      => self::$PROP_ORIGIN,
                          'encoding'    => self::$PROP_ENCODING,
                          'intval'      => self::$PROP_INTVAL,
                          'strval'      => self::$PROP_STRVAL,
                          'enclosing'   => self::$PROP_ENCLOSING,
                          'args_max'    => self::$PROP_ARGS_MAX,
                          'args_min'    => self::$PROP_ARGS_MIN,
                          'bracket'     => self::$PROP_BRACKET,
                          'close_tag'   => self::$PROP_CLOSETAG,
                          'aliased'     => self::$PROP_ALIASED,
                          'boolean'     => self::$PROP_BOOLEAN,
                          );
    }

    public function run() {
        $this->logTime('Start');
        if (!file_exists($this->config->projects_root.'/projects/'.$this->config->project.'/config.ini')) {
            throw new NoSuchProject($this->config->project);
        }

        $files = glob($this->exakatDir.'/*.csv');

        foreach($files as $file) {
            unlink($file);
        }

        $this->checkTokenLimit();

        $this->id0 = $this->addAtom('Project');
        $this->setAtom($this->id0, array('code'     => 'Whole',
                                         'fullcode' => $this->config->project,
                                         'line'     => -1,
                                         'token'    => 'T_WHOLE'));

        if (static::$client === null) {
            $client = $this->config->loader;

            if (!in_array($client, $this->loaderList)) {
                throw new NoSuchLoader($client, $this->loaderList);
            }

            display("Loading with $client\n");

            $client = '\\Exakat\\Loader\\'.$client;
            static::$client = new $client();
        }

        $this->datastore->cleanTable('tokenCounts');
        $this->logTime('Init');

        if ($filename = $this->config->filename) {
            if (!is_file($filename)) {
                throw new MustBeAFile($filename);
            }
            if ($this->processFile($filename)) {
                $this->saveFiles();
            }
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
        $this->datastore->addRow('hash', $this->stats);

        static::$client->finalize();
        $this->datastore->addRow('hash', array('status' => 'Load'));

        $this->logTime('LoadFinal');
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
        $path = $this->config->projects_root.'/projects/'.$project.'/code';
        foreach($files as $file) {
            try {
                if ($r = $this->processFile($path.$file)) {
                    $nbTokens += $r;
                    $this->saveFiles();
                }
            } catch (NoFileToProcess $e) {
                // ignoring empty files
            }
        }
        $this->saveDefinitions();

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
        Files::findFiles($dir, $files, $ignoredFiles);

        $this->reset();

        $nbTokens = 0;
        foreach($files as $file) {
            try {
                if ($r = $this->processFile($dir.$file)) {
                    $nbTokens += $r;
                    $this->saveFiles();
                }
            } catch (NoFileToProcess $e) {
                // Ignoring
            }
        }
        $this->saveDefinitions();

        return array('files'  => count($files),
                     'tokens' => $nbTokens);
    }

    private function reset() {
        $this->atoms = array($this->id0 => $this->atoms[$this->id0]);
        $this->links = array();
        foreach($this->calls as $type => $names) {
            foreach($names as $name => $calls) {
                if (!empty($calls['definitions'])) {
                    $this->calls[$type][$name]['calls'] = array();
                }
            }
        }

        $this->uses  = array('function' => array(),
                             'const'    => array(),
                             'class'    => array());
        $this->usesId = array('function' => array(),
                              'const'    => array(),
                              'class'    => array());
        $this->contexts = $contexts = array(self::CONTEXT_CLASS      => false,
                                            self::CONTEXT_INTERFACE  => false,
                                            self::CONTEXT_TRAIT      => false,
                                            self::CONTEXT_FUNCTION   => false,
                                            self::CONTEXT_NEW        => false,
                                            self::CONTEXT_NOSEQUENCE => 0,
                         );
        $this->expressions = array();
    }

    private function processFile($filename) {
        $this->log->log("$filename");
        $this->filename = $filename;

        ++$this->stats['files'];

        $this->line = 0;
        $log = array();

        if (is_link($filename)) { return true; }
        if (!file_exists($filename)) {
            throw new NoSuchFile( $filename );
        }

        $file = realpath($filename);
        if (strpos($file, '/code/') !== false) {
            $file = substr($file, strpos($file, '/code/') + 5);
        } else {
            $file = $filename;
        }
        if (filesize($filename) === 0) {
            return false;
        }

        if (!$this->php->compile($filename)) {
            throw new NoFileToProcess($filename, 'won\'t compile');
        }

        $tokens = $this->php->getTokenFromFile($filename);
        $log['token_initial'] = count($tokens);

        if (count($tokens) === 1) {
            throw new NoFileToProcess($filename, 'empty');
        }

        $line = 0;
        $comments = 0;
        $this->tokens = array();
        foreach($tokens as $t) {
            if (is_array($t)) {
                if ($t[0] === \Exakat\Tasks\T_WHITESPACE) {
                    $line += substr_count($t[1], "\n");
                } elseif ($t[0] === \Exakat\Tasks\T_COMMENT ||
                          $t[0] === \Exakat\Tasks\T_DOC_COMMENT) {
                    $line += substr_count($t[1], "\n");
                    $comments += substr_count($t[1], "\n");
                    continue;
                } else {
                    $line = $t[2];
                    $this->tokens[] = $t;
                }
            } else {
                $this->tokens[] = array(0 => self::$TOKENS[$t],
                                        1 => $t,
                                        2 => $line);
            }
        }
        $this->stats['loc'] -= $comments;

        // Final token
        $this->tokens[] = array(0 => \Exakat\Tasks\T_END,
                                1 => '/* END */',
                                2 => $line);
        $this->stats['tokens'] += count($tokens);
        unset($tokens);

        $this->uses   = array('function' => array(),
                              'const'    => array(),
                              'class'    => array());
        $this->usesId   = array('function' => array(),
                                'const'    => array(),
                                'class'    => array());

        $id1 = $this->addAtom('File');
        $this->setAtom($id1, array('code'     => $filename,
                                   'fullcode' => $file,
                                   'line'     => -1,
                                   'token'    => 'T_FILENAME'));
        $this->addLink($this->id0, $id1, 'PROJECT');

        try {
            $n = count($this->tokens) - 2;
            $this->id = 0; // set to 0 so as to calculate line in the next call.
            $this->startSequence(); // At least, one sequence available
            $this->id = -1;
            do {
                $theId = $this->processNext();

                if ($theId > 0) {
                    $this->addToSequence($theId);
                }
            } while ($this->id < $n);

            $sequenceId = $this->sequence;
            $this->endSequence();

            $this->addLink($id1, $sequenceId, 'FILE');
            $this->setAtom($sequenceId, array('root' => true));
            $this->checkTokens($filename);
        } catch (LoadError $e) {
            $this->log->log("Can't process file '$this->filename' during load ('{$this->tokens[$this->id][0]}'). Ignoring\n");
            $this->reset();
            throw new NoFileToProcess($filename, 'empty');
        } finally {
            $this->stats['totalLoc'] += $line;
            $this->stats['loc'] += $line;
        }

        return true;
    }

    private function processNext() {
        ++$this->id;

        if ($this->tokens[$this->id][0] === \Exakat\Tasks\T_END ||
            !isset($this->processing[ $this->tokens[$this->id][0] ])) {
            display("Can't process file '$this->filename' during load ('{$this->tokens[$this->id][0]}'). Ignoring\n");
            $this->log->log("Can't process file '$this->filename' during load ('{$this->tokens[$this->id][0]}'). Ignoring\n");

            throw new LoadError('Processing error');
        }
        $method = $this->processing[ $this->tokens[$this->id][0] ];

        return $this->$method();
    }

    private function processColon() {
        return null;// Just ignore
    }

    //////////////////////////////////////////////////////
    /// processing complex tokens
    //////////////////////////////////////////////////////
    private function processQuote() {
        $current = $this->id;
        $fullcode = array();
        $rank = -1;

        if ($this->tokens[$current][0] === \Exakat\Tasks\T_QUOTE) {
            $stringId = $this->addAtom('String');
            $finalToken = \Exakat\Tasks\T_QUOTE;
            $openQuote = '"';
            $closeQuote = '"';
            $type = \Exakat\Tasks\T_QUOTE;
        } elseif ($this->tokens[$current][0] === \Exakat\Tasks\T_BACKTICK) {
            $stringId = $this->addAtom('Shell');
            $finalToken = \Exakat\Tasks\T_BACKTICK;
            $openQuote = '`';
            $closeQuote = '`';
            $type = \Exakat\Tasks\T_BACKTICK;
        } elseif ($this->tokens[$current][0] === \Exakat\Tasks\T_START_HEREDOC) {
            $stringId = $this->addAtom('Heredoc');
            $finalToken = \Exakat\Tasks\T_END_HEREDOC;
            $openQuote = $this->tokens[$this->id][1];
            if ($this->tokens[$this->id][1][3] === "'") {
                $closeQuote = substr($this->tokens[$this->id][1], 4, -2);
            } else {
                $closeQuote = substr($this->tokens[$this->id][1], 3);
            }
            $type = \Exakat\Tasks\T_START_HEREDOC;
        }

        while ($this->tokens[$this->id + 1][0] !== $finalToken) {
            $currentVariableId = $this->id + 1;
            if (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CURLY_OPEN, \Exakat\Tasks\T_DOLLAR_OPEN_CURLY_BRACES))) {
                $openId = $this->id + 1;
                ++$this->id; // Skip {
                while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_CURLY))) {
                    $this->processNext();
                };
                ++$this->id; // Skip }

                $partId = $this->popExpression();
                $this->setAtom($partId, array('enclosing' => true,
                                              'fullcode'  => $this->tokens[$openId][1].$this->atoms[$partId]['fullcode'].'}',
                                              'token'     => $this->getToken($this->tokens[$currentVariableId][0])));
                $this->pushExpression($partId);
            } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_VARIABLE) {
                $this->processNext();

                if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OBJECT_OPERATOR) {
                    ++$this->id;

                    $objectId = $this->popExpression();

                    $propertyNameId = $this->processNextAsIdentifier();

                    $propertyId = $this->addAtom('Property');
                    $this->setAtom($propertyId, array('code'      => $this->tokens[$current][1],
                                                      'fullcode'  => $this->atoms[$objectId]['fullcode'].'->'.$this->atoms[$propertyNameId]['fullcode'],
                                                      'line'      => $this->tokens[$current][2],
                                                      'variadic'  => false,
                                                      'token'     => $this->getToken($this->tokens[$current][0]),
                                                      'enclosing' => false ));

                    $this->addLink($propertyId, $objectId, 'OBJECT');
                    $this->addLink($propertyId, $propertyNameId, 'PROPERTY');

                    $this->pushExpression($propertyId);
                }
            } else {
                $this->processNext();
            }

            $partId = $this->popExpression();
            if ($this->atoms[$partId]['atom'] === 'String') {
                $this->setAtom($partId, array('noDelimiter' => $this->atoms[$partId]['code'],
                                              'delimiter'   => ''));
            } else {
                $this->setAtom($partId, array('noDelimiter' => '',
                                              'delimiter'   => ''));
            }
            $this->setAtom($partId, array('rank' => ++$rank));
            $fullcode[] = $this->atoms[$partId]['fullcode'];
            $this->addLink($stringId, $partId, 'CONCAT');
        }

        ++$this->id;
        $x = array('code'     => $this->tokens[$current][1],
                   'fullcode' => $openQuote.implode('', $fullcode).$closeQuote,
                   'line'     => $this->tokens[$current][2],
                   'token'    => $this->getToken($this->tokens[$current][0]),
                   'count'    => $rank + 1,
                   'boolean'  => (int) (boolean) ($rank + 1));

        if ($type === \Exakat\Tasks\T_START_HEREDOC) {
            $x['delimiter'] = $closeQuote;
            $x['heredoc'] = $openQuote[3] !== "'";
        }

        $this->setAtom($stringId, $x);
        $this->pushExpression($stringId);

        return $stringId;
    }

    private function processDollarCurly() {
        $current = $this->id;
        $variableId = $this->addAtom('Variable');

        ++$this->id; // Skip ${
        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_CURLY))) {
            $this->processNext();
        } ;
        ++$this->id; // Skip }

        $nameId = $this->popExpression();
        $this->addLink($variableId, $nameId, 'NAME');

        $this->setAtom($nameId, array('code'      => $this->tokens[$current][1],
                                      'fullcode'  => '${'.$this->atoms[$nameId]['fullcode'].'}',
                                      'line'      => $this->tokens[$current][2],
                                      'variadic'  => false,
                                      'token'     => $this->getToken($this->tokens[$current][0]),
                                      'enclosing' => true));

        return $variableId;
    }

    private function processTry() {
        $current = $this->id;
        $tryId = $this->addAtom('Try');

        $blockId = $this->processFollowingBlock(array(\Exakat\Tasks\T_CLOSE_CURLY));
        $this->popExpression();
        $this->addLink($tryId, $blockId, 'BLOCK');

        $rank = 0;
        $fullcodeCatch = array();
        while ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CATCH) {
            $catch = $this->id + 1;
            ++$this->id; // Skip catch
            ++$this->id; // Skip (

            $catchId = $this->addAtom('Catch');
            $catchFullcode = array();
            $rankCatch = -1;
            while ($this->tokens[$this->id + 1][0] !== \Exakat\Tasks\T_VARIABLE) {
                $classId = $this->processOneNsname();
                $this->addLink($catchId, $classId, 'CLASS');
                $this->setAtom($catchId, array('rank'       => ++$rankCatch));

                list($fullnspath, $aliased) = $this->getFullnspath($classId);
                $this->setAtom($classId, array('fullnspath' => $fullnspath,
                                               'aliased'    => $aliased));
                $this->addCall('class', $fullnspath, $classId);
                $catchFullcode[] = $this->atoms[$classId]['fullcode'];

                if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_PIPE) {
                    ++$this->id; // Skip |
                }
            }
            $this->setAtom($catchId, array('count' => $rankCatch + 1));
            $catchFullcode = implode(' | ', $catchFullcode);

            // Process variable
            $this->processNext();

            $variableId = $this->popExpression();
            $this->addLink($catchId, $variableId, 'VARIABLE');

            // Skip )
            ++$this->id;

            // Skip }
            $blockCatchId = $this->processFollowingBlock(array(\Exakat\Tasks\T_CLOSE_CURLY));
            $this->popExpression();
            $this->addLink($catchId, $blockCatchId, 'BLOCK');

            $this->setAtom($catchId, array('code'     => $this->tokens[$catch][1],
                                           'fullcode' => $this->tokens[$catch][1].' ('.$catchFullcode.' '.$this->atoms[$variableId]['fullcode'].')'.static::FULLCODE_BLOCK,
                                           'line'     => $this->tokens[$catch][2],
                                           'token'    => $this->getToken($this->tokens[$current][0]),
                                           'rank'     => ++$rank));

            $this->addLink($tryId, $catchId, 'CATCH');
            $fullcodeCatch[] = $this->atoms[$catchId]['fullcode'];
        }

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_FINALLY) {
            $finally = $this->id + 1;
            $finallyId = $this->addAtom('Finally');

            ++$this->id;
            $finallyBlockId = $this->processFollowingBlock(false);
            $this->popExpression();
            $this->addLink($tryId, $finallyId, 'FINALLY');
            $this->addLink($finallyId, $finallyBlockId, 'BLOCK');

            $this->setAtom($finallyId, array('code'     => $this->tokens[$finally][1],
                                             'fullcode' => $this->tokens[$finally][1].static::FULLCODE_BLOCK,
                                             'line'     => $this->tokens[$finally][2],
                                             'token'    => $this->getToken($this->tokens[$current][0])));
        }

        $this->setAtom($tryId, array('code'     => $this->tokens[$current][1],
                                     'fullcode' => $this->tokens[$current][1].static::FULLCODE_BLOCK.implode('', $fullcodeCatch).( isset($finallyId) ? $this->atoms[$finallyId]['fullcode'] : ''),
                                     'line'     => $this->tokens[$current][2],
                                     'token'    => $this->getToken($this->tokens[$current][0]),
                                     'count'    => $rank));

        $this->pushExpression($tryId);
        $this->processSemicolon();

        return $tryId;
    }

    private function processFunction() {
        $current = $this->id;
        $functionId = $this->addAtom('Function');
        $this->toggleContext(self::CONTEXT_FUNCTION);

        $fullcode = array();
        foreach($this->optionsTokens as $name => $optionId) {
            $this->addLink($functionId, $optionId, strtoupper($name));
            $fullcode[] = $this->atoms[$optionId]['fullcode'];
        }
        $this->optionsTokens = array();

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_AND) {
            ++$this->id;
            $this->setAtom($functionId, array('reference' => true));
        } else {
            $this->setAtom($functionId, array('reference' => false));
        }

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_PARENTHESIS) {
            $isClosure = true;
            $nameId = $this->addAtomVoid();
        } else {
            $isClosure = false;
            $nameId = $this->processNextAsIdentifier();
        }
        $this->addLink($functionId, $nameId, 'NAME');

        // Process arguments
        ++$this->id; // Skip arguments
        $argumentsId = $this->processArguments(array(\Exakat\Tasks\T_CLOSE_PARENTHESIS), true);
        $this->addLink($functionId, $argumentsId, 'ARGUMENTS');

        // Process use
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_USE) {
            ++$this->id; // Skip use
            ++$this->id; // Skip (
            $useId = $this->processArguments();
            $this->addLink($functionId, $useId, 'USE');
        }

        // Process return type
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COLON) {
            ++$this->id;
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_QUESTION) {
                $nullableId = $this->processNextAsIdentifier();
                $this->addLink($functionId, $nullableId, 'NULLABLE');
            }

            $returnTypeId = $this->processOneNsname();
            $this->addLink($functionId, $returnTypeId, 'RETURNTYPE');
        }

        // Process block
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_SEMICOLON) {
            $voidId = $this->addAtomVoid();
            $this->addLink($functionId, $voidId, 'BLOCK');
            ++$this->id; // skip the next ;
        } else {
            $blockId = $this->processFollowingBlock(array(\Exakat\Tasks\T_CLOSE_CURLY));
            $this->popExpression();
            $this->addLink($functionId, $blockId, 'BLOCK');
        }

        if (!empty($fullcode)) {
            $fullcode[] = '';
        }

        if ( $isClosure === false &&
            !$this->isContext(self::CONTEXT_CLASS) &&
            !$this->isContext(self::CONTEXT_TRAIT) &&
            !$this->isContext(self::CONTEXT_INTERFACE)) {
            list($fullnspath, $aliased) = $this->getFullnspath($nameId);
            $this->addDefinition('function', $fullnspath, $functionId);
        } else {
            $fullnspath = '';
            $aliased    = self::NOT_ALIASED;
        }
        $this->setAtom($functionId, array('code'       => $this->atoms[$nameId]['fullcode'],
                                          'fullcode'   => implode(' ', $fullcode).$this->tokens[$current][1].' '.($this->atoms[$functionId]['reference'] ? '&' : '').($this->atoms[$nameId]['atom'] === 'Void' ? '' : $this->atoms[$nameId]['fullcode']).'('.$this->atoms[$argumentsId]['fullcode'].')'.(isset($useId) ? ' use ('.$this->atoms[$useId]['fullcode'].')' : '').// No space before use
                                                          (isset($returnTypeId) ? ' : '.(isset($nullableId) ? '?' : '').$this->atoms[$returnTypeId]['fullcode'] : '').(isset($blockId) ? self::FULLCODE_BLOCK : ' ;'),
                                          'line'       => $this->tokens[$current][2],
                                          'token'      => $this->getToken($this->tokens[$current][0]),
                                          'fullnspath' => $fullnspath,
                                          'aliased'    => $aliased ));

        $this->pushExpression($functionId);

        if ($this->atoms[$nameId]['atom'] !== 'Void') {
            $this->processSemicolon();
        }

        if (!$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        $this->toggleContext(self::CONTEXT_FUNCTION);
        return $functionId;
    }

    private function processOneNsname() {
        $rank = -1;
        $fullcode = array();

        if ($this->tokens[$this->id + 1][0] !== \Exakat\Tasks\T_NS_SEPARATOR) {
            $subnameId = $this->processNextAsIdentifier();
            $this->pushExpression($subnameId);

            $hasPrevious = true;
        } else {
            $hasPrevious = false;
        }
        $current = $this->id;

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_NS_SEPARATOR) {
            $extendsId = $this->addAtom('Nsname');

            // Previous one
            if ($hasPrevious === true) {
                $subnameId = $this->popExpression();
                $this->setAtom($subnameId, array('rank' => ++$rank));
                $fullcode[] = $this->atoms[$subnameId]['code'];
                $this->addLink($extendsId, $subnameId, 'SUBNAME');
            } else {
                $fullcode[] = '';
            }

            // Next one (at least one)
            while ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_NS_SEPARATOR &&
                   $this->tokens[$this->id + 2][0] !== \Exakat\Tasks\T_OPEN_CURLY ) {
                ++$this->id; // Skip \

                $subnameId = $this->processNextAsIdentifier(false);

                $this->setAtom($subnameId, array('rank' => ++$rank));
                $fullcode[] = $this->atoms[$subnameId]['code'];
                $this->addLink($extendsId, $subnameId, 'SUBNAME');
            }

            $this->setAtom($extendsId, array('code'     => '\\',
                                             'fullcode' => implode('\\', $fullcode),
                                             'line'     => $this->tokens[$current][2],
                                             'token'    => $this->getToken($this->tokens[$current + 1][0]),
                                             'variadic' => false,
                                             'absolute' => !$hasPrevious));
            list($fullnspath, $aliased) = $this->getFullnspath($extendsId);
            $this->setAtom($extendsId, array('fullnspath' => $fullnspath,
                                             'aliased'    => $aliased));
        } else {
            $extendsId = $this->popExpression();
        }

        return $extendsId;
    }

    private function processTrait() {
        $current = $this->id;
        $traitId = $this->addAtom('Trait');
        $this->currentClassTrait[] = $traitId;
        $this->toggleContext(self::CONTEXT_TRAIT);

        $nameId = $this->processNextAsIdentifier();
        $this->addLink($traitId, $nameId, 'NAME');

        // Process block
        ++$this->id;
        $blockId = $this->processBlock(false);
        $this->popExpression();
        $this->addLink($traitId, $blockId, 'BLOCK');

        list($fullnspath, $aliased) = $this->getFullnspath($nameId);
        $this->setAtom($traitId, array('code'       => $this->tokens[$current][1],
                                       'fullcode'   => $this->tokens[$current][1].' '.$this->atoms[$nameId]['fullcode'].static::FULLCODE_BLOCK,
                                       'line'       => $this->tokens[$current][2],
                                       'token'      => $this->getToken($this->tokens[$current][0]),
                                       'fullnspath' => $this->atoms[$nameId]['fullnspath'],
                                       'aliased'    => $this->atoms[$nameId]['aliased']));

        $this->addDefinition('class', $fullnspath, $traitId);

        $this->pushExpression($traitId);
        $this->processSemicolon();

        $this->toggleContext(self::CONTEXT_TRAIT);

        array_pop($this->currentClassTrait);

        return $traitId;
    }

    private function processInterface() {
        $current = $this->id;
        $interfaceId = $this->addAtom('Interface');
        $this->toggleContext(self::CONTEXT_INTERFACE);

        $nameId = $this->processNextAsIdentifier();
        $this->addLink($interfaceId, $nameId, 'NAME');

        // Process extends
        $rank = 0;
        $fullcode= array();
        $extends = $this->id + 1;
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_EXTENDS) {
            do {
                ++$this->id; // Skip extends or ,
                $extendsId = $this->processOneNsname();
                $this->setAtom($extendsId, array('rank' => $rank));
                $this->addLink($interfaceId, $extendsId, 'EXTENDS');
                $fullcode[] = $this->atoms[$extendsId]['fullcode'];

                list($fullnspath, $aliased) = $this->getFullnspath($extendsId);
                $this->addCall('class', $fullnspath, $extendsId);
            } while ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COMMA);
        }

        // Process block
        ++$this->id;
        $blockId = $this->processBlock(false);
        $this->popExpression();
        $this->addLink($interfaceId, $blockId, 'BLOCK');

        list($fullnspath, $aliased) = $this->getFullnspath($nameId);
        $this->setAtom($interfaceId, array('code'       => $this->tokens[$current][1],
                                           'fullcode'   => $this->tokens[$current][1].' '.$this->atoms[$nameId]['fullcode'].(isset($extendsId) ? ' '.$this->tokens[$extends][1].' '.implode(', ', $fullcode) : '').static::FULLCODE_BLOCK,
                                           'line'       => $this->tokens[$current][2],
                                           'token'      => $this->getToken($this->tokens[$current][0]),
                                           'fullnspath' => $this->atoms[$nameId]['fullnspath'],
                                           'aliased'    => $this->atoms[$nameId]['aliased']));
        $this->addDefinition('class', $fullnspath, $interfaceId);

        $this->pushExpression($interfaceId);
        $this->processSemicolon();

        $this->toggleContext(self::CONTEXT_INTERFACE);

        return $interfaceId;
    }

    private function processClass() {
        $current = $this->id;
        $classId = $this->addAtom('Class');
        $this->currentClassTrait[] = $classId;
        $this->toggleContext(self::CONTEXT_CLASS);

        // Should work on Abstract and Final only
        $fullcode= array();
        foreach($this->optionsTokens as $name => $optionId) {
            $this->addLink($classId, $optionId, strtoupper($name));
            $fullcode[] = $this->atoms[$optionId]['fullcode'];
        }
        $this->optionsTokens = array();

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_STRING) {
            $nameId = $this->processNextAsIdentifier();
        } else {
            $nameId = $this->addAtomVoid();

            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_PARENTHESIS) {
                // Process arguments
                ++$this->id; // Skip arguments
                $argumentsId = $this->processArguments();
                $this->addLink($classId, $argumentsId, 'ARGUMENTS');
            }
        }
        $this->addLink($classId, $nameId, 'NAME');

        // Process extends
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_EXTENDS) {
            $extends = $this->tokens[$this->id + 1][1];
            ++$this->id; // Skip extends

            $extendsId = $this->processOneNsname();

            $this->addLink($classId, $extendsId, 'EXTENDS');
            list($fullnspath, $aliased) = $this->getFullnspath($extendsId);
            if ($aliased === self::ALIASED) {
                $this->addLink($this->usesId['class'][strtolower($this->atoms[$extendsId]['code'])], $extendsId, 'DEFINITION');
            }
            $this->addCall('class', $fullnspath, $extendsId);
        }

        // Process implements
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_IMPLEMENTS) {
            $implements = $this->tokens[$this->id + 1][1];
            $fullcodeImplements = array();
            do {
                ++$this->id; // Skip implements
                $implementsId = $this->processOneNsname();
                $this->addLink($classId, $implementsId, 'IMPLEMENTS');
                $fullcodeImplements[] = $this->atoms[$implementsId]['fullcode'];

                list($fullnspath, $aliased) = $this->getFullnspath($implementsId);
                if ($aliased === self::ALIASED) {
                    $this->addLink($this->usesId['class'][strtolower($this->atoms[$implementsId]['code'])], $implementsId, 'DEFINITION');
                }
                $this->addCall('class', $fullnspath, $implementsId);
            } while ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COMMA);
        }

        // Process block
        ++$this->id;
        $blockId = $this->processBlock(false);
        $this->popExpression();
        $this->addLink($classId, $blockId, 'BLOCK');

        $this->setAtom($classId, array('code'       => $this->tokens[$current][1],
                                       'fullcode'   => (!empty($fullcode) ? implode(' ', $fullcode).' ' : '').$this->tokens[$current][1].($this->atoms[$nameId]['atom'] === 'Void' ? '' : ' '.$this->atoms[$nameId]['fullcode']).(isset($argumentsId) ? ' ('.$this->atoms[$argumentsId]['fullcode'].')' : '').(isset($extendsId) ? ' '.$extends.' '.$this->atoms[$extendsId]['fullcode'] : '').(isset($implementsId) ? ' '.$implements.' '.implode(', ', $fullcodeImplements) : '').static::FULLCODE_BLOCK,
                                       'line'       => $this->tokens[$current][2],
                                       'token'      => $this->getToken($this->tokens[$current][0]),
                                       'fullnspath' => $this->atoms[$nameId]['fullnspath'],
                                       'aliased'    => self::NOT_ALIASED));

        $this->pushExpression($classId);

        $this->addDefinition('class', $this->atoms[$nameId]['fullnspath'], $classId);

        // Case of anonymous classes
        if ($this->tokens[$current - 1][0] !== \Exakat\Tasks\T_NEW) {
            $this->processSemicolon();
        }

        $this->toggleContext(self::CONTEXT_CLASS);
        array_pop($this->currentClassTrait);

        return $classId;
    }

    private function processOpenTag() {
        $id = $this->addAtom('Php');
        $current = $this->id;

        $this->startSequence();

        // Special case for pretty much empty script (<?php .... END)
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_END) {
            $voidId = $this->addAtomVoid();
            $this->addToSequence($voidId);

            $this->addLink($id, $this->sequence, 'CODE');
            $this->endSequence();
            $closing = '';

            $this->setAtom($id, array('code'      => $this->tokens[$current][1],
                                      'fullcode'  => '<?php '.self::FULLCODE_SEQUENCE.' '.$closing,
                                      'line'      => $this->tokens[$current][2],
                                      'close_tag' => false,
                                      'token'     => $this->getToken($this->tokens[$current][0])));

            return $id;
        }

        $n = count($this->tokens) - 2;
        if ($this->tokens[$n][0] === \Exakat\Tasks\T_INLINE_HTML) {
            --$n;
        }

        while ($this->id < $n) {
            if ($this->tokens[$this->id][0] === \Exakat\Tasks\T_OPEN_TAG_WITH_ECHO) {
                --$this->id;
                $this->processOpenWithEcho();
                /// processing the first expression as an echo
                $this->processSemicolon();
                if ($this->tokens[$this->id + 1][0] == \Exakat\Tasks\T_END) {
                    --$this->id;
                }
            } elseif ($this->tokens[$this->id][0] === \Exakat\Tasks\T_CLOSE_TAG) {
                --$this->id;
            }

            $this->processNext();
        };

        if ($this->tokens[$this->id][0] === \Exakat\Tasks\T_INLINE_HTML) {
            --$this->id;
        }

        if ($this->tokens[$this->id - 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $close_tag = true;
            $closing = '?>';
        } elseif ($this->tokens[$this->id][0] === \Exakat\Tasks\T_HALT_COMPILER) {
            $close_tag = false;
            ++$this->id; // Go to HaltCompiler
            $this->processHalt();
            $closing = '';
        } else {
            $close_tag = false;
            $closing = '';
        }

        if ($this->tokens[$this->id - 1][0] === \Exakat\Tasks\T_OPEN_TAG) {
            $voidId = $this->addAtomVoid();
            $this->addToSequence($voidId);
        }
        $this->addLink($id, $this->sequence, 'CODE');
        $this->endSequence();

        $this->setAtom($id, array('code'        => $this->tokens[$current][1],
                                  'fullcode'    => '<?php '.self::FULLCODE_SEQUENCE.' '.$closing,
                                  'line'        => $this->tokens[$current][2],
                                  'token'       => $this->getToken($this->tokens[$current][0]),
                                  'close_tag'   => $close_tag));
        return $id;
    }

    private function processSemicolon() {
        $this->addToSequence($this->popExpression());
    }

    private function processClosingTag() {
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_INLINE_HTML &&
            in_array($this->tokens[$this->id + 2][0], array(\Exakat\Tasks\T_OPEN_TAG, \Exakat\Tasks\T_OPEN_TAG_WITH_ECHO))) {

            ++$this->id;
            $this->processInlinehtml();

            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_TAG_WITH_ECHO) {
                $this->processOpenWithEcho();
                if ($this->tokens[$this->id + 1][0] !== \Exakat\Tasks\T_SEMICOLON) {
                    $this->processSemicolon();
                }
            } else {
                ++$this->id; // set to opening tag
            }
        } elseif (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_OPEN_TAG, \Exakat\Tasks\T_OPEN_TAG_WITH_ECHO))) {

            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_TAG_WITH_ECHO) {
                $this->processOpenWithEcho();
                if ($this->tokens[$this->id + 1][0] !== \Exakat\Tasks\T_SEMICOLON) {
                    $this->processSemicolon();
                }
            } else {
                ++$this->id; // set to opening tag
            }
        } else {
            if ($this->tokens[$this->id - 1][0] === \Exakat\Tasks\T_OPEN_TAG) {
                $voidId = $this->addAtomVoid();
                $this->addToSequence($voidId);
            }
            ++$this->id;
        }
    }

    private function processOpenWithEcho() {
        // Processing ECHO
        $echoId = $this->processNextAsIdentifier();
        $current = $this->id;

        $argumentsId = $this->processArguments(array(\Exakat\Tasks\T_SEMICOLON, \Exakat\Tasks\T_CLOSE_TAG, \Exakat\Tasks\T_END));

        //processArguments goes too far, up to ;
        if ($this->tokens[$this->id][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            --$this->id;
        }

        $functioncallId = $this->addAtom('Functioncall');
        $this->setAtom($functioncallId, array('code'       => $this->atoms[$echoId]['code'],
                                              'fullcode'   => '<?= '.$this->atoms[$argumentsId]['fullcode'],
                                              'line'       => $this->tokens[$current === self::NO_VALUE ? 0 : $current][2],
                                              'token'      => 'T_OPEN_TAG_WITH_ECHO',
                                              'variadic'   => false,
                                              'fullnspath' => '\\echo' ));
        $this->addLink($functioncallId, $argumentsId, 'ARGUMENTS');
        $this->addLink($functioncallId, $echoId, 'NAME');

        $this->pushExpression($functioncallId);
    }

    private function processNsnameAbsolute() {
        $id = $this->processNsname();

        $this->setAtom($id, array('absolute'   => true));
        // No need for fullnspath here

        return $id;
    }

    private function processNsname() {
        $current = $this->id;

        if ($this->tokens[$this->id][0] === \Exakat\Tasks\T_NS_SEPARATOR &&
            $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_STRING &&
            in_array(strtolower($this->tokens[$this->id + 1][1]), array('true', 'false')) &&
            $this->tokens[$this->id + 2][0] !== \Exakat\Tasks\T_NS_SEPARATOR
            ) {
            $nsnameId = $this->addAtom('Boolean');
            $this->setAtom($nsnameId, array('boolean' => (int) (bool) (strtolower($this->tokens[$this->id ][1]) === 'true') ));
        } elseif ($this->tokens[$this->id][0] === \Exakat\Tasks\T_NS_SEPARATOR  &&
            $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_STRING          &&
            strtolower($this->tokens[$this->id + 1][1]) === 'null' &&
            $this->tokens[$this->id + 2][0] !== \Exakat\Tasks\T_NS_SEPARATOR
            ) {
            $nsnameId = $this->addAtom('Null');
            $this->setAtom($nsnameId, array('boolean' => 0));
        } else {
            $nsnameId = $this->addAtom('Nsname');
        }
        $fullcode= array();

        $rank = 0;
        if ($this->hasExpression()) {
            $left = $this->popExpression();
            $this->addLink($nsnameId, $left, 'SUBNAME');
            $fullcode[] = $this->atoms[$left]['code'];

            $this->setAtom($left, array('rank' => $rank));
            $absolute = false;
        } else {
            $fullcode[] = '';
            $absolute = true;
        }

        while ($this->tokens[$this->id][0] === \Exakat\Tasks\T_NS_SEPARATOR) {
            $subnameId = $this->processNextAsIdentifier(false);

            $this->setAtom($subnameId, array('rank' => ++$rank));

            $this->addLink($nsnameId, $subnameId, 'SUBNAME');
            $fullcode[] = $this->atoms[$subnameId]['code'];

            // Go to next
            ++$this->id; // skip \
        }  ;
        // Back up a bit
        --$this->id;

        $x = array('code'     => $this->tokens[$current][1],
                   'fullcode' => implode('\\', $fullcode),
                   'line'     => $this->tokens[$current][2],
                   'variadic' => false,
                   'token'    => $this->getToken($this->tokens[$current][0]),
                   'absolute' => $absolute);
        $this->setAtom($nsnameId, $x);
        // Review this : most nsname will end up as constants!

        if ($this->isContext(self::CONTEXT_NEW) ||
            $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_VARIABLE ||
            $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_DOUBLE_COLON ||
            (isset($this->tokens[$current - 2]) && $this->tokens[$current - 2][0] === \Exakat\Tasks\T_INSTANCEOF)
            ) {
            list($fullnspath, $aliased) = $this->getFullnspath($nsnameId, 'class');
            $this->setAtom($nsnameId, array('fullnspath' => $fullnspath,
                                            'aliased'    => $aliased));

            $this->addCall('class', $fullnspath, $nsnameId);
        } elseif ($this->tokens[$this->id - 1][0] === \Exakat\Tasks\T_DOUBLE_COLON) {
            // DO nothing
        } else {
            list($fullnspath, $aliased) = $this->getFullnspath($nsnameId, 'const');
            $this->setAtom($nsnameId, array('fullnspath' => $fullnspath,
                                            'aliased'    => $aliased));

            $this->addCall('const', $fullnspath, $nsnameId);
        }

        $this->pushExpression($nsnameId);

        return $this->processFCOA($nsnameId);
    }

    private function processTypehint() {
        if (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_ARRAY, \Exakat\Tasks\T_CALLABLE, \Exakat\Tasks\T_STATIC))) {
            $id = $this->processNextAsIdentifier();
            $this->setAtom($id, array('fullnspath' => '\\'.strtolower($this->tokens[$this->id][1]) ,
                                      'variadic'   => false));
            return $id;
        } elseif (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_NS_SEPARATOR, \Exakat\Tasks\T_STRING, \Exakat\Tasks\T_NAMESPACE))) {
            $id = $this->processOneNsname();
            if (in_array(strtolower($this->tokens[$this->id][1]), array('int', 'bool', 'void', 'float', 'string'))) {
                $this->setAtom($id, array('fullnspath' => '\\'.strtolower($this->tokens[$this->id][1]) ));
            } else {
                $this->addCall('class', $this->atoms[$id]['fullnspath'], $id);
                if ($this->atoms[$id]['aliased'] === self::ALIASED) {
                    $this->addLink($this->usesId['class'][strtolower($this->atoms[$id]['code'])], $id, 'DEFINITION');
                }
            }
            return $id;
        } else {
            return 0;
        }
    }

    private function processArguments($finals = array(\Exakat\Tasks\T_CLOSE_PARENTHESIS), $typehint = false) {
        $argumentsId = $this->addAtom('Arguments');
        $current = $this->id;
        $this->argumentsId = array();

        $this->nestContext();
        $fullcode = array();
        if (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_PARENTHESIS, \Exakat\Tasks\T_CLOSE_BRACKET))) {
            $voidId = $this->addAtomVoid();
            $this->setAtom($voidId, array('rank' => 0));
            $this->addLink($argumentsId, $voidId, 'ARGUMENT');

            $this->setAtom($argumentsId, array('code'     => $this->tokens[$current][1],
                                               'fullcode' => self::FULLCODE_VOID,
                                               'line'     => $this->tokens[$current][2],
                                               'token'    => $this->getToken($this->tokens[$current][0]),
                                               'count'    => 0,
                                               'args_max' => 0,
                                               'args_min' => 0));
            $this->argumentsId[] = $voidId;

            ++$this->id;
        } else {
            $typehintId = 0;
            $defaultId = 0;
            $indexId = 0;
            $args_max = 0;
            $args_min = 0;
            $rank = -1;

            while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
                $initialId = $this->id;
                ++$args_max;

                if ($typehint === true) {
                    if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_QUESTION) {
                        $nullableId = $this->processNextAsIdentifier();
                    } else {
                        $nullableId = 0;
                    }
                    $typehintId = $this->processTypehint();

                    $this->processNext();
                    $indexId = $this->popExpression();

                    if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_EQUAL) {
                        ++$this->id; // Skip =
                        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_COMMA, \Exakat\Tasks\T_CLOSE_PARENTHESIS, \Exakat\Tasks\T_CLOSE_BRACKET))) {
                            $this->processNext();
                        }
                        $defaultId = $this->popExpression();
                    } else {
                        ++$args_min;
                        $defaultId = 0;
                    }
                } else {
                    $typehintId = 0;
                    $defaultId  = 0;
                    $nullableId = 0;

                    while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_COMMA, \Exakat\Tasks\T_CLOSE_PARENTHESIS, \Exakat\Tasks\T_SEMICOLON, \Exakat\Tasks\T_CLOSE_BRACKET, \Exakat\Tasks\T_CLOSE_TAG))) {
                        $this->processNext();
                    };
                    $indexId = $this->popExpression();
                }

                while ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COMMA) {
                    if ($indexId === 0) {
                        $indexId = $this->addAtomVoid();
                    }

                    $this->setAtom($indexId, array('rank' => ++$rank));
                    $this->argumentsId[] = $indexId;

                    if ($nullableId > 0) {
                        $this->addLink($indexId, $nullableId, 'NULLABLE');
                        $this->addLink($indexId, $typehintId, 'TYPEHINT');
                        $this->setAtom($indexId, array('fullcode' => '?'.$this->atoms[$typehintId]['fullcode'].' '.$this->atoms[$indexId]['fullcode']));
                    } elseif ($typehintId > 0) {
                        $this->addLink($indexId, $typehintId, 'TYPEHINT');
                        $this->setAtom($indexId, array('fullcode' => $this->atoms[$typehintId]['fullcode'].' '.$this->atoms[$indexId]['fullcode']));
                    }

                    if ($defaultId > 0) {
                        $this->addLink($indexId, $defaultId, 'DEFAULT');
                        $this->setAtom($indexId, array('fullcode' => $this->atoms[$indexId]['fullcode'].' = '.$this->atoms[$defaultId]['fullcode']));
                        $defaultId = 0;
                    }
                    $this->addLink($argumentsId, $indexId, 'ARGUMENT');
                    $fullcode[] = $this->atoms[$indexId]['fullcode'];

                    ++$this->id; // Skipping the comma ,
                    $indexId = 0;
                }

                if ($initialId === $this->id) {
                    throw new NoFileToProcess($this->filename, 'not processable with the current code.');
                }
            };

            if ($indexId === 0) {
                $indexId = $this->addAtomVoid();
            }
            $this->setAtom($indexId, array('rank' => ++$rank));
            $this->argumentsId[] = $indexId;

            if ($nullableId > 0) {
                $this->addLink($indexId, $nullableId, 'NULLABLE');
                $this->addLink($indexId, $typehintId, 'TYPEHINT');
                $this->setAtom($indexId, array('fullcode' => '?'.$this->atoms[$typehintId]['fullcode'].' '.$this->atoms[$indexId]['fullcode']));
            } elseif ($typehintId > 0) {
                $this->addLink($indexId, $typehintId, 'TYPEHINT');
                $this->setAtom($indexId, array('fullcode' => $this->atoms[$typehintId]['fullcode'].' '.$this->atoms[$indexId]['fullcode']));
            }

            if ($defaultId > 0) {
                $this->addLink($indexId, $defaultId, 'DEFAULT');
                $this->setAtom($indexId, array('fullcode' => $this->atoms[$indexId]['fullcode'].' = '.$this->atoms[$defaultId]['fullcode']));
            }
            $this->addLink($argumentsId, $indexId, 'ARGUMENT');

            $fullcode[] = $this->atoms[$indexId]['fullcode'];

            // Skip the )
            ++$this->id;

            $this->setAtom($argumentsId, array('code'     => $this->tokens[$current][1],
                                               'fullcode' => implode(', ', $fullcode),
                                               'line'     => $this->tokens[$current][2],
                                               'token'    => 'T_COMMA',
                                               'count'    => $rank + 1,
                                               'args_max' => $args_max,
                                               'args_min' => $args_min));
        }

        $this->exitContext();

        return $argumentsId;
    }

    private function processNextAsIdentifier($getFullnspath = true) {
        ++$this->id;
        $id = $this->addAtom('Identifier');
        $this->setAtom($id, array('code'       => $this->tokens[$this->id][1],
                                  'fullcode'   => $this->tokens[$this->id][1],
                                  'line'       => $this->tokens[$this->id][2],
                                  'token'      => $this->getToken($this->tokens[$this->id][0]),
                                  'variadic'   => false,
                                  'absolute'   => false));
        if ($getFullnspath === true) {
            list($fullnspath, $aliased) = $this->getFullnspath($id);
            $this->setAtom($id, array('fullnspath' => $fullnspath,
                                      'aliased'    => $aliased));
        }

        return $id;
    }

    private function processConst() {
        $constId = $this->addAtom('Const');
        $current = $this->id;
        $rank = -1;
        --$this->id; // back one step for the init in the next loop

        do {
            ++$this->id;
            $const = $this->id;
            $nameId = $this->processNextAsIdentifier();

            ++$this->id; // Skip =
            while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_SEMICOLON, \Exakat\Tasks\T_COMMA))) {
                $this->processNext();
            }
            $valueId = $this->popExpression();

            $defId = $this->addAtom('Constant');
            $this->addLink($defId, $nameId, 'NAME');
            $this->addLink($defId, $valueId, 'VALUE');

            $this->setAtom($defId, array('code'     => $this->tokens[$const][1],
                                         'fullcode' => $this->atoms[$nameId]['fullcode'].' = '.$this->atoms[$valueId]['fullcode'],
                                         'line'     => $this->tokens[$const][2],
                                         'token'    => $this->getToken($this->tokens[$const][0]),
                                         'rank'     => ++$rank));
            $fullcode[] = $this->atoms[$defId]['fullcode'];

            list($fullnspath, $aliased) = $this->getFullnspath($nameId, 'const');
            $this->addDefinition('const', $fullnspath, $defId);
            $this->setAtom($constId, array('fullnspath'     => $fullnspath,
                                           'aliased'        => $aliased));

            $this->addLink($constId, $defId, 'CONST');
        } while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_SEMICOLON)));

        $this->setAtom($constId, array('code'     => $this->tokens[$current][1],
                                       'fullcode' => $this->tokens[$current][1].' '.implode(', ', $fullcode),
                                       'line'     => $this->tokens[$current][2],
                                       'token'    => $this->getToken($this->tokens[$current][0]),
                                       'count'    => $rank + 1));

        $this->pushExpression($constId);

        return $this->processFCOA($constId);
    }

    private function processOptions($atom) {
        $this->processSingle($atom);

        $this->optionsTokens[$atom] = $this->popExpression();
        return $this->optionsTokens[$atom];
    }

    private function processAbstract() {
        return $this->processOptions('Abstract');
    }

    private function processFinal() {
        return $this->processOptions('Final');
    }

    private function processVar() {
        $id = $this->processOptions('Var');

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_VARIABLE) {
            $pppId = $this->processSGVariable('Ppp');
            return $pppId;
        } else {
            return $id;
        }
    }

    private function processPublic() {
        $id = $this->processOptions('Public');

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_VARIABLE) {
            $pppId = $this->processSGVariable('Ppp');
            return $pppId;
        } else {
            return $id;
        }
    }

    private function processProtected() {
        $id = $this->processOptions('Protected');

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_VARIABLE) {
            $pppId = $this->processSGVariable('Ppp');
            return $pppId;
        } else {
            return $id;
        }
    }

    private function processPrivate() {
        $id = $this->processOptions('Private');

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_VARIABLE) {
            $pppId = $this->processSGVariable('Ppp');
            return $pppId;
        } else {
            return $id;
        }
    }

    private function processFunctioncall() {
        $nameId = $this->popExpression();
        ++$this->id; // Skipping the name, set on (
        $current = $this->id;

        $argumentsId = $this->processArguments();

        $functioncallId = $this->addAtom('Functioncall');
        if (!$this->isContext(self::CONTEXT_NEW)) {
            list($fullnspath, $aliased) = $this->getFullnspath($nameId, 'function');
            $this->setAtom($functioncallId, array('fullnspath' => $fullnspath,
                                                  'aliased'    => $aliased));
            // Probably weak check, since we haven't built fullnspath for functions yet...
            if ($fullnspath === '\\define') {
                $this->processDefineAsConstants($argumentsId);
            }

            $this->addCall('function', $fullnspath, $functioncallId);

            if ($fullnspath === '\\array') {
                $this->setAtom($functioncallId, array('boolean'    => (int) (bool) $this->atoms[$argumentsId]['count']));
            }
        }

        $this->setAtom($functioncallId, array('code'       => $this->atoms[$nameId]['code'],
                                              'fullcode'   => $this->atoms[$nameId]['fullcode'].'('.$this->atoms[$argumentsId]['fullcode'].')',
                                              'line'       => $this->tokens[$current][2],
                                              'variadic'   => false,
                                              'reference'  => false,
                                              'token'      => $this->atoms[$nameId]['token'],
                                              'fullnspath' => isset($this->atoms[$nameId]['fullnspath']) ? $this->atoms[$nameId]['fullnspath'] : self::NO_VALUE,
                                              'aliased'    => isset($this->atoms[$nameId]['aliased']) ? $this->atoms[$nameId]['aliased'] : self::NO_VALUE
                                              ));
        $this->addLink($functioncallId, $argumentsId, 'ARGUMENTS');
        $this->addLink($functioncallId, $nameId, 'NAME');

        $this->pushExpression($functioncallId);

        if ( $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG &&
             $this->tokens[$current - 2][0] !== \Exakat\Tasks\T_OBJECT_OPERATOR &&
             $this->tokens[$current - 2][0] !== \Exakat\Tasks\T_DOUBLE_COLON
             ) {
            $this->processSemicolon();
        } else {
            $functioncallId = $this->processFCOA($functioncallId);
        }
        return $functioncallId;
    }

    private function processString($fullnspath = true) {
        if (strtolower($this->tokens[$this->id][1]) === 'null' ) {
            $id = $this->addAtom('Null');
            $this->setAtom($id, array('boolean' => 0));
        } elseif (in_array(strtolower($this->tokens[$this->id][1]), array('true', 'false'))) {
            $id = $this->addAtom('Boolean');
            $this->setAtom($id, array('boolean' => (int) (bool) (strtolower($this->tokens[$this->id ][1]) === 'true') ));
        } else {
            $id = $this->addAtom('Identifier');
        }

        $this->setAtom($id, array('code'       => $this->tokens[$this->id][1],
                                  'fullcode'   => $this->tokens[$this->id][1],
                                  'line'       => $this->tokens[$this->id][2],
                                  'variadic'   => false,
                                  'token'      => $this->getToken($this->tokens[$this->id][0]),
                                  'absolute'   => false));

        // New is first as it may also be followed by a (
        if ($this->tokens[$this->id - 1][0] === \Exakat\Tasks\T_DOUBLE_COLON ||
            $this->tokens[$this->id - 1][0] === \Exakat\Tasks\T_OBJECT_OPERATOR) {
            // Just skip this : no need for fullnspat with property or methodcall, static or not
        } elseif ($this->tokens[$this->id - 1][0] === \Exakat\Tasks\T_NEW) {
            // Do nothing, this will be done at processNew level
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_PARENTHESIS) {
            // when this is not already done, we prepare the fullnspath as a constant
            list($fullnspath, $aliased) = $this->getFullnspath($id, 'function');
            $this->setAtom($id, array('fullnspath' => $fullnspath,
                                      'aliased'    => $aliased));

            $this->addCall('function', $fullnspath, $id);
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_DOUBLE_COLON ||
                  $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_VARIABLE) {
            list($fullnspath, $aliased) = $this->getFullnspath($id, 'class');
            $this->setAtom($id, array('fullnspath' => $fullnspath,
                                      'aliased'    => $aliased));

            $this->addCall('class', $fullnspath, $id);

            if ($aliased === self::ALIASED) {
                $this->addLink($this->usesId['class'][strtolower($this->atoms[$id]['code'])], $id, 'DEFINITION');
            }
        } else {
            // No new, no () : a constant
            // when this is not already done, we prepare the fullnspath as a constant
            list($fullnspath, $aliased) = $this->getFullnspath($id, 'const');
            $this->setAtom($id, array('fullnspath' => $fullnspath,
                                      'aliased'    => $aliased));
            $this->addCall('const', $fullnspath, $id);
        }

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_NS_SEPARATOR) {
            $this->pushExpression($id);
            ++$this->id;
            $this->processNsname();
            $id = $this->popExpression();
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COLON &&
                  !$this->isContext(self::CONTEXT_NEW) &&
                  !$this->isContext(self::CONTEXT_NOSEQUENCE)                  ) {
            $labelId = $this->addAtom('Label');
            $this->addLink($labelId, $id, 'LABEL');
            $this->setAtom($labelId, array('code'     => ':',
                                           'fullcode' => $this->atoms[$id]['fullcode'].' :',
                                           'line'     => $this->tokens[$this->id][2],
                                           'token'    => $this->getToken($this->tokens[$this->id][0])));

            $this->pushExpression($labelId);
            $this->processSemicolon();
            return $labelId;
        }
        $this->pushExpression($id);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        } else {
            // For functions and constants
            $id = $this->processFCOA($id);
        }

        return $id;
    }

    private function processPlusplus() {
        if ($this->hasExpression()) {
            $previousId = $this->popExpression();
            // postplusplus
            $plusplusId = $this->addAtom('Postplusplus');

            $this->addLink($plusplusId, $previousId, 'POSTPLUSPLUS');

            $this->setAtom($plusplusId, array('code'     => $this->tokens[$this->id][1],
                                              'fullcode' => $this->atoms[$previousId]['fullcode'].$this->tokens[$this->id][1],
                                              'line'     => $this->tokens[$this->id][2],
                                              'token'    => $this->getToken($this->tokens[$this->id][0])));
            $this->pushExpression($plusplusId);

            if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
                $this->processSemicolon();
            }
        } else {
            // preplusplus
            $plusplusId = $this->processSingleOperator('Preplusplus', $this->precedence->get($this->tokens[$this->id][0]), 'PREPLUSPLUS');
        }
    }

    private function processStatic() {
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_DOUBLE_COLON ||
            $this->tokens[$this->id - 1][0] === \Exakat\Tasks\T_INSTANCEOF    ) {
            $id = $this->processSingle('Identifier');
            $this->setAtom($id, array('fullnspath' => '\\static',
                                      'variadic'   => false));
            return $id;
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_PARENTHESIS) {
            $nameId = $this->addAtom('Identifier');
            $this->setAtom($nameId, array('code'       => $this->tokens[$this->id][1],
                                          'fullcode'   => $this->tokens[$this->id][1],
                                          'line'       => $this->tokens[$this->id][2],
                                          'variadic'   => false,
                                          'token'      => $this->getToken($this->tokens[$this->id][0]),
                                          'fullnspath' => '\\static'));
            $this->pushExpression($nameId);

            return $this->processFunctioncall();
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_VARIABLE) {
            if (($this->isContext(self::CONTEXT_CLASS) ||
                 $this->isContext(self::CONTEXT_TRAIT)   ) &&
                !$this->isContext(self::CONTEXT_FUNCTION)) {
                // something like public static
                $this->processOptions('Static');

                return $this->processSGVariable('Ppp');
            } else {
                return $this->processStaticVariable();
            }
        } elseif ($this->isContext(self::CONTEXT_NEW)) {
            // new static; (no parenthesis, as tested above)

            $nameId = $this->processExit();

            return $nameId;
        } else {
            return $this->processOptions('Static');
        }
    }

    private function processSGVariable($atom) {
        $current = $this->id;
        $staticId = $this->addAtom($atom);
        $rank = 0;

        if ($atom === 'Global' || $atom === 'Static') {
            $fullcodePrefix = array($this->tokens[$this->id][1]);
        } else {
            $fullcodePrefix= array();
        }
        foreach($this->optionsTokens as $name => $optionId) {
            $this->addLink($staticId, $optionId, strtoupper($name));
            $fullcodePrefix[] = $this->atoms[$optionId]['fullcode'];
        }
        $fullcodePrefix = implode(' ', $fullcodePrefix);

        $this->optionsTokens = array();

        if (!isset($fullcodePrefix)) {
            $fullcodePrefix = $this->tokens[$current][1];
        }

        $fullcode = array();
        while ($this->tokens[$this->id + 1][0] !== \Exakat\Tasks\T_SEMICOLON) {
            $this->processNext();

            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COMMA) {
                $elementId = $this->popExpression();
                $this->setAtom($elementId, array('rank' => ++$rank));
                $this->addLink($staticId, $elementId, strtoupper($atom));

                $fullcode[] = $this->atoms[$elementId]['fullcode'];
                ++$this->id;
            }
        } ;
        $elementId = $this->popExpression();
        $this->addLink($staticId, $elementId, strtoupper($atom));

        $fullcode[] = $this->atoms[$elementId]['fullcode'];

        $this->setAtom($staticId, array('code'     => $this->tokens[$current][1],
                                        'fullcode' => $fullcodePrefix.' '.implode(', ', $fullcode),
                                        'line'     => $this->tokens[$current][2],
                                        'token'    => $this->getToken($this->tokens[$current][0]),
                                        'count'    => $rank));
        $this->pushExpression($staticId);

        return $staticId;
    }

    private function processStaticVariable() {
        return $this->processSGVariable('Static');
    }

    private function processGlobalVariable() {
        return $this->processSGVariable('Global');
    }

    private function processArrayBracket() {
        $current = $this->id;
        $id = $this->addAtom('Functioncall');

        $variableId = $this->addAtom('Identifier');
        $this->addLink($id, $variableId, 'NAME');
        $this->setAtom($variableId, array('code'       => '[',
                                          'fullcode'   => '[',
                                          'variadic'   => false,
                                          'line'       => $this->tokens[$this->id][2],
                                          'token'      => $this->getToken($this->tokens[$this->id][0]),
                                          'fullnspath' => '\\array'));

        // No need to skip opening bracket
        $argumentId = $this->processArguments(array(\Exakat\Tasks\T_CLOSE_BRACKET));
        $this->addLink($id, $argumentId, 'ARGUMENTS');

        $this->setAtom($id, array('code'       => $this->tokens[$current][1],
                                  'fullcode'   => '['.$this->atoms[$argumentId]['fullcode'].']' ,
                                  'line'       => $this->tokens[$this->id][2],
                                  'variadic'   => false,
                                  'token'      => $this->getToken($this->tokens[$current][0]),
                                  'fullnspath' => '\\array',
                                  'boolean'    => (int) (bool) $this->atoms[$argumentId]['count']));
        $this->pushExpression($id);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        } else {
            $id = $this->processFCOA($id);
        }

        return $id;
    }

    private function processBracket($followupFCOA = true) {
        $id = $this->addAtom('Array');
        $current = $this->id;

        $variableId = $this->popExpression();
        $this->addLink($id, $variableId, 'VARIABLE');

        // Skip opening bracket
        $opening = $this->tokens[$this->id + 1][0];
        if ($opening === '{') {
            $closing = '}';
        } else {
            $closing = ']';
        }

        ++$this->id;
        do {
            $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_BRACKET, \Exakat\Tasks\T_CLOSE_CURLY))) ;

        // Skip closing bracket
        ++$this->id;

        $indexId = $this->popExpression();
        $this->addLink($id, $indexId, 'INDEX');

        $this->setAtom($id, array('code'      => $opening,
                                  'fullcode'  => $this->atoms[$variableId]['fullcode'].$opening.$this->atoms[$indexId]['fullcode'].$closing ,
                                  'line'      => $this->tokens[$current][2],
                                  'variadic'  => false,
                                  'token'     => $this->getToken($this->tokens[$current][0]),
                                  'enclosing' => false));
        $this->pushExpression($id);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        } elseif ($followupFCOA === true) {
            $id = $this->processFCOA($id);
        }

        return $id;
    }

    private function processBlock($standalone = true) {
        $this->startSequence();

        // Case for {}
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_CURLY) {
            $voidId = $this->addAtomVoid();
            $this->addToSequence($voidId);
        } else {
            while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_CURLY))) {
                $this->processNext();
            };

            if ($this->tokens[$this->id + 1][0] !== \Exakat\Tasks\T_CLOSE_CURLY) {
                $this->processSemicolon();
            }
        }

        $blockId = $this->sequence;
        $this->endSequence();

        $this->setAtom($blockId, array('code'     => '{}',
                                       'fullcode' => static::FULLCODE_BLOCK,
                                       'line'     => $this->tokens[$this->id][2],
                                       'token'    => $this->getToken($this->tokens[$this->id][0]),
                                       'bracket'  => true));

        ++$this->id; // skip }

        $this->pushExpression($blockId);
        if ($standalone === true) {
            $this->processSemicolon();
        }

        return $blockId;
    }

    private function processForblock($finals) {
        $this->startSequence();
        $blockId = $this->sequence;

        while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
            $this->processNext();

            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COMMA) {
                $elementId = $this->popExpression();
                $this->addToSequence($elementId);

                ++$this->id;
            }
        };
        $elementId = $this->popExpression();
        $this->addToSequence($elementId);

        ++$this->id;
        $current = $this->sequence;
        $this->endSequence();
        $x = array('code'     => $this->atoms[$current]['code'],
                   'fullcode' => self::FULLCODE_SEQUENCE,
                   'line'     => $this->tokens[$this->id][2],
                   'token'    => $this->getToken($this->tokens[$this->id][0]));

        if ($this->atoms[$current]['count'] === 1) {
            $x['fullcode'] = $this->atoms[$elementId]['fullcode'];
        }
        $this->setAtom($blockId, $x);
        $this->pushExpression($blockId);

        return $blockId;
    }

    private function processFor() {
        $forId = $this->addAtom('For');
        $current = $this->id;
        ++$this->id; // Skip for

        $this->processForblock(array(\Exakat\Tasks\T_SEMICOLON));
        $initId = $this->popExpression();
        $this->addLink($forId, $initId, 'INIT');

        $this->processForblock(array(\Exakat\Tasks\T_SEMICOLON));
        $finalId = $this->popExpression();
        $this->addLink($forId, $finalId, 'FINAL');

        $this->processForblock(array(\Exakat\Tasks\T_CLOSE_PARENTHESIS));
        $incrementId = $this->popExpression();
        $this->addLink($forId, $incrementId, 'INCREMENT');

        $isColon = ($this->tokens[$current][0] === \Exakat\Tasks\T_FOR) && ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COLON);

        $blockId = $this->processFollowingBlock(array(\Exakat\Tasks\T_ENDFOR));
        $this->popExpression();
        $this->addLink($forId, $blockId, 'BLOCK');

        $code = $this->tokens[$current][1];
        if ($isColon) {
            $fullcode = $this->tokens[$current][1].'('.$this->atoms[$initId]['fullcode'].' ; '.$this->atoms[$finalId]['fullcode'].' ; '.$this->atoms[$incrementId]['fullcode'].') : '.self::FULLCODE_SEQUENCE.' '.$this->tokens[$this->id + 1][1];
        } else {
            $fullcode = $this->tokens[$current][1].'('.$this->atoms[$initId]['fullcode'].' ; '.$this->atoms[$finalId]['fullcode'].' ; '.$this->atoms[$incrementId]['fullcode'].')'.($this->atoms[$blockId]['bracket'] === true ? self::FULLCODE_BLOCK : self::FULLCODE_SEQUENCE);
        }

        $this->setAtom($forId, array('code'        => $code,
                                     'fullcode'    => $fullcode,
                                     'line'        => $this->tokens[$current][2],
                                     'token'       => $this->getToken($this->tokens[$this->id][0]),
                                     'alternative' => $isColon));
        $this->pushExpression($forId);

        if ($isColon === true) {
            ++$this->id; // skip endfor
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_SEMICOLON) {
                ++$this->id; // skip ; (will do just below)
            }
        }
        $this->processSemicolon();

        return $forId;
    }

    private function processForeach() {
        $id = $this->addAtom('Foreach');
        $current = $this->id;
        ++$this->id; // Skip foreach

        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_AS))) {
            $this->processNext();
        };

        $sourceId = $this->popExpression();
        $this->addLink($id, $sourceId, 'SOURCE');

        $as = $this->tokens[$this->id + 1][1];
        ++$this->id; // Skip as

        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_PARENTHESIS, \Exakat\Tasks\T_DOUBLE_ARROW))) {
            $this->processNext();
        };

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_DOUBLE_ARROW) {
            $this->processNext();
        }

        $valueId = $this->popExpression();
        $this->addLink($id, $valueId, 'VALUE');

        ++$this->id; // Skip )
        $isColon = ($this->tokens[$current][0] === \Exakat\Tasks\T_FOREACH) && ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COLON);

        $blockId = $this->processFollowingBlock($isColon === true ? array(\Exakat\Tasks\T_ENDFOREACH) : array());

        $this->popExpression();
        $this->addLink($id, $blockId, 'BLOCK');

        if ($isColon === true) {
            ++$this->id; // skip endforeach
            $fullcode = $this->tokens[$current][1].'('.$this->atoms[$sourceId]['fullcode'].' '.$as.' '.$this->atoms[$valueId]['fullcode'].') : '.self::FULLCODE_SEQUENCE.' endforeach';
        } else {
            $fullcode = $this->tokens[$current][1].'('.$this->atoms[$sourceId]['fullcode'].' '.$as.' '.$this->atoms[$valueId]['fullcode'].')'.($this->atoms[$blockId]['bracket'] === true ? self::FULLCODE_BLOCK : self::FULLCODE_SEQUENCE);
        }

        $this->setAtom($id, array('code'        => $this->tokens[$current][1],
                                  'fullcode'    => $fullcode,
                                  'line'        => $this->tokens[$current][2],
                                  'token'       => $this->getToken($this->tokens[$current][0]),
                                  'alternative' => $isColon));
        $this->pushExpression($id);
        $this->processSemicolon();

        if ($this->tokens[$this->id][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            --$this->id;
        }

        return $id;
    }

    private function processFollowingBlock($finals) {
        $this->exitContext();

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_CURLY) {
            ++$this->id;
            $blockId = $this->processBlock(false);
            $this->setAtom($blockId, array('bracket' => true));
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COLON) {
            $this->startSequence();
            $blockId = $this->sequence;
            ++$this->id; // skip :

            while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
                $this->processNext();
            };

            $this->setAtom($blockId, array('bracket' => false));
            $this->pushExpression($this->sequence);
            $this->endSequence();

        } elseif (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_SEMICOLON))) {
            // void; One epxression block, with ;
            $this->startSequence();
            $blockId = $this->sequence;

            $voidId = $this->addAtomVoid();
            $this->addToSequence($voidId);
            $this->endSequence();
            $this->setAtom($blockId, array('bracket' => false));
            $this->pushExpression($blockId);
            ++$this->id;

        } elseif (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_TAG, \Exakat\Tasks\T_CLOSE_CURLY, \Exakat\Tasks\T_CLOSE_PARENTHESIS))) {
            // Completely void (not even ;)
            $this->startSequence();
            $blockId = $this->sequence;

            $voidId = $this->addAtomVoid();
            $this->addToSequence($voidId);
            $this->endSequence();

            $this->setAtom($blockId, array('bracket' => false));
            $this->pushExpression($blockId);

        } else {
            // One expression only
            $this->startSequence();
            $blockId = $this->sequence;
            $current = $this->id;

            // This may include WHILE in the list of finals for do....while
            $finals = array_merge(array(\Exakat\Tasks\T_SEMICOLON, \Exakat\Tasks\T_CLOSE_TAG, \Exakat\Tasks\T_ELSE, \Exakat\Tasks\T_END, \Exakat\Tasks\T_CLOSE_CURLY), $finals);
            $specials = array(\Exakat\Tasks\T_IF, \Exakat\Tasks\T_FOREACH, \Exakat\Tasks\T_SWITCH, \Exakat\Tasks\T_FOR, \Exakat\Tasks\T_TRY, \Exakat\Tasks\T_WHILE);
            if (in_array($this->tokens[$this->id + 1][0], $specials)) {
                $this->processNext();
            } else {
                while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
                    $this->processNext();
                };
                $expressionId = $this->popExpression();
                $this->addToSequence($expressionId);
            }

            $this->endSequence();

            if (!in_array($this->tokens[$current + 1][0], $specials)) {
                ++$this->id;
            }

            $this->setAtom($blockId, array('bracket' => false));
            $this->pushExpression($blockId);
        }

        $this->nestContext();

        return $blockId;
    }

    private function processDo() {
        $dowhileId = $this->addAtom('Dowhile');
        $current = $this->id;

        $blockId = $this->processFollowingBlock(array(\Exakat\Tasks\T_WHILE));
        $this->popExpression();
        $this->addLink($dowhileId, $blockId, 'BLOCK');

        $while = $this->tokens[$this->id + 1][1];
        ++$this->id; // Skip while
        ++$this->id; // Skip (

        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_PARENTHESIS))) {
            $this->processNext();
        };
        ++$this->id; // skip )
        $conditionId = $this->popExpression();
        $this->addLink($dowhileId, $conditionId, 'CONDITION');

        $this->setAtom($dowhileId, array('code'     => $this->tokens[$current][1],
                                         'fullcode' => $this->tokens[$current][1].( $this->atoms[$blockId]['bracket'] === true ? self::FULLCODE_BLOCK : self::FULLCODE_SEQUENCE).$while.'('.$this->atoms[$conditionId]['fullcode'].')',
                                         'line'     => $this->tokens[$current][2],
                                         'token'    => $this->getToken($this->tokens[$current][0]) ));
        $this->pushExpression($dowhileId);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $dowhileId;
    }

    private function processWhile() {
        $whileId = $this->addAtom('While');
        $current = $this->id;

        ++$this->id; // Skip while

        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_PARENTHESIS))) {
            $this->processNext();
        };
        $conditionId = $this->popExpression();
        $this->addLink($whileId, $conditionId, 'CONDITION');

        ++$this->id; // Skip )
        $isColon = ($this->tokens[$current][0] === \Exakat\Tasks\T_WHILE) && ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COLON);
        $blockId = $this->processFollowingBlock(array(\Exakat\Tasks\T_ENDWHILE));
        $this->popExpression();

        $this->addLink($whileId, $blockId, 'BLOCK');

        if ($isColon === true) {
            ++$this->id;
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_SEMICOLON) {
                ++$this->id; // skip ;
            }

            $fullcode = $this->tokens[$current][1].' ('.$this->atoms[$conditionId]['fullcode'].') : '.self::FULLCODE_SEQUENCE.' '.$this->tokens[$this->id - 1][1];
        } else {
            $fullcode = $this->tokens[$current][1].' ('.$this->atoms[$conditionId]['fullcode'].')'.($this->atoms[$blockId]['bracket'] === true ? self::FULLCODE_BLOCK : self::FULLCODE_SEQUENCE);
        }

        $this->setAtom($whileId, array('code'        => $this->tokens[$current][1],
                                       'fullcode'    => $fullcode,
                                       'line'        => $this->tokens[$current][2],
                                       'token'       => $this->getToken($this->tokens[$current][0]),
                                       'alternative' => $isColon ));

        $this->pushExpression($whileId);
        $this->processSemicolon();
        return $whileId;
    }

    private function processDeclare() {
        $declareId = $this->addAtom('Declare');
        $current = $this->id;

        ++$this->id; // Skip declare
        $argsId = $this->processArguments();
        $this->addLink($declareId, $argsId, 'DECLARE');
        $isColon = ($this->tokens[$current][0] === \Exakat\Tasks\T_DECLARE) && ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COLON);

        $blockId = $this->processFollowingBlock(array(\Exakat\Tasks\T_ENDDECLARE));
        $this->popExpression();
        $this->addLink($declareId, $blockId, 'BLOCK');

        if ($isColon === true) {
            $fullcode = $this->tokens[$current][1].' ('.$this->atoms[$argsId]['fullcode'].') : '.self::FULLCODE_SEQUENCE.' '.$this->tokens[$this->id + 1][1];
            ++$this->id; // skip enddeclare
            ++$this->id; // skip ;
        } else {
            $fullcode = $this->tokens[$current][1].' ('.$this->atoms[$argsId]['fullcode'].') '.self::FULLCODE_BLOCK;
        }
        $this->pushExpression($declareId);
        $this->processSemicolon();

        $this->setAtom($declareId, array('code'        => $this->tokens[$current][1],
                                         'fullcode'    => $fullcode,
                                         'line'        => $this->tokens[$current][2],
                                         'token'       => $this->getToken($this->tokens[$current][0]),
                                         'alternative' => $isColon ));
        return $declareId;
    }

    private function processDefault() {
        $defaultId = $this->addAtom('Default');
        $current = $this->id;
        ++$this->id; // Skip : or ;

        $this->startSequence();
        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_CURLY, \Exakat\Tasks\T_CASE, \Exakat\Tasks\T_DEFAULT, \Exakat\Tasks\T_ENDSWITCH))) {
            $this->processNext();
        };
        $this->addLink($defaultId, $this->sequence, 'CODE');
        $this->endSequence();

        $this->setAtom($defaultId, array('code'     => $this->tokens[$current][1],
                                         'fullcode' => $this->tokens[$current][1].' : '.self::FULLCODE_SEQUENCE,
                                         'line'     => $this->tokens[$current][2],
                                         'token'    => $this->getToken($this->tokens[$current][0])));
        $this->pushExpression($defaultId);

        return $defaultId;
    }

    private function processCase() {
        $caseId = $this->addAtom('Case');
        $current = $this->id;

        $this->nestContext();
        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_COLON, \Exakat\Tasks\T_SEMICOLON))) {
            $this->processNext();
        };
        $this->exitContext();

        $itemId = $this->popExpression();
        $this->addLink($caseId, $itemId, 'CASE');

        ++$this->id; // Skip :

        $this->startSequence();
        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_CURLY, \Exakat\Tasks\T_CASE, \Exakat\Tasks\T_DEFAULT, \Exakat\Tasks\T_ENDSWITCH))) {
            $this->processNext();
        };
        $this->addLink($caseId, $this->sequence, 'CODE');
        $this->endSequence();

        $this->setAtom($caseId, array('code'     => $this->tokens[$current][1].' '.$this->atoms[$itemId]['fullcode'].' : '.self::FULLCODE_SEQUENCE.' ',
                                      'fullcode' => $this->tokens[$current][1].' '.$this->atoms[$itemId]['fullcode'].' : '.self::FULLCODE_SEQUENCE.' ',
                                      'line'     => $this->tokens[$current][2],
                                      'token'    => $this->getToken($this->tokens[$current][0])));
        $this->pushExpression($caseId);

        return $caseId;
    }

    private function processSwitch() {
        $switchId = $this->addAtom('Switch');
        $current = $this->id;
        ++$this->id; // Skip (

        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_PARENTHESIS))) {
            $this->processNext();
        };
        $nameId = $this->popExpression();
        $this->addLink($switchId, $nameId, 'NAME');

        $casesId = $this->addAtom('Sequence');
        $this->setAtom($casesId, array('code'     => self::FULLCODE_SEQUENCE,
                                       'fullcode' => self::FULLCODE_SEQUENCE,
                                       'line'     => $this->tokens[$current][2],
                                       'token'    => $this->getToken($this->tokens[$current][0]),
                                       'bracket'  => true));
        $this->addLink($switchId, $casesId, 'CASES');
        ++$this->id;

        $isColon = $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COLON;

        $rank = 0;
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_PARENTHESIS) {
            $voidId = $this->addAtomVoid();
            $this->addLink($casesId, $voidId, 'ELEMENT');
            $this->setAtom($voidId, array('rank' => $rank));

            ++$this->id;
        } else {
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_CURLY) {
                ++$this->id;
                $finals = array(\Exakat\Tasks\T_CLOSE_CURLY);
            } else {
                ++$this->id; // skip :
                $finals = array(\Exakat\Tasks\T_ENDSWITCH);
            }
            while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
                $this->processNext();

                $caseId = $this->popExpression();
                $this->addLink($casesId, $caseId, 'ELEMENT');
                $this->setAtom($caseId, array('rank' => ++$rank));
            };
        }
        ++$this->id;
        $this->setAtom($casesId, array('count'     => $rank));

        if ($isColon) {
            $fullcode = $this->tokens[$current][1].' ('.$this->atoms[$nameId]['fullcode'].') :'.self::FULLCODE_SEQUENCE.' '.$this->tokens[$this->id][1];
        } else {
            $fullcode = $this->tokens[$current][1].' ('.$this->atoms[$nameId]['fullcode'].')'.self::FULLCODE_BLOCK;
        }

        $this->setAtom($switchId, array('code'        => $this->tokens[$current][1],
                                        'fullcode'    => $fullcode,
                                        'line'        => $this->tokens[$current][2],
                                        'token'       => $this->getToken($this->tokens[$current][0]),
                                        'alternative' => $isColon));

        $this->pushExpression($switchId);
        $this->processSemicolon();

        return $switchId;
    }

    private function processIfthen() {
        $id = $this->addAtom('Ifthen');
        $current = $this->id;
        ++$this->id; // Skip (

        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_PARENTHESIS))) {
            $this->processNext();
        };
        $conditionId = $this->popExpression();
        $this->addLink($id, $conditionId, 'CONDITION');

        ++$this->id; // Skip )
        $isInitialIf = $this->tokens[$current][0] === \Exakat\Tasks\T_IF;
        $isColon =  $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COLON;

        $thenId = $this->processFollowingBlock(array(\Exakat\Tasks\T_ENDIF, \Exakat\Tasks\T_ELSE, \Exakat\Tasks\T_ELSEIF));
        $this->popExpression();
        $this->addLink($id, $thenId, 'THEN');

        // Managing else case
        if (in_array($this->tokens[$this->id][0], array(\Exakat\Tasks\T_END, \Exakat\Tasks\T_CLOSE_TAG))) {
            $else = '';
            // No else, end of a script
            --$this->id;
            // Back up one unit to allow later processing for sequence
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_ELSEIF){
            ++$this->id;

            $elseifId = $this->processIfthen();
            $this->addLink($id, $elseifId, 'ELSE');

            $else = $this->atoms[$elseifId]['fullcode'];

        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_ELSE){
            $else = $this->tokens[$this->id + 1][1];
            ++$this->id; // Skip else

            $elseId = $this->processFollowingBlock(array(\Exakat\Tasks\T_ENDIF));
            $this->popExpression();
            $this->addLink($id, $elseId, 'ELSE');

            if ($isColon === true) {
                $else .= ' :';
            }
            $else .= $this->atoms[$elseId]['fullcode'];
        } else {
            $else = '';
        }

        if ($isInitialIf === true && $isColon === true) {
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_SEMICOLON) {
                ++$this->id; // skip ;
            }
            ++$this->id; // skip ;
        }

        if ($isColon) {
            $fullcode = $this->tokens[$current][1].'('.$this->atoms[$conditionId]['fullcode'].') : '.$this->atoms[$thenId]['fullcode'].$else.($isInitialIf === true ? ' endif' : '');
        } else {
            $fullcode = $this->tokens[$current][1].'('.$this->atoms[$conditionId]['fullcode'].')'.$this->atoms[$thenId]['fullcode'].$else;
        }

        if ($this->tokens[$current][0] === \Exakat\Tasks\T_IF) {
            $this->pushExpression($id);
            $this->processSemicolon();
        }

        if ($this->tokens[$this->id][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            --$this->id;
        }

        $this->setAtom($id, array('code'        => $this->tokens[$current][1],
                                  'fullcode'    => $fullcode,
                                  'line'        => $this->tokens[$current][2],
                                  'token'       => $this->getToken($this->tokens[$current][0]),
                                  'alternative' => $isColon ));

        return $id;
    }

    private function processParenthesis() {
        $parentheseId = $this->addAtom('Parenthesis');

        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_PARENTHESIS))) {
            $this->processNext();
        };

        $indexId = $this->popExpression();
        $this->addLink($parentheseId, $indexId, 'CODE');

        $this->setAtom($parentheseId, array('code'     => '(',
                                            'fullcode' => '('.$this->atoms[$indexId]['fullcode'].')',
                                            'line'     => $this->tokens[$this->id][2],
                                            'token'    => 'T_OPEN_PARENTHESIS' ));
        $this->pushExpression($parentheseId);
        ++$this->id; // Skipping the )

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $this->processFCOA($parentheseId);
    }

    private function makeFunctioncall($nameTokenId, $argumentsId = 0) {
        $functioncallId = $this->addAtom('Functioncall');
        $current = $this->id;

        if ($argumentsId === 0) {
            $voidId = $this->addAtomVoid();

            $argumentsId = $this->addAtom('Arguments');
            $this->addLink($argumentsId, $voidId, 'ARGUMENT');
            $this->setAtom($argumentsId, array('code'     => $this->atoms[$voidId]['code'],
                                               'fullcode' => $this->atoms[$voidId]['fullcode'],
                                               'line'     => $this->tokens[$current][2],
                                               'token'    => $this->getToken($this->tokens[$current][0])));
        }

        $nameId = $this->addAtom('Identifier');
        $this->addLink($functioncallId, $nameId, 'NAME');
        $this->setAtom($nameId, array('code'     => $this->tokens[$nameTokenId][1],
                                      'fullcode' => $this->tokens[$nameTokenId][1],
                                      'variadic' => false,
                                      'line'     => $this->tokens[$current][2],
                                      'token'    => $this->getToken($this->tokens[$current][0])));

        $this->addLink($functioncallId, $argumentsId, 'ARGUMENTS');
        $this->setAtom($functioncallId, array('code'     => $this->tokens[$nameTokenId][1],
                                              'fullcode' => $this->tokens[$nameTokenId][1].' '.$this->atoms[$argumentsId]['code'],
                                              'line'     => $this->tokens[$current][2],
                                              'token'    => $this->getToken($this->tokens[$current][0]) ));

        $this->pushExpression($functioncallId);

        return $functioncallId;
    }

    private function processExit() {
        if (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_PARENTHESIS, \Exakat\Tasks\T_SEMICOLON, \Exakat\Tasks\T_CLOSE_TAG, \Exakat\Tasks\T_CLOSE_BRACKET, \Exakat\Tasks\T_COLON))) {
            $nameId = $this->addAtom('Identifier');
            $this->setAtom($nameId, array('code'       => $this->tokens[$this->id][1],
                                          'fullcode'   => $this->tokens[$this->id][1],
                                          'variadic'   => false,
                                          'line'       => $this->tokens[$this->id][2],
                                          'token'      => $this->getToken($this->tokens[$this->id][0]),
                                          'fullnspath' => '\\'.strtolower($this->tokens[$this->id][1]) ));

            $voidId = $this->addAtomVoid();
            $this->setAtom($voidId, array('rank'  => 0));

            $argumentsId = $this->addAtom('Arguments');
            $this->addLink($argumentsId, $voidId, 'ARGUMENT');
            $this->setAtom($argumentsId, array('code'     => $this->atoms[$voidId]['code'],
                                               'fullcode' => $this->atoms[$voidId]['fullcode'],
                                               'line'     => $this->tokens[$this->id][2],
                                               'count'    => 1,
                                               'token'    => $this->getToken($this->tokens[$this->id][0])));

            $functioncallId = $this->addAtom('Functioncall');
            $this->setAtom($functioncallId, array('code'       => $this->atoms[$nameId]['code'],
                                                  'fullcode'   => $this->atoms[$nameId]['fullcode'].' '.($this->atoms[$argumentsId]['atom'] === 'Void' ? self::FULLCODE_VOID :  $this->atoms[$argumentsId]['fullcode']),
                                                  'line'       => $this->tokens[$this->id][2],
                                                  'variadic'   => false,
                                                  'token'      => $this->getToken($this->tokens[$this->id][0]),
                                                  'fullnspath' => '\\'.strtolower($this->atoms[$nameId]['code'])));
            $this->addLink($functioncallId, $argumentsId, 'ARGUMENTS');
            $this->addLink($functioncallId, $nameId, 'NAME');

            $this->pushExpression($functioncallId);

            if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
                $this->processSemicolon();
            }

            return $functioncallId;
        } else {
            --$this->id;
            $nameId = $this->processNextAsIdentifier();
            $this->pushExpression($nameId);

            if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
                $this->processSemicolon();
            } else {
                $nameId = $this->processFCOA($nameId);
            }

            return $nameId;
        }
    }

    private function processArray() {
        return $this->processString();
    }

    private function processTernary() {
        $current = $this->id;

        $conditionId = $this->popExpression();
        $ternaryId = $this->addAtom('Ternary');

        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_COLON)) ) {
            $this->processNext();
        };
        $thenId = $this->popExpression();
        ++$this->id; // Skip colon

        $finals = $this->precedence->get($this->tokens[$this->id][0]);
        $finals[] = \Exakat\Tasks\T_COLON; // Added from nested Ternary
        $finals[] = \Exakat\Tasks\T_CLOSE_TAG;

        $this->nestContext();
        do {
            $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], $finals) );
        $this->exitContext();

        $elseId = $this->popExpression();

        $this->addLink($ternaryId, $conditionId, 'CONDITION');
        $this->addLink($ternaryId, $thenId, 'THEN');
        $this->addLink($ternaryId, $elseId, 'ELSE');

        $x = array('code'     => '?',
                   'fullcode' => $this->atoms[$conditionId]['fullcode'].' ?'.($this->atoms[$thenId]['atom'] === 'Void' ? '' : ' '.$this->atoms[$thenId]['fullcode'].' ' ).': '.$this->atoms[$elseId]['fullcode'],
                   'line'     => $this->tokens[$current][2],
                   'token'    => 'T_QUESTION');
        $this->setAtom($ternaryId, $x);

        $this->pushExpression($ternaryId);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $ternaryId;
    }

    //////////////////////////////////////////////////////
    /// processing single tokens
    //////////////////////////////////////////////////////
    private function processSingle($atom) {
        $id = $this->addAtom($atom);
        if (strlen($this->tokens[$this->id][1]) > 100000) {
            $this->tokens[$this->id][1] = substr($this->tokens[$this->id][1], 0, 100000)."\n[.... 100000 / ".strlen($this->tokens[$this->id][1])."]\n";
        }
        $this->setAtom($id, array('code'     => $this->tokens[$this->id][1],
                                  'fullcode' => $this->tokens[$this->id][1],
                                  'variadic' => false,
                                  'line'     => $this->tokens[$this->id][2],
                                  'token'    => $this->getToken($this->tokens[$this->id][0]) ));
        $this->pushExpression($id);

        return $id;
    }

    private function processInlinehtml() {
        $this->processSingle('Inlinehtml');
        $this->processSemicolon();
    }

    private function processNamespaceBlock() {
        $this->startSequence();

        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_TAG, \Exakat\Tasks\T_NAMESPACE, \Exakat\Tasks\T_END))) {
            $this->processNext();

            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_NAMESPACE &&
                $this->tokens[$this->id + 2][0] === \Exakat\Tasks\T_NS_SEPARATOR) {
                $this->processNext();
            }
        };
        $blockId = $this->sequence;
        $this->endSequence();

        $this->setAtom($blockId, array('code'     => '',
                                       'fullcode' => ' '.self::FULLCODE_SEQUENCE.' ',
                                       'line'     => $this->tokens[$this->id][2],
                                       'token'    => $this->getToken($this->tokens[$this->id][0])));

        return $blockId;
    }

    private function processNamespace() {
        $current = $this->id;

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_CURLY) {
            $nameId = $this->addAtomVoid();
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_NS_SEPARATOR) {
            --$this->id;
            $nsnameId = $this->processOneNsname();
            list($fullnspath, $aliased) = $this->getFullnspath($nsnameId);
            $this->setAtom($nsnameId, array('fullnspath' => $fullnspath,
                                            'aliased'    => $aliased));
            $this->pushExpression($nsnameId);

            return $this->processFCOA($nsnameId);
        } else {
            $nameId = $this->processOneNsname();
        }
        $namespaceId = $this->addAtom('Namespace');
        $this->addLink($namespaceId, $nameId, 'NAME');
        $this->setNamespace($nameId);

        // Here, we make sure namespace is encompassing the next elements.
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_SEMICOLON) {
            // Process block
            ++$this->id; // Skip ; to start actual sequence
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_END) {
                $voidId = $this->addAtomVoid();
                $blockId = $this->addAtom('Sequence');
                $this->setAtom($blockId, array('code'       => '{}',
                                               'fullcode'   => self::FULLCODE_BLOCK,
                                               'line'       => $this->tokens[$this->id][2],
                                               'token'      => $this->getToken($this->tokens[$this->id][0]),
                                               'bracket'    => false ));
                $this->addLink($blockId, $voidId, 'ELEMENT');
            } else {
                $blockId = $this->processNamespaceBlock();
            }
            $this->addLink($namespaceId, $blockId, 'BLOCK');
            $this->addToSequence($namespaceId);
            $block = ';';
        } else {
            // Process block
            $blockId = $this->processFollowingBlock(false);
            $this->popExpression();
            $this->addLink($namespaceId, $blockId, 'BLOCK');

            $this->pushExpression($namespaceId);
            $this->processSemicolon();
            $block = self::FULLCODE_BLOCK;
        }
        $this->setNamespace(0);

        $x = array('code'       => $this->tokens[$current][1],
                   'fullcode'   => $this->tokens[$current][1].' '.$this->atoms[$nameId]['fullcode'].$block,
                   'line'       => $this->tokens[$current][2],
                   'token'      => $this->getToken($this->tokens[$current][0]),
                   'fullnspath' => $this->atoms[$nameId]['fullnspath']);
        $this->setAtom($namespaceId, $x);

        return $namespaceId;
    }

    private function processAs() {
        if (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_PRIVATE, \Exakat\Tasks\T_PUBLIC, \Exakat\Tasks\T_PROTECTED))) {
            $current = $this->id;
            $asId = $this->addAtom('As');

            $left = $this->popExpression();
            $this->addLink($asId, $left, 'NAME');

            if (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_PRIVATE, \Exakat\Tasks\T_PROTECTED, \Exakat\Tasks\T_PUBLIC))) {
                $visibilityId = $this->processNextAsIdentifier();
                $this->addLink($asId, $visibilityId, strtoupper($this->atoms[$visibilityId]['code']));
            }

            if (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_COMMA, \Exakat\Tasks\T_SEMICOLON))) {
                $aliasId = $this->processNextAsIdentifier();
                $this->addLink($asId, $aliasId, 'AS');
            } else {
                $aliasId = $this->addAtomVoid();
                $this->addLink($asId, $aliasId, 'AS');
            }

            $x = array('code'     => $this->tokens[$current][1],
                       'fullcode' => $this->atoms[$left]['fullcode'].' '.$this->tokens[$current][1].' '.(isset($visibilityId) ? $this->atoms[$visibilityId]['fullcode'].' ' : ''),
                                     $this->atoms[$aliasId]['fullcode'],
                       'line'     => $this->tokens[$current][2],
                       'token'    => $this->getToken($this->tokens[$current][0]));
            $this->setAtom($asId, $x);
            $this->pushExpression($asId);

            return $asId;
        } else {
            return $this->processOperator('As', $this->precedence->get($this->tokens[$this->id][0]), array('NAME', 'AS'));
        }
    }

    private function processInsteadof() {
        $insteadofId = $this->processOperator('Insteadof', $this->precedence->get($this->tokens[$this->id][0]), array('NAME', 'INSTEADOF'));
        while ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COMMA) {
            ++$this->id;
            $nextId = $this->processOneNsname();

            $this->addLink($insteadofId, $nextId, 'INSTEADOF');
        }
        return $insteadofId;
    }

    private function processUse() {
        $useId = $this->addAtom('Use');
        $current = $this->id;
        $useType = 'class';

        $fullcode = array();

        // use const
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CONST) {
            ++$this->id;

            $this->processSingle('Identifier');
            $constId = $this->popExpression();
            $this->addLink($useId, $constId, 'CONST');
            $useType = 'const';
        }

        // use function
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_FUNCTION) {
            ++$this->id;

            $this->processSingle('Identifier');
            $constId = $this->popExpression();
            $this->addLink($useId, $constId, 'FUNCTION');
            $useType = 'function';
        }

        --$this->id;
        do {
            ++$this->id;
            $namespaceId = $this->processOneNsname();
            // Default case : use A\B
            $aliasId = $namespaceId;
            $originId = $namespaceId;
            $fullnspath = $this->makeFullnspath($namespaceId);

            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_AS) {
                // use A\B as C
                ++$this->id;
                $this->setAtom($originId, array('fullnspath' => $this->makeFullnspath($originId)));

                $this->pushExpression($namespaceId);
                $this->processAs();
                $namespaceId = $this->popExpression();
                $aliasId = $namespaceId;

                $this->addLink($useId, $namespaceId, 'USE');

                if ($this->isContext(self::CONTEXT_CLASS) ||
                    $this->isContext(self::CONTEXT_TRAIT)) {
                    $this->addCall('class', $fullnspath, $namespaceId);
                }

                $fullcode[] = $this->atoms[$namespaceId]['fullcode'];

                $this->setAtom($namespaceId, array('fullnspath' => $fullnspath));
                if (!$this->isContext(self::CONTEXT_CLASS) &&
                    !$this->isContext(self::CONTEXT_TRAIT) ) {
                    $alias = $this->addNamespaceUse($originId, $aliasId, $useType, $namespaceId);

                    $this->setAtom($namespaceId, array('alias'  => $alias,
                                                       'origin' => $fullnspath ));
                }
            } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_CURLY) {
                //use A\B{} // Group
                $blockId = $this->processFollowingBlock(array(\Exakat\Tasks\T_CLOSE_CURLY));
                $this->popExpression();
                $this->addLink($useId, $blockId, 'BLOCK');
                $fullcode[] = $this->atoms[$namespaceId]['fullcode'].' '.$this->atoms[$blockId]['fullcode'];

                // Several namespaces ? This has to be recalculated inside the block!!
                $fullnspath = $this->makeFullnspath($namespaceId);

                $this->addLink($useId, $namespaceId, 'USE');
            } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_NS_SEPARATOR) {
                //use A\B\ {} // Prefixes, within a Class/Trait
                $this->addLink($useId, $namespaceId, 'GROUPUSE');
                $prefix = $this->makeFullnspath($namespaceId);
                if ($prefix[0] !== '\\') {
                    $prefix = '\\'.$prefix;
                }
                $prefix .= '\\';

                ++$this->id; // Skip \

                $useTypeGeneric = $useType;
                $useTypeId = 0;
                do {
                    ++$this->id; // Skip {

                    $useType = $useTypeGeneric;
                    $useTypeId = 0;
                    if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CONST) {
                        // use const
                        ++$this->id;

                        $this->processSingle('Identifier');
                        $useTypeId = $this->popExpression();
                        $useType = 'const';
                    }

                    if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_FUNCTION) {
                        // use function
                        ++$this->id;

                        $this->processSingle('Identifier');
                        $useTypeId = $this->popExpression();
                        $useType = 'function';
                    }

                    $id = $this->processOneNsname();
                    if ($useTypeId !== 0) {
                        $this->addLink($id, $useTypeId, strtoupper($useType));
                    }

                    if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_AS) {
                        // A\B as C
                        ++$this->id;
                        $this->pushExpression($id);
                        $this->processAs();
                        $aliasId = $this->popExpression();

                        $this->setAtom($id, array('fullnspath' => $prefix.strtolower($this->atoms[$id]['fullcode']),
                                                  'origin'     => $prefix.strtolower($this->atoms[$id]['fullcode']) ));
                        $this->setAtom($aliasId, array('fullnspath' => $prefix.strtolower($this->atoms[$id]['fullcode']),
                                                       'origin'     => $prefix.strtolower($this->atoms[$id]['fullcode']) ));

                        $alias = $this->addNamespaceUse($id, $aliasId, $useType, $aliasId);
                        $this->setAtom($aliasId, array('alias'      => $alias));
                        $this->addLink($useId, $aliasId, 'USE');
                    } else {
                        $this->addLink($useId, $id, 'USE');
                        $this->setAtom($id, array('fullnspath' => $prefix.strtolower($this->atoms[$id]['fullcode']),
                                                  'origin'     => $prefix.strtolower($this->atoms[$id]['fullcode'])));

                        $alias = $this->addNamespaceUse($id, $id, $useType, $aliasId);
                        $this->setAtom($id, array('alias'      => $alias));

                    }
                } while (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_COMMA)));

                $fullcode[] = $this->atoms[$namespaceId]['fullcode'].self::FULLCODE_BLOCK;

                ++$this->id; // Skip }
            } else {
                $this->addLink($useId, $namespaceId, 'USE');

                if (!$this->isContext(self::CONTEXT_CLASS) &&
                    !$this->isContext(self::CONTEXT_TRAIT) ) {

                    $fullnspath = $this->makeFullnspath($namespaceId);
                    $this->setAtom($namespaceId, array('fullnspath' => $fullnspath));
                    $this->addCall('class', $fullnspath, $namespaceId);
                } else {
                    list($fullnspath, $aliased) = $this->getFullnspath($namespaceId);
                    $this->setAtom($namespaceId, array('fullnspath' => $fullnspath,
                                                       'aliased'    => $aliased));

                    $this->addCall('class', $fullnspath, $namespaceId);
                    if ($aliased === self::ALIASED) {
                        $this->addLink($this->usesId['class'][strtolower($this->atoms[$namespaceId]['code'])], $namespaceId, 'DEFINITION');
                    }

                }

                $fullcode[] = $this->atoms[$namespaceId]['fullcode'];

                $this->setAtom($namespaceId, array('fullnspath' => $fullnspath));
                if (!$this->isContext(self::CONTEXT_CLASS) &&
                    !$this->isContext(self::CONTEXT_TRAIT) ) {
                    $alias = $this->addNamespaceUse($aliasId, $aliasId, $useType, $namespaceId);

                    $this->setAtom($namespaceId, array('alias'  => $alias,
                                                       'origin' => $fullnspath ));
                }
            }
            // No Else. Default will be dealt with by while() condition

        } while ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COMMA);

        $x = array('code'     => $this->tokens[$current][1],
                   'fullcode' => $this->tokens[$current][1].(isset($constId) ? ' '.$this->atoms[$constId]['code'] : '').' '.implode(", ", $fullcode),
                   'line'     => $this->tokens[$current][2],
                   'token'    => $this->getToken($this->tokens[$current][0]));
        $this->setAtom($useId, $x);
        $this->pushExpression($useId);

        if ($this->tokens[$this->id + 1][0] !== \Exakat\Tasks\T_SEMICOLON) {
            $this->processSemicolon();
        }

        return $useId;
    }

    private function processVariable() {
        $variableId = $this->processSingle('Variable');
        if ($this->tokens[$this->id][1] === '$this') {
            $this->addCall('class', end($this->currentClassTrait), $variableId);
        }
        $this->setAtom($variableId, array('reference' => false,
                                          'variadic'  => false,
                                          'enclosing' => false));

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        } else {
             $variableId = $this->processFCOA($variableId);
        }

        return $variableId;
    }

    private function processFCOA($id) {
        // For functions and constants
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_PARENTHESIS) {
            return $this->processFunctioncall();
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_BRACKET &&
                  $this->tokens[$this->id + 2][0] === \Exakat\Tasks\T_CLOSE_BRACKET) {
            return $this->processAppend();
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_BRACKET ||
                  $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_CURLY) {
            return $this->processBracket();
        } elseif (in_array($this->atoms[$id]['atom'], array('Nsname', 'Identifier'))) {
            list($fullnspath, $aliased) = $this->getFullnspath($id, $this->isContext(self::CONTEXT_NEW) ? 'class' : 'const');
            $this->setAtom($id, array('fullnspath' => $fullnspath,
                                      'aliased'    => $aliased));
            return $id;
        } else {
            return $id;
        }
    }

    private function processAppend() {
        $current = $this->id;
        $appendId = $this->addAtom('Arrayappend');

        $left = $this->popExpression();
        $this->addLink($appendId, $left, 'APPEND');

        $x = array('code'     => $this->tokens[$current][1],
                   'fullcode' => $this->atoms[$left]['fullcode'].'[]',
                   'line'     => $this->tokens[$current][2],
                   'token'    => $this->getToken($this->tokens[$current][0]));
        $this->setAtom($appendId, $x);
        $this->pushExpression($appendId);

        ++$this->id;
        ++$this->id;

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        } else {
            // Mostly for arrays
            $appendId = $this->processFCOA($appendId);
        }

        return $appendId;
    }

    private function processInteger() {
        $id = $this->processSingle('Integer');
        $value = $this->atoms[$id]['code'];

        if (strtolower(substr($value, 0, 2)) === '0b') {
            $actual = bindec(substr($value, 2));
        } elseif (strtolower(substr($value, 0, 2)) === '0x') {
            $actual = hexdec(substr($value, 2));
        } elseif (strtolower(substr($value, 0, 1)) === '0') {
            // PHP 7 will just stop.
            // PHP 5 will work until it fails
            $actual = octdec(substr($value, 1));
        } else {
            $actual = $value;
        }
        $this->setAtom($id, array('intval'  => (abs($actual) > PHP_INT_MAX ? 0 : $actual),
                                  'boolean' => (int) (boolean) $value));

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $id;
    }

    private function processReal() {
        $id = $this->processSingle('Real');
        $this->setAtom($id, array('boolean' => (int) (strtolower($this->tokens[$this->id][1]) != 0)));

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $id;
    }

    private function processLiteral() {
        $id = $this->processSingle('String');

        if ($this->tokens[$this->id][0] === \Exakat\Tasks\T_CONSTANT_ENCAPSED_STRING) {
            $this->setAtom($id, array('delimiter'   => $this->atoms[$id]['code'][0],
                                      'noDelimiter' => substr($this->atoms[$id]['code'], 1, -1)));
            $this->addNoDelimiterCall($id);
        } elseif ($this->tokens[$this->id][0] === \Exakat\Tasks\T_NUM_STRING) {
            $this->setAtom($id, array('delimiter'   => '',
                                      'noDelimiter' => $this->atoms[$id]['code']));
            $this->addNoDelimiterCall($id);
        } else {
            $this->setAtom($id, array('delimiter'   => '',
                                      'noDelimiter' => ''));
        }
        $this->setAtom($id, array('boolean'   => (int) (bool) $this->atoms[$id]['noDelimiter'] ));

        if (function_exists('mb_detect_encoding')) {
            $this->setAtom($id, array('encoding' => mb_detect_encoding($this->atoms[$id]['noDelimiter'])));
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_BRACKET) {
                $id = $this->processBracket();
            }
        }

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $id;
    }

    private function processMagicConstant() {
        return $this->processSingle('Magicconstant');
    }

    //////////////////////////////////////////////////////
    /// processing single operators
    //////////////////////////////////////////////////////
    private function processSingleOperator($atom, $finals, $link, $separator = '') {
        $current = $this->id;

        $operatorId = $this->addAtom($atom);
        $this->nestContext();
        // Do while, so that AT least one loop is done.
        do {
            $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], $finals));
        $this->exitContext();

        $operandId = $this->popExpression();
        $this->addLink($operatorId, $operandId, $link);

        $x = array('code'     => $this->tokens[$current][1],
                   'fullcode' => $this->tokens[$current][1].$separator.$this->atoms[$operandId]['fullcode'],
                   'line'     => $this->tokens[$current][2],
                   'token'    => $this->getToken($this->tokens[$current][0]));

        $this->setAtom($operatorId, $x);
        $this->pushExpression($operatorId);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $operatorId;
    }

    private function processCast() {
        return $this->processSingleOperator('Cast', $this->precedence->get($this->tokens[$this->id][0]), 'CAST', ' ');
    }

    private function processReturn() {
        if (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_TAG, \Exakat\Tasks\T_SEMICOLON))) {
            $current = $this->id;

            // Case of return ;
            $returnId = $this->addAtom('Return');

            $returnArgId = $this->addAtomVoid();
            $this->addLink($returnId, $returnArgId, 'RETURN');

            $x = array('code'     => $this->tokens[$current][1],
                       'fullcode' => $this->tokens[$current][1].' ;',
                       'line'     => $this->tokens[$current][2],
                       'token'    => $this->getToken($this->tokens[$current][0]));
            $this->setAtom($returnId, $x);

            $this->pushExpression($returnId);
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
                $this->processSemicolon();
            }

            return $returnId;
        } else {
            return $this->processSingleOperator('Return', $this->precedence->get($this->tokens[$this->id][0]), 'RETURN', ' ');
        }
    }

    private function processThrow() {
        return $this->processSingleOperator('Throw', $this->precedence->get($this->tokens[$this->id][0]), 'THROW', ' ');
    }

    private function processYield() {
        if (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_PARENTHESIS, \Exakat\Tasks\T_SEMICOLON, \Exakat\Tasks\T_CLOSE_TAG))) {
            $current = $this->id;

            // Case of return ;
            $returnArgId = $this->addAtomVoid();
            $returnId = $this->addAtom('Yield');

            $this->addLink($returnId, $returnArgId, 'YIELD');

            $x = array('code'     => $this->tokens[$current][1],
                       'fullcode' => $this->tokens[$current][1].' ;',
                       'line'     => $this->tokens[$current][2],
                       'token'    => $this->getToken($this->tokens[$current][0]));
            $this->setAtom($returnId, $x);

            $this->addToSequence($returnId);

            return $returnId;
        } else {
            return $this->processSingleOperator('Yield', $this->precedence->get($this->tokens[$this->id][0]), 'YIELD', ' ');
        }
    }

    private function processYieldfrom() {
        return $this->processSingleOperator('Yieldfrom', $this->precedence->get($this->tokens[$this->id][0]), 'YIELD', ' ');
    }

    private function processNot() {
        return $this->processSingleOperator('Not', $this->precedence->get($this->tokens[$this->id][0]), 'NOT');
    }

    private function processCurlyExpression() {
        ++$this->id;
        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_CURLY))) {
            $this->processNext();
        };

        $codeId = $this->popExpression();
        $blockId = $this->addAtom('Block');
        $this->setAtom($blockId, array('code'     => '{}',
                                       'fullcode' => '{'.$this->atoms[$codeId]['fullcode'].'}',
                                       'line'     => $this->tokens[$this->id][2],
                                       'token'    => $this->getToken($this->tokens[$this->id][0])));
        $this->addLink($blockId, $codeId, 'CODE');
        $this->pushExpression($blockId);

        ++$this->id; // Skip }

        return $blockId;
    }

    private function processDollar() {
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_CURLY) {
            $current = $this->id;

            $variableId = $this->addAtom('Variable');

            ++$this->id;
            while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_CURLY)) ) {
                $id = $this->processNext();
            };

            // Skip }
            ++$this->id;

            $expressionId = $this->popExpression();
            $this->addLink($variableId, $expressionId, 'NAME');

            $x = array('code'     => $this->tokens[$current][1],
                       'fullcode' => $this->tokens[$current][1].'{'.$this->atoms[$expressionId]['fullcode'].'}',
                       'variadic' => false,
                       'line'     => $this->tokens[$current][2],
                       'token'    => $this->getToken($this->tokens[$current][0]));
            $this->setAtom($variableId, $x);

            $this->pushExpression($variableId);

            if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
                $this->processSemicolon();
            }

            return $this->processFCOA($variableId);
        } else {
            $this->nestContext();
            $id = $this->processSingleOperator('Variable', $this->precedence->get($this->tokens[$this->id][0]), 'NAME');
            $this->setAtom($id, array('variadic' => false));
            $this->exitContext();

            if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
                $this->processSemicolon();
            }

            return $id;
        }
    }

    private function processClone() {
        return $this->processSingleOperator('Clone', $this->precedence->get($this->tokens[$this->id][0]), 'CLONE', ' ' );
    }

    private function processGoto() {
        return $this->processSingleOperator('Goto', $this->precedence->get($this->tokens[$this->id][0]), 'GOTO');
    }

    private function processNoscream() {
        return $this->processSingleOperator('Noscream', $this->precedence->get($this->tokens[$this->id][0]), 'AT');
    }

    private function processNew() {
        $this->toggleContext(self::CONTEXT_NEW);
        $id =  $this->processSingleOperator('New', $this->precedence->get($this->tokens[$this->id][0]), 'NEW', ' ');

        if ($this->atoms[$id + 1]['atom'] === 'Nsname') {
            list($fullnspath, $aliased) = $this->getFullnspath($id + 1);
            if ($aliased === self::ALIASED) {
                $this->addLink($this->usesId['class'][strtolower($this->atoms[$id + 1]['code'])], max(array_keys($this->atoms)), 'DEFINITION');
            }
            // max(array_keys($this->atoms)) is the actual Functioncall
            $this->setAtom(max(array_keys($this->atoms)), array('fullnspath' => $fullnspath,
                                                                'aliased'    => $aliased));
            $this->addCall('class', $fullnspath, max(array_keys($this->atoms)));
        } elseif ( !empty($this->atoms[$id + 2]['atom']) &&
                   $this->atoms[$id + 2]['atom'] === 'Nsname') {
            list($fullnspath, $aliased) = $this->getFullnspath($id + 2);
            if ($aliased === self::ALIASED) {
                $this->addLink($this->usesId['class'][strtolower($this->atoms[$id + 2]['code'])], max(array_keys($this->atoms)), 'DEFINITION');
            }
            // max(array_keys($this->atoms)) is the actual Functioncall
            $this->setAtom(max(array_keys($this->atoms)), array('fullnspath' => $fullnspath,
                                                                'aliased'    => $aliased));
            $this->addCall('class', $fullnspath, max(array_keys($this->atoms)));
        } elseif ($this->atoms[$id + 1]['atom'] === 'Identifier') {
            list($fullnspath, $aliased) = $this->getFullnspath($id + 1);
            if ($aliased === self::ALIASED) {
                $this->addLink($this->usesId['class'][strtolower($this->atoms[$id + 1]['code'])], max(array_keys($this->atoms)), 'DEFINITION');
            }

            // max(array_keys($this->atoms)) is the actual Functioncall
            $this->setAtom(max(array_keys($this->atoms)), array('fullnspath' => $fullnspath,
                                                                'aliased'    => $aliased));
            $this->addCall('class', $fullnspath, max(array_keys($this->atoms)));
        }

        $this->toggleContext(self::CONTEXT_NEW);

        return $id;
    }

    //////////////////////////////////////////////////////
    /// processing binary operators
    //////////////////////////////////////////////////////
    private function processSign() {
        $sign = $this->tokens[$this->id][1];
        $code = $sign.'1';
        while (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_PLUS, \Exakat\Tasks\T_MINUS))) {
            ++$this->id;
            $sign = $this->tokens[$this->id][1].$sign;
            $code *= $this->tokens[$this->id][1].'1';
        }

        // -3 ** 3 => -(3 ** 3)
        if (($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_LNUMBER || $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_DNUMBER) &&
            $this->tokens[$this->id + 2][0] !== \Exakat\Tasks\T_POW) {
            $operandId = $this->processNext();

            $x = array('code'     => $sign.$this->atoms[$operandId]['code'],
                       'fullcode' => $sign.$this->atoms[$operandId]['fullcode'],
                       'line'     => $this->tokens[$this->id][2],
                       'token'    => $this->getToken($this->tokens[$this->id][0]));
            $this->setAtom($operandId, $x);

            return $operandId;
        } else {
            $finals = $this->precedence->get($this->tokens[$this->id][0]);
            // process the actual load
            $this->nestContext();
            do {
                $this->processNext();
            } while (!in_array($this->tokens[$this->id + 1][0], $finals)) ;
            $this->exitContext();

            $signedId = $this->popExpression();

            for($i = strlen($sign) - 1; $i >= 0; --$i) {
                $signId = $this->addAtom('Sign');
                $this->addLink($signId, $signedId, 'SIGN');

                $x = array('code'     => $sign[$i] ,
                           'fullcode' => $sign[$i].$this->atoms[$signedId]['fullcode'],
                           'line'     => $this->tokens[$this->id][2],
                           'token'    => $this->getToken($this->tokens[$this->id][0]));
                $this->setAtom($signId, $x);

                $signedId = $signId;
            }

            $this->pushExpression($signId);

            if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
                $this->processSemicolon();
            }
            return $signId;
        }
    }

    private function processAddition() {
        if (!$this->hasExpression()) {
            return $this->processSign();
        }
        $left = $this->popExpression();

        $atom   = 'Addition';
        $current = $this->id;

        $finals = $this->precedence->get($this->tokens[$this->id][0]);

        $additionId = $this->addAtom($atom);
        $this->addLink($additionId, $left, 'LEFT');

        $this->nestContext();
        do {
            $this->processNext();

            if (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_EQUAL, \Exakat\Tasks\T_PLUS_EQUAL, \Exakat\Tasks\T_AND_EQUAL, \Exakat\Tasks\T_CONCAT_EQUAL, \Exakat\Tasks\T_DIV_EQUAL, \Exakat\Tasks\T_MINUS_EQUAL, \Exakat\Tasks\T_MOD_EQUAL, \Exakat\Tasks\T_MUL_EQUAL, \Exakat\Tasks\T_OR_EQUAL, \Exakat\Tasks\T_POW_EQUAL, \Exakat\Tasks\T_SL_EQUAL, \Exakat\Tasks\T_SR_EQUAL, \Exakat\Tasks\T_XOR_EQUAL))) {
                $this->processNext();
            }
        } while (!in_array($this->tokens[$this->id + 1][0], $finals)) ;
        $this->exitContext();

        $right = $this->popExpression();

        $this->addLink($additionId, $right, 'RIGHT');

        $x = array('code'     => $this->tokens[$current][1],
                   'fullcode' => $this->atoms[$left]['fullcode'].' '.$this->tokens[$current][1].' '.$this->atoms[$right]['fullcode'],
                   'line'     => $this->tokens[$current][2],
                   'token'    => $this->getToken($this->tokens[$current][0]));
        $this->setAtom($additionId, $x);
        $this->pushExpression($additionId);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $additionId;
    }

    private function processBreak() {
        $current = $this->id;
        $breakId = $this->addAtom($this->tokens[$this->id][0] === \Exakat\Tasks\T_BREAK ? 'Break' : 'Continue');

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_LNUMBER) {
            $this->processNext();

            $breakLevel = $this->popExpression();
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_PARENTHESIS) {
            ++$this->id; // skip (
            $this->processNext();
            ++$this->id; // skip )

            $breakLevel = $this->popExpression();
        } else {
            $breakLevel = $this->addAtomVoid();
        }

        $this->addLink($breakId, $breakLevel, $this->tokens[$current][0] === \Exakat\Tasks\T_BREAK ? 'BREAK' : 'CONTINUE');
        $x = array('code'     => $this->tokens[$current][1],
                   'fullcode' => $this->tokens[$current][1].( $this->atoms[$breakLevel]['atom'] !== 'Void' ?  ' '.$this->atoms[$breakLevel]['fullcode'] : ''),
                   'line'     => $this->tokens[$current][2],
                   'token'    => $this->getToken($this->tokens[$current][0]) );
        $this->setAtom($breakId, $x);
        $this->pushExpression($breakId);

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $breakId;
    }

    private function processDoubleColon() {
        $current = $this->id;

        $leftId = $this->popExpression();

        $finals = $this->precedence->get($this->tokens[$this->id][0]);
        $finals[] = \Exakat\Tasks\T_DOUBLE_COLON;

        $this->nestContext();
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_CURLY) {
            $blockId = $this->processCurlyExpression();
            $right = $this->processFCOA($blockId);
            $this->popExpression();
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_DOLLAR) {
            ++$this->id; // Skip ::
            $blockId = $this->processDollar();
            $right = $this->processFCOA($blockId);
            $this->popExpression();
        } else {
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_VARIABLE) {
                ++$this->id;
                $this->processSingle('Variable');
                $right = $this->popExpression();
            } else {
                $right = $this->processNextAsIdentifier();
            }

            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_PARENTHESIS) {
                $this->pushExpression($right);
                $right = $this->processFunctioncall();
                $this->popExpression();
            }
        }
        $this->exitContext();

        if ($this->atoms[$right]['token'] === 'T_CLASS') {
            $staticId = $this->addAtom('Staticclass');
            $links = 'CLASS';
        } elseif ($this->atoms[$right]['atom'] === 'Identifier') {
            $staticId = $this->addAtom('Staticconstant');
            $links = 'CONSTANT';
        } elseif (in_array($this->atoms[$right]['atom'], array('Variable', 'Array', 'Arrayappend', 'MagicConstant', 'Concatenation', 'Block', 'Boolean', 'Null'))) {
            $staticId = $this->addAtom('Staticproperty');
            $links = 'PROPERTY';
        } elseif (in_array($this->atoms[$right]['atom'], array('Functioncall'))) {
            $staticId = $this->addAtom('Staticmethodcall');
            $links = 'METHOD';
        } else {
            throw new LoadError("Unprocessed atom in static call (right) : ".$this->atoms[$right]['atom']."\n");
        }

        $this->addLink($staticId, $leftId, 'CLASS');
        $this->addLink($staticId, $right, $links);

        $x = array('code'     => $this->tokens[$current][1],
                   'fullcode' => $this->atoms[$leftId]['fullcode'].'::'.$this->atoms[$right]['fullcode'],
                   'line'     => $this->tokens[$current][2],
                   'variadic' => false,
                   'token'    => $this->getToken($this->tokens[$current][0]));

        $this->setAtom($staticId, $x);
        $this->pushExpression($staticId);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        } else {
            $staticId = $this->processFCOA($staticId);
        }

        return $staticId;
    }

    private function processOperator($atom, $finals, $links = array('LEFT', 'RIGHT')) {
        $current = $this->id;
        $additionId = $this->addAtom($atom);

        $left = $this->popExpression();
        $this->addLink($additionId, $left, $links[0]);

        $this->nestContext();
        $finals = array_merge(array(), $finals);
        do {
            $this->processNext();

            if (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_EQUAL, \Exakat\Tasks\T_PLUS_EQUAL, \Exakat\Tasks\T_AND_EQUAL, \Exakat\Tasks\T_CONCAT_EQUAL, \Exakat\Tasks\T_DIV_EQUAL, \Exakat\Tasks\T_MINUS_EQUAL, \Exakat\Tasks\T_MOD_EQUAL, \Exakat\Tasks\T_MUL_EQUAL, \Exakat\Tasks\T_OR_EQUAL, \Exakat\Tasks\T_POW_EQUAL, \Exakat\Tasks\T_SL_EQUAL, \Exakat\Tasks\T_SR_EQUAL, \Exakat\Tasks\T_XOR_EQUAL))) {
                $this->processNext();
            }
        } while (!in_array($this->tokens[$this->id + 1][0], $finals) );
        $this->exitContext();

        $right = $this->popExpression();

        $this->addLink($additionId, $right, $links[1]);

        $x = array('code'     => $this->tokens[$current][1],
                   'fullcode' => $this->atoms[$left]['fullcode'].' '.$this->tokens[$current][1].' '.$this->atoms[$right]['fullcode'],
                   'line'     => $this->tokens[$current][2],
                   'token'    => $this->getToken($this->tokens[$current][0]));
        $this->setAtom($additionId, $x);
        $this->pushExpression($additionId);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $additionId;
    }

    private function processObjectOperator() {
        $current = $this->id;

        $left = $this->popExpression();

        $this->nestContext();
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_CURLY) {
            $blockId = $this->processCurlyExpression();
            $right = $this->processFCOA($blockId);
            $this->popExpression();
        } else {
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_VARIABLE) {
                ++$this->id;
                $this->processSingle('Variable');
                $right = $this->popExpression();
            } else {
                $right = $this->processNextAsIdentifier();
            }

            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_PARENTHESIS) {
                $this->pushExpression($right);
                $right = $this->processFunctioncall();
                $this->popExpression();
            }
        }
        $this->exitContext();

        if (in_array($this->atoms[$right]['atom'], array('Variable', 'Array', 'Identifier', 'Concatenation', 'Arrayappend', 'Property', 'MagicConstant', 'Block', 'Boolean', 'Null'))) {
            $staticId = $this->addAtom('Property');
            $links = 'PROPERTY';
            $this->setAtom($staticId, array('enclosing' => false));
        } elseif (in_array($this->atoms[$right]['atom'], array('Functioncall', 'Methodcall'))) {
            $staticId = $this->addAtom('Methodcall');
            $links = 'METHOD';
        } else {
            throw new LoadError("Unprocessed atom in object call (right) : ".$this->atoms[$right]['atom']."\n");
        }

        $this->addLink($staticId, $left, 'OBJECT');
        $this->addLink($staticId, $right, $links);

        $x = array('code'      => $this->tokens[$current][1],
                   'fullcode'  => $this->atoms[$left]['fullcode'].'->'.$this->atoms[$right]['fullcode'],
                   'variadic'  => false,
                   'reference' => false,
                   'line'      => $this->tokens[$current][2],
                   'token'     => $this->getToken($this->tokens[$current][0]));

        $this->setAtom($staticId, $x);
        $this->pushExpression($staticId);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        } else {
            $staticId = $this->processFCOA($staticId);
        }

        return $staticId;
    }


    private function processAssignation() {
        $finals = $this->precedence->get($this->tokens[$this->id][0]);
        $finals = array_merge($finals, array(\Exakat\Tasks\T_EQUAL, \Exakat\Tasks\T_PLUS_EQUAL, \Exakat\Tasks\T_AND_EQUAL, \Exakat\Tasks\T_CONCAT_EQUAL, \Exakat\Tasks\T_DIV_EQUAL, \Exakat\Tasks\T_MINUS_EQUAL, \Exakat\Tasks\T_MOD_EQUAL, \Exakat\Tasks\T_MUL_EQUAL, \Exakat\Tasks\T_OR_EQUAL, \Exakat\Tasks\T_POW_EQUAL, \Exakat\Tasks\T_SL_EQUAL, \Exakat\Tasks\T_SR_EQUAL, \Exakat\Tasks\T_XOR_EQUAL));
        $this->processOperator('Assignation', $finals);

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }
    }

    private function processCoalesce() {
        $this->processOperator('Coalesce', $this->precedence->get($this->tokens[$this->id][0]));
    }

    private function processEllipsis() {
        // Simply skipping the ...
        $finals = $this->precedence->get(\Exakat\Tasks\T_ELLIPSIS);
        while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
            $this->processNext();
        };

        $operandId = $this->popExpression();
        $x = array('fullcode'  => '...'.$this->atoms[$operandId]['fullcode'],
                   'variadic'  => true);
        $this->setAtom($operandId, $x);

        $this->pushExpression($operandId);

        return $operandId;
    }

    private function processAnd() {
        if ($this->hasExpression()) {
            return $this->processOperator('Logical', $this->precedence->get($this->tokens[$this->id][0]));
        } else {
            $current = $this->id;

            // Simply skipping the &
            $this->processNext();

            $operandId = $this->popExpression();
            $x = array('fullcode'  => '&'.$this->atoms[$operandId]['fullcode'],
                       'reference' => true);
            $this->setAtom($operandId, $x);

            $this->pushExpression($operandId);

            return $operandId;
        }
    }

    private function processLogical() {
        $this->processOperator('Logical', $this->precedence->get($this->tokens[$this->id][0]));
    }

    private function processMultiplication() {
        $this->processOperator('Multiplication', $this->precedence->get($this->tokens[$this->id][0]));
    }

    private function processPower() {
        $this->processOperator('Power', $this->precedence->get($this->tokens[$this->id][0]));
    }

    private function processComparison() {
        $this->processOperator('Comparison', $this->precedence->get($this->tokens[$this->id][0]));
    }

    private function processDot() {
        $current = $this->id;
        $concatenationId = $this->addAtom('Concatenation');
        $fullcode= array();
        $rank = -1;

        $containsId = $this->popExpression();
        $this->addLink($concatenationId, $containsId, 'CONCAT');
        $this->setAtom($containsId, array('rank' => ++$rank));
        $fullcode[] = $this->atoms[$containsId]['fullcode'];

        $this->nestContext();
        $finals = $this->precedence->get($this->tokens[$this->id][0]);
        $id = array_search(\Exakat\Tasks\T_REQUIRE, $finals);
        unset($finals[$id]);
        $id = array_search(\Exakat\Tasks\T_REQUIRE_ONCE, $finals);
        unset($finals[$id]);
        $id = array_search(\Exakat\Tasks\T_INCLUDE, $finals);
        unset($finals[$id]);
        $id = array_search(\Exakat\Tasks\T_INCLUDE_ONCE, $finals);
        unset($finals[$id]);
        while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
            $this->processNext();

            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_DOT) {
                $containsId = $this->popExpression();
                $this->addLink($concatenationId, $containsId, 'CONCAT');
                $fullcode[] = $this->atoms[$containsId]['fullcode'];
                $this->setAtom($containsId, array('rank' => ++$rank));

                ++$this->id;
            }
        }
        $this->exitContext();

        $containsId = $this->popExpression();
        $this->addLink($concatenationId, $containsId, 'CONCAT');
        $this->setAtom($containsId, array('rank' => ++$rank));
        $fullcode[] = $this->atoms[$containsId]['fullcode'];

        $x = array('code'     => $this->tokens[$current][1],
                   'fullcode' => implode(' . ', $fullcode),
                   'line'     => $this->tokens[$current][2],
                   'token'    => $this->getToken($this->tokens[$current][0]),
                   'count'    => $rank);
        $this->setAtom($concatenationId, $x);
        $this->pushExpression($concatenationId);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $concatenationId;
    }

    private function processInstanceof() {
        $current = $this->id;
        $instanceId = $this->addAtom('Instanceof');

        $left = $this->popExpression();
        $this->addLink($instanceId, $left, 'VARIABLE');

        $finals = array_merge(array(),  $this->precedence->get($this->tokens[$this->id][0]));
        do {
            $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], $finals));

        $right = $this->popExpression();

        $this->addLink($instanceId, $right, 'CLASS');
        list($fullnspath, $aliased) = $this->getFullnspath($right, 'class');
        $this->setAtom($right, array('fullnspath' => $fullnspath,
                                     'aliased'    => $aliased));
        $this->addCall('class', $this->atoms[$right]['fullnspath'], $right);
        if ($aliased === self::ALIASED) {
            $this->addLink($this->usesId['class'][strtolower($this->atoms[$right]['code'])], $right, 'DEFINITION');
        }

        $x = array('code'     => $this->tokens[$current][1],
                   'fullcode' => $this->atoms[$left]['fullcode'].' '.$this->tokens[$current][1].' '.$this->atoms[$right]['fullcode'],
                   'line'     => $this->tokens[$current][2],
                   'token'    => $this->getToken($this->tokens[$current][0]));
        $this->setAtom($instanceId, $x);
        $this->pushExpression($instanceId);

        return $instanceId;
    }

    private function processKeyvalue() {
        return $this->processOperator('Keyvalue', $this->precedence->get($this->tokens[$this->id][0]), array('KEY', 'VALUE'));
    }

    private function processBitshift() {
        $this->processOperator('Bitshift', $this->precedence->get($this->tokens[$this->id][0]));
    }

    private function processEcho() {
        $current = $this->id;
        --$this->id;
        $nameId = $this->processNextAsIdentifier();

        $argumentsId = $this->processArguments(array(\Exakat\Tasks\T_SEMICOLON, \Exakat\Tasks\T_CLOSE_TAG, \Exakat\Tasks\T_END));

        $functioncallId = $this->addAtom('Functioncall');
        list($fullnspath, $aliased) = $this->getFullnspath($nameId);
        $this->setAtom($functioncallId, array('code'       => $this->tokens[$current][1],
                                              'fullcode'   => $this->tokens[$current][1].' '.$this->atoms[$argumentsId]['fullcode'],
                                              'variadic'   => false,
                                              'reference'  => false,
                                              'line'       => $this->tokens[$current][2],
                                              'token'      => $this->getToken($this->tokens[$current][0]),
                                              'fullnspath' => $fullnspath,
                                              'aliased'    => $aliased));
        $this->addLink($functioncallId, $argumentsId, 'ARGUMENTS');
        $this->addLink($functioncallId, $nameId, 'NAME');

        $this->pushExpression($functioncallId);

        // processArguments goes too far, up to ;
        --$this->id;
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $functioncallId;
    }

    private function processHalt() {
        $haltId = $this->addAtom('Halt');
        $this->setAtom($haltId, array('code'     => $this->tokens[$this->id][1],
                                      'fullcode' => $this->tokens[$this->id][1],
                                      'line'     => $this->tokens[$this->id][2],
                                      'token'    => $this->getToken($this->tokens[$this->id][0]) ));

        ++$this->id; // skip halt
        ++$this->id; // skip (
        // Skipping all arguments. This is not a function!

        $this->pushExpression($haltId);
        ++$this->id; // skip (
        $this->processSemicolon();

        return $haltId;
    }

    private function processPrint() {
        if (in_array($this->tokens[$this->id][0], array(\Exakat\Tasks\T_INCLUDE, \Exakat\Tasks\T_INCLUDE_ONCE, \Exakat\Tasks\T_REQUIRE, \Exakat\Tasks\T_REQUIRE_ONCE))) {
            $nameId = $this->addAtom('Include');
        } else {
            $nameId = $this->addAtom('Identifier');
        }
        $this->setAtom($nameId, array('code'      => $this->tokens[$this->id][1],
                                      'fullcode'  => $this->tokens[$this->id][1],
                                      'variadic'  => false,
                                      'reference' => false,
                                      'line'      => $this->tokens[$this->id][2],
                                      'token'     => $this->getToken($this->tokens[$this->id][0]) ));

        $argumentsId = $this->addAtom('Arguments');

        $fullcode = array();
        $finals = $this->precedence->get($this->tokens[$this->id][0]);
        while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
            $this->processNext();
        };

        $indexId = $this->popExpression();
        $this->setAtom($indexId, array('rank' => 0));
        $this->addLink($argumentsId, $indexId, 'ARGUMENT');
        $fullcode[] = $this->atoms[$indexId]['fullcode'];

        $this->setAtom($argumentsId, array('code'     => $this->tokens[$this->id][1],
                                           'fullcode' => implode(', ', $fullcode),
                                           'line'     => $this->tokens[$this->id][2],
                                           'count'    => 1,
                                           'token'    => $this->getToken($this->tokens[$this->id][0])));

        $functioncallId = $this->addAtom('Functioncall');
        $this->setAtom($functioncallId, array('code'       => $this->atoms[$nameId]['code'],
                                              'fullcode'   => $this->atoms[$nameId]['code'].' '.$this->atoms[$argumentsId]['fullcode'],
                                              'variadic'   => false,
                                              'line'       => $this->atoms[$nameId]['line'],
                                              'token'      => $this->atoms[$nameId]['token'],
                                              'fullnspath' => '\\'.strtolower($this->atoms[$nameId]['code'])));
        $this->addLink($functioncallId, $argumentsId, 'ARGUMENTS');
        $this->addLink($functioncallId, $nameId, 'NAME');

        $this->pushExpression($functioncallId);

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }
        return $functioncallId;
    }

    //////////////////////////////////////////////////////
    /// generic methods
    //////////////////////////////////////////////////////
    private function addAtom($atom) {
        ++$this->atomCount;
        $this->atoms[$this->atomCount] = array('id'   => $this->atomCount,
                                          'atom' => $atom);
        return $this->atomCount;
    }

    private function addAtomVoid() {
        $id = $this->addAtom('Void');
        $this->setAtom($id, array('code'       => 'Void',
                                  'fullcode'   => self::FULLCODE_VOID,
                                  'line'       => $this->tokens[$this->id][2],
                                  'token'      => \Exakat\Tasks\T_VOID,
                                  'fullnspath' => '\\'));

        return $id;
    }

    private function setAtom($atomId, $properties) {
        foreach($properties as $k => $v) {
            $this->atoms[$atomId][$k] = $v;
        }
        return true;
    }

    private function addLink($origin, $destination, $label) {
        $o = $this->atoms[$origin]['atom'];
        $d = $this->atoms[$destination]['atom'];

        if (!isset($this->links[$label]))         { $this->links[$label]= array(); }
        if (!isset($this->links[$label][$o]))     { $this->links[$label][$o]= array(); }
        if (!isset($this->links[$label][$o][$d])) { $this->links[$label][$o][$d]= array(); }

        $this->links[$label][$o][$d]["$origin-$destination"] = array('origin'      => $origin,
                                                                     'destination' => $destination);
        return true;
    }

    private function pushExpression($id) {
        $this->expressions[] = $id;
    }

    private function hasExpression() {
        return count($this->expressions) > 0;
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
        if (count($this->expressions) > 0) {
            throw new LoadError("Warning : expression is not empty in $filename\n");
        }

        if ($this->contexts[self::CONTEXT_NOSEQUENCE] > 0) {
            throw new LoadError("Warning : context for sequence is not back to 0 in $filename : it is ".$this->contexts[self::CONTEXT_NOSEQUENCE]."\n");
        }

        // All node has one incoming or one outgoing link (outgoing or incoming).
        $O = $D= array();
        foreach($this->links as $label => $origins) {
            if ($label === 'DEFINITION') { continue; }
            foreach($origins as $origin => $destinations) {
                foreach($destinations as $destination => $links) {
                    foreach($links as $link) {
                        $O[] = $link['origin'];
                        $D[] = $link['destination'];
                    }
                }
            }
        }

        $O = array_count_values($O);
        $D = array_count_values($D);

        $total = 0;
        foreach($this->atoms as $id => $atom) {
            if ($id === 1) { continue; }
            if (!isset($D[$id])) {
                print "Warning : forgotten atom $id in $this->filename : \n";
                print_r($atom);
                print "\n";
                ++$total;
            } elseif ($D[$id] > 1) {
                print "Warning : too linked atom $id : \n";
                print_r($atom);
                print "\n";
                ++$total;
            }

            if (!isset($atom['line'])) {
                print "Warning : missing line atom $id : \n";
                print_r($atom);
                print "\n";
                ++$total;
            }

            if (!isset($atom['code'])) {
                print "Warning : code atom $id : \n";
                print_r($atom);
                print "\n";
                ++$total;
            }

            if (!isset($atom['token'])) {
                print "Warning : token atom $id : \n";
                print_r($atom);
                print "\n";
                ++$total;
            }
        }
    }

    private function processDefineAsConstants($argumentsId) {
        list($fullnspath, $aliased) = $this->getFullnspath($this->argumentsId[0]);

        $this->addDefinition('const', $fullnspath, $argumentsId);
    }

    private function saveFiles() {
        self::$client->saveFiles($this->exakatDir, $this->atoms, $this->links, $this->id0);
        $this->saveDefinitions();
        $this->reset();
    }

    private function saveDefinitions() {
        $begin = microtime(true);
        // Fallback to global if local namespace function doesn't exists
        if (isset($this->calls['function'])) {
            $this->fallbackToGlobal('function');
        }
        if (isset($this->calls['constant'])) {
            $this->fallbackToGlobal('constant');
        }

        static::$client->saveDefinitions($this->exakatDir, $this->calls);

        $end = microtime(true);
        $this->log->log("saveDefinitions\t".(($end - $begin) * 1000)."\t".count($this->calls)."\n");
    }

    private function fallbackToGlobal($type) {
        foreach($this->calls[$type] as $fnp => &$usage) {
            if (substr_count($fnp, '\\') < 2) {
                continue;
            }
            if (!empty($usage['definitions'])) {
                continue;
            }
            $foo = explode('\\', $fnp);
            $globalFnp = '\\'.array_pop($foo);
            if (!isset($this->calls[$type][$globalFnp])) {
                continue;
            }
            if (empty($this->calls[$type][$globalFnp]['definitions'])) {
                continue;
            }

            $usage['definitions'] = $this->calls[$type][$globalFnp]['definitions'];
        }
    }

    private function startSequence() {
        $this->sequence = $this->addAtom('Sequence');
        $this->setAtom($this->sequence, array('code'     => ';',
                                              'fullcode' => ' '.self::FULLCODE_SEQUENCE.' ',
                                              'line'     => $this->tokens[$this->id][2],
                                              'token'    => 'T_SEMICOLON',
                                              'bracket'  => false));

        $this->sequences[]    = $this->sequence;
        $this->sequenceRank[] = -1;
        $this->sequenceCurrentRank = count($this->sequenceRank) - 1;
    }

    private function addToSequence($id) {
        $this->addLink($this->sequence, $id, 'ELEMENT');
        $this->setAtom($id, array('rank' => ++$this->sequenceRank[$this->sequenceCurrentRank]));
    }

    private function endSequence() {
        $this->setAtom($this->sequence, array('count' => $this->sequenceRank[$this->sequenceCurrentRank] + 1));

        array_pop($this->sequences);
        array_pop($this->sequenceRank);
        $this->sequenceCurrentRank = count($this->sequenceRank) - 1;

        if (!empty($this->sequences)) {
            $this->sequence = $this->sequences[count($this->sequences) - 1];
        } else {
            $this->sequence = null;
        }
    }

    private function getToken($token) {
        return $this->php->getTokenName($token);
    }

    private function getFullnspath($nameId, $type = 'class') {

        // Handle static, self, parent and PHP natives function
        if (isset($this->atoms[$nameId]['absolute']) && ($this->atoms[$nameId]['absolute'] === true)) {
            return array(strtolower($this->atoms[$nameId]['fullcode']), self::NOT_ALIASED);
        } elseif (!in_array($this->atoms[$nameId]['atom'], array('Nsname', 'Identifier', 'String', 'Null', 'Boolean'))) {
            // No fullnamespace for non literal namespaces
            return array('', self::NOT_ALIASED);
        } elseif (in_array($this->atoms[$nameId]['token'], array('T_STATIC', 'T_ARRAY', 'T_EVAL', 'T_ISSET', 'T_EXIT', 'T_UNSET', 'T_ECHO', 'T_PRINT', 'T_LIST', 'T_EMPTY'))) {
            // For language structures, it is always in global space, like eval or list
            return array('\\'.strtolower($this->atoms[$nameId]['code']), self::NOT_ALIASED);
        } elseif (strtolower(substr($this->atoms[$nameId]['fullcode'], 0, 9)) === 'namespace') {
            // namespace\A\B
            return array(substr($this->namespace, 0, -1).strtolower(substr($this->atoms[$nameId]['fullcode'], 9)), self::NOT_ALIASED);
        } elseif (in_array($this->atoms[$nameId]['atom'], array('Identifier', 'Boolean', 'Null'))) {
            // This is an identifier, self or parent
            if (strtolower($this->atoms[$nameId]['code']) === 'self'   ||
                strtolower($this->atoms[$nameId]['code']) === 'parent') {
                return array('\\'.strtolower($this->atoms[$nameId]['code']), self::NOT_ALIASED);

                // This is an identifier
            } elseif ($type === 'class' && isset($this->uses['class'][strtolower($this->atoms[$nameId]['code'])])) {
                $this->addLink($this->usesId['class'][strtolower($this->atoms[$nameId]['code'])], $nameId, 'DEFINITION');
                return array($this->uses['class'][strtolower($this->atoms[$nameId]['code'])], self::ALIASED);
            } elseif ($type === 'const' && isset($this->uses['const'][strtolower($this->atoms[$nameId]['code'])])) {
                $this->addLink($this->usesId['const'][strtolower($this->atoms[$nameId]['code'])], $nameId, 'DEFINITION');
                return array($this->uses['const'][strtolower($this->atoms[$nameId]['code'])], self::ALIASED);
            } elseif ($type === 'function' && isset($this->uses['function'][strtolower($this->atoms[$nameId]['code'])])) {
                $this->addLink($this->usesId['function'][strtolower($this->atoms[$nameId]['code'])], $nameId, 'DEFINITION');
                return array($this->uses['function'][strtolower($this->atoms[$nameId]['code'])], self::ALIASED);
            } else {
                return array($this->namespace.strtolower($this->atoms[$nameId]['fullcode']), self::NOT_ALIASED);
            }
        } elseif ($this->atoms[$nameId]['atom'] === 'String' && isset($this->atoms[$nameId]['noDelimiter'])) {
            if (empty($this->atoms[$nameId]['noDelimiter'])) {
                $prefix = '\\';
            } else {
                $prefix =  ($this->atoms[$nameId]['noDelimiter'][0] === '\\' ? '' : '\\').strtolower($this->atoms[$nameId]['noDelimiter']);
            }

            // define doesn't care about use...
            return array($prefix, self::NOT_ALIASED);
        } else {
            // Finally, the case for a nsname
            $prefix = strtolower( substr($this->atoms[$nameId]['fullcode'], 0, strpos($this->atoms[$nameId]['fullcode'], '\\')) );

            if (isset($this->uses[$type][$prefix])) {
                $this->addLink($this->usesId['class'][$prefix], $nameId, 'DEFINITION');
                return array($this->uses[$type][$prefix].strtolower( substr($this->atoms[$nameId]['fullcode'], strlen($prefix)) ) , 0);
            } else {
                return array($this->namespace.strtolower($this->atoms[$nameId]['fullcode']), 0);
            }
        }
    }

    private function nestContext() {
        return ++$this->contexts[self::CONTEXT_NOSEQUENCE];
    }

    private function exitContext() {
        return --$this->contexts[self::CONTEXT_NOSEQUENCE];
    }

    private function toggleContext($context) {
        $this->contexts[$context] = !$this->contexts[$context];
        return $this->contexts[$context];
    }

    private function isContext($context) {
        return (boolean) $this->contexts[$context];
    }

    private function makeFullnspath($namespaceAsId) {
        return strtolower(isset($this->atoms[$namespaceAsId]['absolute']) && $this->atoms[$namespaceAsId]['absolute'] === true ? $this->atoms[$namespaceAsId]['fullcode'] : '\\'.$this->atoms[$namespaceAsId]['fullcode']) ;
    }

    private function setNamespace($namespaceId = 0) {
        if ($namespaceId === 0) {
            $this->namespace = '\\';
            $this->uses = array('function' => array(),
                                'const'    => array(),
                                'class'    => array());
            $this->usesId = array('function' => array(),
                                  'const'    => array(),
                                  'class'    => array());
        } elseif ($this->atoms[$namespaceId]['atom'] === 'Void') {
            $this->namespace = '\\';
        } else {
            $this->namespace = strtolower($this->atoms[$namespaceId]['fullcode']).'\\';
            if ($this->namespace[0] !== '\\') {
                $this->namespace = '\\'.$this->namespace;
            }
        }
    }

    private function addNamespaceUse($originId, $aliasId, $useType, $useId) {
        $fullnspath = $this->atoms[$originId]['fullnspath'];

        if ($originId !== $aliasId) { // Case of A as B
            // Alias is the 'As' expression.
            $offset = strrpos($this->atoms[$aliasId]['fullcode'], ' ');
            $alias = strtolower(substr($this->atoms[$aliasId]['fullcode'], $offset + 1));
        } elseif (($offset = strrpos($this->atoms[$aliasId]['fullnspath'], '\\')) === false) {
            // namespace without \
            $alias = strtolower($this->atoms[$aliasId]['fullnspath']);
        } else {
            // namespace with \
            $alias = substr($this->atoms[$aliasId]['fullnspath'], $offset + 1);
        }

        $this->uses[$useType][strtolower($alias)] = $fullnspath;
        $this->usesId[$useType][strtolower($alias)] = $useId;

        return $alias;
    }

    private function addCall($type, $fullnspath, $callId) {
        if (empty($fullnspath)) {
            return;
        }

        if (!isset($this->calls[$type][$fullnspath])) {
            $this->calls[$type][$fullnspath] = array('calls'       => array(),
                                                     'definitions' => array());
        }
        $atom = $this->atoms[$callId]['atom'];
        if (!isset($this->calls[$type][$fullnspath]['calls'][$atom])) {
            $this->calls[$type][$fullnspath]['calls'][$atom] = array();
        }

        $this->calls[$type][$fullnspath]['calls'][$atom][] = $callId;
    }

    private function addNoDelimiterCall($callId) {
        if (strpos($this->atoms[$callId]['noDelimiter'], '::') !== false) {
            $fullnspath = strtolower(substr($this->atoms[$callId]['noDelimiter'], 0, strpos($this->atoms[$callId]['noDelimiter'], '::')) );

            if (strlen($fullnspath) === 0) {
                $fullnspath = '\\';
            } elseif ($fullnspath[0] !== '\\') {
                $fullnspath = '\\'.$fullnspath;
            }
            $types = array('class');
        } else {
            $types = array('function', 'class');

            $fullnspath = strtolower($this->atoms[$callId]['noDelimiter']);
            if (empty($fullnspath) || $fullnspath[0] !== '\\') {
                $fullnspath = '\\'.$fullnspath;
            }
        }

        $atom = 'String';
        foreach($types  as $type) {
            if (!isset($this->calls[$type][$fullnspath])) {
                $this->calls[$type][$fullnspath] = array('calls' => array(), 'definitions' => array());
            }

            if (!isset($this->calls[$type][$fullnspath]['calls'][$atom])) {
                $this->calls[$type][$fullnspath]['calls'][$atom] = array();
            }
            $this->calls[$type][$fullnspath]['calls'][$atom][] = $callId;
        }
    }

    private function addDefinition($type, $fullnspath, $definitionId) {
        if (empty($fullnspath)) {
            return;
        }

        if (!isset($this->calls[$type][$fullnspath])) {
            $this->calls[$type][$fullnspath] = array('calls'       => array(),
                                                     'definitions' => array());
        }
        $atom = $this->atoms[$definitionId]['atom'];
        if (!isset($this->calls[$type][$fullnspath]['definitions'][$atom])) {
            $this->calls[$type][$fullnspath]['definitions'][$atom] = array();
        }
        $this->calls[$type][$fullnspath]['definitions'][$atom][] = $definitionId;
    }

    private function logTime($step) {
        static $log, $begin, $end, $start;

        if ($log === null) {
            $log = fopen($this->config->projects_root.'/projects/'.$this->config->project.'/log/load.timing.csv', 'w+');
        }

        $end = microtime(true);
        if ($begin === null) {
            $begin = $end;
            $start = $end;
        }

        fwrite($log, $step."\t".($end - $begin)."\t".($end - $start)."\n");
        $begin = $end;
    }

}

?>
