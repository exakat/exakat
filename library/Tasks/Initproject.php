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

class Initproject implements Tasks {
    public function run(\Config $config) {
        $project = $config->project;
        if ($project == 'default') {
            die("No project name provided. Add -p option\n");
        }

        $repo_url = $config->repository;

        if ($config->delete === true) {
            $this->check_project_dir($project);
            display( "Deleting $project\n");
    
            // final wait..., just in case
            sleep(2);

            shell_exec('rm -rf '.$config->projects_root.'/projects/'.$project.'/');
        } elseif ($config->update === true) {
            $this->scheck_project_dir($project);
            display( "Updating $project\n");
    
            shell_exec('cd '.$config->projects_root.'/projects/'.$project.'/code/; git pull');
        } else {
            display( "Initializing $project with '$repo_url'\n");
            $this->init_project($project, $repo_url);
        }

        display( "Done\n");
    }
    
    private function init_project($project, $repo_url) {
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

        if (!file_exists($config->projects_root.'/projects/'.$project.'/config.ini')) {
            // default initial config. Found in test project.
            $configIni = <<<INI
phpversion = 5.6

ignore_dirs[] = /test
ignore_dirs[] = /tests
ignore_dirs[] = /docs
ignore_dirs[] = /doc
ignore_dirs[] = /tmp
ignore_dirs[] = /version

file_extensions =

project_name = "$project";
project_url = "$repo_url";
project_description = "";
project_packagist = "";

INI;

            file_put_contents($config->projects_root.'/projects/'.$project.'/config.ini', $configIni);
        } else {
            display( $config->projects_root.'/projects/'.$project.'/config.ini already exists. Ignoring'."\n");
        }

        shell_exec('chmod -R g+w '.$config->projects_root.'/projects/'.$project);

        if (!file_exists($config->projects_root.'/projects/'.$project.'/code/')) {
            switch (true) {
                // Empty initialization
                case ($repo_url === '' || $repo_url === false) : 
                    display('Empty initialization');
                    break 1;
                
                // Git 
                case ($config->git === true) : 
                    display('Git initialization');
                    print 'cd '.$config->projects_root.'/projects/'.$project.'; git clone '.$repo_url.' code'."\n";
                    shell_exec('cd '.$config->projects_root.'/projects/'.$project.'; git clone '.$repo_url.' code');
                    break 1;

                // SVN 
                case ($config->svn === true) : 
                    display('SVN initialization');
                    shell_exec('cd '.$config->projects_root.'/projects/'.$project.'; svn checkout '.escapeshellarg($repo_url).' code');
                    break 1;

                // HG 
                case ($config->hg === true) : 
                    display('Mercurial initialization');
                    shell_exec('cd '.$config->projects_root.'/projects/'.$project.'; hg clone '.escapeshellarg($repo_url).' code');
                    break 1;

                // Tbz archive 
                case ($config->tbz === true) : 
                    display('Initialization from tar.bz2 archive');
                    print shell_exec('wget -q -O '.$config->projects_root.'/projects/'.$project.'/archive.tbz2 '.escapeshellarg($repo_url).';cd '.$config->projects_root.'/projects/'.$project.'; mkdir code; tar -xjf archive.tbz2 -C code; rm -rf archive.tbz2');
                    break 1;

                // tgz archive 
                case ($config->tgz === true) : 
                    display('Initialization from tar.gz archive');
                    shell_exec('wget -q -O '.$config->projects_root.'/projects/'.$project.'/archive.tgz '.escapeshellarg($repo_url).';cd '.$config->projects_root.'/projects/'.$project.'; mkdir code; tar -xzf archive.tgz -C code; rm -rf archive.tgz');
                    break 1;

                // tgz archive 
                case ($config->zip === true) : 
                    display('Initialization from zip archive');
                    shell_exec('wget -q -O '.$config->projects_root.'/projects/'.$project.'/archive.zip '.escapeshellarg($repo_url).';cd '.$config->projects_root.'/projects/'.$project.'; mkdir code; unzip archive.zip -d code');
                    break 1;

                // composer archive 
                case ($config->composer === true) : 
                    display('Initialization with composer');

                    // composer install
                    $composer = new \stdClass();
                    $composer->require = new \stdClass();
                    $composer->require->$repo_url = 'dev-master';
                    $json = json_encode($composer);
                    file_put_contents($config->projects_root.'/projects/'.$project.'/composer.json', $json);
                    shell_exec('cd '.$config->projects_root.'/projects/'.$project.'; composer -q install; mv vendor code');
                    break 1;
            
                default : 
                    print "No Initialization\n";
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
            die( "Usage : php ".$config->executable." project_init -p project_name -R repository");
        }

        if (!file_exists($config->projects_root.'/projects/'.$project) ) {
            die( "Project $project doesn't exists.\n Aborting\n");
        }
    }

}

?>
