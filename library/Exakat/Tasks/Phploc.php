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


namespace Exakat\Tasks;

use Exakat\Datastore;
use Exakat\Phpexec;

class Phploc extends Tasks {
    const OK = 0;
    const IGNORED_BY_CONFIG = 1;
    const INCOMPILABLE = 2;
    const EMPTYFILE = 4;
    const ONETOKEN = 8;
    
    public function run(\Exakat\Config $config) {
        
        $loc = array('files'    => 0,
                     'total'    => 0,
                     'tokens'   => 0,
                     'comments' => 0,
                     'code'     => 0);
        $project = $config->project;
        if ($project != 'default') {
            $projectPath = $config->projects_root.'/projects/'.$project;

            if (!file_exists($projectPath)) {
                die("Project '$project' doesn't exist\n");
            }

            if (!file_exists($projectPath.'/datastore.sqlite')) {
                die("Datastore for '$project' doesn't exist. Run 'files' first.\n");
            }
            
            $datastore = new Datastore($config);
            $files = $datastore->getCol('files', 'file');

            foreach($files as $file) {
                $counts = $this->countLocInFile($config->projects_root.'/projects/'.$project.'/code'.$file);
                array_add($loc, $counts);
                
                if ($counts['error'] != self::OK) {
                    $datastore->deleteRow('files', array('file' => $file));
                    $datastore->addRow('ignoredFiles', array(array('file' => $file,
                                                             'reason' => $counts['error'])));
                    display("Finally ignoring $file\n");
                }
            }
            
            $this->datastore->addRow('hash', array(array('key' => 'loc',         'value' => $loc['code']),
                                                   array('key' => 'locTotal',    'value' => $loc['total']),
                                                   array('key' => 'files',       'value' => $loc['files']),
                                                   array('key' => 'tokens',      'value' => $loc['tokens']),
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
                array_add($loc, $this->countLocInFile($file));
            }
        } elseif (!empty($config->filename)) {
            $loc = $this->countLocInFile($config->filename);
        } else {
            die("Usage : php exakat phploc <-p project> <-d dirname> <-f filename>\n");
        }
        
        if ($config->json) {
            print json_encode($loc);
        } elseif ($config->verbose) {
            foreach($loc as $k => $v) {
                print substr("$k        ", 0, 8)." : $v\n";
            }
        }
    }

    private function readRecursiveDir($dirname, $excludeFiles = array(), $excludeDirs = array()) {
        $dir = opendir($dirname);
        
        $r = array();
        while(false !== ($file = readdir($dir))) {
            if ($file[0] == '.') { continue; }
            foreach($excludeFiles as $part) {
                if (strpos($file, $part) !== false) {
                    continue 2;
                }
            }
            
            if (is_dir($dirname.'/'.$file) && !in_array($dirname.'/'.$file, $excludeDirs)) {
                $r = $this->readRecursiveDir($dirname.'/'.$file, $excludeFiles, $excludeDirs);
            } else {
                if (substr($file, -4) !== '.php') {
                    continue;
                }
                $r[] = [$dirname.'/'.$file];
            }
        }
        $return = call_user_func_array('array_merge', $r);
        
        return $return;
    }

    private function countLocInFile($filename) {
        $return = array('comments'   => 0,
                        'whitespace' => 0,
                        'tokens'     => 0,
                        'total'      => 0,
                        'code'       => 0,
                        'files'      => 1);

        $lines = array();
        $php = new Phpexec();
        
        $tokens = $php->getTokenFromFile($filename);

        if (empty($tokens)) {
            display( "$filename is empty\n");
            $return['files'] = 0;
            $return['error'] = self::EMPTYFILE;
            return $return;
        }
        
        // One token if it fails compilation but we don't know the error
        if (count($tokens) == 1) {
            display( "$filename doesn't compile\n");
            $return['files'] = 0;
            $return['error'] = self::INCOMPILABLE;
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
                    ++$return['comments'];
                } elseif ($tokenName == 'T_WHITESPACE') {
                    ++$return['whitespace'];
                } else {
                    if (isset($lines[$line])) {
                        ++$lines[$line];
                    } else {
                        $lines[$line] = 1;
                    }
                    ++$return['tokens'];
                }
            } else {
                ++$return['tokens'];
                if (!in_array($token, array('{', '}'))) {
                    if (isset($lines[$line])) {
                        ++$lines[$line];
                    } else {
                        $lines[$line] = 1;
                    }
                }
            }
        }

        if (is_array($token) && ($tokenName == 'T_CLOSE_TAG')) {
            --$lines[$line];
            if ($lines[$line] == 0) {
                unset($lines[$line]);
                --$line;
            }
        }
        
        $return['total']  = $line;
        $return['code']  = count($lines);
        $return['error']  = self::OK;
        
        return $return;
    }
}

?>
