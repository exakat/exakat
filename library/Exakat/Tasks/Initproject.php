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

use Exakat\Config;
use Exakat\Configsource\ProjectConfig;
use Exakat\Datastore;
use Exakat\Exceptions\InvalidProjectName;
use Exakat\Exceptions\ProjectNeeded;
use Exakat\Exceptions\HelperException;
use Exakat\Exceptions\VcsError;
use Exakat\Project;
use Exakat\Vcs\Vcs;
use Exakat\Vcs\None;

class Initproject extends Tasks {
    const CONCURENCE = self::ANYTIME;

    public function run() {
        $project = new Project($this->config->project);

        if ($this->config->project === 'default') {
            throw new ProjectNeeded();
        }
        
        if ($this->config->project === 'test') {
            throw new InvalidProjectName('Can\t use test as project name.');
        }

        if (!$project->validate()) {
            throw new InvalidProjectName($project->getError());
        }

        $repositoryURL = $this->config->repository;

        if ($this->config->delete === true) {
            display("Deleting $project");

            // final wait..., just in case
            sleep(2);

            rmdirRecursive("{$this->config->projects_root}/projects/$project");
        }
        
        display("Initializing $project" . (!empty($repositoryURL) ? " with $repositoryURL" : '') );
        $this->init_project($project, $repositoryURL);

        display('Done');
    }

    private function init_project($project, $repositoryURL) {
        $finalPath = "{$this->config->projects_root}/projects/$project";

        if (file_exists($finalPath)) {
            display( "$finalPath already exists. Reusing it.\n");

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

        $dotProject          = ".$project";
        if (empty($repositoryURL)) {
            $vcs = new None($dotProject, "$tmpPath/code");
        } else {
            $vcsClass = Vcs::getVcs($this->config);
            $vcs = new $vcsClass($dotProject, "$tmpPath/code");
        }
 
        if (empty($repositoryURL)) {
            $projectName = $project;
        } elseif ($this->config->symlink === true) {
            $projectName = basename($repositoryURL);
        } elseif ($this->config->svn === true) {
            $projectName = basename($repositoryURL);
            if (in_array($projectName, array('trunk', 'code'))) {
                $projectName = basename(dirname($repositoryURL));
                if (in_array($projectName, array('trunk', 'code'))) {
                    $projectName = basename(dirname($repositoryURL, 2));
                }
            }
        } elseif ($this->config->git === true) {
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
                $repositoryBranch =  '';
                $repositoryTag =  '';
            }
        } elseif ($this->config->cvs === true) {
            $projectName = basename($repositoryURL);
        } elseif ($this->config->copy === true) {
            $projectName = basename($repositoryURL);
        } elseif ($this->config->bzr === true) {
            list(, $projectName) = explode(':', $repositoryURL);
        } elseif ($this->config->hg === true) {
            $projectName = basename($repositoryURL);
        } elseif ($this->config->zip === true) {
            $projectName = basename($repositoryURL);
            $projectName = str_replace('.zip', '', $projectName);
        } elseif ($this->config->rar === true) {
            $projectName = basename($repositoryURL);
            $projectName = str_replace('.rar', '', $projectName);
        } elseif ($this->config->tgz === true) {
            $projectName = basename($repositoryURL);
            $projectName = str_replace(array('.tgz', '.tar.gz'), '', $projectName);
        } elseif ($this->config->composer === true) {
            $projectName = str_replace('/', '_', $repositoryURL);
 
            // Updating config.ini to include the vendor directory
            $include_dirs[] = "/vendor/$repositoryURL";
        } else {
            $projectName = basename($repositoryURL);
            $projectName = str_replace('/\.git/', '', $projectName);
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
        $projectConfig->setConfig('project_vcs',    $vcs->getName());
        $projectConfig->setConfig('project_tag',    $repositoryBranch);
        $projectConfig->setConfig('project_branch', $repositoryTag);
 
        $projectConfig->setConfig('ignore_dirs',    $ignore_dirs);
        $projectConfig->setConfig('include_dirs',   $include_dirs);

        shell_exec("chmod -R g+w $tmpPath");

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

        display('Running files');

        // Running script as a separate process, to take into account the actual config file..
        $shell = "{$this->config->php} {$this->config->executable} files -p {$this->config->project}";
        $res = shell_exec($shell);
        
        if (!empty($res)) {
            $this->datastore->addRow('hash', array('init error' => $res ));
        }

        $this->datastore->addRow('hash', array('status' => 'Initproject',
                                               'inited' => date('r')));
    }
}

?>