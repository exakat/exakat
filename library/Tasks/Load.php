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

namespace Tasks;

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
    private $php    = null;
    private static $client = null;
    private $config = null;

    private $calls = array();

    private $namespace = '\\';
    private $uses = array('function' => array(),
                          'const'    => array(),
                          'class'    => array());

    private $links = array();
    
    const FULLCODE_SEQUENCE = ' /**/ ';
    const FULLCODE_BLOCK    = ' {' . self::FULLCODE_SEQUENCE.'} ';
    const FULLCODE_VOID     = ' ';
    
    const CONTEXT_CLASS     = 1;
    const CONTEXT_INTERFACE = 2;
    const CONTEXT_TRAIT     = 3;
    const CONTEXT_FUNCTION  = 4;
    const CONTEXT_NEW       = 5;
    private $contexts = [self::CONTEXT_CLASS     => false,
                         self::CONTEXT_INTERFACE => false,
                         self::CONTEXT_TRAIT     => false,
                         self::CONTEXT_FUNCTION  => false,
                         self::CONTEXT_NEW       => false,
                         ];
    
    private $optionsTokens = array();
     
    const PRECEDENCE = [
                        T_OBJECT_OPERATOR             => 0,
                        T_DOUBLE_COLON                => 0,
                        T_DOLLAR                      => 0,
                        T_STATIC                      => 0,
                        T_EXIT                        => 0,

                        T_CLONE                       => 1,
                        T_NEW                         => 1,

                        T_OPEN_BRACKET                => 2,
               
                        T_POW                         => 3,
                        
                        T_INC                         => 4,
                        T_DEC                         => 4,
                        T_TILDE                       => 4,
                        T_ARRAY_CAST                  => 4,
                        T_BOOL_CAST                   => 4,
                        T_DOUBLE_CAST                 => 4,
                        T_INT_CAST                    => 4,
                        T_OBJECT_CAST                 => 4,
                        T_STRING_CAST                 => 4,
                        T_UNSET_CAST                  => 4,
                        T_AT                          => 4,

                        T_INSTANCEOF                  => 5,
                        
                        T_BANG                        => 6,

                        T_REFERENCE                   => 6, // Special for reference's usage of &

                        T_SLASH                       => 7,
                        T_STAR                        => 7,
                        T_PERCENTAGE                  => 7,
                 
                        T_PLUS                        => 8,
                        T_MINUS                       => 8,
                        T_DOT                         => 8,

                        T_SR                          => 9,
                        T_SL                          => 9,
                        
                        T_IS_SMALLER_OR_EQUAL         => 10,
                        T_IS_GREATER_OR_EQUAL         => 10,
                        T_GREATER                     => 10,
                        T_SMALLER                     => 10,
                            
                        T_IS_EQUAL                    => 11,
                        T_IS_NOT_EQUAL                => 11, // Double operator
                        T_IS_IDENTICAL                => 11,
                        T_IS_NOT_IDENTICAL            => 11,
                        T_SPACESHIP                   => 11,

                        T_AND                         => 12,    // &

                        T_CARET                       => 13,    // ^

                        T_PIPE                        => 14,     // |

                        T_BOOLEAN_AND                 => 15, // &&

                        T_BOOLEAN_OR                  => 16, // ||

                        T_COALESCE                    => 17,
                            
                        T_QUESTION                    => 18,
               
                        T_EQUAL                       => 19,
                        T_PLUS_EQUAL                  => 19,
                        T_AND_EQUAL                   => 19,
                        T_CONCAT_EQUAL                => 19,
                        T_DIV_EQUAL                   => 19,
                        T_MINUS_EQUAL                 => 19,
                        T_MOD_EQUAL                   => 19,
                        T_MUL_EQUAL                   => 19,
                        T_OR_EQUAL                    => 19,
                        T_POW_EQUAL                   => 19,
                        T_SL_EQUAL                    => 19,
                        T_SR_EQUAL                    => 19,
                        T_XOR_EQUAL                   => 19,
                        
                        T_LOGICAL_AND                 => 20, // and

                        T_LOGICAL_XOR                 => 21, // xor

                        T_LOGICAL_OR                  => 22, // or

                        T_ECHO                        => 30,
                        T_HALT_COMPILER               => 30,
                        T_PRINT                       => 30,
                        T_INCLUDE                     => 2,
                        T_INCLUDE_ONCE                => 2,
                        T_REQUIRE                     => 2,
                        T_REQUIRE_ONCE                => 2,
                        T_DOUBLE_ARROW                => 30,

                        T_RETURN                      => 31,
                        T_THROW                       => 31,
                        T_YIELD                       => 31,
                        T_YIELD_FROM                  => 31,
                        T_COLON                       => 31,
                        T_COMMA                       => 31,
                        T_CLOSE_TAG                   => 31,
                        T_CLOSE_PARENTHESIS           => 31,
                        T_CLOSE_BRACKET               => 31,
                        T_CLOSE_CURLY                 => 31,
                        T_AS                          => 31,
                        T_CONTINUE                    => 31,
                        T_BREAK                       => 31,
                        T_ELLIPSIS                    => 31,
                        T_GOTO                        => 31,
                        T_INSTEADOF                   => 31,

                        T_SEMICOLON                   => 32,
    ];
    
    const PROP_ALTERNATIVE = ['Declare', 'Ifthen', 'For', 'Foreach', 'Switch', 'While'];
    const PROP_REFERENCE   = ['Variable', 'Property', 'Staticproperty'];
    const PROP_VARIADIC    = ['Variable', 'Property', 'Staticproperty', 'Methodcall', 'Staticmethodcall', 'Functioncall', 'Identifier', 'Nsname'];
    const PROP_DELIMITER   = ['String', 'Heredoc'];
    const PROP_NODELIMITER = ['String', 'Variable'];
    const PROP_HEREDOC     = ['Heredoc'];
    const PROP_COUNT       = ['Sequence', 'Arguments', 'Heredoc', 'Shell', 'String', 'Try', 'Const', 'Ppp', 'Global', 'Static'];
    const PROP_FNSNAME     = ['Functioncall', 'Function', 'Class', 'Trait', 'Interface', 'Identifier', 'Nsname', 'As', 'Void', 'Static'];
    const PROP_ABSOLUTE    = ['Nsname'];
    const PROP_ALIAS       = ['Nsname', 'Identifier', 'As'];
    const PROP_ORIGIN      = self::PROP_ALIAS;
    const PROP_ENCODING    = ['String'];
    const PROP_INTVAL      = ['Integer'];
    const PROP_STRVAL      = ['String'];
    const PROP_ENCLOSING   = ['Variable', 'Array', 'Property'];
    const PROP_ARGS_MAX    = ['Arguments'];
    const PROP_ARGS_MIN    = ['Arguments'];
    const PROP_BRACKET     = ['Sequence'];

    const PROP_OPTIONS = ['alternative' => self::PROP_ALTERNATIVE,
                          'reference'   => self::PROP_REFERENCE,
                          'heredoc'     => self::PROP_HEREDOC,
                          'delimiter'   => self::PROP_DELIMITER,
                          'noDelimiter' => self::PROP_NODELIMITER,
                          'variadic'    => self::PROP_VARIADIC,
                          'count'       => self::PROP_COUNT,
                          'fullnspath'  => self::PROP_FNSNAME,
                          'absolute'    => self::PROP_ABSOLUTE,
                          'alias'       => self::PROP_ALIAS,
                          'origin'      => self::PROP_ORIGIN,
                          'encoding'    => self::PROP_ENCODING,
                          'intval'      => self::PROP_INTVAL,
                          'strval'      => self::PROP_STRVAL,
                          'enclosing'   => self::PROP_ENCLOSING,
                          'args_max'    => self::PROP_ARGS_MAX,
                          'args_min'    => self::PROP_ARGS_MIN,
                          'bracket'     => self::PROP_BRACKET,
                          ];
    
    const TOKENS = [ ';'  => T_SEMICOLON,
                     '+'  => T_PLUS,
                     '-'  => T_MINUS,
                     '/'  => T_SLASH,
                     '*'  => T_STAR,
                     '.'  => T_DOT,
                     '['  => T_OPEN_BRACKET,
                     ']'  => T_CLOSE_BRACKET,
                     '('  => T_OPEN_PARENTHESIS,
                     ')'  => T_CLOSE_PARENTHESIS,
                     '{'  => T_OPEN_CURLY,
                     '}'  => T_CLOSE_CURLY,
                     '='  => T_EQUAL,
                     ','  => T_COMMA,
                     '!'  => T_BANG,
                     '~'  => T_TILDE,
                     '@'  => T_AT,
                     '?'  => T_QUESTION,
                     ':'  => T_COLON,
                     '<' => T_SMALLER,
                     '>' => T_GREATER,
                     '%' => T_PERCENTAGE,
                     '"' => T_QUOTE,
                     '$' => T_DOLLAR,
                     '&' => T_AND,
                     '|' => T_PIPE,
                     '^' => T_CARET,
                     '`' => T_BACKTICK,
                   ];
    
    const TOKENNAMES = [ ';'  => 'T_SEMICOLON',
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
                   ];
    private $expressions = [];
    private $atoms = array();
    private $atomCount = 0;
    private $argumentsId = array();

    public function run(\Config $config) {
        $this->config = $config;
        
        if (!file_exists($this->config->projects_root.'/projects/'.$this->config->project.'/config.ini')) {
            display('No such project as "'.$this->config->project.'". Aborting');
            die();
        }

        $this->checkTokenLimit();

        $this->id0 = $this->addAtom('Project');
        $this->setAtom($this->id0, ['code'     => 'Whole',
                                    'fullcode' => 'Whole',
                                    'line'     => -1,
                                    'token'    => 'T_WHOLE']);

        $this->php = new \Phpexec();
        if (!$this->php->isValid()) {
            die("This PHP binary is not valid for running Exakat.\n");
        }
        $this->php->getTokens();

        if (static::$client === null) {
            static::$client = new \Loader\CypherG3();
        }
        
        $this->datastore->cleanTable('tokenCounts');

        if ($filename = $this->config->filename) {
            $this->processFile($filename);
            $this->saveFiles();
            $this->saveDefinitions();
        } elseif ($dirName = $this->config->dirname) {
            $this->processDir($dirName);
        } elseif (($project = $this->config->project) != 'default') {
            $this->processProject($project);
        } else {
            die('No file to process. Aborting');
        }

        static::$client->finalize();
        display('Final memory : '.number_format(memory_get_usage() / pow(2, 20)).'Mb');
        display('Maximum memory : '.number_format(memory_get_peak_usage() / pow(2, 20)).'Mb');
        display('Tokens size : '.count($this->tokens).' items');
        display('Links size : '.count($this->links).' items');
        $this->datastore->addRow('hash', array('status' => 'Load'));
        
        $loadFinal = new LoadFinal($this->gremlin);
        $loadFinal->run($config);
    }

    private function processProject($project) {
        $files = $this->datastore->getCol('files', 'file');
    
        $nbTokens = 0;
        $path = $this->config->projects_root.'/projects/'.$this->config->project.'/code';
        foreach($files as $file) {
            $nbTokens += $this->processFile($path.$file);
            $this->saveFiles();
        }
        $this->saveDefinitions();

        return array('files' => count($files), 'tokens' => $nbTokens);
    }

    private function processDir($dir) {
        if (!file_exists($dir)) {
            return array('files' => -1, 'tokens' => -1);
        }

        $ignoreDirs = array();
        $dir = rtrim($dir, '/');
        foreach($this->config->ignore_dirs as $ignore) {
            if ($ignore[0] === '/') {
                $ignoreDirs[] = $dir.$ignore.'*';
            } else {
                $ignoreDirs[] = '*'.$ignore.'*';
            }
        }

        $extPhp = array('php', 'php3', 'inc', 'tpl', 'phtml', 'tmpl', 'phps', 'ctp'  );
        $shell = 'find '.$dir.' \\( -name "*.'.(join('" -o -name "*.', $extPhp)).'" \\) \\( -not -path "*'.(join('" -and -not -path "', $ignoreDirs )).'" \\) ! -type l ! -type d';
        $res = trim(shell_exec($shell));
        $files = explode("\n", $res);

        $this->atoms = array();
        $this->atomCount = 0;
        $this->links = array();

        $nbTokens = 0;
        foreach($files as $file) {
            $nbTokens += $this->processFile($file);
            $this->saveFiles();
        }
        $this->saveDefinitions();

        return array('files' => count($files), 'tokens' => $nbTokens);
    }

    private function processFile($filename) {
        display( "Process $filename\n");
        $this->filename = $filename;
        
        $this->line = 0;
        $log = array();
        $begin = microtime(true);
    
        if (is_link($filename)) { return true; }
        if (!file_exists($filename)) {
            die( '"'.$filename.'" doesn\'t exists. Aborting');
        }

        $file = realpath($filename);
        if (strpos($file, '/code/') !== false) {
            $file = substr($file, strpos($file, '/code/') + 5);
        } else {
            $file = $filename;
        }
        if (filesize($filename) == 0) {
            return false;
        }

        if (!$this->php->compile($filename)) {
            display('Ignoring file '.$filename.' as it won\'t compile with the configured PHP version ('.$this->config->phpversion.')');
            return false;
        }
    
        $tokens = $this->php->getTokenFromFile($filename);
        $log['token_initial'] = count($tokens);
        if (count($tokens) == 1) {
            display('Ignoring file '.$filename.' as it is not a PHP file (No PHP token found)');
            return false;
        }
        
        $line = 0;
        $this->tokens = [];
        foreach($tokens as $t) {
            if (is_array($t)) {
                if ($t[0] == T_COMMENT ||
                    $t[0] == T_WHITESPACE ||
                    $t[0] == T_DOC_COMMENT) {
                    $line += substr_count($t[1], "\n");
                    continue;
                } else {
                    $line = $t[2];
                    $this->tokens[] = $t;
                }
            } else {
                $this->tokens[] = array(0 => self::TOKENS[$t],
                                        1 => $t,
                                        2 => $line);
            }
        }
        // Final token
        $this->tokens[] = array(0 => T_END,
                                1 => '/* END */',
                                2 => $line);
        unset($tokens);
        unset($lines);
        
        
        $id1 = $this->addAtom('File');
        $this->setAtom($id1, ['code'     => $filename,
                              'fullcode' => $filename,
                              'line'     => -1,
                              'token'    => 'T_FILENAME']);
        $this->addLink($this->id0, $id1, 'PROJECT');
        
        $n = count($this->tokens) - 2;
        $this->id = 0; // set to 0 so as to calculate line in the next call.
        $this->startSequence(); // At least, one sequence available
        $this->id = -1;
        do {
            $id = $this->processNext();
            display( "$this->id / $n\n");
            
            if ($id > 0) {
                $this->addToSequence($id);
            }
        } while ($this->id < $n);
        
        $id = $this->sequence;
        $this->endSequence();

        $this->addLink($id1, $id, 'FILE');
        $this->setAtom($id, ['root' => true]);

        $this->checkTokens($filename);
        
        print count($this->atoms)." atoms\n";
        print count($this->links)." links\n";
        print "Final id : $this->id\n";
    }

    private function processNext() {
       ++$this->id;
       
       display( $this->id.") ".$this->tokens[$this->id][1]."\n");
       $this->processing = [T_OPEN_TAG                 => 'processOpenTag',
                            T_OPEN_TAG_WITH_ECHO       => 'processOpenTag',
                            
                            T_DOLLAR                   => 'processDollar',
                            T_VARIABLE                 => 'processVariable',
                            T_LNUMBER                  => 'processInteger',
                            T_DNUMBER                  => 'processReal',
                            
                            T_OPEN_PARENTHESIS         => 'processParenthesis',
           
                            T_PLUS                     => 'processAddition',
                            T_MINUS                    => 'processAddition',
                            T_STAR                     => 'processMultiplication',
                            T_SLASH                    => 'processMultiplication',
                            T_PERCENTAGE               => 'processMultiplication',
                            T_POW                      => 'processPower',
                            T_INSTANCEOF               => 'processInstanceof',
                            T_SL                       => 'processBitshift',
                            T_SR                       => 'processBitshift',

                            T_DOUBLE_COLON             => 'processDoubleColon',
                            T_OBJECT_OPERATOR          => 'processObjectOperator',
                            T_NEW                      => 'processNew',
                            
                            T_DOT                      => 'processDot',
                            T_OPEN_CURLY               => 'processBlock',
                            
                            T_IS_SMALLER_OR_EQUAL      => 'processComparison',
                            T_IS_GREATER_OR_EQUAL      => 'processComparison',
                            T_GREATER                  => 'processComparison',
                            T_SMALLER                  => 'processComparison',

                            T_IS_EQUAL                 => 'processComparison',
                            T_IS_NOT_EQUAL             => 'processComparison',
                            T_IS_IDENTICAL             => 'processComparison',
                            T_IS_NOT_IDENTICAL         => 'processComparison',
                            T_SPACESHIP                => 'processComparison',

                            T_OPEN_BRACKET             => 'processArrayBracket',
                            T_ARRAY                    => 'processArray',
                            T_EMPTY                    => 'processArray',
                            T_LIST                     => 'processArray',
                            T_EVAL                     => 'processArray',
                            T_UNSET                    => 'processArray',
                            T_ISSET                    => 'processArray',
                            T_EXIT                     => 'processExit',
                            T_DOUBLE_ARROW             => 'processKeyvalue',
                            T_ECHO                     => 'processEcho',

                            T_HALT_COMPILER            => 'processHalt',
                            T_PRINT                    => 'processPrint',
                            T_INCLUDE                  => 'processPrint',
                            T_INCLUDE_ONCE             => 'processPrint',
                            T_REQUIRE                  => 'processPrint',
                            T_REQUIRE_ONCE             => 'processPrint',
                            T_RETURN                   => 'processReturn',
                            T_THROW                    => 'processThrow',
                            T_YIELD                    => 'processYield',
                            T_YIELD_FROM               => 'processYieldfrom',

                            T_EQUAL                    => 'processAssignation',
                            T_PLUS_EQUAL               => 'processAssignation',
                            T_AND_EQUAL                => 'processAssignation',
                            T_CONCAT_EQUAL             => 'processAssignation',
                            T_DIV_EQUAL                => 'processAssignation',
                            T_MINUS_EQUAL              => 'processAssignation',
                            T_MOD_EQUAL                => 'processAssignation',
                            T_MUL_EQUAL                => 'processAssignation',
                            T_OR_EQUAL                 => 'processAssignation',
                            T_POW_EQUAL                => 'processAssignation',
                            T_SL_EQUAL                 => 'processAssignation',
                            T_SR_EQUAL                 => 'processAssignation',
                            T_XOR_EQUAL                => 'processAssignation',

                            T_CONTINUE                 => 'processBreak',
                            T_BREAK                    => 'processBreak',

                            T_LOGICAL_AND              => 'processLogical',
                            T_LOGICAL_XOR              => 'processLogical',
                            T_LOGICAL_OR               => 'processLogical',
                            T_PIPE                     => 'processLogical',
                            T_CARET                    => 'processLogical',
                            T_AND                      => 'processAnd',

                            T_BOOLEAN_AND              => 'processLogical',
                            T_BOOLEAN_OR               => 'processLogical',

                            T_QUESTION                 => 'processTernary',
                            T_NS_SEPARATOR             => 'processNsnameAbsolute',
                            T_COALESCE                 => 'processCoalesce',

                            T_INLINE_HTML              => 'processInlineHtml',

                            T_INC                      => 'processPlusplus',
                            T_DEC                      => 'processPlusplus',

                            T_WHILE                    => 'processWhile',
                            T_DO                       => 'processDo',
                            T_IF                       => 'processIfthen',
                            T_FOREACH                  => 'processForeach',
                            T_FOR                      => 'processFor',
                            T_TRY                      => 'processTry',
                            T_CONST                    => 'processConst',
                            T_SWITCH                   => 'processSwitch',
                            T_DEFAULT                  => 'processDefault',
                            T_CASE                     => 'processCase',
                            T_DECLARE                  => 'processDeclare',

                            T_AT                       => 'processNoscream',
                            T_CLONE                    => 'processClone',
                            T_GOTO                     => 'processGoto',

                            T_STRING                   => 'processString',
                            T_CONSTANT_ENCAPSED_STRING => 'processLiteral',
                            T_ENCAPSED_AND_WHITESPACE  => 'processLiteral',
                            T_NUM_STRING               => 'processLiteral',
                            T_STRING_VARNAME           => 'processVariable',

                            T_ARRAY_CAST               => 'processCast',
                            T_BOOL_CAST                => 'processCast',
                            T_DOUBLE_CAST              => 'processCast',
                            T_INT_CAST                 => 'processCast',
                            T_OBJECT_CAST              => 'processCast',
                            T_STRING_CAST              => 'processCast',
                            T_UNSET_CAST               => 'processCast',

                            T_FILE                     => 'processMagicConstant',
                            T_CLASS_C                  => 'processMagicConstant',
                            T_FUNC_C                   => 'processMagicConstant',
                            T_LINE                     => 'processMagicConstant',
                            T_DIR                      => 'processMagicConstant',
                            T_METHOD_C                 => 'processMagicConstant',
                            T_NS_C                     => 'processMagicConstant',
                            T_TRAIT_C                  => 'processMagicConstant',

                            T_BANG                     => 'processNot',
                            T_TILDE                    => 'processNot',
                            T_ELLIPSIS                 => 'processEllipsis',
                             
                            T_SEMICOLON                => 'processSemicolon',
                            T_CLOSE_TAG                => 'processClosingTag',
                            T_END                      => 'processEnd',
                            T_COLON                    => 'processNone',
                            
                            T_FUNCTION                 => 'processFunction',
                            T_CLASS                    => 'processClass',
                            T_TRAIT                    => 'processTrait',
                            T_INTERFACE                => 'processInterface',
                            T_NAMESPACE                => 'processNamespace',
                            T_USE                      => 'processUse',
                            T_AS                       => 'processAs',
                            T_INSTEADOF                => 'processInsteadof',

                            T_ABSTRACT                 => 'processAbstract',
                            T_FINAL                    => 'processFinal',
                            T_PRIVATE                  => 'processPrivate',
                            T_PROTECTED                => 'processProtected',
                            T_PUBLIC                   => 'processPublic',
                            T_VAR                      => 'processVar',
                            
                            T_QUOTE                    => 'processQuote',
                            T_START_HEREDOC            => 'processQuote',
                            T_BACKTICK                 => 'processQuote',
                            T_DOLLAR_OPEN_CURLY_BRACES => 'processDollarCurly',
                            T_STATIC                   => 'processStatic',
                            T_GLOBAL                   => 'processGlobalVariable',
                            ];
        if (!isset($this->processing[ $this->tokens[$this->id][0] ])) {
            print "Defaulting a : $this->id in $this->filename\n";
            print_r($this->tokens[$this->id]);
            die("Missing the method\n");
        }
        $method = $this->processing[ $this->tokens[$this->id][0] ];
        
        print "$method\n";
        
        return $this->$method();
    }

    private function processNone() {
        return null;// Just ignore
    }

    //////////////////////////////////////////////////////
    /// processing complex tokens
    //////////////////////////////////////////////////////
    private function processQuote() {
        $current = $this->id;
        $fullcode = [];
        $rank = 0;
        
        if ($this->tokens[$current][0] === T_QUOTE) {
            $stringId = $this->addAtom('String');
            $finalToken = T_QUOTE;
            $openQuote = '"';
            $closeQuote = '"';
            $type = T_QUOTE;
        } elseif ($this->tokens[$current][0] === T_BACKTICK) {
            $stringId = $this->addAtom('Shell');
            $finalToken = T_BACKTICK;
            $openQuote = '`';
            $closeQuote = '`';
            $type = T_BACKTICK;
        } elseif ($this->tokens[$current][0] === T_START_HEREDOC) {
            $stringId = $this->addAtom('Heredoc');
            $finalToken = T_END_HEREDOC;
            $openQuote = $this->tokens[$this->id][1];
            if ($this->tokens[$this->id][1][3] === "'") {
                $closeQuote = substr($this->tokens[$this->id][1], 4, -2);
            } else {
                $closeQuote = substr($this->tokens[$this->id][1], 3);
            }
            $type = T_START_HEREDOC;
        }
        
        while ($this->tokens[$this->id + 1][0] !== $finalToken) {
            $currentVariableId = $this->id + 1;
            if (in_array($this->tokens[$this->id + 1][0], [T_CURLY_OPEN, T_DOLLAR_OPEN_CURLY_BRACES])) {
                $openId = $this->id + 1;
                ++$this->id; // Skip {
                while (!in_array($this->tokens[$this->id + 1][0], [T_CLOSE_CURLY])) {
                    $this->processNext();
                };
                ++$this->id; // Skip }

                $partId = $this->popExpression();
                $this->setAtom($partId, ['enclosing' => true,
                                         'fullcode'  => $this->tokens[$openId][1] . $this->atoms[$partId]['fullcode'] . '}',
                                         'token'     => $this->getToken($this->tokens[$currentVariableId][0])]);
                $this->pushExpression($partId);
            } elseif ($this->tokens[$this->id + 1][0] == T_VARIABLE) {
                $this->processNext();

                if ($this->tokens[$this->id + 1][0] == T_OBJECT_OPERATOR) {
                    ++$this->id;
                    
                    $objectId = $this->popExpression();

                    $propertyNameId = $this->processNextAsIdentifier();

                    $propertyId = $this->addAtom('Property');
                    $this->setAtom($propertyId, ['code'     => $this->tokens[$current][1],
                                                 'fullcode' => $this->atoms[$objectId]['fullcode']. '->' .
                                                               $this->atoms[$propertyNameId]['fullcode'],
                                                'line'      => $this->tokens[$current][2],
                                                'token'     => $this->getToken($this->tokens[$current][0]),
                                                'enclosing' => false ]);

                    $this->addLink($propertyId, $objectId, 'OBJECT');
                    $this->addLink($propertyId, $propertyNameId, 'PROPERTY');
                    
                    $this->pushExpression($propertyId);
                }
            } else {
                $this->processNext();
            }
            
            $partId = $this->popExpression();
            if ($this->atoms[$partId]['atom'] === 'String') {
                $this->setAtom($partId, ['noDelimiter' => $this->atoms[$partId]['code'],
                                         'delimiter'   => '']);
            } else {
                $this->setAtom($partId, ['noDelimiter' => '',
                                         'delimiter'   => '']);
            }
            $this->setAtom($partId, ['rank' => $rank++]);
            $fullcode[] = $this->atoms[$partId]['fullcode'];
            $this->addLink($stringId, $partId, 'CONCAT');
        }
        
        ++$this->id;
        $x = ['code'     => $this->tokens[$current][1],
              'fullcode' => $openQuote.join('', $fullcode).$closeQuote,
              'line'     => $this->tokens[$current][2],
              'token'    => $this->getToken($this->tokens[$current][0]),
              'count'    => $rank];
              
        if ($type === T_START_HEREDOC) {
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
        while (!in_array($this->tokens[$this->id + 1][0], [T_CLOSE_CURLY])) {
            $this->processNext();
        } ;
        ++$this->id; // Skip }

        $nameId = $this->popExpression();
        $this->addLink($variableId, $nameId, 'NAME');

        $this->setAtom($nameId, ['code'      => $this->tokens[$current][1],
                                 'fullcode'  => '${'.$this->atoms[$nameId]['fullcode'].'}',
                                 'line'      => $this->tokens[$current][2],
                                 'token'     => $this->getToken($this->tokens[$current][0]),
                                 'enclosing' => true]);
        
        return $variableId;
    }
    
    private function processTry() {
        $current = $this->id;
        $tryId = $this->addAtom('Try');
        
        $blockId = $this->processFollowingBlock([T_CLOSE_CURLY]);
        $this->popExpression();
        $this->addLink($tryId, $blockId, 'BLOCK');
        
        $rank = 0;
        $fullcodeCatch = array();
        while ($this->tokens[$this->id + 1][0] == T_CATCH) {
            $catch = $this->id + 1;
            ++$this->id; // Skip catch
            ++$this->id; // Skip (

            $catchId = $this->addAtom('Catch');
            $classId = $this->processOneNsname();
            $this->addLink($catchId, $classId, 'CLASS');
            $this->addCall('class', $this->getFullnspath($classId), $classId);

            // Process variable
            $this->processNext();
        
            $variableId = $this->popExpression();
            $this->addLink($catchId, $variableId, 'VARIABLE');

            // Skip )
            ++$this->id;

            // Skip }
            $blockCatchId = $this->processFollowingBlock([T_CLOSE_CURLY]);
            $this->popExpression();
            $this->addLink($catchId, $blockCatchId, 'BLOCK');

            $this->setAtom($catchId, ['code'     => $this->tokens[$catch][1],
                                      'fullcode' => $this->tokens[$catch][1].' ('.$this->atoms[$classId]['fullcode'].' '.
                                                     $this->atoms[$variableId]['fullcode'].')'.static::FULLCODE_BLOCK,
                                      'line'     => $this->tokens[$catch][2],
                                      'token'    => $this->getToken($this->tokens[$current][0]),
                                      'rank'     => $rank++]);

            $this->addLink($tryId, $catchId, 'CATCH');
            $fullcodeCatch[] = $this->atoms[$catchId]['fullcode'];
        }
        
        if ($this->tokens[$this->id + 1][0] === T_FINALLY) {
            $finally = $this->id + 1;
            $finallyId = $this->addAtom('Finally');

            ++$this->id;
            $finallyBlockId = $this->processFollowingBlock(false);
            $this->popExpression();
            $this->addLink($tryId, $finallyId, 'FINALLY');
            $this->addLink($finallyId, $finallyBlockId, 'BLOCK');
            

            $this->setAtom($finallyId, ['code'     => $this->tokens[$finally][1],
                                        'fullcode' => $this->tokens[$finally][1] . static::FULLCODE_BLOCK,
                                        'line'     => $this->tokens[$finally][2],
                                        'token'    => $this->getToken($this->tokens[$current][0])]);
        }

        $this->setAtom($tryId, ['code'     => $this->tokens[$current][1],
                                'fullcode' => $this->tokens[$current][1] . static::FULLCODE_BLOCK .
                                              join('', $fullcodeCatch) .
                                              ( isset($finallyId) ? $this->atoms[$finallyId]['fullcode'] : ''),
                                'line'     => $this->tokens[$current][2],
                                'token'    => $this->getToken($this->tokens[$current][0]),
                                'count'    => $rank]);

        $this->pushExpression($tryId);
        $this->processSemicolon();
        
        return $tryId;
    }

    private function processFunction() {
        $current = $this->id;
        $functionId = $this->addAtom('Function');
        $this->toggleContext(self::CONTEXT_FUNCTION);

        $fullcode = [];
        foreach($this->optionsTokens as $name => $optionId) {
            $this->addLink($functionId, $optionId, strtoupper($name));
            $fullcode[] = $this->atoms[$optionId]['fullcode'];
        }
        $this->optionsTokens = array();

        if ($this->tokens[$this->id + 1][0] === T_AND) {
            ++$this->id;
            $this->setAtom($functionId, ['reference' => true]);
        } else {
            $this->setAtom($functionId, ['reference' => false]);
        }

        if ($this->tokens[$this->id + 1][0] === T_OPEN_PARENTHESIS) {
            $isClosure = true;
            $nameId = $this->addAtomVoid();
        } else {
            $isClosure = false;
            $nameId = $this->processNextAsIdentifier();
        }
        $this->addLink($functionId, $nameId, 'NAME');
        
        // Process arguments
        ++$this->id; // Skip arguments
        $argumentsId = $this->processArguments([T_CLOSE_PARENTHESIS], true);
        $this->addLink($functionId, $argumentsId, 'ARGUMENTS');
        
        // Process use
        if ($this->tokens[$this->id + 1][0] === T_USE) {
            ++$this->id; // Skip use
            ++$this->id; // Skip (
            $useId = $this->processArguments();
            $this->addLink($functionId, $useId, 'USE');
        }
        
        // Process return type
        if ($this->tokens[$this->id + 1][0] === T_COLON) {
            ++$this->id;
            $returnTypeId = $this->processOneNsname();

            $this->addLink($functionId, $returnTypeId, 'RETURNTYPE');
        }

        // Process block 
        if ($this->tokens[$this->id + 1][0] === T_SEMICOLON) {
            $voidId = $this->addAtomVoid();
            $this->addLink($functionId, $voidId, 'BLOCK');
        } else {
            $blockId = $this->processFollowingBlock([T_CLOSE_CURLY]);
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
            $fullnspath = $this->getFullnspath($nameId);
            $this->addDefinition('function', $fullnspath, $functionId);
        } else {
            $fullnspath = '';
        }
        $this->setAtom($functionId, ['code'       => $this->atoms[$nameId]['fullcode'],
                                     'fullcode'   => join(' ', $fullcode).$this->tokens[$current][1] . ' ' .
                                                     ($this->atoms[$functionId]['reference'] ? '&' : '') .
                                                     ($this->atoms[$nameId]['atom'] === 'Void' ? '' : $this->atoms[$nameId]['fullcode']).
                                                     '('.$this->atoms[$argumentsId]['fullcode'].')'.
                                                     (isset($useId) ? ' use ('.$this->atoms[$useId]['fullcode'].')' : ''). // No space before use
                                                     (isset($returnTypeId) ? ' : '.$this->atoms[$returnTypeId]['fullcode'] : '').
                                                     (isset($blockId) ? self::FULLCODE_BLOCK : ' ;'),
                                     'line'       => $this->tokens[$current][2],
                                     'token'      => $this->getToken($this->tokens[$current][0]),
                                     'fullnspath' => $fullnspath ]);
        
        $this->pushExpression($functionId);
        
        if ($this->atoms[$nameId]['atom'] !== 'Void') {
            $this->processSemicolon();
        }

        $this->toggleContext(self::CONTEXT_FUNCTION);
        return $functionId;
    }
    
    private function processOneNsname() {
        $rank = 0;
        $fullcode = [];
        
        if ($this->tokens[$this->id + 1][0] !== T_NS_SEPARATOR) {
            $subnameId = $this->processNextAsIdentifier();
            $this->pushExpression($subnameId);
            
            $hasPrevious = true;
        } else {
            $hasPrevious = false;
        }
        $current = $this->id;
        
        if ($this->tokens[$this->id + 1][0] === T_NS_SEPARATOR) {
            $extendsId = $this->addAtom('Nsname');
            
            // Previous one
            if ($hasPrevious === true) {
                $subnameId = $this->popExpression();
                $this->setAtom($subnameId, ['rank' => $rank++]);
                $fullcode[] = $this->atoms[$subnameId]['code'];
                $this->addLink($extendsId, $subnameId, 'SUBNAME');
            } else {
                $fullcode[] = '';
            }
            
            // Next one (at least one)
            while ($this->tokens[$this->id + 1][0] === T_NS_SEPARATOR &&
                   $this->tokens[$this->id + 2][0] !== T_OPEN_CURLY ) {
                ++$this->id; // Skip \

                $subnameId = $this->processNextAsIdentifier();
    
                $this->setAtom($subnameId, ['rank' => $rank++]);
                $fullcode[] = $this->atoms[$subnameId]['code'];
                $this->addLink($extendsId, $subnameId, 'SUBNAME');
            }
            
            $this->setAtom($extendsId, ['code'     => '\\',
                                        'fullcode' => join('\\', $fullcode),
                                        'line'     => $this->tokens[$current][2],
                                        'token'    => $this->getToken($this->tokens[$current][0]),
                                        'absolute' => !$hasPrevious]);
            $fullnspath = $this->getFullnspath($extendsId);
            $this->setAtom($extendsId, ['fullnspath' => $fullnspath]);
        } else {
            $extendsId = $this->popExpression();
        }
        
        return $extendsId;
    }

    private function processTrait() {
        $current = $this->id;
        $traitId = $this->addAtom('Trait');
        $this->toggleContext(self::CONTEXT_TRAIT);
        
        $nameId = $this->processNextAsIdentifier();
        $this->addLink($traitId, $nameId, 'NAME');

        // Process block 
        ++$this->id;
        $blockId = $this->processBlock(false);
        $this->popExpression();
        $this->addLink($traitId, $blockId, 'BLOCK');
        
        $fullnspath = $this->getFullnspath($nameId);
        $this->setAtom($traitId, ['code'       => $this->tokens[$current][1],
                                  'fullcode'   => $this->tokens[$current][1].' '.$this->atoms[$nameId]['fullcode'].
                                                  static::FULLCODE_BLOCK,
                                  'line'       => $this->tokens[$current][2],
                                  'token'      => $this->getToken($this->tokens[$current][0]),
                                  'fullnspath' => $fullnspath]);
        
        $this->addDefinition('class', $fullnspath, $traitId);
        
        $this->pushExpression($traitId);
        $this->processSemicolon();

        $this->toggleContext(self::CONTEXT_TRAIT);

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
        $fullcode = [];
        $extends = $this->id + 1;
        if ($this->tokens[$this->id + 1][0] == T_EXTENDS) {
            do {
                ++$this->id; // Skip extends or ,
                $extendsId = $this->processOneNsname();
                $this->setAtom($extendsId, ['rank' => $rank]);
                $this->addLink($interfaceId, $extendsId, 'EXTENDS');
                $fullcode[] = $this->atoms[$extendsId]['fullcode'];

                $this->addCall('class', $this->getFullnspath($extendsId), $extendsId);
            } while ($this->tokens[$this->id + 1][0] === T_COMMA);
        }

        // Process block 
        ++$this->id;
        $blockId = $this->processBlock(false);
        $this->popExpression();
        $this->addLink($interfaceId, $blockId, 'BLOCK');
        
        $fullnspath = $this->getFullnspath($nameId);
        $this->setAtom($interfaceId, ['code'       => $this->tokens[$current][1],
                                      'fullcode'   => $this->tokens[$current][1] . ' ' . $this->atoms[$nameId]['fullcode'] .
                                                      (isset($extendsId) ? ' ' . $this->tokens[$extends][1] . ' ' . join(', ', $fullcode) : '') . 
                                                      static::FULLCODE_BLOCK,
                                      'line'       => $this->tokens[$current][2],
                                      'token'      => $this->getToken($this->tokens[$current][0]),
                                      'fullnspath' => $fullnspath]);

        $this->addDefinition('class', $fullnspath, $interfaceId);
        
        $this->pushExpression($interfaceId);
        $this->processSemicolon();

        $this->toggleContext(self::CONTEXT_INTERFACE);

        return $interfaceId;
    }
    
    private function processClass() {
        $current = $this->id;
        $classId = $this->addAtom('Class');
        $this->toggleContext(self::CONTEXT_CLASS);

        // Should work on Abstract and Final only
        $fullcode = [];
        foreach($this->optionsTokens as $name => $optionId) {
            $this->addLink($classId, $optionId, strtoupper($name));
            $fullcode[] = $this->atoms[$optionId]['fullcode'];
        }
        $this->optionsTokens = array();
        
        if ($this->tokens[$this->id + 1][0] === T_STRING) {
            $nameId = $this->processNextAsIdentifier();
        } else {
            $nameId = $this->addAtomVoid();
            
            if ($this->tokens[$this->id + 1][0] === T_OPEN_PARENTHESIS) {
                // Process arguments
                ++$this->id; // Skip arguments
                $argumentsId = $this->processArguments();
                $this->addLink($classId, $argumentsId, 'ARGUMENTS');
            }
        }
        $this->addLink($classId, $nameId, 'NAME');

        // Process extends
        if ($this->tokens[$this->id + 1][0] == T_EXTENDS) {
            $extends = $this->tokens[$this->id + 1][1];
            ++$this->id; // Skip extends

            $extendsId = $this->processOneNsname();
            
            $this->addLink($classId, $extendsId, 'EXTENDS');
            $this->addCall('class', $this->getFullnspath($extendsId), $extendsId);
        }

        // Process implements
        if ($this->tokens[$this->id + 1][0] == T_IMPLEMENTS) {
            $implements = $this->tokens[$this->id + 1][1];
            $fullcodeImplements = array();
            do {
                ++$this->id; // Skip implements
                $implementsId = $this->processOneNsname();
                $this->addLink($classId, $implementsId, 'IMPLEMENTS');
                $fullcodeImplements[] = $this->atoms[$implementsId]['fullcode'];
                
                $this->addCall('class', $this->getFullnspath($implementsId), $implementsId);
            } while ($this->tokens[$this->id + 1][0] === T_COMMA);
        }
        
        // Process block 
        ++$this->id;
        $blockId = $this->processBlock(false);
        $this->popExpression();
        $this->addLink($classId, $blockId, 'BLOCK');
        
        $fullnspath = $this->getFullnspath($nameId);
        $this->setAtom($classId, ['code'       => $this->tokens[$current][1],
                                  'fullcode'   => (!empty($fullcode) ? join(' ', $fullcode).' ' : '') .
                                                  $this->tokens[$current][1] .
                                                  ($this->atoms[$nameId]['atom'] === 'Void' ? '' : ' '.$this->atoms[$nameId]['fullcode']) .
                                                  (isset($argumentsId) ? ' ('.$this->atoms[$argumentsId]['fullcode'].')' : '').
                                                  (isset($extendsId) ? ' '.$extends.' ' . $this->atoms[$extendsId]['fullcode'] : '') .
                                                  (isset($implementsId) ? ' '.$implements.' ' . join(', ', $fullcodeImplements) : '') .
                                                  static::FULLCODE_BLOCK,
                                  'line'       => $this->tokens[$current][2],
                                  'token'      => $this->getToken($this->tokens[$current][0]),
                                  'fullnspath' => $fullnspath]);
        
        $this->pushExpression($classId);
        
        $this->addDefinition('class', $fullnspath, $classId);
        
        // Case of anonymous classes
        if ($this->tokens[$current - 1][0] !== T_NEW) {
            $this->processSemicolon();
        }

        $this->toggleContext(self::CONTEXT_CLASS);

        return $classId;
    }

    private function processOpenTag() {
        $id = $this->addAtom('Php');
        $current = $this->id;

        $this->startSequence();
        if ($this->tokens[$this->id][0] === T_OPEN_TAG_WITH_ECHO) {
            --$this->id;
            $this->processOpenWithEcho();
        }
        
        while (!in_array($this->tokens[$this->id + 1][0], [T_CLOSE_TAG, T_END, T_HALT_COMPILER])) {
            $this->processNext();
        };

        if ($this->tokens[$this->id + 1][0] == T_CLOSE_TAG) {
            $this->processSemicolon();
            $closing = '?>';
        } elseif ($this->tokens[$this->id + 1][0] == T_HALT_COMPILER) {
            ++$this->id;
            $this->processHalt();
            $closing = '';
        } else {
            $closing = '';
        }

        $this->addLink($id, $this->sequence, 'CODE');
        $this->endSequence();
        
        $this->setAtom($id, ['code'     => $this->tokens[$current][1],
                             'fullcode' => '<?php '.self::FULLCODE_SEQUENCE.' '.$closing,
                             'line'     => $this->tokens[$current][2],
                             'token'    => $this->getToken($this->tokens[$current][0])]);
        ++$this->id;
        return $id;
    }

    private function processSemicolon() {
        $this->addToSequence($this->popExpression());
    }

    private function processClosingTag() {
        $current = $this->id;
        if ($this->tokens[$this->id + 1][0] === T_END) {
            if ($this->tokens[$this->id - 1][0] !== T_SEMICOLON) {
                $this->processSemicolon();
            }
        } elseif ($this->tokens[$this->id + 1][0] === T_INLINE_HTML &&
                  $this->tokens[$this->id + 2][0] === T_END) {

            if ($this->tokens[$this->id - 1][0] !== T_SEMICOLON) {
                $this->processSemicolon();
            }

            ++$this->id;
            $id = $this->processInlineHtml();
            $this->pushExpression($id);
            $this->processSemicolon();
        } elseif ($this->tokens[$this->id + 1][0] === T_INLINE_HTML &&
                  $this->tokens[$this->id + 2][0] === T_OPEN_TAG) {

            if ($this->tokens[$this->id - 1][0] !== T_SEMICOLON) {
                $this->processSemicolon();
            }
            ++$this->id;
            $id = $this->processInlineHtml();
            $this->pushExpression($id);
            $this->processSemicolon();

            ++$this->id;
        } elseif ($this->tokens[$this->id + 1][0] === T_INLINE_HTML &&
                  $this->tokens[$this->id + 2][0] === T_OPEN_TAG_WITH_ECHO) {

            if ($this->tokens[$this->id - 1][0] !== T_SEMICOLON) {
                $this->processSemicolon();
            }

            ++$this->id;
            $id = $this->processInlineHtml();
            $this->pushExpression($id);
            $this->processSemicolon();

            $this->processOpenWithEcho();

        } elseif ($this->tokens[$this->id + 1][0] === T_OPEN_TAG) {
            $this->processSemicolon();
            ++$this->id;
        } elseif ($this->tokens[$this->id + 1][0] === T_OPEN_TAG_WITH_ECHO) {
            if ($this->tokens[$this->id - 1][0] !== T_SEMICOLON) {
                $this->processSemicolon();
            }

            $this->processOpenWithEcho();
        } else {
            ++$this->id;
            $this->processInlineHtml();
            $this->pushExpression($id);
            $this->processSemicolon();
            die("PROCESSING INLINE ALONE?");
        }

        return 0;
    }
    
    private function processOpenWithEcho() {
        $current = $this->id;
    
        // Processing ECHO
        $echoId = $this->processNextAsIdentifier();
    
        $argumentsId = $this->processArguments([T_SEMICOLON, T_CLOSE_TAG, T_END]);
        //processArguments goes too far, up to ;
        --$this->id;
    
        $functioncallId = $this->addAtom('Functioncall');
        $this->setAtom($functioncallId, ['code'       => $this->atoms[$echoId]['code'],
                                         'fullcode'   => '<?php echo ' . $this->atoms[$argumentsId]['fullcode'],
                                         'line'       => $this->tokens[$current === -1 ? 0 : $current][2],
                                         'token'      => 'T_OPEN_TAG_WITH_ECHO',
                                         'fullnspath' => '\\echo' ]);
        $this->addLink($functioncallId, $argumentsId, 'ARGUMENTS');
        $this->addLink($functioncallId, $echoId, 'NAME');
        $this->pushExpression($functioncallId);
    }

    private function processNsnameAbsolute() {
        $id = $this->processNsname();

        $this->setAtom($id, ['absolute'   => true]);
        // No need for fullnspath here 

        return $id;
    }

    private function processNsname() {
        $current = $this->id;

        $nsnameId = $this->addAtom('Nsname');
        $fullcode = [];

        $rank = 0;
        if ($this->hasExpression()) {
            $left = $this->popExpression();
            $this->addLink($nsnameId, $left, 'SUBNAME');
            $fullcode[] = $this->atoms[$left]['code'];

            $this->setAtom($left, ['rank' => $rank]);
            $absolute = false;
        } else {
            $fullcode[] = '';
            $absolute = true;
        }
        
        while ($this->tokens[$this->id][0] === T_NS_SEPARATOR) {
            $subnameId = $this->processNextAsIdentifier();

            ++$rank;
            $this->setAtom($subnameId, ['rank' => $rank]);

            $this->addLink($nsnameId, $subnameId, 'SUBNAME');
            $fullcode[] = $this->atoms[$subnameId]['code'];

            // Go to next
            ++$this->id; // skip \
        }  ;
        // Back up a bit
        $this->id--;

        $x = ['code'     => $this->tokens[$current][1],
              'fullcode' => join('\\', $fullcode),
              'line'     => $this->tokens[$current][2],
              'token'    => $this->getToken($this->tokens[$current][0]),
              'absolute' => $absolute];
        $this->setAtom($nsnameId, $x);
        if ($this->isContext(self::CONTEXT_NEW)) {
            $fullnspath = $this->getFullnspath($nsnameId, 'class');
            $this->setAtom($nsnameId, ['fullnspath' => $fullnspath]);
            $this->addCall('class', $this->getFullnspath($nsnameId), $nsnameId);
        } else {
            $fullnspath = $this->getFullnspath($nsnameId, 'const');
            $this->setAtom($nsnameId, ['fullnspath' => $fullnspath]);
            $this->addCall('const', $this->getFullnspath($nsnameId), $nsnameId);
        }

        $this->pushExpression($nsnameId);

        return $this->processFCOA($nsnameId);
    }
    
    private function processTypehint() {
        if (in_array($this->tokens[$this->id + 1][0], [T_ARRAY, T_CALLABLE, T_STATIC])) {
            $id = $this->processNextAsIdentifier();
            $this->setAtom($id, ['fullnspath' => '\\'.strtolower($this->tokens[$this->id][1]) ]);
            return $id;
        } elseif (in_array($this->tokens[$this->id + 1][0], [T_NS_SEPARATOR, T_STRING, T_NAMESPACE])) {
            $id = $this->processOneNsname();
            if (in_array(strtolower($this->tokens[$this->id][1]), ['int', 'bool', 'void', 'float', 'string'])) {
                $this->setAtom($id, ['fullnspath' => '\\'.strtolower($this->tokens[$this->id][1]) ]);
            } else {
                $this->addCall('class', $this->atoms[$id]['fullnspath'], $id);
            }
            return $id;
        } else {
            return 0;
        }
    }

    private function processArguments($finals = [T_CLOSE_PARENTHESIS], $typehint = false) {
        $argumentsId = $this->addAtom('Arguments');
        $current = $this->id;
        $this->argumentsId = array();

        $fullcode = array();
        $rank = 0;
        if (in_array($this->tokens[$this->id + 1][0], [T_CLOSE_PARENTHESIS, T_CLOSE_BRACKET])) {
            $voidId = $this->addAtomVoid();
            $this->setAtom($voidId, ['rank' => 0]);
            $this->addLink($argumentsId, $voidId, 'ARGUMENT');

            $this->setAtom($argumentsId, ['code'     => $this->tokens[$current][1],
                                          'fullcode' => self::FULLCODE_VOID,
                                          'line'     => $this->tokens[$current][2],
                                          'token'    => $this->getToken($this->tokens[$current][0]),
                                          'count'    => 0,
                                          'args_max' => 0,
                                          'args_min' => 0]);
            $this->argumentsId[] = $voidId;

            ++$this->id;
        } else {
            $typehintId = 0;
            $defaultId = 0;
            $indexId = 0;
            $args_max = 0;
            $args_min = 0;
            
            while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
                ++$args_max;
                if ($typehint === true) {
                    $typehintId = $this->processTypehint();
    
                    $this->processNext();
                    $indexId = $this->popExpression();
                
                    if ($this->tokens[$this->id + 1][0] === T_EQUAL) {
                        ++$this->id; // Skip =
                        while (!in_array($this->tokens[$this->id + 1][0], [T_COMMA, T_CLOSE_PARENTHESIS, T_CLOSE_BRACKET])) {
                            $this->processNext();
                        }
                        $defaultId = $this->popExpression();
                    } else {
                        ++$args_min;
                        $defaultId = 0;
                    }
                } else {
                    $typehintId = 0;
                    $defaultId = 0;

                    while (!in_array($this->tokens[$this->id + 1][0], [T_COMMA, T_CLOSE_PARENTHESIS, T_SEMICOLON, T_CLOSE_BRACKET, T_CLOSE_TAG])) {
                        $this->processNext();
                    }
                    $indexId = $this->popExpression();
                }
                                
                while ($this->tokens[$this->id + 1][0] === T_COMMA) {
                    if ($indexId === 0) {
                        $indexId = $this->addAtomVoid();
                    }
                    
                    $this->setAtom($indexId, ['rank' => $rank++]);
                    $this->argumentsId[] = $indexId;
                    
                    if ($typehintId > 0) {
                        $this->addLink($indexId, $typehintId, 'TYPEHINT');
                        $this->setAtom($indexId, ['fullcode' => $this->atoms[$typehintId]['fullcode'] . ' '. $this->atoms[$indexId]['fullcode']]);
                    }
                    if ($defaultId > 0) {
                        $this->addLink($indexId, $defaultId, 'DEFAULT');
                        $this->setAtom($indexId, ['fullcode' => $this->atoms[$indexId]['fullcode'] . ' = '. $this->atoms[$defaultId]['fullcode']]);
                        $defaultId = 0;
                    }
                    $this->addLink($argumentsId, $indexId, 'ARGUMENT');
                    $fullcode[] = $this->atoms[$indexId]['fullcode'];
    
                    ++$this->id; // Skipping the comma ,
                    $indexId = 0;
                }
            };

            if ($indexId === 0) {
                $indexId = $this->addAtomVoid();
            }
            $this->setAtom($indexId, ['rank' => $rank++]);
            $this->argumentsId[] = $indexId;
            
            if ($typehintId > 0) {
                $this->addLink($indexId, $typehintId, 'TYPEHINT');
                $this->setAtom($indexId, ['fullcode' => $this->atoms[$typehintId]['fullcode'] . ' '. $this->atoms[$indexId]['fullcode']]);
            }
            if ($defaultId > 0) {
                $this->addLink($indexId, $defaultId, 'DEFAULT');
                $this->setAtom($indexId, ['fullcode' => $this->atoms[$indexId]['fullcode'] . ' = '. $this->atoms[$defaultId]['fullcode']]);
            }
            $this->addLink($argumentsId, $indexId, 'ARGUMENT');

            $fullcode[] = $this->atoms[$indexId]['fullcode'];

            // Skip the ) 
            ++$this->id;

            $this->setAtom($argumentsId, ['code'     => $this->tokens[$current][1],
                                          'fullcode' => join(', ', $fullcode),
                                          'line'     => $this->tokens[$current][2],
                                          'token'    => $this->getToken($this->tokens[$current][0]),
                                          'count'    => $rank,
                                          'args_max' => $args_max,
                                          'args_min' => $args_min]);
        }
        return $argumentsId;
    }
    
    private function processNextAsIdentifier() {
        ++$this->id;
        $id = $this->addAtom('Identifier');
        $this->setAtom($id, ['code'       => $this->tokens[$this->id][1],
                             'fullcode'   => $this->tokens[$this->id][1],
                             'line'       => $this->tokens[$this->id][2],
                             'token'      => $this->getToken($this->tokens[$this->id][0]),
                             'absolute'   => false]);
        $this->setAtom($id, ['fullnspath' => $this->getFullnspath($id)]);
        
        return $id;
    }

    private function processConst() {
        $constId = $this->addAtom('Const');
        $current = $this->id;
        $rank = 0;
        --$this->id; // back one step for the init in the next loop

        do {
            ++$this->id;
            $const = $this->id;
            $nameId = $this->processNextAsIdentifier();

            ++$this->id; // Skip = 
            while (!in_array($this->tokens[$this->id + 1][0], array(T_SEMICOLON, T_COMMA))) {
                $this->processNext();
            }
            $valueId = $this->popExpression();
            
            $defId = $this->addAtom('Constant');
            $this->addLink($defId, $nameId, 'NAME');
            $this->addLink($defId, $valueId, 'VALUE');

            $this->setAtom($defId, ['code'     => $this->tokens[$const][1],
                                    'fullcode' => $this->atoms[$nameId]['fullcode'].' = '.$this->atoms[$valueId]['fullcode'],
                                    'line'     => $this->tokens[$const][2],
                                    'token'    => $this->getToken($this->tokens[$const][0]),
                                    'rank'     => $rank++]);
            $fullcode[] = $this->atoms[$defId]['fullcode'];

            $fullnspath = $this->getFullnspath($nameId, 'const');
            $this->addDefinition('const', $fullnspath, $defId);
            $this->setAtom($constId, ['fullnspath'     => $fullnspath]);
            
            $this->addLink($constId, $defId, 'CONST');
        } while (!in_array($this->tokens[$this->id + 1][0], [T_SEMICOLON]));

        $this->setAtom($constId, ['code'     => $this->tokens[$current][1],
                                  'fullcode' => $this->tokens[$current][1].' '.join(', ', $fullcode),
                                  'line'     => $this->tokens[$current][2],
                                  'token'    => $this->getToken($this->tokens[$current][0]),
                                  'count'    => $rank]);

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

        if ($this->tokens[$this->id + 1][0] === T_VARIABLE) {
            $pppId = $this->processSGVariable('Ppp');
            $this->optionsTokens = array();
            return $pppId;
        }
        
        return $id;
    }

    private function processPublic() {
        $id = $this->processOptions('Public');

        if ($this->tokens[$this->id + 1][0] === T_VARIABLE) {
            $pppId = $this->processSGVariable('Ppp');
            $this->optionsTokens = array();
            return $pppId;
        }
        
        return $id;
    }

    private function processProtected() {
        $id = $this->processOptions('Protected');

        if ($this->tokens[$this->id + 1][0] === T_VARIABLE) {
            $pppId = $this->processSGVariable('Ppp');
            $this->optionsTokens = array();
            return $pppId;
        }

        return $id;
    }

    private function processPrivate() {
        $id = $this->processOptions('Private');

        if ($this->tokens[$this->id + 1][0] === T_VARIABLE) {
            $pppId = $this->processSGVariable('Ppp');
            return $pppId;
        }

        return $id;
    }

    private function processFunctioncall() {
        $nameId = $this->popExpression();
        ++$this->id; // Skipping the name, set on (
        $current = $this->id;

        $argumentsId = $this->processArguments();

        $functioncallId = $this->addAtom('Functioncall');
        if ($this->isContext(self::CONTEXT_NEW)) {
            $fullnspath = $this->getFullnspath($nameId, 'class');
            $this->addCall('class', $fullnspath, $functioncallId);
        } else {
            $fullnspath = $this->getFullnspath($nameId, 'function');
            // Probably weak check, since we haven't built fullnspath for functions yet... 
            if($fullnspath === '\\define') {
                $this->processDefineAsConstants($argumentsId);
            }
            $this->addCall('function', $fullnspath, $functioncallId);
        }
        $this->setAtom($functioncallId, ['code'       => $this->atoms[$nameId]['code'],
                                         'fullcode'   => $this->atoms[$nameId]['fullcode'].'('.$this->atoms[$argumentsId]['fullcode'].')',
                                         'line'       => $this->tokens[$current][2],
                                         'token'      => $this->atoms[$nameId]['token'],
                                         'fullnspath' => $fullnspath
                                        ]);
        $this->addLink($functioncallId, $argumentsId, 'ARGUMENTS');
        $this->addLink($functioncallId, $nameId, 'NAME');

        $this->pushExpression($functioncallId);
        $functioncallId = $this->processFCOA($functioncallId);

        return $functioncallId;
    }
    
    private function processString($fullnspath = true) {
        if (strtolower($this->tokens[$this->id][1]) == 'null' ) {
            $id = $this->addAtom('Null');
        } elseif (in_array(strtolower($this->tokens[$this->id][1]), ['true', 'false'])) {
            $id = $this->addAtom('Boolean');
        } else {
            $id = $this->addAtom('Identifier');
        }

        $this->setAtom($id, ['code'       => $this->tokens[$this->id][1],
                             'fullcode'   => $this->tokens[$this->id][1],
                             'line'       => $this->tokens[$this->id][2],
                             'token'      => $this->getToken($this->tokens[$this->id][0]),
                             'absolute'   => false]);
        // when this is not already done, we prepare the fullnspath as a constant
        $fullnspath = $this->getFullnspath($id, 'const');
        $this->setAtom($id, ['fullnspath' => $fullnspath]);
        $this->addCall('const', $fullnspath, $id);
        
        if ($this->tokens[$this->id + 1][0] === T_NS_SEPARATOR) {
            $this->pushExpression($id);
            ++$this->id;
            $this->processNsname();
            $id = $this->popExpression();
        } elseif ($this->tokens[$this->id + 1][0] === T_COLON &&
                  !in_array($this->tokens[$this->id - 1][0], array(T_DOUBLE_COLON, T_OBJECT_OPERATOR, T_QUESTION))) {
            $labelId = $this->addAtom('Label');
            $this->addLink($labelId, $id, 'LABEL');
            $this->setAtom($labelId, ['code'     => ':',
                                      'fullcode' => $this->atoms[$id]['fullcode'].' :',
                                      'line'     => $this->tokens[$this->id][2],
                                      'token'    => $this->getToken($this->tokens[$this->id][0])]);
                                      
            $this->pushExpression($labelId);
            $this->processSemicolon();
            return $labelId;
        }
        $this->pushExpression($id);

        // For functions and constants 
        $id = $this->processFCOA($id);

        return $id;
    }

    private function processPlusplus() {
        if ($this->hasExpression()) {
            $previousId = $this->popExpression();
            // postplusplus
            $plusplusId = $this->addAtom('Postplusplus');
            
            $this->addLink($plusplusId, $previousId, 'POSTPLUSPLUS');

            $fullcode = '';
            $this->setAtom($plusplusId, ['code'     => $this->tokens[$this->id][1],
                                         'fullcode' => $this->atoms[$previousId]['fullcode'] .
                                                       $this->tokens[$this->id][1],
                                         'line'     => $this->tokens[$this->id][2],
                                         'token'    => $this->getToken($this->tokens[$this->id][0])]);
            $this->pushExpression($plusplusId);
        } else {
            // preplusplus
            $plusplusId = $this->processSingleOperator('Preplusplus', $this->getPrecedence($this->tokens[$this->id][0]), 'PREPLUSPLUS');
        }
    }
    
    private function processStatic() {
        if ($this->tokens[$this->id + 1][0] === T_DOUBLE_COLON) {
            $id = $this->processSingle('Identifier');
            $this->setAtom($id, ['fullnspath' => '\\static']);
            return $id;
        } elseif ($this->tokens[$this->id + 1][0] === T_OPEN_PARENTHESIS) {
            $nameId = $this->addAtom('Identifier');
            $this->setAtom($nameId, ['code'       => $this->tokens[$this->id][1],
                                     'fullcode'   => $this->tokens[$this->id][1],
                                     'line'       => $this->tokens[$this->id][2],
                                     'token'      => $this->getToken($this->tokens[$this->id][0]),
                                     'fullnspath' => '\\static']
                                     );
            $this->pushExpression($nameId);

            return $this->processFunctioncall();
        } elseif ($this->tokens[$this->id + 1][0] === T_VARIABLE) {
            if ($this->isContext(self::CONTEXT_CLASS) &&
                !$this->isContext(self::CONTEXT_FUNCTION)) {
                // something like public static
                $this->processOptions('Static');

                return $this->processSGVariable('Ppp');
            } else {
                return $this->processStaticVariable();
            }
        } else {
            return $this->processOptions('Static');
        }
    }

    private function processSGVariable($atom) {
        $current = $this->id;
        $staticId = $this->addAtom($atom);
        $rank = 0;

        $fullcode = array();
        foreach($this->optionsTokens as $name => $optionId) {
            $this->addLink($staticId, $optionId, strtoupper($name));
            $fullcode[] = $this->atoms[$optionId]['fullcode'];
        }
        $this->optionsTokens = array();
        
        while ($this->tokens[$this->id + 1][0] !== T_SEMICOLON) {
            $this->processNext();
            
            if ($this->tokens[$this->id + 1][0] === T_COMMA) {
                $elementId = $this->popExpression();
                $this->setAtom($elementId, ['rank' => $rank++]);
                $this->addLink($staticId, $elementId, strtoupper($atom));

                $fullcode[] = $this->atoms[$elementId]['fullcode'];
                ++$this->id;
            }
        } ;
       $elementId = $this->popExpression();
       $this->addLink($staticId, $elementId, strtoupper($atom));

       $fullcode[] = $this->atoms[$elementId]['fullcode'];

        $this->setAtom($staticId, ['code'     => $this->tokens[$current][1],
                                   'fullcode' => $this->tokens[$current][1] . ' ' . join(' ', $fullcode),
                                   'line'     => $this->tokens[$current][2],
                                   'token'    => $this->getToken($this->tokens[$current][0]),
                                   'count'    => $rank]);
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
        $this->addLink($id, $variableId, 'VARIABLE');
        $this->setAtom($variableId, ['code'       => '[',
                                     'fullcode'   => '[ '.self::FULLCODE_SEQUENCE.' ]',
                                     'line'       => $this->tokens[$this->id][2],
                                     'token'      => $this->getToken($this->tokens[$this->id][0]),
                                     'fullnspath' => '\\array']);

        // No need to skip opening bracket
        $argumentId = $this->processArguments([T_CLOSE_BRACKET]);
        $this->addLink($id, $argumentId, 'ARGUMENTS');

        $this->setAtom($id, ['code'       => $this->tokens[$current][1],
                             'fullcode'   => '[' . $this->atoms[$argumentId]['fullcode'] . ']' ,
                             'line'       => $this->tokens[$this->id][2],
                             'token'      => $this->getToken($this->tokens[$current][0]),
                             'fullnspath' => '\\array']);
        $this->pushExpression($id);
        
        return $this->processFCOA($id);
    }
    
    private function processBracket() {
        $id = $this->addAtom('Array');
        $current = $this->id;

        $variableId = $this->popExpression();
        $this->addLink($id, $variableId, 'VARIABLE');

        // Skip opening bracket
        $opening = $this->tokens[$this->id + 1][0];
        $opening === '{' ? $closing = '}' : $closing = ']';
         
        ++$this->id;
        do {
            $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], [T_CLOSE_BRACKET, T_CLOSE_CURLY])) ;

        // Skip closing bracket
        ++$this->id;

        $indexId = $this->popExpression();
        $this->addLink($id, $indexId, 'INDEX');

        $this->setAtom($id, ['code'      => $opening,
                             'fullcode'  => $this->atoms[$variableId]['fullcode'] . $opening .
                                            $this->atoms[$indexId]['fullcode']    . $closing ,
                             'line'      => $this->tokens[$current][2],
                             'token'     => $this->getToken($this->tokens[$current][0]),
                             'enclosing' => false]);
        $this->pushExpression($id);
        
        return $this->processFCOA($id);
    }
    
    private function processBlock($standalone = true) {
        $this->startSequence();
        
        // Case for {}
        if ($this->tokens[$this->id + 1][0] === T_CLOSE_CURLY) {
            $voidId = $this->addAtomVoid();
            $this->addToSequence($voidId);
        } else {
            while (!in_array($this->tokens[$this->id + 1][0], [T_CLOSE_CURLY])) {
                $this->processNext();
            };
            
            if ($this->tokens[$this->id + 1][0] !== T_CLOSE_CURLY) {
                $this->processSemicolon();
            }
        }

        $blockId = $this->sequence;
        $this->endSequence();
        
        $this->setAtom($blockId, ['code'     => '{}',
                                  'fullcode' => static::FULLCODE_BLOCK,
                                  'line'     => $this->tokens[$this->id][2],
                                  'token'    => $this->getToken($this->tokens[$this->id][0]),
                                  'bracket'  => true]);

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
            
            if ($this->tokens[$this->id + 1][0] === T_COMMA) {
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
        $x = ['code'     => $this->atoms[$current]['code'],
              'fullcode' => self::FULLCODE_SEQUENCE,
              'line'     => $this->tokens[$this->id][2],
              'token'    => $this->getToken($this->tokens[$this->id][0])];

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

        $this->processForblock([T_SEMICOLON]);
        $initId = $this->popExpression();
        $this->addLink($forId, $initId, 'INIT');

        $this->processForblock([T_SEMICOLON]);
        $finalId = $this->popExpression();
        $this->addLink($forId, $finalId, 'FINAL');

        $this->processForblock([T_CLOSE_PARENTHESIS]);
        $incrementId = $this->popExpression();
        $this->addLink($forId, $incrementId, 'INCREMENT');

        $isColon = ($this->tokens[$current][0] === T_FOR) && ($this->tokens[$this->id + 1][0] === T_COLON);

        $blockId = $this->processFollowingBlock([T_ENDFOR]);
        $this->popExpression();
        $this->addLink($forId, $blockId, 'BLOCK');
        
        $code = $this->tokens[$current][1];
        if ($isColon) {
            $fullcode = $this->tokens[$current][1].'(' . $this->atoms[$initId]['fullcode'] . ' ; ' . $this->atoms[$finalId]['fullcode'] . ' ; ' . $this->atoms[$incrementId]['fullcode'] . ') : '.self::FULLCODE_SEQUENCE.' endfor';
        } else {
            $fullcode = $this->tokens[$current][1].'(' . $this->atoms[$initId]['fullcode'] . ' ; ' . $this->atoms[$finalId]['fullcode'] . ' ; ' . $this->atoms[$incrementId]['fullcode'] . ')'.self::FULLCODE_BLOCK;
        }
        
        $this->setAtom($forId, ['code'        => $code,
                                'fullcode'    => $fullcode,
                                'line'        => $this->tokens[$current][2],
                                'token'       => $this->getToken($this->tokens[$this->id][0]),
                                'alternative' => $isColon]);
        $this->pushExpression($forId);
        
        if ($isColon === true) {
            ++$this->id; // skip endfor
            if ($this->tokens[$this->id + 1][0] === T_SEMICOLON) {
                ++$this->id; // skip ; (will do just below)
            }
        }
        $this->processSemicolon($forId);

        return $forId;
    }
    
    private function processForeach() {
        $id = $this->addAtom('Foreach');
        $current = $this->id;
        ++$this->id; // Skip foreach

        while (!in_array($this->tokens[$this->id + 1][0], [T_AS])) {
            $this->processNext();
        };

        $sourceId = $this->popExpression();
        $this->addLink($id, $sourceId, 'SOURCE');
        
        $as = $this->tokens[$this->id + 1][1];
        ++$this->id; // Skip as

        while (!in_array($this->tokens[$this->id + 1][0], [T_CLOSE_PARENTHESIS, T_DOUBLE_ARROW])) {
            $this->processNext();
        };

        if ($this->tokens[$this->id + 1][0] === T_DOUBLE_ARROW) {
            $this->processNext();
        }
        
        $valueId = $this->popExpression();
        $this->addLink($id, $valueId, 'VALUE');

        ++$this->id; // Skip )
        $isColon = ($this->tokens[$current][0] === T_FOREACH) && ($this->tokens[$this->id + 1][0] === T_COLON);

        $blockId = $this->processFollowingBlock([T_ENDFOREACH]);
        $this->popExpression();
        $this->addLink($id, $blockId, 'BLOCK');

        if ($isColon === true) {
            ++$this->id; // skip endforeach
            $fullcode = $this->tokens[$current][1].'(' . $this->atoms[$sourceId]['fullcode'] . ' '.$as.' '. $this->atoms[$valueId]['fullcode'] .') : '.self::FULLCODE_SEQUENCE.' endforeach';
        } else {
            $fullcode = $this->tokens[$current][1].'(' . $this->atoms[$sourceId]['fullcode'] . ' '.$as.' '. $this->atoms[$valueId]['fullcode'] .')'.self::FULLCODE_BLOCK;
        }

        $this->setAtom($id, ['code'        => $this->tokens[$current][1],
                             'fullcode'    => $fullcode,
                             'line'        => $this->tokens[$current][2],
                             'token'       => $this->getToken($this->tokens[$current][0]),
                             'alternative' => $isColon]);
        $this->pushExpression($id);
        $this->processSemicolon();

        return $id;
    }

    private function processFollowingBlock($finals) {
        if ($this->tokens[$this->id + 1][0] === T_OPEN_CURLY) {
            ++$this->id;
            $blockId = $this->processBlock(false);
        } elseif ($this->tokens[$this->id + 1][0] === T_COLON) {
            $this->startSequence();
            $blockId = $this->sequence;
            ++$this->id; // skip :

            while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
                $this->processNext();
            };

            $this->pushExpression($this->sequence);
            $this->endSequence();
        } elseif (in_array($this->tokens[$this->id + 1][0], [T_SEMICOLON])) {
            // void; One epxression block, with ;
            $this->startSequence();
            $blockId = $this->sequence;

            $voidId = $this->addAtomVoid();
            $this->addToSequence($voidId);
            $this->endSequence();
            $this->pushExpression($blockId);
            ++$this->id;

        } elseif (in_array($this->tokens[$this->id + 1][0], [T_CLOSE_TAG, T_CLOSE_CURLY, T_CLOSE_PARENTHESIS])) {
            // Completely void (not even ;)
            $this->startSequence();
            $blockId = $this->sequence;

            $voidId = $this->addAtomVoid();
            $this->addToSequence($voidId);
            $this->endSequence();
            
        } else {
            // One expression only
            $this->startSequence();
            $blockId = $this->sequence;
            $current = $this->id;
            
            // This may include WHILE in the list of finals for do....while
            $finals = array_merge([T_SEMICOLON, T_CLOSE_TAG, T_ELSE, T_END, T_CLOSE_CURLY], $finals);
            if (in_array($this->tokens[$this->id + 1][0], [T_IF, T_FOREACH, T_SWITCH, T_FOR])) {
                $this->processNext();
            } else {
                while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
                    $this->processNext();
                };
                $expressionId = $this->popExpression();
                $this->addToSequence($expressionId);
            }
            
            $this->endSequence();
            
            if (!in_array($this->tokens[$current + 1][0], [T_IF, T_SWITCH, T_WHILE, T_FOR, T_FOREACH])) {
                ++$this->id;
            }
            
            $this->pushExpression($blockId);
        }
        
        return $blockId;
    }

    private function processDo() {
        $dowhileId = $this->addAtom('Dowhile');
        $current = $this->id;
        
        $blockId = $this->processFollowingBlock([T_WHILE]);
        $this->popExpression();
        $this->addLink($dowhileId, $blockId, 'BLOCK');

        $while = $this->tokens[$this->id + 1][1];
        ++$this->id; // Skip while
        ++$this->id; // Skip (

        while (!in_array($this->tokens[$this->id + 1][0], [T_CLOSE_PARENTHESIS])) {
            $this->processNext();
        };
        ++$this->id; // skip )
        $conditionId = $this->popExpression();
        $this->addLink($dowhileId, $conditionId, 'CONDITION');

        $this->setAtom($dowhileId, ['code'     => $this->tokens[$current][1],
                                    'fullcode' => $this->tokens[$current][1].self::FULLCODE_BLOCK.$while.' (' . $this->atoms[$conditionId]['fullcode'] . ')',
                                    'line'     => $this->tokens[$current][2],
                                    'token'    => $this->getToken($this->tokens[$current][0]) ]);
        $this->pushExpression($dowhileId);
        
        return $dowhileId;
    }
    
    private function processWhile() {
        $whileId = $this->addAtom('While');
        $current = $this->id;
        
        ++$this->id; // Skip while

        while (!in_array($this->tokens[$this->id + 1][0], [T_CLOSE_PARENTHESIS])) {
            $this->processNext();
        };
        $conditionId = $this->popExpression();
        $this->addLink($whileId, $conditionId, 'CONDITION');

        ++$this->id; // Skip )
        $isColon = ($this->tokens[$current][0] === T_WHILE) && ($this->tokens[$this->id + 1][0] === T_COLON);
        $blockId = $this->processFollowingBlock([T_ENDWHILE]);
        $this->popExpression();

        $this->addLink($whileId, $blockId, 'BLOCK');

        if ($isColon === true) {
            ++$this->id;
            if ($this->tokens[$this->id + 1][0] === T_SEMICOLON) {
                ++$this->id; // skip ;
            }
            
            $fullcode = $this->tokens[$current][1] . ' (' . $this->atoms[$conditionId]['fullcode'] . ') : ' . self::FULLCODE_SEQUENCE . ' endwhile';
        } else {
            $fullcode = $this->tokens[$current][1] . ' (' . $this->atoms[$conditionId]['fullcode'] . ')' . self::FULLCODE_BLOCK;
        }

        $this->setAtom($whileId, ['code'        => $this->tokens[$current][1],
                                  'fullcode'    => $fullcode,
                                  'line'        => $this->tokens[$current][2],
                                  'token'       => $this->getToken($this->tokens[$current][0]),
                                  'alternative' => $isColon ]);

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
        $isColon = ($this->tokens[$current][0] === T_DECLARE) && ($this->tokens[$this->id + 1][0] === T_COLON);

        $blockId = $this->processFollowingBlock([T_ENDDECLARE]);
        $this->popExpression();
        $this->addLink($declareId, $blockId, 'BLOCK');

        if ($isColon === true) {
            ++$this->id; // skip enddeclare
            ++$this->id; // skip ;
            $fullcode = $this->tokens[$current][1].' (' . $this->atoms[$argsId]['fullcode'] . ') : '.self::FULLCODE_SEQUENCE.' enddeclare';
        } else {
            $fullcode = $this->tokens[$current][1].' (' . $this->atoms[$argsId]['fullcode'] . ') '.self::FULLCODE_BLOCK;
        }
        $this->pushExpression($declareId);
        $this->processSemicolon();
            
        $this->setAtom($declareId, ['code'        => $this->tokens[$current][1],
                                    'fullcode'    => $fullcode,
                                    'line'        => $this->tokens[$current][2],
                                    'token'       => $this->getToken($this->tokens[$current][0]),
                                    'alternative' => $isColon ]);
        return $declareId;
    }
    
    private function processDefault() {
        $defaultId = $this->addAtom('Default');
        $current = $this->id;
        ++$this->id; // Skip : or ;

        $this->startSequence();
        while (!in_array($this->tokens[$this->id + 1][0], [T_CLOSE_CURLY, T_CASE, T_DEFAULT, T_ENDSWITCH])) {
            $this->processNext();
        };
        $this->addLink($defaultId, $this->sequence, 'CODE');
        $this->endSequence();
        
        $this->setAtom($defaultId, ['code'     => $this->tokens[$current][1],
                                    'fullcode' => $this->tokens[$current][1].' : '.self::FULLCODE_SEQUENCE,
                                    'line'     => $this->tokens[$current][2],
                                    'token'    => $this->getToken($this->tokens[$current][0])]);
        $this->pushExpression($defaultId);
        
        return $defaultId;
    }

    private function processCase() {
        $caseId = $this->addAtom('Case');
        $current = $this->id;

        while (!in_array($this->tokens[$this->id + 1][0], [T_COLON, T_SEMICOLON])) {
            $this->processNext();
        };
        
        $itemId = $this->popExpression();
        $this->addLink($caseId, $itemId, 'CASE');

        ++$this->id; // Skip : 

        $this->startSequence();
        while (!in_array($this->tokens[$this->id + 1][0], [T_CLOSE_CURLY, T_CASE, T_DEFAULT, T_ENDSWITCH])) {
            $this->processNext();
        };
        $this->addLink($caseId, $this->sequence, 'CODE');
        $this->endSequence();
        
        $this->setAtom($caseId, ['code'     => $this->tokens[$current][1].' '.$this->atoms[$itemId]['fullcode'].' : '.self::FULLCODE_SEQUENCE.' ',
                                 'fullcode' => $this->tokens[$current][1].' '.$this->atoms[$itemId]['fullcode'].' : '.self::FULLCODE_SEQUENCE.' ',
                                 'line'     => $this->tokens[$current][2],
                                 'token'    => $this->getToken($this->tokens[$current][0])]);
        $this->pushExpression($caseId);
        
        return $caseId;
    }
    
    private function processSwitch() {
        $switchId = $this->addAtom('Switch');
        $current = $this->id;
        ++$this->id; // Skip (

        while (!in_array($this->tokens[$this->id + 1][0], [T_CLOSE_PARENTHESIS])) {
            $this->processNext();
        };
        $nameId = $this->popExpression();
        $this->addLink($switchId, $nameId, 'NAME');

        $casesId = $this->addAtom('Sequence');
        $this->setAtom($casesId, ['code'     => ' / * cases * /',
                                  'fullcode' => ' / * cases * /',
                                  'line'     => $this->tokens[$current][2],
                                  'token'    => $this->getToken($this->tokens[$current][0]),
                                  'bracket'  => true]);
        $this->addLink($switchId, $casesId, 'CASES');
        ++$this->id;

        $isColon = ($this->tokens[$this->id + 1][0] === T_COLON);
        
        $rank = 0;
        if ($this->tokens[$this->id + 1][0] === T_CLOSE_PARENTHESIS) {
            $voidId = $this->addAtomVoid();
            $this->addLink($casesId, $voidId, 'ELEMENT');
            $this->setAtom($voidId, ['rank' => $rank]);
            
            ++$this->id;
        } else {
            if ($this->tokens[$this->id + 1][0] === T_OPEN_CURLY) {
                ++$this->id;
                $finals = [T_CLOSE_CURLY];
            } else {
                ++$this->id; // skip :
                $finals = [T_ENDSWITCH];
            }
            while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
                $this->processNext();
            
                $caseId = $this->popExpression();
                $this->addLink($casesId, $caseId, 'ELEMENT');
                $this->setAtom($caseId, ['rank' => $rank++]);
            };
        }
        ++$this->id;
        $this->setAtom($casesId, ['count'     => $rank]);

        
        if ($isColon) {
            $fullcode = $this->tokens[$current][1].' ('.$this->atoms[$nameId]['fullcode'].') : /* cases */ endswitch';
        } else {
            $fullcode = $this->tokens[$current][1].' ('.$this->atoms[$nameId]['fullcode'].') { /* cases */ }';
        }

        $this->setAtom($switchId, ['code'        => $this->tokens[$current][1],
                                   'fullcode'    => $fullcode,
                                   'line'        => $this->tokens[$current][2],
                                   'token'       => $this->getToken($this->tokens[$current][0]),
                                   'alternative' => $isColon]);
        
        $this->pushExpression($switchId);
        $this->processSemicolon();
        
        return $switchId;
    }

    private function processIfthen() {
        $id = $this->addAtom('Ifthen');
        $current = $this->id;
        ++$this->id; // Skip (

        while (!in_array($this->tokens[$this->id + 1][0], [T_CLOSE_PARENTHESIS])) {
            $this->processNext();
        };
        $conditionId = $this->popExpression();
        $this->addLink($id, $conditionId, 'CONDITION');

        ++$this->id; // Skip )
        $isInitialIf = $this->tokens[$current][0] === T_IF;
        $isColon =  $this->tokens[$this->id + 1][0] === T_COLON;
        
        $thenId = $this->processFollowingBlock([T_ENDIF, T_ELSE, T_ELSEIF]);
        $this->popExpression();
        $this->addLink($id, $thenId, 'THEN');
        
        // Managing else case
        if (in_array($this->tokens[$this->id][0], [T_END, T_CLOSE_TAG])) {
            $else = '';
            // No else, end of a script
            --$this->id;
            // Back up one unit to allow later processing for sequence
        } elseif ($this->tokens[$this->id + 1][0] == T_ELSEIF){
            ++$this->id;

            $elseifId = $this->processIfthen();
            $this->addLink($id, $elseifId, 'ELSE');

            $else = $this->atoms[$elseifId]['fullcode'];
        } elseif ($this->tokens[$this->id + 1][0] == T_ELSE){
            $else = $this->tokens[$this->id + 1][1];
            ++$this->id; // Skip else

            $elseId = $this->processFollowingBlock([T_ENDIF]);
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
            ++$this->id;
            if ($this->tokens[$this->id + 1][0] === T_SEMICOLON) {
                ++$this->id; // skip ;
            }
        }
        
        if ($isColon) {
            $fullcode = $this->tokens[$current][1].' (' . $this->atoms[$conditionId]['fullcode'] . ') : '.$this->atoms[$thenId]['fullcode'].$else
                        .($isInitialIf === true ? ' endif' : '');
        } else {
            $fullcode = $this->tokens[$current][1].' (' . $this->atoms[$conditionId]['fullcode'] . ')'.$this->atoms[$thenId]['fullcode'].$else;
        }
        
        if ($this->tokens[$current][0] === T_IF) {
            $this->pushExpression($id);
            $this->processSemicolon();
        }

        $this->setAtom($id, ['code'        => $this->tokens[$current][1],
                             'fullcode'    => $fullcode,
                             'line'        => $this->tokens[$current][2],
                             'token'       => $this->getToken($this->tokens[$current][0]),
                             'alternative' => $isColon ]);
        
        return $id;
    }

    private function processParenthesis() {
        $parentheseId = $this->addAtom('Parenthesis');

        while (!in_array($this->tokens[$this->id + 1][0], [T_CLOSE_PARENTHESIS])) {
            $this->processNext();
        };

        $indexId = $this->popExpression();
        $this->addLink($parentheseId, $indexId, 'CODE');

        $this->setAtom($parentheseId, ['code'     => $this->tokens[$this->id][1],
                                       'fullcode' => '(' . $this->atoms[$indexId]['fullcode'] . ')',
                                       'line'     => $this->tokens[$this->id][2],
                                       'token'    => $this->getToken($this->tokens[$this->id][0]) ]);
        $this->pushExpression($parentheseId);
        ++$this->id; // Skipping the )

        return $this->processFCOA($parentheseId);
    }
    
    private function makeFunctioncall($nameTokenId, $argumentsId = 0) {
        $functioncallId = $this->addAtom('Functioncall');
        $current = $this->id;

        if ($argumentsId === 0) {
            $voidId = $this->addAtomVoid();

            $argumentsId = $this->addAtom('Arguments');
            $this->addLink($argumentsId, $voidId, 'ARGUMENT');
            $this->setAtom($argumentsId, ['code'     => $this->atoms[$voidId]['code'],
                                          'fullcode' => $this->atoms[$voidId]['fullcode'],
                                          'line'     => $this->tokens[$current][2],
                                          'token'    => $this->getToken($this->tokens[$current][0])]);
        }
    
        $nameId = $this->addAtom('Identifier');
        $this->addLink($functioncallId, $nameId, 'NAME');
        $this->setAtom($nameId, ['code'     => $this->tokens[$nameTokenId][1],
                                 'fullcode' => $this->tokens[$nameTokenId][1],
                                 'line'     => $this->tokens[$current][2],
                                 'token'    => $this->getToken($this->tokens[$current][0])]);
    
        $this->addLink($functioncallId, $argumentsId, 'ARGUMENTS');
        $this->setAtom($functioncallId, ['code'     => $this->tokens[$nameTokenId][1],
                                         'fullcode' => $this->tokens[$nameTokenId][1]. ' '.
                                                       $this->atoms[$argumentsId]['code'],
                                         'line'     => $this->tokens[$current][2],
                                         'token'    => $this->getToken($this->tokens[$current][0]) ]);

        $this->pushExpression($functioncallId);
        
        return $functioncallId;
    }

    private function processExit() {
        if (in_array($this->tokens[$this->id + 1][0], [T_CLOSE_PARENTHESIS, T_SEMICOLON, T_CLOSE_TAG, T_CLOSE_BRACKET, T_COLON])) {
            $nameId = $this->addAtom('Identifier');
            $this->setAtom($nameId, ['code'     => $this->tokens[$this->id][1],
                                     'fullcode' => $this->tokens[$this->id][1],
                                     'line'     => $this->tokens[$this->id][2],
                                     'token'    => $this->getToken($this->tokens[$this->id][0]) ]);

            $voidId = $this->addAtomVoid();

            $argumentsId = $this->addAtom('Arguments');
            $this->addLink($argumentsId, $voidId, 'ARGUMENT');
            $this->setAtom($argumentsId, ['code'    => $this->atoms[$voidId]['code'],
                                         'fullcode' => $this->atoms[$voidId]['fullcode'],
                                         'line'     => $this->tokens[$this->id][2],
                                         'token'    => $this->getToken($this->tokens[$this->id][0])]);

            $functioncallId = $this->addAtom('Functioncall');
            $this->setAtom($functioncallId, ['code'       => $this->atoms[$nameId]['code'],
                                             'fullcode'   => $this->atoms[$nameId]['fullcode'] . ' ' .
                                                             ($this->atoms[$argumentsId]['atom'] === 'Void' ? self::FULLCODE_VOID :  $this->atoms[$argumentsId]['fullcode']),
                                             'line'       => $this->tokens[$this->id][2],
                                             'token'      => $this->getToken($this->tokens[$this->id][0]),
                                             'fullnspath' => '\\'.strtolower($this->atoms[$nameId]['code']),
                                             
                                           ]);
            $this->addLink($functioncallId, $argumentsId, 'ARGUMENTS');
            $this->addLink($functioncallId, $nameId, 'NAME');

            $this->pushExpression($functioncallId);

            return $functioncallId;
        } else {
            --$this->id;
            $nameId = $this->processNextAsIdentifier();
            $this->pushExpression($nameId);

            return $this->processFCOA($nameId);
        }
    }
    
    private function processArray() {
        return $this->processString();
    }

    private function processTernary() {
        static $r = 0;
        $r++;

        $current = $this->id;

        $conditionId = $this->popExpression();
        $ternaryId = $this->addAtom('Ternary');
        
        while (!in_array($this->tokens[$this->id + 1][0], [T_COLON]) ) {
            $id = $this->processNext();
        };
        $thenId = $this->popExpression();
        ++$this->id; // Skip colon

        $finals = $this->getPrecedence($this->tokens[$this->id][0]);
        $finals[] = T_COLON; // Added from nested Ternary
        while (!in_array($this->tokens[$this->id + 1][0], $finals) ) {
            $id = $this->processNext();
        } ;
        $elseId = $this->popExpression();

        $this->addLink($ternaryId, $conditionId, 'CONDITION');
        $this->addLink($ternaryId, $thenId, 'THEN');
        $this->addLink($ternaryId, $elseId, 'ELSE');

        $x = ['code'     => $this->tokens[$current][1],
              'fullcode' => $this->atoms[$conditionId]['fullcode'] . ' ?' .
                            ($this->atoms[$thenId]['atom'] == 'Void' ? '' : ' '.$this->atoms[$thenId]['fullcode'].' ' ). ': ' .
                            $this->atoms[$elseId]['fullcode'],
              'line'     => $this->tokens[$current][2],
              'token'    => $this->getToken($this->tokens[$current][0])];
        $this->setAtom($ternaryId, $x);

        $this->pushExpression($ternaryId);

        return $ternaryId;
    }
    
    //////////////////////////////////////////////////////
    /// processing single tokens
    //////////////////////////////////////////////////////
    private function processSingle($atom) {
        $id = $this->addAtom($atom);
        $this->setAtom($id, ['code'     => $this->tokens[$this->id][1],
                             'fullcode' => $this->tokens[$this->id][1],
                             'line'     => $this->tokens[$this->id][2],
                             'token'    => $this->getToken($this->tokens[$this->id][0]) ]);
        $this->pushExpression($id);

        return $id;
    }

    private function processInlineHtml() {
        $inlineId = $this->processSingle('InlineHtml');
        $this->popExpression();
        
        return $inlineId;
    }

    private function processNamespaceBlock() {
        $this->startSequence();

        while (!in_array($this->tokens[$this->id + 1][0], [T_CLOSE_TAG, T_NAMESPACE, T_END])) {
            $this->processNext();
            
            if ($this->tokens[$this->id + 1][0] === T_NAMESPACE &&
                $this->tokens[$this->id + 2][0] === T_NS_SEPARATOR) {
                $this->processNext();
            }
        };
        $blockId = $this->sequence;
        $this->endSequence();
        
        $this->setAtom($blockId, ['code'     => '',
                                  'fullcode' => ' '.self::FULLCODE_SEQUENCE.' ',
                                  'line'     => $this->tokens[$this->id][2],
                                  'token'    => $this->getToken($this->tokens[$this->id][0])]);

        return $blockId;
    }
    
    private function processNamespace() {
        $current = $this->id;
        
        if ($this->tokens[$this->id + 1][0] === T_OPEN_CURLY) {
            $nameId = $this->addAtomVoid();
        } elseif ($this->tokens[$this->id + 1][0] === T_NS_SEPARATOR) {
            --$this->id;
            $nsnameId = $this->processOneNsname();
            $this->setAtom($nsnameId, ['fullnspath' => $this->getFullnspath($nsnameId)]);
            $this->pushExpression($nsnameId);
            
            return $this->processFCOA($nsnameId);
        } else {
            $nameId = $this->processOneNsname();
        }
        $namespaceId = $this->addAtom('Namespace');
        $this->addLink($namespaceId, $nameId, 'NAME');
        $this->setNamespace($nameId);

        // Here, we make sure namespace is encompassing the next elements.
        if ($this->tokens[$this->id + 1][0] === T_SEMICOLON) {
            // Process block 
            ++$this->id; // Skip ; to start actual sequence
            if ($this->tokens[$this->id + 1][0] === T_END) {
                $voidId = $this->addAtomVoid();
                $blockId = $this->addAtom('Sequence');
                $this->setAtom($blockId, ['code' => '{}',
                                          'fullcode' => self::FULLCODE_BLOCK,
                                          'line'     => $this->tokens[$this->id][2],
                                          'token'    => $this->getToken($this->tokens[$this->id][0]),
                                          'bracket'  => false]);
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
        
        $x = ['code'     => $this->tokens[$current][1],
              'fullcode' => $this->tokens[$current][1].' '.$this->atoms[$nameId]['fullcode'] .$block,
              'line'     => $this->tokens[$current][2],
              'token'    => $this->getToken($this->tokens[$current][0])];
        $this->setAtom($namespaceId, $x);

        return $namespaceId;
    }

    private function processAs() {
        if (in_array($this->tokens[$this->id + 1][0], [T_PRIVATE, T_PUBLIC, T_PROTECTED])) {
            $current = $this->id;
            $asId = $this->addAtom('As');

            $left = $this->popExpression();
            $this->addLink($asId, $left, 'NAME');
            
            if (in_array($this->tokens[$this->id + 1][0], [T_PRIVATE, T_PROTECTED, T_PUBLIC])) {
                $visibilityId = $this->processNextAsIdentifier();
                $this->addLink($asId, $visibilityId, strtoupper($this->atoms[$visibilityId]['code']));
            }

            if (!in_array($this->tokens[$this->id + 1][0], [T_COMMA, T_SEMICOLON])) {
                $aliasId = $this->processNextAsIdentifier();
                $this->addLink($asId, $aliasId, 'AS');
            } else {
                $aliasId = $this->addAtomVoid();
                $this->addLink($asId, $aliasId, 'AS');
            }

            $x = ['code'     => $this->tokens[$current][1],
                  'fullcode' => $this->atoms[$left]['fullcode'] . ' ' .
                                $this->tokens[$current][1] . ' ' .
                                (isset($visibilityId) ? $this->atoms[$visibilityId]['fullcode']. ' ' : ''),
                                $this->atoms[$aliasId]['fullcode'],
                  'line'     => $this->tokens[$current][2],
                  'token'    => $this->getToken($this->tokens[$current][0])];
            $this->setAtom($asId, $x);
            $this->pushExpression($asId);
            
            return $asId;
        return $additionId;
        } else {
            return $this->processOperator('As', $this->getPrecedence($this->tokens[$this->id][0]), ['NAME', 'AS']);
        }
    }

    private function processInsteadof() {
        return $this->processOperator('Insteadof', $this->getPrecedence($this->tokens[$this->id][0]), ['NAME', 'INSTEADOF']);
    }

    private function processUse() {
        $useId = $this->addAtom('Use');
        $current = $this->id;
        $useType = 'class';

        // use const
        if ($this->tokens[$this->id + 1][0] === T_CONST) {
            ++$this->id;

            $this->processSingle('Identifier');
            $constId = $this->popExpression();
            $this->addLink($useId, $constId, 'CONST');
            $useType = 'const';
        }

        // use function
        if ($this->tokens[$this->id + 1][0] === T_FUNCTION) {
            ++$this->id;

            $this->processSingle('Identifier');
            $constId = $this->popExpression();
            $this->addLink($useId, $constId, 'FUNCTION');
            $useType = 'function';
        }
        
        $fullcode = array();
        --$this->id;
        do {
            ++$this->id;
            $namespaceId = $this->processOneNsname();
            // Default case : use A\B
            $aliasId = $namespaceId;
            $originId = $namespaceId;
            $fullnspath = $this->makeFullnspath($namespaceId);

            if ($this->tokens[$this->id + 1][0] === T_AS) {
                // use A\B as C
                ++$this->id;
                $this->setAtom($originId, ['fullnspath' => $this->makeFullnspath($originId)]);
                
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

                $this->setAtom($namespaceId, ['fullnspath' => $fullnspath]);
                if (!$this->isContext(self::CONTEXT_CLASS) &&
                    !$this->isContext(self::CONTEXT_TRAIT) ) {
                    $alias = $this->addNamespaceUse($originId, $aliasId, $useType);
    
                    $this->setAtom($namespaceId, ['alias'  => $alias,
                                                  'origin' => $fullnspath ]);
                }
            } elseif ($this->tokens[$this->id + 1][0] === T_OPEN_CURLY) {
                //use A\B{} // Group
                $blockId = $this->processFollowingBlock([T_CLOSE_CURLY]);
                $this->popExpression();
                $this->addLink($useId, $blockId, 'BLOCK');
                $fullcode[] = $this->atoms[$namespaceId]['fullcode'] . ' ' . $this->atoms[$blockId]['fullcode'];

                // Several namespaces ? This has to be recalculated inside the block!! 
                $fullnspath = $this->makeFullnspath($namespaceId);

                $this->addLink($useId, $namespaceId, 'USE');
            } elseif ($this->tokens[$this->id + 1][0] === T_NS_SEPARATOR) {
                //use A\B\ {} // Prefixes, within a Class/Trait 
                $this->addLink($useId, $namespaceId, 'GROUPUSE');
                $prefix = $this->makeFullnspath($namespaceId);
                if ($prefix[0] != '\\') {
                    $prefix = '\\'.$prefix;
                }
                $prefix .= '\\';

                ++$this->id; // Skip \

                $fullcode2 = [];
                do {
                    ++$this->id; // Skip {

                    $useType = 'class';
                    // use const
                    if ($this->tokens[$this->id + 1][0] === T_CONST) {
                        ++$this->id;

                        $this->processSingle('Identifier');
                        $useTypeId = $this->popExpression();
                        $useType = 'const';
                    }

                    // use function
                    if ($this->tokens[$this->id + 1][0] === T_FUNCTION) {
                        ++$this->id;

                        $this->processSingle('Identifier');
                        $useTypeId = $this->popExpression();
                        $useType = 'function';
                    }

                    $id = $this->processOneNsname();
                    if ($useType !== 'class') {
                        $this->addLink($id, $useTypeId, strtoupper($useType));
                    }

                    if ($this->tokens[$this->id + 1][0] === T_AS) {
                        // A\B as C
                        ++$this->id;
                        $this->pushExpression($id);
                        $this->processAs();
                        $aliasId = $this->popExpression();

                        $this->setAtom($id, ['fullnspath' => $prefix.strtolower($this->atoms[$id]['fullcode']),
                                             'origin'     => $prefix.strtolower($this->atoms[$id]['fullcode']) ]);
                        $this->setAtom($aliasId, ['fullnspath' => $prefix.strtolower($this->atoms[$id]['fullcode']),
                                                  'origin'     => $prefix.strtolower($this->atoms[$id]['fullcode']) ]);

                        $alias = $this->addNamespaceUse($id, $aliasId, $useType);
                        $this->setAtom($aliasId, ['alias'      => $alias]);
                        $this->addLink($useId, $aliasId, 'USE');
                    } else {
                        $this->addLink($useId, $id, 'USE');
                        $this->setAtom($id, ['fullnspath' => $prefix.strtolower($this->atoms[$id]['fullcode']),
                                             'origin'     => $prefix.strtolower($this->atoms[$id]['fullcode'])]);

                        $alias = $this->addNamespaceUse($id, $id, $useType);
                        $this->setAtom($id, ['alias'      => $alias]);
                        
                    }
                } while (in_array($this->tokens[$this->id + 1][0], [T_COMMA]));
                
                $fullcode[] = $this->atoms[$namespaceId]['fullcode'] . self::FULLCODE_BLOCK;

                ++$this->id; // Skip }
            } else {
                $this->addLink($useId, $namespaceId, 'USE');

                if ($this->isContext(self::CONTEXT_CLASS) ||
                    $this->isContext(self::CONTEXT_TRAIT)) {
                    $this->addCall('class', $fullnspath, $namespaceId);
                }

                $fullcode[] = $this->atoms[$namespaceId]['fullcode'];

                $this->setAtom($namespaceId, ['fullnspath' => $fullnspath]);
                if (!$this->isContext(self::CONTEXT_CLASS) &&
                    !$this->isContext(self::CONTEXT_TRAIT) ) {
                    $alias = $this->addNamespaceUse($aliasId, $aliasId, $useType);
    
                    $this->setAtom($namespaceId, ['alias'  => $alias,
                                                  'origin' => $fullnspath ]);
                }
            }
            // No Else. Default will be dealt with by while() condition

        } while ($this->tokens[$this->id + 1][0] === T_COMMA);
        
        $x = ['code'     => $this->tokens[$current][1],
              'fullcode' => $this->tokens[$current][1].' '.join(", ", $fullcode),
              'line'     => $this->tokens[$current][2],
              'token'    => $this->getToken($this->tokens[$current][0])];
        $this->setAtom($useId, $x);
        $this->pushExpression($useId);

        if ($this->tokens[$this->id + 1][0] !== T_SEMICOLON) {
            $this->processSemicolon();
        }

        return $useId;
    }
    
    private function processVariable() {
        $variableId = $this->processSingle('Variable');
        $this->setAtom($variableId, ['reference' => false,
                                     'variadic'  => false,
                                     'enclosing' => false]);

        return $this->processFCOA($variableId);
    }
    
    private function processFCOA($id) {
        // For functions and constants 
        if ($this->tokens[$this->id + 1][0] === T_OPEN_PARENTHESIS) {
            return $this->processFunctioncall();
        } elseif ($this->tokens[$this->id + 1][0] === T_OPEN_BRACKET &&
                  $this->tokens[$this->id + 2][0] === T_CLOSE_BRACKET) {
            return $this->processAppend();
        } elseif ($this->tokens[$this->id + 1][0] === T_OPEN_BRACKET ||
                  $this->tokens[$this->id + 1][0] === T_OPEN_CURLY) {
            return $this->processBracket();
        } elseif (in_array($this->atoms[$id]['atom'], ['Nsname', 'Identifier'])) {
            $this->setAtom($id, ['fullnspath' => $this->getFullnspath($id, $this->isContext(self::CONTEXT_NEW) ? 'class' : 'const')]);
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
        
        $x = ['code'     => $this->tokens[$current][1],
              'fullcode' => $this->atoms[$left]['fullcode'] . '[]',
              'line'     => $this->tokens[$current][2],
              'token'    => $this->getToken($this->tokens[$current][0])];
        $this->setAtom($appendId, $x);
        $this->pushExpression($appendId);
        
        ++$this->id;
        ++$this->id;
        
        // Mostly for arrays
        return $this->processFCOA($appendId);
    }
    
    private function processInteger() {
        $id = $this->processSingle('Integer');
        $value = $this->atoms[$id]['code'];
        
        if (strtolower(substr($value, 0, 2)) === '0b') {
            $actual = bindec(substr($value, 2));
        } elseif (strtolower(substr($value, 0, 2)) === '0x') {
            $actual = hexdec(substr($value, 2));
        } elseif (strtolower(substr($value, 0, 2)) === '0') {
            // PHP 7 will just stop.
            // PHP 5 will work until it fails
            $actual = octdec(substr($value, 1));
        } else {
            $actual = $value;
        }
        $this->setAtom($id, ['intval' => (abs($actual) > PHP_INT_MAX ? 0 : $actual)]);
        return $id;
    }

    private function processReal() {
        return $this->processSingle('Real');
    }
    
    private function processLiteral() {
        $id = $this->processSingle('String');
        
        if ($this->tokens[$this->id][0] === T_CONSTANT_ENCAPSED_STRING) {
            $this->setAtom($id, ['delimiter'   => $this->atoms[$id]['code'][0],
                                 'noDelimiter' => substr($this->atoms[$id]['code'], 1, -1)]);
        } else {
            $this->setAtom($id, ['delimiter'   => '',
                                 'noDelimiter' => '']);
        }
        $this->setAtom($id, ['encoding' => mb_detect_encoding($this->atoms[$id]['noDelimiter'])]);
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
        // Do while, so that AT least one loop is done.
        do {
            $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], $finals));

        $operandId = $this->popExpression();
        
        $this->addLink($operatorId, $operandId, $link);

        $x = ['code'     => $this->tokens[$current][1],
              'fullcode' => $this->tokens[$current][1] . $separator .
                            $this->atoms[$operandId]['fullcode'],
              'line'     => $this->tokens[$current][2],
              'token'    => $this->getToken($this->tokens[$current][0])];

        $this->setAtom($operatorId, $x);
        $this->pushExpression($operatorId);
        
        return $operatorId;
    }

    private function processCast() {
        return $this->processSingleOperator('Cast', $this->getPrecedence($this->tokens[$this->id][0]), 'CAST', ' ');
    }

    private function processReturn() {
        if (in_array($this->tokens[$this->id + 1][0], [T_CLOSE_TAG, T_SEMICOLON])) {
            $current = $this->id;
            
            // Case of return ; 
            $returnArgId = $this->addAtomVoid();
            $returnId = $this->addAtom('Return');
        
            $this->addLink($returnId, $returnArgId, 'RETURN');

            $x = ['code'     => $this->tokens[$current][1],
                  'fullcode' => $this->tokens[$current][1] . ' ;',
                  'line'     => $this->tokens[$current][2],
                  'token'    => $this->getToken($this->tokens[$current][0])];
            $this->setAtom($returnId, $x);

            $this->addToSequence($returnId);
        
            return $returnId;
        } else {
            return $this->processSingleOperator('Return', $this->getPrecedence($this->tokens[$this->id][0]), 'RETURN', ' ');
        }
    }
    
    private function processThrow() {
        return $this->processSingleOperator('Throw', $this->getPrecedence($this->tokens[$this->id][0]), 'THROW', ' ');
    }

    private function processYield() {
        if (in_array($this->tokens[$this->id + 1][0], [T_CLOSE_PARENTHESIS, T_SEMICOLON])) {
            $current = $this->id;
            
            // Case of return ; 
            $returnArgId = $this->addAtomVoid();
            $returnId = $this->addAtom('Yield');
        
            $this->addLink($returnId, $returnArgId, 'YIELD');

            $x = ['code'     => $this->tokens[$current][1],
                  'fullcode' => $this->tokens[$current][1] . ' ;',
                  'line'     => $this->tokens[$current][2],
                  'token'    => $this->getToken($this->tokens[$current][0])];
            $this->setAtom($returnId, $x);

            $this->addToSequence($returnId);
        
            return $returnId;
        } else {
            return $this->processSingleOperator('Yield', $this->getPrecedence($this->tokens[$this->id][0]), 'YIELD', ' ');
        }
    }

    private function processYieldfrom() {
        return $this->processSingleOperator('Yieldfrom', $this->getPrecedence($this->tokens[$this->id][0]), 'YIELD', ' ');
    }

    private function processNot() {
        return $this->processSingleOperator('Not', $this->getPrecedence($this->tokens[$this->id][0]), 'NOT');
    }

    private function processCurlyExpression() {
        ++$this->id;
        while (!in_array($this->tokens[$this->id + 1][0], [T_CLOSE_CURLY])) {
            $id = $this->processNext();
        } ;
        
        $codeId = $this->popExpression();
        $blockId = $this->addAtom('Block');
        $this->setAtom($blockId, ['code'     => '{}',
                                  'fullcode' => '{'.$this->atoms[$codeId]['fullcode'].'}',
                                  'line'     => $this->tokens[$this->id][2],
                                  'token'    => $this->getToken($this->tokens[$this->id][0])]);
        $this->addLink($blockId, $codeId, 'CODE');
        $this->pushExpression($blockId);

        ++$this->id; // Skip }

        return $blockId;
    }

    private function processDollar() {
        if ($this->tokens[$this->id + 1][0] === T_OPEN_CURLY) {
            $current = $this->id;

            $variableId = $this->addAtom('Variable');

            ++$this->id;
            while (!in_array($this->tokens[$this->id + 1][0], [T_CLOSE_CURLY]) ) {
                $id = $this->processNext();
            };
            
            // Skip }
            ++$this->id;

            $expressionId = $this->popExpression();
            $this->addLink($variableId, $expressionId, 'NAME');

            $x = ['code'     => $this->tokens[$current][1],
                  'fullcode' => $this->tokens[$current][1] . '{' .
                                $this->atoms[$expressionId]['fullcode'].'}',
                  'line'     => $this->tokens[$current][2],
                  'token'    => $this->getToken($this->tokens[$current][0])];
            $this->setAtom($variableId, $x);
            
            $this->pushExpression($variableId);
            return $this->processFCOA($variableId);
        } else {
            return $this->processSingleOperator('Variable', $this->getPrecedence($this->tokens[$this->id][0]), 'NAME');
        }
    }

    private function processClone() {
        return $this->processSingleOperator('Clone', $this->getPrecedence($this->tokens[$this->id][0]), 'CLONE', ' ' );
    }
    
    private function processGoto() {
        return $this->processSingleOperator('Goto', $this->getPrecedence($this->tokens[$this->id][0]), 'GOTO');
    }

    private function processNoscream() {
        return $this->processSingleOperator('Noscream', $this->getPrecedence($this->tokens[$this->id][0]), 'AT');
    }

    private function processNew() {
        $this->toggleContext(self::CONTEXT_NEW);
        $id =  $this->processSingleOperator('New', $this->getPrecedence($this->tokens[$this->id][0]), 'NEW', ' ');
        $this->toggleContext(self::CONTEXT_NEW);
        return $id;
    }

    //////////////////////////////////////////////////////
    /// processing binary operators
    //////////////////////////////////////////////////////
    private function processSign() {
        $current = $this->id;
        $sign = $this->tokens[$this->id][1];
        $code = $sign.'1';
        while (in_array($this->tokens[$this->id + 1][0], [T_PLUS, T_MINUS])) {
            ++$this->id;
            $sign = $this->tokens[$this->id][1].$sign;
            $code *= $this->tokens[$this->id][1].'1';
        }

        // -3 ** 3 => -(3 ** 3)
        if (($this->tokens[$this->id + 1][0] == T_LNUMBER || $this->tokens[$this->id + 1][0] == T_DNUMBER) &&
            $this->tokens[$this->id + 2][0] != T_POW) {
            $operandId = $this->processNext();

            $x = ['code'     => $sign . $this->atoms[$operandId]['code'],
                  'fullcode' => $sign . $this->atoms[$operandId]['fullcode'],
                  'line'     => $this->tokens[$this->id][2],
                  'token'    => $this->getToken($this->tokens[$this->id][0])];
            $this->setAtom($operandId, $x);

            return $operandId;
        } else {
            $finals = $this->getPrecedence($this->tokens[$this->id][0]);
            // process the actual load
            do {
                $this->processNext();
            } while (!in_array($this->tokens[$this->id + 1][0], $finals)) ;

            $signedId = $this->popExpression();

            for($i = strlen($sign) - 1; $i >= 0; --$i) {
                $signId = $this->addAtom('Sign');
                $this->addLink($signId, $signedId, 'SIGN');

                $x = ['code'     => $sign[$i] ,
                      'fullcode' => $sign[$i] . $this->atoms[$signedId]['fullcode'],
                      'line'     => $this->tokens[$this->id][2],
                      'token'    => $this->getToken($this->tokens[$this->id][0])];
                $this->setAtom($signId, $x);

                $signedId = $signId;
            }
            
            $this->pushExpression($signId);
            
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

        $finals = $this->getPrecedence($this->tokens[$this->id][0]);

        $additionId = $this->addAtom($atom);
        $this->addLink($additionId, $left, 'LEFT');
        
        do {
            $id = $this->processNext();

            if (in_array($this->tokens[$this->id + 1][0], [T_EQUAL, T_PLUS_EQUAL, T_AND_EQUAL, T_CONCAT_EQUAL, T_DIV_EQUAL, T_MINUS_EQUAL, T_MOD_EQUAL, T_MUL_EQUAL, T_OR_EQUAL, T_POW_EQUAL, T_SL_EQUAL, T_SR_EQUAL, T_XOR_EQUAL])) {
                $this->processNext();
            }
        } while (!in_array($this->tokens[$this->id + 1][0], $finals)) ;

        $right = $this->popExpression();
        
        $this->addLink($additionId, $right, 'RIGHT');

        $x = ['code'     => $this->tokens[$current][1],
              'fullcode' => $this->atoms[$left]['fullcode'] . ' ' .
                            $this->tokens[$current][1] . ' ' .
                            $this->atoms[$right]['fullcode'],
              'line'     => $this->tokens[$current][2],
              'token'    => $this->getToken($this->tokens[$current][0])];
        $this->setAtom($additionId, $x);
        $this->pushExpression($additionId);
        
        return $additionId;
    }

    private function processBreak() {
        $current = $this->id;
        $breakId = $this->addAtom($this->tokens[$this->id][0] === T_BREAK ? 'Break' : 'Continue');
        
        if ($this->tokens[$this->id + 1][0] === T_LNUMBER) {
            $this->processNext();
            
            $breakLevel = $this->popExpression();
        } else {
            $breakLevel = $this->addAtomVoid();
        }
        
        $this->addLink($breakId, $breakLevel, $this->tokens[$current][0] === T_BREAK ? 'BREAK' : 'CONTINUE');
        $x = ['code'     => $this->tokens[$current][1],
              'fullcode' => $this->tokens[$current][1] . ( $this->atoms[$breakLevel]['atom'] !== 'Void' ?  ' '. $this->atoms[$breakLevel]['fullcode'] : ''),
              'line'     => $this->tokens[$current][2],
              'token'    => $this->getToken($this->tokens[$current][0]) ];
        $this->setAtom($breakId, $x);
        $this->pushExpression($breakId);
        
        return $breakId;
    }
    
    private function processDoubleColon() {
        $current = $this->id;

        $leftId = $this->popExpression();

        $finals = $this->getPrecedence($this->tokens[$this->id][0]);
        $finals[] = T_DOUBLE_COLON;
        
        if ($this->tokens[$this->id + 1][0] === T_OPEN_CURLY) {
            $blockId = $this->processCurlyExpression();
            $right = $this->processFCOA($blockId);
            $this->popExpression();
        } elseif ($this->tokens[$this->id + 1][0] === T_DOLLAR) {
            ++$this->id; // Skip ::
            $blockId = $this->processDollar();
            $right = $this->processFCOA($blockId);
            $this->popExpression();
        } elseif (!in_array($this->tokens[$this->id + 1][0], [ T_VARIABLE, T_STRING])) {
            $right = $this->processNextAsIdentifier();
            $this->pushExpression($right);
            $right = $this->processFCOA($right);
            $this->popExpression();
        } else {
            do {
                $id = $this->processNext();
            } while (!in_array($this->tokens[$this->id + 1][0], $finals)) ;

            $right = $this->popExpression();
        }

        if ($this->atoms[$right]['atom'] == 'Identifier') {
            $staticId = $this->addAtom('Staticconstant');
            $links = 'CONSTANT';
        } elseif (in_array($this->atoms[$right]['atom'], array('Variable', 'Array', 'Arrayappend', 'MagicConstant', 'Concatenation', 'Block', 'Boolean', 'Null'))) {
            $staticId = $this->addAtom('Staticproperty');
            $links = 'PROPERTY';
        } elseif (in_array($this->atoms[$right]['atom'], array('Functioncall'))) {
            $staticId = $this->addAtom('Staticmethodcall');
            $links = 'METHOD';
        } else {
            die("Unprocessed atom in static call (right) : ".$this->atoms[$right]['atom']."\n");
        }

        $this->addLink($staticId, $leftId, 'CLASS');
        
        $fullnspath = $this->getFullnspath($leftId);
        $this->setAtom($leftId, ['fullnspath' => $this->getFullnspath($leftId)] );
        $this->addCall('class', $fullnspath, $leftId);
        
        $this->addLink($staticId, $right, $links);

        $x = ['code'     => $this->tokens[$current][1],
              'fullcode' => $this->atoms[$leftId]['fullcode'] . '::' . $this->atoms[$right]['fullcode'],
              'line'     => $this->tokens[$current][2],
              'token'    => $this->getToken($this->tokens[$current][0])];

        $this->setAtom($staticId, $x);
        $this->pushExpression($staticId);
    }

    private function processOperator($atom, $finals, $links = ['LEFT', 'RIGHT']) {
        $current = $this->id;
        $additionId = $this->addAtom($atom);

        $left = $this->popExpression();
        $this->addLink($additionId, $left, $links[0]);
        
        $finals = array_merge([], $finals);
        do {
            $this->processNext();
            
            if (in_array($this->tokens[$this->id + 1][0], [T_EQUAL, T_PLUS_EQUAL, T_AND_EQUAL, T_CONCAT_EQUAL, T_DIV_EQUAL, T_MINUS_EQUAL, T_MOD_EQUAL, T_MUL_EQUAL, T_OR_EQUAL, T_POW_EQUAL, T_SL_EQUAL, T_SR_EQUAL, T_XOR_EQUAL])) {
                $this->processNext();
            }
        } while (!in_array($this->tokens[$this->id + 1][0], $finals) );

        $right = $this->popExpression();
        
        $this->addLink($additionId, $right, $links[1]);

        $x = ['code'     => $this->tokens[$current][1],
              'fullcode' => $this->atoms[$left]['fullcode'] . ' ' .
                            $this->tokens[$current][1] . ' ' .
                            $this->atoms[$right]['fullcode'],
              'line'     => $this->tokens[$current][2],
              'token'    => $this->getToken($this->tokens[$current][0])];
        $this->setAtom($additionId, $x);
        $this->pushExpression($additionId);
        
        return $additionId;
    }

    private function processObjectOperator() {
        $current = $this->id;

        $left = $this->popExpression();

        if ($this->tokens[$this->id + 1][0] === T_OPEN_CURLY) {
            $blockId = $this->processCurlyExpression();
            $right = $this->processFCOA($blockId);
            $this->popExpression();
        } else {
            $finals = $this->getPrecedence($this->tokens[$this->id][0]);
            $finals[] = T_OBJECT_OPERATOR;
            while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
                $id = $this->processNext();
            } ;
            $right = $this->popExpression();
        }

        if (in_array($this->atoms[$right]['atom'], array('Variable', 'Array', 'Identifier', 'Concatenation', 'Arrayappend', 'Property', 'MagicConstant', 'Block', 'Boolean', 'Null'))) {
            $staticId = $this->addAtom('Property');
            $links = 'PROPERTY';
            $this->setAtom($staticId, ['enclosing' => false ]);
        } elseif (in_array($this->atoms[$right]['atom'], array('Functioncall', 'Methodcall'))) {
            $staticId = $this->addAtom('Methodcall');
            $links = 'METHOD';
        } else {
            die("Unprocessed atom in object call (right) : ".$this->atoms[$right]['atom']."\n");
        }

        $this->addLink($staticId, $left, 'OBJECT');
        $this->addLink($staticId, $right, $links);

        $x = ['code'     => $this->tokens[$current][1],
              'fullcode' => $this->atoms[$left]['fullcode'] . '->' .
                            $this->atoms[$right]['fullcode'],
              'line'     => $this->tokens[$current][2],
              'token'    => $this->getToken($this->tokens[$current][0])];

        $this->setAtom($staticId, $x);
        $this->pushExpression($staticId);
        
        return $staticId;
    }
    

    private function processAssignation() {
        $finals = $this->getPrecedence($this->tokens[$this->id][0]);
        $finals = array_merge($finals, [T_EQUAL, T_PLUS_EQUAL, T_AND_EQUAL, T_CONCAT_EQUAL, T_DIV_EQUAL, T_MINUS_EQUAL, T_MOD_EQUAL, T_MUL_EQUAL, T_OR_EQUAL, T_POW_EQUAL, T_SL_EQUAL, T_SR_EQUAL, T_XOR_EQUAL]);
        $this->processOperator('Assignation', $finals);
    }

    private function processCoalesce() {
        $this->processOperator('Coalesce', $this->getPrecedence($this->tokens[$this->id][0]));
    }

    private function processEllipsis() {
        $current = $this->id;
    
        // Simply skipping the ...
        $finals = $this->getPrecedence(T_ELLIPSIS);
        while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
            $id = $this->processNext();
        };
    
        $operandId = $this->popExpression();
        $x = ['fullcode'  => '...'.$this->atoms[$operandId]['fullcode'],
              'variadic'  => true];
        $this->setAtom($operandId, $x);
    
        $this->pushExpression($operandId);
        
        return $operandId;
    }
    
    private function processAnd() {
        if (!$this->hasExpression()) {
            $current = $this->id;

            // Simply skipping the &
            $finals = $this->getPrecedence(T_REFERENCE);
            while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
                $id = $this->processNext();
            };

            $operandId = $this->popExpression();
            $x = ['fullcode'  => '&'.$this->atoms[$operandId]['fullcode'],
                  'reference' => true];
            $this->setAtom($operandId, $x);

            $this->pushExpression($operandId);
        
            return $operandId;
        } else {
            return $this->processOperator('Logical', $this->getPrecedence($this->tokens[$this->id][0]));
        }
    }

    private function processLogical() {
        $this->processOperator('Logical', $this->getPrecedence($this->tokens[$this->id][0]));
    }

    private function processMultiplication() {
        $this->processOperator('Multiplication', $this->getPrecedence($this->tokens[$this->id][0]));
    }

    private function processPower() {
        $this->processOperator('Power', $this->getPrecedence($this->tokens[$this->id][0]));
    }

    private function processComparison() {
        $this->processOperator('Comparison', $this->getPrecedence($this->tokens[$this->id][0]));
    }

    private function processDot() {
        $current = $this->id;
        $concatenationId = $this->addAtom('Concatenation');
        $fullcode = [];
        $rank = 0;

        $containsId = $this->popExpression();
        $this->addLink($concatenationId, $containsId, 'CONCAT');
        $this->setAtom($containsId, ['rank' => $rank++]);
        $fullcode[] = $this->atoms[$containsId]['fullcode'];

        $finals = $this->getPrecedence($this->tokens[$this->id][0]);
        while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
            $this->processNext();
            
            if ($this->tokens[$this->id + 1][0] === T_DOT) {
                $containsId = $this->popExpression();
                $this->addLink($concatenationId, $containsId, 'CONCAT');
                $fullcode[] = $this->atoms[$containsId]['fullcode'];
                $this->setAtom($containsId, ['rank' => $rank++]);

                ++$this->id;
            }
        }

        $containsId = $this->popExpression();
        $this->addLink($concatenationId, $containsId, 'CONCAT');
        $this->setAtom($containsId, ['rank' => $rank++]);
        $fullcode[] = $this->atoms[$containsId]['fullcode'];
        
        $x = ['code'     => $this->tokens[$current][1],
              'fullcode' => join(' . ', $fullcode),
              'line'     => $this->tokens[$current][2],
              'token'    => $this->getToken($this->tokens[$current][0]),
              'count'    => $rank];
        $this->setAtom($concatenationId, $x);
        $this->pushExpression($concatenationId);
        
        return $concatenationId;
    }

    private function processInstanceof() {
        $current = $this->id;
        $instanceId = $this->addAtom('Instanceof');

        $left = $this->popExpression();
        $this->addLink($instanceId, $left, 'VARIABLE');
        
        $finals = array_merge([],  $this->getPrecedence($this->tokens[$this->id][0]));
        do {
            $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], $finals));

        $right = $this->popExpression();
        
        $this->addLink($instanceId, $right, 'CLASS');
        $this->setAtom($right, ['fullnspath' => $this->getFullnspath($right, 'class')]);
        $this->addCall('class', $this->atoms[$right]['fullnspath'], $right);

        $x = ['code'     => $this->tokens[$current][1],
              'fullcode' => $this->atoms[$left]['fullcode'] . ' ' .
                            $this->tokens[$current][1] . ' ' .
                            $this->atoms[$right]['fullcode'],
              'line'     => $this->tokens[$current][2],
              'token'    => $this->getToken($this->tokens[$current][0])];
        $this->setAtom($instanceId, $x);
        $this->pushExpression($instanceId);
        
        return $instanceId;
    }

    private function processKeyvalue() {
        return $this->processOperator('Keyvalue', $this->getPrecedence($this->tokens[$this->id][0]), ['KEY', 'VALUE']);
    }

    private function processBitshift() {
        $this->processOperator('Bitshift', $this->getPrecedence($this->tokens[$this->id][0]));
    }

    private function processEcho() {
        $current = $this->id;
        --$this->id;
        $nameId = $this->processNextAsIdentifier();

        $argumentsId = $this->processArguments([T_SEMICOLON, T_CLOSE_TAG, T_END]);
        // processArguments goes too far, up to ;
        --$this->id;

        $functioncallId = $this->addAtom('Functioncall');
        $this->setAtom($functioncallId, ['code'       => $this->tokens[$current][1],
                                         'fullcode'   => $this->tokens[$current][1] . ' ' .
                                                         $this->atoms[$argumentsId]['fullcode'],
                                         'line'       => $this->tokens[$current][2],
                                         'token'      => $this->getToken($this->tokens[$current][0]),
                                         'fullnspath' => $this->getFullnspath($nameId)
                                        ]);
        $this->addLink($functioncallId, $argumentsId, 'ARGUMENTS');
        $this->addLink($functioncallId, $nameId, 'NAME');

        $this->pushExpression($functioncallId);

        return $functioncallId;
    }

    private function processHalt() {
        $haltId = $this->addAtom('Halt');
        $this->setAtom($haltId, ['code'     => $this->tokens[$this->id][1],
                                 'fullcode' => $this->tokens[$this->id][1],
                                 'line'     => $this->tokens[$this->id][2],
                                 'token'    => $this->getToken($this->tokens[$this->id][0]) ]);

        ++$this->id; // skip halt
        ++$this->id; // skip (
        // Skipping all arguments. This is not a function!

        $this->pushExpression($haltId);
        ++$this->id; // skip (
        $this->processSemicolon();

        return $haltId;
    }
    
    private function processPrint() {
        if (in_array($this->tokens[$this->id][0], array(T_INCLUDE, T_INCLUDE_ONCE, T_REQUIRE, T_REQUIRE_ONCE))) {
            $nameId = $this->addAtom('Include');
        } else {
            $nameId = $this->addAtom('Identifier');
        }
        $this->setAtom($nameId, ['code'     => $this->tokens[$this->id][1],
                                 'fullcode' => $this->tokens[$this->id][1],
                                 'line'     => $this->tokens[$this->id][2],
                                 'token'    => $this->getToken($this->tokens[$this->id][0]) ]);

        $argumentsId = $this->addAtom('Arguments');

        $fullcode = array();
        $finals = $this->getPrecedence($this->tokens[$this->id][0]);
        while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
            $this->processNext();
        };

        $indexId = $this->popExpression();
        $this->addLink($argumentsId, $indexId, 'ARGUMENT');
        $fullcode[] = $this->atoms[$indexId]['fullcode'];

        $this->setAtom($argumentsId, ['code'     => $this->tokens[$this->id][1],
                                      'fullcode' => join(', ', $fullcode),
                                      'line'     => $this->tokens[$this->id][2],
                                      'token'    => $this->getToken($this->tokens[$this->id][0])]);

        $functioncallId = $this->addAtom('Functioncall');
        $this->setAtom($functioncallId, ['code'       => $this->atoms[$nameId]['code'],
                                         'fullcode'   => $this->atoms[$nameId]['code'].' '.
                                                         $this->atoms[$argumentsId]['fullcode'],
                                         'line'       => $this->atoms[$nameId]['line'],
                                         'token'      => $this->atoms[$nameId]['token'],
                                         'fullnspath' => '\\'.strtolower($this->atoms[$nameId]['code'])
                                        ]);
        $this->addLink($functioncallId, $argumentsId, 'ARGUMENTS');
        $this->addLink($functioncallId, $nameId, 'NAME');

        $this->pushExpression($functioncallId);
        
        return $functioncallId;
    }

    private function processEnd() {
        die("Attempt to process T_END token\n\n");
    }

    //////////////////////////////////////////////////////
    /// generic methods
    //////////////////////////////////////////////////////
    private function addAtom($atom) {
        $this->atomCount++;
        $this->atoms[$this->atomCount] = ['id'   => $this->atomCount,
                                          'atom' => $atom];
        return $this->atomCount;
    }

    private function addAtomVoid() {
        $id = $this->addAtom('Void');
        $this->setAtom($id, ['code'     => 'Void',
                             'fullcode' => self::FULLCODE_VOID,
                             'line'     => $this->tokens[$this->id][2],
                             'token'    => T_VOID,
                             'fullnspath' => '\\'
                             ]);
        
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
        
        if (!isset($this->links[$label]))         { $this->links[$label] = []; }
        if (!isset($this->links[$label][$o]))     { $this->links[$label][$o] = []; }
        if (!isset($this->links[$label][$o][$d])) { $this->links[$label][$o][$d] = []; }

        $this->links[$label][$o][$d][] = ['origin'      => $origin,
                                          'destination' => $destination];
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
        if (count($this->expressions) > 0) {
            print "Warning : expression is not empty in $filename\n";
            print_r($this->expressions);
            foreach($this->expressions as $atomId) {
                print_r($this->atoms[$atomId]);
            }
//            die();
        }
    
        // All node has one incoming or one outgoing link (outgoing or incoming).
        $O = $D = [];
        foreach($this->links as $label => $origins) {
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
            if ($id == 1) { continue; }
            if (!isset($D[$id])) {
                print "Forgotten atom $id in $this->filename : \n";
                print_r($atom);
                print "\n";
                ++$total;
            } elseif ($D[$id] > 1) {
                print "Too linked atom $id : \n";
                print_r($atom);
                print "\n";
                ++$total;
            }
            
            if (!isset($atom['line'])) {
                print "Missing line atom $id : \n";
                print_r($atom);
                print "\n";
                ++$total;
            }

            if (!isset($atom['code'])) {
                print "Missing code atom $id : \n";
                print_r($atom);
                print "\n";
                ++$total;
            }

            if (!isset($atom['token'])) {
                print "Missing token atom $id : \n";
                print_r($atom);
                print "\n";
                ++$total;
            }
        }
        if ($total > 0) {
            print $total." errors found\n";
//            die();
        }
        
    }

    private function processDefineAsConstants($argumentsId) {
        $fullnspath = $this->getFullnspath($this->argumentsId[0]);
        
        $this->addDefinition('const', $fullnspath, $argumentsId);
    }

    private function saveFiles() {
        static $extras  = [];
        
        // Saving atoms
        foreach($this->atoms as $atom) {
            $fileName = './nodes.g3.'.$atom['atom'].'.csv';
            if (isset($extras[$atom['atom']])) {
                $fp = fopen($fileName, 'a');
            } else {
                $fp = fopen($fileName, 'w+');
                $headers = ['id', 'atom', 'code', 'fullcode', 'line', 'token', 'rank'];

                $extras[$atom['atom']] = [];
                foreach(self::PROP_OPTIONS as $title => $atoms) {
                    if (in_array($atom['atom'], $atoms)) {
                        $headers[] = $title;
                        $extras[$atom['atom']][] = $title;
                    }
                }
                fputcsv($fp, $headers);
            }

            $extra = [];
            foreach($extras[$atom['atom']] as $e) {
                $extra[] = isset($atom[$e]) ? "\"".$this->escapeCsv($atom[$e])."\"" : "\"-1\"";
            }

            if (count($extras[$atom['atom']]) > 0) {
                $extra = ','.join(',', $extra);
            } else {
                $extra = '';
            }
            
            fwrite($fp, $atom['id'].','.
                        $atom['atom'].',"'.
                        $this->escapeCsv( $atom['code'] ).'","'.
                        $this->escapeCsv( $atom['fullcode']).'",'.
                        ($atom['line'] ?? 0).',"'.
                        $this->escapeCsv( $atom['token'] ?? '') .'","'.
                        ($atom['rank'] ?? -1).'"'.
                        $extra.
                        "\n");

            fclose($fp);
        }
        
        $this->atoms = array(1 => $this->atoms[1]);

        // Saving the links between atoms
        foreach($this->links as $label => $origins) {
            foreach($origins as $origin => $destinations) {
                foreach($destinations as $destination => $links) {
                    $csv = $label.'.'.$origin.'.'.$destination;
                    $fileName = './rels.g3.'.$csv.'.csv';
                    if (isset($extras[$csv])) {
                        $fp = fopen($fileName, 'a');
                    } else {
                        $fp = fopen($fileName, 'w+');
                        fputcsv($fp, ['start', 'end']);
                        $extras[$csv] = 1;
                    }
    
                    foreach($links as $link) {
                        fputcsv($fp, [$link['origin'], $link['destination']], ',', '"', '\\');
                    }
                    
                    fclose($fp);
                }
            }
        }
        $this->links = array();
        
    }

    private function saveDefinitions() {

        // Saving the function / class definitions
        foreach($this->calls as $type => $paths) {
            foreach($paths as $path) {
                foreach($path['calls'] as $origin => $origins) {
                    foreach($path['definitions'] as $destination => $destinations) {
                        $csv = 'DEFINITION.'.$destination.'.'.$origin;

                        $filePath = './rels.g3.'.$csv.'.csv';
                        if (file_exists($filePath)) {
                            $fp = fopen('./rels.g3.'.$csv.'.csv', 'a');
                        } else {
                            $fp = fopen('./rels.g3.'.$csv.'.csv', 'w+');
                            fputcsv($fp, ['start', 'end']);
                        }

                        foreach($origins as $o) {
                            foreach($destinations as $d) {
                                fputcsv($fp, [$d, $o], ',', '"', '\\');
                            }
                        }
                    }
                }
            }
        }
    }

    private function escapeCsv($string) {
        return str_replace(array('\\', '"'), array('\\\\', '\\"'), $string);
    }
    
    private function startSequence() {
        $this->sequence = $this->addAtom('Sequence');
        $this->setAtom($this->sequence, ['code'     => ';',
                                         'fullcode' => ' '.self::FULLCODE_SEQUENCE.' ',
                                         'line'     => $this->tokens[$this->id][2],
                                         'token'    => 'T_SEMICOLON',
                                         'bracket'  => false]);
        
        $this->sequences[]    = $this->sequence;
        $this->sequenceRank[] = 0;
        $this->sequenceCurrentRank = count($this->sequenceRank) - 1;
    }

    private function addToSequence($id) {
        $this->addLink($this->sequence, $id, 'ELEMENT');
        $this->setAtom($id, ['rank' => $this->sequenceRank[$this->sequenceCurrentRank]++]);
    }

    private function endSequence() {
        $this->setAtom($this->sequence, ['count' => $this->sequenceRank[$this->sequenceCurrentRank]]);

        $id = array_pop($this->sequences);
        array_pop($this->sequenceRank);
        $this->sequenceCurrentRank = count($this->sequenceRank) - 1;
        
        if (!empty($this->sequences)) {
            $this->sequence = $this->sequences[count($this->sequences) - 1];
        } else {
            $this->sequence = null;
        }
    }
    
    private function getToken($token) {
        if (is_string($token)) {
            return self::TOKENNAMES[$token];
        } else {
            return token_name($token);
        }
    }

    private function getPrecedence($token, $itself = false) {
        static $cache;
        
        if ($cache === null) {
            $cache = [];
            foreach(self::PRECEDENCE as $k1 => $p1) {
                $cache[$k1] = [];
                foreach(self::PRECEDENCE as $k2 => $p2) {
                    if ($p1 <= $p2 && ($itself === true || $k1 != $k2) ) {// && (!in_array($token, [T_COALESCE]) || $token != $k2)
                        $cache[$k1][] = $k2;
                    }
                }
            }
        }
        
        if (!isset($cache[$token])) {
            die("No precedence for $token\n");
        }
        
        return $cache[$token];
    }
    
    private function getFullnspath($nameId, $type = 'class') {
        // Handle static, self, parent and PHP natives function
        if (isset($this->atoms[$nameId]['absolute']) && ($this->atoms[$nameId]['absolute'] === true)) {
            return strtolower($this->atoms[$nameId]['fullcode']);
        } elseif (!in_array($this->atoms[$nameId]['atom'], ['Nsname', 'Identifier', 'String'])) {
            // No fullnamespace for non literal namespaces
            return '';
        } elseif (in_array($this->atoms[$nameId]['token'], ['T_ARRAY', 'T_EVAL', 'T_ISSET', 'T_EXIT', 'T_UNSET', 'T_ECHO', 'T_PRINT', 'T_LIST', 'T_EMPTY'])) {
            // For language structures, it is always in global space, like eval or list
            return '\\'.strtolower($this->atoms[$nameId]['code']);
        } elseif (strtolower(substr($this->atoms[$nameId]['fullcode'], 0, 9)) === 'namespace') {
            // namespace\A\B 
            return substr($this->namespace, 0, -1).strtolower(substr($this->atoms[$nameId]['fullcode'], 9));
        } elseif ($this->atoms[$nameId]['atom'] === 'Identifier') {
            if ($type === 'class' && isset($this->uses['class'][strtolower($this->atoms[$nameId]['code'])])) {
                return $this->uses['class'][strtolower($this->atoms[$nameId]['code'])];
            } elseif ($type === 'const' && isset($this->uses['const'][strtolower($this->atoms[$nameId]['code'])])) {
                return $this->uses['const'][strtolower($this->atoms[$nameId]['code'])];
            } elseif ($type === 'function' && isset($this->uses['function'][strtolower($this->atoms[$nameId]['code'])])) {
                return $this->uses['function'][strtolower($this->atoms[$nameId]['code'])];
            } else {
                return $this->namespace.strtolower($this->atoms[$nameId]['fullcode']);
            }
        } elseif ($this->atoms[$nameId]['atom'] === 'String') {
            $prefix =  ($this->atoms[$nameId]['noDelimiter'][0] === '\\' ? '' : '\\') .
                        strtolower($this->atoms[$nameId]['noDelimiter']);

            // define doesn't care about use...
            return $prefix;
        } else {
            $prefix = substr($this->atoms[$nameId]['fullcode'], 0, strpos($this->atoms[$nameId]['fullcode'], '\\'));

            if (isset($this->uses[$type][$prefix])) {
                return $this->uses[$type][$prefix].substr($this->atoms[$nameId]['fullcode'], strlen($prefix));
            } else {
                return $this->namespace.strtolower($this->atoms[$nameId]['fullcode']);
            }
        }
    }
    
    private function toggleContext($context) {
        $this->contexts[$context] = !$this->contexts[$context];
        return $this->contexts[$context];
    }

    private function isContext($context) {
        return $this->contexts[$context];
    }
    
    private function makeFullnspath($namespaceAsId) {
        return strtolower(isset($this->atoms[$namespaceAsId]['absolute']) && $this->atoms[$namespaceAsId]['absolute'] === true ? $this->atoms[$namespaceAsId]['fullcode'] : '\\'.$this->atoms[$namespaceAsId]['fullcode']) ;
    }
    
    private function setNamespace($namespaceId = 0) {
        if ($namespaceId == 0) {
            $this->namespace = '\\';
            $this->uses = array('function' => array(),
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

    private function addNamespaceUse($originId, $aliasId, $useType) {
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
        
        return $alias;
    }
    
    private function addCall($type, $fullnspath, $callId) {
        if (empty($fullnspath)) {
            return;
        }
        
        if (!isset($this->calls[$type][$fullnspath])) {
            $this->calls[$type][$fullnspath] = array('calls' => array(), 'definitions' => array());
        }
        $atom = $this->atoms[$callId]['atom'];
        if (!isset($this->calls[$type][$fullnspath]['calls'][$atom])) {
            $this->calls[$type][$fullnspath]['calls'][$atom] = array();
        }
        $this->calls[$type][$fullnspath]['calls'][$atom][] = $callId;
    }

    private function addDefinition($type, $fullnspath, $definitionId) {
        if (empty($fullnspath)) {
            return;
        }

        if (!isset($this->calls[$type][$fullnspath])) {
            $this->calls[$type][$fullnspath] = array('calls' => array(), 'definitions' => array());
        }
        $atom = $this->atoms[$definitionId]['atom'];
        if (!isset($this->calls[$type][$fullnspath]['definitions'][$atom])) {
            $this->calls[$type][$fullnspath]['definitions'][$atom] = array();
        }
       $this->calls[$type][$fullnspath]['definitions'][$atom][] = $definitionId;
    }
}

?>
