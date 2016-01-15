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

class Load extends Tasks {
    private $php    = null;
    
    public function run(\Config $config) {
        $this->config = $config;
        
        if (!file_exists($this->config->projects_root.'/projects/'.$this->config->project.'/config.ini')) {
            display('No such project as "'.$this->config->project.'". Aborting');
            die();
        }

        $this->checkTokenLimit();

        $this->php = new \Phpexec($this->config->phpversion);

        // formerly -q option. Currently, only one loader, via csv-batchimport;
        $this->client = new \Loader\Cypher();

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
        $files = $this->datastore->getCol('files', 'file');
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
        if (count($tokens) == 1) {
            display('Ignoring file '.$filename.' as it is not a PHP file (No PHP token found)');
            return false;
        }

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
                ++$deleted;
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

        if (count($tokens) == 0) {
            display( "Ignoring file $filename, as it is empty\n");
            return false;
        }
    
        $log['token_cleaned'] = count($tokens);

        $regexIndex = array(
                    'INDEX' => $this->client->makeNode()->setProperty('token', 'INDEX')
                                                        ->setProperty('code', 'Index for INDEX')
                                                        ->setProperty('index', 'true')
                                                        ->save(),

        // @doc delete old tokens
        // This index should be only created once. It will hold all the index for files.
                    'FILE' => $this->client->makeNode()->setProperty('token', 'FILE')
                                                       ->setProperty('code', 'Index for FILE')
                                                       ->setProperty('index', 'true')
                                                       ->save());
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

        $regexIndex['S_ARRAY'] = $this->client->makeNode()->setProperty('token', 'S_ARRAY')
                                                    ->setProperty('code', 'Index for S_ARRAY')
                                                    ->setProperty('index', 'true')
                                                    ->save();
        $regexIndex['INDEX']->relateTo($regexIndex['S_ARRAY'], 'INDEXED');
    
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

         $tokenToIndex = array('T_BREAK'    => '_Break',
                               'T_CONTINUE' => '_Continue',
                               'T_YIELD'    => '_Yield',
                               'T_RETURN'   => '_Return');
            
    
        $nb = count($tokens);
        $T = array();
        $Tid = -1;
        $inQuote = 0;
        $in_for = 0;
        $dowhiles = array();
        $block_level = 0;
        $regex = \Tokenizer\Token::getTokenizers();
    
        for($id = 0; $id < $nb; ++$id) {
            if (empty($tokens[$id])) { continue; }
            ++$Tid;
            $token = $tokens[$id];
            $to_index = true;

            if (is_array($token)) {
                $token[3] = $this->php->getTokenname($token[0]);
                
                $colonTokens->surveyToken($token);
                // Break, return, Yield ;
                if (in_array($token[3], array_keys($tokenToIndex))      &&
                    is_string($tokens[$id + 1]) &&
                    $tokens[$id + 1] == ';') {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $token[3])
                                                  ->setProperty('code', $token[1])
                                                  ->setProperty('line', $token[2])->save();

                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    
                    $regexIndex[$tokenToIndex[$token[3]]]->relateTo($T[$Tid], 'INDEXED')->save();
                    $previous = $T[$Tid];
                
                    ++$Tid;
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                  ->setProperty('code', 'void')
                                                  ->setProperty('fullcode', ' ')
                                                  ->setProperty('line', $line)
                                                  ->setProperty('modifiedBy', 'bin/load13')
                                                  ->setProperty('atom', 'Void')
                                                  ->save();

                    $to_index = false;

        // TODO : centralize this with RETURN, CONTINUE, etc...
                } elseif (in_array($token[3], array_keys($tokenToIndex))      &&
                          is_array($tokens[$id + 1]) &&
                          $this->php->getTokenname($tokens[$id + 1][0]) == 'T_CLOSE_TAG') {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $token[3])
                                                  ->setProperty('code', $token[1])
                                                  ->setProperty('line', $token[2])->save();

                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $previous = $T[$Tid];
                    $regexIndex[$tokenToIndex[$token[3]]]->relateTo($T[$Tid], 'INDEXED')->save();
                
                    ++$Tid;
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                  ->setProperty('code', 'void')
                                                  ->setProperty('fullcode', ' ')
                                                  ->setProperty('line', $line)
                                                  ->setProperty('modifiedBy', 'bin/load13')
                                                  ->setProperty('atom', 'Void')
                                                  ->save();
                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $previous = $T[$Tid];

                    ++$Tid;
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_SEMICOLON')
                                                        ->setProperty('code', ';')
                                                        ->setProperty('line', $line)
                                                        ->setProperty('modifiedBy', 'bin/load18')
                                                        ->save();
                    $regexIndex['Sequence']->relateTo($T[$Tid], 'INDEXED')->save();

                    $to_index = false;
                } elseif ($token[3] == 'T_STATIC' && is_string($tokens[$id + 1]) &&
                          $tokens[$id + 1] != '(' && $this->php->getTokenname($tokens[$id - 1][0]) == 'T_NEW') {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $token[3])
                                                        ->setProperty('code', $token[1])
                                                        ->setProperty('line', $token[2])->save();

                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $regexIndex['Functioncall']->relateTo($T[$Tid], 'INDEXED')->save();
                    $previous = $T[$Tid];
                
                    ++$Tid;
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                        ->setProperty('code', 'void')
                                                        ->setProperty('fullcode', ' ')
                                                        ->setProperty('line', $line)
                                                        ->setProperty('modifiedBy', 'bin/load21')
                                                        ->setProperty('atom', 'Void')
                                                        ->save();

                    $to_index = false;
                } elseif ($token[3] == 'T_NS_SEPARATOR' ||
                          (($token[3] == 'T_STRING' || $token[3] == 'T_NAMESPACE') && is_array($tokens[$id + 1]) &&
                           'T_NS_SEPARATOR' === $this->php->getTokenname($tokens[$id + 1][0]))) {

                    if ($token[3] == 'T_NS_SEPARATOR') {
                        // Then the NSname itself
                        $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_NS_SEPARATOR')
                                                            ->setProperty('code', '\\')
                                                            ->setProperty('line', $line)
                                                            ->setProperty('atom', 'Nsname')
                                                            ->setProperty('absolutens', 'true')
                                                            ->setProperty('modifiedBy', 'bin/load30')
                                                            ->save();
                        $nsname = $T[$Tid];
                        ++$Tid;
                        $fullcode = '';
                        $rank = -1;
    
                        $theToken = $token[3];
                        $f = 0;
                    } else {
                        // Starting with a a\b\c
                        $rank = 0;
                        $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_STRING')
                                                            ->setProperty('code', $token[1])
                                                            ->setProperty('fullcode', $token[1])
                                                            ->setProperty('line', $line)
                                                            ->setProperty('rank', 0)
                                                            ->setProperty('atom', 'Identifier')
                                                            ->setProperty('modifiedBy', 'bin/load30')
                                                            ->save();
                        ++$Tid;

                        // Then the NSname itself
                        $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_NS_SEPARATOR')
                                                            ->setProperty('code', '\\')
                                                            ->setProperty('line', $line)
                                                            ->setProperty('atom', 'Nsname')
                                                            ->setProperty('modifiedBy', 'bin/load30')
                                                            ->save();
                        $nsname = $T[$Tid];
                        ++$Tid;

                        $nsname->relateTo($T[$Tid - 2], 'SUBNAME')->save();
                        $fullcode = $token[1];

                        $theToken = 'T_NS_SEPARATOR';
                        $f = 1;
                    }

                    while ($theToken == 'T_NS_SEPARATOR') {
                        ++$f;
                        
                        // use a\b\{ grouping }
                        if (is_string($tokens[$id + $f]) && $tokens[$id + $f] == '{') {
                            break 1;
                        }

                        $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_STRING')
                                                            ->setProperty('code', $tokens[$id + $f][1])
                                                            ->setProperty('fullcode', $tokens[$id + $f][1])
                                                            ->setProperty('line', $line)
                                                            ->setProperty('rank', ++$rank)
                                                            ->setProperty('atom', 'Identifier')
                                                            ->setProperty('modifiedBy', 'bin/load30')
                                                            ->save();
                        $nsname->relateTo($T[$Tid], 'SUBNAME')->save();
                        ++$Tid;
                        $fullcode .= '\\' . $tokens[$id + $f][1];

                        ++$f;
                        if (is_array($tokens[$id + $f])) {
                            $theToken = $this->php->getTokenname($tokens[$id + $f][0]);
                        } else {
                            $theToken = 'T_NOT_NS_SEPARATOR';
                        }
                    }
                    
                    $nsname->setProperty('fullcode', $fullcode)
                           ->save();

                    $previous->relateTo($nsname, 'NEXT')->save();
                    $previous = $nsname;
                    $regexIndex['Functioncall']->relateTo($nsname, 'INDEXED')->save();

                    if (is_string($tokens[$id + $f]) && $tokens[$id + $f] == '{') {
                        if (is_array($tokens[$id + $f - 1]) && $this->php->getTokenname($tokens[$id + $f -1][0]) == 'T_NS_SEPARATOR') {
                            $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_NS_SEPARATOR')
                                                                ->setProperty('code', '\\')
                                                                ->setProperty('line', $line)
                                                                ->setProperty('modifiedBy', 'bin/load30')
                                                                ->save();
                            $previous->relateTo($T[$Tid], 'NEXT')->save();
                            $previous = $T[$Tid];
                            ++$Tid;
                        } else {
//                            die('YEPS    ');
                        }
                    }

                    $id += $f - 1;

                    continue;
                } elseif ($token[3] == 'T_OPEN_TAG' &&
                          !isset($tokens[$id + 1])) {
                    if ($previous->getProperty('token') != 'T_SEMICOLON') {
                        $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_SEMICOLON')
                                                            ->setProperty('code', ';')
                                                            ->setProperty('line', $line)
                                                            ->setProperty('modifiedBy', 'bin/load18')
                                                            ->save();
                        $regexIndex['Sequence']->relateTo($T[$Tid], 'INDEXED')->save();

                        $previous->relateTo($T[$Tid], 'NEXT')->save();
                        $previous = $T[$Tid];

                        ++$Tid;
                    }
                    
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_OPEN_TAG')
                                                        ->setProperty('code', '<?php /* empty, no closing tag */ ')
                                                        ->setProperty('fullcode', '<?php /* empty, no closing tag */ ')
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
                
                    ++$Tid;
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_ECHO')
                                                        ->setProperty('code', 'echo')
                                                        ->setProperty('line', $line)
                                                        ->setProperty('modifiedBy', 'bin/load19b')
                                                        ->save();
                    $regexIndex['ArgumentsNoParenthesis']->relateTo($T[$Tid], 'INDEXED')->save();
                    $regexIndex['Functioncall']->relateTo($T[$Tid], 'INDEXED')->save();

                } elseif ($token[3] == 'T_OPEN_TAG' &&
                          isset($tokens[$id + 1]) &&
                          is_string($tokens[$id + 1]) &&
                          $tokens[$id + 1] == ';') {

                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_OPEN_TAG')
                                                        ->setProperty('code', $token[1])
                                                        ->setProperty('tag', '<?php')
                                                        ->setProperty('line', $token[2])
                                                        ->setProperty('modifiedBy', 'bin/load27')
                                                        ->save();
                    $regexIndex['Phpcode']->relateTo($T[$Tid], 'INDEXED')->save();
                    if (isset($previous)) {
                        $previous->relateTo($T[$Tid], 'NEXT')->save();
                    }
                    $previous = $T[$Tid];
                
                    ++$Tid;
                    $T[$Tid]   = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                          ->setProperty('code', 'void')
                                                          ->setProperty('fullcode', ' ')
                                                          ->setProperty('line', $line)
                                                          ->setProperty('atom', 'Void')
                                                          ->setProperty('modifiedBy', 'bin/load27b')
                                                          ->save();
                    $to_index = false;
                } elseif ($token[3] == 'T_OPEN_TAG'  &&
                          isset($tokens[$id + 1])    &&
                          is_array($tokens[$id + 1]) &&
                          $this->php->getTokenname($tokens[$id + 1][0]) == 'T_CLOSE_TAG') {

                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_OPEN_TAG')
                                                  ->setProperty('code', $token[1])
                                                  ->setProperty('tag', '<?php')
                                                  ->setProperty('line', $token[2])
                                                  ->setProperty('modifiedBy', 'bin/load27')
                                                  ->save();
                    $regexIndex['Phpcode']->relateTo($T[$Tid], 'INDEXED')->save();
                    if (isset($previous)) {
                        $previous->relateTo($T[$Tid], 'NEXT')->save();
                    }
                    $previous = $T[$Tid];
                
                    ++$Tid;
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_SEMICOLON')
                                                        ->setProperty('code', ';')
                                                        ->setProperty('fullcode', ';')
                                                        ->setProperty('line', $line)
                                                        ->setProperty('atom', 'Sequence')
                                                        ->setProperty('modifiedBy', 'bin/load24a')
                                                        ->setProperty('root', 'true')
                                                        ->save();
                    $regexIndex['Sequence']->relateTo($T[$Tid], 'INDEXED')->save();
  
                    $void = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                            ->setProperty('code', 'void')
                                            ->setProperty('rank', 0)
                                            ->setProperty('fullcode', ' ')
                                            ->setProperty('line', $line)
                                            ->setProperty('atom', 'Void')
                                            ->setProperty('modifiedBy', 'bin/load24')
                                            ->save();
                    $T[$Tid]->relateTo($void, 'ELEMENT')->save();

                    $to_index = false;
                } elseif ($token[3] == 'T_CLOSE_TAG' &&
                          isset($tokens[$id + 1]) &&
                          is_array($tokens[$id + 1]) &&
                          $this->php->getTokenname($tokens[$id + 1][0]) == 'T_OPEN_TAG'
                          ) {
                            $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_SEMICOLON')
                                                                ->setProperty('code', ';')
                                                                ->setProperty('fullcode', ';')
                                                                ->setProperty('line', $line)
                                                                ->setProperty('atom', 'Sequence')
                                                                ->setProperty('modifiedBy', 'bin/load24a')
                                                                ->setProperty('root', 'true')
                                                                ->save();
                            $regexIndex['Sequence']->relateTo($T[$Tid], 'INDEXED')->save();
                            $previous->relateTo($T[$Tid], 'NEXT')->save();
                            $previous = $T[$Tid];
  
                            $void = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                    ->setProperty('code', 'void')
                                                    ->setProperty('rank', 0)
                                                    ->setProperty('fullcode', ' ')
                                                    ->setProperty('line', $line)
                                                    ->setProperty('atom', 'Void')
                                                    ->setProperty('modifiedBy', 'bin/load24')
                                                    ->save();
                            $T[$Tid]->relateTo($void, 'ELEMENT')->save();
  
                            ++$id;
                            continue;
                } elseif ($token[3] == 'T_CLOSE_TAG' &&
                          isset($tokens[$id + 1]) &&
                          is_array($tokens[$id + 1]) &&
                          $this->php->getTokenname($tokens[$id + 1][0]) == 'T_OPEN_TAG_WITH_ECHO'
                          ) {
                    if (in_array($previous->getProperty('token'), array('T_CLOSE_PARENTHESIS', 'T_CLOSE_BRACKET', 'T_STRING', 'T_CONTINUE', 'T_BREAK'))) {
                          $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_SEMICOLON')
                                                              ->setProperty('code', ';')
                                                              ->setProperty('fullcode', ';')
                                                              ->setProperty('line', $line)
                                                              ->setProperty('modifiedBy', 'bin/load28')
                                                              ->setProperty('root', 'true')
                                                              ->save();
                          $regexIndex['Sequence']->relateTo($T[$Tid], 'INDEXED')->save();
                          $previous->relateTo($T[$Tid], 'NEXT')->save();
                          $previous = $T[$Tid];

                          ++$Tid;
                          $T[$Tid] = $this->client->makeNode()->setProperty('token', $token[3])
                                                  ->setProperty('code', $token[1])
                                                  ->setProperty('fullcode', $token[1])
                                                  ->setProperty('line', $token[2])->save();

                          $to_index = false;
                    }

                    $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_ECHO')
                                                        ->setProperty('code', 'echo')
                                                        ->setProperty('line', $line)
                                                        ->setProperty('modifiedBy', 'bin/load22')
                                                        ->save();
                    $regexIndex['ArgumentsNoParenthesis']->relateTo($T[$Tid], 'INDEXED')->save();
                    $regexIndex['Functioncall']->relateTo($T[$Tid], 'INDEXED')->save();

                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $previous = $T[$Tid];
                    
                    $this->processComma('T_OPEN_TAG_WITH_ECHO');

                    ++$id;
                    continue;
                } elseif ($token[3] == 'T_CLOSE_TAG' &&
                          in_array($previous->getProperty('token'), array('T_CLOSE_PARENTHESIS', 'T_CLOSE_BRACKET', 'T_STRING', 'T_CONTINUE', 'T_BREAK'))) {

                          $T[$Tid] = $this->client->makeNode()->setProperty('token', 'T_SEMICOLON')
                                                              ->setProperty('code', ';')
                                                              ->setProperty('fullcode', ';')
                                                              ->setProperty('line', $line)
                                                              ->setProperty('modifiedBy', 'bin/load28')
                                                              ->setProperty('root', 'true')
                                                              ->save();
                          $regexIndex['Sequence']->relateTo($T[$Tid], 'INDEXED')->save();
                          $previous->relateTo($T[$Tid], 'NEXT')->save();
                          $previous = $T[$Tid];

                          ++$Tid;
                          $T[$Tid] = $this->client->makeNode()->setProperty('token', $token[3])
                                                  ->setProperty('code', $token[1])
                                                  ->setProperty('fullcode', $token[1])
                                                  ->setProperty('line', $token[2])->save();

                          $to_index = false;
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

                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $previous = $T[$Tid];
               
                    ++$Tid;
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
                    $dowhiles[] = array('node' => $T[$Tid],
                                        'level' => $block_level);
                } elseif ($token[3] == 'T_WHILE') {
                    if (!empty($dowhiles)) {
                        if ($block_level == $dowhiles[count($dowhiles) - 1]['level']) {
                            $T[$Tid]->setProperty('association', 'dowhile')->save();
                            array_pop($dowhiles);
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
                    if (in_array($token[3], array('T_STRING', 'T_VARIABLE'))) {
                        $T[$Tid]->setProperty('code', $token[1])->save();
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
                    } else {
                        $T[$Tid]->setProperty('atom', $atoms[$token[3]])
                                ->setProperty('modifiedBy', 'bin/load17')
                                ->setProperty('code', $token[1])
                                ->setProperty('fullcode', $token[1])->save();
                    }
                }

                if ($token[3] == 'T_CURLY_OPEN' || $token[3] == 'T_DOLLAR_OPEN_CURLY_BRACES') {
                    ++$block_level;
                }

                $this->processComma($token[3]);

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
                    ++$block_level;
                } elseif ($token_value == 'T_CLOSE_CURLY') {
                    --$block_level;
                }
            
                if (in_array($token_value, array('T_QUOTE', 'T_SHELL_QUOTE'))) {
                    if ($delimitedStrings['T_QUOTE'] % 2 == 1) {
                        if ( $delimitedStrings['T_QUOTE_2'] % 2 == 1) {
                            if (in_array($tokens[$id - 1], array('[', '+', '-', '*', '/', '%', '.', '('))) { // string inside a string !!
                                throw new Exceptions\TooManyLevelInsideAStringException();
                            } else {
                                ++$delimitedStrings['T_QUOTE_2'];
                            }
                        } elseif (in_array($tokens[$id - 1], array('[', '+', '-', '*', '/', '%', '.', '('))) { // string inside a string !!
                            ++$delimitedStrings['T_QUOTE_2'];
                        } else {
                            ++$delimitedStrings[$token_value];
                        }
                    } else {
                        ++$delimitedStrings[$token_value];
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
                    // This will be a structure with Association
                    if ( $tokens[$id - 1] == ')' || (is_array($tokens[$id - 1]) &&
                        in_array($this->php->getTokenName($tokens[$id - 1][0]), array('T_STRING', 'T_NAMESPACE', 'T_TRY', 'T_ELSE', 'T_FINALLY', 'T_CALLALBLE', 'T_ARRAY')))) {
                        $T[$Tid] = $this->client->makeNode()->setProperty('token', $this->php->getTokenName($token))
                                                            ->setProperty('code', $token)
                                                            ->setProperty('fullcode', '{')
                                                            ->setProperty('line', $line)
                                                            ->setProperty('modifiedBy', 'bin/load12a')
                                                            ->save();
                        if ($type = $this->processBlocks($token_value)) {
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

                        ++$Tid;
                        $T[$Tid] = $void;
                    } else {
                        --$block_level;
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
                          && in_array($this->php->getTokenname($tokens[$id + 2][0]), array('T_ELSE', 'T_ELSEIF', 'T_ENDIF', 'T_ENDFOR', 'T_ENDFOREACH', 'T_ENDWHILE', 'T_ENDDECLARE', 'T_CLOSE_TAG'))) {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $this->php->getTokenName($token))
                                                  ->setProperty('code', $token)
                                                  ->setProperty('line', $line)
                                                  ->save();
                    if ($token == ':') {
                        list($label, $value) = $colonTokens->characterizeToken();
                        $T[$Tid]->setProperty($label, $value)->save();
                    }
                    if ($type = $this->processBlocks($token_value)) {
                        $T[$Tid]->setProperty('association', $type)->save();
                    }
                    
                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $previous = $T[$Tid];

                    ++$Tid;
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
                    $this->processComma($token_value);
                    if ($type = $this->processParenthesis($token_value)) {
                        $T[$Tid]->setProperty('association', $type)->save();
                    } else {
                        $regexIndex['Parenthesis']->relateTo($T[$Tid], 'INDEXED')->save();
                        $regexIndex['ArgumentsNoComma']->relateTo($T[$Tid], 'INDEXED')->save();
                        $regexIndex['Typehint']->relateTo($T[$Tid], 'INDEXED')->save();
                    }

                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $previous = $T[$Tid];
                
                    ++$Tid;
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
                        // This must be after the processing of case with ( and ) (right above)
                        $T[$Tid] = $this->client->makeNode()->setProperty('token', $this->php->getTokenName($token))
                                                            ->setProperty('code', $token)
                                                            ->setProperty('line', $line)
                                                            ->setProperty('modifiedBy', 'bin/load18a')
                                                            ->save();
                        $this->processComma($token_value);
                        // ';' will not be processed by processParenthesis
                        if ($type = $this->processParenthesis($token_value)) {
                            $T[$Tid]->setProperty('association', $type)->save();
                        }

                        if ($in_for < 1) {
                            $regexIndex['Sequence']->relateTo($T[$Tid], 'INDEXED')->save();
                        }
                        $previous->relateTo($T[$Tid], 'NEXT')->save();
                        $previous = $T[$Tid];
                
                        ++$Tid;
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
                
                        ++$Tid;
                        $T[$Tid]   = $this->client->makeNode()->setProperty('token', 'T_VOID')
                                                        ->setProperty('code', 'void')
                                                        ->setProperty('fullcode', ' ')
                                                        ->setProperty('line', $line)
                                                        ->setProperty('atom', 'Void')
                                                        ->setProperty('modifiedBy', 'bin/load8b')
                                                        ->save();
                        $to_index = false;
                } elseif ($token == ':' && isset($tokens[$id + 1]) && is_array($tokens[$id + 1])
                          && in_array($this->php->getTokenname($tokens[$id + 1][0]), array('T_ELSE', 'T_ELSEIF', 'T_ENDIF', 'T_ENDFOR', 'T_ENDFOREACH', 'T_ENDWHILE', 'T_ENDDECLARE', 'T_ENDSWITCH'))) {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $this->php->getTokenName($token))
                                                  ->setProperty('code', $token)
                                                  ->setProperty('line', $line)
                                                  ->save();
                    if ($token == ':') {
                        list($label, $value) = $colonTokens->characterizeToken();
                        $T[$Tid]->setProperty($label, $value)->save();
                    }
                    if ($type = $this->processBlocks($token_value)) {
                        $T[$Tid]->setProperty('association', $type)->save();
                    }
                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $previous = $T[$Tid];

                    ++$Tid;
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

                    if ($type = $this->processBlocks($token_value)) {
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

                    ++$Tid;
                    $T[$Tid] = $sequence;
                    $sequence->relateTo($void, 'ELEMENT')->save();

                    unset($tokens[$id + 1]);
                    $to_index = false;
                } elseif ($token == '{' && $tokens[$id + 1] == ';') {
                    $T[$Tid] = $this->client->makeNode()->setProperty('token', $this->php->getTokenName($token))
                                                  ->setProperty('code', $token)
                                                  ->setProperty('line', $line)
                                                  ->save();
                    if ($type = $this->processBlocks($token_value)) {
                        $T[$Tid]->setProperty('association', $type)->save();
                    } else {
                        $regexIndex['Block']->relateTo($T[$Tid], 'INDEXED')->save();
                    }

                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $previous = $T[$Tid];
                
                    ++$Tid;
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
                    $this->processComma($token_value);
                    if ($type = $this->processParenthesis($token_value)) {
                        $T[$Tid]->setProperty('association', $type)->save();
                    }

                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $previous = $T[$Tid];
                
                    ++$Tid;
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

                    $this->processComma($token_value);
                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $regexIndex['Arguments']->relateTo($T[$Tid], 'INDEXED')->save();
                    $previous = $T[$Tid];
                
                    ++$Tid;
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

                    $this->processComma($token_value);
                    $previous->relateTo($T[$Tid], 'NEXT')->save();
                    $regexIndex['Arguments']->relateTo($T[$Tid], 'INDEXED')->save();
                    $previous = $T[$Tid];
                
                    ++$Tid;
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

                    if (($rank = $this->processComma($token_value)) !== '') {
                        if ($token_value === 'T_COMMA') {
                            $T[$Tid]->setProperty('rank', $rank);
                        }
                        if ($rank > 0 && $in_for == 0) {
                            $to_index = false;
                        }
                    }
                }

                $T[$Tid]->save();
            }

            if (in_array($token_value, array('T_QUOTE', 'T_SHELL_QUOTE', 'T_START_HEREDOC'))) {
                ++$inQuote;
            } elseif ($inQuote && in_array($token_value, array('T_QUOTE_CLOSE', 'T_SHELL_QUOTE_CLOSE', 'T_END_HEREDOC'))) {
                --$inQuote;
                $T[$Tid]->setProperty('in_quote', 'true')->save();
            }

            if ($inQuote) {
                $T[$Tid]->setProperty('in_quote', 'true')->save();
            }

            if (in_array($token_value, array('T_FOR'))) {
                $in_for = 1;
            }

            if ($in_for > 0) {
                if (in_array($token_value, array('T_OPEN_PARENTHESIS'))) {
                    ++$in_for;
                } elseif (in_array($token_value, array('T_CLOSE_PARENTHESIS'))) {
                    --$in_for;
                    if ($in_for == 1) {
                        $in_for = 0;
                    }
                }
            }

            if ($in_for > 1) {
                $T[$Tid]->setProperty('in_for', 'true')->save();
            }
        
            if (!empty($previous) &&
                $previous->getProperty('token') != 'T_DOUBLE_COLON' &&
                $type = $this->processBlocks($token_value)) {
                $T[$Tid]->setProperty('association', $type)->save();
            }

            if ($type = $this->processParenthesis($token_value)) {
                $T[$Tid]->setProperty('association', $type)->save();
            }

            if ($this->processFunctionDefinition($token_value)) {
                $T[$Tid]->setProperty('isFunctionDefinition', 'true')->save();
            }

            // test is for booleans.
            if ($to_index) {
                foreach($regex as $r) {
                    $class = "Tokenizer\\$r";
                    if (in_array($token_value, $class::$operators)) {
                        if (in_array($token_value, array('T_OPEN_CURLY', 'T_OPEN_PARENTHESIS'))) {
                            if (!$T[$Tid]->hasProperty('association')) {
                                $regexIndex[$r]->relateTo($T[$Tid], 'INDEXED')->save();
                            }
                        } elseif ($token_value == 'T_DOUBLE_COLON') {
                            if (is_array($tokens[$id + 1]) &&
                                $this->php->getTokenName($tokens[$id + 1][0]) == 'T_CLASS') {
                                $regexIndex['Staticclass']->relateTo($T[$Tid], 'INDEXED')->save();
                            } else {
                                $regexIndex['Staticproperty']->relateTo($T[$Tid], 'INDEXED')->save();
                            }
                        } elseif ($token_value == 'T_OBJECT_OPERATOR') {
                             $regexIndex['Property']->relateTo($T[$Tid], 'INDEXED')->save();
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
        $this->log->log($filename."\t".(($end - $begin)*1000)."\t".join("\t", $log));

        if (!isset($T)) {
            display( "Empty script. Ignoring\n");
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

        if (!empty($this->processBlocks('T_OPEN_CURLY'))) {
            display( "Alert, all { and } were not flushed in '", $filename, "'\n");
            $this->log->log("Alert, all { and } were not flushed in '", $filename, "'\n");
        }
        if (!empty($this->processBlocks('T_OPEN_PARENTHESIS'))) {
            display( "Alert, all parenthesis were not flushed in '", $filename, "'\n");
            $this->log->log( "Alert, all parenthesis were not flushed in '", $filename, "'\n");
        }
        $this->processComma('T_IGNORE', true);

        $this->client->save_chunk();
        $this->log->log('      memory : '.number_format(memory_get_usage()/ pow(2, 20)).'Mb');

        return $Tid;
    }

    private function processBlocks($tokenValue, $display = false) {
        static $states = array();
        static $statesId = 0;
        
        if ($display) {
            echo "Display\n", var_dump($states, true);
        }
        
        if ($tokenValue == 'T_CLASS' ) {
            $states[] = 'Class';
            ++$statesId;
            return '';
        }

        if ($tokenValue == 'T_FUNCTION' ) {
            $states[] = 'Function';
            ++$statesId;
            return '';
        }

        if ($tokenValue == 'T_FINALLY' ) {
            $states[] = 'Finally';
            ++$statesId;
            return '';
        }

        if ($tokenValue == 'T_USE' ) {
            $states[] = 'Use';
            ++$statesId;
            return '';
        }

        if ($tokenValue == 'T_CATCH' ) {
            $states[] = 'Catch';
            ++$statesId;
            return '';
        }

        if ($tokenValue == 'T_TRY' ) {
            $states[] = 'Try';
            ++$statesId;
            return '';
        }

        if ($tokenValue == 'T_INTERFACE' ) {
            $states[] = 'Interface';
            ++$statesId;
            return '';
        }

        if ($tokenValue == 'T_TRAIT' ) {
            $states[] = 'Trait';
            ++$statesId;
            return '';
        }

        if ($tokenValue == 'T_OPEN_CURLY' )    {
            if (count($states) > 0) {
                $state = array_pop($states);
                return $state;
            } else {
                return '';
            }
        }
        
        if ($tokenValue == 'T_SEMICOLON' &&
            count($states) > 0) {
                if (in_array($states[count($states) - 1], array('Use', 'Function'))) {
                    array_pop($states);
                    return '';
                }
        }
    
        return '';
    }

    private function processParenthesis($tokenValue) {
        static $states = array();
        static $statesId = 0;
        
        if ($tokenValue == 'T_FOR' ) {
            $states[] = 'For';
            ++$statesId;
            return '';
        }

        if ($tokenValue == 'T_FOREACH' ) {
            $states[] = 'Foreach';
            ++$statesId;
            return '';
        }
        
        if ($tokenValue == 'T_WHILE' ) {
            $states[] = 'While';
            ++$statesId;
            return '';
        }

        if ($tokenValue == 'T_SWITCH' ) {
            $states[] = 'Switch';
            ++$statesId;
            return '';
        }

        if ($tokenValue == 'T_DECLARE' ) {
            $states[] = 'Declare';
            ++$statesId;
            return '';
        }

        if ($tokenValue == 'T_CATCH' ) {
            $states[] = 'Catch';
            ++$statesId;
            return '';
        }

        if ($tokenValue == 'T_IF' || $tokenValue == 'T_ELSEIF') {
            $states[] = 'If';
            ++$statesId;
            return '';
        }

        if ($tokenValue == 'T_OPEN_PARENTHESIS' )    {
            if (count($states) > 0) {
                $state = array_pop($states);
                return $state;
            } else {
                return '';
            }
        }

        return '';
    }

    private function processComma($tokenValue, $display = false) {
        static $echoCount         = 0;
        static $parenthesisCount  = 0;
        static $parenthesisId     = 0;
        static $parenthesisStates = array();
        static $commaCount        = array();
        static $isNotFunctioncall = false;
        static $isArray           = false;
        
        if ($display === true) {
            return '';
        }
        
        if (in_array($tokenValue, array('T_PUBLIC', 'T_PRIVATE', 'T_PROTECTED','T_VAR', 'T_CONST', 'T_GLOBAL', 'T_STATIC')) ) {
            $isNotFunctioncall = true;
            return '';
        }

        if (($isNotFunctioncall === true) && $tokenValue === 'T_SEMICOLON' ) {
            $isNotFunctioncall = false;
            return '';
        }

        if ($tokenValue === 'T_ARRAY') {
            ++$isArray;
            return '';
        }

        if (($isArray === true) && $tokenValue === 'T_SEMICOLON' ) {
            $isArray = false;
            return '';
        }

        if ($tokenValue === 'T_COMMA' ) {
            if ($isNotFunctioncall) {
                return '';
            }
            if (isset($commaCount[$parenthesisId])) {
                ++$commaCount[$parenthesisId];
            } else {
                $commaCount[$parenthesisId] = 0;
            }
            return $commaCount[$parenthesisId];
        }

        if ($tokenValue === 'T_OPEN_PARENTHESIS' ) {
            $parenthesisStates[] = $parenthesisId;
            ++$parenthesisCount;
            $parenthesisId       = $parenthesisCount;
            return '';
        }

        if ($tokenValue === 'T_CLOSE_PARENTHESIS' ) {
            $parenthesisId = array_pop($parenthesisStates);
            return '';
        }

        // Handle cases of arrays [1,2,3]
        if ($tokenValue === 'T_OPEN_BRACKET' ) {
            $parenthesisStates[] = $parenthesisId;
            ++$parenthesisCount;
            $parenthesisId       = $parenthesisCount;
            return '';
        }

        if ($tokenValue === 'T_CLOSE_BRACKET' ) {
            $parenthesisId = array_pop($parenthesisStates);
            return '';
        }

        // Handle cases of echo
        if ($tokenValue === 'T_ECHO' || $tokenValue === 'T_OPEN_TAG_WITH_ECHO') {
            $parenthesisStates[] = $parenthesisId;
            ++$parenthesisCount;
            $parenthesisId       = $parenthesisCount;
            ++$echoCount;
            return '';
        }

        return '';
    }
    
    private function processFunctionDefinition($tokenValue) {
        static $inFunction  = false;
        static $parenthesisLevel = 0;
        
        if ($tokenValue === 'T_FUNCTION' ) {
            $inFunction = true;
            $parenthesisLevel = 0;
            return true;
        }
        
        if (!$inFunction) {
            return false;
        }

        // Only level 1, deeper means expression

        if ($tokenValue === 'T_OPEN_PARENTHESIS') {
            ++$parenthesisLevel;
            return $parenthesisLevel == 1;
        }

        if ($tokenValue === 'T_CLOSE_PARENTHESIS') {
            --$parenthesisLevel;
            if ($parenthesisLevel == 0) {
                $inFunction = false;
            }
            return $parenthesisLevel == 1;
        }
        
        return $parenthesisLevel == 1;
    }
}

?>
