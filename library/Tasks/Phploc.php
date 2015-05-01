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

class Phploc implements Tasks {
    public function run(\Config $config) {
        
        $loc = array('comments' => 0,
                     'tokens'   => 0,
                     'total'    => 0,
                     'code'     => 0,
                     'files'    => 0);
        if ($config->project != 'default') {
            $project = $config->project;
            $dirPath = $config->projects_root.'/projects/'.$config->project.'/code';

            if (!file_exists($dirPath)) {
                die("Project '$project' doesn't exists\n");
            }

            $ignoreDirs = array();
            $ignoreName = array();
            foreach($config->ignore_dirs as $ignore) {
                if ($ignore[0] == '/') {
                    $d = $config->projects_root.'/projects/'.$config->project.'/code'.$ignore;
                    if ($toIgnore = glob($d."*")) {
                        foreach($toIgnore as $x) {
                            $ignoreDirs[] = $x;
                        }
                    }
                } else {
                    $ignoreName[] = $ignore;
                }
            }

            $files = $this->readRecursiveDir($dirPath, $ignoreName, $ignoreDirs);
            
            foreach($files as $file) {
                $this->array_add($loc, $this->countLocInFile($file));
            }
            
            $datastore = new \Datastore($config);
            $datastore->addRow('hash', array(array('key' => 'loc',         'value' => $loc['code']),
                                             array('key' => 'locTotal',    'value' => $loc['total']),
                                             array('key' => 'files',       'value' => $loc['files']),
                                             array('key' => 'tokens',      'value' => $loc['tokens']),
//                                             array('key' => 'directories', 'value' => $loc['dirs']),
                                        )
                          );
        } elseif (!empty($config->dirname)) {
            $dirPath = $config->dirname;

            $ignoreDirs = array();
            $ignoreName = array();
            foreach($config->ignore_dirs as $ignore) {
                if ($ignore[0] == '/') {
                    $d = $dirPath.$ignore;
                    if (file_exists($d)) {
                        $ignoreDirs[] = $d;
                    }
                } else {
                    $ignoreName[] = $ignore;
                }
            }

            $files = $this->readRecursiveDir($dirPath, $ignoreName, $ignoreDirs);
            
            foreach($files as $file) {
                $this->array_add($loc, $this->countLocInFile($file));
            }
        } elseif (!empty($config->filename)) {
            $loc = $this->countLocInFile($config->filename);
        } else {
            die("Usage : php exakat phploc <-p project> <-d dirname> <-f filename>\n");
        }
        
        if ($config->json) {
            print json_encode($loc);
        } elseif ($config->verbose) {
            print_r($loc);
        }
    }

    private function readRecursiveDir($dirname, $excludeFiles = array(), $excludeDirs = array()) { 
        $dir = opendir($dirname);
        
        $return = array();
        while(false !== ($file = readdir($dir))) {
            if ($file[0] == '.') { continue; }
            foreach($excludeFiles as $part) {
                if (strpos($file, $part) !== false) { 
                    continue 2; 
                }
            }
            
            if (is_dir($dirname.'/'.$file) && !in_array($dirname.'/'.$file, $excludeDirs)) {
                $return = array_merge($return, $this->readRecursiveDir($dirname.'/'.$file, $excludeFiles, $excludeDirs));
            } else {
                if (substr($file, -4) !== '.php') { continue; }

                $return[] = $dirname.'/'.$file;
            }
        }
        
        return $return;
    } 

    
    private function countLocInFile($filename) {
        $return = array('comments' => 0,
                        'tokens'   => 0,
                        'total'    => 0,
                        'code'     => 0,
                        'files'    => 1);
        
        $res = shell_exec('php -l '.escapeshellarg($filename).' 2>&1');
        if (strpos($res, 'No syntax errors detected in ') === false) {
            display( "$filename can't compile\n");
            $return['files'] = 0;
            return $return;
        }
        
        $lines = array();
        $tokens = token_get_all(file_get_contents($filename));
        
        if (empty($tokens)) {
            display( "$filename is empty\n");
            $return['files'] = 0;
            return $return;
        }

        $line = 0;
        foreach($tokens as $token) {
            if (is_array($token)) {
                $line = $token[2];
                
                $tokenName = token_name($token[0]);
                
                // counting comments
                if ($tokenName == 'T_DOC_COMMENT') {
                    $return['comments'] += substr_count($token[1], "\n") + 1;
                } elseif ($tokenName == 'T_COMMENT') {
                    $return['comments'] ++;
                } elseif ($tokenName == 'T_WHITESPACE') {
                    
                } else {
                    if (isset($lines[$line])) {
                        $lines[$line]++;
                    } else {
                        $lines[$line] = 1;
                    }
                    $return['tokens']++;
                }
            } else {
                $return['tokens']++;
                if (!in_array($token, array('{', '}'))) {
                    if (isset($lines[$line])) {
                        $lines[$line]++;
                    } else {
                        $lines[$line] = 1;
                    }
                }
            }
        }

        if (!isset($token)) {
            print "Unset token in ". $filename."\n";
        }
        if (is_array($token) && ($tokenName == 'T_CLOSE_TAG')) {
            $lines[$line]--;
            if ($lines[$line] == 0) {
                unset($lines[$line]);
                $line--;
            }
        }
        
        $return['total']  = $line;
        $return['code']  = count($lines);
        
        return $return;
    }
    
    private function array_add(&$array1, $array2) {
        foreach($array1 as $k => &$v) {
            $v += $array2[$k];
        }
    }
}

?>
