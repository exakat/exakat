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
use Exakat\Configsource\ProjectConfig;
use Exakat\Datastore;
use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\ProjectNeeded;
use Exakat\Exceptions\HelperException;
use Exakat\Exceptions\VcsError;
use Exakat\Project;
use Exakat\Vcs\Vcs;
use Exakat\Vcs\{Bazaar, Composer, Copy, EmptyCode, Git, Mercurial, Svn, Symlink, Tarbz, Targz, Zip};

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
        $finalPath = "{$this->config->projects_root}/projects/$project";

        if (file_exists($finalPath)) {
            display( "$finalPath already exists. Reusing.\n");

            return;
        }

        $tmpPath = "{$this->config->projects_root}/projects/.$project";
        if (file_exists($tmpPath)) {
            display("Removing tmpPath : $tmpPath\n");
            rmdirRecursive($tmpPath);
        }
        
        mkdir($tmpPath, 0755);
        mkdir("{$tmpPath}/log/", 0755);

        $repositoryBranch    = '';
        $repositoryTag       = '';
        $include_dirs        = $this->config->include_dirs;
 
        if ($this->config->symlink === true) {
            $vcs = 'symlink';
            $projectName = basename($repositoryURL);
        } elseif ($this->config->svn === true) {
            $vcs = 'svn';
            $projectName = basename($repositoryURL);
            if (in_array($projectName, array('trunk', 'code'))) {
                $projectName = basename(dirname($repositoryURL));
                if (in_array($projectName, array('trunk', 'code'))) {
                    $projectName = basename(dirname($repositoryURL, 2));
                }
            }
        } elseif ($this->config->git === true) {
            $vcs = 'git';
            $projectName = basename($repositoryURL);
            $projectName = str_replace('.git', '', $projectName);
            
            if (!empty($this->config->branch) &&
                $this->config->branch !== 'master') {
                $repositoryBranch =  $this->config->branch;
                $repositoryTag =  '';
            } elseif (!empty($this->config->tag)) {
                $repositoryBranch =  '';
                $repositoryTag =  $this->config->tag;
            } else {
                $repositoryBranch =  'master';
                $repositoryTag =  '';
            }
        
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
            $projectName = str_replace(array('.tgz', '.tar.gz'), '', $projectName);
        } elseif ($this->config->composer === true) {
            $vcs = 'composer';
            $projectName = str_replace('/', '_', $repositoryURL);
 
            // Updating config.ini to include the vendor directory
            $include_dirs[] = "/vendor/$repositoryURL";
        } else {
            $vcs = '';
            $projectName = basename($repositoryURL);
            $projectName = preg_replace('/\.git/', '', $projectName);
        }
 
        // default initial config. Found in test project.
        $phpversion = $this->config->phpversion;
        if ($this->config->composer === true) {
            $ignore_dirs = $this->config->ignore_dirs;
        } else {
            $ignore_dirs = array_merge($this->config->ignore_dirs, array('/vendor'));
        }
 
        $projectConfig = new ProjectConfig($this->config->projects_root);
        $projectConfig->setProject($project);
        $projectConfig->setConfig('phpversion',     $phpversion);
        $projectConfig->setConfig('project_name',   $projectName);
        $projectConfig->setConfig('project_url',    $repositoryURL);
        $projectConfig->setConfig('project_vcs',    $vcs);
        $projectConfig->setConfig('project_tag',    $repositoryBranch);
        $projectConfig->setConfig('project_branch', $repositoryTag);
 
        $projectConfig->setConfig('ignore_dirs',    $ignore_dirs);
        $projectConfig->setConfig('include_dirs',   $include_dirs);

        shell_exec("chmod -R g+w $tmpPath");
        $repositoryDetails = parse_url($repositoryURL);

        $skipFiles           = false;
        $dotProject          = ".$project";
        
        $vcsClass = Vcs::getVcs($this->config);
        $vcs = new $vcsClass($dotProject, $this->config->projects_root);
        if (!empty($this->config->branch)){
            $vcs->setBranch($this->config->branch);
        }

        if (!empty($this->config->tag)){
            $vcs->setTag($this->config->tag);
        }

        try {
            $vcs->clone($repositoryURL);
        } catch (VcsError $e) {
            rename($tmpPath, $finalPath);

            $this->datastore = new Datastore($this->config, Datastore::CREATE);
            $errorMessage = $e->getMessage();
            $this->datastore->addRow('hash', array('init error' => $errorMessage,
                                                   'inited'     => date('r')));
            display("An error prevented code initialization : '$errorMessage'\n.No code was loaded.");

            $projectConfig->writeConfig();
            
            return;
        }

        rename($tmpPath, $finalPath);
        $projectConfig->writeConfig();
        $this->datastore = new Datastore($this->config, Datastore::CREATE);

        $this->datastore->addRow('hash', array('status' => 'Cloned',
                                              ));

        if (!$skipFiles) {
            display('Running files');

            // Running script as a separate process, to take into account the actual config file..
            $shell = "{$this->config->php} {$this->config->executable} files -p {$this->config->project}";
            $res = shell_exec($shell);
            
            if (!empty($res)) {
                $this->datastore->addRow('hash', array('init error' => $res ));
            }
        }

        $this->datastore->addRow('hash', array('status' => 'Initproject',
                                               'inited' => date('r')));
    }
}


?>
