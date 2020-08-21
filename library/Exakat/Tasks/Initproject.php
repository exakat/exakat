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

use Exakat\Configsource\ProjectConfig;
use Exakat\Exceptions\InvalidProjectName;
use Exakat\Exceptions\ProjectNeeded;
use Exakat\Exceptions\VcsError;
use Exakat\Project;
use Exakat\Vcs\Vcs;
use Exakat\Vcs\None;

class Initproject extends Tasks {
    const CONCURENCE = self::ANYTIME;

    public function run(): void {
        if ($this->config->project === 'default') {
            throw new ProjectNeeded();
        }

        if ($this->config->project === 'test') {
            throw new InvalidProjectName('Can\t use test as project name.');
        }

        if (!$this->config->project->validate()) {
            throw new InvalidProjectName($this->config->project->getError());
        }

        $repositoryURL = $this->config->repository;

        if ($this->config->delete === true) {
            display("Deleting {$this->config->project}");

            // final wait..., just in case
            sleep(2);

            rmdirRecursive("{$this->config->projects_root}/projects/{$this->config->project}");
        }

        display("Initializing {$this->config->project}" . (!empty($repositoryURL) ? " with $repositoryURL" : '') );
        $this->initProject($this->config->project, $repositoryURL ?: '');

        display('Done');
    }

    private function initProject(Project $project, string $repositoryURL): void {
        $finalPath = "{$this->config->projects_root}/projects/$project";

        if (file_exists($finalPath)) {
            display( "$finalPath already exists. Reusing it.\n");

            return;
        }

        $tmpPath = "{$this->config->projects_root}/projects/$project";
        if (file_exists($tmpPath)) {
            display("Removing tmpPath : $tmpPath\n");
            rmdirRecursive($tmpPath);
        }

        if (!file_exists("{$this->config->projects_root}/projects/")) {
            mkdir("{$this->config->projects_root}/projects/", 0755);
        }

        if (!mkdir($tmpPath, 0755)) {
            die("Could not create project directory '$project'");
        }

        if (!mkdir("{$tmpPath}/log/", 0755)) {
            die("Could not finalyze project directory '$project'");
        }

        $repositoryBranch    = '';
        $repositoryTag       = '';
        $include_dirs        = $this->config->include_dirs;

        $dotProject          = ".$project";
        if (empty($repositoryURL)) {
            $vcs = new None($dotProject, "$tmpPath/code");
            $projectName = $project;
        } else {
            $vcsClass = Vcs::getVcs($this->config);
            $vcs = new $vcsClass($dotProject, "$tmpPath/code");

            switch($vcs->getName()) {
                case 'symlink' :
                    $projectName = basename($repositoryURL);
                    break;

                case 'svn' :
                    $projectName = basename($repositoryURL);
                    if (in_array($projectName, array('trunk', 'code'))) {
                        $projectName = basename(dirname($repositoryURL));
                        if (in_array($projectName, array('trunk', 'code'))) {
                            $projectName = basename(dirname($repositoryURL, 2));
                        }
                    }
                    break;

                case 'git' :
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
                    break;

                case 'cvs' :
                    $projectName = basename($repositoryURL);
                    break;

                case 'copy' :
                    $projectName = basename($repositoryURL);
                    break;

                case 'mercurial' :
                    $projectName = basename($repositoryURL);
                    break;

                case 'bazaar' :
                    list(, $projectName) = explode(':', $repositoryURL);
                    break;

                case 'zip' :
                    $projectName = basename($repositoryURL);
                    $projectName = str_replace('.zip', '', $projectName);
                    break;

                case 'rar' :
                    $projectName = basename($repositoryURL);
                    $projectName = str_replace('.rar', '', $projectName);
                    break;

                case 'targz' :
                    $projectName = basename($repositoryURL);
                    $projectName = str_replace(array('.tgz', '.tar.gz'), '', $projectName);
                    break;

                case 'tarbz' :
                    $projectName = basename($repositoryURL);
                    $projectName = str_replace(array('.tbz', '.tar.bz'), '', $projectName);
                    break;

                case 'composer' :
                    $projectName = str_replace('/', '_', $repositoryURL);

                    // Updating config.ini to include the vendor directory
                    $include_dirs[] = "/vendor/$repositoryURL";
                    break;

                default :
                    $projectName = basename($repositoryURL);
                    $projectName = str_replace('/\.git/', '', $projectName);
                    break;
            }
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
        $projectConfig->setConfig('project_tag',    $repositoryTag);
        $projectConfig->setConfig('project_branch', $repositoryBranch);

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
            $vcs->clone((string) $repositoryURL);
        } catch (VcsError $e) {
            rename($tmpPath, $finalPath);

            $this->datastore = exakat('datastore');
            $this->datastore->create();

            $errorMessage = $e->getMessage();
            $this->datastore->addRow('hash', array('init error' => $errorMessage,
                                                   'inited'     => date('r')));
            display("An error prevented code initialization : '$errorMessage'\n.No code was loaded.");

            file_put_contents("{$this->config->project_dir}/config.ini", $projectConfig->getConfig($this->config->dir_root));

            return;
        }

        rename($tmpPath, $finalPath);
        file_put_contents("{$this->config->project_dir}/config.ini", $projectConfig->getConfig($this->config->dir_root));
        $this->datastore = exakat('datastore');
        $this->datastore->create();

        $this->datastore->addRow('hash', array('status' => 'Cloned',
                                              ));

        $this->datastore->addRow('hash', array('status' => 'Initproject',
                                               'inited' => date('r')));
    }
}

?>