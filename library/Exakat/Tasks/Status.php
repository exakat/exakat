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
use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\ProjectNeeded;
use Exakat\Reports\Reports;
use Exakat\Vcs\Vcs;

class Status extends Tasks {
    const CONCURENCE = self::ANYTIME;

    public function run() {
        $project = $this->config->project;

        if ($project === 'default') {
            $status = array();

            if (file_exists("$this->exakatDir/Project.json")) {
                if (file_exists("$this->exakatDir/Project.json")) {
                    $json = file_get_contents("$this->exakatDir/Project.json");
                    if (empty($json)) {
                        $projectStatus = '';
                        $projectStep = '';
                    } else {
                        $json = json_decode($json);
                        $projectStatus = $json->project;
                        $projectStep = $json->step;
                    }
                } else {
                    $projectStatus = '';
                    $projectStep = '';
                }

                $status = array('Running'  => 'Project',
                                'project'  => $projectStatus,
                                'step'     => $projectStep,);
            } else {
                $status['Running'] = 'idle';
            }

            $this->display($status, $this->config->json);
            return;
        }

        $path = $this->config->projects_root . '/projects/' . $project;

        if (!file_exists($path . '/')) {
            throw new NoSuchProject($project);
        }

        $status = array('project' => $project,
                        'files'   => $this->datastore->getHash('files')  ?? -1,
                        'loc'     => $this->datastore->getHash('loc')    ?? -1,
                        'tokens'  => $this->datastore->getHash('tokens') ?? -1,
                        );
        if (file_exists("$this->exakatDir/Project.json")) {
            $text = file_get_contents("$this->exakatDir/Project.json");
            if (empty($text)) {
                 $inited = $this->datastore->getHash('inited');
                 $status['status'] = empty($inited) ? 'Init phase' : 'Not running';
            } else {
             $json = json_decode($text);
             if ($json->project === $project) {
                 $status['status'] = $json->step;
             } else {
                 $inited = $this->datastore->getHash('inited');
                 $status['status'] = empty($inited) ? 'Init phase' : 'Not running';
             }
            }
        } else {
            $inited = $this->datastore->getHash('inited');
            $status['status'] = empty($inited) ? 'Init phase' : 'Not running';
        }

        if (($vcsClass = Vcs::getVcs($this->config)) === 'None') {
            $status['hash']      = 'None';
            $status['updatable'] = 'N/A';
        } else {
            $vcs = new $vcsClass($this->config->project, $this->config->projects_root);
            $status = array_merge($status, $vcs->getStatus());
        }

        $status['updatable'] = $status['updatable'] === true ? 'Yes' : 'No';

        // Check the logs
        $errors = $this->getErrors($path);
        if (!empty($errors)) {
            $status['errors'] = $errors;
        }

        // Status of progress
        // errors?

        $formats = array();
        foreach(Reports::$FORMATS as $format) {
            $a = $this->datastore->getHash($format);
            if (!empty($a)) {
                $formats[$format] = $a;
            }
        }
        // Always have formats, even if empty
        $status['formats'] = $formats;

        $this->display($status, $this->config->json);
    }

    private function display($status, $json = false) {
        // Json publication
        if ($json === true) {
            print json_encode($status);
            return;
        }

        // commandline publication
        $text = '';
        $size = 0;
        foreach($status as $k => $v) {
            $size = max($size, strlen($k));
        }

        foreach($status as $field => $value) {
            if (is_array($value)) {
                $sub = substr($field . str_repeat(' ', $size), 0, $size) . ' : ' . PHP_EOL;

                $sizea = 0;
                foreach($value as $k => $v) {
                    $sizea = max($sizea, strlen($k));
                }
                foreach($value as $k => $v) {
                    $sub .= '    ' . substr($k . str_repeat(' ', $sizea), 0, $sizea) . " : $v" . PHP_EOL;
                }
                $text .= PHP_EOL . $sub . PHP_EOL;
            } else {
                $text .= substr($field . str_repeat(' ', $size), 0, $size) . ' : ' . $value . PHP_EOL;
            }
        }

        print $text;
    }

    private function getErrors($path) {
        $errors = array();

        // Init error
        $e = $this->datastore->getHash('init error');
        if (!empty($e)) {
            $errors['init error'] = $e;
            return $errors;
        }

        // Size error
        $e = $this->datastore->getHash('token error');
        if (!empty($e)) {
            $errors['init error'] = $e;
            return $errors;
        }

        return $errors;
    }
}

?>
