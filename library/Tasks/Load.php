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
const T_REFERENCE                    = 'r';
const T_END                          = 'The End';

class Load extends Tasks {
    private $php    = null;
    private static $client = null;
    private $config = null;
    
    const FULLCODE_BLOCK = ' {/**/} ';
    
    private $optionsTokens = array('Abstract'  => 0,
                                   'Final'     => 0,
                                   'Var'       => 0,
                                   'Public'    => 0,
                                   'Protected' => 0,
                                   'Private'   => 0,
                                   'Static'    => 0,
                                   );
     
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

                        T_AND                         => 12,	// &

                        T_CARET                       => 13,	// ^

                        T_PIPE                        => 14,	 // |
                        	
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
                        T_INCLUDE                     => 30,
                        T_INCLUDE_ONCE                => 30,
                        T_REQUIRE                     => 30,
                        T_REQUIRE_ONCE                => 30,
                        T_DOUBLE_ARROW                => 30,

                        T_RETURN                      => 31,
                        T_THROW                       => 31,
                        T_YIELD                       => 31,
                        T_YIELD_FROM                  => 31,
                        T_COLON                       => 31,
                        T_COMMA                       => 31,
                        T_SEMICOLON                   => 31,
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
    ];
    
    const PROP_ALTERNATIVE = ['Declare'];
    const PROP_REFERENCE = ['Variable'];

    const PROP_OPTIONS = ['alternative' => self::PROP_ALTERNATIVE,
                          'reference'   => self::PROP_REFERENCE,];

    
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
    
    private $expressions = [];
    private $atoms = array();
    private $atomCount = 0;

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
        } elseif ($dirName = $this->config->dirname) {
            $this->processDir($dirName);
        } elseif (($project = $this->config->project) != 'default') {
            $this->processProject($project);
        } else {
            die('No file to process. Aborting');
        }

        static::$client->finalize();
        display('Final memory : '.number_format(memory_get_usage()/ pow(2, 20)).'Mb');
        $this->datastore->addRow('hash', array('status' => 'Load'));
    }

    private function processProject($project) {
        $files = $this->datastore->getCol('files', 'file');
    
        $nbTokens = 0;
        $path = $this->config->projects_root.'/projects/'.$this->config->project.'/code';
        foreach($files as $file) {
            $nbTokens += $this->processFile($path.$file);
        }
        $this->saveFiles();

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
        $shell = 'find '.$dir.' \\( -name "*.'.(join('" -o -name "*.', $extPhp)).'" \\) \\( -not -path "*'.(join('" -and -not -path "', $ignoreDirs )).'" \\) ! -type l';
        $res = trim(shell_exec($shell));
        $files = explode("\n", $res);

        $this->atoms = array();
        $this->atomCount = 0;
        $this->links = array();

        $nbTokens = 0;
        foreach($files as $file) {
            $nbTokens += $this->processFile($file);
        }
        $this->saveFiles();

        return array('files' => count($files), 'tokens' => $nbTokens);
    }

    private function processFile($filename) {
        print "Process $filename\n";
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
        
        $n = count($this->tokens) - 1;
        $this->id = 0; // set to 0 so as to calculate line in the next call.
        $this->startSequence(); // At least, one sequence available
        $this->id = -1;
        do {
            $id = $this->processNext();
            print "$this->id / $n\n";
            
            if ($id > 0) {
                $this->addLink($this->sequence, $id, 'ELEMENT');
            }
        } while ($this->id < $n);
        
        $id = $this->sequence;
        $this->endSequence();

        $this->addLink($id1, $id, 'FILE');
        $this->setAtom($id, ['root' => true]);
        
        print count($this->atoms)." atoms\n";
        print count($this->links)." links\n";
        print "Final id : $this->id\n";
    }

    private function processNext() {
       ++$this->id;
       
       print $this->id.") ".$this->tokens[$this->id][1]."\n";
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

                            T_HALT_COMPILER            => 'processPrint',
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
                            T_END                      => 'processNone',
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
            print "Defaulting a : $this->id ";
            print_r($this->tokens[$this->id]);
//            print_r($this->atoms);
            die("Missing the method\n");
        }
        $method = $this->processing[ $this->tokens[$this->id][0] ];
        
        print "$method\n";
        
        return $this->$method();
    }

    // Dummy method
    private function processNone() {
        return null;// Just ignore
    }

    //////////////////////////////////////////////////////
    /// processing complex tokens
    //////////////////////////////////////////////////////
    private function processQuote() {
        $current = $this->id;
        
        if ($this->tokens[$current][0] === T_QUOTE) {
            $stringId = $this->addAtom('String');
            $finalToken = T_QUOTE;
            $openQuote = '"';
            $closeQuote = '"';
        } elseif ($this->tokens[$current][0] === T_BACKTICK) {
            $stringId = $this->addAtom('Shell');
            $finalToken = T_BACKTICK;
            $openQuote = '`';
            $closeQuote = '`';
        } elseif ($this->tokens[$current][0] === T_START_HEREDOC) {
            $stringId = $this->addAtom('Heredoc');
            $finalToken = T_END_HEREDOC;
            $openQuote = $this->tokens[$this->id][1];
            if ($this->tokens[$this->id][1][3] === "'") {
                $closeQuote = substr($this->tokens[$this->id][1], 4, -2);
            } else {
                $closeQuote = substr($this->tokens[$this->id][1], 3);
            }
        }
        
        while ($this->tokens[$this->id + 1][0] !== $finalToken) {
            if (in_array($this->tokens[$this->id + 1][0], [T_CURLY_OPEN, T_DOLLAR_OPEN_CURLY_BRACES])) {
                ++$this->id; // Skip {
                while (!in_array($this->tokens[$this->id + 1][0], [T_CLOSE_CURLY])) {
                    $this->processNext();
                } ;
                ++$this->id; // Skip }
            } elseif ($this->tokens[$this->id + 1][0] == T_VARIABLE) {
                $this->processNext();

                if ($this->tokens[$this->id + 1][0] == T_OBJECT_OPERATOR) {
                    ++$this->id;
                    
                    $objectId = $this->popExpression();

                    $propertyNameId = $this->processNextAsIdentifier();

                    $propertyId = $this->addAtom('Property');
                    $this->setAtom($propertyId, ['code'     => $this->tokens[$current][1], 
                                                 'fullcode' => $this->atoms[$objectId]['fullcode']. $this->tokens[$current][1] .
                                                               $this->atoms[$propertyNameId]['fullcode'],
                                                'line'      => $this->tokens[$current][2],
                                                'token'     => $this->getToken($this->tokens[$current][0]) ]);

                    $this->addLink($propertyId, $objectId, 'OBJECT');
                    $this->addLink($propertyId, $propertyNameId, 'PROPERTY');
                    
                    $this->pushExpression($propertyId);
                }
            } else {
                $this->processNext();
            }
            
            $partId = $this->popExpression();
            $fullcode[] = $this->atoms[$partId]['fullcode'];
            $this->addLink($stringId, $partId, 'CONCAT');
        }
        
        ++$this->id;

        $this->setAtom($stringId, ['code'     => $this->tokens[$current][1], 
                                   'fullcode' => $openQuote.join('', $fullcode).$closeQuote,
                                   'line'     => $this->tokens[$current][2],
                                   'token'    => $this->getToken($this->tokens[$current][0])]);

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

        $this->setAtom($nameId, ['code'     => $this->tokens[$current][1], 
                                 'fullcode' => '${'.$this->atoms[$nameId]['fullcode'].'}',
                                 'line'     => $this->tokens[$current][2],
                                 'token'    => $this->getToken($this->tokens[$current][0])]);
        
        $this->pushExpression($variableId);
        
        return $variableId;
    }
    
    private function processTry() {
        $current = $this->id;
        $tryId = $this->addAtom('Try');
        
        $blockId = $this->processFollowingBlock([T_CLOSE_CURLY]);
        $this->addLink($tryId, $blockId, 'BLOCK');
        
        $fullcodeCatch = array();
        while ($this->tokens[$this->id + 1][0] == T_CATCH) {
            ++$this->id; // Skip catch
            ++$this->id;
        
            $catchId = $this->addAtom('Catch');
            while ($this->tokens[$this->id + 1][0] !== T_VARIABLE) {
                $this->processNext();
            };
            
            $classId = $this->popExpression();
            $this->addLink($catchId, $classId, 'CLASS');

            // Process variable
            $this->processNext();
        
            $variableId = $this->popExpression();
            $this->addLink($catchId, $variableId, 'VARIABLE');

            // Skip )
            ++$this->id;

            // Skip }
            $blockCatchId = $this->processFollowingBlock([T_CLOSE_CURLY]);
            $this->addLink($catchId, $blockCatchId, 'BLOCK');

            $this->setAtom($catchId, ['code'     => $this->tokens[$current][1],
                                      'fullcode' => $this->tokens[$current][1].' ('.$this->atoms[$classId]['fullcode'].' '.
                                                     $this->atoms[$variableId]['fullcode'].']) '.static::FULLCODE_BLOCK.' ',
                                      'line'     => $this->tokens[$current][2],
                                      'token'    => $this->getToken($this->tokens[$current][0])]);

            $this->addLink($tryId, $catchId, 'CATCH');
            $fullcodeCatch[] = $this->atoms[$catchId]['fullcode'];
        }
        
        if ($this->tokens[$this->id + 1][0] === T_FINALLY) {
            $finallyId = $this->addAtom('Finally');

            ++$this->id;
            $finallyBlockId = $this->processFollowingBlock(false);
            $this->addLink($tryId, $finallyId, 'FINALLY');
            $this->addLink($finallyId, $finallyBlockId, 'BLOCK');
            

            $this->setAtom($finallyId, ['code'     => $this->tokens[$current][1],
                                        'fullcode' => $this->tokens[$current][1].' '.static::FULLCODE_BLOCK,
                                        'line'     => $this->tokens[$current][2],
                                        'token'    => $this->getToken($this->tokens[$current][0])]);
        }

        $this->setAtom($tryId, ['code'     => $this->tokens[$current][1],
                                'fullcode' => $this->tokens[$current][1].static::FULLCODE_BLOCK.' '.
                                               join(' ', $fullcodeCatch).''
                                               .( isset($finallyId) ? $this->atoms[$finallyId]['fullcode'] : ''),
                                'line'     => $this->tokens[$current][2],
                                'token'    => $this->getToken($this->tokens[$current][0])]);

        $this->pushExpression($tryId);
        $this->processSemicolon();
        
        return $tryId;
    }

    private function processFunction() {
        $current = $this->id;
        $functionId = $this->addAtom('Function');

        $options = array('Abstract', 'Static', 'Final', 'Private', 'Protected', 'Public');
        foreach($options as $option) {
            if ($this->optionsTokens[$option] > 0) {
                $this->addLink($functionId, $this->optionsTokens[$option], strtoupper($option));
                $this->optionsTokens[$option] = 0;
            }
        }

        if ($this->tokens[$this->id + 1][0] === T_AND) {
            ++$this->id;
            $this->setAtom($functionId, ['reference' => true]);
        } else {
            $this->setAtom($functionId, ['reference' => false]);
        }

        if ($this->tokens[$this->id + 1][0] === T_OPEN_PARENTHESIS) {
            $nameId = $this->addAtomVoid();
        } else {
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
            $argumentsId = $this->processArguments();
            $this->addLink($functionId, $argumentsId, 'USE');
        
        }
        
        // Process return type
        if ($this->tokens[$this->id + 1][0] === T_COLON) {
            ++$this->id;
            $returnTypeId = $this->processOneNsname();

            $this->addLink($functionId, $returnTypeId, 'RETURNTYPE');
        }

        print "Process block in Function\n";
        // Process block 
        if ($this->tokens[$this->id + 1][0] === T_SEMICOLON) {
            $voidId = $this->addAtomVoid();
            $this->addLink($functionId, $voidId, 'BLOCK');
            ++$this->id;
        } else {
            ++$this->id;
            $blockId = $this->processFollowingBlock([T_CLOSE_CURLY]);
            $this->popExpression();
            $this->addLink($functionId, $blockId, 'BLOCK');
        }
        
        $this->setAtom($functionId, ['code'     => $this->atoms[$nameId]['fullcode'], 
                                     'fullcode' => 'function '.($this->atoms[$argumentsId]['reference'] ? '&' : '').$this->atoms[$nameId]['fullcode'].
                                                   ' ('.$this->atoms[$argumentsId]['fullcode'].') '.
                                                   (isset($blockId) ? static::FULLCODE_BLOCK : ';'),
                                     'line'     => $this->tokens[$current][2],
                                     'token'    => $this->getToken($this->tokens[$current][0])]);
        
        $this->pushExpression($functionId);
        
        if ($this->atoms[$nameId]['code'] !== 'Void') {
            $this->processSemicolon();
        }
        
        return $functionId;
    }
    
    private function processOneNsname() {
        $fullcode = [];
        if ($this->tokens[$this->id + 1][0] !== T_NS_SEPARATOR) {
            $subnameId = $this->processNextAsIdentifier();
            $this->pushExpression($subnameId);
            
            $hasPrevious = true;
        }
        $current = $this->id;
        
        if ($this->tokens[$this->id + 1][0] === T_NS_SEPARATOR) {
            $extendsId = $this->addAtom('Nsname');
            
            // Previous one
            if (isset($hasPrevious)) {
                $subnameId = $this->popExpression();
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
    
                $fullcode[] = $this->atoms[$subnameId]['code'];
                $this->addLink($extendsId, $subnameId, 'SUBNAME');
            }
            
            
            $this->setAtom($extendsId, ['code'     => '/', 
                                        'fullcode' => join('\\', $fullcode),
                                        'line'     => $this->tokens[$current][2],
                                        'token'    => $this->getToken($this->tokens[$current][0])]);
        } else {
            $extendsId = $this->popExpression();
        }
        
        return $extendsId;
    }

    private function processTrait() {
        $current = $this->id;
        $traitId = $this->addAtom('Trait');
        
        $nameId = $this->processNextAsIdentifier();
        $this->addLink($traitId, $nameId, 'NAME');

        // Process block 
        ++$this->id;
        $blockId = $this->processBlock(false);
        $this->popExpression();
        $this->addLink($traitId, $blockId, 'BLOCK');
        
        $this->setAtom($traitId, ['code'     => $this->tokens[$current][1], 
                                  'fullcode' => $this->tokens[$current][1].' '.$this->atoms[$nameId]['fullcode'].' '.
                                                static::FULLCODE_BLOCK,
                                  'line'     => $this->tokens[$current][2],
                                  'token'    => $this->getToken($this->tokens[$current][0])]);
        
        $this->pushExpression($traitId);
        $this->processSemicolon();

        return $traitId;
    }

    private function processInterface() {
        $current = $this->id;
        $interfaceId = $this->addAtom('Interface');
        
        $nameId = $this->processNextAsIdentifier();
        $this->addLink($interfaceId, $nameId, 'NAME');

        // Process extends
        if ($this->tokens[$this->id + 1][0] == T_EXTENDS) {
            do {
                ++$this->id; // Skip extends
                $extendsId = $this->processOneNsname();
                $this->addLink($interfaceId, $extendsId, 'EXTENDS');
            } while ($this->tokens[$this->id + 1][0] === T_COMMA);
        }

        // Process implements
        if ($this->tokens[$this->id + 1][0] == T_IMPLEMENTS) {
            do {
                ++$this->id; // Skip extends
                $extendsId = $this->processOneNsname();
                $this->addLink($interfaceId, $extendsId, 'IMPLEMENTS');
            } while ($this->tokens[$this->id + 1][0] === T_COMMA);
        }

        // Process block 
        ++$this->id;
        $blockId = $this->processBlock(false);
        $this->popExpression();
        $this->addLink($interfaceId, $blockId, 'BLOCK');
        
        $this->setAtom($interfaceId, ['code'     => $this->tokens[$current][1], 
                                      'fullcode' => $this->tokens[$current][1].' '.$this->atoms[$nameId]['fullcode'].' '.
                                                    static::FULLCODE_BLOCK,
                                      'line'     => $this->tokens[$current][2],
                                      'token'    => $this->getToken($this->tokens[$current][0])]);
        
        $this->pushExpression($interfaceId);
        $this->processSemicolon();

        return $interfaceId;
    }
    
    private function processClass() {
        $current = $this->id;
        $classId = $this->addAtom('Class');
        
        if ($this->optionsTokens['Abstract'] > 0) {
            $this->addLink($classId, $this->optionsTokens['Abstract'], 'ABSTRACT');
            $this->optionsTokens['Abstract'] = 0;
        }

        if ($this->optionsTokens['Final'] > 0) {
            $this->addLink($classId, $this->optionsTokens['Final'], 'FINAL');
            $this->optionsTokens['Final'] = 0;
        }
        
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
            ++$this->id; // Skip extends
            
            $extendsId = $this->processOneNsname();
            
            $this->addLink($classId, $extendsId, 'EXTENDS');
        }

        // Process implements
        if ($this->tokens[$this->id + 1][0] == T_IMPLEMENTS) {
            
            do {
                ++$this->id; // Skip extends
                $extendsId = $this->processOneNsname();
                $this->addLink($classId, $extendsId, 'IMPLEMENTS');
            } while ($this->tokens[$this->id + 1][0] === T_COMMA);
        }
        
        // Process block 
        ++$this->id;
        $blockId = $this->processBlock(false);
        $this->popExpression();
        $this->addLink($classId, $blockId, 'BLOCK');
        
        $this->setAtom($classId, ['code'     => $this->tokens[$current][1], 
                                  'fullcode' => $this->tokens[$current][1].' '.$this->atoms[$nameId]['fullcode'].' '.
                                                static::FULLCODE_BLOCK,
                                  'line'     => $this->tokens[$current][2],
                                  'token'    => $this->getToken($this->tokens[$current][0])]);
        
        $this->pushExpression($classId);
        
        // Case of anonymous classes
        if ($this->tokens[$current - 1][0] !== T_NEW) {
            $this->processSemicolon();
        }

        return $classId;
    }

    private function processOpenTag() {
        $id = $this->addAtom('Php');
        $current = $this->id;

        $this->startSequence();
        while (!in_array($this->tokens[$this->id + 1][0], [T_CLOSE_TAG, T_END])) {
            $this->processNext();
            
            // Keep a closing tag as a part of the sequence, unless it is the last
            if ($this->tokens[$this->id + 1][0] === T_CLOSE_TAG &&
                $this->tokens[$this->id + 2][0] === T_INLINE_HTML &&
                in_array($this->tokens[$this->id + 3][0], [T_OPEN_TAG, T_OPEN_TAG_WITH_ECHO])) {
                    ++$this->id;
                    $this->processClosingTag();
            } elseif ($this->tokens[$this->id + 1][0] === T_CLOSE_TAG &&
                      in_array($this->tokens[$this->id + 2][0], [T_OPEN_TAG, T_OPEN_TAG_WITH_ECHO])) {
                    ++$this->id;
                    $this->processClosingTag();
            } 
        };

        if ($this->tokens[$this->id -1][0] == T_CLOSE_TAG) {
            $closing = '?>';
        } else {
            $closing = '';
        }

        $this->addLink($id, $this->sequence, 'CODE');
        $this->endSequence();
        
        $this->setAtom($id, ['code'     => $this->tokens[$current][1], 
                             'fullcode' => '<?php /**/ '.$closing,
                             'line'     => $this->tokens[$current][2],
                             'token'    => $this->getToken($this->tokens[$current][0])]);
        ++$this->id;
        return $id;
    }

    private function processSemicolon() {
        $this->addLink($this->sequence, $id = $this->popExpression(), 'ELEMENT');
        var_dump($id);
    }

    private function processClosingTag() {
        $current = $this->id;
        if ($this->tokens[$this->id + 1][0] === T_END) {
            $this->processSemicolon();
        } elseif ($this->tokens[$this->id + 1][0] === T_INLINE_HTML &&
                  $this->tokens[$this->id + 2][0] === T_END) {
            $this->processInlineHtml();
            ++$this->id;
            return 0;
        } elseif ($this->tokens[$this->id + 1][0] === T_INLINE_HTML &&
                  $this->tokens[$this->id + 2][0] === T_OPEN_TAG) {
            if (!in_array($this->tokens[$this->id - 1][0], [T_SEMICOLON, T_COLON])) {
                $this->processSemicolon();
            }
            ++$this->id;
            $this->processInlineHtml();
            ++$this->id;
        } elseif ($this->tokens[$this->id + 1][0] === T_INLINE_HTML &&
                  $this->tokens[$this->id + 2][0] === T_OPEN_TAG_WITH_ECHO) {
            ++$this->id;
            $this->processInlineHtml();

            // Processing ECHO
            $echoId = $this->processNextAsIdentifier();

            $argumentsId = $this->processArguments([T_SEMICOLON, T_CLOSE_TAG]);
            // processArguments goes too far, up to ;
            --$this->id;

            $functioncallId = $this->addAtom('Functioncall');
            $this->setAtom($functioncallId, ['code'     => $this->atoms[$echoId]['code'], 
                                             'fullcode' => 'echo ' . $this->atoms[$argumentsId]['fullcode'],
                                             'line'     => $this->tokens[$current][2],
                                             'token'    => $this->getToken($this->tokens[$current][0])]);
            $this->addLink($functioncallId, $argumentsId, 'ARGUMENTS');
            $this->addLink($functioncallId, $echoId, 'NAME');
            $this->addLink($this->sequence, $functioncallId, 'ELEMENT');
        } elseif ($this->tokens[$this->id + 1][0] === T_OPEN_TAG) {
            $this->processSemicolon();
            ++$this->id;
        } else {
            ++$this->id;
            $this->processInlineHtml();
        }

        return 0;
    }

    private function processNsnameAbsolute() {
        $this->pushExpression($this->addAtomVoid());
        
        return $this->processNsname();
    }

    private function processNsname() {
        $current = $this->id;

        $nsnameId = $this->addAtom('Nsname');
        $fullcode = [];

        $left = $this->popExpression();
        if ($this->atoms[$left]['atom'] !== 'Void') {
            $this->addLink($nsnameId, $left, 'SUBNAME');
            $fullcode[] = $this->atoms[$left]['code'];
        } else {
            $fullcode[] = '';
        }
        
        while ($this->tokens[$this->id][0] === T_NS_SEPARATOR) {
            $subnameId = $this->processNextAsIdentifier();

            $this->addLink($nsnameId, $subnameId, 'SUBNAME');
            $fullcode[] = $this->atoms[$subnameId]['code'];

            // Go to next
            ++$this->id; // skip \
        }  ;
        // Back up a whole cycle
        $this->id--;
        $this->id--;

        $x = ['code'     => $this->tokens[$current][1], 
              'fullcode' => join('\\', $fullcode),
              'line'     => $this->tokens[$current][2],
              'token'    => $this->getToken($this->tokens[$current][0])];
        $this->setAtom($nsnameId, $x);

        $this->pushExpression($nsnameId);

        return $this->processFCOA($nsnameId);
    }
    
    private function processTypehint() {
        if (in_array($this->tokens[$this->id + 1][0], [T_ARRAY, T_CALLABLE, T_STATIC])) {
            ++$this->id;
            $this->processSingle('Identifier');
            
            return $this->popExpression();
        } elseif (in_array($this->tokens[$this->id + 1][0], [T_STRING])) {
            $current = $this->id;
            $fullcode = array();
            
            ++$this->id; // Skip \
            $this->processSingle('Identifier');
            
            if ($this->tokens[$this->id + 1][0] === T_VARIABLE) {
                return $this->popExpression();
            }
            
            $nsnameId = $this->addAtom('Nsname');
            $subnameId = $this->popExpression();
            $this->addLink($nsnameId, $subnameId, 'SUBNAME');
            $fullcode[] = $this->atoms[$subnameId]['code'];

            while (!in_array($this->tokens[$this->id + 1][0], [T_VARIABLE, T_AND])) {
                ++$this->id; // Skip \

                ++$this->id;
                $this->processSingle('Identifier');
                $subnameId = $this->popExpression();
                $this->addLink($nsnameId, $subnameId, 'SUBNAME');
                $fullcode[] = $this->atoms[$subnameId]['code'];
            }
            
            $this->setAtom($nsnameId, ['code' => '\\',
                                       'fullcode' => join('\\', $fullcode),
                                       'line'     => $this->tokens[$current][2],
                                       'token'    => $this->getToken($this->tokens[$current][0])]);
            return $nsnameId;
        } elseif (in_array($this->tokens[$this->id + 1][0], [T_NS_SEPARATOR])) {
            $current = $this->id;
            $nsnameId = $this->addAtom('Nsname');
            $fullcode = array('');

            do {
                ++$this->id; // Skip \

                ++$this->id;
                $this->processSingle('Identifier');
                $subnameId = $this->popExpression();
                $this->addLink($nsnameId, $subnameId, 'SUBNAME');
                $fullcode[] = $this->atoms[$subnameId]['code'];
            } while (!in_array($this->tokens[$this->id + 1][0], [T_VARIABLE, T_AND]));
            
            $this->setAtom($nsnameId, ['code'     => '\\',
                                       'fullcode' => join('\\', $fullcode),
                                       'line'     => $this->tokens[$current][2],
                                       'token'    => $this->getToken($this->tokens[$current][0])]);
            return $nsnameId;
        }
        
        return 0;
    }

    private function processArguments($finals = [T_CLOSE_PARENTHESIS], $typehint = false) {
        $argumentsId = $this->addAtom('Arguments');

        $fullcode = array();
        if ($this->tokens[$this->id + 1][0] === T_CLOSE_PARENTHESIS) {
            $voidId = $this->addAtomVoid();
            $this->addLink($argumentsId, $voidId, 'ARGUMENT');

            $this->setAtom($argumentsId, ['code'     => $this->tokens[$this->id][1], 
                                          'fullcode' => 'Void',
                                          'line'     => $this->tokens[$this->id][2],
                                          'token'    => $this->getToken($this->tokens[$this->id][0])]);

            ++$this->id;
            
        } else {
            // In case we start with a ,
            while ($this->tokens[$this->id + 1][0] === T_COMMA) {
                $voidId = $this->addAtomVoid();
                $this->addLink($argumentsId, $voidId, 'ARGUMENT');
                
                $fullcode[] = '';
                ++$this->id;
            }
            
            while (!in_array($this->tokens[$this->id + 1][0], $finals )) {
               if ($typehint === true && $typehintId = $this->processTypehint()) {
                   $this->addLink($argumentsId, $typehintId, 'TYPEHINT');
               }

                $this->processNext();
               
                while ($this->tokens[$this->id + 1][0] === T_COMMA) {
                    $indexId = $this->popExpression();
                    
                    $this->addLink($argumentsId, $indexId, 'ARGUMENT');
                    $fullcode[] = $this->atoms[$indexId]['fullcode'];
    
                    ++$this->id; // Skipping the comma ,
                }
            };
            $indexId = $this->popExpression();
            $this->addLink($argumentsId, $indexId, 'ARGUMENT');
            $fullcode[] = $this->atoms[$indexId]['fullcode'];

            // Skip the ) 
            ++$this->id;

            $this->setAtom($argumentsId, ['code'     => $this->tokens[$this->id][1], 
                                          'fullcode' => join(', ', $fullcode),
                                          'line'     => $this->tokens[$this->id][2],
                                          'token'    => $this->getToken($this->tokens[$this->id][0])]);
        }
        return $argumentsId;
    }
    
    private function processNextAsIdentifier() {
        ++$this->id;
        $id = $this->addAtom('Identifier');
        $this->setAtom($id, ['code'     => $this->tokens[$this->id][1], 
                             'fullcode' => $this->tokens[$this->id][1],
                             'line'     => $this->tokens[$this->id][2],
                             'token'    => $this->getToken($this->tokens[$this->id][0])]);
        
        return $id;
    }

    private function processConst() {
        $constId = $this->addAtom('Const');
        $current = $this->id;

        $nameId = $this->processNextAsIdentifier();
        $this->pushExpression($nameId);
        while (!in_array($this->tokens[$this->id + 1][0], [T_SEMICOLON])) {
            $this->processNext();
            
            if ($this->tokens[$this->id + 1][0] === T_COMMA) {
                $defId = $this->popExpression();
                $this->addLink($constId, $defId, 'CONST');
                
                $fullcode[] = $this->atoms[$defId]['fullcode'];
                ++$this->id;

                $nameId = $this->processNextAsIdentifier();
                $this->pushExpression($nameId);
            }
        } ;

        $defId = $this->popExpression();
        $this->addLink($constId, $defId, 'CONST');
        $fullcode[] = $this->atoms[$defId]['fullcode'];

        $this->setAtom($constId, ['code'     => $this->tokens[$current][1], 
                                  'fullcode' => $this->tokens[$current][1].' '.join(', ', $fullcode),
                                  'line'     => $this->tokens[$current][2],
                                  'token'    => $this->getToken($this->tokens[$current][0])]);

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
            $this->addLink($pppId, $id, 'VAR');
            $this->optionsTokens['Var'] = 0;
            return $pppId;
        } 
        
        return $id;
    }

    private function processPublic() {
        $id = $this->processOptions('Public');

        if ($this->tokens[$this->id + 1][0] === T_VARIABLE) {
            $pppId = $this->processSGVariable('Ppp');
            $this->addLink($pppId, $id, 'PUBLIC');
            $this->optionsTokens['Public'] = 0;
            return $pppId;
        } 
        
        return $id;
    }

    private function processProtected() {
        $id = $this->processOptions('Protected');

        if ($this->tokens[$this->id + 1][0] === T_VARIABLE) {
            $pppId = $this->processSGVariable('Ppp');
            $this->addLink($pppId, $id, 'PROTECTED');
            $this->optionsTokens['Protected'] = 0;
            return $pppId;
        } 

        return $id;
    }

    private function processPrivate() {
        $id = $this->processOptions('Private');

        if ($this->tokens[$this->id + 1][0] === T_VARIABLE) {
            $pppId = $this->processSGVariable('Ppp');
            $this->addLink($pppId, $id, 'PRIVATE');
            $this->optionsTokens['Private'] = 0;
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
        $this->setAtom($functioncallId, ['code'     => $this->atoms[$nameId]['code'], 
                                         'fullcode' => $this->atoms[$nameId]['fullcode'].'('.
                                                       $this->atoms[$argumentsId]['fullcode'].')',
                                         'line'     => $this->tokens[$current][2],
                                         'token'    => $this->getToken($this->tokens[$current][0])
                                        ]);
        $this->addLink($functioncallId, $argumentsId, 'ARGUMENTS');
        $this->addLink($functioncallId, $nameId, 'NAME');

        $this->pushExpression($functioncallId);

        return $this->processFCOA($functioncallId);
    }
    
    private function processString() {
        $id = $this->addAtom('Identifier');
        $this->setAtom($id, ['code'     => $this->tokens[$this->id][1], 
                             'fullcode' => $this->tokens[$this->id][1],
                             'line'     => $this->tokens[$this->id][2],
                             'token'    => $this->getToken($this->tokens[$this->id][0]) ]);
        
        if ($this->tokens[$this->id + 1][0] === T_NS_SEPARATOR) {
            ++$this->id;
            $this->pushExpression($id);
            $this->processNsname();
            ++$this->id;
            $id = $this->popExpression();
        } elseif ($this->tokens[$this->id + 1][0] === T_COLON &&
                  !in_array($this->tokens[$this->id - 1][0], array(T_DOUBLE_COLON, T_OBJECT_OPERATOR))) {
            $labelId = $this->addAtom('Label');
            $this->addLink($labelId, $id, 'LABEL');
            $this->setAtom($labelId, ['code'     => ':', 
                                      'fullcode' => $this->atoms[$id]['fullcode'].' :',
                                      'line'     => $this->tokens[$this->id][2],
                                      'token'    => $this->getToken($this->tokens[$current][0])]);
                                      
            $this->pushExpression($labelId);
            return $labelId;
        }
        $this->pushExpression($id);

        // For functions and constants 
        return $this->processFCOA($id, false);
    }

    private function processPlusplus() {
        if ($this->hasExpression()) {
            $previousId = $this->popExpression();
            // postplusplus
            $plusplusId = $this->addAtom('PostPlusPlus');
            
            $this->addLink($plusplusId, $previousId, 'PREPLUSPLUS');

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
            $this->processSingle('Static');
        } elseif ($this->tokens[$this->id + 1][0] === T_OPEN_PARENTHESIS) {
            $nameId = $this->addAtom('Identifier');
            $this->setAtom($nameId, ['code'     => $this->tokens[$this->id][1],
                                     'fullcode' => $this->tokens[$this->id][1],
                                     'line'     => $this->tokens[$this->id][2],
                                     'token'    => $this->getToken($this->tokens[$this->id][0])]
                                     );
            $this->pushExpression($nameId);

            $this->processFunctioncall();
        } elseif ($this->tokens[$this->id + 1][0] === T_VARIABLE) {
            return $this->processStaticVariable();
        } else {
            return $this->processOptions('Static');
        }
    }

    private function processSGVariable($atom) {
        $current = $this->id;
        $staticId = $this->addAtom($atom);
        
        $fullcode = array();
        while ($this->tokens[$this->id + 1][0] !== T_SEMICOLON) {
            $this->processNext();
            
            if ($this->tokens[$this->id + 1][0] === T_COMMA) {
                $elementId = $this->popExpression();
                $this->addLink($staticId, $elementId, strtoupper($atom));

                $fullcode[] = $this->atoms[$elementId]['fullcode'];
                ++$this->id;
            }
        } ;
       $elementId = $this->popExpression();
       $this->addLink($staticId, $elementId, strtoupper($atom));

       $fullcode[] = $this->atoms[$elementId]['fullcode'];

        $this->setAtom($staticId, ['code'     => $this->tokens[$current][1], 
                                   'fullcode' => $this->tokens[$current][1] .' ' . join(', ', $fullcode),
                                   'line'     => $this->tokens[$current][2],
                                   'token'    => $this->getToken($this->tokens[$current][0])]);
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
        $id = $this->addAtom('Functioncall');

        $variableId = $this->addAtom('Identifier');
        $this->addLink($id, $variableId, 'VARIABLE');
        $this->setAtom($variableId, ['code' => '[',
                                     'fullcode' => '[ /**/ ]',
                                     'line'     => $this->tokens[$this->id][2],
                                     'token'    => $this->getToken($this->tokens[$this->id][0])]);

        // No need to skip opening bracket
        $argumentId = $this->processArguments([T_CLOSE_BRACKET]);
        $this->addLink($id, $argumentId, 'ARGUMENTS');

        $this->setAtom($id, ['code'     => $this->tokens[$this->id][1], 
                             'fullcode' => '[' . $this->atoms[$argumentId]['fullcode'] . ']' ,
                             'line'     => $this->tokens[$this->id][2],
                             'token'    => $this->getToken($this->tokens[$this->id][0])]);
        $this->pushExpression($id);
        
        return $this->processFCOA($id);
    }
    
    private function processBracket() {
        $id = $this->addAtom('Array');

        $variableId = $this->popExpression();
        $this->addLink($id, $variableId, 'VARIABLE');

        // Skip opening bracket
        $opening = $this->tokens[$this->id + 1][0];
        $opening === '}' ? $closing = '{' : $closing = ']';
         
        ++$this->id; 
        do {
            $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], [T_CLOSE_BRACKET, T_CLOSE_CURLY])) ;

        // Skip closing bracket
        ++$this->id; 

        $indexId = $this->popExpression();
        $this->addLink($id, $indexId, 'INDEX');

        $this->setAtom($id, ['code'     => $this->tokens[$this->id][1], 
                             'fullcode' => $this->atoms[$variableId]['fullcode'] . $opening .
                                           $this->atoms[$indexId]['fullcode']    . $closing ,
                             'line'     => $this->tokens[$this->id][2],
                             'token'    => $this->getToken($this->tokens[$this->id][0])]);
        $this->pushExpression($id);
        
        return $this->processFCOA($id);
    }
    
    private function processBlock($standalone = true) {
        $this->startSequence();
        
        // Case for {}
        if ($this->tokens[$this->id + 1][0] === T_CLOSE_CURLY) {
            $voidId = $this->addAtomVoid();
            $this->addLink($this->sequence, $voidId, 'ELEMENT');
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
                                  'token'    => $this->getToken($this->tokens[$this->id][0])]);

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
                $this->addLink($this->sequence, $elementId, 'ELEMENT');
                
                ++$this->id;
            }
        };
        $elementId = $this->popExpression();
        $this->addLink($this->sequence, $elementId, 'ELEMENT');
        
        ++$this->id;
 
        $this->endSequence();

        $this->setAtom($blockId, ['code'     => '/**/',
                                  'fullcode' => '/**/',
                                  'line'     => $this->tokens[$this->id][2],
                                  'token'    => $this->getToken($this->tokens[$this->id][0])]);
        $this->pushExpression($blockId);
        
        return $blockId;
    }

    private function processFor() {
        $forId = $this->addAtom('For');
        $current = $this->id;
        ++$this->id; // Skip for
        $current = $this->id;

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
        
        $this->setAtom($forId, ['code'     => $this->tokens[$current][1].' (' . $this->atoms[$initId]['fullcode'] .') { /**/ }',
                                'fullcode' => $this->tokens[$current][1].' (' . $this->atoms[$initId]['fullcode'] .') { /**/ }',
                                'line'     => $this->tokens[$current][2],
                                'token'    => $this->getToken($this->tokens[$this->id][0])]);
        $this->pushExpression($forId);
        
        if ($isColon === true) {
            ++$this->id; // skip endfor
            ++$this->id; // skip ; (will do just below)
        }
        $this->processSemicolon($forId);

        return $forId;
    }
    
    private function processForeach() {
        $id = $this->addAtom('Foreach');
        $current = $this->id;
        ++$this->id; // Skip if

        while (!in_array($this->tokens[$this->id + 1][0], [T_AS])) {
            $this->processNext();
        };

        $sourceId = $this->popExpression();
        $this->addLink($id, $sourceId, 'SOURCE');
        
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
        $this->addLink($id, $blockId, 'BLOCK');
        if ($isColon === true) {
            ++$this->id; // skip endforeach
        }

        $this->setAtom($id, ['code'     => 'foreach (' . $this->atoms[$sourceId]['fullcode'] . ' as '. $this->atoms[$valueId]['fullcode'] .') { /**/ }',
                             'fullcode' => 'foreach (' . $this->atoms[$sourceId]['fullcode'] . ' as '. $this->atoms[$valueId]['fullcode'] .') { /**/ }',
                             'line'     => $this->tokens[$current][2],
                             'token'    => $this->getToken($this->tokens[$current][0])]);
        $this->pushExpression($id);
        $this->processSemicolon($id);

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
            $this->addLink($blockId, $voidId, 'ELEMENT');
            $this->endSequence();
            ++$this->id;
        } elseif (in_array($this->tokens[$this->id + 1][0], [T_CLOSE_TAG, T_CLOSE_CURLY, T_CLOSE_PARENTHESIS])) {
            // Completely void (no ;)
            $this->startSequence();
            $blockId = $this->sequence;

            $voidId = $this->addAtomVoid();
            $this->addLink($blockId, $voidId, 'ELEMENT');
            $this->endSequence();

        } else {
            // One expression only
            $this->startSequence();
            $blockId = $this->sequence;
            $current = $this->id;
            
            $finals = array_merge([T_SEMICOLON, T_CLOSE_TAG, T_ELSE, T_END, T_CLOSE_CURLY], $finals);
            while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
                $this->processNext();
            };
            $expressionId = $this->popExpression();
            
            $this->endSequence();
            $this->popExpression(); // remove the sequence from the stack, to avoid double linking
            
            if (!in_array($this->tokens[$current + 1][0], [T_IF, T_SWITCH, T_WHILE, T_FOR, T_FOREACH])) {
                ++$this->id;
            }
        }
        
        return $blockId;
    }

    private function processDo() {
        $dowhileId = $this->addAtom('Dowhile');
        $current = $this->id;
        
        $blockId = $this->processFollowingBlock([T_WHILE]);
        $this->addLink($dowhileId, $blockId, 'BLOCK');

        ++$this->id; // Skip while
        ++$this->id; // Skip (

        while (!in_array($this->tokens[$this->id + 1][0], [T_CLOSE_PARENTHESIS])) {
            $this->processNext();
        };
        ++$this->id; // skip )
        $conditionId = $this->popExpression();
        $this->addLink($dowhileId, $conditionId, 'CONDITION');

        $this->setAtom($dowhileId, ['code'     => 'do { /**/ } while (' . $this->atoms[$conditionId]['fullcode'] . ')',
                                    'fullcode' => 'do { /**/ } while (' . $this->atoms[$conditionId]['fullcode'] . ')',
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

        $this->addLink($whileId, $blockId, 'BLOCK');

        $this->setAtom($whileId, ['code'     => $this->tokens[$current][1].' (' . $this->atoms[$conditionId]['fullcode'] . ') { /**/ }',
                                  'fullcode' => $this->tokens[$current][1].' (' . $this->atoms[$conditionId]['fullcode'] . ') { /**/ }',
                                  'line'     => $this->tokens[$current][2],
                                  'token'    => $this->getToken($this->tokens[$current][0]) ]);

        if ($isColon === true) {
            ++$this->id;
            if ($this->tokens[$this->id + 1][0] === T_SEMICOLON) {
                ++$this->id; // skip ;
            }
        }
        
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
        }
        $this->pushExpression($declareId);
        $this->processSemicolon();
            
        $this->setAtom($declareId, ['code'        => $this->tokens[$current][1].' (' . $this->atoms[$argsId]['fullcode'] . ') { /**/ }',
                                    'fullcode'    => $this->tokens[$current][1].' (' . $this->atoms[$argsId]['fullcode'] . ') { /**/ }',
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
                                    'fullcode' => $this->tokens[$current][1].' : /**/',
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
        
        $this->setAtom($caseId, ['code'     => $this->tokens[$current][1].' '.$this->atoms[$itemId]['fullcode'].' : /**/ ',
                                 'fullcode' => $this->tokens[$current][1].' '.$this->atoms[$itemId]['fullcode'].' : /**/ ',
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

        $this->setAtom($switchId, ['code'     => $this->tokens[$current][1].' (' . $this->atoms[$nameId]['fullcode'] . ') { /**/ }',
                                   'fullcode' => $this->tokens[$current][1].' (' . $this->atoms[$nameId]['fullcode'] . ') { /**/ }',
                                   'line'     => $this->tokens[$current][2],
                                   'token'    => $this->getToken($this->tokens[$current][0]) ]);

        $casesId = $this->addAtom('Sequence');
        $this->setAtom($casesId, ['code'     => ' / * cases * /',
                                  'fullcode' => ' / * cases * /',
                                  'line'     => $this->tokens[$current][2],
                                  'token'    => $this->getToken($this->tokens[$current][0])]);
        $this->addLink($switchId, $casesId, 'CASES');
        ++$this->id;
        
        if ($this->tokens[$this->id + 1][0] === T_CLOSE_PARENTHESIS) {
            $voidId = $this->addAtomVoid();
            $this->addLink($casesId, $voidId, 'ELEMENT');
            
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
            };
        }
        ++$this->id;

        $this->setAtom($switchId, ['code'     => $this->tokens[$current][1].' ('.$this->atoms[$nameId]['fullcode'].') { /* cases */ }',
                                   'fullcode' => $this->tokens[$current][1].' ('.$this->atoms[$nameId]['fullcode'].') { /* cases */ }',
                                   'line'     => $this->tokens[$current][2],
                                   'token'    => $this->getToken($this->tokens[$current][0])]);
        
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
        $isColon = ($this->tokens[$current][0] === T_IF) && ($this->tokens[$this->id + 1][0] === T_COLON);
        
        $blockId = $this->processFollowingBlock([T_ENDIF, T_ELSE, T_ELSEIF]);
        $this->addLink($id, $blockId, 'THEN');
        
        // Managing else case
        if (in_array($this->tokens[$this->id][0], [T_END, T_CLOSE_TAG])) {
            --$this->id;
            // Back up one unit to allow later processing for sequence
        } elseif ($this->tokens[$this->id + 1][0] == T_ELSEIF){
            ++$this->id;

            $elseifId = $this->processIfthen();
            $this->addLink($id, $elseifId, 'ELSE');
        } elseif ($this->tokens[$this->id + 1][0] == T_ELSE){
            ++$this->id; // Skip else
            $elseId = $this->processFollowingBlock([T_ENDIF]);
            $this->addLink($id, $elseId, 'ELSE');
        } 

        if ($isColon === true) {
            ++$this->id;
            if ($this->tokens[$this->id + 1][0] === T_SEMICOLON) {
                ++$this->id; // skip ;
            }
        }
        
        if ($this->tokens[$current][0] === T_IF) {
            $this->pushExpression($id);
            $this->processSemicolon();
        } 

        $this->setAtom($id, ['code'     => $this->tokens[$current][1].' (' . $this->atoms[$conditionId]['fullcode'] . ') { /**/ }',
                             'fullcode' => $this->tokens[$current][1].' (' . $this->atoms[$conditionId]['fullcode'] . ') { /**/ }',
                             'line'     => $this->tokens[$current][2],
                             'token'    => $this->getToken($this->tokens[$current][0]) ]);
        
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
                                     'token'    => $this->getToken($this->tokens[$current][0]) ]);

            $voidId = $this->addAtomVoid();

            $argumentsId = $this->addAtom('Arguments');
            $this->addLink($argumentsId, $voidId, 'ARGUMENT');
            $this->setAtom($argumentsId, ['code'    => $this->atoms[$voidId]['code'],
                                         'fullcode' => $this->atoms[$voidId]['fullcode'],
                                         'line'     => $this->tokens[$this->id][2],
                                         'token'    => $this->getToken($this->tokens[$this->id][0])]);

            $functioncallId = $this->addAtom('Functioncall');
            $this->setAtom($functioncallId, ['code'     => $this->atoms[$nameId]['code'], 
                                             'fullcode' => $this->atoms[$nameId]['fullcode'] . ' ' .
                                                           ($this->atoms[$argumentsId]['fullcode'] === 'Void' ? '' :  $this->atoms[$argumentsId]['fullcode']),
                                             'line'     => $this->tokens[$this->id][2],
                                             'token'    => $this->getToken($this->tokens[$this->id][0])
                                           ]);
            $this->addLink($functioncallId, $argumentsId, 'ARGUMENTS');
            $this->addLink($functioncallId, $nameId, 'NAME');

            $this->pushExpression($functioncallId);

            return $functioncallId;    
        } else {
            $nameId = $this->addAtom('Identifier');
            $this->setAtom($nameId, ['code'     => $this->tokens[$this->id][1], 
                                     'fullcode' => $this->tokens[$this->id][1],
                                     'line'     => $this->tokens[$this->id][2],
                                     'token'    => $this->getToken($this->tokens[$current][0]) ]);
                                     
            $this->pushExpression($nameId);

            $this->processFunctioncall();
        }
    }
    
    private function processArray() {
//        $this->tokens[$this->id + 1][0] == T_OPEN_PARENTHESIS)
        return $this->processString();
    }

    private function processTernary() {
        $current = $this->id;

        $conditionId = $this->popExpression();
        $ternaryId = $this->addAtom('Ternary');
        
        while (!in_array($this->tokens[$this->id + 1][0], [T_COLON]) ) {
            $id = $this->processNext();
        } ;
        $thenId = $this->popExpression();
        ++$this->id; // Skip colon

        $finals = $this->getPrecedence($this->tokens[$this->id][0]);
        while (!in_array($this->tokens[$this->id + 1][0], $finals) ) {
            $id = $this->processNext();
        } ;
        $elseId = $this->popExpression();

        $this->addLink($ternaryId, $conditionId, 'CONDITION');
        $this->addLink($ternaryId, $thenId, 'THEN');
        $this->addLink($ternaryId, $elseId, 'ELSE');

        $x = ['code'     => $this->tokens[$current][1], 
              'fullcode' => $this->atoms[$conditionId]['fullcode'] . ' ? ' .
                            $this->atoms[$thenId]['fullcode'] . ' : ' . 
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
        };
        $blockId = $this->sequence;
        $this->endSequence();
        
        $this->setAtom($blockId, ['code'     => '',
                                  'fullcode' => ' /**/ ',
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
            $this->pushExpression($nsnameId);
            
            return $this->processFCOA($nsnameId);
        } else {
            while (!in_array($this->tokens[$this->id + 1][0], [T_OPEN_CURLY, T_SEMICOLON]) ) {
                $this->processNext();
            };
            $nameId = $this->popExpression();
        }
        $namespaceId = $this->addAtom('Namespace');
        $this->addLink($namespaceId, $nameId, 'NAME');

        // Here, we make sure namespace is encompassing the next elements.
        if ($this->tokens[$this->id + 1][0] === T_SEMICOLON) {
            // Process block 
            ++$this->id; // Skip ; to start actual sequence
            if ($this->tokens[$this->id + 1][0] === T_END) {
                $voidId = $this->addAtomVoid();
                $blockId = $this->addAtom('Sequence');
                $this->setAtom($blockId, ['code' => '{}', 
                                          'fullcode' => ' {/**/} ',
                                          'line'     => $this->tokens[$this->id][2],
                                          'token'    => $this->getToken($this->tokens[$this->id][0])]);
                $this->addLink($blockId, $voidId, 'ELEMENT');
            } else {
                $blockId = $this->processNamespaceBlock();
            }
            $this->addLink($namespaceId, $blockId, 'BLOCK');
            $this->addLink($this->sequence, $namespaceId, 'ELEMENT3');
        } else {
            // Process block 
            $blockId = $this->processFollowingBlock(false);
            $this->addLink($namespaceId, $blockId, 'BLOCK');
        }
        
        $x = ['code'     => $this->tokens[$current][1], 
              'fullcode' => $this->tokens[$current][1].' '.$this->atoms[$nameId]['fullcode'] .' { /**/ }',
              'line'     => $this->tokens[$current][2],
              'token'    => $this->getToken($this->tokens[$current][0])];
        $this->setAtom($namespaceId, $x);
        
        $this->pushExpression($namespaceId);

        return $namespaceId;
    }

    private function processAs() {
        return $this->processOperator('As', $this->getPrecedence($this->tokens[$this->id][0]), ['NAME', 'AS']);
    }

    private function processInsteadof() {
        return $this->processOperator('Insteadof', $this->getPrecedence($this->tokens[$this->id][0]), ['NAME', 'AS']);
    }

    private function processUse() {
        $useId = $this->addAtom('Use');
        $current = $this->id;

        // use const
        if ($this->tokens[$this->id + 1][0] === T_CONST) {
            ++$this->id;

            $this->processSingle('Identifier');
            $constId = $this->popExpression();
            $this->addLink($useId, $constId, 'CONST');
        }

        // use function
        if ($this->tokens[$this->id + 1][0] === T_FUNCTION) {
            ++$this->id;

            $this->processSingle('Identifier');
            $constId = $this->popExpression();
            $this->addLink($useId, $constId, 'FUNCTION');
        }
        
        $fullcode = array();
        --$this->id;
        do {
            ++$this->id;
            $namespaceId = $this->processOneNsname();

            if ($this->tokens[$this->id + 1][0] === T_AS) {
                ++$this->id;
                $this->pushExpression($namespaceId);
                $this->processAs();
                $namespaceId = $this->popExpression();
            } elseif ($this->tokens[$this->id + 1][0] === T_NS_SEPARATOR) {
                $this->atoms[$namespaceId]['fullcode'] .= '\\';
                
                ++$this->id;
                ++$this->id;
                $subuseId = $this->addAtom('Sequence');
                $this->addLink($namespaceId, $subuseId, 'GROUPUSE');
                $fullcode = [];
                
                $forcId = 0;
                while($this->tokens[$this->id + 1][0] !== T_CLOSE_CURLY) {
                    if (in_array($this->tokens[$this->id + 1][0], [T_FUNCTION, T_CONST])) {
                        ++$this->id;
                        $this->processSingle('Identifier');
                        $forcId = $this->popExpression();
                    }
                    
                    $this->processNextAsIdentifier();
                    if ($this->tokens[$this->id + 1][0] === T_AS) {
                        ++$this->id;
                        $this->processAs();
                    }
                    
                    if ($this->tokens[$this->id + 1][0] === T_COMMA) {
                        $elementId = $this->popExpression();
                        $this->addLink($subuseId, $elementId, 'ELEMENT');
                        $fullcode[] = $this->atoms[$elementId]['fullcode'];

                        if ($forcId > 0) {
                            $this->addLink($elementId, $forcId, strtoupper($this->atoms[$forcId]['code']) );
                            $forcId = 0;
                        }
                        
                        ++$this->id;
                    }
                };
                
                $elementId = $this->popExpression();
                $this->addLink($subuseId, $elementId, 'ELEMENT');
                $fullcode[] = $this->atoms[$elementId]['fullcode'];

                if ($forcId > 0) {
                    $this->addLink($elementId, $forcId, strtoupper($this->atoms[$forcId]['code']) );
                    $forcId = 0;
                }
                
                $this->setAtom($subuseId, ['code'     => '{ /**/ }',
                                           'fullcode' => '{' . join(', ', $fullcode).' }',
                                           'line'     => $this->tokens[$this->id][2],
                                           'token'    => $this->getToken($this->tokens[$this->id][0])]);
                ++$this->id;
            }
            
            $this->addLink($useId, $namespaceId, 'USE');
            $fullcode[] = $this->atoms[$namespaceId]['fullcode'];

        } while ($this->tokens[$this->id + 1][0] === T_COMMA);
        
        if ($this->tokens[$this->id + 1][0] === T_OPEN_CURLY) {
            $useBlockId = $this->processFollowingBlock([T_CLOSE_CURLY]);
            $this->addLink($namespaceId, $useBlockId, 'BLOCK');
        }

        $x = ['code'     => $this->tokens[$current][1], 
              'fullcode' => $this->tokens[$current][1].' '.join(", ", $fullcode)];
        $this->setAtom($useId, $x);
        $this->pushExpression($useId);

        return $useId;
    }
    
    private function processVariable() {
        $variableId = $this->processSingle('Variable');

        return $this->processFCOA($variableId);
    }
    
    private function processFCOA($id, $alsoCurly = true) {
        // For functions and constants 
        if ($this->tokens[$this->id + 1][0] === T_OPEN_PARENTHESIS) {
            return $this->processFunctioncall();
        } elseif ($this->tokens[$this->id + 1][0] === T_OPEN_BRACKET &&
                  $this->tokens[$this->id + 2][0] === T_CLOSE_BRACKET) {
            return $this->processAppend();
        } elseif ($this->tokens[$this->id + 1][0] === T_OPEN_BRACKET ||
                  ($alsoCurly &&
                  $this->tokens[$this->id + 1][0] === T_OPEN_CURLY)) {
            return $this->processBracket();
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
        
        return $appendId;
    }
    
    private function processInteger() {
        return $this->processSingle('Integer');
    }

    private function processReal() {
        return $this->processSingle('Real');
    }
    
    private function processLiteral() {
        return $this->processSingle('String');
    }

    private function processMagicConstant() {
        return $this->processSingle('MagicConstant');
    }

    //////////////////////////////////////////////////////
    /// processing single operators
    //////////////////////////////////////////////////////
    private function processSingleOperator($atom, $finals, $link) {
        $current = $this->id;

        $operatorId = $this->addAtom($atom);
        while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
            $id = $this->processNext();
        };

        $operandId = $this->popExpression();
        
        $this->addLink($operatorId, $operandId, $link);

        $x = ['code'     => $this->tokens[$current][1], 
              'fullcode' => $this->tokens[$current][1] . ' ' .
                            $this->atoms[$operandId]['fullcode'],
              'line'     => $this->tokens[$current][2],
              'token'    => $this->getToken($this->tokens[$current][0])];

        $this->setAtom($operatorId, $x);
        $this->pushExpression($operatorId);
        
        return $operatorId;
    }

    private function processCast() {
        return $this->processSingleOperator('Cast', $this->getPrecedence($this->tokens[$this->id][0]), 'CAST');
    }

    private function processReturn() {
        return $this->processSingleOperator('Return', $this->getPrecedence($this->tokens[$this->id][0]), 'RETURN');
    }
    
    private function processThrow() {
        return $this->processSingleOperator('Throw', $this->getPrecedence($this->tokens[$this->id][0]), 'THROW');
    }

    private function processYield() {
        return $this->processSingleOperator('Yield', $this->getPrecedence($this->tokens[$this->id][0]), 'YIELD');
    }

    private function processYieldfrom() {
        return $this->processSingleOperator('Yieldfrom', $this->getPrecedence($this->tokens[$this->id][0]), 'RETURN');
    }

    private function processNot() {
        return $this->processSingleOperator('Not', $this->getPrecedence($this->tokens[$this->id][0]), 'NOT');
    }

    private function processEllipsis() {
        return $this->processSingleOperator('Ellipsis', $this->getPrecedence($this->tokens[$this->id][0]), 'TRIPLEDOT');
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
                                  'token'    => $this->getToken($this->tokens[$current][0])]);
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
            $this->addLink($variableId, $expressionId, 'VARIABLE');

            $x = ['code'     => $this->tokens[$current][1], 
                  'fullcode' => $this->tokens[$current][1] . '{' .
                                $this->atoms[$expressionId]['fullcode'].'}',
                  'line'     => $this->tokens[$current][2],
                  'token'    => $this->getToken($this->tokens[$current][0])];
            $this->setAtom($variableId, $x);
            $this->pushExpression($variableId);

            return $variableId;
        } else {
            return $this->processSingleOperator('Variable', $this->getPrecedence($this->tokens[$this->id][0]), 'VARIABLE');
        }
    }

    private function processClone() {
        return $this->processSingleOperator('Clone', $this->getPrecedence($this->tokens[$this->id][0]), 'CLONE');
    }
    
    private function processGoto() {
        return $this->processSingleOperator('Goto', $this->getPrecedence($this->tokens[$this->id][0]), 'GOTO');
    }

    private function processNoscream() {
        return $this->processSingleOperator('Noscream', $this->getPrecedence($this->tokens[$this->id][0]), 'AT');
    }

    private function processNew() {
        return $this->processSingleOperator('New', $this->getPrecedence($this->tokens[$this->id][0]), 'NEW');
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

        if ($this->tokens[$this->id + 1][0] == T_LNUMBER || $this->tokens[$this->id + 1][0] == T_DNUMBER) {
            $operandId = $this->processNext();

            $x = ['code'     => $code * $this->atoms[$operandId]['code'],
                  'fullcode' => $sign . $this->atoms[$operandId]['fullcode'],
                  'line'     => $this->tokens[$this->id][2],
                  'token'    => $this->getToken($this->tokens[$this->id][0])];
            $this->setAtom($operandId, $x);

            return $operandId;
        } else {
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

        $left = $this->popExpression();

        $finals = $this->getPrecedence($this->tokens[$this->id][0]);
        
        if ($this->tokens[$this->id + 1][0] === T_OPEN_CURLY) {
            $blockId = $this->processCurlyExpression();
            $right = $this->processFCOA($blockId);
        } elseif ($this->tokens[$this->id + 1][0] === T_DOLLAR) {
            ++$this->id; // Skip $
            $blockId = $this->processCurlyExpression();
            $right = $this->processFCOA($blockId);
        } elseif (!in_array($this->tokens[$this->id + 1][0], [ T_VARIABLE, T_STRING])) {
            $right = $this->processNextAsIdentifier();
        } else {
            do {
                $id = $this->processNext();
            } while (!in_array($this->tokens[$this->id + 1][0], $finals)) ;

            $right = $this->popExpression();
        }

        if ($this->atoms[$right]['atom'] == 'Identifier') {
            $staticId = $this->addAtom('Staticconstant');
            $links = 'CONSTANT';
        } elseif (in_array($this->atoms[$right]['atom'], array('Variable', 'Array', 'Arrayappend', 'MagicConstant', 'Concatenation', 'Block'))) {
            $staticId = $this->addAtom('Staticproperty');
            $links = 'PROPERTY';
        } elseif (in_array($this->atoms[$right]['atom'], array('Functioncall'))) {
            $staticId = $this->addAtom('Staticmethodcall');
            $links = 'METHOD';
        } else {
            die("Unprocessed atom in static call (right) : ".$this->atoms[$right]['atom']."\n");
        }

        $this->addLink($staticId, $left, 'CLASS');
        $this->addLink($staticId, $right, $links);

        $x = ['code'     => $this->tokens[$current][1], 
              'fullcode' => $this->atoms[$left]['fullcode'] . '::' .
                            $this->atoms[$right]['fullcode'],
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
        while (!in_array($this->tokens[$this->id + 1][0], $finals) ) {
            $id = $this->processNext();
        };

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
    }

    private function processObjectOperator() {
        $current = $this->id;

        $left = $this->popExpression();

        if ($this->tokens[$this->id + 1][0] === T_OPEN_CURLY) {
            $blockId = $this->processCurlyExpression();
            $right = $this->processFCOA($blockId);
        } else {
            $finals =$this->getPrecedence($this->tokens[$this->id][0]);
            while (!in_array($this->tokens[$this->id + 1][0], $finals)) {
                $id = $this->processNext();
            } ;
            $right = $this->popExpression();
        }

        if (in_array($this->atoms[$right]['atom'], array('Variable', 'Array', 'Identifier', 'Concatenation', 'Arrayappend', 'PostPlusPlus', 'Property', 'MagicConstant', 'Block'))) {
            $staticId = $this->addAtom('Property');
            $links = 'PROPERTY';
        } elseif (in_array($this->atoms[$right]['atom'], array('Functioncall'))) {
            $staticId = $this->addAtom('Methodcall');
            $links = 'METHOD';
        }  else {
            die("Unprocessed atom in object call (right) : ".$this->atoms[$right]['atom']."\n");
        }

        $this->addLink($staticId, $left, 'CLASS');
        $this->addLink($staticId, $right, $links);

        $x = ['code'     => $this->tokens[$current][1], 
              'fullcode' => $this->atoms[$left]['fullcode'] . '->' .
                            $this->atoms[$right]['fullcode'],
              'line'     => $this->tokens[$current][2],
              'token'    => $this->getToken($this->tokens[$current][0])];

        $this->setAtom($staticId, $x);
        $this->pushExpression($staticId);
    }    
    

    private function processAssignation() {
        $this->processOperator('Assignation', $this->getPrecedence($this->tokens[$this->id][0]));
    }

    private function processCoalesce() {
        $this->processOperator('Coalesce', $this->getPrecedence($this->tokens[$this->id][0]));
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
            print "$operandId is a reference\n";
            
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
        $this->processOperator('Concatenation', $this->getPrecedence($this->tokens[$this->id][0]));
    }

    private function processInstanceof() {
        $this->processOperator('Instanceof', $this->getPrecedence($this->tokens[$this->id][0]), ['VARIABLE', 'CLASS']);
    }

    private function processKeyvalue() {
        return $this->processOperator('Keyvalue', $this->getPrecedence($this->tokens[$this->id][0]), ['KEY', 'VALUE']);
    }

    private function processBitshift() {
        $this->processOperator('Bitshift', $this->getPrecedence($this->tokens[$this->id][0]));
    }

    private function processEcho() {
        $nameId = $this->addAtom('Identifier');
        $this->setAtom($nameId, ['code'     => $this->tokens[$this->id][1], 
                                 'fullcode' => $this->tokens[$this->id][1],
                                 'line'     => $this->tokens[$this->id][2],
                                 'token'    => $this->getToken($this->tokens[$this->id][0]) ]);

        $argumentsId = $this->processArguments([T_SEMICOLON, T_CLOSE_TAG]);
        // processArguments goes too far, up to ;
        --$this->id;

        $functioncallId = $this->addAtom('Functioncall');
        $this->setAtom($functioncallId, ['code'     => $this->atoms[$nameId]['code'], 
                                         'fullcode' => $this->atoms[$nameId]['fullcode'] . ' ' .
                                                       $this->atoms[$argumentsId]['fullcode'],
                                         'line'     => $this->tokens[$this->id][2],
                                         'token'    => $this->getToken($this->tokens[$this->id][0])
                                        ]);
        $this->addLink($functioncallId, $argumentsId, 'ARGUMENTS');
        $this->addLink($functioncallId, $nameId, 'NAME');

        $this->pushExpression($functioncallId);

        return $functioncallId;    
    }

    private function processPrint() {
        $nameId = $this->addAtom('Identifier');
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
        $this->setAtom($functioncallId, ['code'     => $this->atoms[$nameId]['code'], 
                                         'fullcode' => $this->atoms[$nameId]['code'].' '.
                                                       $this->atoms[$argumentsId]['fullcode'],
                                         'line'     => $this->tokens[$this->id][2],
                                         'token'    => $this->getToken($this->tokens[$this->id][0]) 
                                        ]);
        $this->addLink($functioncallId, $argumentsId, 'ARGUMENTS');
        $this->addLink($functioncallId, $nameId, 'NAME');

        $this->pushExpression($functioncallId);
        
        return $functioncallId;
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
        print "  Adding a new void\n";
        $id = $this->addAtom('Void');
        $this->setAtom($id, ['code'     => 'Void', 
                             'fullcode' => 'Void',
                             'line'     => $this->tokens[$this->id][2],
                             'token'    => 'T_VOID'
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
            $id = $this->addAtomVoid('Void');
        } else {
            $id = array_pop($this->expressions);
        }
        return $id;
    }
    
    private function checkTokens() {
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
                print "Forgotten atom $id : \n";
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
        print $total." errors found\n";
    }
    

    private function saveFiles() {
        $this->checkTokens();
        
        $files   = [];
        $extras  = [];
        
        foreach($this->atoms as $atom) {
            if (!isset($files[$atom['atom']])) {
                $files[$atom['atom']] = fopen('./nodes.g3.'.$atom['atom'].'.csv', 'w+');
                $headers = ['id', 'atom', 'code', 'fullcode', 'line', 'token'];

                $extras[$atom['atom']] = [];
                foreach(self::PROP_OPTIONS as $title => $atoms) {
                    if (in_array($atom['atom'], $atoms)) { 
                        print "Extra $title for $atom[atom]\n";
                        $headers[] = $title;
                        $extras[$atom['atom']][] = $title;
                    }
                }
                fputcsv($files[$atom['atom']], $headers);
            }

            $extra = [];
            foreach($extras[$atom['atom']] as $e) {
                print_r($atom);
                $extra[] = (int) $atom[$e];
            }
            $extra = join(',', $extra);
            if (count($extra) > 0) {
                print_r($extras);
                print $extra."\n";
                $extra = ','.$extra;
            }
            
            fwrite($files[$atom['atom']], $atom['id'].','.
                                          $atom['atom'].',"'.
                                          str_replace(array('\\', '"'), array('\\\\', '\\"'), $atom['code']).'","'.
                                          str_replace(array('\\', '"'), array('\\\\', '\\"'), $atom['fullcode']).'",'.
                                          ($atom['line'] ?? 0).',"'.
                                          ($atom['token'] ?? '').'"'.
                                          $extra.
                                          "\n");
        }
        foreach($files as $fp) {
            fclose($fp);
        }
        
        foreach($this->links as $label => $origins) {
            foreach($origins as $origin => $destinations) {
                foreach($destinations as $destination => $links) {
                    $csv = $label.'.'.$origin.'.'.$destination;
                    $fp = fopen('./rels.g3.'.$csv.'.csv', 'w+');
                    if (!is_resource($fp)) { print count($files); die();}
                    fputcsv($fp, ['start', 'end']);
    
                    foreach($links as $link) {
                        fputcsv($fp, [$link['origin'], $link['destination']], ',', '"', '\\');
                    }
                    
                    fclose($fp);
                }
            }
        }
    }
    
    private function startSequence() {
        $this->sequence = $this->addAtom('Sequence');
        $this->setAtom($this->sequence, ['code'     => ';', 
                                         'fullcode' => ' /**/ ',
                                         'line'     => $this->tokens[$this->id][2],
                                         'token'    => 'T_SEMICOLON']);
        
        $this->sequences[] = $this->sequence;
    }

    private function endSequence() {
        array_pop($this->sequences);
        if (!empty($this->sequences)) {
            $this->sequence = $this->sequences[count($this->sequences) - 1];
        } else {
            $this->sequence = null;
        }
    }
    
    private function getToken($token) {
        if (is_string($token)) {
            return self::TOKENS[$token];
        } else {
            return token_name($token);
        }
    }

    private function getPrecedence($token) {
        static $cache;
        
        if ($cache === null) {
            $cache = [];
            foreach(self::PRECEDENCE as $k1 => $p1) {
                $cache[$k1] = [];
                foreach(self::PRECEDENCE as $k2 => $p2) {
                    if ($p1 <= $p2) {
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
}

?>
