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

use Exakat\Analyzer\Analyzer;
use Exakat\Config;
use Exakat\Datastore;
use Exakat\Exakat;
use Exakat\Exceptions\NoFileToProcess;
use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\ProjectNeeded;

class Project extends Tasks {
    const CONCURENCE = self::NONE;

    private $project_dir = '.';

    protected $themesToRun = array('CompatibilityPHP53', 'CompatibilityPHP54', 'CompatibilityPHP55', 'CompatibilityPHP56',
                                   'CompatibilityPHP70', 'CompatibilityPHP71', 'CompatibilityPHP72', 'CompatibilityPHP73',
                                   'Analyze', 'Preferences', 'Inventory', 'Performances',
                                   'Appinfo', 'Appcontent', 'Dead code', 'Security', 'Custom',
                                   );

    protected $reports = array();

    public function __construct($gremlin, $config, $subTask = self::IS_NOT_SUBTASK) {
        parent::__construct($gremlin, $config, $subTask);

        if (empty($this->reports)) {
            $this->reports = makeArray($config->project_reports);
        }
    }

    public function run() {
        $project = $this->config->project;

        $this->project_dir = $this->config->projects_root.'/projects/'.$project;

        if ($this->config->project === 'default') {
            throw new ProjectNeeded();
        }

        if (!file_exists($this->config->projects_root.'/projects/'.$project)) {
            throw new NoSuchProject($this->config->project);
        }

        // cleaning log directory (possibly logs)
        $logs = glob($this->config->projects_root.'/projects/'.$project.'/log/*');
        foreach($logs as $log) {
            unlink($log);
        }

        display("Search for external libraries".PHP_EOL);
        if (file_exists($this->config->projects_root.'/projects/'.$project.'/config.cache')) {
            unlink($this->config->projects_root.'/projects/'.$project.'/config.cache');
        }
        $args = array ( 1 => 'findextlib',
                        2 => '-p',
                        3 => $this->config->project,
                        4 => '-u',
                        );

        $configThema = new Config($args);

        $analyze = new FindExternalLibraries($this->gremlin, $configThema, Tasks::IS_SUBTASK);
        $analyze->run();

        $this->addSnitch(array('step'    => 'External lib',
                               'project' => $this->config->project));
        unset($analyze);

        $this->logTime('Start');
        $this->addSnitch(array('step'    => 'Start',
                               'project' => $this->config->project));

        // cleaning datastore
        $this->datastore = new Datastore($this->config, Datastore::CREATE);

        $audit_start = time();
        $this->datastore->addRow('hash', array('audit_start'    => $audit_start,
                                               'exakat_version' => Exakat::VERSION,
                                               'exakat_build'   => Exakat::BUILD,
                                               'php_version'    => $this->config->phpversion,
                                               'audit_name'     => $this->generateName(),
                                         ));

        if (file_exists($this->config->projects_root.'/projects/'.$this->config->project.'/code/.git/config')) {
            $info = array();
            $info['vcs_type'] = 'git';
            
            $gitConfig = file_get_contents($this->config->projects_root.'/projects/'.$this->config->project.'/code/.git/config');
            if (preg_match('#url = (\S+)\s#is', $gitConfig, $r)) {
                $info['vcs_url'] = $r[1];
            } else {
                $info['vcs_url'] = 'No URL';
            }

            $res = shell_exec('cd '.$this->config->projects_root.'/projects/'.$this->config->project.'/code/; git branch');
            $info['vcs_branch'] = trim($res, " *\n");

            $res = shell_exec('cd '.$this->config->projects_root.'/projects/'.$this->config->project.'/code/; git rev-parse HEAD');
            $info['vcs_revision'] = trim($res);
        } else {
            $info = array();
            $info['vcs_type'] = 'Downloaded archive';
        }
        $this->datastore->addRow('hash', $info);

        display("Running project '$project'".PHP_EOL);
        display("Running the following analysis : ".implode(', ', $this->themesToRun));
        display("Producing the following reports : ".implode(', ', $this->reports));

        display("Cleaning DB".PHP_EOL);
        $analyze = new CleanDb($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        $analyze->run();
        unset($analyze);
        $this->logTime('CleanDb');
        $this->addSnitch(array('step'    => 'Clean DB',
                               'project' => $this->config->project));

        display("Running files".PHP_EOL);
        $analyze = new Files($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        $analyze->run();
        unset($analyze);
        $this->logTime('Files');
        $this->addSnitch(array('step'    => 'Files',
                               'project' => $this->config->project));

        $this->checkTokenLimit();

        $analyze = new Load($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        try {
            $analyze->run();
        } catch (NoFileToProcess $e) {
            $this->datastore->addRow(array('init error' => $e->getMessage(),
                                           'status'     => 'Error',
                                           ));
        }
        unset($analyze);
        display("Project loaded".PHP_EOL);
        $this->logTime('Loading');

        // Always run this one first
        $this->analyzeThemes(['First'], $audit_start, true);

        // Dump is a child process
        // initialization and first collection (action done once)
        $shell = $this->config->php.' '.$this->config->executable.' dump -p '.$this->config->project.' -T First -collect';
        shell_exec($shell);
        $this->logTime('Dumped and inited');

        if ($this->config->program !== null) {
            $this->analyzeOne($this->config->program, $audit_start, $this->config->quiet);
        } else {
            $this->analyzeThemes($this->themesToRun, $audit_start, $this->config->quiet);
        }

        display("Analyzed project".PHP_EOL);
        $this->logTime('Analyze');
        $this->addSnitch(array('step'    => 'Analyzed',
                               'project' => $this->config->project));

        $this->logTime('Analyze');

        foreach($this->reports as $format) {
            display("Reporting $format".PHP_EOL);
            $this->addSnitch(array('step'    => 'Report : '.$format,
                                   'project' => $this->config->project));

            $args = array ( 1 => 'report',
                            2 => '-p',
                            3 => $this->config->project,
                            4 => '-file',
                            5 => constant('\\Exakat\\Reports\\'.$format.'::FILE_FILENAME'),
                            6 => '-format',
                            7 => $format,
                            );
            $reportConfig = new Config($args);

            try {
                $report = new Report2($this->gremlin, $reportConfig, Tasks::IS_SUBTASK);
                $report->run();
                unset($report);
            } catch (\Exception $e) {
                echo "Error while building $format in $format ".PHP_EOL,
                     $e->getMessage(),
                     PHP_EOL."Trying next report".PHP_EOL;
            }
            unset($reportConfig);
        }

        display("Reported project".PHP_EOL);

        $this->logTime('Final');
        $this->removeSnitch();
        display("End" . PHP_EOL);
    }

    private function logTime($step) {
        static $log, $begin, $end, $start;

        if ($log === null) {
            $log = fopen($this->project_dir.'/log/project.timing.csv', 'w+');
        }
        $end = microtime(true);
        if ($begin === null) {
            $begin = $end;
            $start = $end;
        }

        fwrite($log, $step."\t".($end - $begin)."\t".($end - $start).PHP_EOL);
        $begin = $end;
    }

    private function analyzeOne($analyzers, $audit_start, $quiet) {
        $this->addSnitch(array('step'    => 'Analyzer',
                               'project' => $this->config->project));

        $args = array ( 1 => 'analyze',
                        2 => '-p',
                        3 => $this->config->project,
                        4 => '-P',
                        5 => $analyzers,
                        6 => '-norefresh',
                        7 => '-u'
                        );
        if ($quiet === true) {
            $args[] = '-q';
        }

        try {
            $analyzeConfig = new Config($args);

            $analyze = new Analyze($this->gremlin, $analyzeConfig, Tasks::IS_SUBTASK);
            $analyze->run();
            unset($analyze);
            unset($analyzeConfig);
            $this->logTime('Analyze : '.(is_array($analyzers) ? implode(', ', $analyzers) : $analyzers));

            $args = array ( 1 => 'dump',
                            2 => '-p',
                            3 => $this->config->project,
                            4 => '-P',
                            5 => $analyzers,
                            6 => '-u',
                        );
            $dumpConfig = new Config($args);

            $audit_end = time();
            $query = "g.V().count()";
            $res = $this->gremlin->query($query);
            if (is_object($res)) {
                $nodes = $res->results[0];
            } else {
                $nodes = $res[0];
            }
            $query = "g.E().count()";
            $res = $this->gremlin->query($query);
            if (is_object($res)) {
                $links = $res->results[0];
            } else {
                $links = $res[0];
            }

            $this->datastore->addRow('hash', array('audit_end'    => $audit_end,
                                                   'audit_length' => $audit_end - $audit_start,
                                                   'graphNodes'   => $nodes,
                                                   'graphLinks'   => $links));

            $dump = new Dump($this->gremlin, $dumpConfig, Tasks::IS_SUBTASK);
            $dump->run();
            unset($dump);
            unset($dumpConfig);

        } catch (\Exception $e) {
            echo "Error while running the Analyzer {$this->config->project} ".PHP_EOL,
                 $e->getMessage(),
                 PHP_EOL."Trying next analysis".PHP_EOL;
            file_put_contents($this->config->projects_root.'/projects/'.$this->config->project.'/log/analyze.final.log', $e->getMessage());
        }
    }

    private function analyzeThemes($themes, $audit_start, $quiet) {
        if (empty($themes)) {
            $themes = $this->config->project_themes;
        }

        if (!is_array($themes)) {
            $themes = array($themes);
        }
        
        $availableThemes = $this->themes->listAllThemes();

        $diff = array_diff($themes, $availableThemes);
        if (!empty($diff)) {
            display("Ignoring the following unknown themes : ".implode(', ', $diff).PHP_EOL);
        }
        
        $themes = array_intersect($availableThemes, $themes);
        display("Running the following themes : ".implode(', ', $themes).PHP_EOL);

        global $VERBOSE;
        $oldVerbose = $VERBOSE;
        $VERBOSE = false;
        foreach($themes as $theme) {
            $this->addSnitch(array('step'    => 'Analyze : '.$theme,
                                   'project' => $this->config->project));
            $themeForFile = strtolower(str_replace(' ', '_', trim($theme, '"')));

            $args = array ( 1 => 'analyze',
                            2 => '-p',
                            3 => $this->config->project,
                            4 => '-T',
                            5 => $theme,
                            6 => '-norefresh',
                            7 => '-u'
                            );
            if ($quiet === true) {
                $args[] = '-q';
            }

            try {
                $analyzeConfig = new Config($args);

                $analyze = new Analyze($this->gremlin, $analyzeConfig, Tasks::IS_SUBTASK);
                $analyze->run();
                unset($analyze);
                unset($analyzeConfig);
                $this->logTime('Analyze : '.$theme);

                $args = array ( 1 => 'dump',
                                2 => '-p',
                                3 => $this->config->project,
                                4 => '-T',
                                5 => trim($theme, '"'), // No need to protect anymore, as this is internal
                                6 => '-u',
                            );

                $dumpConfig = new Config($args);

                $audit_end = time();
                $query = "g.V().count()";
                $res = $this->gremlin->query($query);
                if (isset($res->results)) {
                    $nodes = $res->results[0];
                } else {
                    $nodes = $res[0];
                }
                $query = "g.E().count()";
                $res = $this->gremlin->query($query);
                if (isset($res->results)) {
                    $links = $res->results[0];
                } else {
                    $links = $res[0];
                }

                $finalMark = array('audit_end'    => $audit_end,
                                   'audit_length' => $audit_end - $audit_start,
                                   'graphNodes'   => $nodes,
                                   'graphLinks'   => $links);
                $this->datastore->addRow('hash', $finalMark);
                
                $dump = new Dump($this->gremlin, $dumpConfig, Tasks::IS_SUBTASK);
                $dump->run();
                $dump->finalMark($finalMark);
                unset($dump);
                unset($dumpConfig);
            } catch (\Exception $e) {
                echo "Error while running the Analyze $theme ".PHP_EOL,
                     $e->getMessage(),
                     PHP_EOL."Trying next analysis".PHP_EOL;
                file_put_contents($this->config->projects_root.'/projects/'.$this->config->project.'/log/analyze.'.$themeForFile.'.final.log', $e->getMessage());
            }
        }
        $VERBOSE = $oldVerbose;
    }
    
    private function generateName() {
        $ini = parse_ini_file($this->config->dir_root.'/data/audit_names.ini');
        
        $names = $ini['names'];
        $adjectives = $ini['adjectives'];
        
        shuffle($names);
        shuffle($adjectives);
        
        $x = mt_rand(0, PHP_INT_MAX);
        
        $name = $names[ $x % (count($names) - 1)];
        $adjective = $adjectives[ $x % (count($adjectives) - 1)];

        return ucfirst($adjective).' '.$name;
    }
}

?>
