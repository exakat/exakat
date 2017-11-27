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
use Exakat\Exceptions\NoCodeInProject;
use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\NoFileToProcess;
use Exakat\Exceptions\ProjectNeeded;

class Update extends Tasks {
    const CONCURENCE = self::ANYTIME;

    protected $logname = self::LOG_NONE;

    public function run() {
        if ($this->config->project === 'default') {
            $this->runDefault();
        } else {
            $this->runProjet($this->config->project);
        }
    }
    
    private function runDefault() {
        $paths = glob($this->config->projects_root.'/projects/*');
        $projects = array_map('basename', $paths);
        $projects = array_diff($projects, array('test'));
        
        print "Updating ".count($projects)." projects".PHP_EOL;
        shuffle($projects);
        foreach($projects as $project) {
            display("updating $project".PHP_EOL);
            $this->update($project);
        }
    }
    
    private function runProject($project) {
        $path = $this->config->projects_root.'/projects/'.$project;
    
        if (!file_exists($path)) {
            throw new NoSuchProject($this->config->project);
        }
    
        if (!file_exists($path.'/code')) {
            throw new NoCodeInProject($this->config->project);
        }
        
        $this->update($this->config->project);
    }
    
    private function update($project) {
        $path = $this->config->projects_root.'/projects/'.$project;
        
        switch(true) {
            // symlink case
            case $this->config->project_vcs === 'symlink' :
                // Nothing to do, the symlink is here for that
                break;

            // copy case
            case $this->config->project_vcs === 'copy' :
                // Remove and copy again
                $total = rmdirRecursive($this->config->projects_root.'/projects/'.$project.'/code/');
                display("$total files were removed");

                $total = copyDir(realpath($this->config->project_url), $this->config->projects_root.'/projects/'.$project.'/code');
                display("$total files were copied");
                break;

            // Git case
            case file_exists($path.'/code/.git') :
                display('Git pull for '.$project);
                $res = shell_exec('cd '.$path.'/code/; git branch | grep \\*');
                $branch = substr(trim($res), 2);
                
                if (strpos($branch, ' detached at ') !== false) {
                    $resInitial = shell_exec('cd '.$path.'/code/; git checkout master --quiet; git pull');
                    $branch = 'master';
                    
                } else {
                    $resInitial = shell_exec('cd '.$path.'/code/; git show-ref --heads '.$branch);
                }

                $date = trim(shell_exec('cd '.$path.'/code/; git pull --quiet; git log -1 --format=%cd '));
                $resFinal = shell_exec('cd '.$path.'/code/; git show-ref --heads '.$branch);
                if (strpos($resFinal, ' ') !== false) {
                    list($resFinal, ) = explode(' ', $resFinal);
                }

                if ($resFinal != $resInitial) {
                    display( "Git updated to commit $resFinal (Last commit : $date)");
                } else {
                    display( "No update available (Last commit : $date)");
                }

                break;

            // svn case
            case file_exists($path.'/code/.svn') :
                display('SVN update '.$project);
                $res = shell_exec('cd '.$path.'/code/; svn update');
                if (!preg_match('/Updated to revision (\d+)\./', $res, $r)) {
                    preg_match('/At revision (\d+)/', $res, $r);
                }

                display( "SVN updated to revision $r[1]");

                break;

            // bazaar case
            case file_exists($path.'/code/.bzr') :
                display('Bazaar update '.$project);
                $res = shell_exec('cd '.$path.'/code/; bzr update 2>&1');
                preg_match('/revision (\d+)/', $res, $r);

                display( "Bazaar updated to revision $r[1]");

                break;

            // mercurial
            case file_exists($path.'/code/.hg') :
                display('Mercurial update '.$project);
                $res = shell_exec('cd '.$path.'/code/; hg pull 2>&1; hg update; hg log -l 1');
                preg_match('/changeset:\s+(\S+)/', $res, $changeset);
                preg_match("/date:\s+([^\n]+)/", $res, $date);

                display( "Mercurial updated to revision $changeset[1] ($date[1])");

                break;

            // composer case
            case $this->config->project_vcs === 'composer' :
                display('Composer update '.$project);
                $res = shell_exec('cd '.$path.'/code/; composer -q install ');

                $json = file_get_contents($path.'/code/composer.lock');
                $json = json_decode($json);

                foreach($json->packages as $package) {
                    if ($package->name == $this->config->project_url) {
                        display( "Composer updated to revision ".$package->source->reference.' ( version : '.$package->version.' )');
                    }
                }

                break;

            default :
                display('No VCS found to update. git, mercurial, svn and bazaar are supported.');
                return;
        }
        
        display('Running files');
        $updateCache = new Files($this->gremlin, new Config(array(1 => '-p',
                                                                  2 => $project)));
        try {
            $updateCache->run();
        } catch (NoFileToProcess $e) {
            // OK, just carry on.
        }
    }
}

?>
