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
            print "Removing tmpPath : $tmpPath\n";
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
            $projectName = str_replace(array('.tbz', '.tar.bz2'), '', $projectName);
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

        switch (true) {
            // Symlink
            case ($this->config->symlink === true) :
                display('Symlink initialization : '.realpath($repositoryURL));
                $vcs = new Symlink($dotProject, $this->config->projects_root);
                break;

            // Initialization by copy
            case ($this->config->copy === true) :
                display('Copy initialization');
                $vcs = new Copy($dotProject, $this->config->projects_root);
                break;

            // Empty initialization
            case ($repositoryURL === '' || $repositoryURL === false) :
                display('Empty initialization');
                $vcs = new EmptyCode($dotProject, $this->config->projects_root);
                $repositoryURL = '';

                $skipFiles = true;
                break;

            // composer archive (early in the list, as this won't have 'scheme'
            case ($this->config->composer === true) :
                display('Initialization with composer');
                $vcs = new Composer($dotProject, $this->config->projects_root);
                break;

            // SVN
            case (isset($repositoryDetails['scheme']) && $repositoryDetails['scheme'] == 'svn' || $this->config->svn === true) :
                display('SVN initialization');
                $vcs = new Svn($dotProject, $this->config->projects_root);
                break;

            // Bazaar
            case ($this->config->bzr === true) :
                display('Bazaar initialization');
                $vcs = new Bazaar($dotProject, $this->config->projects_root);
                break;

            // HG
            case ($this->config->hg === true) :
                display('Mercurial initialization');
                $vcs = new Mercurial($dotProject, $this->config->projects_root);
                break;

            // Tbz archive
            case ($this->config->tbz === true) :
                display('Download the tar.bz2');
                $vcs = new Tarbz($dotProject, $this->config->projects_root);
                break;

            // tgz archive
            case ($this->config->tgz === true) :
                display('Download the tar.gz');
                $vcs = new Targz($dotProject, $this->config->projects_root);
                break;

            // zip archive
            case ($this->config->zip === true) :
                display('Download the zip');
                $vcs = new Zip($dotProject, $this->config->projects_root);
                break;

            // Git
            // Git is last, as it will act as a default
            case ((isset($repositoryDetails['scheme']) && $repositoryDetails['scheme'] === 'git') || $this->config->git === true) :
                display('Download with git');
                $vcs = new Git($dotProject, $this->config->projects_root);
                
                if (!empty($this->config->branch)){
                    $vcs->setBranch($this->config->branch);
                }

                if (!empty($this->config->tag)){
                    $vcs->setTag($this->config->tag);
                }
                break;

            default :
                display('Empty initialization');
                $vcs = new EmptyCode($dotProject, $this->config->projects_root);
                $skipFiles = true;
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
            $shell = $this->config->php.' '.$this->config->executable.' files -p '.$this->config->project;
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
