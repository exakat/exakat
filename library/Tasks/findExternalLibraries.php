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

class findExternalLibraries implements Tasks {
    const WHOLE_DIR  = 1;
    const FILE_ONLY  = 2;
    
    // classic must be in lower case form. 
    private $classic = array('bbq'              => self::WHOLE_DIR,
                             'fpdf'             => self::FILE_ONLY, 
                             'html2pdf'         => self::WHOLE_DIR,
                             'htmlpurifier'     => self::FILE_ONLY,
                             'http_class'       => self::WHOLE_DIR,
                             'idna_convert'     => self::WHOLE_DIR,
                             'magpierss'        => self::WHOLE_DIR,
                             'markdown_parser'  => self::FILE_ONLY,
                             'markdown'         => self::WHOLE_DIR,
                             'mpdf'             => self::WHOLE_DIR,
                             'oauthtoken'       => self::WHOLE_DIR,
                             'passwordhash'     => self::FILE_ONLY,
                             'pchart'           => self::WHOLE_DIR,
                             'pclzip'           => self::FILE_ONLY,
                             'phpexcel'         => self::WHOLE_DIR,
                             'phpmailer'        => self::WHOLE_DIR,
                             'services_json'    => self::FILE_ONLY,
                             'sfyaml'           => self::WHOLE_DIR,
                             'smarty'           => self::WHOLE_DIR,
                             'tcpdf'            => self::WHOLE_DIR,
                             'text_diff'        => self::WHOLE_DIR,
                             'text_highlighter' => self::WHOLE_DIR,
                             'tfpdf'            => self::WHOLE_DIR,
                             'yii'              => self::FILE_ONLY,
                             );

    public function run(\Config $config) {
        $project = $config->project;
        if ($project == 'default') {
            die("Magicnumber needs a -p <project>\nAborting\n");
        }

        if (!file_exists($config->projects_root.'/projects/'.$project.'/')) {
            die("No such project as $project.\nAborting\n");
        }
        $dir = $config->projects_root.'/projects/'.$project.'/code';
        $configFile = $config->projects_root.'/projects/'.$project.'/config.ini';
        $ini = parse_ini_file($configFile);
        
        if ($config->update && isset($ini['findExternalLibraries'])) {
            display('Not updating '.$project.'/config.ini. This tool was already run. Please, clean the file.');
            return true; //Cancel task
        }
    
        $newConfigs = $this->processDir($dir);

        if (count($newConfigs) == 1) {
            display("One external libraries is going to be omitted : ".join(', ', array_keys($newConfigs)));
        } elseif (count($newConfigs)) {
            display(count($newConfigs)." external libraries are going to be omitted : ".join(', ', array_keys($newConfigs)));
        } 
        
        if ($config->update === true && count($newConfigs) > 0) {
             display('Updating '.$project.'/config.ini');
             $ini = file_get_contents($configFile);
             $ini = preg_replace("#(ignore_dirs\[\] = \/.*?\n)\n#is", '$1'."\n".';Ignoring external libraries'."\n".'ignore_dirs[] = '.join("\n".'ignore_dirs[] = ', $newConfigs)."\n;Ignoring external libraries\n\n", $ini);

             $ini .= "\nfindExternalLibraries = 1\n";

             file_put_contents($configFile, $ini);
        } else {
            display('Not updating '.$project.'/config.ini. '.count($newConfigs).' external libraries found');
        }
    }
    
    
    private function processDir($dir) {
       $return = array();
    
       $files = glob($dir.'/*');
       foreach($files as $file) {
           if (is_file($file)) {
               $return = array_merge($this->process($file), $return);
           } elseif (is_dir($file)) {
               $return = array_merge($this->processDir($file), $return);
           } 
           // else should go to LOG
       }
    
        return $return;
    }

    private function process($filename) {
        $code = file_get_contents($filename);
        $tokens = @token_get_all($code);
    
        $return = array();

        foreach($tokens as $id => $token) {
            if (is_string($token)) { continue; }

            if ($token[0] == T_WHITESPACE) { continue; }
            if ($token[0] == T_DOC_COMMENT) { continue; }
            if ($token[0] == T_COMMENT) { continue; }
        
            if ($token[0] == T_CLASS) { 
                if (!is_array($tokens[$id + 2])) { continue; }
                $class = $tokens[$id + 2][1];
                if (!is_string($class)) {
                    // ignoring errors in the parsed code. Should go to log.
                    continue;
                }

                $lclass = strtolower($class);
                if (isset($this->classic[$lclass])) {
                    if ($this->classic[$lclass] == self::WHOLE_DIR) {
                        $return[$class] = dirname(preg_replace('#projects/.*?/code/#', '/', $filename));
                    } elseif ($this->classic[$lclass] == self::ONE_FILE) {
                        $return[$class] = preg_replace('#projects/.*?/code/#', '/', $filename);
                    } else {
                        // This is a coding error
                    }
                }
            }
        }
    
        return $return;
    }
}

?>
