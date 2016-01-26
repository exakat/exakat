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

class Initproject extends Tasks {

    public function run(\Config $config) {
        $project = $config->project;

        if ($project == 'default') {
            die("No project name provided. Add -p option\n");
        }

        $repositoryURL = $config->repository;

        if ($config->delete === true) {
            $this->check_project_dir($project);
            display( "Deleting $project\n");
    
            // final wait..., just in case
            sleep(2);

            rmdirRecursive($config->projects_root.'/projects/'.$project);
        } elseif ($config->update === true) {
            $this->scheck_project_dir($project);
            display( "Updating $project\n");
    
            shell_exec('cd '.$config->projects_root.'/projects/'.$project.'/code/; git pull');
        } else {
            display( "Initializing $project with '$repositoryURL'\n");
            $this->init_project($project, $repositoryURL);
        }

        display( "Done\n");
    }
    
    private function init_project($project, $repositoryURL) {
        $config = \Config::factory();
        
        if (!file_exists($config->projects_root.'/projects/'.$project)) {
            mkdir($config->projects_root.'/projects/'.$project, 0755);
        } else {
            display( $config->projects_root.'/projects/'.$project.' already exists. Reusing'."\n");
        }

        if (!file_exists($config->projects_root.'/projects/'.$project.'/log/')) {
            mkdir($config->projects_root.'/projects/'.$project.'/log/', 0755);
        } else {
            display( $config->projects_root.'/projects/'.$project.'/log/ already exists. Ignoring'."\n");
            return null;
        }

        $this->datastore = new \Datastore(\Config::factory(), \Datastore::CREATE);

        if (!file_exists($config->projects_root.'/projects/'.$project.'/config.ini')) {
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

project_name = "$project";
project_url = "$repositoryURL";
project_description = "";
project_packagist = "";

INI;

            file_put_contents($config->projects_root.'/projects/'.$project.'/config.ini', $configIni);
        } else {
            display( $config->projects_root.'/projects/'.$project.'/config.ini already exists. Ignoring'."\n");
        }

        shell_exec('chmod -R g+w '.$config->projects_root.'/projects/'.$project);
        $repositoryDetails = parse_url($repositoryURL);

        if (!file_exists($config->projects_root.'/projects/'.$project.'/code/')) {
            switch (true) {
                // Empty initialization
                case ($repositoryURL === '' || $repositoryURL === false) :
                    display('Empty initialization');
                    break 1;

                // composer archive (early in the list, as this won't have 'scheme'
                case ($config->composer === true) :
                    display('Initialization with composer');

                    // composer install
                    $composer = new \stdClass();
                    $composer->require = new \stdClass();
                    $composer->require->$repositoryURL = 'dev-master';
                    $json = json_encode($composer);
                    file_put_contents($config->projects_root.'/projects/'.$project.'/composer.json', $json);
                    shell_exec('cd '.$config->projects_root.'/projects/'.$project.'; composer -q install; mv vendor code');
                    break 1;

                // SVN
                case ($repositoryDetails['scheme'] == 'svn' || $config->svn === true) :
                    display('SVN initialization');
                    shell_exec('cd '.$config->projects_root.'/projects/'.$project.'; svn checkout '.escapeshellarg($repositoryURL).' code');
                    break 1;

                // Bazaar
                case ($config->bzr === true) :
                    display('Bazaar initialization');
                    shell_exec('cd '.$config->projects_root.'/projects/'.$project.'; bzr branch '.escapeshellarg($repositoryURL).' code');
                    break 1;

                // HG
                case ($config->hg === true) :
                    display('Mercurial initialization');
                    shell_exec('cd '.$config->projects_root.'/projects/'.$project.'; hg clone '.escapeshellarg($repositoryURL).' code');
                    break 1;

                // Tbz archive
                case ($config->tbz === true) :
                    display('Download the tar.bz2');
                    $binary = file_get_contents($repositoryURL);
                    display('Saving');
                    $archiveFile = tempnam(sys_get_temp_dir(), 'archiveTgz').'.tgz';
                    file_put_contents($archiveFile, $binary);
                    display('Unarchive');
                    shell_exec('tar -jxf '.$archiveFile.' --directory '.$config->projects_root.'/projects/'.$project.'/code/');
                    display('Cleanup');
                    unlink($archiveFile);
                    break 1;

                // tgz archive
                case ($config->tgz === true) :
                    display('Download the tar.gz');
                    $binary = file_get_contents($repositoryURL);
                    display('Saving');
                    $archiveFile = tempnam(sys_get_temp_dir(), 'archiveTgz').'.tgz';
                    file_put_contents($archiveFile, $binary);
                    display('Unarchive');
                    shell_exec('tar -zxf '.$archiveFile.' --directory '.$config->projects_root.'/projects/'.$project.'/code/');
                    display('Cleanup');
                    unlink($archiveFile);
                    break 1;

                // zip archive
                case ($config->zip === true) :
                    display('Download the zip');
                    $binary = file_get_contents($repositoryURL);
                    display('Saving');
                    $archiveFile = tempnam(sys_get_temp_dir(), 'archiveZip').'.zip';
                    file_put_contents($archiveFile, $binary);
                    display('Unzip');
                    shell_exec('unzip '.$archiveFile.' -d '.$config->projects_root.'/projects/'.$project.'/code/');
                    display('Cleanup');
                    unlink($archiveFile);
                    break 1;

                // Git
                // Git is last, as it will act as a default
                case ($repositoryDetails['scheme'] == 'git' || $config->git === true) :
                    display('Git initialization');
                    shell_exec('cd '.$config->projects_root.'/projects/'.$project.'; git clone -q '.$repositoryURL.' code 2>&1 >> /dev/null');
                    break 1;

                default :
                    display('No Initialization');
            }
        } elseif (file_exists($config->projects_root.'/projects/'.$project.'/code/')) {
            display( "Code folder is already there. Leaving it intact.\n");
        }

        display( "Counting files\n");
        shell_exec('php '.$config->executable.' files -p '.$project);
    }

    private function check_project_dir($project) {
        $config = \Config::factory();
        
        if ($project === null ) {
            die( 'Usage : php '.$config->executable.' project_init -p project_name -R repository');
        }

        if (!file_exists($config->projects_root.'/projects/'.$project) ) {
            die( "Project $project doesn't exists.\n Aborting\n");
        }
    }

}

?>
