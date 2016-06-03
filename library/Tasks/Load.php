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
const T_COMMA                        = ',';
const T_DOT                          = '.';
const T_EQUAL                        = '=';
const T_MINUS                        = '-';
const T_AT                           = '@';
const T_OPEN_BRACKET                 = '[';
const T_OPEN_PARENTHESIS             = '(';
const T_PERCENTAGE                   = '%';
const T_PLUS                         = '+';
const T_PLUS_EQUAL                   = '+=';
const T_SEMICOLON                    = ';';
const T_SLASH                        = '/';
const T_STAR                         = '*';
const T_STARSTAR                     = '**';
const T_TILDE                        = '~';
const T_END                          = 'The End';

class Load extends Tasks {
    private $php    = null;
    private $client = null;
    private $config = null;
     
    const PRECEDENCE = [
                        T_OBJECT_OPERATOR             => 1,
                        T_CLONE                       => 1,
                        T_NEW                         => 1, 
                        T_OPEN_BRACKET                => 2,
               
                        T_STARSTAR                    => 3,
                        
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
                        T_NOSCREAM                    => 4,

                        T_INSTANCEOF                  => 5,
                        
                        T_BANG                        => 6,
               
                        T_SLASH                       => 7,
                        T_STAR                        => 7,
                        T_PERCENTAGE                  => 7,
                 
                        T_PLUS                        => 8,
                        T_MINUS                       => 8,
                        T_DOT                         => 8,
               
                        T_EQUAL                       => 19,
                        T_PLUS_EQUAL                  => 19,
                        
                        T_ECHO                        => 20,
    ];
    
    const TOKENS = [ ';'  => T_SEMICOLON,
                     '+'  => T_PLUS,
                     '-'  => T_MINUS,
                     '/'  => T_SLASH,
                     '*'  => T_STAR,
                     '.'  => T_DOT,
                     '**' => T_STARSTAR,
                     '['  => T_OPEN_BRACKET,
                     ']'  => T_CLOSE_BRACKET,
                     '('  => T_OPEN_PARENTHESIS,
                     ')'  => T_CLOSE_PARENTHESIS,
                     '='  => T_EQUAL,
                     '+=' => T_PLUS_EQUAL,
                     ','  => T_COMMA,
                     '!'  => T_BANG,
                     '~'  => T_TILDE,
                     '@'  => T_AT,
                   ];
    
    public function run(\Config $config) {
        $this->config = $config;
        
        if (!file_exists($this->config->projects_root.'/projects/'.$this->config->project.'/config.ini')) {
            display('No such project as "'.$this->config->project.'". Aborting');
            die();
        }

        $this->checkTokenLimit();

        $this->php = new \Phpexec();
        if (!$this->php->isValid()) {
            die("This PHP binary is not valid for running Exakat.\n");
        }
        $this->php->getTokens();

        // formerly -q option. Currently, only one loader, via csv-batchimport;
        $this->client = new \Loader\CypherG3();
        
        $this->datastore->cleanTable('tokenCounts');

        if ($filename = $this->config->filename) {
            $this->processFile($filename);
        } elseif ($dirName = $this->config->dirname) {
            $this->processDir($dirName);
        } elseif (($project = $this->config->project) != 'default') {
            $this->processProject($project);
        } else {
            die('No file to process. Aborting');
        }

        $this->client->finalize();
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
    
        $nbTokens = 0;
        foreach($files as $file) {
            $nbTokens += $this->processFile($file);
        }
        return array('files' => count($files), 'tokens' => $nbTokens);
    }

    private function processFile($filename) {
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
        foreach($tokens as $t) {
            if (is_array($t)) {
                if ($t[0] == T_COMMENT || 
                    $t[0] == T_WHITESPACE) { 
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
        
        $this->atoms = array();
        $this->atomCount = 0;
        $this->links = array();
        $this->id = -1;
        
        $id0 = $this->addAtom('Project');
        $this->setAtom($id0, ['code'     => 'Whole', 
                              'fullcode' => 'Whole']);
        $id1 = $this->addAtom('File');
        $this->setAtom($id1, ['code'     => './test.php', 
                              'fullcode' => './test.php']);
        $this->addLink($id0, $id1, 'PROJECT');
        
        $n = count($this->tokens) - 1;
        do {
            $this->processNext();
            print "$this->id / $n\n";
        } while ($this->id < $n);
        
        $id = $this->popExpression();
        $this->addLink($id1, $id, 'FILE');
        $this->setAtom($id, ['root' => true]);
        
        print count($this->atoms)." atoms\n";
        print count($this->links)." links\n";
        print "Final id : $this->id\n";
        
        $this->saveFiles();
    }

    private function processNext() {
       ++$this->id;
       
       print $this->id.") ".$this->tokens[$this->id][1]."\n";
       $this->processing = [T_OPEN_TAG                 => 'processOpenTag',
       
                            T_VARIABLE                 => 'processVariable',
                            T_LNUMBER                  => 'processInteger',
                            T_DNUMBER                  => 'processReal',
       
                            T_OPEN_PARENTHESIS         => 'processParenthesis',
                            T_CLOSE_PARENTHESIS        => 'processNone',
           
                            T_PLUS                     => 'processAddition',
                            T_MINUS                    => 'processAddition',
                            T_STAR                     => 'processMultiplication',
                            T_SLASH                    => 'processMultiplication',
                            T_STARSTAR                 => 'processPower',
                            T_INSTANCEOF               => 'processInstanceof',
       
                            T_DOT                      => 'processDot',
                            T_ECHO                     => 'processEcho',
       
                            T_EQUAL                    => 'processAssignation',
                            T_PLUS_EQUAL               => 'processAssignation',
           
                            T_OPEN_BRACKET             => 'processBracket',
                            T_CLOSE_BRACKET            => 'processNone',

                            T_AT                       => 'processNoscream',
       
                            T_STRING                   => 'processString',
                            T_CONSTANT_ENCAPSED_STRING => 'processLiteral',
   
                            T_ARRAY_CAST               => 'processCast',
                            T_BOOL_CAST                => 'processCast',
                            T_DOUBLE_CAST              => 'processCast',
                            T_INT_CAST                 => 'processCast',
                            T_OBJECT_CAST              => 'processCast',
                            T_STRING_CAST              => 'processCast',
                            T_UNSET_CAST               => 'processCast',

                            T_BANG                     => 'processNot',
                            T_TILDE                    => 'processNot',
                             
                            T_SEMICOLON                => 'processSemicolon',
                            T_CLOSE_TAG                => 'processClosingTag',
                            T_END                      => 'processNone',
                            ];
                            
        if (!isset($this->processing[ $this->tokens[$this->id][0] ])) {
            print "Defaulting a : $this->id ";
            print_r($this->tokens[$this->id]);
            die("Missing the method\n");
        }
        $method = $this->processing[ $this->tokens[$this->id][0] ];
        
        return $this->$method();
    }

    // Dummy method
    private function processNone() {
        return null;// Just ignore
    }

    //////////////////////////////////////////////////////
    /// processing complex tokens
    //////////////////////////////////////////////////////
    private function processOpenTag() {
        $id = $this->addAtom('Php');
        
        $this->startSequence();

        do {
            $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], [T_END, T_CLOSE_TAG])) ;

        // always do this, T_END or T_CLOSE_TAG, to close the sequence
        $this->processClosingTag();
        if ($this->tokens[$this->id + 1][0] == T_CLOSE_TAG) {
            $this->id++;
            $closing = $this->tokens[$this->id + 1][1];
        } else {
            $closing = '';
        }

        $this->addLink($id, $this->popExpression(), 'CODE');

        $this->setAtom($id, ['code'     => $this->tokens[$id][1], 
                             'fullcode' => '<?php /**/ '.$closing]);
        $this->pushExpression($id);
        
        return $id;        
    }

    private function processSemicolon() {
        $this->addLink($this->sequence, $this->popExpression(), 'ELEMENT');
    }

    private function processClosingTag() {
        $pop = $this->popExpression();
        if ($this->atoms[$pop]['atom'] != 'Void') {
            $this->addLink($this->sequence, $pop, 'ELEMENT');
        } else {
            $this->pushExpression($pop);
        }

        $this->pushExpression($this->sequence);
        $this->endSequence();
    }

    private function processString() {
        // For functions and constants 
        if ($this->tokens[$this->id + 1][0] == T_OPEN_PARENTHESIS) {
            
            $nameId = $this->addAtom('Identifier');
            $this->setAtom($nameId, ['code'     => $this->tokens[$this->id][1], 
                                     'fullcode' => $this->tokens[$this->id][1] ]);

            $argumentsId = $this->addAtom('Arguments');
            ++$this->id; // Skipping the (        

            $this->pushExpression(); // set Void()
            $fullcode = array();
            while (!in_array($this->tokens[$this->id][0], [T_CLOSE_PARENTHESIS])) {
               $this->processNext();

                if ($this->tokens[$this->id + 1][0] == T_COMMA) {
                    $indexId = $this->popExpression();
                    $this->addLink($argumentsId, $indexId, 'ARGUMENT');
                    $fullcode[] = $this->atoms[$indexId]['fullcode'];

                    ++$this->id; // Skipping the comma ,
                }
            };

            $indexId = $this->popExpression();
            $this->addLink($argumentsId, $indexId, 'ARGUMENT');
            $fullcode[] = $this->atoms[$indexId]['fullcode'];

            $this->setAtom($argumentsId, ['code'     => $this->tokens[$this->id][1], 
                                          'fullcode' => join(', ', $fullcode)]);
//          // Skipping the ) is already done in the loops       

            $functioncallId = $this->addAtom('Functioncall');
            $this->setAtom($functioncallId, ['code'     => $this->atoms[$nameId]['code'], 
                                             'fullcode' => $this->atoms[$nameId]['code'].'('.
                                                           $this->atoms[$argumentsId]['fullcode'].')'
                                            ]);
            $this->addLink($functioncallId, $argumentsId, 'ARGUMENTS');
            $this->addLink($functioncallId, $nameId, 'NAME');

            $this->pushExpression($functioncallId);
            
            return $functioncallId;
        } else {
            $id = $this->addAtom('Identifier');
            $this->setAtom($id, ['code'     => $this->tokens[$this->id][1], 
                                 'fullcode' => $this->tokens[$this->id][1] ]);
            $this->pushExpression($id);
        }
        return $id;
    }
    
    private function processBracket() {
        $id = $this->addAtom('Array');

        $variableId = $this->popExpression();
        $this->addLink($id, $variableId, 'VARIABLE');

        do {
            $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], [T_CLOSE_BRACKET])) ;

        $indexId = $this->popExpression();
        $this->addLink($id, $indexId, 'INDEX');

        $this->setAtom($id, ['code'     => $this->tokens[$this->id][1], 
                             'fullcode' => $this->atoms[$variableId]['fullcode'] . '[' .
                                           $this->atoms[$indexId]['fullcode']    . ']' ]);
        $this->pushExpression($id);

        return $id;
    }

    private function processParenthesis() {
        $parentheseId = $this->addAtom('Parenthesis');

        do {
            $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], [T_CLOSE_PARENTHESIS])) ;

        $indexId = $this->popExpression();
        $this->addLink($parentheseId, $indexId, 'CODE');

        $this->setAtom($parentheseId, ['code'     => $this->tokens[$this->id][1], 
                             'fullcode' => '(' . $this->atoms[$indexId]['fullcode'] . ')' ]);
        $this->pushExpression($parentheseId);
        ++$this->id; // Skipping the )

        return $parentheseId;
    }
    
    //////////////////////////////////////////////////////
    /// processing single tokens
    //////////////////////////////////////////////////////
    private function processSingle($atom) {
        $id = $this->addAtom($atom);
        $this->setAtom($id, ['code'     => $this->tokens[$this->id][1], 
                             'fullcode' => $this->tokens[$this->id][1] ]);
        $this->pushExpression($id);

        return $id;
    }

    private function processVariable() {
        return $this->processSingle('Variable');
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

    //////////////////////////////////////////////////////
    /// processing single operators
    //////////////////////////////////////////////////////
    private function processSingleOperator($atom, $finals, $link) {
        $current = $this->id;

        $operatorId = $this->addAtom($atom);
        $finals = array_merge([T_SEMICOLON, T_CLOSE_TAG, T_COMMA, 
                               T_OPEN_PARENTHESIS, T_CLOSE_PARENTHESIS,
                               T_CLOSE_BRACKET], $finals);
        do {
            $id = $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], $finals)) ;

        $operandId = $this->popExpression();
        
        $this->addLink($operatorId, $operandId, $link);

        $x = ['code'     => $this->tokens[$current][1], 
              'fullcode' => $this->tokens[$current][1] . ' ' .
                            $this->atoms[$operandId]['fullcode']];

        $this->setAtom($operatorId, $x);
        $this->pushExpression($operatorId);
    }

    private function processCast() {
        return $this->processSingleOperator('Cast', $this->getPrecedence($this->tokens[$this->id][0]), 'CAST');
    }

    private function processNot() {
        return $this->processSingleOperator('Not', $this->getPrecedence($this->tokens[$this->id][0]), 'NOT');
    }

    private function processNoscream() {
        return $this->processSingleOperator('Noscream', $this->getPrecedence($this->tokens[$this->id][0]), 'AT');
    }

    //////////////////////////////////////////////////////
    /// processing binary operators
    //////////////////////////////////////////////////////
    private function processAddition() {
        $atom   = 'Addition';
        $finals = $this->getPrecedence($this->tokens[$this->id][0]);
        $current = $this->id;

        $left = $this->popExpression();
        if ($this->atoms[$left]['atom'] == 'Void') {
            $this->pushExpression($left);
            $sign = $this->tokens[$current][1];
            $code = $sign.'1';
            while (in_array($this->tokens[$this->id + 1][0], [T_PLUS, T_MINUS])) {
                ++$this->id;
                $sign = $this->tokens[$this->id][1].$sign;
                $code *= $this->tokens[$this->id][1].'1';
            }

            if ($this->tokens[$this->id + 1][0] == T_LNUMBER || $this->tokens[$this->id + 1][0] == T_DNUMBER) {
                $operandId = $this->processNext();

                $this->atoms[$operandId]['code']     = $code.$this->atoms[$operandId]['code'];
                $this->atoms[$operandId]['fullcode'] = $sign.$this->atoms[$operandId]['fullcode'];

                return $operandId;
            } else {
                do {
                    $operandId = $this->processNext();
                } while (!in_array($this->tokens[$this->id + 1][0], [T_SEMICOLON])) ;

                $this->popExpression();
                for($i = $this->id - 1; $i >= $current; --$i) {
                    $signId = $this->addAtom('Sign');

                    $this->addLink($signId, $operandId, 'SIGN');
                    $x = ['code'     => $this->tokens[$i][1], 
                          'fullcode' => $this->tokens[$i][1] . 
                                        $this->atoms[$operandId]['fullcode']];
                    $this->setAtom($signId, $x);
                    $operandId = $signId;
                }
                $this->pushExpression($signId);
                
                return $signId;
            }
        }
        $additionId = $this->addAtom($atom);
        $this->addLink($additionId, $left, 'LEFT');
        
        $finals = array_merge([T_SEMICOLON, T_CLOSE_TAG, T_COMMA, 
                               T_OPEN_PARENTHESIS, T_CLOSE_PARENTHESIS,
                               T_CLOSE_BRACKET], $finals);
        do {
            $id = $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], $finals)) ;

        $right = $this->popExpression();
        
        $this->addLink($additionId, $right, 'RIGHT');

        $x = ['code'     => $this->tokens[$current][1], 
              'fullcode' => $this->atoms[$left]['fullcode'] . ' ' .
                            $this->tokens[$current][1] . ' ' .
                            $this->atoms[$right]['fullcode']];
        $this->setAtom($additionId, $x);
        $this->pushExpression($additionId);
    }

    private function processOperator($atom, $finals, $links = ['LEFT', 'RIGHT']) {
        $current = $this->id;

        $left = $this->popExpression();
        $additionId = $this->addAtom($atom);
        $this->addLink($additionId, $left, $links[0]);
        
        $finals = array_merge([T_SEMICOLON, T_CLOSE_TAG, T_COMMA, 
                               T_OPEN_PARENTHESIS, T_CLOSE_PARENTHESIS,
                               T_CLOSE_BRACKET], $finals);
        do {
            $id = $this->processNext();
        } while (!in_array($this->tokens[$this->id + 1][0], $finals)) ;

        $right = $this->popExpression();
        
        $this->addLink($additionId, $right, $links[1]);

        $x = ['code'     => $this->tokens[$current][1], 
              'fullcode' => $this->atoms[$left]['fullcode'] . ' ' .
                            $this->tokens[$current][1] . ' ' .
                            $this->atoms[$right]['fullcode']];
        $this->setAtom($additionId, $x);
        $this->pushExpression($additionId);
    }

    private function processAssignation() {
        $this->processOperator('Assignation', $this->getPrecedence($this->tokens[$this->id][0]));
    }

    private function processMultiplication() {
        $this->processOperator('Multiplication', $this->getPrecedence($this->tokens[$this->id][0]));
    }

    private function processPower() {
        $this->processOperator('Power', $this->getPrecedence($this->tokens[$this->id][0]));
    }

    private function processDot() {
        $this->processOperator('Concantenation', $this->getPrecedence($this->tokens[$this->id][0]));
    }

    private function processInstanceof() {
        $this->processOperator('Instanceof', $this->getPrecedence($this->tokens[$this->id][0]), ['VARIABLE', 'CLASS']);
    }

    private function processEcho() {
        // TODO : upgrade this to functioncall
        $this->processOperator('Functioncall', $this->getPrecedence($this->tokens[$this->id][0]));
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

    private function setAtom($atomId, $properties) {
        foreach($properties as $k => $v) {
            $this->atoms[$atomId][$k] = $v;
        }
        return true;
    }

    private function addLink($origin, $destination, $label) {
        $this->links[] = ['origin'      => $origin, 
                          'destination' => $destination, 
                          'label'       => $label];
        return true;
    }

    private function pushExpression($id = 0) {
        if ($id === 0) {
            $id = $this->addAtom('Void');
            $this->setAtom($id, ['code' => 'Void', 'fullcode' => 'Void']);
        } 
        $this->expressions[] = $id;
    }

    private function popExpression() {
        if (count($this->expressions) === 0) {
            $id = $this->addAtom('Void');
            $this->setAtom($id, ['code' => 'Void', 'fullcode' => 'Void']);
        } else {
            $id = array_pop($this->expressions);
        }
        return $id;
    }
    
    private function saveFiles() {
        $fp = fopen('./nodes.g3.csv', 'w+');
        fputcsv($fp, ['id', 'atom', 'code', 'fullcode']);
        foreach($this->atoms as $atom) {
            fputcsv($fp, $atom);
        }
        fclose($fp);

        $files = [];
        foreach($this->links as $link) {
            if (!isset($files[$link['label']])) {
                $files[$link['label']] = fopen('./rels.g3.'.$link['label'].'.csv', 'w+');
                fputcsv($files[$link['label']], ['start', 'end']);
            }
            fputcsv($files[$link['label']], [$link['origin'], $link['destination']]);
        }
        
        foreach($files as $fp) {
            fclose($fp);
        }
    }
    
    private function startSequence() {
        $this->sequence = $this->addAtom('Sequence');
        $this->setAtom($this->sequence, ['code' => ';', 'fullcode' => ' /**/ ']);
        
        $this->sequences[] = $this->sequence;
    }

    private function endSequence() {
        array_pop($this->sequences);
        if (!empty($this->sequences)) {
            $this->sequence = $this->sequences[count($this->sequences) - 1];
        }
    }

    private function getPrecedence($token) {
        static $cache;
        
        if ($cache === null) {
            $cache = [];
            foreach(self::PRECEDENCE as $k1 => $p1) {
                $cache[$k1] = [];
                foreach(self::PRECEDENCE as $k2 => $p2) {
                    if ($p1 < $p2) {
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
