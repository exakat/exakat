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

use Exakat\Config;
use Exakat\Datastore;
use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\ProjectNeeded;
use Exakat\Exceptions\HelperException;
use Exakat\Project;

class Initproject extends Tasks {
    const CONCURENCE = self::ANYTIME;

    public function run() {
        $project = new Project($this->config->project);

        if ($project == 'default') {
            throw new ProjectNeeded();
        }

        if (!$project->validate()) {
            throw new NoSuchProject($project);
        }

        $repositoryURL = $this->config->repository;

        if ($this->config->delete === true) {
            display('Deleting '.$project);

            // final wait..., just in case
            sleep(2);

            rmdirRecursive($this->config->projects_root.'/projects/'.$project);
        } elseif ($this->config->update === true) {
            display('Updating '.$project);

            shell_exec('cd '.$this->config->projects_root.'/projects/'.$project.'/code/; git pull');
        } else {
            display('Initializing '.$project.' with '.$repositoryURL);
            $this->init_project($project, $repositoryURL);
        }

        display('Done');
    }

    private function init_project($project, $repositoryURL) {
        if (!file_exists($this->config->projects_root.'/projects/'.$project)) {
            mkdir($this->config->projects_root.'/projects/'.$project, 0755);
        } else {
            display( $this->config->projects_root.'/projects/'.$project.' already exists. Reusing'."\n");
        }

        if (!file_exists($this->config->projects_root.'/projects/'.$project.'/log/')) {
            mkdir($this->config->projects_root.'/projects/'.$project.'/log/', 0755);
        } else {
            display( $this->config->projects_root.'/projects/'.$project.'/log/ already exists. Ignoring');
            return null;
        }

        $this->datastore = new Datastore($this->config, Datastore::CREATE);

        if (!file_exists($this->config->projects_root.'/projects/'.$project.'/config.ini')) {
            if ($this->config->symlink === true) {
                $vcs = 'symlink';
                $projectName = basename($repositoryURL);
            } elseif ($this->config->svn === true) {
                $vcs = 'svn';
                $projectName = basename($repositoryURL);
                if (in_array($projectName, array('trunk', 'code'))) {
                    $projectName = basename(dirname($repositoryURL));
                    if (in_array($projectName, array('trunk', 'code'))) {
                        $projectName = basename(dirname(dirname($repositoryURL)));
                    }
                }
            } elseif ($this->config->git === true) {
                $vcs = 'git';
                $projectName = basename($repositoryURL);
                $projectName = str_replace('.git', '', $projectName);
            } elseif ($this->config->copy === true) {
                $vcs = 'copy';
                $projectName = basename($repositoryURL);
            } elseif ($this->config->bzr === true) {
                $vcs = 'bzr';
                list(, $projectName) = explode(':', $repositoryURL);
            } elseif ($this->config->hg === true) {
                $vcs = 'hg';
                $projectName = basename($repositoryURL);
            } elseif ($this->config->zip === true) {
                $vcs = 'zip';
                $projectName = basename($repositoryURL);
                $projectName = str_replace('.zip', '', $projectName);
            } elseif ($this->config->tgz === true) {
                $vcs = 'tgz';
                $projectName = basename($repositoryURL);
                $projectName = str_replace(array('.tbz', '.tar.bz2'), '', $projectName);
            } elseif ($this->config->composer === true) {
                $vcs = 'composer';
                $projectName = str_replace('/', '_', $repositoryURL);
            } else {
                $vcs = '';
                $projectName = basename($repositoryURL);
                $projectName = preg_replace('.git', '', $projectName);
            }

        } else {
            display( $this->config->projects_root.'/projects/'.$project.'/config.ini already exists. Ignoring');
        }

        shell_exec('chmod -R g+w '.$this->config->projects_root.'/projects/'.$project);
        $repositoryDetails = parse_url($repositoryURL);

        $skipFiles           = false;
        $repositoryPackagist = '';
        $repositoryBranch    = '';
        $repositoryTag       = '';
        $include_dirs        = '';

        if (!file_exists($this->config->projects_root.'/projects/'.$project.'/code/')) {
            switch (true) {
                // Symlink
                case ($this->config->symlink === true) :
                    display('Symlink initialization : '.realpath($repositoryURL));
                    symlink(realpath($repositoryURL), $this->config->projects_root.'/projects/'.$project.'/code');
                    break 1;

                // Initialization by copy
                case ($this->config->copy === true) :
                    display('Copy initialization');
                    $total = copyDir(realpath($repositoryURL), $this->config->projects_root.'/projects/'.$project.'/code');
                    display($total.' files were copied');
                    break 1;

                // Empty initialization
                case ($repositoryURL === '' || $repositoryURL === false) :
                    display('Empty initialization');
                    $skipFiles = true;

                    break 1;

                // composer archive (early in the list, as this won't have 'scheme'
                case ($this->config->composer === true) :
                    display('Initialization with composer');

                    $res = shell_exec('composer --version');
                    if (strpos($res, 'Composer') === false) {
                        throw new HelperException('Composer');
                    }

                    // composer install
                    $composer = new \stdClass();
                    $composer->{'minimum-stability'} = 'dev';
                    $composer->require = new \stdClass();
                    $composer->require->$repositoryURL = 'dev-master';
                    $json = json_encode($composer, JSON_PRETTY_PRINT);
                    mkdir($this->config->projects_root.'/projects/'.$project.'/code', 0755);
                    file_put_contents($this->config->projects_root.'/projects/'.$project.'/code/composer.json', $json);
                    shell_exec('cd '.$this->config->projects_root.'/projects/'.$project.'/code; composer -q install');

                    // Updating config.ini to include the vendor directory
                    $include_dirs = "include_dirs[] = /vendor/$repositoryURL";
                    break 1;

                // SVN
                case (isset($repositoryDetails['scheme']) && $repositoryDetails['scheme'] == 'svn' || $this->config->svn === true) :

                    $res = shell_exec('svn --version');
                    if (strpos($res, 'svn') === false) {
                        throw new HelperException('SVN');
                    }
                    display('SVN initialization');
                    shell_exec('cd '.$this->config->projects_root.'/projects/'.$project.'; svn checkout '.escapeshellarg($repositoryURL).' code');
                    break 1;

                // Bazaar
                case ($this->config->bzr === true) :
                    $res = shell_exec('bzr --version');
                    if (strpos($res, 'Bazaar') === false) {
                        throw new HelperException('Bazar');
                    }
                    display('Bazaar initialization');
                    shell_exec('cd '.$this->config->projects_root.'/projects/'.$project.'; bzr branch '.escapeshellarg($repositoryURL).' code');
                    break 1;

                // HG
                case ($this->config->hg === true) :
                    $res = shell_exec('hg --version');
                    if (strpos($res, 'Mercurial') === false) {
                        throw new HelperException('Mercurial');
                    }
                    display('Mercurial initialization');
                    shell_exec('cd '.$this->config->projects_root.'/projects/'.$project.'; hg clone '.escapeshellarg($repositoryURL).' code');
                    break 1;

                // Tbz archive
                case ($this->config->tbz === true) :
                    display('Download the tar.bz2');
                    $binary = file_get_contents($repositoryURL);
                    display('Saving');
                    $archiveFile = tempnam(sys_get_temp_dir(), 'archiveTgz').'.tgz';
                    file_put_contents($archiveFile, $binary);
                    display('Unarchive');
                    shell_exec('tar -jxf '.$archiveFile.' --directory '.$this->config->projects_root.'/projects/'.$project.'/code/');
                    display('Cleanup');
                    unlink($archiveFile);
                    break 1;

                // tgz archive
                case ($this->config->tgz === true) :
                    display('Download the tar.gz');
                    $binary = file_get_contents($repositoryURL);
                    display('Saving');
                    $archiveFile = tempnam(sys_get_temp_dir(), 'archiveTgz').'.tgz';
                    file_put_contents($archiveFile, $binary);
                    display('Unarchive');
                    mkdir($this->config->projects_root.'/projects/'.$project.'/code/', 0755);
                    shell_exec('tar -zxf '.$archiveFile.' -C '.$this->config->projects_root.'/projects/'.$project.'/code/');
                    display('Cleanup');
                    unlink($archiveFile);
                    break 1;

                // zip archive
                case ($this->config->zip === true) :
                    $res = shell_exec('zip --version');
                    if (strpos($res, 'Zip') === false) {
                        throw new HelperException('zip');
                    }

                    display('Download the zip');
                    $binary = file_get_contents($repositoryURL);
                    display('Saving');
                    $archiveFile = tempnam(sys_get_temp_dir(), 'archiveZip').'.zip';
                    file_put_contents($archiveFile, $binary);
                    display('Unzip');
                    shell_exec('unzip '.$archiveFile.' -d '.$this->config->projects_root.'/projects/'.$project.'/code/');
                    display('Cleanup');
                    unlink($archiveFile);
                    break 1;

                // Git
                // Git is last, as it will act as a default
                case ((isset($repositoryDetails['scheme']) && $repositoryDetails['scheme'] === 'git') || $this->config->git === true) :
                    $res = shell_exec('git --version');
                    if (strpos($res, 'git') === false) {
                        throw new HelperException('git');
                    }
                    
                    display('Git initialization');
                    if (isset($repositoryDetails['user'])) {
                        $repositoryDetails['user'] = escapeshellarg($repositoryDetails['user']);
                    } else {
                        $repositoryDetails['user'] = 'exakat';
                    }
                    if (isset($repositoryDetails['pass'])) {
                        $repositoryDetails['pass'] = escapeshellarg($repositoryDetails['pass']);
                    } else {
                        $repositoryDetails['pass'] = 'exakat';
                    }
                    
                    unset($repositoryDetails['query']);
                    unset($repositoryDetails['fragment']);
                    $repositoryNormalizedURL = unparse_url($repositoryDetails);

                    $shell = 'cd '.$this->config->projects_root.'/projects/'.$project.'; git clone -q '.$repositoryNormalizedURL;
                    if (!empty($this->config->branch) &&
                        $this->config->branch !== 'master') {
                        display("Check out with branch ".$this->config->branch);
                        $shell .= ' -b '.$this->config->branch.' ';

                        $repositoryBranch =  $this->config->branch;
                        $repositoryTag =  '';
                    } elseif (!empty($this->config->tag)) {
                        display("Check out with tag ".$this->config->tag);
                        $shell .= ' -b '.$this->config->tag.' ';

                        $repositoryBranch =  '';
                        $repositoryTag =  $this->config->tag;
                    } else {
                        $repositoryBranch =  'master';
                        $repositoryTag =  '';
                    }
                    $shell .= ' code 2>&1 ';
                    $res = shell_exec($shell);

                    if (($offset = strpos($res, 'fatal: ')) !== false) {
                        $this->datastore->addRow('hash', array('init error' => trim(substr($res, $offset + 7)) ));
                        var_dump(trim(substr($res, $offset + 7)));
                        $res = str_replace($repositoryNormalizedURL, $repositoryURL, $res);
                        $res = trim(substr($res, $offset + 7));
                        display('An error prevented code initialization : '.$res.PHP_EOL.'No code was loaded.');

                        $skipFiles = true;
                    }
                    break 1;

                default :
                    display('No Initialization');
            }
        } elseif (file_exists($this->config->projects_root.'/projects/'.$project.'/code/')) {
            display('Folder "code" is already existing. Leaving it intact.');
        }

        // default initial config. Found in test project.
        $phpversion = $this->config->phpversion;
        $configIni = <<<INI
;Main PHP version for this code.
phpversion = $phpversion

;Ignored dirs and files, relative to code source root.
ignore_dirs[] = /assets
ignore_dirs[] = /cache
ignore_dirs[] = /css
ignore_dirs[] = /data
ignore_dirs[] = /doc
ignore_dirs[] = /docs
ignore_dirs[] = /example
ignore_dirs[] = /examples
ignore_dirs[] = /js
ignore_dirs[] = /lang
ignore_dirs[] = /spec
ignore_dirs[] = /sql
ignore_dirs[] = /test
ignore_dirs[] = /tests
ignore_dirs[] = /tmp
ignore_dirs[] = /vendor
ignore_dirs[] = /version

;Included dirs or files, relative to code source root. Default to all.
;Those are added after ignoring directories
include_dirs[] = /
$include_dirs

;Accepted file extensions
file_extensions = .php,.php3,.inc,.tpl,.phtml,.tmpl,.phps,.ctp

;Description of the project
project_name        = "$projectName";
project_url         = "$repositoryURL";
project_vcs         = "$vcs";
project_description = "";
project_packagist   = "$repositoryPackagist";
project_branch      = "$repositoryBranch";
project_tag         = "$repositoryTag";

INI;

        file_put_contents($this->config->projects_root.'/projects/'.$project.'/config.ini', $configIni);

        display('Counting files');
        $this->datastore->addRow('hash', array('status' => 'Initproject'));

        if (!$skipFiles) {
            display('Running files');
            $analyze = new Files($this->gremlin, $this->config);
            $analyze->run();
            unset($analyze);
        }
    }
}


?>
