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

use Exakat\Analyzer\Docs;
use Exakat\Data\Methods;
use Exakat\Config;
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
use Exakat\Tasks\Precedence;
use Exakat\Tasks\Helpers\Atom;

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
    private $filename   = null;
    private $line       = 0;

    private $links = array();

    private $sequences = array();

    private $currentClassTrait = array();
    private $currentParentClassTrait = array();

    private $tokens = array();
    private $id = 0;
    private $id0 = 0;

    const FULLCODE_SEQUENCE = ' /**/ ';
    const FULLCODE_BLOCK    = ' { /**/ } ';
    const FULLCODE_VOID     = ' ';

    const ALIASED           = 1;
    const NOT_ALIASED       = '';
    
    const NO_LINE           = -1;

    const VARIADIC          = 1;
    const NOT_VARIADIC      = '';

    const REFERENCE         = 1;
    const NOT_REFERENCE     = '';

    const BRACKET          = true;
    const NOT_BRACKET      = false;

    const ENCLOSING        = true;
    const NO_ENCLOSING     = false;
    
    const ALTERNATIVE      = true;
    const NOT_ALTERNATIVE  = false;
    
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

    const CONTEXT_CLASS        = 1;
    const CONTEXT_INTERFACE    = 2;
    const CONTEXT_TRAIT        = 3;
    const CONTEXT_FUNCTION     = 4;
    const CONTEXT_NEW          = 5;
    const CONTEXT_NOSEQUENCE   = 6;
    private $contexts = array(self::CONTEXT_CLASS        => 0,
                              self::CONTEXT_INTERFACE    => false,
                              self::CONTEXT_TRAIT        => false,
                              self::CONTEXT_FUNCTION     => 0,
                              self::CONTEXT_NEW          => false,
                              self::CONTEXT_NOSEQUENCE   => 0
                         );

    private $optionsTokens = array();

    static public $PROP_ALTERNATIVE = array('Declare', 'Ifthen', 'For', 'Foreach', 'Switch', 'While');
    static public $PROP_REFERENCE   = array('Variable', 'Variableobject', 'Variablearray', 'Property', 'Array', 'Function', 'Closure', 'Method', 'Functioncall', 'Methodcall');
    static public $PROP_VARIADIC    = array('Variable', 'Array', 'Property', 'Staticproperty', 'Staticconstant', 'Methodcall', 'Staticmethodcall', 'Functioncall', 'Identifier', 'Nsname');
    static public $PROP_DELIMITER   = array('String', 'Heredoc');
    static public $PROP_NODELIMITER = array('String', 'Variable');
    static public $PROP_HEREDOC     = array('Heredoc');
    static public $PROP_COUNT       = array('Sequence', 'Arguments', 'Heredoc', 'Shell', 'String', 'Try', 'Catch', 'Const', 'Ppp', 'Global', 'Static');
    static public $PROP_FNSNAME     = array('Functioncall', 'Newcall', 'Function', 'Closure', 'Method', 'Class', 'Classanonymous', 'Trait', 'Interface', 'Identifier', 'Nsname', 'As', 'Void', 'Static', 'Namespace', 'String');
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
    static public $PROP_PROPERTYNAME= array('Propertydefinition', 'Assignation');
    static public $PROP_CONSTANT    = array('Integer', 'Boolean', 'Real', 'Null', 'Void', 'Inlinehtml', 'String', 'Magicconstant', 'Staticconstant', 'Void', 'Addition', 'Nsname', 'Bitshift', 'Multiplication', 'Power', 'Comparison', 'Logical', 'Keyvalue', 'Arguments', 'Break', 'Continue', 'Return', 'Comparison', 'Ternary', 'Parenthesis', 'Noscream', 'Not', 'Yield', 'Identifier', 'Functioncall', 'Concatenation', 'Sequence', 'Arrayliteral', 'Function', 'Closure');
    static public $PROP_GLOBALVAR   = array('Array');
    static public $PROP_BINARYSTRING= array('String', 'Heredoc');

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
    private $expressions         = array();
    private $atoms               = array();
    private $atomCount           = 0;
    private $argumentsId         = array();
    private $sequence            = array();
    private $sequenceCurrentRank = 0;
    private $sequenceRank        = array();
    
    private $loaderList = array('CypherG3', 'Neo4jImport');

    private $processing = array();

    private $stats = array('loc'       => 0,
                           'totalLoc'  => 0,
                           'files'     => 0,
                           'tokens'    => 0);

    public function __construct($gremlin, $config, $subtask = Tasks::IS_NOT_SUBTASK) {
        parent::__construct($gremlin, $config, $subtask);

        $this->php = new Phpexec($this->config->phpversion, $this->config);
        if (!$this->php->isValid()) {
            throw new InvalidPHPBinary($this->php->getVersion());
        }

        $this->precedence = new Precedence($this->config->phpversion, $this->config);

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

                            \Exakat\Tasks\T_OPEN_BRACKET             => 'processArrayLiteral',
                            \Exakat\Tasks\T_ARRAY                    => 'processArrayLiteral',
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
                            \Exakat\Tasks\T_NS_SEPARATOR             => 'processNsname',
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
                          'propertyname'=> self::$PROP_PROPERTYNAME,
                          'constant'    => self::$PROP_CONSTANT,
                          'globalvar'   => self::$PROP_GLOBALVAR,
                          'binaryString'=> self::$PROP_BINARYSTRING,
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
        $this->id0->code      = 'Whole';
        $this->id0->fullcode  = $this->config->project;
        $this->id0->token     = 'T_WHOLE';

        if (static::$client === null) {
            $client = $this->config->loader;

            if (!in_array($client, $this->loaderList)) {
                throw new NoSuchLoader($client, $this->loaderList);
            }

            display('Loading with '.$client.PHP_EOL);

            $client = '\\Exakat\\Loader\\'.$client;
            static::$client = new $client($this->config);
        }

        $this->datastore->cleanTable('tokenCounts');
        $this->logTime('Init');

        if ($filename = $this->config->filename) {
            if (!is_file($filename)) {
                throw new MustBeAFile($filename);
            }
            if ($this->processFile($filename, '')) {
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
                if ($r = $this->processFile($file, $path)) {
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
        $tokens = 0;
        Files::findFiles($dir, $files, $ignoredFiles, $this->config, $tokens);

        $this->reset();

        $nbTokens = 0;
        foreach($files as $file) {
            try {
                if ($r = $this->processFile($file, $dir)) {
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
        $this->atoms = array($this->id0->id => $this->id0);
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
        $this->contexts = array(self::CONTEXT_CLASS        => 0,
                                self::CONTEXT_INTERFACE    => false,
                                self::CONTEXT_TRAIT        => false,
                                self::CONTEXT_FUNCTION     => 0,
                                self::CONTEXT_NEW          => false,
                                self::CONTEXT_NOSEQUENCE   => 0                         );
        $this->expressions = array();
    }

    private function processFile($filename, $path) {
        $begin = microtime(true);
        $fullpath = $path.$filename;
        
        $this->log->log($fullpath);
        $this->filename = $filename;

        ++$this->stats['files'];

        $this->line = 0;
        $log = array();

        if (is_link($fullpath)) {
            return true;
        }
        if (!file_exists($fullpath)) {
            throw new NoSuchFile( $filename );
        }

        if (filesize($fullpath) === 0) {
            return false;
        }

        if (!$this->php->compile($fullpath)) {
            throw new NoFileToProcess($filename, 'won\'t compile');
        }

        $tokens = $this->php->getTokenFromFile($fullpath);
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

        $id1 = $this->addAtom('File');
        $id1->code     = $filename;
        $id1->fullcode = $filename;
        $id1->token    = 'T_FILENAME';

        $this->addLink($this->id0, $id1, 'PROJECT');

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

            $this->checkTokens($filename);
        } catch (LoadError $e) {
//            print $e->getMessage();
//            print_r($this->expressions[0]);
            $this->log->log('Can\'t process file \''.$this->filename.'\' during load (\''.$this->tokens[$this->id][0].'\', line \''.$this->tokens[$this->id][2].'\'). Ignoring'.PHP_EOL);
            $this->reset();
            throw new NoFileToProcess($filename, 'empty', 0, $e);
        } finally {
            $this->stats['totalLoc'] += $line;
            $this->stats['loc'] += $line;
        }

        $end = microtime(true);
        $this->log->log("processFile\t".(($end - $begin) * 1000)."\t".$log['token_initial'].PHP_EOL);

        return true;
    }

    private function processNext() {
        ++$this->id;

        if ($this->tokens[$this->id][0] === \Exakat\Tasks\T_END ||
            !isset($this->processing[ $this->tokens[$this->id][0] ])) {
            display("Can't process file '$this->filename' during load ('{$this->tokens[$this->id][0]}', line {$this->tokens[$this->id][2]}). Ignoring".PHP_EOL);
            $this->log->log("Can't process file '$this->filename' during load ('{$this->tokens[$this->id][0]}', line {$this->tokens[$this->id][2]}). Ignoring".PHP_EOL);

            throw new LoadError('Processing error');
        }
        $method = $this->processing[ $this->tokens[$this->id][0] ];
        
//        print "  $method in".PHP_EOL;
        $id = $this->$method();
//        print "  $method out ".PHP_EOL;
        
        return $id;
    }
    
    private function processColon() {
        $label = $this->addAtom('Label');
        $tag = $this->popExpression();

        $this->addLink($label, $tag, 'LABEL');
        $label->code     = ':';
        $label->fullcode = $tag->fullcode.' :';
        $label->line     = $this->tokens[$this->id][2];
        $label->token    = $this->getToken($this->tokens[$this->id][0]);

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
        $constant = self::CONSTANT_EXPRESSION;

        if ($this->tokens[$current][0] === \Exakat\Tasks\T_QUOTE) {
            $string = $this->addAtom('String');
            $finalToken = \Exakat\Tasks\T_QUOTE;
            $openQuote = '"';
            $closeQuote = '"';
            $type = \Exakat\Tasks\T_QUOTE;
        } elseif ($this->tokens[$current][0] === \Exakat\Tasks\T_BACKTICK) {
            $string = $this->addAtom('Shell');
            $finalToken = \Exakat\Tasks\T_BACKTICK;
            $openQuote = '`';
            $closeQuote = '`';
            $type = \Exakat\Tasks\T_BACKTICK;
        } elseif ($this->tokens[$current][0] === \Exakat\Tasks\T_START_HEREDOC) {
            $string = $this->addAtom('Heredoc');
            $finalToken = \Exakat\Tasks\T_END_HEREDOC;
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
            $type = \Exakat\Tasks\T_START_HEREDOC;
        }

        while ($this->tokens[$this->id + 1][0] !== $finalToken) {
            $currentVariable = $this->id + 1;
            if (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CURLY_OPEN, \Exakat\Tasks\T_DOLLAR_OPEN_CURLY_BRACES))) {
                $open = $this->id + 1;
                ++$this->id; // Skip {
                while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_CURLY))) {
                    $part = $this->processNext();
                };
                ++$this->id; // Skip }
                
                $this->popExpression();
                
                $part->enclosing = self::ENCLOSING;
                $part->fullcode  = $this->tokens[$open][1].$part->fullcode.'}';
                $part->token     = $this->getToken($this->tokens[$currentVariable][0]);

                $this->pushExpression($part);

                $constant = self::NOT_CONSTANT_EXPRESSION;
            } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_VARIABLE) {
                $this->processNext();

                if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OBJECT_OPERATOR) {
                    ++$this->id;

                    $object = $this->popExpression();

                    $propertyName = $this->processNextAsIdentifier();

                    $property = $this->addAtom('Property');
                    $property->code      = $this->tokens[$current][1];
                    $property->fullcode  = $object->fullcode.'->'.$propertyName->fullcode;
                    $property->line      = $this->tokens[$current][2];
                    $property->token     = $this->getToken($this->tokens[$current][0]);
                    $property->enclosing = self::NO_ENCLOSING;

                    $this->addLink($property, $object, 'OBJECT');
                    $this->addLink($property, $propertyName, 'PROPERTY');

                    $this->pushExpression($property);
                }
                $constant = self::NOT_CONSTANT_EXPRESSION;
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

            $this->addLink($string, $part, 'CONCAT');
        }

        ++$this->id;
        $string->code     = $this->tokens[$current][1];
        $string->fullcode = $string->binaryString.$openQuote.implode('', $fullcode).$closeQuote;
        $string->line     = $this->tokens[$current][2];
        $string->token    = $this->getToken($this->tokens[$current][0]);
        $string->count    = $rank + 1;
        $string->boolean  = (int) (boolean) ($rank + 1);
        $string->constant = $constant;

        if ($type === \Exakat\Tasks\T_START_HEREDOC) {
            $string->delimiter = $closeQuote;
            $string->heredoc   = $openQuote[3] !== "'";
        }

        $this->pushExpression($string);

        return $string;
    }

    private function processDollarCurly() {
        $current = $this->id;
        $atom = ($this->tokens[$this->id - 1][0] === \Exakat\Tasks\T_GLOBAL) ? 'Globaldefinition' : 'Variable';
        $variable = $this->addAtom($atom);

        ++$this->id; // Skip ${
        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_CURLY))) {
            $this->processNext();
        } ;
        ++$this->id; // Skip }

        $name = $this->popExpression();
        $this->addLink($variable, $name, 'NAME');

        $name->code      = $this->tokens[$current][1];
        $name->fullcode  = '${'.$name->fullcode.'}';
        $name->line      = $this->tokens[$current][2];
        $name->token     = $this->getToken($this->tokens[$current][0]);
        $name->enclosing = self::ENCLOSING;

        $this->pushExpression($variable);
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $variable;
    }

    private function processTry() {
        $current = $this->id;
        $try = $this->addAtom('Try');

        $block = $this->processFollowingBlock(array(\Exakat\Tasks\T_CLOSE_CURLY));
        $this->popExpression();
        $this->addLink($try, $block, 'BLOCK');

        $rank = 0;
        $fullcodeCatch = array();
        while ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CATCH) {
            $catchId = $this->id + 1;
            ++$this->id; // Skip catch
            ++$this->id; // Skip (

            $catch = $this->addAtom('Catch');
            $catchFullcode = array();
            $rankCatch = -1;
            while ($this->tokens[$this->id + 1][0] !== \Exakat\Tasks\T_VARIABLE) {
                $class = $this->processOneNsname();
                $this->addLink($catch, $class, 'CLASS');
                $catch->rank = ++$rankCatch;

                $this->addCall('class', $class->fullnspath, $class);
                $catchFullcode[] = $class->fullcode;

                if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_PIPE) {
                    ++$this->id; // Skip |
                }
            }
            $catch->count = $rankCatch + 1;
            $catchFullcode = implode(' | ', $catchFullcode);

            // Process variable
            $this->processNext();

            $variable = $this->popExpression();
            $this->addLink($catch, $variable, 'VARIABLE');

            // Skip )
            ++$this->id;

            // Skip }
            $blockCatch = $this->processFollowingBlock(array(\Exakat\Tasks\T_CLOSE_CURLY));
            $this->popExpression();
            $this->addLink($catch, $blockCatch, 'BLOCK');

            $catch->code     = $this->tokens[$catchId][1];
            $catch->fullcode = $this->tokens[$catchId][1].' ('.$catchFullcode.' '.$variable->fullcode.')'.static::FULLCODE_BLOCK;
            $catch->line     = $this->tokens[$catchId][2];
            $catch->token    = $this->getToken($this->tokens[$current][0]);
            $catch->rank     = ++$rank;

            $this->addLink($try, $catch, 'CATCH');
            $fullcodeCatch[] = $catch->fullcode;
        }

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_FINALLY) {
            $finallyId = $this->id + 1;
            $finally = $this->addAtom('Finally');

            ++$this->id;
            $finallyBlock = $this->processFollowingBlock(false);
            $this->popExpression();
            $this->addLink($try, $finally, 'FINALLY');
            $this->addLink($finally, $finallyBlock, 'BLOCK');

            $finally->code     = $this->tokens[$finallyId][1];
            $finally->fullcode = $this->tokens[$finallyId][1].static::FULLCODE_BLOCK;
            $finally->line     = $this->tokens[$finallyId][2];
            $finally->token    = $this->getToken($this->tokens[$current][0]);
        }

        $try->code     = $this->tokens[$current][1];
        $try->fullcode = $this->tokens[$current][1].static::FULLCODE_BLOCK.implode('', $fullcodeCatch).( isset($finallyId) ? $finally->fullcode : '');
        $try->line     = $this->tokens[$current][2];
        $try->token    = $this->getToken($this->tokens[$current][0]);
        $try->count    = $rank;

        $this->pushExpression($try);
        $this->processSemicolon();

        return $try;
    }

    private function processFunction() {
        $current = $this->id;
        
        if (($this->isContext(self::CONTEXT_CLASS) ||
             $this->isContext(self::CONTEXT_TRAIT) ||
             $this->isContext(self::CONTEXT_INTERFACE)) &&
             
             !$this->isContext(self::CONTEXT_FUNCTION)) {
            $function = $this->addAtom('Method');
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_PARENTHESIS) {
            $function = $this->addAtom('Closure');
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_AND &&
                  $this->tokens[$this->id + 2][0] === \Exakat\Tasks\T_OPEN_PARENTHESIS) {
            $function = $this->addAtom('Closure');
        } else {
            $function = $this->addAtom('Function');
        }

        $previousClassContext = $this->contexts[self::CONTEXT_CLASS];
        $previousFunctionContext = $this->contexts[self::CONTEXT_FUNCTION];
        $this->contexts[self::CONTEXT_CLASS] = 0;
        $this->contexts[self::CONTEXT_FUNCTION] = 1;

        $fullcode = array();
        foreach($this->optionsTokens as $name => $option) {
            $this->addLink($function, $option, strtoupper($name));
            $fullcode[] = $option->fullcode;
        }
        $this->optionsTokens = array();

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_AND) {
            ++$this->id;
            $function->reference = self::REFERENCE;
        } else {
            $function->reference = self::NOT_REFERENCE;
        }

        if ($function->atom === 'Closure') {
            ++$this->id;
        } else {
            $name = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);
            $this->addLink($function, $name, 'NAME');
            ++$this->id;
        }
        
        // Process arguments
        $arguments = $this->processArguments(array(\Exakat\Tasks\T_CLOSE_PARENTHESIS), true);
        $this->addLink($function, $arguments, 'ARGUMENTS');

        // Process use
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_USE) {
            ++$this->id; // Skip use
            ++$this->id; // Skip (
            $use = $this->processArguments();
            $this->addLink($function, $use, 'USE');
        } else {
            $function->constant = self::CONSTANT_EXPRESSION;
        }

        // Process return type
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COLON) {
            ++$this->id;
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_QUESTION) {
                $nullable = $this->processNextAsIdentifier();
                $this->addLink($function, $nullable, 'NULLABLE');
            }

            $returnType = $this->processOneNsname();
            $this->addLink($function, $returnType, 'RETURNTYPE');
        }
        
        // Process block
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_SEMICOLON) {
            $void = $this->addAtomVoid();
            $this->addLink($function, $void, 'BLOCK');
            ++$this->id; // skip the next ;
        } else {
            $block = $this->processFollowingBlock(array(\Exakat\Tasks\T_CLOSE_CURLY));
            $this->popExpression();
            $this->addLink($function, $block, 'BLOCK');
        }

        if (!empty($fullcode)) {
            $fullcode[] = '';
        }

        if ( $function->atom === 'Function') {
            list($fullnspath, $aliased) = $this->getFullnspath($name);
            $this->addDefinition('function', $fullnspath, $function);
        } elseif ( $function->atom === 'Closure') {
            $fullnspath = $this->makeAnonymous('function');
            $aliased    = self::NOT_ALIASED;
        } elseif ( $function->atom === 'Method') {
            $fullnspath = end($this->currentClassTrait)->fullnspath.':'.$name->code;
            $aliased    = self::NOT_ALIASED;
        } else {
            assert(false, 'Wrong type of function '.$function->atom);
        }

        $function->code       = $function->atom === 'Closure' ? 'function' : $name->fullcode;
        $function->fullcode   = implode(' ', $fullcode).$this->tokens[$current][1].' '.($function->reference ? '&' : '').
                                ($function->atom === 'Closure' ? '' : $name->fullcode).'('.$arguments->fullcode.')'.
                                (isset($use) ? ' use ('.$use->fullcode.')' : '').// No space before use
                                (isset($returnType) ? ' : '.(isset($nullable) ? '?' : '').$returnType->fullcode : '').
                                (isset($block) ? self::FULLCODE_BLOCK : ' ;');
        $function->line       = $this->tokens[$current][2];
        $function->token      = $this->getToken($this->tokens[$current][0]);
        $function->fullnspath = $fullnspath;
        $function->aliased    = $aliased;

        $this->pushExpression($function);

        if ($function->atom === 'Function' ) {
            $this->processSemicolon();
        }

        if (!$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        $this->contexts[self::CONTEXT_CLASS] = $previousClassContext;
        $this->contexts[self::CONTEXT_FUNCTION] = $previousFunctionContext;
        return $function;
    }

    private function processOneNsname($getFullnspath = self::WITH_FULLNSPATH) {
        ++$this->id;
        if ($this->tokens[$this->id][0] === \Exakat\Tasks\T_NAMESPACE) {
            ++$this->id;
        }
        $nsname = $this->makeNsname();
 
        if ($getFullnspath === self::WITH_FULLNSPATH) {
            list($fullnspath, $aliased) = $this->getFullnspath($nsname, 'class');
            $this->addCall('class', $nsname->fullnspath, $nsname);
            $nsname->fullnspath = $fullnspath;
            $nsname->aliased    = $aliased;
        }

        return $nsname;
    }

    private function processTrait() {
        $current = $this->id;
        $trait = $this->addAtom('Trait');
        $this->currentClassTrait[] = $trait;
        $this->toggleContext(self::CONTEXT_TRAIT);

        $name = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);
        $this->addLink($trait, $name, 'NAME');

        list($fullnspath, $aliased) = $this->getFullnspath($name, 'class');
        $trait->fullnspath = $fullnspath;
        $trait->aliased    = $aliased;
        $this->addDefinition('class', $trait->fullnspath, $trait);

        // Process block
        $this->makeCitBody($trait);

        list($fullnspath, $aliased) = $this->getFullnspath($name);
        $trait->code       = $this->tokens[$current][1];
        $trait->fullcode   = $this->tokens[$current][1].' '.$name->fullcode.static::FULLCODE_BLOCK;
        $trait->line       = $this->tokens[$current][2];
        $trait->token      = $this->getToken($this->tokens[$current][0]);

        $this->pushExpression($trait);
        $this->processSemicolon();

        $this->toggleContext(self::CONTEXT_TRAIT);

        array_pop($this->currentClassTrait);

        return $trait;
    }

    private function processInterface() {
        $current = $this->id;
        $interface = $this->addAtom('Interface');
        $this->currentClassTrait[] = $interface;
        $this->toggleContext(self::CONTEXT_INTERFACE);

        $name = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);
        $this->addLink($interface, $name, 'NAME');

        list($fullnspath, $aliased) = $this->getFullnspath($name, 'class');
        $interface->fullnspath = $fullnspath;
        $interface->aliased    = $aliased;

        $this->addDefinition('class', $fullnspath, $interface);

        // Process extends
        $rank = 0;
        $fullcode= array();
        $extends = $this->id + 1;
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_EXTENDS) {
            $extendsKeyword = $this->tokens[$this->id + 1][1];
            do {
                ++$this->id; // Skip extends or ,
                $extends = $this->processOneNsname(self::WITH_FULLNSPATH);
                $extends->rank = $rank;

                $this->addLink($interface, $extends, 'EXTENDS');
                $this->addCall('class', $extends->fullnspath, $extends);

                $fullcode[] = $extends->fullcode;
            } while ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COMMA);
        }

        // Process block
        $block = $this->makeCitBody($interface);

        $interface->code       = $this->tokens[$current][1];
        $interface->fullcode   = $this->tokens[$current][1].' '.$name->fullcode.(isset($extendsKeyword) ? ' '.$extendsKeyword.' '.implode(', ', $fullcode) : '').static::FULLCODE_BLOCK;
        $interface->line       = $this->tokens[$current][2];
        $interface->token      = $this->getToken($this->tokens[$current][0]);

        $this->pushExpression($interface);
        $this->processSemicolon();

        $this->toggleContext(self::CONTEXT_INTERFACE);
        array_pop($this->currentClassTrait);

        return $interface;
    }

    private function makeCitBody($class) {
        ++$this->id;
        $rank = -1;
        while($this->tokens[$this->id + 1][0] !== \Exakat\Tasks\T_CLOSE_CURLY) {
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_SEMICOLON) {
                ++$this->id;
                continue;
            }
            
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_PRIVATE) {
                ++$this->id;
                $cpm = $this->processPrivate();
                if ($cpm->atom === 'Ppp'){
                    $cpm->rank = ++$rank;
                    $this->addLink($class, $cpm, strtoupper($cpm->atom));
                }

                continue;
            }

            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_PUBLIC) {
                ++$this->id;
                $cpm = $this->processPublic();
                if ($cpm->atom === 'Ppp'){
                    $cpm->rank = ++$rank;
                    $this->addLink($class, $cpm, strtoupper($cpm->atom));
                }

                continue;
            }

            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_PROTECTED) {
                ++$this->id;
                $cpm = $this->processProtected();
                if ($cpm->atom === 'Ppp'){
                    $cpm->rank = ++$rank;
                    $this->addLink($class, $cpm, strtoupper($cpm->atom));
                }

                continue;
            }

            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_FINAL) {
                ++$this->id;
                $cpm = $this->processFinal();
                continue;
            }

            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_ABSTRACT) {
                ++$this->id;
                $cpm = $this->processAbstract();
                continue;
            }

            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_STATIC) {
                ++$this->id;
                $cpm = $this->processStatic();
                if ($cpm->atom === 'Ppp'){
                    $cpm->rank = ++$rank;
                    $this->addLink($class, $cpm, strtoupper($cpm->atom));
                }
                continue;
            }
            
            $cpm = $this->processNext();
            $this->popExpression();

            $cpm->rank = ++$rank;
            $this->addLink($class, $cpm, strtoupper($cpm->atom));
        }
        
        ++$this->id;
    }
    
    private function processClass() {
        $current = $this->id;
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_STRING) {
            $class = $this->addAtom('Class');
        } else {
            $class = $this->addAtom('Classanonymous');
        }
        $this->currentClassTrait[] = $class;
        $this->nestContext(self::CONTEXT_CLASS);
        $previousFunctionContext = $this->contexts[self::CONTEXT_FUNCTION];
        $this->contexts[self::CONTEXT_FUNCTION] = 0;

        // Should work on Abstract and Final only
        $fullcode= array();
        foreach($this->optionsTokens as $name => $option) {
            $this->addLink($class, $option, strtoupper($name));
            $fullcode[] = $option->fullcode;
        }
        $this->optionsTokens = array();

        if ($class->atom === 'Class') {
            $name = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);
            
            list($fullnspath, $aliased) = $this->getFullnspath($name, 'class');
            $class->fullnspath = $fullnspath;
            $class->aliased    = $aliased;

            $this->addDefinition('class', $class->fullnspath, $class);
            $this->addLink($class, $name, 'NAME');
        } else {
            $class->fullnspath = $this->makeAnonymous();
            $class->aliased    = self::NOT_ALIASED;
            $this->addDefinition('class', $class->fullnspath, $class);

            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_PARENTHESIS) {
                // Process arguments
                ++$this->id; // Skip arguments
                $arguments = $this->processArguments();
                $this->addLink($class, $arguments, 'ARGUMENTS');
            }
        }

        // Process extends
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_EXTENDS) {
            $extendsKeyword = $this->tokens[$this->id + 1][1];
            ++$this->id; // Skip extends

            $extends = $this->processOneNsname(self::WITH_FULLNSPATH);

            $this->addLink($class, $extends, 'EXTENDS');
            list($fullnspath, $aliased) = $this->getFullnspath($extends, 'class');
            $this->addCall('class', $extends->fullnspath, $extends);

            $this->currentParentClassTrait[] = $extends;
            $isExtended = true;
        }

        // Process implements
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_IMPLEMENTS) {
            $implementsKeyword = $this->tokens[$this->id + 1][1];
            $fullcodeImplements = array();
            do {
                ++$this->id; // Skip implements
                $implements = $this->processOneNsname();
                $this->addLink($class, $implements, 'IMPLEMENTS');
                $fullcodeImplements[] = $implements->fullcode;

                list($fullnspath, $aliased) = $this->getFullnspath($implements);
                $this->addCall('class', $fullnspath, $implements);
            } while ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COMMA);
        }

        // Process block
        $newContext = $this->isContext(self::CONTEXT_NEW);
        if ($newContext === true) {
            $this->toggleContext(self::CONTEXT_NEW);
        }
        
        $this->makeCitBody($class);
        
        if ($newContext === true) {
            $this->toggleContext(self::CONTEXT_NEW);
        }
        
        $class->code       = $this->tokens[$current][1];
        $class->fullcode   = (!empty($fullcode) ? implode(' ', $fullcode).' ' : '').$this->tokens[$current][1].($class->atom === 'Classanonymous' ? '' : ' '.$name->fullcode)
                             .(isset($arguments) ? ' ('.$arguments->fullcode.')' : '')
                             .(isset($extends) ? ' '.$extendsKeyword.' '.$extends->fullcode : '')
                             .(isset($implements) ? ' '.$implementsKeyword.' '.implode(', ', $fullcodeImplements) : '')
                             .static::FULLCODE_BLOCK;
        $class->line       = $this->tokens[$current][2];
        $class->token      = $this->getToken($this->tokens[$current][0]) ;

        $this->pushExpression($class);

        // Case of anonymous classes
        if ($this->tokens[$current - 1][0] !== \Exakat\Tasks\T_NEW) {
            $this->processSemicolon();
        }

        $this->exitContext(self::CONTEXT_CLASS);
        array_pop($this->currentClassTrait);
        if (isset($isExtended)) {
            array_pop($this->currentParentClassTrait);
        }

        $this->contexts[self::CONTEXT_FUNCTION] = $previousFunctionContext;
        
        return $class;
    }

    private function processOpenTag() {
        $phpcode = $this->addAtom('Php');
        $current = $this->id;

        $this->startSequence();

        // Special case for pretty much empty script (<?php .... END)
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_END) {
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
            $close_tag = self::CLOSING_TAG;
            $closing = '?>';
        } elseif ($this->tokens[$this->id][0] === \Exakat\Tasks\T_HALT_COMPILER) {
            $close_tag = self::NO_CLOSING_TAG;
            ++$this->id; // Go to HaltCompiler
            $this->processHalt();
            $closing = '';
        } else {
            $close_tag = self::NO_CLOSING_TAG;
            $closing = '';
        }

        if ($this->tokens[$this->id - 1][0] === \Exakat\Tasks\T_OPEN_TAG) {
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
                $void = $this->addAtomVoid();
                $this->addToSequence($void);
            } else {
                // do nothing
            }
            ++$this->id;
        }
    }

    private function processOpenWithEcho() {
        // Processing ECHO
        $echo = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);
        $current = $this->id;

        $noSequence = $this->isContext(self::CONTEXT_NOSEQUENCE);
        if ($noSequence === false) {
            $this->toggleContext(self::CONTEXT_NOSEQUENCE);
        }
        $arguments = $this->processArguments(array(\Exakat\Tasks\T_SEMICOLON, \Exakat\Tasks\T_CLOSE_TAG, \Exakat\Tasks\T_END));
        if ($noSequence === false) {
            $this->toggleContext(self::CONTEXT_NOSEQUENCE);
        }

        //processArguments goes too far, up to ;
        if ($this->tokens[$this->id][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            --$this->id;
        }

        $functioncall = $this->addAtom('Functioncall');
        $functioncall->code       = $echo->code;
        $functioncall->fullcode   = '<?= '.$arguments->fullcode;
        $functioncall->line       = $this->tokens[$current === self::NO_VALUE ? 0 : $current][2];
        $functioncall->token      = 'T_OPEN_TAG_WITH_ECHO';
        $functioncall->fullnspath = '\\echo';

        $this->addLink($functioncall, $arguments, 'ARGUMENTS');
        $this->addLink($functioncall, $echo, 'NAME');

        $this->pushExpression($functioncall);
    }

    private function makeNsname() {
        $current = $this->id;

        if ($this->tokens[$this->id][0]     === \Exakat\Tasks\T_NS_SEPARATOR              &&
            $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_STRING                    &&
            in_array(strtolower($this->tokens[$this->id + 1][1]), array('true', 'false')) &&
            $this->tokens[$this->id + 2][0] !== \Exakat\Tasks\T_NS_SEPARATOR
            ) {

            $nsname = $this->addAtom('Boolean');
            $nsname->boolean = (int) (strtolower($this->tokens[$this->id ][1]) === 'true');
            $nsname->constant = self::CONSTANT_EXPRESSION;
        } elseif ($this->tokens[$this->id][0]     === \Exakat\Tasks\T_NS_SEPARATOR &&
                  $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_STRING       &&
                  strtolower($this->tokens[$this->id + 1][1]) === 'null'           &&
                  $this->tokens[$this->id + 2][0] !== \Exakat\Tasks\T_NS_SEPARATOR ) {

            $nsname = $this->addAtom('Null');
            $nsname->boolean = 0;
            $nsname->constant = self::CONSTANT_EXPRESSION;
        } elseif ($this->tokens[$this->id][0] === \Exakat\Tasks\T_CALLABLE) {
            $nsname = $this->addAtom('Nsname');
            $nsname->token      = 'T_CALLABLE';
            $nsname->fullnspath = '\\callable';
        } elseif ($this->tokens[$this->id][0] === \Exakat\Tasks\T_ARRAY) {
            $nsname = $this->addAtom('Nsname');
            $nsname->token      = 'T_ARRAY';
            $nsname->fullnspath = '\\array';
        } else {
            $nsname = $this->addAtom('Nsname');
            $nsname->token     = 'T_STRING';
        }

        $fullcode = array();

        if ($this->tokens[$this->id][0] === \Exakat\Tasks\T_STRING) {
            $fullcode[] = $this->tokens[$this->id][1];
            ++$this->id;

            $nsname->absolute = self::NOT_ABSOLUTE;
        } elseif ($this->tokens[$this->id][0] === \Exakat\Tasks\T_ARRAY    ||
                  $this->tokens[$this->id][0] === \Exakat\Tasks\T_CALLABLE ) {
            $fullcode[] = $this->tokens[$this->id][1];

            ++$this->id;

            $nsname->absolute = self::ABSOLUTE;
        } elseif ($this->tokens[$this->id - 1][0] === \Exakat\Tasks\T_NAMESPACE) {
            $fullcode[] = $this->tokens[$this->id - 1][1];

            $nsname->absolute = self::ABSOLUTE;
        } else {
            $fullcode[] = '';

            $nsname->absolute = self::ABSOLUTE;
        }

        while ($this->tokens[$this->id][0]     === \Exakat\Tasks\T_NS_SEPARATOR    &&
               $this->tokens[$this->id + 1][0] !== \Exakat\Tasks\T_OPEN_CURLY
               ) {
            ++$this->id; // skip \
            $fullcode[] = $this->tokens[$this->id][1];

            // Go to next
            ++$this->id; // skip \
            $nsname->token    = 'T_NS_SEPARATOR';
        };
        // Back up a bit
        --$this->id;

        $nsname->code     = implode('\\', $fullcode);
        $nsname->fullcode = implode('\\', $fullcode);
        $nsname->line     = $this->tokens[$current][2];
        
        return $nsname;
    }

    private function processNsname() {
        $current = $this->id;
        $nsname = $this->makeNsname();
        
        // Review this : most nsname will end up as constants!

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_DOUBLE_COLON ||
            $this->tokens[$this->id - 2][0] === \Exakat\Tasks\T_INSTANCEOF) {

            list($fullnspath, $aliased) = $this->getFullnspath($nsname, 'class');
            $nsname->fullnspath = $fullnspath;
            $nsname->aliased    = $aliased;

            $this->addCall('class', $fullnspath, $nsname);
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_VARIABLE ||
            (isset($this->tokens[$current - 2]) && $this->tokens[$current - 2][0] === \Exakat\Tasks\T_INSTANCEOF)
            ) {

            list($fullnspath, $aliased) = $this->getFullnspath($nsname, 'class');
            $nsname->fullnspath = $fullnspath;
            $nsname->aliased    = $aliased;

            $this->addCall('class', $fullnspath, $nsname);
        } elseif ($this->isContext(self::CONTEXT_NEW)) {

            list($fullnspath, $aliased) = $this->getFullnspath($nsname, 'class');
            $nsname->fullnspath = $fullnspath;
            $nsname->aliased    = $aliased;

            $this->addCall('class', $fullnspath, $nsname);
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_PARENTHESIS) {
            // DO nothing

        } else {
            list($fullnspath, $aliased) = $this->getFullnspath($nsname, 'const');
            $nsname->fullnspath = $fullnspath;
            $nsname->aliased    = $aliased;

            $this->addCall('const', $fullnspath, $nsname);
        }
        
        $this->pushExpression($nsname);

        return $this->processFCOA($nsname);
    }

    private function processTypehint() {
        if (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_ARRAY,
                                                            \Exakat\Tasks\T_CALLABLE,
                                                            \Exakat\Tasks\T_STATIC))) {
            $nsname = $this->processNextAsIdentifier();

            return $nsname;
        }
        
        if (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_NS_SEPARATOR,
                                                            \Exakat\Tasks\T_STRING,
                                                            \Exakat\Tasks\T_NAMESPACE))) {
            $nsname = $this->processOneNsname(self::WITHOUT_FULLNSPATH);
            
            if ($this->tokens[$this->id + 1][1] === ',') {
                ++$this->id;
            }
            
            if (in_array(strtolower($nsname->code), array('int', 'bool', 'void', 'float', 'string'))) {
                $nsname->fullnspath = '\\'.strtolower($nsname->code);
            } else {
                list($fullnspath, $aliased) = $this->getFullnspath($nsname, 'class');

                $nsname->fullnspath = $fullnspath;
                $nsname->aliased    = $aliased;
                
                $this->addCall('class', $fullnspath, $nsname);
            }
            
            return $nsname;
        }
        
        // Nothing to do, return 0 for the calling method
        return 0;
    }

    private function processArguments($finals = array(\Exakat\Tasks\T_CLOSE_PARENTHESIS), $typehintSupport = false, $allowFinalVoid = false) {
        $arguments = $this->addAtom('Arguments');
        $current = $this->id;
        $argumentsId = array();

        $newContext = $this->isContext(self::CONTEXT_NEW);
        $this->contexts[self::CONTEXT_NEW] = 0;
        $this->nestContext();
        $fullcode = array();
        if (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_PARENTHESIS, \Exakat\Tasks\T_CLOSE_BRACKET))) {
            $void = $this->addAtomVoid();
            $void->rank = 0;
            $this->addLink($arguments, $void, 'ARGUMENT');

            $arguments->code     = $this->tokens[$current][1];
            $arguments->fullcode = self::FULLCODE_VOID;
            $arguments->line     = $this->tokens[$current][2];
            $arguments->token    = $this->getToken($this->tokens[$current][0]);
            $arguments->constant = self::CONSTANT_EXPRESSION;
            $arguments->args_max = 0;
            $arguments->args_min = 0;
            $argumentsId[] = $void;

            ++$this->id;
        } else {
            $typehint   = 0;
            $default    = 0;
            $index      = 0;
            $args_max   = 0;
            $args_min   = 0;
            $rank       = -1;
            $nullable   = 0;
            $typehint   = 0;
            $constant = self::CONSTANT_EXPRESSION;

            while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
                $initialId = $this->id;
                ++$args_max;

                if ($typehintSupport === true) {
                    if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_QUESTION) {
                        $nullable = $this->processNextAsIdentifier();
                    } else {
                        $nullable = 0;
                    }
                    $typehint = $this->processTypehint();

                    $this->processNext();
                    $index = $this->popExpression();

                    if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_EQUAL) {
                        ++$this->id; // Skip =
                        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_COMMA, \Exakat\Tasks\T_CLOSE_PARENTHESIS, \Exakat\Tasks\T_CLOSE_BRACKET))) {
                            $this->processNext();
                        }
                        $default = $this->popExpression();
                    } else {
                        ++$args_min;
                        $default = 0;
                    }
                } else {
                    $typehint = 0;
                    $default  = 0;
                    $nullable = 0;

                    while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_COMMA, \Exakat\Tasks\T_CLOSE_PARENTHESIS, \Exakat\Tasks\T_SEMICOLON, \Exakat\Tasks\T_CLOSE_BRACKET, \Exakat\Tasks\T_CLOSE_TAG))) {
                        $this->processNext();
                    };
                    $index = $this->popExpression();
                }

                while ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COMMA) {
                    if ($index === 0) {
                        $index = $this->addAtomVoid();
                    }

                    $index->rank = ++$rank;

                    if ($nullable !== 0) {
                        $this->addLink($index, $nullable, 'NULLABLE');
                        $this->addLink($index, $typehint, 'TYPEHINT');
                        $index->fullcode = '?'.$typehint->fullcode.' '.$index->fullcode;
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
                    $constant = $constant && ($index->constant === self::CONSTANT_EXPRESSION);

                    ++$this->id; // Skipping the comma ,
                    $index = 0;
                }

                if ($initialId === $this->id) {
                    throw new NoFileToProcess($this->filename, 'not processable with the current code.');
                }
            };

            if ($index === 0 && $allowFinalVoid === false) {
                $fullcode[] = ' ';
            } else {
                
                if ($index === 0) {
                    $index = $this->addAtomVoid();
                }

                $index->rank = ++$rank;
                $argumentsId[] = $index;
                $this->argumentsId = $argumentsId; // This avoid overwriting when nesting functioncall
    
                if ($nullable !== 0) {
                    $this->addLink($index, $nullable, 'NULLABLE');
                    $this->addLink($index, $typehint, 'TYPEHINT');
                    $index->fullcode = '?'.$typehint->fullcode.' '.$index->fullcode;
                } elseif ($typehint !== 0) {
                    $this->addLink($index, $typehint, 'TYPEHINT');
                    $index->fullcode = $typehint->fullcode.' '.$index->fullcode;
                }
    
                if ($default !== 0) {
                    $this->addLink($index, $default, 'DEFAULT');
                    $index->fullcode .= ' = '.$default->fullcode;
                }
                $this->addLink($arguments, $index, 'ARGUMENT');
                $constant = $constant && ($index->constant === self::CONSTANT_EXPRESSION);
    
                $fullcode[] = $index->fullcode;
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
            $arguments->constant = $constant;
        }

        $this->exitContext();
        $this->contexts[self::CONTEXT_NEW] = $newContext;

        return $arguments;
    }

    private function processNextAsIdentifier($getFullnspath = self::WITH_FULLNSPATH) {
        ++$this->id;

        $identifier = $this->addAtom('Identifier');
        $identifier->code       = $this->tokens[$this->id][1];
        $identifier->fullcode   = $this->tokens[$this->id][1];
        $identifier->line       = $this->tokens[$this->id][2];
        $identifier->token      = $this->getToken($this->tokens[$this->id][0]);

        if ($getFullnspath === self::WITH_FULLNSPATH) {
            list($fullnspath, $aliased) = $this->getFullnspath($identifier, 'class');
            $identifier->fullnspath = $fullnspath;
            $identifier->aliased    = $aliased;
        }

        return $identifier;
    }

    private function processConst() {
        $const = $this->addAtom('Const');
        $current = $this->id;
        $rank = -1;
        --$this->id; // back one step for the init in the next loop

        $options = array();
        foreach($this->optionsTokens as $name => $option) {
            $this->addLink($const, $option, strtoupper($name));
            $options[] = $this->atoms[$option->id]->fullcode;
        }
        $this->optionsTokens = array();

        $fullcode = array();
        do {
            ++$this->id;
            $constId = $this->id;
            $name = $this->processNextAsIdentifier();

            ++$this->id; // Skip =
            while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_SEMICOLON, \Exakat\Tasks\T_COMMA))) {
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

            list($fullnspath, $aliased) = $this->getFullnspath($name, 'const');
            $name->fullnspath = $fullnspath;
            $name->aliased    = $aliased;

            $this->addDefinition('const', $fullnspath, $def);

            $this->addLink($const, $def, 'CONST');
        } while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_SEMICOLON)));

        $const->code     = $this->tokens[$current][1];
        $const->fullcode = (empty($options) ? '' : join(' ', $options).' ').$this->tokens[$current][1].' '.implode(', ', $fullcode);
        $const->line     = $this->tokens[$current][2];
        $const->token    = $this->getToken($this->tokens[$current][0]);
        $const->count    = $rank + 1;

        $this->pushExpression($const);

        return $this->processFCOA($const);
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
        $var = $this->processOptions('Var');

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_VARIABLE) {
            $ppp = $this->processSGVariable('Ppp');
            return $ppp;
        } else {
            return $var;
        }
    }

    private function processPublic() {
        $public = $this->processOptions('Public');

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_VARIABLE) {
            $ppp = $this->processSGVariable('Ppp');
            $this->popExpression();
            return $ppp;
        } else {
            return $public;
        }
    }

    private function processProtected() {
        $protected = $this->processOptions('Protected');

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_VARIABLE) {
            $ppp = $this->processSGVariable('Ppp');
            $this->popExpression();
            return $ppp;
        } else {
            return $protected;
        }
    }

    private function processPrivate() {
        $private = $this->processOptions('Private');

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_VARIABLE) {
            $ppp = $this->processSGVariable('Ppp');
            $this->popExpression();
            return $ppp;
        } else {
            return $private;
        }
    }

    private function processFunctioncall($getFullnspath = self::WITH_FULLNSPATH) {
        $name = $this->popExpression();
        ++$this->id; // Skipping the name, set on (
        $current = $this->id;

        $arguments = $this->processArguments(array(\Exakat\Tasks\T_CLOSE_PARENTHESIS), false, $name->token === 'T_LIST');

        if ($this->isContext(self::CONTEXT_NEW)) {
            $atom = 'Newcall';
        } elseif ($getFullnspath === self::WITH_FULLNSPATH) {
            $atom = 'Functioncall';
        } else {
            $atom = 'Methodcallname';
        }
        
        $functioncall = $this->addAtom($atom);
        $functioncall->code      = $name->code;
        $functioncall->fullcode  = $name->fullcode.'('.$arguments->fullcode.')';
        $functioncall->line      = $this->tokens[$current][2];
        $functioncall->token     = $name->token;

        if ($this->isContext(self::CONTEXT_NEW)) {
            list($fullnspath, $aliased) = $this->getFullnspath($name, 'class');
            $functioncall->fullnspath = $fullnspath;
            $functioncall->aliased    = $aliased;

            $this->addCall('class', $fullnspath, $functioncall);
        } elseif ($getFullnspath === self::WITHOUT_FULLNSPATH) {
            // Nothing
        } else {
            list($fullnspath, $aliased) = $this->getFullnspath($name, 'function');
            $functioncall->fullnspath = $fullnspath;
            $functioncall->aliased    = $aliased;

            $name->fullnspath = $fullnspath;
            $name->aliased    = $aliased;

            // Probably weak check, since we haven't built fullnspath for functions yet...
            if (strtolower($name->code) === 'define') {
                $this->processDefineAsConstants($arguments);
            }

            $this->addCall('function', $fullnspath, $functioncall);

            static $deterministFunctions;
            
            if ($deterministFunctions === null) {
                $data = new Methods($this->config);
                $deterministFunctions = $data->getDeterministFunctions();
                $deterministFunctions = array_map(function ($x) { return '\\'.$x;}, $deterministFunctions);
                unset($data);
            }
            
            if (in_array($fullnspath, $deterministFunctions)) {
                $functioncall->boolean  = (int) (bool) $arguments->count;
                $functioncall->constant = ($arguments->constant === self::CONSTANT_EXPRESSION);
            }
        }

        $this->addLink($functioncall, $arguments, 'ARGUMENTS');
        $this->addLink($functioncall, $name, 'NAME');

        $this->pushExpression($functioncall);

        if ($getFullnspath === self::WITHOUT_FULLNSPATH) {
            return $functioncall;
        } elseif ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG
             && ($getFullnspath === self::WITH_FULLNSPATH) ) {
            $this->processSemicolon();
        } else {
            $functioncall = $this->processFCOA($functioncall);
        }

        return $functioncall;
    }

    private function processString() {
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_NS_SEPARATOR ) {
            return $this->processNsname();
        } elseif (in_array(strtolower($this->tokens[$this->id][1]), array('true', 'false'))) {
            $string = $this->addAtom('Boolean');
            $string->boolean  = (int) (strtolower($this->tokens[$this->id ][1]) === 'true');
            $string->constant = self::CONSTANT_EXPRESSION;
        } elseif (strtolower($this->tokens[$this->id][1]) === 'null') {
            $string = $this->addAtom('Null');
            $string->boolean  = 0;
            $string->constant = self::CONSTANT_EXPRESSION;
        } else {
            $string = $this->addAtom('Identifier');
            $string->constant = self::CONSTANT_EXPRESSION;
        }

        $string->code       = $this->tokens[$this->id][1];
        $string->fullcode   = $this->tokens[$this->id][1];
        $string->line       = $this->tokens[$this->id][2];
        $string->token      = $this->getToken($this->tokens[$this->id][0]);
        $string->absolute   = self::NOT_ABSOLUTE;

        $this->pushExpression($string);
        
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_DOUBLE_COLON ||
            $this->tokens[$this->id - 1][0] === \Exakat\Tasks\T_INSTANCEOF   ||
            $this->tokens[$this->id - 1][0] === \Exakat\Tasks\T_NEW
            ) {
            list($fullnspath, $aliased) = $this->getFullnspath($string, 'class');
            $string->fullnspath = $fullnspath;
            $string->aliased    = $aliased;

            $this->addCall('class', $fullnspath, $string);
        } else {
            list($fullnspath, $aliased) = $this->getFullnspath($string, 'const');
            $string->fullnspath = $fullnspath;
            $string->aliased    = $aliased;
        }

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $string->constant = self::CONSTANT_EXPRESSION;
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

            if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
                $this->processSemicolon();
            }
            
            return $plusplus;
        } else {
            // preplusplus
            $this->processSingleOperator('Preplusplus', $this->precedence->get($this->tokens[$this->id][0]), 'PREPLUSPLUS');
            $operator = $this->popExpression();
            $this->pushExpression($operator);

            return $operator;
        }
    }

    private function processStatic() {
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_DOUBLE_COLON ||
            $this->tokens[$this->id - 1][0] === \Exakat\Tasks\T_INSTANCEOF    ) {

            $identifier = $this->processSingle('Identifier');
            list($fullnspath, $aliased) = $this->getFullnspath($identifier, 'class');
            $identifier->fullnspath = $fullnspath;
            
            return $identifier;
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_PARENTHESIS) {
            $name = $this->addAtom('Identifier');
            $name->code       = $this->tokens[$this->id][1];
            $name->fullcode   = $this->tokens[$this->id][1];
            $name->line       = $this->tokens[$this->id][2];
            $name->token      = $this->getToken($this->tokens[$this->id][0]);

            list($fullnspath, $aliased) = $this->getFullnspath($name);
            $name->fullnspath = $fullnspath;
            $name->aliased    = $aliased;
                                          
            $this->pushExpression($name);

            return $this->processFunctioncall();
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_VARIABLE) {
            if (($this->isContext(self::CONTEXT_CLASS) ||
                 $this->isContext(self::CONTEXT_TRAIT)   ) &&
                !$this->isContext(self::CONTEXT_FUNCTION)) {
                // something like public static
                $this->processOptions('Static');

                $ppp = $this->processSGVariable('Ppp');
                $this->popExpression();

                return $ppp;
            } else {
                return $this->processStaticVariable();
            }
        } elseif ($this->isContext(self::CONTEXT_NEW)) {
            // new static; (no parenthesis, as tested above)

            --$this->id;
            $name = $this->processNextAsIdentifier();
            $this->pushExpression($name);

            return $name;
        } else {
            return $this->processOptions('Static');
        }
    }

    private function processSGVariable($atom) {
        $current = $this->id;
        $static = $this->addAtom($atom);
        $rank = 0;

        if ($atom === 'Global' || $atom === 'Static') {
            $fullcodePrefix = array($this->tokens[$this->id][1]);
            $link = strtoupper($atom);
            $atom .= 'definition';
        } else {
            $fullcodePrefix= array();
            $link = 'PPP';
            $atom = 'Propertydefinition';
        }
        
        foreach($this->optionsTokens as $name => $option) {
            $this->addLink($static, $option, strtoupper($name));
            $fullcodePrefix[] = $option->fullcode;
        }
        $fullcodePrefix = implode(' ', $fullcodePrefix);

        $this->optionsTokens = array();

        if (!isset($fullcodePrefix)) {
            $fullcodePrefix = $this->tokens[$current][1];
        }

        $fullcode = array();
        while ($this->tokens[$this->id + 1][0] !== \Exakat\Tasks\T_SEMICOLON) {
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_VARIABLE) {
                ++$this->id;
                $this->processSingle($atom);
                if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_EQUAL) {
                    $this->processNext();
                }
            } else {
                $this->processNext();
            }

            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COMMA) {
                $element = $this->popExpression();
                $element->rank = +$rank;
                $this->addLink($static, $element, $link);
                
                if ($atom === 'Propertydefinition') {
                    preg_match('/^\$([^ ]+)/', $element->fullcode, $r);
                    $element->propertyname = $r[1];
                }

                $fullcode[] = $element->fullcode;
                ++$this->id;
            }
        };
        $element = $this->popExpression();
        $this->addLink($static, $element, $link);

        if ($atom === 'Propertydefinition') {
            preg_match('/^\$([^ ]+)/', $element->fullcode, $r);
            $element->propertyname = $r[1];
        }
        $fullcode[] = $element->fullcode;

        $static->code     = $this->tokens[$current][1];
        $static->fullcode = $fullcodePrefix.' '.implode(', ', $fullcode);
        $static->line     = $this->tokens[$current][2];
        $static->token    = $this->getToken($this->tokens[$current][0]);
        $static->count    = $rank;

        $this->pushExpression($static);

        return $static;
    }

    private function processStaticVariable() {
        return $this->processSGVariable('Static');
    }

    private function processGlobalVariable() {
        return $this->processSGVariable('Global');
    }

    private function processBracket($followupFCOA = true) {
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
        do {
            $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_BRACKET, \Exakat\Tasks\T_CLOSE_CURLY))) ;

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

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        } elseif ($followupFCOA === true) {
            $bracket = $this->processFCOA($bracket);
        }

        return $bracket;
    }

    private function processBlock($standalone = true) {
        $this->startSequence();

        // Case for {}
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_CURLY) {
            $void = $this->addAtomVoid();
            $this->addToSequence($void);
        } else {
            while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_CURLY))) {
                $this->processNext();

                if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
                    $this->processSemicolon();
                }
            };

            if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
                $this->processSemicolon();
            }
        }

        $block = $this->sequence;
        $this->endSequence();

        $block->code     = '{}';
        $block->fullcode = static::FULLCODE_BLOCK;
        $block->line     = $this->tokens[$this->id][2];
        $block->token    = $this->getToken($this->tokens[$this->id][0]);
        $block->bracket  = Load::BRACKET;

        ++$this->id; // skip }

        $this->pushExpression($block);
        if ($standalone === true) {
            $this->processSemicolon();
        }

        return $block;
    }

    private function processForblock($finals) {
        $this->startSequence();
        $block = $this->sequence;
        $constant = self::CONSTANT_EXPRESSION;

        while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
            $element = $this->processNext();
            
            $constant = $constant && $element->constant;

            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COMMA) {
                $element = $this->popExpression();
                $this->addToSequence($element);

                ++$this->id;
            }
        };
        $element = $this->popExpression();
        $this->addToSequence($element);

        ++$this->id;
        $current = $this->sequence;
        $this->endSequence();
        $block->code     = $current->code;
        $block->fullcode = self::FULLCODE_SEQUENCE;
        $block->line     = $this->tokens[$this->id][2];
        $block->token    = $this->getToken($this->tokens[$this->id][0]);
        $block->constant = $constant;

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

        $this->processForblock(array(\Exakat\Tasks\T_SEMICOLON));
        $init = $this->popExpression();
        $this->addLink($for, $init, 'INIT');

        $this->processForblock(array(\Exakat\Tasks\T_SEMICOLON));
        $final = $this->popExpression();
        $this->addLink($for, $final, 'FINAL');

        $this->processForblock(array(\Exakat\Tasks\T_CLOSE_PARENTHESIS));
        $increment = $this->popExpression();
        $this->addLink($for, $increment, 'INCREMENT');

        $isColon = ($this->tokens[$current][0] === \Exakat\Tasks\T_FOR) && ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COLON);

        $block = $this->processFollowingBlock(array(\Exakat\Tasks\T_ENDFOR));
        $this->popExpression();
        $this->addLink($for, $block, 'BLOCK');

        $code = $this->tokens[$current][1];
        if ($isColon) {
            $fullcode = $this->tokens[$current][1].'('.$init->fullcode.' ; '.$final->fullcode.' ; '.$increment->fullcode.') : '.self::FULLCODE_SEQUENCE.' '.$this->tokens[$this->id + 1][1];
        } else {
            $fullcode = $this->tokens[$current][1].'('.$init->fullcode.' ; '.$final->fullcode.' ; '.$increment->fullcode.')'.($block->bracket === self::BRACKET ? self::FULLCODE_BLOCK : self::FULLCODE_SEQUENCE);
        }

        $for->code        = $code;
        $for->fullcode    = $fullcode;
        $for->line        = $this->tokens[$current][2];
        $for->token       = $this->getToken($this->tokens[$this->id][0]);
        $for->alternative = $isColon;

        $this->pushExpression($for);

        if ($isColon === true) {
            ++$this->id; // skip endfor
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_SEMICOLON) {
                ++$this->id; // skip ; (will do just below)
            }
        }
        $this->processSemicolon();

        return $for;
    }

    private function processForeach() {
        $foreach = $this->addAtom('Foreach');
        $current = $this->id;
        ++$this->id; // Skip foreach

        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_AS))) {
            $this->processNext();
        };

        $source = $this->popExpression();
        $this->addLink($foreach, $source, 'SOURCE');

        $as = $this->tokens[$this->id + 1][1];
        ++$this->id; // Skip as

        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_PARENTHESIS, \Exakat\Tasks\T_DOUBLE_ARROW))) {
            $this->processNext();
        };

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_DOUBLE_ARROW) {
            $this->processNext();
        }

        $value = $this->popExpression();
        $this->addLink($foreach, $value, 'VALUE');

        ++$this->id; // Skip )
        $isColon = ($this->tokens[$current][0] === \Exakat\Tasks\T_FOREACH) && ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COLON);

        $block = $this->processFollowingBlock($isColon === true ? array(\Exakat\Tasks\T_ENDFOREACH) : array());

        $this->popExpression();
        $this->addLink($foreach, $block, 'BLOCK');

        if ($isColon === true) {
            ++$this->id; // skip endforeach
            $fullcode = $this->tokens[$current][1].'('.$source->fullcode.' '.$as.' '.$value->fullcode.') : '.self::FULLCODE_SEQUENCE.' endforeach';
        } else {
            $fullcode = $this->tokens[$current][1].'('.$source->fullcode.' '.$as.' '.$value->fullcode.')'.($block->bracket === self::BRACKET ? self::FULLCODE_BLOCK : self::FULLCODE_SEQUENCE);
        }

        $foreach->code        = $this->tokens[$current][1];
        $foreach->fullcode    = $fullcode;
        $foreach->line        = $this->tokens[$current][2];
        $foreach->token       = $this->getToken($this->tokens[$current][0]);
        $foreach->alternative = $isColon;

        $this->pushExpression($foreach);
        $this->processSemicolon();

        if ($this->tokens[$this->id][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            --$this->id;
        }

        return $foreach;
    }

    private function processFollowingBlock($finals) {
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_CURLY) {
            ++$this->id;
            $block = $this->processBlock(false);
            $block->bracket = self::BRACKET;
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COLON) {
            $this->startSequence();
            $block = $this->sequence;
            ++$this->id; // skip :

            while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
                $this->processNext();
            };

            $this->pushExpression($this->sequence);
            $this->endSequence();

        } elseif (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_SEMICOLON))) {
            // void; One epxression block, with ;
            $this->startSequence();
            $block = $this->sequence;

            $void = $this->addAtomVoid();
            $this->addToSequence($void);
            $this->endSequence();
            $this->pushExpression($block);
            ++$this->id;

        } elseif (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_TAG, \Exakat\Tasks\T_CLOSE_CURLY, \Exakat\Tasks\T_CLOSE_PARENTHESIS))) {
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
            $finals = array_merge(array(\Exakat\Tasks\T_SEMICOLON, \Exakat\Tasks\T_CLOSE_TAG, \Exakat\Tasks\T_ELSE, \Exakat\Tasks\T_END, \Exakat\Tasks\T_CLOSE_CURLY), $finals);
            $specials = array(\Exakat\Tasks\T_IF, \Exakat\Tasks\T_FOREACH, \Exakat\Tasks\T_SWITCH, \Exakat\Tasks\T_FOR, \Exakat\Tasks\T_TRY, \Exakat\Tasks\T_WHILE);
//, \Exakat\Tasks\T_EXIT
            if (in_array($this->tokens[$this->id + 1][0], $specials)) {
                $this->processNext();
            } else {
                while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
                    $this->processNext();
                };
                $expression = $this->popExpression();
                $this->addToSequence($expression);
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

        $block = $this->processFollowingBlock(array(\Exakat\Tasks\T_WHILE));
        $this->popExpression();
        $this->addLink($dowhile, $block, 'BLOCK');

        $while = $this->tokens[$this->id + 1][1];
        ++$this->id; // Skip while
        ++$this->id; // Skip (

        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_PARENTHESIS))) {
            $this->processNext();
        };
        ++$this->id; // skip )
        $condition = $this->popExpression();
        $this->addLink($dowhile, $condition, 'CONDITION');

        $dowhile->code     = $this->tokens[$current][1];
        $dowhile->fullcode = $this->tokens[$current][1].( $block->bracket === self::BRACKET ? self::FULLCODE_BLOCK : self::FULLCODE_SEQUENCE).$while.'('.$condition->fullcode.')';
        $dowhile->line     = $this->tokens[$current][2];
        $dowhile->token    = $this->getToken($this->tokens[$current][0]);
        $this->pushExpression($dowhile);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $dowhile;
    }

    private function processWhile() {
        $while = $this->addAtom('While');
        $current = $this->id;

        ++$this->id; // Skip while

        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_PARENTHESIS))) {
            $this->processNext();
        };
        $condition = $this->popExpression();
        $this->addLink($while, $condition, 'CONDITION');

        ++$this->id; // Skip )
        $isColon = ($this->tokens[$current][0] === \Exakat\Tasks\T_WHILE) && ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COLON);
        $block = $this->processFollowingBlock(array(\Exakat\Tasks\T_ENDWHILE));
        $this->popExpression();

        $this->addLink($while, $block, 'BLOCK');

        if ($isColon === true) {
            ++$this->id;
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_SEMICOLON) {
                ++$this->id; // skip ;
            }

            $fullcode = $this->tokens[$current][1].' ('.$condition->fullcode.') : '.self::FULLCODE_SEQUENCE.' '.$this->tokens[$this->id - 1][1];
        } else {
            $fullcode = $this->tokens[$current][1].' ('.$condition->fullcode.')'.($block->bracket === self::BRACKET ? self::FULLCODE_BLOCK : self::FULLCODE_SEQUENCE);
        }

        $while->code        = $this->tokens[$current][1];
        $while->fullcode    = $fullcode;
        $while->line        = $this->tokens[$current][2];
        $while->token       = $this->getToken($this->tokens[$current][0]);
        $while->alternative = $isColon;

        $this->pushExpression($while);
        $this->processSemicolon();

        return $while;
    }

    private function processDeclare() {
        $declare = $this->addAtom('Declare');
        $current = $this->id;

        ++$this->id; // Skip declare
        $args = $this->processArguments();
        $this->addLink($declare, $args, 'DECLARE');
        $isColon = ($this->tokens[$current][0] === \Exakat\Tasks\T_DECLARE) && ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COLON);

        $block = $this->processFollowingBlock(array(\Exakat\Tasks\T_ENDDECLARE));
        $this->popExpression();
        $this->addLink($declare, $block, 'BLOCK');

        if ($isColon === true) {
            $fullcode = $this->tokens[$current][1].' ('.$args->fullcode.') : '.self::FULLCODE_SEQUENCE.' '.$this->tokens[$this->id + 1][1];
            ++$this->id; // skip enddeclare
            ++$this->id; // skip ;
        } else {
            $fullcode = $this->tokens[$current][1].' ('.$args->fullcode.') '.self::FULLCODE_BLOCK;
        }
        $this->pushExpression($declare);
        $this->processSemicolon();

        $declare->code        = $this->tokens[$current][1];
        $declare->fullcode    = $fullcode;
        $declare->line        = $this->tokens[$current][2];
        $declare->token       = $this->getToken($this->tokens[$current][0]);
        $declare->alternative = $isColon ;
        
        return $declare;
    }

    private function processDefault() {
        $default = $this->addAtom('Default');
        $current = $this->id;
        ++$this->id; // Skip : or ;

        $this->startSequence();
        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_CURLY, \Exakat\Tasks\T_CASE, \Exakat\Tasks\T_DEFAULT, \Exakat\Tasks\T_ENDSWITCH))) {
            $this->processNext();
        };
        $this->addLink($default, $this->sequence, 'CODE');
        $this->endSequence();

        $default->code     = $this->tokens[$current][1];
        $default->fullcode = $this->tokens[$current][1].' : '.self::FULLCODE_SEQUENCE;
        $default->line     = $this->tokens[$current][2];
        $default->token    = $this->getToken($this->tokens[$current][0]);

        $this->pushExpression($default);

        return $default;
    }

    private function processCase() {
        $case = $this->addAtom('Case');
        $current = $this->id;

        $this->nestContext();
        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_COLON, \Exakat\Tasks\T_SEMICOLON))) {
            $this->processNext();
        };
        $this->exitContext();

        $item = $this->popExpression();
        $this->addLink($case, $item, 'CASE');

        ++$this->id; // Skip :

        $this->startSequence();
        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_CURLY, \Exakat\Tasks\T_CASE, \Exakat\Tasks\T_DEFAULT, \Exakat\Tasks\T_ENDSWITCH))) {
            $this->processNext();
        };
        $this->addLink($case, $this->sequence, 'CODE');
        $this->endSequence();

        $case->code     = $this->tokens[$current][1].' '.$item->fullcode.' : '.self::FULLCODE_SEQUENCE.' ';
        $case->fullcode = $this->tokens[$current][1].' '.$item->fullcode.' : '.self::FULLCODE_SEQUENCE.' ';
        $case->line     = $this->tokens[$current][2];
        $case->token    = $this->getToken($this->tokens[$current][0]);
        $this->pushExpression($case);

        return $case;
    }

    private function processSwitch() {
        $switch = $this->addAtom('Switch');
        $current = $this->id;
        ++$this->id; // Skip (

        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_PARENTHESIS))) {
            $this->processNext();
        };
        $name = $this->popExpression();
        $this->addLink($switch, $name, 'NAME');

        $cases = $this->addAtom('Sequence');
        $cases->code     = self::FULLCODE_SEQUENCE;
        $cases->fullcode = self::FULLCODE_SEQUENCE;
        $cases->line     = $this->tokens[$current][2];
        $cases->token    = $this->getToken($this->tokens[$current][0]);
        $cases->bracket  = self::BRACKET;

        $this->addLink($switch, $cases, 'CASES');
        ++$this->id;

        $isColon = $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COLON;

        $rank = 0;
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_PARENTHESIS) {
            $void = $this->addAtomVoid();
            $this->addLink($cases, $void, 'ELEMENT');
            $void->rank = $rank;

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

                $case = $this->popExpression();
                $this->addLink($cases, $case, 'ELEMENT');
                $case->rank = ++$rank;
            };
        }
        ++$this->id;
        $cases->count = $rank;

        if ($isColon) {
            $fullcode = $this->tokens[$current][1].' ('.$name->fullcode.') :'.self::FULLCODE_SEQUENCE.' '.$this->tokens[$this->id][1];
        } else {
            $fullcode = $this->tokens[$current][1].' ('.$name->fullcode.')'.self::FULLCODE_BLOCK;
        }

        $switch->code        = $this->tokens[$current][1];
        $switch->fullcode    = $fullcode;
        $switch->line        = $this->tokens[$current][2];
        $switch->token       = $this->getToken($this->tokens[$current][0]);
        $switch->alternative = $isColon;

        $this->pushExpression($switch);
        $this->processSemicolon();

        return $switch;
    }

    private function processIfthen() {
        $ifthen = $this->addAtom('Ifthen');
        $current = $this->id;
        ++$this->id; // Skip (

        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_PARENTHESIS))) {
            $this->processNext();
        };
        $condition = $this->popExpression();
        $this->addLink($ifthen, $condition, 'CONDITION');

        ++$this->id; // Skip )
        $isInitialIf = $this->tokens[$current][0] === \Exakat\Tasks\T_IF;
        $isColon =  $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COLON;

        $then = $this->processFollowingBlock(array(\Exakat\Tasks\T_ENDIF, \Exakat\Tasks\T_ELSE, \Exakat\Tasks\T_ELSEIF));
        $this->popExpression();
        $this->addLink($ifthen, $then, 'THEN');

        // Managing else case
        if (in_array($this->tokens[$this->id][0], array(\Exakat\Tasks\T_END, \Exakat\Tasks\T_CLOSE_TAG))) {
            $elseFullcode = '';
            // No else, end of a script
            --$this->id;
            // Back up one unit to allow later processing for sequence
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_ELSEIF){
            ++$this->id;

            $elseif = $this->processIfthen();
            $this->addLink($ifthen, $elseif, 'ELSE');

            $elseFullcode = $elseif->fullcode;

        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_ELSE){
            $elseFullcode = $this->tokens[$this->id + 1][1];
            ++$this->id; // Skip else

            $else = $this->processFollowingBlock(array(\Exakat\Tasks\T_ENDIF));
            $this->popExpression();
            $this->addLink($ifthen, $else, 'ELSE');

            if ($isColon === true) {
                $elseFullcode .= ' :';
            }
            $elseFullcode .= $else->fullcode;
        } else {
            $elseFullcode = '';
        }

        if ($isInitialIf === true && $isColon === true) {
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_SEMICOLON) {
                ++$this->id; // skip ;
            }
            ++$this->id; // skip ;
        }

        if ($isColon) {
            $fullcode = $this->tokens[$current][1].'('.$condition->fullcode.') : '.$then->fullcode.$elseFullcode.($isInitialIf === true ? ' endif' : '');
        } else {
            $fullcode = $this->tokens[$current][1].'('.$condition->fullcode.')'.$then->fullcode.$elseFullcode;
        }

        if ($this->tokens[$current][0] === \Exakat\Tasks\T_IF) {
            $this->pushExpression($ifthen);
            $this->processSemicolon();
        }

        if ($this->tokens[$this->id][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            --$this->id;
        }

        $ifthen->code        = $this->tokens[$current][1];
        $ifthen->fullcode    = $fullcode;
        $ifthen->line        = $this->tokens[$current][2];
        $ifthen->token       = $this->getToken($this->tokens[$current][0]);
        $ifthen->alternative = $isColon;

        return $ifthen;
    }

    private function processParenthesis() {
        $parenthese = $this->addAtom('Parenthesis');

        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_PARENTHESIS))) {
            $this->processNext();
        };

        $code = $this->popExpression();
        $this->addLink($parenthese, $code, 'CODE');

        $parenthese->code     = '(';
        $parenthese->fullcode = '('.$code->fullcode.')';
        $parenthese->line     = $this->tokens[$this->id][2];
        $parenthese->token    = 'T_OPEN_PARENTHESIS';
        $parenthese->constant = $code->constant;

        $this->pushExpression($parenthese);
        ++$this->id; // Skipping the )

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        } else {
            $parenthese = $this->processFCOA($parenthese);
        }

        return $parenthese;
    }

    private function processExit() {
        if (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_PARENTHESIS, \Exakat\Tasks\T_SEMICOLON, \Exakat\Tasks\T_CLOSE_TAG, \Exakat\Tasks\T_CLOSE_BRACKET, \Exakat\Tasks\T_COLON))) {
            $name = $this->addAtom('Identifier');
            $name->code       = $this->tokens[$this->id][1];
            $name->fullcode   = $this->tokens[$this->id][1];
            $name->line       = $this->tokens[$this->id][2];
            $name->token      = $this->getToken($this->tokens[$this->id][0]);
            $name->fullnspath = '\\'.strtolower($this->tokens[$this->id][1]);

            $void = $this->addAtomVoid();
            $void->rank = 0;

            $arguments = $this->addAtom('Arguments');
            $this->addLink($arguments, $void, 'ARGUMENT');
            $arguments->code     = $void->code;
            $arguments->fullcode = $void->fullcode;
            $arguments->line     = $this->tokens[$this->id][2];
            $arguments->count    = 1;
            $arguments->token    = $this->getToken($this->tokens[$this->id][0]);

            $functioncall = $this->addAtom('Functioncall');
            $functioncall->code       = $name->code;
            $functioncall->fullcode   = $name->fullcode.' '.($arguments->atom === 'Void' ? self::FULLCODE_VOID :  $arguments->fullcode);
            $functioncall->line       = $this->tokens[$this->id][2];
            $functioncall->token      = $this->getToken($this->tokens[$this->id][0]);
            $functioncall->fullnspath = '\\'.strtolower($name->code);

            $this->addLink($functioncall, $arguments, 'ARGUMENTS');
            $this->addLink($functioncall, $name, 'NAME');

            $this->pushExpression($functioncall);

            if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
                $this->processSemicolon();
            }

            return $functioncall;
        } else {
            --$this->id;
            $name = $this->processNextAsIdentifier();
            $this->pushExpression($name);

            if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
                $this->processSemicolon();
            } else {
                $name = $this->processFCOA($name);
            }

            return $name;
        }
    }

    private function processArrayLiteral() {
        $current = $this->id;

        if ($this->tokens[$current][0] === \Exakat\Tasks\T_ARRAY) {
            ++$this->id; // Skipping the name, set on (
            $arguments = $this->processArguments();
        } else {
            $arguments = $this->processArguments(array(\Exakat\Tasks\T_CLOSE_BRACKET));
        }
        
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_EQUAL) {
            // This is a T_LIST !
            $array  = $this->addAtom('Functioncall');
            $array->token      = 'T_OPEN_BRACKET';
            $array->fullnspath = '\list';
            $array->fullcode  = '['.$arguments->fullcode.']';
        } else {
            $array = $this->addAtom('Arrayliteral');
            $array->token      = $this->getToken($this->tokens[$current][0]);

            if ($this->tokens[$current][0] === \Exakat\Tasks\T_ARRAY) {
                $array->fullcode  = $this->tokens[$current][1].'('.$arguments->fullcode.')';
            } else {
                $array->fullcode  = '['.$arguments->fullcode.']';
            }
        }

        $this->addLink($array, $arguments, 'ARGUMENTS');
        $array->code      = $this->tokens[$current][1];

        $array->line      = $this->tokens[$current][2];
        $array->boolean    = (int) (bool) $arguments->count;
        $array->constant   = $arguments->constant;
        $this->pushExpression($array);
        
        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
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

        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_COLON)) ) {
            $this->processNext();
        };
        $then = $this->popExpression();
        ++$this->id; // Skip colon

        $finals = $this->precedence->get($this->tokens[$this->id][0]);
        $finals[] = \Exakat\Tasks\T_COLON; // Added from nested Ternary
        $finals[] = \Exakat\Tasks\T_CLOSE_TAG;

        $this->nestContext();
        do {
            $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], $finals) );
        $this->exitContext();

        $else = $this->popExpression();

        $this->addLink($ternary, $condition, 'CONDITION');
        $this->addLink($ternary, $then, 'THEN');
        $this->addLink($ternary, $else, 'ELSE');

        $ternary->code     = '?';
        $ternary->fullcode = $condition->fullcode.' ?'.($then->atom === 'Void' ? '' : ' '.$then->fullcode.' ' ).': '.$else->fullcode;
        $ternary->line     = $this->tokens[$current][2];
        $ternary->token    = 'T_QUESTION';
        $ternary->constant = $condition->constant && $then->constant && $else->constant;

        $this->pushExpression($ternary);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $ternary;
    }

    //////////////////////////////////////////////////////
    /// processing single tokens
    //////////////////////////////////////////////////////
    private function processSingle($atom) {
        $atom = $this->addAtom($atom);
        if (strlen($this->tokens[$this->id][1]) > 100000) {
            $this->tokens[$this->id][1] = substr($this->tokens[$this->id][1], 0, 100000).PHP_EOL."[.... 100000 / ".strlen($this->tokens[$this->id][1])."]".PHP_EOL;
        }
        $atom->code     = $this->tokens[$this->id][1];
        $atom->fullcode = $this->tokens[$this->id][1];
        $atom->line     = $this->tokens[$this->id][2];
        $atom->token    = $this->getToken($this->tokens[$this->id][0]);

        $this->pushExpression($atom);

        return $atom;
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

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_CURLY) {
            $name = $this->addAtomVoid();
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_NS_SEPARATOR) {
            $nsname = $this->processOneNsname();

            list($fullnspath, $aliased) = $this->getFullnspath($nsname);
            $nsname->fullnspath = $fullnspath;
            $nsname->aliased    = $aliased;
            $this->pushExpression($nsname);

            return $this->processFCOA($nsname);
        } else {
            $name = $this->processOneNsname();
        }

        $namespace = $this->addAtom('Namespace');
        $this->addLink($namespace, $name, 'NAME');
        $this->setNamespace($name);

        // Here, we make sure namespace is encompassing the next elements.
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_SEMICOLON) {
            // Process block
            ++$this->id; // Skip ; to start actual sequence
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_END) {
                $void = $this->addAtomVoid();
                $block = $this->addAtom('Sequence');
                $block->code       = '{}';
                $block->fullcode   = self::FULLCODE_BLOCK;
                $block->line       = $this->tokens[$this->id][2];
                $block->token      = $this->getToken($this->tokens[$this->id][0]);
                $block->bracket    = self::NOT_BRACKET;

                $this->addLink($block, $void, 'ELEMENT');
            } else {
                $block = $this->processNamespaceBlock();
            }
            $this->addLink($namespace, $block, 'BLOCK');
            $this->addToSequence($namespace);
            $block = ';';
        } else {
            // Process block
            $this->processFollowingBlock(false);
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
        if (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_PRIVATE, \Exakat\Tasks\T_PUBLIC, \Exakat\Tasks\T_PROTECTED))) {
            $current = $this->id;
            $as = $this->addAtom('As');

            $left = $this->popExpression();
            $this->addLink($as, $left, 'NAME');

            if (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_PRIVATE, \Exakat\Tasks\T_PROTECTED, \Exakat\Tasks\T_PUBLIC))) {
                $visibility = $this->processNextAsIdentifier();
                $this->addLink($as, $visibility, strtoupper($visibility->code));
            }

            if (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_COMMA, \Exakat\Tasks\T_SEMICOLON))) {
                $alias = $this->processNextAsIdentifier();
                $this->addLink($as, $alias, 'AS');
            } else {
                $alias = $this->addAtomVoid();
                $this->addLink($as, $alias, 'AS');
            }

            $as->code     = $this->tokens[$current][1];
            $as->fullcode = $left->fullcode.' '.$this->tokens[$current][1].' '.(isset($visibility) ? $visibility->fullcode.' ' : '').$alias->fullcode;
            $as->line     = $this->tokens[$current][2];
            $as->token    = $this->getToken($this->tokens[$current][0]);

            $this->pushExpression($as);

            return $as;
        } else {
            return $this->processOperator('As', $this->precedence->get($this->tokens[$this->id][0]), array('NAME', 'AS'));
        }
    }

    private function processInsteadof() {
        $insteadof = $this->processOperator('Insteadof', $this->precedence->get($this->tokens[$this->id][0]), array('NAME', 'INSTEADOF'));
        while ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COMMA) {
            ++$this->id;
            $nsname = $this->processOneNsname();

            $this->addLink($insteadof, $nsname, 'INSTEADOF');
        }
        return $insteadof;
    }

    private function processUse() {
        $use = $this->addAtom('Use');
        $current = $this->id;
        $useType = 'class';

        $fullcode = array();

        // use const
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CONST) {
            ++$this->id;

            $this->processSingle('Identifier');
            $const = $this->popExpression();
            $this->addLink($use, $const, 'CONST');
            $useType = 'const';
        }

        // use function
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_FUNCTION) {
            ++$this->id;

            $this->processSingle('Identifier');
            $const = $this->popExpression();
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
            
            $fullnspath = strtolower($namespace->fullcode);
            if ($fullnspath[0] !== '\\') {
                list($prefix, ) = explode('\\', $fullnspath);
                $fullnspath = '\\'.$fullnspath;
            }

            $this->addCall('class', $fullnspath, $namespace);

            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_AS) {
                // use A\B as C
                ++$this->id;

                $alias = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);

                $as = $this->addAtom('As');
                $this->addLink($as, $namespace, 'NAME');
                $this->addLink($as, $alias, 'ALIAS');
                $as->fullcode = $namespace->fullcode. ' '.$this->tokens[$this->id - 1][1].' '.$alias->fullcode;
                $fullcode[] = $as->fullcode;
                $as->token = 'T_AS';
                $as->line = $this->tokens[$this->id - 1][2];
                $as->code = $this->tokens[$this->id - 1][1];
                $as->fullnspath = $fullnspath;
                if (isset($this->uses['class'][$prefix])) {
                    $this->addLink($as, $this->uses['class'][$prefix], 'DEFINITION');
                }
                $this->addLink($use, $as, 'USE');

                if (!$this->isContext(self::CONTEXT_CLASS) &&
                    !$this->isContext(self::CONTEXT_TRAIT) ) {
                    $alias = $this->addNamespaceUse($origin, $alias, $useType, $as);

                    $as->alias  = $alias;
                    $as->origin = $fullnspath;
                }

                $namespace = $as;
            } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_CURLY) {
                //use A\B{} // Group
                $block = $this->processFollowingBlock(array(\Exakat\Tasks\T_CLOSE_CURLY));
                $this->popExpression();
                $this->addLink($use, $block, 'BLOCK');
                $fullcode[] = $namespace->fullcode.' '.$block->fullcode;

                // Several namespaces ? This has to be recalculated inside the block!!
                $namespace->fullnspath = $this->makeFullnspath($namespace);

                $this->addLink($use, $namespace, 'USE');
            } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_NS_SEPARATOR) {
                //use A\B\ {} // Prefixes, within a Class/Trait
                $this->addLink($use, $namespace, 'GROUPUSE');
                $prefix = $this->makeFullnspath($namespace);
                if ($prefix[0] !== '\\') {
                    $prefix = '\\'.$prefix;
                }
                $prefix .= '\\';

                ++$this->id; // Skip \

                $useTypeGeneric = $useType;
                $useTypeAtom = 0;
                do {
                    ++$this->id; // Skip {

                    $useType = $useTypeGeneric;
                    $useTypeAtom = 0;
                    if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CONST) {
                        // use const
                        ++$this->id;

                        $this->processSingle('Identifier');
                        $useTypeAtom = $this->popExpression();
                        $useType = 'const';
                    }

                    if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_FUNCTION) {
                        // use function
                        ++$this->id;

                        $this->processSingle('Identifier');
                        $useTypeAtom = $this->popExpression();
                        $useType = 'function';
                    }

                    $nsname = $this->processOneNsname();
                    if ($useTypeAtom !== 0) {
                        $this->addLink($nsname, $useTypeAtom, strtoupper($useType));
                    }

                    if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_AS) {
                        // A\B as C
                        ++$this->id;
                        $this->pushExpression($nsname);
                        $this->processAs();
                        $alias = $this->popExpression();

                        $nsname->fullnspath = $prefix.strtolower($nsname->fullcode);
                        $nsname->origin     = $prefix.strtolower($nsname->fullcode);

                        $alias->fullnspath  = $prefix.strtolower($nsname->fullcode);
                        $alias->origin      = $prefix.strtolower($nsname->fullcode);

                        $aliasName = $this->addNamespaceUse($nsname, $alias, $useType, $alias);
                        $alias->alias = $aliasName;
                        $this->addLink($use, $alias, 'USE');
                    } else {
                        $this->addLink($use, $nsname, 'USE');
                        $nsname->fullnspath = $prefix.strtolower($nsname->fullcode);
                        $nsname->origin     = $prefix.strtolower($nsname->fullcode);

                        $alias = $this->addNamespaceUse($nsname, $nsname, $useType, $nsname);
                        $nsname->alias = $alias;

                    }
                } while (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_COMMA)));

                $fullcode[] = $namespace->fullcode.self::FULLCODE_BLOCK;

                ++$this->id; // Skip }
            } else {
                $this->addLink($use, $namespace, 'USE');

                if (!$this->isContext(self::CONTEXT_CLASS) &&
                    !$this->isContext(self::CONTEXT_TRAIT) ) {

                    $fullnspath = $this->makeFullnspath($namespace);
                    $namespace->fullnspath = $fullnspath;
                    $namespace->origin     = $fullnspath;

                    if (isset($this->uses['class'][$prefix])) {
                        $this->addLink($namespace, $this->uses['class'][$prefix], 'DEFINITION');
                    }

                    $namespace->fullnspath = $fullnspath;

                    $alias = $this->addNamespaceUse($alias, $alias, $useType, $namespace);

                    $namespace->alias = $alias;
                    $origin->alias = $alias;
 
                } else {
                    if (isset($this->uses['class'][$prefix])) {
                        $this->addLink($namespace, $this->uses['class'][$prefix], 'DEFINITION');
                        $namespace->fullnspath = $this->uses['class'][$prefix]->fullnspath;

                        $this->addCall('class', $namespace->fullnspath, $namespace);
                    } else {
                        list($fullnspath, $aliased) = $this->getFullnspath($namespace, 'class');

                        $namespace->fullnspath      = $fullnspath;
                        $this->addCall('class', $namespace->fullnspath, $namespace);
                    }
                }

                $fullcode[] = $namespace->fullcode;
            }
            // No Else. Default will be dealt with by while() condition

        } while ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_COMMA);

        $use->code     = $this->tokens[$current][1];
        $use->fullcode = $this->tokens[$current][1].(isset($const) ? ' '.$const->code : '').' '.implode(", ", $fullcode);
        $use->line     = $this->tokens[$current][2];
        $use->token    = $this->getToken($this->tokens[$current][0]);

        $this->pushExpression($use);

        return $use;
    }

    private function processVariable() {
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OBJECT_OPERATOR) {
            $atom = 'Variableobject';
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_BRACKET) {
            $atom = 'Variablearray';
        } else {
            $atom = 'Variable';
        }
        $variable = $this->processSingle($atom);
        if ($this->tokens[$this->id][1] === '$this') {
            $currentClass = end($this->currentClassTrait);
            if ($currentClass instanceof Atom) {
                $this->addCall('class', end($this->currentClassTrait)->fullnspath, $variable);
            }
        }

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        } else {
             $variable = $this->processFCOA($variable);
        }

        return $variable;
    }

    private function processFCOA($nsname) {
        // For functions and constants
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_PARENTHESIS) {
            return $this->processFunctioncall();
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_BRACKET &&
                  $this->tokens[$this->id + 2][0] === \Exakat\Tasks\T_CLOSE_BRACKET) {
            return $this->processAppend();
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_BRACKET ||
                  $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_CURLY) {
            return $this->processBracket();
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_DOUBLE_COLON ||
                  $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_NS_SEPARATOR ||
                  $this->tokens[$this->id - 1][0] === \Exakat\Tasks\T_INSTANCEOF   ||
                  $this->tokens[$this->id - 1][0] === \Exakat\Tasks\T_AS) {
            return $nsname;
        } elseif (in_array($nsname->atom, array('Nsname', 'Identifier'))) {

            $type = $this->isContext(self::CONTEXT_NEW) ? 'class' : 'const';
            
            list($fullnspath, $aliased) = $this->getFullnspath($nsname, $type);
            $nsname->fullnspath = $fullnspath;
            $nsname->aliased    = $aliased;

            if ($type === 'const') {
                $this->addCall('const', $fullnspath, $nsname);
                $nsname->constant = self::CONSTANT_EXPRESSION;
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

        ++$this->id;
        ++$this->id;

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        } else {
            // Mostly for arrays
            $append = $this->processFCOA($append);
        }

        return $append;
    }

    private function processInteger() {
        $integer = $this->processSingle('Integer');
        $value = $integer->code;

        if (strtolower(substr($value, 0, 2)) === '0b') {
            $actual = bindec(substr($value, 2));
        } elseif (strtolower(substr($value, 0, 2)) === '0x') {
            $actual = hexdec(substr($value, 2));
        } elseif (strtolower($value[0]) === '0') {
            // PHP 7 will just stop.
            // PHP 5 will work until it fails
            $actual = octdec(substr($value, 1));
        } else {
            $actual = $value;
        }
        $integer->intval  = abs($actual) > PHP_INT_MAX ? 0 : $actual;
        $integer->boolean = (int) (boolean) $value;
        $integer->constant = self::CONSTANT_EXPRESSION;

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }
        
        return $integer;
    }

    private function processReal() {
        $real = $this->processSingle('Real');
        $real->boolean  = (int) (strtolower($this->tokens[$this->id][1]) != 0);
        $real->constant = self::CONSTANT_EXPRESSION;

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $real;
    }

    private function processLiteral() {
        $literal = $this->processSingle('String');
        $literal->constant = self::CONSTANT_EXPRESSION;
        
        if ($this->tokens[$this->id][0] === \Exakat\Tasks\T_CONSTANT_ENCAPSED_STRING) {
            $literal->delimiter   = $literal->code[0];
            if ($literal->delimiter === 'b' || $literal->delimiter === 'B') {
                $literal->binaryString = $literal->delimiter;
                $literal->delimiter    = $literal->code[1];
                $literal->noDelimiter  = substr($literal->code, 2, -1);
            } else {
                $literal->noDelimiter = substr($literal->code, 1, -1);
            }

            $this->addNoDelimiterCall($literal);
        } elseif ($this->tokens[$this->id][0] === \Exakat\Tasks\T_NUM_STRING) {
            $literal->delimiter   = '';
            $literal->noDelimiter = $literal->code;

            $this->addNoDelimiterCall($literal);
        } else {
            $literal->delimiter   = '';
            $literal->noDelimiter = '';
        }

        $literal->boolean   = (int) (bool) $literal->noDelimiter;

        if (function_exists('mb_detect_encoding')) {
            $literal->encoding = mb_detect_encoding($literal->noDelimiter);
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_BRACKET) {
                $literal = $this->processBracket();
            }
        }

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_PARENTHESIS) {
            $literal = $this->processFCOA($literal);
        }

        return $literal;
    }

    private function processMagicConstant() {
        return $this->processSingle('Magicconstant');
    }

    //////////////////////////////////////////////////////
    /// processing single operators
    //////////////////////////////////////////////////////
    private function processSingleOperator($atom, $finals, $link, $separator = '') {
        $current = $this->id;

        $operator = $this->addAtom($atom);
        $this->nestContext();
        // Do while, so that AT least one loop is done.
        do {
            $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], $finals));
        $this->exitContext();

        $operand = $this->popExpression();
        $this->addLink($operator, $operand, $link);

        $operator->code      = $this->tokens[$current][1];
        $operator->fullcode  = $this->tokens[$current][1].$separator.$operand->fullcode;
        $operator->line      = $this->tokens[$current][2];
        $operator->token     = $this->getToken($this->tokens[$current][0]);

        $this->pushExpression($operator);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

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
        if (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_TAG, \Exakat\Tasks\T_SEMICOLON))) {
            $current = $this->id;

            // Case of return ;
            $return = $this->addAtom('Return');

            $returnArg = $this->addAtomVoid();
            $this->addLink($return, $returnArg, 'RETURN');

            $return->code     = $this->tokens[$current][1];
            $return->fullcode = $this->tokens[$current][1].' ;';
            $return->line     = $this->tokens[$current][2];
            $return->token    = $this->getToken($this->tokens[$current][0]);
            $return->constant = self::CONSTANT_EXPRESSION;

            $this->pushExpression($return);
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
                $this->processSemicolon();
            }

            return $return;
        } else {
            $return = $this->processSingleOperator('Return', $this->precedence->get($this->tokens[$this->id][0]), 'RETURN', ' ');
            $operator = $this->popExpression();
            $this->pushExpression($operator);
            $return->constant = $operator->constant;

            if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
                $this->processSemicolon();
            }

            return $operator;
        }
    }

    private function processThrow() {
        $this->processSingleOperator('Throw', $this->precedence->get($this->tokens[$this->id][0]), 'THROW', ' ');
        $operator = $this->popExpression();
        $this->pushExpression($operator);
        return $operator;
    }

    private function processYield() {
        if (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_PARENTHESIS, \Exakat\Tasks\T_SEMICOLON, \Exakat\Tasks\T_CLOSE_TAG))) {
            $current = $this->id;

            // Case of return ;
            $returnArg = $this->addAtomVoid();
            $yield = $this->addAtom('Yield');

            $this->addLink($yield, $returnArg, 'YIELD');

            $yield->code     = $this->tokens[$current][1];
            $yield->fullcode = $this->tokens[$current][1].' ;';
            $yield->line     = $this->tokens[$current][2];
            $yield->token    = $this->getToken($this->tokens[$current][0]);

            $this->pushExpression($yield);

            return $yield;
        } else {
            $yield = $this->processSingleOperator('Yield', $this->precedence->get($this->tokens[$this->id][0]), 'YIELD', ' ');
            $operator = $this->popExpression();
            $this->pushExpression($operator);
            $yield->constant = $operator->constant;

            return $operator;
        }
    }

    private function processYieldfrom() {
        $this->processSingleOperator('Yieldfrom', $this->precedence->get($this->tokens[$this->id][0]), 'YIELD', ' ');
        $operatorId = $this->popExpression();
        $this->pushExpression($operatorId);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $operatorId;
    }

    private function processNot() {
        $not = $this->processSingleOperator('Not', $this->precedence->get($this->tokens[$this->id][0]), 'NOT');
        $operator = $this->popExpression();
        $this->pushExpression($operator);
        $operator->constant = $not->constant;

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $operator;
    }

    private function processCurlyExpression() {
        ++$this->id;
        while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_CURLY))) {
            $this->processNext();
        };

        $code = $this->popExpression();
        $block = $this->addAtom('Block');
        $block->code     = '{}';
        $block->fullcode = '{'.$code->fullcode.'}';
        $block->line     = $this->tokens[$this->id][2];
        $block->token    = $this->getToken($this->tokens[$this->id][0]);

        $this->addLink($block, $code, 'CODE');
        $this->pushExpression($block);

        ++$this->id; // Skip }

        return $block;
    }

    private function processDollar() {
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_CURLY) {
            $current = $this->id;

            $variable = $this->addAtom('Variable');

            ++$this->id;
            while (!in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_CLOSE_CURLY)) ) {
                $id = $this->processNext();
            };

            // Skip }
            ++$this->id;

            $expression = $this->popExpression();
            $this->addLink($variable, $expression, 'NAME');

            $variable->code     = $this->tokens[$current][1];
            $variable->fullcode = $this->tokens[$current][1].'{'.$expression->fullcode.'}';
            $variable->line     = $this->tokens[$current][2];
            $variable->token    = $this->getToken($this->tokens[$current][0]);

            $this->pushExpression($variable);

            if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
                $this->processSemicolon();
            }

            return $this->processFCOA($variable);
        } else {
            $this->nestContext();
            $this->processSingleOperator('Variable', $this->precedence->get($this->tokens[$this->id][0]), 'NAME');
            $variable = $this->popExpression();

            $this->exitContext();
            $this->pushExpression($variable);
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
                $this->processSemicolon();
            }

            return $variable;
        }
    }

    private function processClone() {
        $this->processSingleOperator('Clone', $this->precedence->get($this->tokens[$this->id][0]), 'CLONE', ' ' );
        $operatorId = $this->popExpression();
        $this->pushExpression($operatorId);
        return $operatorId;
    }

    private function processGoto() {
        $this->processSingleOperator('Goto', $this->precedence->get($this->tokens[$this->id][0]), 'GOTO');
        $operatorId = $this->popExpression();
        $this->pushExpression($operatorId);
        return $operatorId;
    }

    private function processNoscream() {
        $noscream = $this->processSingleOperator('Noscream', $this->precedence->get($this->tokens[$this->id][0]), 'AT');
        $operator = $this->popExpression();
        $this->pushExpression($operator);
        $operator->constant = $noscream->constant;

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $operator;
    }

    private function processNew() {
        $this->toggleContext(self::CONTEXT_NEW);
        $noSequence = $this->isContext(self::CONTEXT_NOSEQUENCE);
        if ($noSequence === false) {
            $this->toggleContext(self::CONTEXT_NOSEQUENCE);
        }

        $id =  $this->processSingleOperator('New', $this->precedence->get($this->tokens[$this->id][0]), 'NEW', ' ');

        $this->toggleContext(self::CONTEXT_NEW);
        if ($noSequence === false) {
            $this->toggleContext(self::CONTEXT_NOSEQUENCE);
        }

        $operatorId = $this->popExpression();
        $this->pushExpression($operatorId);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $operatorId;
    }

    //////////////////////////////////////////////////////
    /// processing binary operators
    //////////////////////////////////////////////////////
    private function processSign() {
        $signExpression = $this->tokens[$this->id][1];
        $code = $signExpression.'1';
        while (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_PLUS, \Exakat\Tasks\T_MINUS))) {
            ++$this->id;
            $signExpression = $this->tokens[$this->id][1].$signExpression;
            $code *= $this->tokens[$this->id][1].'1';
        }

        if (($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_LNUMBER || $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_DNUMBER) &&
            $this->tokens[$this->id + 2][0] !== \Exakat\Tasks\T_POW) {
            $operand = $this->processNext();

            $operand->code     = $signExpression.$operand->code;
            $operand->fullcode = $signExpression.$operand->fullcode;
            $operand->line     = $this->tokens[$this->id][2];
            $operand->token    = $this->getToken($this->tokens[$this->id][0]);

            return $operand;
        }
        
        $finals = $this->precedence->get($this->tokens[$this->id][0]);
        $noSequence = $this->isContext(self::CONTEXT_NOSEQUENCE);
        if ($noSequence === false) {
            $this->toggleContext(self::CONTEXT_NOSEQUENCE);
        }
        do {
            $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], $finals)) ;
        if ($noSequence === false) {
            $this->toggleContext(self::CONTEXT_NOSEQUENCE);
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

        $this->pushExpression($signed);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }
        return $signed;
    }

    private function processAddition() {
        if (!$this->hasExpression() ||
            $this->tokens[$this->id - 1][0] === \Exakat\Tasks\T_DOT) {
            return $this->processSign();
        }
        $left = $this->popExpression();

        $current = $this->id;

        $finals = $this->precedence->get($this->tokens[$this->id][0]);

        $addition = $this->addAtom('Addition');
        $this->addLink($addition, $left, 'LEFT');

        $this->nestContext();
        do {
            $this->processNext();

            if (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_EQUAL, \Exakat\Tasks\T_PLUS_EQUAL, \Exakat\Tasks\T_AND_EQUAL, \Exakat\Tasks\T_CONCAT_EQUAL, \Exakat\Tasks\T_DIV_EQUAL, \Exakat\Tasks\T_MINUS_EQUAL, \Exakat\Tasks\T_MOD_EQUAL, \Exakat\Tasks\T_MUL_EQUAL, \Exakat\Tasks\T_OR_EQUAL, \Exakat\Tasks\T_POW_EQUAL, \Exakat\Tasks\T_SL_EQUAL, \Exakat\Tasks\T_SR_EQUAL, \Exakat\Tasks\T_XOR_EQUAL))) {
                $this->processNext();
            }
        } while (!in_array($this->tokens[$this->id + 1][0], $finals)) ;
        $this->exitContext();

        $right = $this->popExpression();

        $this->addLink($addition, $right, 'RIGHT');
        
        $addition->code     = $this->tokens[$current][1];
        $addition->fullcode = $left->fullcode.' '.$this->tokens[$current][1].' '.$right->fullcode;
        $addition->line     = $this->tokens[$current][2];
        $addition->token    = $this->getToken($this->tokens[$current][0]);
        $addition->constant = $right->constant === self::CONSTANT_EXPRESSION && $left->constant === self::CONSTANT_EXPRESSION;

        $this->pushExpression($addition);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }
        
        return $addition;
    }

    private function processBreak() {
        $current = $this->id;
        $break = $this->addAtom($this->tokens[$this->id][0] === \Exakat\Tasks\T_BREAK ? 'Break' : 'Continue');

        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_LNUMBER) {
            $noSequence = $this->isContext(self::CONTEXT_NOSEQUENCE);
            if ($noSequence === false) {
                $this->toggleContext(self::CONTEXT_NOSEQUENCE);
            }

            ++$this->id;
            $breakLevel = $this->processInteger();
            $this->popExpression();

            if ($noSequence === false) {
                $this->toggleContext(self::CONTEXT_NOSEQUENCE);
            }

        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_PARENTHESIS) {
            ++$this->id; // skip (
            $this->processNext();
            ++$this->id; // skip )

            $breakLevel = $this->popExpression();
        } else {
            $breakLevel = $this->addAtomVoid();
        }

        $this->addLink($break, $breakLevel, $this->tokens[$current][0] === \Exakat\Tasks\T_BREAK ? 'BREAK' : 'CONTINUE');
        $break->code     = $this->tokens[$current][1];
        $break->fullcode = $this->tokens[$current][1].( $breakLevel->atom !== 'Void' ?  ' '.$breakLevel->fullcode : '');
        $break->line     = $this->tokens[$current][2];
        $break->token    = $this->getToken($this->tokens[$current][0]);
        $break->constant = self::CONSTANT_EXPRESSION ;

        $this->pushExpression($break);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $break;
    }

    private function processDoubleColon() {
        $current = $this->id;

        $left = $this->popExpression();

        $finals = $this->precedence->get($this->tokens[$this->id][0]);
        $finals[] = \Exakat\Tasks\T_DOUBLE_COLON;

        $newContext = $this->isContext(self::CONTEXT_NEW);
        $this->contexts[self::CONTEXT_NEW] = 0;
        $this->nestContext();
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_CURLY) {
            $block = $this->processCurlyExpression();
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_PARENTHESIS) {
                $right = $this->processFunctioncall(self::WITHOUT_FULLNSPATH);
            } else {
                $right = $this->processFCOA($block);
            }
            $this->popExpression();
        } elseif ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_DOLLAR) {
            ++$this->id; // Skip ::
            $block = $this->processDollar();
            $this->popExpression();
            $right = $this->processFCOA($block);
        } else {
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_VARIABLE) {
                ++$this->id;
                $this->processSingle('Variable');
                $right = $this->popExpression();
            } else {
                $right = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);
            }

            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_PARENTHESIS) {
                $this->pushExpression($right);
                $right = $this->processFunctioncall(self::WITHOUT_FULLNSPATH);
                $this->popExpression();
            }
        }
        $this->contexts[self::CONTEXT_NEW] = $newContext;
        $this->exitContext();

        if ($right->token === 'T_CLASS') {
            $static = $this->addAtom('Staticclass');
            $links = 'CLASS';
        } elseif ($right->atom === 'Identifier') {
            $static = $this->addAtom('Staticconstant');
            $links = 'CONSTANT';
            $static->constant = self::CONSTANT_EXPRESSION;
        } elseif (in_array($right->atom, array('Variable', 'Array', 'Arrayappend', 'MagicConstant', 'Concatenation', 'Block', 'Boolean', 'Null'))) {
            $static = $this->addAtom('Staticproperty');
            $links = 'PROPERTY';
        } elseif (in_array($right->atom, array('Methodcallname'))) {
            $static = $this->addAtom('Staticmethodcall');
            $links = 'METHOD';
        } else {
            throw new LoadError("Unprocessed atom in static call (right) : ".$right->atom.PHP_EOL);
        }

        $this->addLink($static, $left, 'CLASS');
        $this->addLink($static, $right, $links);

        $static->code     = $this->tokens[$current][1];
        $static->fullcode = $left->fullcode.'::'.$right->fullcode;
        $static->line     = $this->tokens[$current][2];
        $static->token    = $this->getToken($this->tokens[$current][0]);

        $this->pushExpression($static);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
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

        $this->nestContext();
        $noSequence = $this->isContext(self::CONTEXT_NOSEQUENCE);
        if ($noSequence === false) {
            $this->toggleContext(self::CONTEXT_NOSEQUENCE);
        }
        do {
            $right = $this->processNext();

            if (in_array($this->tokens[$this->id + 1][0], array(\Exakat\Tasks\T_EQUAL, \Exakat\Tasks\T_PLUS_EQUAL, \Exakat\Tasks\T_AND_EQUAL, \Exakat\Tasks\T_CONCAT_EQUAL, \Exakat\Tasks\T_DIV_EQUAL, \Exakat\Tasks\T_MINUS_EQUAL, \Exakat\Tasks\T_MOD_EQUAL, \Exakat\Tasks\T_MUL_EQUAL, \Exakat\Tasks\T_OR_EQUAL, \Exakat\Tasks\T_POW_EQUAL, \Exakat\Tasks\T_SL_EQUAL, \Exakat\Tasks\T_SR_EQUAL, \Exakat\Tasks\T_XOR_EQUAL))) {
                $right = $this->processNext();
            }
        } while (!in_array($this->tokens[$this->id + 1][0], $finals) );
        if ($noSequence === false) {
            $this->toggleContext(self::CONTEXT_NOSEQUENCE);
        }
        $this->exitContext();

        $this->popExpression();

        $this->addLink($operator, $right, $links[1]);
        
        $operator->code      = $this->tokens[$current][1];
        $operator->fullcode  = $left->fullcode.' '.$this->tokens[$current][1].' '.$right->fullcode;
        $operator->line      = $this->tokens[$current][2];
        $operator->token     = $this->getToken($this->tokens[$current][0]);
        $operator->constant  = ($right->constant === self::CONSTANT_EXPRESSION) && ($left->constant === self::CONSTANT_EXPRESSION);
        $this->pushExpression($operator);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

        return $operator;
    }

    private function processObjectOperator() {
        $current = $this->id;

        $left = $this->popExpression();

        $newContext = $this->isContext(self::CONTEXT_NEW);
        $this->contexts[self::CONTEXT_NEW] = 0;
        $this->nestContext();
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_CURLY) {
            $block = $this->processCurlyExpression();
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_PARENTHESIS) {
                $right = $this->processFunctioncall(self::WITHOUT_FULLNSPATH);
            } else {
                $right = $this->processFCOA($block);
            }
            $this->popExpression();
        } else {
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_VARIABLE) {
                ++$this->id;
                $this->processSingle('Variable');
                $right = $this->popExpression();
            } else {
                $right = $this->processNextAsIdentifier(self::WITHOUT_FULLNSPATH);
            }

            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_OPEN_PARENTHESIS) {
                $this->pushExpression($right);
                $right = $this->processFunctioncall(self::WITHOUT_FULLNSPATH);
                $this->popExpression();
            }
        }
        $this->contexts[self::CONTEXT_NEW] = $newContext;
        $this->exitContext();

        if (in_array($right->atom, array('Variable', 'Array', 'Identifier', 'Concatenation', 'Arrayappend', 'Property', 'MagicConstant', 'Block', 'Boolean', 'Null'))) {
            $static = $this->addAtom('Property');
            $links = 'PROPERTY';
            $static->enclosing = self::NO_ENCLOSING;
        } elseif (in_array($right->atom, array('Methodcallname', 'Methodcall'))) {
            $static = $this->addAtom('Methodcall');
            $links = 'METHOD';
        } else {
            throw new LoadError("Unprocessed atom in object call (right) : ".$right->atom.PHP_EOL);
        }

        $this->addLink($static, $left, 'OBJECT');
        $this->addLink($static, $right, $links);

        $static->code      = $this->tokens[$current][1];
        $static->fullcode  = $left->fullcode.'->'.$right->fullcode;
        $static->line      = $this->tokens[$current][2];
        $static->token     = $this->getToken($this->tokens[$current][0]);

        $this->pushExpression($static);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        } else {
            $static = $this->processFCOA($static);
        }

        return $static;
    }

    private function processAssignation() {
        $finals = $this->precedence->get($this->tokens[$this->id][0]);
        $finals = array_merge($finals, array(\Exakat\Tasks\T_EQUAL, \Exakat\Tasks\T_PLUS_EQUAL, \Exakat\Tasks\T_AND_EQUAL, \Exakat\Tasks\T_CONCAT_EQUAL, \Exakat\Tasks\T_DIV_EQUAL, \Exakat\Tasks\T_MINUS_EQUAL, \Exakat\Tasks\T_MOD_EQUAL, \Exakat\Tasks\T_MUL_EQUAL, \Exakat\Tasks\T_OR_EQUAL, \Exakat\Tasks\T_POW_EQUAL, \Exakat\Tasks\T_SL_EQUAL, \Exakat\Tasks\T_SR_EQUAL, \Exakat\Tasks\T_XOR_EQUAL));
        $id = $this->processOperator('Assignation', $finals);
        
        return $id;
    }

    private function processCoalesce() {
        return $this->processOperator('Coalesce', $this->precedence->get($this->tokens[$this->id][0]));
    }

    private function processEllipsis() {
        // Simply skipping the ...
        $finals = $this->precedence->get(\Exakat\Tasks\T_ELLIPSIS);
        while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
            $this->processNext();
        };

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
            $current = $this->id;

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
        return $this->processOperator('Multiplication', $this->precedence->get($this->tokens[$this->id][0]));
    }

    private function processPower() {
        return $this->processOperator('Power', $this->precedence->get($this->tokens[$this->id][0]));
    }

    private function processComparison() {
        return $this->processOperator('Comparison', $this->precedence->get($this->tokens[$this->id][0]));
    }

    private function processDot() {
        $current = $this->id;
        $concatenation = $this->addAtom('Concatenation');
        $fullcode= array();
        $rank = -1;

        $contains = $this->popExpression();
        $this->addLink($concatenation, $contains, 'CONCAT');
        $contains->rank = ++$rank;
        $fullcode[] = $contains->fullcode;

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
        $id = array_search(\Exakat\Tasks\T_PLUS, $finals);
        unset($finals[$id]);
        $id = array_search(\Exakat\Tasks\T_MINUS, $finals);
        unset($finals[$id]);

        $noSequence = $this->isContext(self::CONTEXT_NOSEQUENCE);
        if ($noSequence === false) {
            $this->toggleContext(self::CONTEXT_NOSEQUENCE);
        }

        $constant = self::CONSTANT_EXPRESSION;

        while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
            $contains = $this->processNext();
            
            $constant = $constant && $contains->constant;
            
            if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_DOT) {
                $this->popExpression();
                $this->addLink($concatenation, $contains, 'CONCAT');
                $fullcode[] = $contains->fullcode;
                $contains->rank = ++$rank;

                ++$this->id;
            }
        }

        $this->popExpression();
        $this->addLink($concatenation, $contains, 'CONCAT');
        $fullcode[] = $contains->fullcode;
        $contains->rank = ++$rank;
        if ($noSequence === false) {
            $this->toggleContext(self::CONTEXT_NOSEQUENCE);
        }
        $this->exitContext();

        $concatenation->code     = $this->tokens[$current][1];
        $concatenation->fullcode = implode(' . ', $fullcode);
        $concatenation->line     = $this->tokens[$current][2];
        $concatenation->token    = $this->getToken($this->tokens[$current][0]);
        $concatenation->count    = $rank;
        $concatenation->constant = $constant;

        $this->pushExpression($concatenation);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }

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
        };
        $right = $this->popExpression();

        $this->addLink($instanceof, $right, 'CLASS');
        
        list($fullnspath, $aliased) = $this->getFullnspath($right);
        $this->addCall('class', $fullnspath, $right);

        $instanceof->code     = $this->tokens[$current][1];
        $instanceof->fullcode = $left->fullcode.' '.$this->tokens[$current][1].' '.$right->fullcode;
        $instanceof->line     = $this->tokens[$current][2];
        $instanceof->token    = $this->getToken($this->tokens[$current][0]);

        $this->pushExpression($instanceof);

        return $instanceof;
    }

    private function processKeyvalue() {
        return $this->processOperator('Keyvalue', $this->precedence->get($this->tokens[$this->id][0]), array('KEY', 'VALUE'));
    }

    private function processBitshift() {
        return $this->processOperator('Bitshift', $this->precedence->get($this->tokens[$this->id][0]));
    }

    private function processEcho() {
        $current = $this->id;
        --$this->id;
        $name = $this->processNextAsIdentifier();
        
        $arguments = $this->processArguments(array(\Exakat\Tasks\T_SEMICOLON, \Exakat\Tasks\T_CLOSE_TAG, \Exakat\Tasks\T_END));

        $functioncall = $this->addAtom('Functioncall');
        list($fullnspath, $aliased) = $this->getFullnspath($name);
        $functioncall->code       = $this->tokens[$current][1];
        $functioncall->fullcode   = $this->tokens[$current][1].' '.$arguments->fullcode;
        $functioncall->line       = $this->tokens[$current][2];
        $functioncall->token      = $this->getToken($this->tokens[$current][0]);
        $functioncall->fullnspath = $fullnspath;
        $functioncall->aliased    = $aliased;

        $this->addLink($functioncall, $arguments, 'ARGUMENTS');
        $this->addLink($functioncall, $name, 'NAME');

        $this->pushExpression($functioncall);

        // processArguments goes too far, up to ;
        --$this->id;
        if ($this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
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
        if (in_array($this->tokens[$this->id][0], array(\Exakat\Tasks\T_INCLUDE, \Exakat\Tasks\T_INCLUDE_ONCE, \Exakat\Tasks\T_REQUIRE, \Exakat\Tasks\T_REQUIRE_ONCE))) {
            $name = $this->addAtom('Include');
        } else {
            $name = $this->addAtom('Identifier');
        }

        $name->code      = $this->tokens[$this->id][1];
        $name->fullcode  = $this->tokens[$this->id][1];
        $name->line      = $this->tokens[$this->id][2];
        $name->token     = $this->getToken($this->tokens[$this->id][0]);

        $arguments = $this->addAtom('Arguments');

        $noSequence = $this->isContext(self::CONTEXT_NOSEQUENCE);
        if ($noSequence === false) {
            $this->toggleContext(self::CONTEXT_NOSEQUENCE);
        }

        $fullcode = array();
        $finals = $this->precedence->get($this->tokens[$this->id][0]);
        while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
            $this->processNext();
        };
        if ($noSequence === false) {
            $this->toggleContext(self::CONTEXT_NOSEQUENCE);
        }

        $index = $this->popExpression();
        $index->rank = 0;
        $this->addLink($arguments, $index, 'ARGUMENT');
        $fullcode[] = $index->fullcode;

        $arguments->code     = $this->tokens[$this->id][1];
        $arguments->fullcode = implode(', ', $fullcode);
        $arguments->line     = $this->tokens[$this->id][2];
        $arguments->count    = 1;
        $arguments->token    = $this->getToken($this->tokens[$this->id][0]);

        $functioncall = $this->addAtom('Functioncall');
        $functioncall->code       = $name->code;
        $functioncall->fullcode   = $name->code.' '.$arguments->fullcode;
        $functioncall->line       = $name->line;
        $functioncall->token      = $name->token;
        $functioncall->fullnspath = '\\'.strtolower($name->code);

        $this->addLink($functioncall, $arguments, 'ARGUMENTS');
        $this->addLink($functioncall, $name, 'NAME');

        $this->pushExpression($functioncall);

        if ( !$this->isContext(self::CONTEXT_NOSEQUENCE) && $this->tokens[$this->id + 1][0] === \Exakat\Tasks\T_CLOSE_TAG) {
            $this->processSemicolon();
        }
        
        return $functioncall;
    }

    //////////////////////////////////////////////////////
    /// generic methods
    //////////////////////////////////////////////////////
    private function addAtom($atom) {
        $a = new Atom($atom);
        $this->atoms[$a->id] = $a;
        
        return $a;
    }

    private function addAtomVoid() {
        $void = $this->addAtom('Void');
        $void->code        = 'Void';
        $void->fullcode    = self::FULLCODE_VOID;
        $void->line        = $this->tokens[$this->id][2];
        $void->token       = \Exakat\Tasks\T_VOID;
        $void->constant    = self::CONSTANT_EXPRESSION;
        $void->noDelimiter = '';
        $void->delimiter   = '';

        return $void;
    }

    private function addLink($origin, $destination, $label) {
        if (!($destination instanceof Atom)) {
            print debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);die();
        }
        assert($origin instanceof Atom);
        assert($destination instanceof Atom);
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
            throw new LoadError("Warning : expression is not empty in $filename : ".count($this->expressions).PHP_EOL);
        }

        if ($this->contexts[self::CONTEXT_NOSEQUENCE] > 0) {
            throw new LoadError("Warning : context for sequence is not back to 0 in $filename : it is ".$this->contexts[self::CONTEXT_NOSEQUENCE].PHP_EOL);
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
            assert(isset($D[$id]), "Warning : forgotten atom $id in $this->filename : ".print_r($this->atoms[$id], true));
            assert($D[$id] <= 1, "Warning : too linked atom $id : ".$this->atoms[$id]->atom.PHP_EOL);

            assert(isset($atom->line), "Warning : missing line atom $id : ".PHP_EOL);

            assert(isset($atom->code), "Warning : forgotten code for atom $id in $this->filename : ".print_r($this->atoms[$id], true));

            assert(isset($atom->code), "Warning : forgotten token for atom $id in $this->filename : ".print_r($this->atoms[$id], true));
        }
    }

    private function processDefineAsConstants($argumentsId) {
        if (empty($this->argumentsId[0]->noDelimiter)) {
            $this->argumentsId[0]->fullnspath = '\\';
            return;
        }
        
        $fullnspath = '\\'.strtolower($this->argumentsId[0]->noDelimiter);
        
        $this->addDefinition('const', $fullnspath, $argumentsId);
        $this->argumentsId[0]->fullnspath = $fullnspath;
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
        $this->log->log("saveDefinitions\t".(($end - $begin) * 1000)."\t".count($this->calls).PHP_EOL);
    }

    private function fallbackToGlobal($type) {
        $b = microtime(true);
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
        $e = microtime(true);
    }

    private function startSequence() {
        $this->sequence = $this->addAtom('Sequence');
        $this->sequence->code      = ';';
        $this->sequence->fullcode  = ' '.self::FULLCODE_SEQUENCE.' ';
        $this->sequence->line      = $this->tokens[$this->id][2];
        $this->sequence->token     = 'T_SEMICOLON';
        $this->sequence->bracket   = self::NOT_BRACKET;
        $this->sequence->constant  = self::CONSTANT_EXPRESSION;

        $this->sequences[]    = $this->sequence;
        $this->sequenceRank[] = -1;
        $this->sequenceCurrentRank = count($this->sequenceRank) - 1;
    }

    private function addToSequence($id) {
        $this->addLink($this->sequence, $id, 'ELEMENT');
        $id->rank = ++$this->sequenceRank[$this->sequenceCurrentRank];
        $this->sequence->constant = $this->sequence->constant && isset($id->constant) && $id->constant === self::CONSTANT_EXPRESSION;
    }

    private function endSequence() {
        $this->sequence->count = $this->sequenceRank[$this->sequenceCurrentRank] + 1;

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

    private function getFullnspath($name, $type = 'class') {

        // Handle static, self, parent and PHP natives function
        if (isset($name->absolute) && ($name->absolute === self::ABSOLUTE)) {
            return array(strtolower($name->fullcode), self::NOT_ALIASED);
        } elseif (!in_array($name->atom, array('Nsname', 'Identifier', 'String', 'Null', 'Boolean'))) {
            // No fullnamespace for non literal namespaces
            return array('', self::NOT_ALIASED);
        } elseif (in_array($name->token, array('T_ARRAY', 'T_EVAL', 'T_ISSET', 'T_EXIT', 'T_UNSET', 'T_ECHO', 'T_PRINT', 'T_LIST', 'T_EMPTY'))) {
            // For language structures, it is always in global space, like eval or list
            return array('\\'.strtolower($name->code), self::NOT_ALIASED);
        } elseif (strtolower(substr($name->fullcode, 0, 10)) === 'namespace\\') {
            // namespace\A\B
            return array(substr($this->namespace, 0, -1).strtolower(substr($name->fullcode, 9)), self::NOT_ALIASED);
        } elseif (in_array($name->atom, array('Identifier', 'Boolean', 'Null'))) {

            // This is an identifier, self or parent
            if (strtolower($name->code) === 'self' ||
                strtolower($name->code) === 'static') {
                if (empty($this->currentClassTrait)) {
                    return array(self::FULLNSPATH_UNDEFINED, self::NOT_ALIASED);
                } else {
                    $this->addCall('class', $this->currentClassTrait[count($this->currentClassTrait) - 1]->fullnspath, $name);
                    return array($this->currentClassTrait[count($this->currentClassTrait) - 1]->fullnspath, self::NOT_ALIASED);
                }

            } elseif (strtolower($name->code) === 'parent') {
                if (empty($this->currentParentClassTrait)) {
                    return array(self::FULLNSPATH_UNDEFINED, self::NOT_ALIASED);
                } else {
                    $this->addCall('class', $this->currentParentClassTrait[count($this->currentParentClassTrait) - 1]->fullnspath, $name);
                    return array($this->currentParentClassTrait[count($this->currentParentClassTrait) - 1]->fullnspath, self::NOT_ALIASED);
                }

            // This is a normal identifier
            } elseif ($type === 'class' && isset($this->uses['class'][strtolower($name->code)])) {

                $this->addLink($name, $this->uses['class'][strtolower($name->code)], 'DEFINITION');
                return array($this->uses['class'][strtolower($name->code)]->fullnspath, self::ALIASED);

            } elseif ($type === 'const' && isset($this->uses['const'][strtolower($name->code)])) {
            
                $this->addLink($this->uses['const'][strtolower($name->code)], $name, 'DEFINITION');
                return array($this->uses['const'][strtolower($name->code)]->fullnspath, self::ALIASED);

            } elseif ($type === 'function' && isset($this->uses['function'][strtolower($name->code)])) {

                $this->addLink($this->uses['function'][strtolower($name->code)], $name, 'DEFINITION');
                return array($this->uses['function'][strtolower($name->code)]->fullnspath, self::ALIASED);

            } elseif ($type === 'function' && !empty($this->calls['function']['\\'.strtolower($name->code)]['definitions'])) {

                // This is a fall back ONLY if we already know about the constant (aka, if it is defined later, then no fallback)
                return array('\\'.strtolower($name->code), self::NOT_ALIASED);
            } else {
                return array($this->namespace.strtolower($name->fullcode), self::NOT_ALIASED);
            }
        } elseif ($name->atom === 'String' && isset($name->noDelimiter)) {
            $prefix =  str_replace('\\\\', '\\', strtolower($name->noDelimiter));
            $prefix = '\\'.$prefix;

            // define doesn't care about use...
            return array($prefix, self::NOT_ALIASED);
        } else {
            // Finally, the case for a nsname
            $prefix = strtolower( substr($name->code, 0, strpos($name->code.'\\', '\\')) );

            if (isset($this->uses[$type][$prefix])) {
                $this->addLink( $name, $this->uses[$type][$prefix], 'DEFINITION');
                return array($this->uses[$type][$prefix]->fullnspath.strtolower( substr($name->fullcode, strlen($prefix)) ) , 0);
            } else {
                return array($this->namespace.strtolower($name->fullcode), 0);
            }
        }
    }

    private function nestContext($context = self::CONTEXT_NOSEQUENCE) {
        ++$this->contexts[$context];
    }

    private function exitContext($context = self::CONTEXT_NOSEQUENCE) {
        --$this->contexts[$context];
    }

    private function toggleContext($context) {
        $this->contexts[$context] = !$this->contexts[$context];
        return $this->contexts[$context];
    }

    private function isContext($context) {
        return (boolean) $this->contexts[$context];
    }

    private function makeFullnspath($namespaceAs) {
        return strtolower(isset($namespaceAs->absolute) && $namespaceAs->absolute === self::ABSOLUTE ? $namespaceAs->fullcode : '\\'.$namespaceAs->fullcode) ;
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
            $this->namespace = strtolower($namespace->fullcode).'\\';
            if ($this->namespace[0] !== '\\') {
                $this->namespace = '\\'.$this->namespace;
            }
        }
    }

    private function addNamespaceUse($origin, $alias, $useType, $use) {
        $fullnspath = $origin->fullnspath;

        if ($origin !== $alias) { // Case of A as B
            // Alias is the 'As' expression.
            $offset = strrpos($alias->fullcode, ' ');
            $alias = strtolower($alias->code);
        } elseif (($offset = strrpos($alias->fullnspath, '\\')) === false) {
            // namespace without \
            $alias = strtolower($alias->fullnspath);
        } else {
            // namespace with \
            $alias = substr($alias->fullnspath, $offset + 1);
        }

        if (!($use instanceof Atom)) {
            print debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);die();
        }
        assert($use instanceof Atom);
        $this->uses[$useType][strtolower($alias)] = $use;

        return $alias;
    }

    private function addCall($type, $fullnspath, $call) {
        if (empty($fullnspath)) {
            return;
        }
        
        assert(is_string($fullnspath));
        
        if (!isset($this->calls[$type][$fullnspath])) {
            $this->calls[$type][$fullnspath] = array('calls'       => array(),
                                                     'definitions' => array());
        }
        $atom = $call->atom;
        if (!isset($this->calls[$type][$fullnspath]['calls'][$atom])) {
            $this->calls[$type][$fullnspath]['calls'][$atom] = array();
        }
        
        $this->calls[$type][$fullnspath]['calls'][$atom][] = $call->id;
    }

    private function addNoDelimiterCall($call) {
        if (empty($call->noDelimiter)) {
            return; // Can't be a class anyway.
        }
        if (intval($call->noDelimiter)) {
            return; // Can't be a class anyway.
        }
        // single : is OK
        if (preg_match('/[$ #?;%^\*\'\"\. <>~&,|\(\){}\[\]\/\s=+!`@\-]/is', $call->noDelimiter)) {
            return; // Can't be a class anyway.
        }
        
        if (strpos($call->noDelimiter, '::') !== false) {
            $fullnspath = strtolower(substr($call->noDelimiter, 0, strpos($call->noDelimiter, '::')) );

            if (strlen($fullnspath) === 0) {
                $fullnspath = '\\';
            } elseif ($fullnspath[0] !== '\\') {
                $fullnspath = '\\'.$fullnspath;
            }
            $types = array('class');
        } else {
            $types = array('function', 'class');

            $fullnspath = strtolower($call->noDelimiter);
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
            $this->calls[$type][$fullnspath]['calls'][$atom][] = $call->id;
        }
    }

    private function addDefinition($type, $fullnspath, $definition) {
        if (empty($fullnspath)) {
            return;
        }

        if (!isset($this->calls[$type][$fullnspath])) {
            $this->calls[$type][$fullnspath] = array('calls'       => array(),
                                                     'definitions' => array());
        }
        $atom = $definition->atom;
        if (!isset($this->calls[$type][$fullnspath]['definitions'][$atom])) {
            $this->calls[$type][$fullnspath]['definitions'][$atom] = array();
        }
        $this->calls[$type][$fullnspath]['definitions'][$atom][] = $definition->id;
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

        fwrite($log, $step."\t".($end - $begin)."\t".($end - $start).PHP_EOL);
        $begin = $end;
    }
    
    private function makeAnonymous($type = 'class') {
        static $anonymous = 'a';
        
        assert(in_array($type, array('class', 'function')), 'Classes and Functions are the only anonymous');
        return $type.'@'.++$anonymous;
    }
}

?>
