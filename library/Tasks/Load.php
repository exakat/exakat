<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

//use Everyman\Neo4j\Client;

class Load implements Tasks {
    private $log    = null;
    private $php    = null;
    private $config = null;
    
    public function run(\Config $config) {
        $this->config = $config;
        
        $this->log = new \Log('load', $this->config->projects_root.'/projects/'.$this->config->project);

        if (!file_exists($this->config->projects_root.'/projects/'.$this->config->project.'/config.ini')) {
            display('No such project as "'.$this->config->project.'". Aborting');
            die();
        }

        $this->php = new \Phpexec($this->config->phpversion);

        // formerly -q option. Currently, only one loader, via csv-batchimport;
        $this->client = new \Loader\Cypher();
//        $this->client = new \Loader\Csv();

        if ($filename = $this->config->filename) {
            $nbTokens = $this->process_file($filename);
            $nbFiles = 1;

        } elseif ($dirName = $this->config->dirname) {
            $res = $this->process_dir($dirName);
            $nbFiles = $res['files'];
            $nbTokens = $res['tokens'];
    
        } else {
            display('No file to process. Aborting');
            die();
        }

        $this->client->finalize();
        display('Final memory : '.number_format(memory_get_usage()/ pow(2, 20)).'Mb');
    }

    private function process_dir($dir) {
        if (!file_exists($dir)) {
            return array('files' => -1, 'tokens' => -1);
        }

        $ignoreDirs = array();
        if (substr($dir, -1) == '/') { $dir = substr($dir, 0, -1); }
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
            $nbTokens += $this->process_file($file);
        }
        return array('files' => count($files), 'tokens' => $nbTokens);
    }

    private function process_file($filename) {
        display($filename);
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
            display('Ignoring file '.$filename.' as it won\'t compile with the configure PHP version ('.$this->config->phpversion.')');
            return false;
        }
    
        $tokens = $this->php->getTokenFromFile($filename);
        $log['token_initial'] = count($tokens);

        $delimitedStrings = array('T_QUOTE' => 0, 'T_QUOTE_2' => 0, 'T_SHELL_QUOTE' => 0);
        $whiteCode = $this->php->getWhiteCode();
        $tokensNewlines = array();
        $merge = 0;
        $deleted = 0;
        foreach($tokens as $id => $token) {
            if ($this->php->getTokenname($token[0]) == 'T_OPEN_TAG') {
                $merge = $id - $deleted + 1; // presetting the next id.
                $tokensNewlines[$merge]  = substr_count($tokens[$id][1], "\n");
            } elseif ($this->php->getTokenname($token[0]) == 'T_CLOSE_TAG') {
                if (!$merge) {
                    $merge = $id - count($tokensNewlines) + 1; // presetting the next id.
                    $tokensNewlines[$merge]  = substr_count($tokens[$id][1], "\n");
                } else {
                    $tokensNewlines[$merge] += substr_count($tokens[$id][1], "\n");
                }
            } elseif (isset($whiteCode[$token[0]])) {
                if (!$merge) {
                    $merge = $id - $deleted;
                    $tokensNewlines[$merge]  = substr_count($tokens[$id][1], "\n");
                } else {
                    $tokensNewlines[$merge] += substr_count($tokens[$id][1], "\n");
                }
                $x[] = substr_count($tokens[$id][1], "\n");
                $deleted++;
                unset($tokens[$id]);
            } else {
                $merge = false;
            }
        }
    
        $tokens = array_values($tokens);
    
        if (empty($tokens)) {
            return true; // we just ignore the file.
        }

        if (count($tokens) == 1) {
            return true; // we just ignore the file. this is an empty script or a text file
        }

        while ( $this->php->getTokenname($tokens[0][0]) == 'T_OPEN_TAG' &&
                $this->php->getTokenname($tokens[1][0]) == 'T_CLOSE_TAG') {
            unset($tokens[0]);
            unset($tokens[1]);
            $tokens = array_values($tokens);

            if (empty($tokens)) {
                return true; // we just ignore the file.
            }

            if (count($tokens) == 1) {
                return true; // we just ignore the file. this is an empty script or a text file
            }
        }

        if (count($tokens) == 0) {
            echo 'Ignoring file ', $filename, " as it is empty\n";
            return false;
        }
    
        $log['token_cleaned'] = count($tokens);

        $regexIndex = array();
        $regexIndex['INDEX'] = $this->client->makeNode()->setProperty('token', 'INDEX')
                                                  ->setProperty('code', 'Index for INDEX')
                                                  ->setProperty('index', 'true')
                                                  ->save();

        // @doc delete old tokens
        // This index should be only created once. It will hold all the index for files.
        $regexIndex['FILE'] = $this->client->makeNode()->setProperty('token', 'FILE')
                                                 ->setProperty('code', 'Index for FILE')
                                                 ->setProperty('index', 'true')
                                                 ->save();
        $regexIndex['INDEX']->relateTo($regexIndex['FILE'], 'INDEXED');
    
        $regexIndex['CLASS'] = $this->client->makeNode()->setProperty('token', 'CLASS')
                                                  ->setProperty('code', 'Index for CLASS')
                                                  ->setProperty('index', 'true')
                                                  ->save();
        $regexIndex['INDEX']->relateTo($regexIndex['CLASS'], 'INDEXED');

        $regex = \Tokenizer\Token::getTokenizers();

        foreach($regex as $r) {
            $regexIndex[$r] = $this->client->makeNode()->setProperty('token', $r)
                                                 ->setProperty('code', 'Index for '.$r)
                                                 ->setProperty('index', 'true')
                                                 ->save();
            $regexIndex['INDEX']->relateTo($regexIndex[$r], 'INDEXED');
        }

        $regexIndex['S_STRING'] = $this->client->makeNode()->setProperty('token', 'S_STRING')
                                                    ->setProperty('code', 'Index for S_STRING')
                                                    ->setProperty('index', 'true')
                                                    ->save();
        $regexIndex['INDEX']->relateTo($regexIndex['S_STRING'], 'INDEXED');

        $regexIndex['S_ARRAY'] = $this->client->makeNode()->setProperty('token', 'S_ARRAY')
                                                    ->setProperty('code', 'Index for S_ARRAY')
                                                    ->setProperty('index', 'true')
                                                    ->save();
        $regexIndex['INDEX']->relateTo($regexIndex['S_ARRAY'], 'INDEXED');
    
        $regexIndex['DELETE'] = $this->client->makeNode()->setProperty('token', 'DELETE')
                                                   ->setProperty('code', 'Index for DELETE')
                                                   ->setProperty('index', 'true')
                                                   ->save();
        $regexIndex['INDEX']->relateTo($regexIndex['DELETE'], 'INDEXED');

        $regexIndex['ROOT'] = $this->client->makeNode()->setProperty('token', 'ROOT')
                                                 ->setProperty('code', 'Index for ROOT')
                                                 ->setProperty('index', 'true')
                                                 ->save();
        $regexIndex['INDEX']->relateTo($regexIndex['ROOT'], 'INDEXED');

        // load new tokens
        $line = 0;
        $colonTokens = new \Loader\ColonType();
    
        $atoms = array( 'T_STRING'                   => 'Identifier',
                        'T_CONSTANT_ENCAPSED_STRING' => 'String',
                        'T_ENCAPSED_AND_WHITESPACE'  => 'String',
                        'T_INLINE_HTML'              => 'RawString',
                        'T_VARIABLE'                 => 'Variable',
                        'T_STRING_VARNAME'           => 'Variable',
                        'T_LNUMBER'                  => 'Integer',
                        'T_NUM_STRING'               => 'Integer',
                        'T_DNUMBER'                  => 'Float',
                        'T_CLASS_C'                  => 'Magicconstant',
                        'T_FUNC_C'                   => 'Magicconstant',
                        'T_DIR'                      => 'Magicconstant',
                        'T_FILE'                     => 'Magicconstant',
                        'T_LINE'                     => 'Magicconstant',
                        'T_METHOD_C'                 => 'Magicconstant',
                        'T_NS_C'                     => 'Magicconstant',
                        'T_TRAIT_C'                  => 'Magicconstant',
                        'T_CALLABLE'                 => 'Identifer',
                        );
            
    
        $nb = count($tokens);
        $Tid = -1;
        $root = 0;
        $inQuote = false;
        $in_for = 0;
        $dowhiles = array();
        $block_level = 0;
        $regex = \Tokenizer\Token::getTokenizers();
    
        for($id = 0; $id < $nb; $id++) {
            if (empty($tokens[$id])) { continue; }
            $Tid++;
            $token = $tokens[$id];
            $to_index = true;

            if (is_array($token)) {
                $token[3] = $this->php->getTokenname($token[0]);

                $colonTokens->surveyToken($token);
                if ($token[3] == 'T_BREAK' && is_string($tokens[$id + 1]) && $tokens[$id + 1] == ';') {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $token[3])
                                                  ->setProperty('code', $token[1])
                                                  ->setProperty('line', $token[2])->save();

                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $regexIndex['_Break']->relateTo($T[$Tid], 'INDEXED')->save();
                    $previous = $T[$Tid];
                
                    $Tid++;
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                  ->setProperty('code', 'void')
                                                  ->setProperty('fullcode', ' ')
                                                  ->setProperty('line', $line)
                                                  ->setProperty('modifiedBy', 'bin/load13')
                                                  ->setProperty('atom', 'Void')
                                                  ->save();

                    $to_index = false;
                } elseif ($token[3] == 'T_YIELD' && is_string($tokens[$id + 1]) && $tokens[$id + 1] == ';') {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $token[3])
                                                  ->setProperty('code', $token[1])
                                                  ->setProperty('line', $token[2])->save();

                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $regexIndex['_Yield']->relateTo($T[$Tid], 'INDEXED')->save();
                    $previous = $T[$Tid];
                
                    $Tid++;
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                  ->setProperty('code', 'void')
                                                  ->setProperty('fullcode', ' ')
                                                  ->setProperty('line', $line)
                                                  ->setProperty('modifiedBy', 'bin/load13')
                                                  ->setProperty('atom', 'Void')
                                                  ->save();

                    $to_index = false;
                } elseif ($token[3] == 'T_STATIC' && is_string($tokens[$id + 1]) &&
                          $tokens[$id + 1] != '(' && $this->php->getTokenname($tokens[$id - 1][0]) == 'T_NEW') {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $token[3])
                                                  ->setProperty('code', $token[1])
                                                  ->setProperty('line', $token[2])->save();

                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $regexIndex['Functioncall']->relateTo($T[$Tid], 'INDEXED')->save();
                    $previous = $T[$Tid];
                
                    $Tid++;
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                  ->setProperty('code', 'void')
                                                  ->setProperty('fullcode', ' ')
                                                  ->setProperty('line', $line)
                                                  ->setProperty('modifiedBy', 'bin/load21')
                                                  ->setProperty('atom', 'Void')
                                                  ->save();

                    $to_index = false;
                } elseif ($token[3] == 'T_RETURN' && is_array($tokens[$id + 1]) &&
                          $this->php->getTokenname($tokens[$id + 1][0]) == 'T_CLOSE_TAG') {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $token[3])
                                                  ->setProperty('code', $token[1])
                                                  ->setProperty('line', $token[2])->save();

                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $regexIndex['_Return']->relateTo($T[$Tid], 'INDEXED')->save();
                    $previous = $T[$Tid];

                    $Tid++;
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_SEMICOLON')
                                                  ->setProperty('code', ';')
                                                  ->setProperty('fullcode', ';')
                                                  ->setProperty('line', $line)
                                                  ->setProperty('modifiedBy', 'bin/load18')
                                                  ->save();
                    $regexIndex['Sequence']->relateTo($T[$Tid], 'INDEXED')->save();

                    $to_index = false;
                } elseif ($token[3] == 'T_START_HEREDOC' &&
                          is_array($tokens[$id + 1]) &&
                          $this->php->getTokenname($tokens[$id + 1][0]) == 'T_END_HEREDOC') {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $token[3])
                                                  ->setProperty('code', $token[1])
                                                  ->setProperty('fullcode', $token[1])
                                                  ->setProperty('line', $token[2])->save();

                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $regexIndex['Heredoc']->relateTo($T[$Tid], 'INDEXED')->save();
                    $inQuote = true;
                    $previous = $T[$Tid];

                    $Tid++;
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_ENCAPSED_AND_WHITESPACE')
                                                  ->setProperty('atom', 'String')
                                                  ->setProperty('code', ' ')
                                                  ->setProperty('fullcode', ' ')
                                                  ->setProperty('line', $line)
                                                  ->setProperty('modifiedBy', 'bin/load26')
                                                  ->save();

                    $to_index = false;
                } elseif ($token[3] == 'T_OPEN_TAG' && !isset($tokens[$id + 1])) {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $token[3])
                                                  ->setProperty('code', $token[1])
                                                  ->setProperty('line', $token[2])
                                                  ->save();

                    if (isset($previous)) {
                        $previous->relateTo($T[$Tid], 'NEXT')->save();
                    }
                    $regexIndex['Phpcode']->relateTo($T[$Tid], 'INDEXED')->save();
                    $previous = $T[$Tid];
                
                    $Tid++;
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_OPEN_TAG')
                                                  ->setProperty('code', '<?php /* empty, no closing tag */ ?>')
                                                  ->setProperty('fullcode', '<?php /* empty, no closing tag */ ?>')
                                                  ->setProperty('line', $line)
                                                  ->setProperty('atom', 'Phpcode')
                                                  ->setProperty('closing_tag', 'false')
                                                  ->setProperty('modifiedBy', 'bin/load1')
                                                  ->save();

                    $to_index = false;
                } elseif ($token[3] == 'T_OPEN_TAG_WITH_ECHO') {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_OPEN_TAG')
                                                  ->setProperty('code', str_replace('<?=', '<?php', $token[1]))
                                                  ->setProperty('tag', '<?=')
                                                  ->setProperty('line', $token[2])
                                                  ->setProperty('modifiedBy', 'bin/load19a')
                                                  ->save();
                    $regexIndex['Phpcode']->relateTo($T[$Tid], 'INDEXED')->save();
                    if (isset($previous)) {
                        $previous->relateTo($T[$Tid], 'NEXT')->save();
                    }
                    $previous = $T[$Tid];
                
                    $Tid++;
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_ECHO')
                                                  ->setProperty('code', 'echo')
                                                  ->setProperty('line', $line)
                                                  ->setProperty('modifiedBy', 'bin/load19b')
                                                  ->save();
                    $regexIndex['ArgumentsNoParenthesis']->relateTo($T[$Tid], 'INDEXED')->save();
                    $regexIndex['Functioncall']->relateTo($T[$Tid], 'INDEXED')->save();

                } elseif ($token[3] == 'T_CLOSE_TAG' &&
                          isset($tokens[$id + 1]) &&
                          is_array($tokens[$id + 1]) &&
                          $this->php->getTokenname($tokens[$id + 1][0]) == 'T_OPEN_TAG') {
                      
                    if ($previous->getProperty('code') == ':' ||
                       ($previous->getProperty('code') == '{' &&
                        isset($tokens[$id + 2]) &&
                        is_string($tokens[$id + 2]) &&
                        $tokens[$id + 2] == '}')
                       ) {
                        $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                      ->setProperty('code', 'void')
                                                      ->setProperty('fullcode', ' ')
                                                      ->setProperty('line', $line)
                                                      ->setProperty('modifiedBy', 'bin/load24b')
                                                      ->setProperty('atom', 'Void')
                                                      ->save();
                        $previous->relateTo($T[$Tid], 'NEXT')->save();
                        $previous = $T[$Tid];

                        $Tid++;
                    }
                
                    if ( !in_array($previous->getProperty('code'), array(';', '{'))) {
                        $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_SEMICOLON')
                                                      ->setProperty('code', ';')
                                                      ->setProperty('line', $line)
                                                      ->setProperty('modifiedBy', 'bin/load24')
                                                      ->save();
                        $regexIndex['Sequence']->relateTo($T[$Tid], 'INDEXED')->save();

                        $previous->relateTo($T[$Tid], 'NEXT')->save();
                        $previous = $T[$Tid];
                    }
                    $id++;
                    continue;
                } elseif ($token[3] == 'T_CLOSE_TAG' &&
                          isset($tokens[$id + 1]) &&
                          is_array($tokens[$id + 1]) &&
                          $this->php->getTokenname($tokens[$id + 1][0]) == 'T_OPEN_TAG_WITH_ECHO') {

                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_ECHO')
                                                  ->setProperty('code', 'echo')
                                                  ->setProperty('line', $line)
                                                  ->setProperty('modifiedBy', 'bin/load22')
                                                  ->save();
                    $regexIndex['ArgumentsNoParenthesis']->relateTo($T[$Tid], 'INDEXED')->save();
                    $regexIndex['Functioncall']->relateTo($T[$Tid], 'INDEXED')->save();

                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $previous = $T[$Tid];

                    $id++;
                    continue;
                } elseif ($token[3] == 'T_INLINE_HTML' &&
                          $id == 0) {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_SEMICOLON')
                                                  ->setProperty('code', ';')
                                                  ->setProperty('fullcode', ';')
                                                  ->setProperty('line', $line)
                                                  ->setProperty('atom', 'Sequence')
                                                  ->setProperty('modifiedBy', 'bin/load14')
                                                  ->setProperty('root', 'true')
                                                  ->save();
                    $regexIndex['Sequence']->relateTo($T[$Tid], 'INDEXED')->save();

                    $inline =  $this->client->makeNode()->setProperty('token', $token[3])
                                                  ->setProperty('atom', 'RawString')
                                                  ->setProperty('fullcode', $token[1])
                                                  ->setProperty('line', $token[2])
                                                  ->setProperty('rank', 0)
                                                  ->setProperty('modifiedBy', 'bin/load14-2')
                                                  ->setProperty('code', $token[1])
                                                  ->save();

                    $T[$Tid]->relateTo($inline, 'ELEMENT')->save();
                } elseif ($token[3]           == 'T_INLINE_HTML' &&
                          isset($tokens[$id + 1]) &&
                          $this->php->getTokenname($tokens[$id + 1][0]) == 'T_INLINE_HTML') {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_SEMICOLON')
                                                  ->setProperty('code', ';')
                                                  ->setProperty('fullcode', ';')
                                                  ->setProperty('line', $line)
                                                  ->setProperty('atom', 'Sequence')
                                                  ->setProperty('modifiedBy', 'bin/load15')
                                                  ->setProperty('root', 'true')
                                                  ->save();
                    $regexIndex['Sequence']->relateTo($T[$Tid], 'INDEXED')->save();

        // while ici
                    $inline =  $this->client->makeNode()->setProperty('token', $token[3])
                                                  ->setProperty('atom', 'RawString')
                                                  ->setProperty('fullcode', $token[1])
                                                  ->setProperty('line', $token[2])
                                                  ->setProperty('rank', 0)
                                                  ->setProperty('modifiedBy', 'bin/load15-2')
                                                  ->setProperty('code', $token[1])
                                                  ->save();

                    $T[$Tid]->relateTo($inline, 'ELEMENT')->save();
                } elseif ($token[3] == 'T_ELSE' &&
                          is_string($tokens[$id + 1]) &&
                          $tokens[$id + 1] == ';') {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $token[3])
                                                  ->setProperty('code', $token[1])
                                                  ->setProperty('line', $token[2])
                                                  ->setProperty('modifiedBy', 'bin/load25a')
                                                  ->save();

                    $regexIndex['IfthenElse']->relateTo($T[$Tid], 'INDEXED')->save();
                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $previous = $T[$Tid];
               
                    $Tid++;
                    $T[$Tid]   = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                    ->setProperty('code', 'void')
                                                    ->setProperty('fullcode', ' ')
                                                    ->setProperty('line', $line)
                                                    ->setProperty('atom', 'Void')
                                                    ->setProperty('modifiedBy', 'bin/load25b')
                                                    ->save();
                    $to_index = false;
                } else {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $token[3])
                                                  ->setProperty('code', $token[1])
                                                  ->setProperty('line', $token[2]);
                }

                // do.. while special
                if ($token[3] == 'T_DO') {
                    $dowhiles[] = array('node' => $T[$Tid], 'level' => $block_level);
                } elseif ($token[3] == 'T_WHILE') {
                    if (empty($dowhiles)) {
                        $T[$Tid]->setProperty('dowhile', 'false')->save();
                    } else {
                        if ($block_level == $dowhiles[count($dowhiles) - 1]['level']) {
                            $T[$Tid]->setProperty('dowhile', 'true')->save();
                            array_pop($dowhiles);
                        } else {
                            $T[$Tid]->setProperty('dowhile', 'false')->save();
                        }
                    }
                }

                $x = $T[$Tid]->getProperty('code');
                // guessing binary code : one may also use 4, 8 or any chr that is supposed to be non ascii.
                if (strpos($x, chr(2)) !== false) {
                    $T[$Tid]->setProperty('code', 'Binary data <length '.strlen($x).' bytes>');
                }

                $T[$Tid]->save();
            
                if ($token[3] == 'T_STRING' && (strtolower($token[1]) == 'true' || strtolower($token[1]) == 'false')) {
                    $T[$Tid]->setProperty('atom', 'Boolean')
                            ->setProperty('code', $token[1])
                            ->setProperty('fullcode', $token[1])->save();
                    $to_index = ($tokens[$id + 1] == '('); // if the next is (, this may be a function or a method!!
                } elseif ($token[3] == 'T_STRING' && strtolower($token[1]) == 'null') {
                    $T[$Tid]->setProperty('atom', 'Null')
                            ->setProperty('code', $token[1])
                            ->setProperty('fullcode', $token[1])->save();
                    $to_index = ($tokens[$id + 1] == '('); // if the next is (, this may be a function or a method!!
                } elseif (isset($atoms[$token[3]])) {
                    if ($token[3] == 'T_STRING') {
                        $T[$Tid]->setProperty('atom', $atoms[$token[3]])
                                ->setProperty('code', $token[1])
                                ->setProperty('fullcode', $token[1])->save();
                        $regexIndex['S_STRING']->relateTo($T[$Tid], 'INDEXED');

                    } elseif ($token[3] == 'T_STRING_VARNAME') {
                        $T[$Tid]->setProperty('atom', $atoms[$token[3]])
                                ->setProperty('code', '$'.$token[1])
                                ->setProperty('fullcode', '$'.$token[1])
                                ->save();
                    } elseif ($token[3] == 'T_INLINE_HTML' && $id == 0) {
                        // ignore
                    } elseif ($token[3] == 'T_INLINE_HTML' &&
                              isset($tokens[$id + 1]) &&
                              $this->php->getTokenname($tokens[$id + 1][0]) == 'T_INLINE_HTML') {
                        // ignore
                    } elseif ($token[3] == 'T_CONSTANT_ENCAPSED_STRING') {
                        $delimiter = in_array($token[1][0], array("'", '"')) ? $token[1][0] : '';
                        $T[$Tid]->setProperty('delimiter', $delimiter)
                                ->setProperty('noDelimiter', $delimiter == '' ? $token[1] : substr($token[1], 1, -1))
                                ->setProperty('atom', $atoms[$token[3]])
                                ->setProperty('code', $token[1])
                                ->setProperty('fullcode', $token[1])->save();

                        $regexIndex['S_STRING']->relateTo($T[$Tid], 'INDEXED');
                    } else {
                        $T[$Tid]->setProperty('atom', $atoms[$token[3]])
                                ->setProperty('modifiedBy', 'bin/load17')
                                ->setProperty('code', $token[1])
                                ->setProperty('fullcode', $token[1])->save();
                        if ($token[3] == 'T_ENCAPSED_AND_WHITESPACE') {
                            $regexIndex['S_STRING']->relateTo($T[$Tid], 'INDEXED');
                        }
                    }
                }

                if ($token[3] == 'T_CURLY_OPEN' || $token[3] == 'T_DOLLAR_OPEN_CURLY_BRACES') {
                    $block_level++;
                }

                if ($token[3] == 'T_OPEN_CURLY') {
                    $token[3] = 'T_CURLY_OPEN';
                }
                $token_value = $token[3];

                $line = $token[2];
            } else {
    /// case of the token that is not an array

                $colonTokens->surveyToken($token);

                if (isset($tokensNewlines[$id])) {
                    $line += $tokensNewlines[$id];
                    unset($tokensNewlines[$id]);
                }

                $token_value = $this->php->getTokenname($token);
            
                if ($token_value == 'T_OPEN_CURLY') {
                    $block_level ++;
                } elseif ($token_value == 'T_CLOSE_CURLY') {
                    $block_level --;
                }
            
                if (in_array($token_value, array('T_QUOTE', 'T_SHELL_QUOTE'))) {
                    if ($delimitedStrings['T_QUOTE'] % 2 == 1) {
                        if ( $delimitedStrings['T_QUOTE_2'] % 2 == 1) {
                            if (in_array($tokens[$id - 1], array('[', '+', '-', '*', '/', '%', '.', '('))) { // string inside a string !!
                                throw new Exceptions\TooManyLevelInsideAStringException();
                            } else {
                                $delimitedStrings['T_QUOTE_2']++;
                            }
                        } elseif (in_array($tokens[$id - 1], array('[', '+', '-', '*', '/', '%', '.', '('))) { // string inside a string !!
                            $delimitedStrings['T_QUOTE_2']++;
                        } else {
                            $delimitedStrings[$token_value]++;
                        }
                    } else {
                        $delimitedStrings[$token_value]++;
                    }
                
                    if ($delimitedStrings[$token_value] % 2 == 0) {
                        $token .= '_CLOSE';
                        $token_value .= '_CLOSE';
                        $delimitedStrings['T_QUOTE_2'] = 0;
                    } elseif (($delimitedStrings['T_QUOTE'] % 2 == 1   ) &&
                              ($delimitedStrings['T_QUOTE_2'] > 0      ) &&
                              ($delimitedStrings['T_QUOTE_2'] % 2 == 0 )   ) {
                        $token .= '_CLOSE';
                        $token_value .= '_CLOSE';
                    }
                }
            
                if ($token == '{' && $tokens[$id + 1] == '}') {
                    $block_level--;
                    // This will be a structure with Association
                    if ( $tokens[$id - 1] == ')' || (is_array($tokens[$id - 1]) && in_array($this->php->getTokenName($tokens[$id - 1][0]), array('T_STRING', 'T_NAMESPACE', 'T_TRY', 'T_ELSE', 'T_FINALLY')))) {
                        $T[$Tid] = $this->client->makeNode()->setProperty('token', $this->php->getTokenName($token))
                                                            ->setProperty('code', $token)
                                                            ->setProperty('fullcode', '{')
                                                            ->setProperty('line', $line)
                                                            ->setProperty('modifiedBy', 'bin/load12a')
                                                            ->save();
                        if ($type = $this->process_blocks($token_value)) {
                            $T[$Tid]->setProperty('association', $type)->save();
                        }

                        $previous->relateTo($T[$Tid], 'NEXT')->save();
                        $previous = $T[$Tid];

                        $void   = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                     ->setProperty('code', 'void')
                                                     ->setProperty('fullcode', ' ')
                                                     ->setProperty('line', $line)
                                                     ->setProperty('atom', 'Void')
                                                     ->setProperty('modifiedBy', 'bin/load3')
                                                     ->setProperty('rank', 0)
                                                     ->save();

                        $Tid++;
                        $T[$Tid] = $void;
                    } else {
                        $block = $this->client->makeNode()->setProperty('token', $this->php->getTokenName($token))
                                                      ->setProperty('code', $token)
                                                      ->setProperty('fullcode', '{ /**/ } ')
                                                      ->setProperty('line', $line)
                                                      ->setProperty('atom', 'Sequence')
                                                      ->setProperty('rank', 0)
                                                      ->setProperty('block', 'true')
                                                      ->setProperty('bracket', 'true')
                                                      ->setProperty('modifiedBy', 'bin/load12b')
                                                      ->save();

                        $void   = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                     ->setProperty('code', 'void')
                                                     ->setProperty('fullcode', ' ')
                                                     ->setProperty('line', $line)
                                                     ->setProperty('atom', 'Void')
                                                     ->setProperty('modifiedBy', 'bin/load3')
                                                     ->setProperty('rank', 0)
                                                     ->save();

                        $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_SEMICOLON')
                                                     ->setProperty('code', ';')
                                                     ->setProperty('fullcode', ';')
                                                     ->setProperty('line', $line)
                                                     ->setProperty('atom', 'Sequence')
                                                     ->setProperty('modifiedBy', 'bin/load3')
                                                     ->save();
                        $T[$Tid]->relateTo($block, 'ELEMENT')->save();
                        $regexIndex['Sequence']->relateTo($T[$Tid], 'INDEXED')->save();

                        $block->relateTo($void, 'ELEMENT')->save();
                        unset($tokens[$id + 1]);
                    }

                    $to_index = false;
                } elseif ($token == ':'
                          && isset($tokens[$id + 1]) && is_string($tokens[$id + 1])
                          && $tokens[$id + 1] == ';'
                          && isset($tokens[$id + 2]) && is_array($tokens[$id + 2])
                          && in_array($this->php->getTokenname($tokens[$id + 2][0]), array('T_ELSE', 'T_ELSEIF', 'T_ENDIF', 'T_ENDFOR', 'T_ENDFOREACH', 'T_ENDWHILE', 'T_ENDDECLARE'))) {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $this->php->getTokenName($token))
                                                  ->setProperty('code', $token)
                                                  ->setProperty('line', $line)
                                                  ->save();
                    if ($token == ':') {
                        list($label, $value) = $colonTokens->characterizeToken();
                        $T[$Tid]->setProperty($label, $value)->save();
                    }
                    if ($type = $this->process_colon($token_value)) {
                        $T[$Tid]->setProperty('association', $type)->save();
                    }
                    if ($type = $this->process_blocks($token_value)) {
                        $T[$Tid]->setProperty('association', $type)->save();
                    }
                    
                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $previous = $T[$Tid];

                    $Tid++;
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                  ->setProperty('code', 'void')
                                                  ->setProperty('fullcode', ' ')
                                                  ->setProperty('line', $line)
                                                  ->setProperty('atom', 'Void')
                                                  ->setProperty('modifiedBy', 'bin/load5')
                                                  ->save();
                    $to_index = false;
                } elseif ($token == '(' && $tokens[$id + 1] == ')' &&
                          $this->php->getTokenname($tokens[$id - 1][0]) != 'T_HALT_COMPILER') {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $this->php->getTokenName($token))
                                                  ->setProperty('code', $token)
                                                  ->setProperty('line', $line)
                                                  ->save();
                    $regexIndex['Parenthesis']->relateTo($T[$Tid], 'INDEXED')->save();
                    $regexIndex['ArgumentsNoComma']->relateTo($T[$Tid], 'INDEXED')->save();
                    $regexIndex['Typehint']->relateTo($T[$Tid], 'INDEXED')->save();

                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $previous = $T[$Tid];
                
                    $Tid++;
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                  ->setProperty('code', 'void')
                                                  ->setProperty('fullcode', ' ')
                                                  ->setProperty('line', $line)
                                                  ->setProperty('atom', 'Void')
                                                  ->setProperty('modifiedBy', 'bin/load6')
                                                  ->save();

                    $to_index = false;
                } elseif ( ($tokens[$id] == '(' || $tokens[$id] == ';') &&
                                isset($tokens[$id + 1]) && is_string($tokens[$id + 1]) &&
                                ( $tokens[$id + 1] == ';' || $tokens[$id + 1] == ')')) {
                        // This must be after the processing of ( and ) (right above)
                        $T[$Tid] = $this->client->makeNode()->setProperty('token', $this->php->getTokenName($token))
                                                      ->setProperty('code', $token)
                                                      ->setProperty('line', $line)
                                                      ->setProperty('modifiedBy', 'bin/load18a')
                                                      ->save();

                        $regexIndex['Sequence']->relateTo($T[$Tid], 'INDEXED')->save();
                        $previous->relateTo($T[$Tid], 'NEXT')->save();
                        $previous = $T[$Tid];
                
                        $Tid++;
                        $T[$Tid]   = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                        ->setProperty('code', 'void')
                                                        ->setProperty('fullcode', ' ')
                                                        ->setProperty('line', $line)
                                                        ->setProperty('atom', 'Void')
                                                        ->setProperty('modifiedBy', 'bin/load8b')
                                                        ->save();
                        $to_index = false;
                } elseif ( $tokens[$id] == ',' &&
                           isset($tokens[$id + 1]) && is_string($tokens[$id + 1]) && $tokens[$id + 1] == ']') {
                        // This must be [1, 2, ], array short syntax final comma
                        $T[$Tid] = $this->client->makeNode()->setProperty('token', $this->php->getTokenName($token))
                                                      ->setProperty('code', $token)
                                                      ->setProperty('line', $line)
                                                      ->setProperty('modifiedBy', 'bin/load23')
                                                      ->save();
                        $regexIndex['Arguments']->relateTo($T[$Tid], 'INDEXED')->save();

                        $previous->relateTo($T[$Tid], 'NEXT')->save();
                        $previous = $T[$Tid];
                
                        $Tid++;
                        $T[$Tid]   = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                        ->setProperty('code', 'void')
                                                        ->setProperty('fullcode', ' ')
                                                        ->setProperty('line', $line)
                                                        ->setProperty('atom', 'Void')
                                                        ->setProperty('modifiedBy', 'bin/load8b')
                                                        ->save();
                        $to_index = false;
                } elseif ($token == ':' && isset($tokens[$id + 1]) && is_array($tokens[$id + 1])
                          && in_array($this->php->getTokenname($tokens[$id + 1][0]), array('T_ELSE', 'T_ELSEIF', 'T_ENDIF', 'T_ENDFOR', 'T_ENDFOREACH', 'T_ENDWHILE', 'T_ENDDECLARE'))) {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $this->php->getTokenName($token))
                                                  ->setProperty('code', $token)
                                                  ->setProperty('line', $line)
                                                  ->save();
                    if ($token == ':') {
                        list($label, $value) = $colonTokens->characterizeToken();
                        $T[$Tid]->setProperty($label, $value)->save();
                    }
                    if ($type = $this->process_colon($token_value)) {
                        $T[$Tid]->setProperty('association', $type)->save();
                    }
                    if ($type = $this->process_blocks($token_value)) {
                        $T[$Tid]->setProperty('association', $type)->save();
                    }
                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $previous = $T[$Tid];

                    $Tid++;
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                  ->setProperty('code', 'void')
                                                  ->setProperty('fullcode', ' ')
                                                  ->setProperty('line', $line)
                                                  ->setProperty('atom', 'Void')
                                                  ->setProperty('modifiedBy', 'bin/load4')
                                                  ->save();
                } elseif ($token == '{' && $tokens[$id + 1] == ';' && $tokens[$id + 2] == '}' ) {
                    
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $this->php->getTokenName($token))
                                                        ->setProperty('code', $token)
                                                        ->setProperty('line', $line)
                                                        ->save();

                    if ($type = $this->process_blocks($token_value)) {
                        $T[$Tid]->setProperty('association', $type)->save();
                    } else {
                        // Only index for block is not associated
                        $regexIndex['Block']->relateTo($T[$Tid], 'INDEXED')->save();
                    }

                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $previous = $T[$Tid];

                    $void   = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                 ->setProperty('code', 'void')
                                                 ->setProperty('fullcode', ' ')
                                                 ->setProperty('line', $line)
                                                 ->setProperty('modifiedBy', 'bin/load11')
                                                 ->setProperty('atom', 'Void')
                                                 ->setProperty('rank', 0)
                                                 ->save();

                    $sequence = $this->client->makeNode()->setProperty('token', 'T_SEMICOLON')
                                                   ->setProperty('code', ';')
                                                   ->setProperty('fullcode', ';')
                                                   ->setProperty('line', $line)
                                                   ->setProperty('atom', 'Sequence')
                                                   ->setProperty('rank', 0)
                                                   ->save();

                    $Tid++;
                    $T[$Tid] = $sequence;
                    $sequence->relateTo($void, 'ELEMENT')->save();

                    unset($tokens[$id + 1]);
                    $to_index = false;
                } elseif ($token == '{' && $tokens[$id + 1] == ';') {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $this->php->getTokenName($token))
                                                  ->setProperty('code', $token)
                                                  ->setProperty('line', $line)
                                                  ->save();
                    if ($type = $this->process_blocks($token_value)) {
                        $T[$Tid]->setProperty('association', $type)->save();
                    } else {
                        $regexIndex['Block']->relateTo($T[$Tid], 'INDEXED')->save();
                    }

                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $previous = $T[$Tid];
                
                    $Tid++;
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                  ->setProperty('code', 'void')
                                                  ->setProperty('fullcode', ' ')
                                                  ->setProperty('line', $line)
                                                  ->setProperty('atom', 'Void')
                                                  ->setProperty('modifiedBy', 'bin/load7')
                                                  ->save();

                    $to_index = false;
                } elseif ($token == '(' && $tokens[$id + 1] == ',') {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $this->php->getTokenName($token))
                                                  ->setProperty('code', $token)
                                                  ->setProperty('line', $line)
                                                  ->save();

                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $previous = $T[$Tid];
                
                    $Tid++;
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                  ->setProperty('code', 'void')
                                                  ->setProperty('fullcode', ' ')
                                                  ->setProperty('line', $line)
                                                  ->setProperty('modifiedBy', 'bin/load8')
                                                  ->setProperty('atom', 'Void')
                                                  ->save();

                    $to_index = false;
                } elseif ($token == ',' && $tokens[$id + 1] == ')') {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $this->php->getTokenName($token))
                                                  ->setProperty('code', $token)
                                                  ->setProperty('line', $line)
                                                  ->save();

                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $regexIndex['Arguments']->relateTo($T[$Tid], 'INDEXED')->save();
                    $previous = $T[$Tid];
                
                    $Tid++;
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                  ->setProperty('code', 'void')
                                                  ->setProperty('fullcode', ' ')
                                                  ->setProperty('line', $line)
                                                  ->setProperty('modifiedBy', 'bin/load9')
                                                  ->setProperty('atom', 'Void')
                                                  ->save();

                    $to_index = false;
                } elseif ($token == ',' && $tokens[$id + 1] == ',') {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $this->php->getTokenName($token))
                                                  ->setProperty('code', $token)
                                                  ->setProperty('line', $line)
                                                  ->save();

                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $regexIndex['Arguments']->relateTo($T[$Tid], 'INDEXED')->save();
                    $previous = $T[$Tid];
                
                    $Tid++;
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                  ->setProperty('code', 'void')
                                                  ->setProperty('fullcode', ' ')
                                                  ->setProperty('line', $line)
                                                  ->setProperty('modifiedBy', 'bin/load10')
                                                  ->setProperty('atom', 'Void')
                                                  ->save();

                    $to_index = false;
                } else {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $this->php->getTokenName($token))
                                                  ->setProperty('code', $token) // no fullcode at this level!
                                                  ->setProperty('line', $line)
                                                  ->save();
                    if ($token == ':') {
                        list($label, $value) = $colonTokens->characterizeToken();
                        $T[$Tid]->setProperty($label, $value)->save();
                    }
                }
        
    
                $T[$Tid]->save();
            }

            if (!$inQuote && in_array($token_value, array('T_QUOTE', 'T_SHELL_QUOTE', 'T_START_HEREDOC'))) {
                $inQuote = true;
                if (is_array($token)) {
                    $T[$Tid]->setProperty('fullcode', $token[1])->save();
                } else {
                    $T[$Tid]->setProperty('fullcode', $token)->save();
                }
            } elseif ($inQuote && in_array($token_value, array('T_QUOTE_CLOSE', 'T_SHELL_QUOTE_CLOSE', 'T_END_HEREDOC'))) {
                $inQuote = false;
                $T[$Tid]->setProperty('in_quote', 'true')->save();
                if (is_array($token)) {
                    $T[$Tid]->setProperty('fullcode', $token[1])->save();
                } else {
                    $T[$Tid]->setProperty('fullcode', $token)->save();
                }
            }

            if ($inQuote) {
                $T[$Tid]->setProperty('in_quote', 'true')->save();
            }

            if (in_array($token_value, array('T_FOR'))) {
                $in_for = 1;
            }

            if ($in_for > 0) {
                if (in_array($token_value, array('T_OPEN_PARENTHESIS'))) {
                    $in_for++;
                } elseif (in_array($token_value, array('T_CLOSE_PARENTHESIS'))) {
                    $in_for--;
                    if ($in_for == 1) {
                        $in_for = 0;
                    }
                }
            }

            if ($in_for > 1) {
                $T[$Tid]->setProperty('in_for', 'true')->save();
            }
        
            if ($type = $this->process_colon($token_value)) {
                $T[$Tid]->setProperty('association', $type)->save();
            }
            if ($type = $this->process_blocks($token_value)) {
                $T[$Tid]->setProperty('association', $type)->save();
            }

            // test is for booleans.
            if ($to_index) {
                foreach($regex as $r) {
                    $class = "Tokenizer\\$r";
                    if (in_array($token_value, $class::$operators)) {
                        if ($token_value == 'T_OPEN_CURLY') {
                            if (!$T[$Tid]->hasProperty('association')) {
                                $regexIndex[$r]->relateTo($T[$Tid], 'INDEXED')->save();
                            }
                        } elseif ($token_value == 'T_COLON') {
                            if (!$T[$Tid]->hasProperty('association')) {
                                $regexIndex[$r]->relateTo($T[$Tid], 'INDEXED')->save();
                            }
                        } else {
                            $regexIndex[$r]->relateTo($T[$Tid], 'INDEXED')->save();
                        }
                    }
                }
            }
        
            if (!isset($previous)) {
                $previous = $T[$Tid];
            } else {
                $previous->relateTo($T[$Tid], 'NEXT')->save();
                $previous = $T[$Tid];
            
                // Saving on memory, we don't keep the previous ones.
                if ($Tid > 1) {
                    unset($T[$Tid - 1]);
                }
            }
        }

        $end = microtime(true);
        $log['memory_usage'] = memory_get_usage(true);
        $log['memory_max_usage'] = memory_get_peak_usage(true);
        $this->log->log("$filename\t".(($end - $begin)*1000)."\t".join("\t", $log));

        if (!isset($T)) {
            echo "Empty script. Ignoring\n";
            return false;
        }
        $T[0]->setProperty('root', 'true')->save();
    
        $T[-1] = $this->client->makeNode()->setProperty('token', 'T_ROOT')
                                    ->setProperty('code', '/**/')
                                    ->setProperty('hidden', true)
                                    ->save();
        $regexIndex['ROOT']->relateTo($T[-1], 'INDEXED')->save();

        $fileNode = $this->client->makeNode()->setProperty('token', 'T_FILENAME')
                                       ->setProperty('atom', 'File')
                                       ->setProperty('filename', $filename)
                                       ->setProperty('code', $filename)
                                       ->setProperty('fullcode', preg_replace('#^.*?/projects/[^/]+/code/#', '/', $filename))
                                       ->save();
        $fileNode->relateTo($T[0], 'FILE')->save();

    
        $last     = $this->client->makeNode()->setProperty('token', 'T_END')
                                       ->setProperty('code', '/**/')
                                       ->setProperty('line', $line)
                                       ->setProperty('hidden', true)
                                       ->save();

        $T[-1]->relateTo($T[0], 'NEXT')->setProperty('file', $file)->save();
        $previous->relateTo($last, 'NEXT')->setProperty('file', $file)->save();
    
        $last2     = $this->client->makeNode()->setProperty('token', 'T_END')
                                        ->setProperty('code', '/* * */')
                                        ->setProperty('line', $line)
                                        ->setProperty('hidden', true)
                                        ->save();

        $last->relateTo($last2, 'NEXT')->setProperty('file', $file)->save();

        $this->client->save_chunk();
        display('      memory : '.number_format(memory_get_usage()/ pow(2, 20)).'Mb');

        return $Tid;
    }

    private function process_blocks($token_value) {
        static $states = array();
        static $states_id = 0;
        
        if ($token_value == 'T_CLASS' ) {
            $states[] = 'Class';
            $states_id++;
            return '';
        }

        if ($token_value == 'T_FUNCTION' ) {
            $states[] = 'Function';
            $states_id++;
            return '';
        }

        if ($token_value == 'T_FINALLY' ) {
            $states[] = 'Finally';
            $states_id++;
            return '';
        }

        if ($token_value == 'T_CATCH' ) {
            $states[] = 'Catch';
            $states_id++;
            return '';
        }

        if ($token_value == 'T_TRY' ) {
            $states[] = 'Try';
            $states_id++;
            return '';
        }

        if ($token_value == 'T_INTERFACE' ) {
            $states[] = 'Interface';
            $states_id++;
            return '';
        }

        if ($token_value == 'T_TRAIT' ) {
            $states[] = 'Trait';
            $states_id++;
            return '';
        }

        if ($token_value == 'T_OPEN_CURLY' )    {
            if (count($states) > 0) {
                $state = array_pop($states);
                return $state;
            } else {
                return '';
            }
        }
    
        return '';
    }
    
    private function process_colon($token_value) {
        static $states = array();
        static $states_id = 0;
    
        if ($token_value == 'T_QUESTION' ) {
            $states[] = 'Ternary';
            $states_id++;
            return '';
        }

        if ($token_value == 'T_SWITCH' )   {
            $states[] = 'Switch';
            $states_id++;
            return '';
        }
        if ($token_value == 'T_CASE' )     {
            $states[] = 'Case';
            $states_id++;
            return '';
        }
        if ($token_value == 'T_DEFAULT' )  {
            $states[] = 'Default';
            $states_id++;
            return '';
        }

        if ($token_value == 'T_FOR' )      {
            $states[] = 'For';
            $states_id++;
            return '';
        }
        if ($token_value == 'T_FOREACH' )  {
            $states[] = 'Foreach';
            $states_id++;
            return '';
        }
        if ($token_value == 'T_WHILE' )    {
            $states[] = 'While';
            $states_id++;
            return '';
        }

        if ($token_value == 'T_IF' )       {
            $states[] = 'If';
            $states_id++;
            return '';
        }
        if ($token_value == 'T_ELSEIF' )   {
            $states[] = 'Elseif';
            $states_id++;
            return '';
        }
        if ($token_value == 'T_ELSE' )     {
            $states[] = 'Else';
            $states_id++;
            return '';
        }

        if ($token_value == 'T_COLON' )    {
            $state = array_pop($states);
            return $state;
        }
    
        return '';
    }
}

?>
