<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

use Exakat\Phpexec;
use Exakat\Config;
use Exakat\Exceptions\MissingFile;
use Exakat\Exceptions\NoCodeInProject;
use Exakat\Exceptions\NoFileToProcess;
use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\ProjectNeeded;

class Files extends Tasks {
    const CONCURENCE = self::ANYTIME;

    public function run() {
        $dir = $this->config->project;

        $stats = array('notCompilable52' => 'N/C',
                       'notCompilable53' => 'N/C',
                       'notCompilable54' => 'N/C',
                       'notCompilable55' => 'N/C',
                       'notCompilable56' => 'N/C',
                       'notCompilable70' => 'N/C',
                       'notCompilable71' => 'N/C',
                       'notCompilable72' => 'N/C',
                       'notCompilable73' => 'N/C',
                       ) ;
        $unknown = array();

        if ($this->config->project === 'default') {
            throw new ProjectNeeded();
        } elseif (!file_exists($this->config->projects_root.'/projects/'.$dir)) {
            throw new NoSuchProject($this->config->project);
        } elseif (!file_exists($this->config->projects_root.'/projects/'.$dir.'/code/')) {
            throw new NoCodeInProject($this->config->project);
        }

        $this->checkComposer($dir);
        $this->checkLicence($dir);

        $ignoredFiles = array();
        $files = array();
        $tokens = 0;
        self::findFiles($this->config->projects_root.'/projects/'.$dir.'/code', $files, $ignoredFiles, $this->config, $tokens);
        $i = array();
        foreach($ignoredFiles as $file => $reason) {
            $i[] = array('file'   => $file,
                         'reason' => $reason);
        }
        $ignoredFiles = $i;
        $this->datastore->cleanTable('ignoredFiles');
        $this->datastore->addRow('ignoredFiles', $ignoredFiles);

        $this->datastore->addRow('hash', array('files'  => count($files),
                                               'tokens' => $tokens));
        if (empty($files)) {
            throw new NoFileToProcess($this->config->project);
        }

        $tmpFileName = $this->config->projects_root.'/projects/.exakat/files.'.getmypid().'.txt';
        $path = $this->config->projects_root.'/projects/'.$dir.'/code';
        $tmpFiles = array_map(function ($file) use ($path) { return str_replace(array('(', ')', ' ', '$'), array('\\(', '\\)', '\\ ', '\\$'), '.'.$file);}, $files);
        file_put_contents($tmpFileName, implode("\n", $tmpFiles));

        $versions = $this->config->other_php_versions;
        $versions = array('54', '55', '56', '70', '71', '72', '73');

        $missing = array();
        foreach($files as $file) {
            if (!file_exists($path.$file)) {
                $missing[] = $file;
            }
        }
        if (!empty($missing)) {
            throw new MissingFile($missing);
        }

        $analyzingVersion = $this->config->phpversion[0].$this->config->phpversion[2];
        $id = array_search($analyzingVersion, $versions);
        unset($versions[$id]);
        $versions[] = $analyzingVersion;

        foreach($versions as $version) {
            if (empty($this->config->{'php'.$version})) {
                // This version is not defined
                continue; 
            }
            $toRemoveFromFiles = array();
            display('Check compilation for '.$version);
            $stats['notCompilable'.$version] = -1;
            
            $shell = 'cd '.$this->config->projects_root.'/projects/'.$dir.'/code; cat '.$tmpFileName.' | sed "s/>/\\\\\\\\>/g" | tr "\n" "\0" | xargs -0 -n1 -P5 -I {} sh -c "'.$this->config->{'php'.$version}.' -l {} 2>&1 || true "';
            $res = trim(shell_exec($shell));

            $resFiles = explode("\n", $res);
            $incompilables = array();
            
            foreach($resFiles as $resFile) {
                if (substr($resFile, 0, 28) == 'No syntax errors detected in') {
                    continue;
                    // do nothing. All is fine.
                } elseif (trim($resFile) == '') {
                    continue;
                    // do nothing. All is fine.
                } elseif (substr($resFile, 0, 17) == 'PHP Parse error: ') {
                    preg_match('#Parse error: (.+?) in (\./.+?) on line (\d+)#', $resFile, $r);
                    $file = str_replace($this->config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]);
                    if (!isset($toRemoveFromFiles['/'.$file])) {
                        $fileName = str_replace($this->config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]);
                        if (isset($incompilables[$fileName])) {
                            continue;
                        }
                        $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
                        $toRemoveFromFiles['/'.$file] = 1;
                    }
                } elseif (substr($resFile, 0, 13) == 'Parse error: ') {
                    // Actually, almost a repeat of the previous. We just ignore it. (Except in PHP 5.4)
                    if (in_array($version, array('52', '70', '71', '72', '73'))) {
                        preg_match('#Parse error: (.+?) in (\./.+?) on line (\d+)#', $resFile, $r);
                        $fileName = str_replace($this->config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]);
                        if (isset($incompilables[$fileName])) {
                            continue;

                        }
                        $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
                    }
                } elseif (substr($resFile, 0, 14) == 'PHP Warning:  ') {
                    preg_match('#PHP Warning:  (.+?) in (\./.+?) on line (\d+)#', $resFile, $r);
                    $file = str_replace($this->config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]);
                    if (!isset($toRemoveFromFiles['/'.$file])) {
                        $fileName = str_replace($this->config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]);
                        $toRemoveFromFiles['/'.$file] = 1;
                        if (isset($incompilables[$fileName])) {
                            continue;
                        }
                        $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
                    }
                } elseif (substr($resFile, 0, 13) == 'Fatal error: ') {
                    if (preg_match('#Fatal error: (.+?) in (\./.+?) on line (\d+)#', $resFile, $r)) {
                        $fileName = str_replace($this->config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]);
                        if (isset($incompilables[$fileName])) {
                            continue;
                        }
                        $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
                    }
                    // else ignore Fatal error we can't understand
                } elseif (substr($resFile, 0, 18) == 'PHP Fatal error:  ') {
                    // Actually, a repeat of the previous. We just ignore it.
                    continue;
                } elseif (substr($resFile, 0, 23) == 'PHP Strict standards:  ') {
                    preg_match('#PHP Strict standards:  (.+?) in (\./.+?) on line (\d+)#', $resFile, $r);
                    $file = str_replace($this->config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]);
                    if (!isset($toRemoveFromFiles['/'.$file])) {
                        $toRemoveFromFiles['/'.$file] = 1;
                        $fileName = str_replace($this->config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]);
                        if (isset($incompilables[$fileName])) {
                            continue;
                        }
                        $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
                    }
                } elseif (substr($resFile, 0, 18) == 'Strict Standards: ') {
                    preg_match('#Strict Standards: (.+?) in (\./.+?) on line (\d+)#', $resFile, $r);
                    $fileName = str_replace($this->config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]);
                    if (!isset($toRemoveFromFiles['/'.$fileName])) {
                        $toRemoveFromFiles['/'.$fileName] = 1;
                        if (isset($incompilables[$fileName])) {
                            continue;
                        }
                        $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
                    }
                } elseif (substr($resFile, 0, 18) == 'Strict standards: ') {
                    preg_match('#Strict standards: (.+?) in (\./.+?) on line (\d+)#', $resFile, $r);
                    $file = str_replace($this->config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]);
                    if (!isset($toRemoveFromFiles['/'.$file])) {
                        $toRemoveFromFiles['/'.$file] = 1;
                        $fileName = str_replace($this->config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]);
                        if (isset($incompilables[$fileName])) {
                            continue;
                        }
                        $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
                    }
                } elseif (substr($resFile, 0, 22) == 'PHP Strict Standards: ') {
                    preg_match('#PHP Strict Standards: (.+?) in (\./.+?) on line (\d+)#', $resFile, $r);
                    $file = str_replace($this->config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]);
                    if (!isset($toRemoveFromFiles['/'.$file])) {
                        $toRemoveFromFiles['/'.$file] = 1;
                        $fileName = str_replace($this->config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]);
                        if (isset($incompilables[$fileName])) {
                            continue;
                        }
                        $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
                    }
                } elseif (substr($resFile, 0, 17) == 'PHP Deprecated:  ') {
                    preg_match('#PHP Deprecated:  (.+?) in (\./.+?) on line (\d+)#', $resFile, $r);
                    $file = str_replace($this->config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]);
                    if (!isset($toRemoveFromFiles['/'.$file])) {
                        $toRemoveFromFiles['/'.$file] = 1;
                        $fileName = str_replace($this->config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]);
                        if (isset($incompilables[$fileName])) {
                            continue;
                        }
                        $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
                    }
                } elseif (substr($resFile, 0, 12) == 'Deprecated: ') {
                    preg_match('#Deprecated: (.+?) in (\./.+?) on line (\d+)#', $resFile, $r);
                    $file = str_replace($this->config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]);
                    if (!isset($toRemoveFromFiles['/'.$file])) {
                        $toRemoveFromFiles['/'.$file] = 1;
                        $fileName = str_replace($this->config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]);
                        if (isset($incompilables[$fileName])) {
                            continue;
                        }
                        $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
                    }
                } elseif (substr($resFile, 0, 9) == 'Warning: ') {
                    preg_match('#Warning: (.+?) in (\./.+?) on line (\d+)#', $resFile, $r);
                    $fileName = str_replace($this->config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]);
                    if (!isset($toRemoveFromFiles['/'.$fileName])) {
                        $toRemoveFromFiles['/'.$fileName] = 1;
                        if (isset($incompilables[$fileName])) {
                            continue;
                        }
                        $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
                    }
                } elseif (substr($resFile, 0, 8) == 'Notice: ') {
                    preg_match('#Notice: (.+?) in (\./.+?) on line (\d+)#', $resFile, $r);
                    $fileName = str_replace($this->config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]);
                    if (!isset($toRemoveFromFiles['/'.$fileName])) {
                        $toRemoveFromFiles['/'.$fileName] = 1;
                        if (isset($incompilables[$fileName])) {
                            continue;
                        }
                        $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
                    }
                } elseif (substr($resFile, 0, 12) == 'PHP Notice: ') {
                    preg_match('#PHP Notice: (.+?) in (\./.+?) on line (\d+)#', $resFile, $r);
                    $fileName = str_replace($this->config->projects_root.'/projects/'.$dir.'/code/', '', $r[2]);
                    if (!isset($toRemoveFromFiles['/'.$fileName])) {
                        $toRemoveFromFiles['/'.$fileName] = 1;
                        if (isset($incompilables[$fileName])) {
                            continue;
                        }
                        $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
                    }
                } elseif (substr($resFile, 0, 14) == 'Errors parsing') {
                    continue; 
                } else {
                    assert(false,  "'".print_r($resFile, true)."'\n");
                }
            }

            $this->datastore->cleanTable('compilation'.$version);
            $this->datastore->addRow('compilation'.$version, array_values($incompilables));
            $stats['notCompilable'.$version] = count($incompilables);
        }

        $files = array_diff($files, array_keys($toRemoveFromFiles));
        unset($toRemoveFromFiles);

        $this->datastore->cleanTable('files');
        $this->datastore->addRow('files', array_map(function ($a) {
                return array('file'   => $a);
        }, $files));
        $this->datastore->reload();

        display('Check short tag (normal pass)');
        $stats['php'] = count($files);
        $shell = 'cat '.$tmpFileName.' | xargs -n1 -P5 '.$this->config->php.' -d short_open_tag=0 -d error_reporting=0 -r "echo count(token_get_all(file_get_contents(\$argv[1]))).\" \$argv[1]\n\";" 2>>/dev/null || true';

        $resultNosot = shell_exec($shell);
        $tokens = (int) array_sum(explode("\n", $resultNosot));

        display('Check short tag (with directive activated)');
        $shell = 'cat '.$tmpFileName.' |  xargs -n1 -P5 '.$this->config->php.' -d short_open_tag=1 -d error_reporting=0 -r "echo count(@token_get_all(file_get_contents(\$argv[1]))).\" \$argv[1]\n\";" 2>>/dev/null || true ';

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
                        $shortOpenTag[] = array('file' => str_replace($this->config->projects_root.'/projects/'.$dir.'/code/', '', $file));
                    }
                }
                $this->datastore->addRow('shortopentag', $shortOpenTag);
            }
        } else {
            display('Short tag OK');
        }

        $this->datastore->addRow('hash', $stats);

        // check for special files
        display('Check config files');
        $files = glob($this->config->projects_root.'/projects/'.$dir.'/code/{,.}*', GLOB_BRACE);
        $files = array_map('basename', $files);

        $services = json_decode(file_get_contents($this->config->dir_root.'/data/serviceConfig.json'));

        $configFiles = array();
        foreach($services as $name => $service) {
            $diff = array_intersect((array) $service->file, $files);
            foreach($diff as $d) {
                $configFiles[] = array('file'     => $d,
                                       'name'     => $name,
                                       'homepage' => $service->homepage);
            }
        }
        $this->datastore->addRow('configFiles', $configFiles);
        display("\n + ".implode("\n + ", array_map(function ($x) { return $x['file']; }, $configFiles))."\n\n");
        // Composer is check previously

        display('Done');

        if ($this->config->json) {
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

    private function checkLicence($dir) {
        $licenses = parse_ini_file($this->config->dir_root.'/data/license.ini');
        $licenses = $licenses['files'];
        
        $path = $this->config->projects_root.'/projects/'.$dir.'/code';
        foreach($licenses as $file) {
            if (file_exists($path.'/'.$file)) {
                $this->datastore->addRow('hash', array('licence_file' => 'unknown'));

                return true;
            }
        }
        $this->datastore->addRow('hash', array('licence_file' => 'unknown'));
    }
    
    public static function findFiles($path, &$files, &$ignoredFiles, $config, &$tokens) {
        $ignore_dirs = $config->ignore_dirs;
        $dir = $config->project;

        $ignore_files = parse_ini_file($config->dir_root.'/data/ignore_files.ini');
        $ignore_files = array_flip($ignore_files['files']);
        
        // Regex to ignore files and folders
        $ignoreDirs = array();
        foreach($ignore_dirs as $ignore) {
            if ($ignore[0] == '/') {
                $d = $config->projects_root.'/projects/'.$dir.'/code'.$ignore;
                if (!file_exists($d)) {
                    continue;
                }
                $ignoreDirs[] = $ignore.'.*';
            } else {
                $ignoreDirs[] = '.*'.$ignore.'.*';
            }
        }
        if (empty($ignoreDirs)) {
            $ignoreDirsRegex = '';
        } else {
            $ignoreDirsRegex = '#^('.implode('|', $ignoreDirs).')#';
        }

        // Regex to include files and folders
        $includeDirs = array();
        foreach($config->include_dirs as $include) {
            if ($include === '/') { continue; }
            if ($include[0] == '/') {
                $d = $config->projects_root.'/projects/'.$dir.'/code'.$include;
                if (!file_exists($d)) {
                    continue;
                }
                $includeDirs[] = $include.'.*';
            } else {
                $includeDirs[] = '.*'.$include.'.*';
            }
        }
        if (empty($includeDirs)) {
            $includeDirsRegex = '';
        } else {
            $includeDirsRegex = '#^('.implode('|', $includeDirs).')#';
        }

        $php = new Phpexec($config->phpversion, $config);
        $ignoredFiles = array();

        $d = getcwd();
        if (!file_exists($path)) {
            display( "No such file as ".$path." when looking for files\n");
            $files = array();
            $ignoredFiles = array();
            return ;
        }
        chdir($path);
        $files = rglob('.');
        chdir($d);
        $exts = $config->file_extensions;

        foreach($files as $id => &$file) {
            if (isset($ignore_files[basename($file)])) {
                unset($files[$id]);
                $ignoredFiles[$file] = "Ignored file (".basename($file).")";
                continue;
            }
            $file = substr($file, 1); // drop the initial /
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if (!in_array($ext, $exts)) {
                // selection of extensions
                unset($files[$id]);
                $ignoredFiles[$file] = "Ignored extension ($ext)";
                continue;
            }

            if (!empty($includeDirsRegex) && preg_match($includeDirsRegex, $file)) {
                // Matching the 'include dir' pattern
                // it's OK.
                continue;
            } elseif (!empty($ignoreDirsRegex) && preg_match($ignoreDirsRegex, $file)) {
                // Matching the 'ignored dir' pattern
                unset($files[$id]);
                $ignoredFiles[$file] = 'Ignored dir';
            } elseif (($t = $php->countTokenFromFile($path.$file)) < 2) {
                unset($files[$id]);
                $ignoredFiles[$file] = 'Not a PHP File';
            } else {
                $tokens += $t;
            }
        }
    }
}

?>
