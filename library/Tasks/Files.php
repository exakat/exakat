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

class Files extends Tasks {
    private $config = null;
    
      static public $exts = array('php'      => array('php', 'php3', 'inc', 'tpl', 'phtml', 'tmpl', 'phps', 'ctp'  ),
                                  'images'   => array('jpg', 'gif', 'ico', 'png', 'svg', 'eps', 'psd', 'dot', 'dhp', 'JPG',),
                                  'media'    => array('ttf', 'swf', 'woff', 'eot', 'otf', ),
                                  'text'     => array('xml', 'txt', 'rst', 'md', 'markdown', 'po', 'mo', 'pot', 'dtd', 'TXT',
                                                      'WEBHELP', 'mxml', 'mime', 'latte', 'MIT', 'python', 'text'),
                                  'config'   => array('neon', 'ini', 'yml', 'yaml') ,
                                  'web'      => array('html', 'htm', 'css', 'js', 'json', 'less', 'webloc', 'wsdl',  ),
                                  'document' => array('doc', 'xls', 'docx', 'pdf', 'odt', 'epub', 'book', 'xlsx', 'ods', 'slk' ),
                                  'archives' => array('tgz', 'bz2' ,'z', 'zip', 'gz', 'tar', 'bz', 'tbz', ),
                                  'audio'    => array('mp3', 'fla', 'wav', 'xap', 'ses'),
                                  'video'    => array('avi', 'pxm') ,
                                  'data'     => array('sql', 'properties', 'yml', 'dist', 'csv', 'log', 'profile', 'info', 'module','install',
                                                      'sqlite', 'lang', 'conf', 'config', 'db', 'phar', 'db3', 'neon', 'data', 'ast'),
                                  'prog'     => array('py', 'bat', 'c', 'h', 'twig', 'sh', 'jar', 'java', 'rb', 'phpt', 'sass', 'scss',
                                                      'xsl', 'as', 'cmd','m4', 'dsp', 'sln', 'vcproj', 'w32', 'diff', 'pl', 'dsw', 'am', 'in', 'ac', ),
                                  'misc'     => array('test', 'table', 'dat', 'admin', 'cur', 'git', 'rng', 'bin',  'ser', 'mgc',),
                                  'security' => array('pub', 'pem', 'crt', 'xcf', ),
                     );

    public function run(\Config $config) {
        $dir = $config->project;
        $this->config = $config;

        $stats = array('notCompilable52' => 'N/C',
                       'notCompilable53' => 'N/C',
                       'notCompilable54' => 'N/C',
                       'notCompilable55' => 'N/C',
                       'notCompilable56' => 'N/C',
                       'notCompilable70' => 'N/C',
                       'notCompilable71' => 'N/C',
                       ) ;
        $unknown = array();

        if ($config->project === null) {
            die("Usage : exakat files -p project\nAborting\n");
        } elseif (!file_exists($config->projects_root.'/projects/'.$dir)) {
            die("No such project as '{$config->projects_root}/projects/$dir'\nAborting\n");
        } elseif (!file_exists($config->projects_root.'/projects/'.$dir.'/code/')) {
            die("No code in project '$dir'\nAborting\n");
        }
        
        $this->checkComposer($dir);

        // Actually finding the files
        $ignoreDirs = array();
        foreach($config->ignore_dirs as $ignore) {
            if ($ignore[0] == '/') {
                $d = $config->projects_root.'/projects/'.$dir.'/code'.$ignore;
                if (!file_exists($d)) {
                    continue;
                }
                $d .= '*';
                $ignoreDirs[] = $d;
            } else {
                $ignoreDirs[] = '*'.$ignore.'*';
            }
        }
        if (empty($ignoreDirs)) {
            $regex = '';
        } else {
            $regex = '#^('.join('|', $ignoreDirs).')#';
        }

        $php = new \Phpexec();
        $ignoredFiles = array();

        $d = getcwd();
        chdir($config->projects_root.'/projects/'.$dir.'/code');
        $files = $this->rglob( '.');
        chdir($d);
        $exts = static::$exts['php'];

        foreach($files as $id => &$file) {
            $file = substr($file, 1);
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if (empty($ext)) {
                if ($php->countTokenFromFile($config->projects_root.'/projects/'.$dir.'/code'.$file) < 2) {
                    unset($files[$id]);
                    $ignoredFiles[] = $file;
                }
            } elseif (!in_array($ext, static::$exts['php'])) {
                // selection of extensions
                unset($files[$id]);
                $ignoredFiles[] = $file;
            } elseif (!empty($regex) && preg_match($regex, $file)) {
                // Matching the 'ignored dir' pattern
                unset($files[$id]);
                $ignoredFiles[] = $file;
            } else {
                // Check for compilation
                if ($php->countTokenFromFile($config->projects_root.'/projects/'.$dir.'/code'.$file) < 2) {
                    unset($files[$id]);
                    $ignoredFiles[] = $file;
                }
            }
        }

        $this->datastore->addRow('ignoredFiles', array_map(function ($a) {
                return array('file'   => $a);
            }, $ignoredFiles));
        $this->datastore->addRow('files', array_map(function ($a) {
                return array('file'   => $a);
            }, $files));
        $this->datastore->reload();

        display('Counting files');
        // Also refining the file list with empty, one-tokened and incompilable files.
        $counting = new Phploc($this->gremlin);
        $counting->run($config);
        display('Counted files');
        
        $tmpFileName = tempnam(sys_get_temp_dir(), 'exakatFile');
        file_put_contents($tmpFileName, $config->projects_root.'/projects/'.$dir.'/code'.join("\n$config->projects_root/projects/$dir/code", $files));

        $versions = $config->other_php_versions;

        foreach($versions as $version) {
            display('Check compilation for '.$version);
            $stats['notCompilable'.$version] = -1;
            
            $shell = 'cat '.$tmpFileName.'  | tr "\n" "\0" | xargs -n1 -P5 -0I {} sh -c "'.$config->{'php'.$version}.' -l {} 2>&1" || true';
            $res = trim(shell_exec($shell));

            $resFiles = explode("\n", $res);
            $incompilables = array();
    
            foreach($resFiles as $resFile) {
                if (substr($resFile, 0, 28) == 'No syntax errors detected in') {
                    continue;
                    // do nothing. All is fine.
                } elseif ($resFile == '') {
                    continue;
                    // do nothing. All is fine.
                } elseif (substr($resFile, 0, 17) == 'PHP Parse error: ') {
                    preg_match('#Parse error: (.+?) in (.+?) on line (\d+)#', $resFile, $r);
                    $incompilables[] = array('error' => $r[1], 'file' => str_replace($config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]), 'line' => $r[3]);
                } elseif (substr($resFile, 0, 13) == 'Parse error: ') {
                    // Actually, almost a repeat of the previous. We just ignore it. (Except in PHP 5.4)
                    if ($version == '52' || $version == '71') {
                        preg_match('#Parse error: (.+?) in (.+?) on line (\d+)#', $resFile, $r);
                        $incompilables[] = array('error' => $r[1], 'file' => str_replace($config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]), 'line' => $r[3]);
                    }
                } elseif (substr($resFile, 0, 14) == 'PHP Warning:  ') {
                    preg_match('#PHP Warning:  (.+?) in (.+?) on line (\d+)#', $resFile, $r);
                    $incompilables[] = array('error' => $r[1], 'file' => str_replace($config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]), 'line' => $r[3]);
                } elseif (substr($resFile, 0, 13) == 'Fatal error: ') {
                    preg_match('#Fatal error: (.+?) in (.+?) on line (\d+)#', $resFile, $r);
                    $incompilables[] = array('error' => $r[1], 'file' => str_replace($config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]), 'line' => $r[3]);
                } elseif (substr($resFile, 0, 18) == 'PHP Fatal error:  ') {
                    // Actually, a repeat of the previous. We just ignore it.
                } elseif (substr($resFile, 0, 23) == 'PHP Strict standards:  ') {
                    preg_match('#PHP Strict standards:  (.+?) in (.+?) on line (\d+)#', $resFile, $r);
                    $incompilables[] = array('error' => $r[1], 'file' => str_replace($config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]), 'line' => $r[3]);
                } elseif (substr($resFile, 0, 18) == 'Strict Standards: ') {
                    preg_match('#Strict Standards: (.+?) in (.+?) on line (\d+)#', $resFile, $r);
                    $incompilables[] = array('error' => $r[1], 'file' => str_replace($config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]), 'line' => $r[3]);
                } elseif (substr($resFile, 0, 18) == 'Strict standards: ') {
                    preg_match('#Strict standards: (.+?) in (.+?) on line (\d+)#', $resFile, $r);
                    $incompilables[] = array('error' => $r[1], 'file' => str_replace($config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]), 'line' => $r[3]);
                } elseif (substr($resFile, 0, 22) == 'PHP Strict Standards: ') {
                    preg_match('#PHP Strict Standards: (.+?) in (.+?) on line (\d+)#', $resFile, $r);
                    $incompilables[] = array('error' => $r[1], 'file' => str_replace($config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]), 'line' => $r[3]);
                } elseif (substr($resFile, 0, 17) == 'PHP Deprecated:  ') {
                    preg_match('#PHP Deprecated:  (.+?) in (.+?) on line (\d+)#', $resFile, $r);
                    $incompilables[] = array('error' => $r[1], 'file' => str_replace($config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]), 'line' => $r[3]);
                } elseif (substr($resFile, 0, 12) == 'Deprecated: ') {
                    preg_match('#Deprecated: (.+?) in (.+?) on line (\d+)#', $resFile, $r);
                    $incompilables[] = array('error' => $r[1], 'file' => str_replace($config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]), 'line' => $r[3]);
                } elseif (substr($resFile, 0, 9) == 'Warning: ') {
                    preg_match('#Warning: (.+?) in (.+?) on line (\d+)#', $resFile, $r);
                    $incompilables[] = array('error' => $r[1], 'file' => str_replace($config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]), 'line' => $r[3]);
                } elseif (substr($resFile, 0, 14) == 'Errors parsing') {
                    // ignore (stdout reporting)
                } else {
                    $this->log->log( "\nCouldn't interpret on syntax error : \n" .
                         print_r($resFile, true) .
                         print_r($res, true) .
                         "\n" . __FILE__ . "\n");
                    // Then, ignore it.
                }
            }
    
            $this->datastore->cleanTable('compilation'.$version);
            $this->datastore->addRow('compilation'.$version, $incompilables);
            $stats['notCompilable'.$version] = count($incompilables);
        }

        display('Check short tag (normal pass)');
        $stats['php'] = count($files);
        $shell = 'cat '.$tmpFileName.' | sort | tr "\n" "\0" |  xargs -n1 -P5 -0I '.$config->php.' -d short_open_tag=0 -r "echo count(token_get_all(file_get_contents(\$argv[1]))).\" \$argv[1]\n\";" 2>>/dev/null || true';
        
        $resultNosot = shell_exec($shell);
        $tokens = (int) array_sum(explode("\n", $resultNosot));

        display('Check short tag (with directive activated)');
        $shell = 'cat '.$tmpFileName.' | sort |  tr "\n" "\0" |  xargs -n1 -P5 -0I '.$config->php.' -d short_open_tag=1 -r "echo count(token_get_all(file_get_contents(\$argv[1]))).\" \$argv[1]\n\";" 2>>/dev/null || true ';
        
        $resultSot = shell_exec($shell);
        $tokenssot = (int) array_sum(explode("\n", $resultSot));

        unlink($tmpFileName);

        if ($tokenssot != $tokens) {
            $nosot = explode("\n", trim($resultNosot));
            $nosot2 = array();
            foreach($nosot as $value) {
                list($count, $file) = explode(' ', $value);
                $nosot2[$file] = $count;
            }
            $nosot = $nosot2;
            unset($nosot2);

            $sot = explode("\n", trim($resultSot));
            $sot2 = array();
            foreach($sot as $value) {
                list($count, $file) = explode(' ', $value);
                $sot2[$file] = $count;
            }
            $sot = $sot2;
            unset($sot2);
    
            if (count($nosot) != count($sot)) {
                $this->log->log('Error in short open tag analyze : not the same number of files '.count($nosot).' / '.count($sot).".\n");
                display('Short tag KO');
                $shortOpenTag = array();
                foreach($nosot as $file => $countNoSot) {
                    if ($sot[$file] != $countNoSot) {
                        $shortOpenTag[] = array('file' => str_replace($config->projects_root.'/projects/'.$dir.'/code/', '', $file));
                    }
                }
            }
    
            $this->datastore->addRow('shortopentag', $shortOpenTag);
        } else {
            display('Short tag OK');
        }

        $this->datastore->addRow('hash', $stats);
        
        // check for special files
        display('Check config files');
        $files = glob($config->projects_root.'/projects/'.$dir.'/code/{,.}*', GLOB_BRACE);
        $files = array_map('basename', $files);
        
        $services = json_decode(file_get_contents($config->dir_root.'/data/serviceConfig.json'));

        $configFiles = array();
        foreach($services as $name => $service) {
            $diff = array_intersect((array) $service->file, $files);
            if (!empty($diff)) {
                foreach($diff as $d) {
                    $configFiles[] = array('file'     => $d,
                                           'name'     => $name,
                                           'homepage' => $service->homepage);
                }
            }
        }
        $this->datastore->addRow('configFiles', $configFiles);
        display(print_r($configFiles, true));
        // Composer is check previously

        display('Done');
        
        if ($config->json) {
            if ($unknown) {
                $stats['unknown'] = $unknown;
            }
            echo json_encode($stats);
        } else {
            display_r($stats);
            if ($unknown) {
                display_r($unknown);
            }
        }
        $this->datastore->addRow('hash', array('status' => 'Initproject'));
        $this->checkTokenLimit();
    }
    
    private function checkComposer($dir) {
        // composer.json
        display('Check composer');
        $composerInfo = array();
        if ($composerInfo['composer.json'] = file_exists($this->config->projects_root.'/projects/'.$dir.'/code/composer.json')) {
            $composerInfo['composer.lock'] = file_exists($this->config->projects_root.'/projects/'.$dir.'/code/composer.lock');
            
            $composer = json_decode(file_get_contents($this->config->projects_root.'/projects/'.$dir.'/code/composer.json'));
            
            if (isset($composer->autoload)) {
                $composerInfo['autoload'] = isset($composer->autoload->{'psr-0'}) ? 'psr-0' : 'psr-4';
            } else {
                $composerInfo['autoload'] = false;
            }
            
            if (isset($composer->require)) {
                $this->datastore->addRow('composer', (array) $composer->require);
            }
        }
        $this->datastore->addRow('hash', $composerInfo);
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
