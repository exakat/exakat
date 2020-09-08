<?php declare(strict_types = 1);
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
use Exakat\Exceptions\NoCodeInProject;
use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\ProjectNeeded;
use Exakat\Vcs\Vcs;

class Files extends Tasks {
    const CONCURENCE = self::ANYTIME;

    private $tmpFileName = '';

    public function run(): void {
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

        display("Searching for files \n");
        self::findFiles($this->config->code_dir, $files, $ignoredFiles, $this->config);
        display('Found ' . count($files) . " files.\n");

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

        if (empty($files)) {
            $this->datastore->addRow('hash', array('files'           => 0,
                                                   'filesIgnored'    => count($ignoredFiles),
                                                   'tokens'          => 0,
                                                   'file_extensions' => json_encode($this->config->file_extensions),
                                                   'ignore_dirs'     => json_encode($this->config->ignore_dirs),
                                                   'include_dirs'    => json_encode($this->config->include_dirs),
                                               )
                                            );
            return;
        }

        $this->tmpFileName = "{$this->config->tmp_dir}/files{$this->config->pid}.txt";
        $tmpFiles = array_map(function (string $file): string {
            return str_replace(array('\\', '(', ')', ' ', '$', '<', "'", '"', ';', '&', '`', '|', "\t"),
                               array('\\\\', '\\(', '\\)', '\\ ', '\\$', '\\<', "\\'", '\\"', '\\;', '\\&', '\\`', '\\|', "\\\t", ),
                               ".$file");
                               }, $files);
        file_put_contents($this->tmpFileName, implode("\n", $tmpFiles));

        $vcsClass = Vcs::getVcs($this->config);
        $vcs = new $vcsClass($this->config->project, $this->config->code_dir);

        $SQLresults = $this->checkCompilations();

        $SQLresults += $this->checkShortTags();

        $i = array();
        foreach($ignoredFiles as $file => $reason) {
            $i[] = compact('file', 'reason');
        }
        $ignoredFiles = $i;
        $this->datastore->cleanTable('ignoredFiles');
        $this->datastore->addRow('ignoredFiles', $ignoredFiles);

        $this->datastore->cleanTable('files');

        $this->datastore->addRow('files', $filesRows);
        $this->datastore->addRow('hash', array('files'           => count($files),
                                               'filesIgnored'    => count($ignoredFiles),
                                               'tokens'          => 0,
                                               'file_extensions' => json_encode($this->config->file_extensions),
                                               'ignore_dirs'     => json_encode($this->config->ignore_dirs),
                                               'include_dirs'    => json_encode($this->config->include_dirs),
                                               )
                                            );
        $this->datastore->reload();

        $stats['php'] = count($files);
        $this->datastore->addRow('hash', $stats);

        // check for special files
        display('Check config files');
        // Avoid , GLOB_BRACE
        $files = array_merge(glob("{$this->config->code_dir}/.*"),
                             glob("{$this->config->code_dir}/*")) ;
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
        // Composer is checked previously

        $files = array();
        $i = 0;
        while(count($files) != $SQLresults) {
            $files = glob("{$this->config->project_dir}/.exakat/dump-*.php");
            usleep(random_int(0,1000) * 1000);

            ++$i;
            if ($i >= 60) {
                break 1;
            }
        }
        // TODO : log it when

        foreach($files as $file) {
            include $file;

            $this->datastore->storeQueries($queries);
            unlink($file);
        }

        display('Done');

        if ($this->config->json) {
            echo json_encode($stats);
        }
        $this->datastore->addRow('hash', array('status' => 'Initproject'));
        $this->checkTokenLimit();
    }

    private function checkComposer(string $dir): void {
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

    private function checkLicence(string $dir): bool {
        $licenses = parse_ini_file($this->config->dir_root . '/data/license.ini');
        $licenses = $licenses['files'];

        foreach($licenses as $file) {
            if (file_exists("$dir/$file")) {
                $this->datastore->addRow('hash', array('licence_file' => 'unknown'));

                return true;
            }
        }
        $this->datastore->addRow('hash', array('licence_file' => 'unknown'));

        return false;
    }

    public static function findFiles(string $path, array &$files, array &$ignoredFiles, Config $config): void {
        $ignoreFileNames = parse_ini_file("{$config->dir_root}/data/ignore_files.ini");
        $ignoreFileNames = array_flip($ignoreFileNames['files']);

        // Regex to ignore files and folders
        $ignoreDirs = array();
        foreach($config->ignore_dirs as $ignore) {
            // ignore mis configuration
            if (empty($ignore)) {
                continue;
            }

            if ($ignore[0] === '/') {
                $ignoreDirs[] = "$ignore*";
            } else {
                $ignoreDirs[] = "*$ignore*";
            }
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
                $includeDirs[] = "$include*";
            } else {
                $includeDirs[] = "*$include*'";
            }
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
        $allFiles = array_map(function (string $path): string { return ltrim($path, '.'); }, $allFiles);
        chdir($d);

        $exts = $config->file_extensions;

        foreach($allFiles as $file) {
            foreach($ignoreDirs as $ignore) {
                if (fnmatch($ignore, $file)) {
                    $ignoredFiles[] = $file;
                    continue 2;
                }
            }

            $files[] = $file;
        }

        foreach($ignoredFiles as $id => $file) {
            foreach($includeDirs as $ignore) {
                if (fnmatch($ignore, $file)) {
                    $files[] = $file;
                    unset($ignoredFiles[$id]);
                    continue 2;
                }
            }
        }

        foreach($files as $id => $file) {
            if (is_link($path . $file)) {
                unset($files[$id]);
                $ignoredFiles[$file] = "Symbolic link ($f)";
                continue;
            }
            $f = basename($file);
            if (isset($ignoreFileNames[mb_strtolower($f)])) {
                unset($files[$id]);
                $ignoredFiles[$file] = "Ignored file ($f)";
                continue;
            }
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if (!in_array(mb_strtolower($ext), $exts)) {
                // selection of extensions
                unset($files[$id]);
                $ignoredFiles[$file] = "Ignored extension ($ext)";
                continue;
            }
        }
    }

    public function __destruct() {
        if (file_exists($this->tmpFileName)) {
            unlink($this->tmpFileName);
        }
        if (file_exists($this->config->tmp_dir . '/lint.php')) {
            unlink($this->config->tmp_dir . '/lint.php');
        }
        if (file_exists($this->config->tmp_dir . '/lint_short_tags.php')) {
            unlink($this->config->tmp_dir . '/lint_short_tags.php');
        }
    }

    private function checkCompilations(): int {
        $versions = Config::PHP_VERSIONS;
        $SQLresults = 0;

        $analyzingVersion = $this->config->phpversion[0] . $this->config->phpversion[2];
        $this->datastore->cleanTable("compilation$analyzingVersion");
        if ($this->is_subtask === self::IS_SUBTASK) {
            $id = array_search($analyzingVersion, $versions);
            unset($versions[$id]);
        }

        foreach($versions as $version) {
            $phpVersion = "php$version";

            if (empty($this->config->{$phpVersion})) {
                // This version is not defined
                continue;
            }

            display("Check compilation for $version");

            $php = new Phpexec($phpVersion, $this->config->{$phpVersion});
            $php->compileFiles($this->config->code_dir, $this->tmpFileName, $this->config->dir_root);
            ++$SQLresults;
        }

        return $SQLresults;
    }

    private function checkShortTags(): int {
        copy("{$this->config->dir_root}/server/lint_short_tags.php", "{$this->config->project_dir}/.exakat/lint_short_tags.php");
        $shell = "nohup php {$this->config->project_dir}/.exakat/lint_short_tags.php {$this->config->php} {$this->config->project_dir} {$this->tmpFileName} 2>&1 >/dev/null & echo $!";
        shell_exec($shell);

        return 1;
    }
}

?>