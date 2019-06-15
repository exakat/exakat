<?php
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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
use Exakat\Vcs\Vcs;

class Files extends Tasks {
    const CONCURENCE = self::ANYTIME;

    public function run() {
        $stats = array();
        foreach(Config::PHP_VERSIONS as $version) {
            $stats["notCompilable$version"] = 'N/C';
        }

        if ($this->config->inside_code === Config::INSIDE_CODE) {
            // OK
        } elseif ($this->config->project === 'default') {
            throw new ProjectNeeded();
        } elseif (!file_exists($this->config->project_dir)) {
            throw new NoSuchProject($this->config->project);
        } elseif (!file_exists($this->config->code_dir)) {
            throw new NoCodeInProject($this->config->project);
        }

        $this->checkComposer($this->config->code_dir);
        $this->checkLicence($this->config->code_dir);

        $ignoredFiles = array();
        $files = array();
        $tokens = 0;

        display( "Searching for files \n");
        self::findFiles($this->config->code_dir, $files, $ignoredFiles, $this->config);
        display('Found ' . count($files) . " files.\n");

        $tmpFileName = "{$this->config->tmp_dir}/files{$this->config->pid}.txt";
        $tmpFiles = array_map(function ($file) {
            return str_replace(array('\\', '(', ')', ' ', '$', '<', "'", '"', ';', '&', '`', '|', "\t"),
                               array('\\\\', '\\(', '\\)', '\\ ', '\\$', '\\<', "\\'", '\\"', '\\;', '\\&', '\\`', '\\|', "\\\t", ),
                               ".$file");
                               }, $files);
        file_put_contents($tmpFileName, implode("\n", $tmpFiles));

        $versions = Config::PHP_VERSIONS;

        $missing = array();
        foreach($files as $file) {
            if (!file_exists($this->config->code_dir . $file)) {
                $missing[] = $file;
            }
        }

        if (!empty($missing)) {
            throw new MissingFile($missing);
        }

        $analyzingVersion = $this->config->phpversion[0] . $this->config->phpversion[2];
        $this->datastore->cleanTable("compilation$analyzingVersion");
        $id = array_search($analyzingVersion, $versions);
        unset($versions[$id]);

        $toRemoveFromFiles = array();
        foreach($versions as $version) {
            if (empty($this->config->{"php$version"})) {
                // This version is not defined
                continue;
            }

            display("Check compilation for $version");
            $stats["notCompilable$version"] = -1;

            $php = new Phpexec("php$version", $this->config->{"php$version"});
            $resFiles = $php->compileFiles($this->config->project_dir, $tmpFileName);

            $incompilables = array();

            foreach($resFiles as $resFile) {
                if (trim($resFile) == '') {
                    continue; // do nothing. All is fine.
                }
                
                if ($php->isError($resFile)) {
                    $error = $php->getError();

                    $file = trim($error['file'], '.');
                    $error['file'] = $file;

                    $incompilables[$file] = $error;
                    $toRemoveFromFiles["/$file"] = 1;
                }
            }

            $this->datastore->cleanTable("compilation$version");
            $this->datastore->addRow("compilation$version", array_values($incompilables));
            $stats["notCompilable$version"] = count($incompilables);
        }

        $files = array_diff($files, array_keys($toRemoveFromFiles));
        unset($toRemoveFromFiles);

        display('Check short tag (normal pass)');
        $shell = "cd {$this->config->code_dir}; cat $tmpFileName | xargs -n1 -P5 {$this->config->php} -d short_open_tag=0 -d error_reporting=0 -r \"echo count(token_get_all(file_get_contents(\$argv[1]))).\" \$argv[1]\n\";\" 2>>/dev/null || true";

        $resultNosot = shell_exec($shell);
        $tokens = (int) array_sum(explode("\n", $resultNosot));

        display('Check short tag (with directive activated)');
        $shell = "cd {$this->config->code_dir}; cat $tmpFileName |  xargs -n1 -P5 " . $this->config->php . ' -d short_open_tag=1 -d error_reporting=0 -r "echo count(@token_get_all(file_get_contents(\$argv[1]))).\" \$argv[1]\n\";" 2>>/dev/null || true ';

        $resultSot = shell_exec($shell);
        $tokenssot = (int) array_sum(explode("\n", $resultSot));

        // Extra check
        if (file_exists($tmpFileName)) {
            unlink($tmpFileName);
        }

        if ($tokenssot === $tokens) {
            display('Short tag OK');
        } else {
            $nosot = explode("\n", trim($resultNosot));
            $nosot2 = array();
            foreach($nosot as $value) {
                if (strpos($value, ' ') === false) {
                    continue;
                }
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

            if (count($nosot) === count($sot)) {
                display('Short tag has diff');
                $shortOpenTag = array();
                foreach($nosot as $file => $countNoSot) {
                    if ($sot[$file] != $countNoSot) {
                        $file = trim($file, '.');
                        $shortOpenTag[] = array('file' => trim($file, '.'));
                        if (ini_get('short_open_tag') == false) {
                            $ignoredFiles[$file] = 'Uses short tags';
                            $id = array_search($file, $files);
                            unset($files[$id]);
                        }
                    }
                }
                $this->datastore->addRow('shortopentag', $shortOpenTag);
            } else {
                $this->log->log('Error in short open tag analyze : not the same number of files ' . count($nosot) . ' / ' . count($sot) . ".\n");
            }
        }

        $vcsClass = Vcs::getVcs($this->config);
        $vcs = new $vcsClass($this->config->project, $this->config->code_dir);
        $fileModifications = $vcs->getFileModificationLoad();

        $filesRows = array();
        $hashes = array();
        $duplicates = 0;
        foreach($files as $id => $file) {
            $fnv132 = hash_file('fnv132', $this->config->code_dir . $file);
            if (isset($hashes[$fnv132])) {
                $ignoredFiles[$file] = "Duplicate ({$hashes[$fnv132]})";
                ++$duplicates;
                unset($files[$id]);
                continue;
            } else {
                $hashes[$fnv132] = $file;
            }
            $modifications = $fileModifications[trim($file, '/')] ?? 0;
            $filesRows[] = compact('file', 'fnv132', 'modifications');
        }
        display("Removed $duplicates duplicates files\n");

        $i = array();
        foreach($ignoredFiles as $file => $reason) {
            $i[] = compact('file', 'reason');
        }
        $ignoredFiles = $i;
        $this->datastore->cleanTable('ignoredFiles');
        $this->datastore->addRow('ignoredFiles', $ignoredFiles);

        $this->datastore->cleanTable('files');

        $this->datastore->addRow('files', $filesRows);
        $this->datastore->addRow('hash', array('files'  => count($files),
                                               'tokens' => $tokens));
        $this->datastore->reload();

        $stats['php'] = count($files);
        $this->datastore->addRow('hash', $stats);

        // check for special files
        display('Check config files');
        $files = glob("{$this->config->code_dir}{,.}*", GLOB_BRACE);
        $files = array_map('basename', $files);

        $services = json_decode(file_get_contents("{$this->config->dir_root}/data/serviceConfig.json"));

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
        display("\n + " . implode("\n + ", array_map(function ($x) { return $x['file']; }, $configFiles)) . "\n\n");
        // Composer is check previously

        display('Done');

        if ($this->config->json) {
            echo json_encode($stats);
        } else {
            display_r($stats);
        }
        $this->datastore->addRow('hash', array('status' => 'Initproject'));
        $this->checkTokenLimit();
    }

    private function checkComposer($dir) {
        // composer.json
        display('Check composer');
        $composerInfo = array();
        if ($composerInfo['composer.json'] = file_exists("{$dir}/composer.json")) {
            $composerInfo['composer.lock'] = file_exists("{$dir}/composer.lock");

            $composer = json_decode(file_get_contents("{$dir}/composer.json"));

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
    
    private function countTokens($path, &$files, &$ignoredFiles) {
        $tokens = 0;

        $php = new Phpexec($this->config->phpversion, $this->config->{'php' . str_replace('.', '', $this->config->phpversion)});

        foreach($files as $id => $file) {
            if (($t = $php->countTokenFromFile($path . $file)) < 2) {
                unset($files[$id]);
                $ignoredFiles[$file] = 'Not a PHP File';
            } else {
                $tokens += $t;
            }
        }
        
        return $tokens;
    }

    private function checkLicence($dir) {
        $licenses = parse_ini_file($this->config->dir_root . '/data/license.ini');
        $licenses = $licenses['files'];
        
        foreach($licenses as $file) {
            if (file_exists("$dir/$file")) {
                $this->datastore->addRow('hash', array('licence_file' => 'unknown'));

                return true;
            }
        }
        $this->datastore->addRow('hash', array('licence_file' => 'unknown'));
    }

    public static function findFiles($path, &$files, &$ignoredFiles, $config) {
        $ignore_dirs = $config->ignore_dirs;

        $ignore_files = parse_ini_file("{$config->dir_root}/data/ignore_files.ini");
        $ignore_files = array_flip($ignore_files['files']);
        
        // Regex to ignore files and folders
        $ignoreDirs = array();
        foreach($ignore_dirs as $ignore) {
            // ignore mis configuration
            if (empty($ignore)) {
                continue;
            }

            if ($ignore[0] === '/') {
                $ignoreDirs[] = "$ignore.*";
            } else {
                $ignoreDirs[] = ".*$ignore.*";
            }
        }
        if (empty($ignoreDirs)) {
            $ignoreDirsRegex = '#^$#';
        } else {
            $ignoreDirsRegex = '#^(' . implode('|', $ignoreDirs) . ')#';
        }

        // Regex to include files and folders
        $includeDirs = array();
        foreach($config->include_dirs as $include) {
            if (empty($include)) {
                continue;
            }

            if ($include === '/') {
                $includeDirs[] = '/.*';
            } elseif ($include[0] === '/') {
                $includeDirs[] = "$include.*";
            } else {
                $includeDirs[] = ".*$include.*'";
            }
        }
        if (empty($includeDirs)) {
            $includeDirsRegex = '';
        } else {
            $includeDirsRegex = '#^(' . implode('|', $includeDirs) . ')#';
        }

        $d = getcwd();
        if (!file_exists($path)) {
            display( "No such file as '$path' when looking for files\n");
            $files = array();
            $ignoredFiles = array();
            return ;
        }
        chdir($path);
        $allFiles = rglob('.');
        $allFiles = array_map(function ($path) { return ltrim($path, '.'); }, $allFiles);
        chdir($d);

        $exts = $config->file_extensions;

        $notIgnored = preg_grep($ignoreDirsRegex, $allFiles, PREG_GREP_INVERT);

        if (empty($includeDirsRegex)) {
            $files = $notIgnored;
        } else {
            $included = preg_grep($includeDirsRegex, $allFiles);
            $files    = array_merge($notIgnored, $included);
            $files    = array_unique($files);
        }

        $ignoredFiles = array_fill_keys(array_diff($allFiles, $files), 'Ignored dir');

        foreach($files as $id => $file) {
            $f = basename($file);
            if (isset($ignore_files[$f])) {
                unset($files[$id]);
                $ignoredFiles[$file] = "Ignored file ($f)";
                continue;
            }
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if (!in_array($ext, $exts)) {
                // selection of extensions
                unset($files[$id]);
                $ignoredFiles[$file] = "Ignored extension ($ext)";
                continue;
            }
        }
    }
}

?>
