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

class FindExternalLibraries extends Tasks {
    const WHOLE_DIR    = 1;
    const FILE_ONLY    = 2;
    const PARENT_DIR   = 3; // Whole_dir and parent.
    const COMPOSER_DIR = 4; // whole_dir + 4 levels (ex : fzaninoto/faker/src/Faker/Factory.php)

    // classic must be in lower case form.
    private $classic = array();

    public function __construct($gremlin) {
        parent::__construct($gremlin);
    }

    public function run(\Config $config) {
        $project = $config->project;
        if ($project == 'default') {
            die("findextlib needs a -p <project>\nAborting\n");
        }

        if (!file_exists($config->projects_root.'/projects/'.$project.'/')) {
            die("No such project as $project.\nAborting\n");
        }

        $dir = $config->projects_root.'/projects/'.$project.'/code';
        $configFile = $config->projects_root.'/projects/'.$project.'/config.ini';
        $ini = parse_ini_file($configFile);
        
        if ($config->update && isset($ini['FindExternalLibraries'])) {
            display('Not updating '.$project.'/config.ini. This tool was already run. Please, clean the config.ini file in the project directory, before running it again.');
            return; //Cancel task
        }

        $json = json_decode(file_get_contents($config->dir_root.'/data/externallibraries.json'));
        foreach($json as $k => $v) {
            $this->classic[$k] = constant('self::'.$v->ignore);
        }
    
        $d = getcwd();
        chdir($config->projects_root.'/projects/'.$config->project.'/code');
        $files = $this->rglob('.');
        chdir($d);
        
        display('Processing '.count($files).' files');
        if (empty($files)) {
            display('No files to process. Aborting');
            return;
        }
        
        $exts = Files::$exts['php'];
        $r = array();
        foreach($files as $file) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);

            if (!in_array($ext, $exts)) {
                // Ignoring some file extensions for faster processing
                continue;
            } else {
                $s = $this->process($config->projects_root.'/projects/'.$config->project.'/code'.substr($file, 1));
            }
            
            if (!empty($s)) {
                $r[] = $s;
            }
       }

       if (!empty($r)) {
           $newConfigs = call_user_func_array('array_merge', $r);
        } else {
            $newConfigs = array();
        }

        if (count($newConfigs) == 1) {
            display('One external library is going to be omitted : '.implode(', ', array_keys($newConfigs)));
        } elseif (count($newConfigs)) {
            display(count($newConfigs).' external libraries are going to be omitted : '.implode(', ', array_keys($newConfigs)));
        }

        $store = [];
        foreach($newConfigs as $library => $file) {
            $store[] = ['library' => $library,
                        'file'    => $file];
        }

        $this->datastore->cleanTable('externallibraries');
        $this->datastore->addRow('externallibraries', $store);

        if ($config->update === true && count($newConfigs) > 0) {
             display('Updating '.$project.'/config.ini');
             $ini = file_get_contents($configFile);
             $ini = preg_replace("#(ignore_dirs\[\] = \/.*?\n)\n#is", '$1'."\n".';Ignoring external libraries'."\n".'ignore_dirs[] = '.implode("\n".'ignore_dirs[] = ', $newConfigs)."\n;Ignoring external libraries\n\n", $ini);

             $ini .= "\nFindExternalLibraries = 1\n";

             file_put_contents($configFile, $ini);
        } else {
            display('Not updating '.$project.'/config.ini. '.count($newConfigs).' external libraries found');
        }
    }
    
    private function process($filename) {
        $return = array();

        static $php, $t_class, $t_namespace, $t_whitecode;
        if (!isset($php)) {
            $php = new \Phpexec();

            $php->getTokens();
            $t_class = $php->getTokenValue('T_CLASS');
            $t_namespace = $php->getTokenValue('T_NAMESPACE');
            $t_whitecode = $php->getWhiteCode();
        }
        
        $tokens = $php->getTokenFromFile($filename);
        if (count($tokens) == 1) {
            return $return;
        }
        $this->log->log("$filename : ".count($tokens));

        $namespace = '';
        foreach($tokens as $id => $token) {
            if (is_string($token)) { continue; }

            if (in_array($token[0], $t_whitecode))  { continue; }
            
            if ($token[0] == $t_namespace) {
                if (!is_array($tokens[$id + 2])) { continue; }

                // This will only work with one-string namespaces. Might need to upgrade this later to full NSname
                $namespace = strtolower($tokens[$id + 2][1]);
                if (!is_string($namespace)) {
                    // ignoring errors in the parsed code. Should go to log.
                    continue;
                }
                continue;
            }

            if ($token[0] == $t_class && is_array($tokens[$id - 1]) && $tokens[$id - 1][1] != '::') {
                if (!is_array($tokens[$id + 2])) { continue; }
                $class = $tokens[$id + 2][1];
                if (!is_string($class)) {
                    // ignoring errors in the parsed code. Should go to log.
                    continue;
                }

                $lclass = strtolower($class);

                if (isset($this->classic[$lclass])) {
                    if ($this->classic[$lclass] == self::WHOLE_DIR) {
                        $returnPath = dirname(preg_replace('#.*projects/.*?/code/#', '/', $filename));
                    } elseif ($this->classic[$lclass] == self::PARENT_DIR) {
                        $returnPath = dirname(dirname(preg_replace('#.*projects/.*?/code/#', '/', $filename)));
                    } elseif ($this->classic[$lclass] == self::FILE_ONLY) {
                        $returnPath = preg_replace('#.*projects/.*?/code/#', '/', $filename);
                    }
                    if ($returnPath != '/') {
                        $return[$class] = $returnPath;
                    }
                    return $return;
                } elseif (isset($this->classic["$namespace\\$lclass"])) {
                    if ($this->classic[$namespace.'\\'.$lclass] == self::COMPOSER_DIR) {
                        $returnPath = dirname(dirname(dirname(dirname(preg_replace('#.*projects/.*?/code/#', '/', $filename)))));
                    }
                    if ($returnPath != '/') {
                        $return[$class] = $returnPath;
                    }
                    return $return;
                }
            }
        }
        return $return;
    }

    private function rglob($pattern, $flags = 0) {
        $files = glob($pattern.'/*', $flags);
        $dirs  = glob($pattern.'/*', GLOB_ONLYDIR | GLOB_NOSORT);
        $files = array_diff($files, $dirs);

        $subdirs = array($files);
        foreach ($dirs as $dir) {
            $f = $this->rglob($dir, $flags);
            if (!empty($f)) {
                $subdirs[] = $f;
            }
        }

        return call_user_func_array('array_merge', $subdirs);
    }
}

?>
