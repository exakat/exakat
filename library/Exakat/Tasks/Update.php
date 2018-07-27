<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
use Exakat\Vcs\Bazaar;
use Exakat\Vcs\Composer;
use Exakat\Vcs\Copy;
use Exakat\Vcs\EmptyCode;
use Exakat\Vcs\Git;
use Exakat\Vcs\Mercurial;
use Exakat\Vcs\Svn;
use Exakat\Vcs\Symlink;
use Exakat\Vcs\Tarbz;
use Exakat\Vcs\Targz;
use Exakat\Vcs\Zip;

class Update extends Tasks {
    const CONCURENCE = self::ANYTIME;

    protected $logname = self::LOG_NONE;

    public function run() {
        if ($this->config->project === 'default') {
            $this->runDefault();
        } else {
            $this->runProject($this->config->project);
        }
    }
    
    private function runDefault() {
        $paths = glob("{$this->config->projects_root}/projects/*");
        $projects = array_map('basename', $paths);
        $projects = array_diff($projects, array('test'));
        
        print "Updating ".count($projects)." projects".PHP_EOL;
        shuffle($projects);
        foreach($projects as $project) {
            display("updating $project".PHP_EOL);

            $args = array ( 1 => 'update',
                            2 => '-p',
                            3 => $project,
            );
            $updateConfig = new Config($args);

            $this->update($updateConfig);
        }
    }
    
    private function runProject($project) {
        $path = "{$this->config->projects_root}/projects/$project";
    
        if (!file_exists($path)) {
            throw new NoSuchProject($this->config->project);
        }

        if (!is_dir($path)) {
            throw new NoSuchProject($this->config->project);
        }
    
        if (!file_exists("$path/code")) {
            throw new NoCodeInProject($this->config->project);
        }
        
        $this->update($this->config);
    }
    
    private function update(Config $updateConfig) {
        switch(true) {
            // symlink case
            case $updateConfig->project_vcs === 'rar' :
            case $updateConfig->project_vcs === 'zip' :
            case $updateConfig->project_vcs === 'tgz' :
            case $updateConfig->project_vcs === 'tbz' :
            case $updateConfig->project_vcs === 'symlink' :
                // Nothing to do just ignore
                break;

            // copy case
            case $updateConfig->project_vcs === 'copy' :
                $vcs = new Copy($updateConfig->project, $updateConfig->projects_root);
                $new = $vcs->update();
                display("Source copied again");
                break;

            // svn case
            case $updateConfig->project_vcs === 'svn' :
                display("SVN update $updateConfig->project");
                $vcs = new Svn($updateConfig->project, $updateConfig->projects_root);
                $new = $vcs->update();
                display("SVN updated to revision $new");
                break;

            // bazaar case
            case $updateConfig->project_vcs === 'bzr' :
                display("Bazaar update $updateConfig->project");
                $vcs = new Bazaar($updateConfig->project, $updateConfig->projects_root);
                $new = $vcs->update();
                display( "Bazaar updated to revision $new");
                break;

            // mercurial
            case $updateConfig->project_vcs === 'hg' :
                display("Mercurial update $updateConfig->project");
                $vcs = new Mercurial($updateConfig->project, $updateConfig->projects_root);
                $new = $vcs->update();
                display("Mercurial updated to revision $new");
                break;

            // composer case
            case $updateConfig->project_vcs === 'composer' :
                display("Composer update $updateConfig->project");
                $vcs = new Composer($updateConfig->project, $updateConfig->projects_root);
                $new = $vcs->update();
                display("Composer updated to version $new");
                break;

            // Git case
            case $updateConfig->project_vcs === 'git' :
                display("Git pull for $updateConfig->project");
                $vcs = new Git($updateConfig->project, $updateConfig->projects_root);
                $new = $vcs->update();
                display("Updated git version $new");
                break;

            default :
                display('No VCS found to update. check project/config.ini and try again.');
                return;
        }
        
        display('Running files');
        $updateCache = new Files($this->gremlin, $updateConfig);
        try {
            $updateCache->run();
        } catch (NoFileToProcess $e) {
            display("No file to process\n");
            // OK, just carry on.
        }
    }
}

?>
