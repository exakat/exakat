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

use Exakat\Config;
use Exakat\Datastore;
use Exakat\Exceptions\ProjectNeeded;

class Initproject extends Tasks {
    const CONCURENCE = self::ANYTIME;
    
    public function run(Config $config) {
        $this->config = $config;
        $project = $config->project;

        if ($project == 'default') {
            throw ProjectNeeded();
        }

        $repositoryURL = $config->repository;

        if ($config->delete === true) {
            display( "Deleting $project\n");
    
            // final wait..., just in case
            sleep(2);

            rmdirRecursive($config->projects_root.'/projects/'.$project);
        } elseif ($config->update === true) {
            display( "Updating $project\n");
    
            shell_exec('cd '.$config->projects_root.'/projects/'.$project.'/code/; git pull');
        } else {
            display( "Initializing $project with '$repositoryURL'\n");
            $this->init_project($project, $repositoryURL);
        }

        display( "Done\n");
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
            display( $this->config->projects_root.'/projects/'.$project.'/log/ already exists. Ignoring'."\n");
            return null;
        }

        $this->datastore = new Datastore(Config::factory(), Datastore::CREATE);

        if (!file_exists($this->config->projects_root.'/projects/'.$project.'/config.ini')) {
            if ($this->config->symlink === true) {
                $vcs = 'symlink';
            } elseif ($this->config->svn === true) {
                $vcs = 'svn';
            } elseif ($this->config->git === true) {
                $vcs = 'git';
            } elseif ($this->config->copy === true) {
                $vcs = 'copy';
            } elseif ($this->config->bzr === true) {
                $vcs = 'bzr';
            } elseif ($this->config->hg === true) {
                $vcs = 'hg';
            } elseif ($this->config->composer === true) {
                $vcs = 'composer';
            } else {
                $vcs = 'git';
            }
            // default initial config. Found in test project.
            $configIni = <<<INI
phpversion = 7.0

ignore_dirs[] = /test
ignore_dirs[] = /tests
ignore_dirs[] = /Tests
ignore_dirs[] = /Test
ignore_dirs[] = /example
ignore_dirs[] = /examples
ignore_dirs[] = /docs
ignore_dirs[] = /doc
ignore_dirs[] = /tmp
ignore_dirs[] = /version
ignore_dirs[] = /vendor
ignore_dirs[] = /js
ignore_dirs[] = /lang
ignore_dirs[] = /data
ignore_dirs[] = /css
ignore_dirs[] = /cache
ignore_dirs[] = /vendor
ignore_dirs[] = /assets
ignore_dirs[] = /spec
ignore_dirs[] = /sql

file_extensions =

project_name        = "$project";
project_url         = "$repositoryURL";
project_vcs         = "$vcs";
project_description = "";
project_packagist   = "";

INI;

            file_put_contents($this->config->projects_root.'/projects/'.$project.'/config.ini', $configIni);
        } else {
            display( $this->config->projects_root.'/projects/'.$project.'/config.ini already exists. Ignoring'."\n");
        }

        shell_exec('chmod -R g+w '.$this->config->projects_root.'/projects/'.$project);
        $repositoryDetails = parse_url($repositoryURL);

        $skipFiles = false;
        if (!file_exists($this->config->projects_root.'/projects/'.$project.'/code/')) {
            switch (true) {
                // Empty initialization
                case ($repositoryURL === '' || $repositoryURL === false) :
                    display('Empty initialization');
                    break 1;

                // Symlink
                case ($this->config->symlink === true) :
                    display('Symlink initialization : '.realpath($repositoryURL));
                    symlink(realpath($repositoryURL), $this->config->projects_root.'/projects/'.$project.'/code');
                    break 1;

                // Empty initialization
                case ($this->config->copy === true) :
                    display('Copy initialization');
                    $total = copyDir(realpath($repositoryURL), $this->config->projects_root.'/projects/'.$project.'/code');
                    display($total . ' files were copied');
                    break 1;

                // composer archive (early in the list, as this won't have 'scheme'
                case ($this->config->composer === true) :
                    display('Initialization with composer');

                // composer install
                    $composer = new \stdClass();
                    $composer->require = new \stdClass();
                    $composer->require->$repositoryURL = 'dev-master';
                    $json = json_encode($composer);
                    mkdir($this->config->projects_root.'/projects/'.$project.'/code', 0755);
                    file_put_contents($this->config->projects_root.'/projects/'.$project.'/code/composer.json', $json);
                    shell_exec('cd '.$this->config->projects_root.'/projects/'.$project.'/code; composer -q install');
                    break 1;

                // SVN
                case (isset($repositoryDetails['scheme']) && $repositoryDetails['scheme'] == 'svn' || $this->config->svn === true) :
                    display('SVN initialization');
                    shell_exec('cd '.$this->config->projects_root.'/projects/'.$project.'; svn checkout '.escapeshellarg($repositoryURL).' code');
                    break 1;

                // Bazaar
                case ($this->config->bzr === true) :
                    display('Bazaar initialization');
                    shell_exec('cd '.$this->config->projects_root.'/projects/'.$project.'; bzr branch '.escapeshellarg($repositoryURL).' code');
                    break 1;

                // HG
                case ($this->config->hg === true) :
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
                    shell_exec('tar -zxf '.$archiveFile.' --directory '.$this->config->projects_root.'/projects/'.$project.'/code/');
                    display('Cleanup');
                    unlink($archiveFile);
                    break 1;

                // zip archive
                case ($this->config->zip === true) :
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
                case ((isset($repositoryDetails['scheme']) && $repositoryDetails['scheme'] == 'git') || $this->config->git === true) :
                    display('Git initialization');
                    $res = shell_exec('cd '.$this->config->projects_root.'/projects/'.$project.'; git clone -q '.$repositoryURL.' code 2>&1 ');
                    if (($offset = strpos($res, 'fatal: ')) !== false) {
                        $this->datastore->addRow('hash', array('init error' => trim(substr($res, $offset + 7)) ));
                        display( "An error prevented code initialization : ".trim(substr($res, $offset + 7))."\nNo code was loaded.\n");

                        $skipFiles = true;
                    }
                    break 1;

                default :
                    display('No Initialization');
            }
        } elseif (file_exists($this->config->projects_root.'/projects/'.$project.'/code/')) {
            display( "Code folder is already there. Leaving it intact.\n");
        }

        display( "Counting files\n");
        $this->datastore->addRow('hash', array('status' => 'Initproject'));

        if (!$skipFiles) {
            display("Running files\n");
            $analyze = new Files($this->gremlin);
            $analyze->run($this->config);
            unset($analyze);
        }
    }
}

?>
