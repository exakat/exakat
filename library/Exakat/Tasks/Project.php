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

use Exakat\Analyzer\Analyzer;
use Exakat\Config;
use Exakat\Datastore;
use Exakat\Exakat;
use Exakat\Exceptions\ProjectNeeded;
use Exakat\Exceptions\NoSuchProject;

class Project extends Tasks {
    const CONCURENCE = self::NONE;

    private $project_dir = '.';

    protected $themes = array('CompatibilityPHP53', 'CompatibilityPHP54', 'CompatibilityPHP55', 'CompatibilityPHP56',
                              'CompatibilityPHP70', 'CompatibilityPHP71', 'CompatibilityPHP72', 'CompatibilityPHP73',
                              'Analyze', 'Preferences',
                              'Appinfo', 'Appcontent', '"Dead code"', 'Security', 'Custom',
                              );

    protected $reports = array();

    public function __construct($gremlin, $config, $subTask = self::IS_NOT_SUBTASK) {
        parent::__construct($gremlin, $config, $subTask);

        if (is_array($config->project_reports)) {
            $this->reports = $config->project_reports;
        } else {
            $this->reports = array($config->project_reports);
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

        $this->logTime('Start');
        $this->addSnitch(array('step'    => 'Start',
                               'project' => $this->config->project));

        // cleaning datastore
        $this->datastore = new Datastore($this->config, Datastore::CREATE);

        $audit_start = time();
        $this->datastore->addRow('hash', array('audit_start'    => $audit_start,
                                               'exakat_version' => Exakat::VERSION,
                                               'exakat_build'   => Exakat::BUILD,
                                         ));

        display("Running project '$project'\n");
        display("Running the following analysis : ".implode(', ', $this->config->project_themes));
        display("Producing the following reports : ".implode(', ', $this->reports));

        display("Cleaning DB\n");
        $analyze = new CleanDb($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        $analyze->run();
        unset($analyze);
        $this->logTime('CleanDb');
        $this->addSnitch(array('step'    => 'Clean DB',
                               'project' => $this->config->project));

        display("Search for external libraries\n");
        $args = array ( 1 => 'findextlib',
                        2 => '-p',
                        3 => $this->config->project,
                        4 => '-u',
                        );

        $configThema = Config::push($args);

        $analyze = new FindExternalLibraries($this->gremlin, $configThema, Tasks::IS_SUBTASK);
        $analyze->run();

        $this->addSnitch(array('step'   => 'External lib',
                              'project' => $this->config->project));

        Config::pop();
        unset($analyze);

        display("Running files\n");
        $analyze = new Files($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        $analyze->run();
        unset($analyze);
        $this->logTime('Files');
        $this->addSnitch(array('step'    => 'Files',
                               'project' => $this->config->project));

        $this->checkTokenLimit();

        $analyze = new Load($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        $analyze->run();
        unset($analyze);
        display("Project loaded\n");
        $this->logTime('Loading');

        // Dump is a child process
        $shell = $this->config->php.' '.$this->config->executable.' dump -p '.$this->config->project;
        if (!in_array('Codacy', $this->config->project_themes)) {
            $shell .= ' -collect ';
        }
        shell_exec($shell);
        $this->logTime('Dumped and inited');

        if ($this->config->program !== null) {
            $this->analyzeOne($this->config->program, $audit_start);
        } else {
            $this->analyzeThemes($this->config->project_themes, $audit_start);
        }

        display("Analyzed project\n");
        $this->logTime('Analyze');
        $this->addSnitch(array('step'    => 'Analyzed',
                               'project' => $this->config->project));

        $this->logTime('Analyze');

        $oldConfig = Config::factory();
        foreach($this->reports as $format) {
            display("Reporting $format\n");
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
            $this->config = Config::factory($args);

            try {
                $report = new Report2($this->gremlin, $this->config, Tasks::IS_SUBTASK);
                $report->run();
                unset($report);
            } catch (\Exception $e) {
                echo "Error while building $format in $format \n",
                     $e->getMessage(),
                     "\nTrying next report\n";
            }
        }

        Config::factory($oldConfig);
        display("Reported project\n");

        /*
            This is dev code, not production
        $query = <<<GREMLIN
g.V().where( __.sideEffect{x = []; }.in('ANALYZED').sideEffect{ x.add(it.get().value('analyzer')); }.barrier().sideEffect{ y = x.groupBy().findAll{ i,j -> j.size() > 1;};} )
.filter{ y.size() > 0; }
.map{ y; };
GREMLIN;

        $res = $this->gremlin->query($query);
        if (!empty($res)) {
            file_put_contents($this->config->projects_root.'/projects/'.$project.'/log/doublons.log', var_export($res, true));
        }
        */
        $this->logTime('Final');
        $this->removeSnitch();
        display("End\n");
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

        fwrite($log, $step."\t".($end - $begin)."\t".($end - $start)."\n");
        $begin = $end;
    }

    private function analyzeOne($analyzers, $audit_start) {
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
        if ($this->config->quiet === true) {
            $args[] = '-q';
        }

        try {
            $configThema = Config::push($args);

            $analyze = new Analyze($this->gremlin, $configThema, Tasks::IS_SUBTASK);
            $analyze->run();
            unset($analyze);
            $this->logTime('Analyze : '.(is_array($analyzers) ? implode(', ', $analyzers) : $analyzers));

            Config::pop();

            $args = array ( 1 => 'dump',
                            2 => '-p',
                            3 => $this->config->project,
                            4 => '-P',
                            5 => $analyzers,
                            6 => '-u',
                        );

            $configThema = Config::push($args);

            $audit_end = time();
            $query = "g.V().count()";
            $res = $this->gremlin->query($query);
            $nodes = $res->results[0];
            $query = "g.E().count()";
            $res = $this->gremlin->query($query);
            $links = $res->results[0];

            $this->datastore->addRow('hash', array('audit_end'    => $audit_end,
                                                   'audit_length' => $audit_end - $audit_start,
                                                   'graphNodes'   => $nodes,
                                                   'graphLinks'   => $links));

            $dump = new Dump($this->gremlin, $configThema, Tasks::IS_SUBTASK);
            $dump->run();
            unset($dump);

            Config::pop();
        } catch (\Exception $e) {
            echo "Error while running the Analyzer $theme \n",
                 $e->getMessage(),
                 "\nTrying next analysis\n";
            file_put_contents($this->config->projects_root.'/projects/'.$project.'/log/analyze.'.$themeForFile.'.final.log', $e->getMessage());
        }
    }

    private function analyzeThemes($themes, $audit_start) {
        if (empty($themes)) {
            $themes = $this->config->project_themes;
        }

        if (!is_array($themes)) {
            $themes = array($themes);
        }
        
        $availableThemes = Analyzer::listAllThemes();

        $diff = array_diff($themes, $availableThemes);
        if (!empty($diff)) {
            display("Ignoring the following unknown themes : ".implode(', ', $diff)."\n");
        }
        
        $themes = array_intersect($availableThemes, $themes);
        display("Running the following themes : ".implode(', ', $diff)."\n");

        foreach($themes as $theme) {
            $this->addSnitch(array('step'    => 'Analyze : '.$theme,
                                   'project' => $this->config->project));
            $themeForFile = strtolower(str_replace(' ', '_', trim($theme, '"')));

            $args = array ( 1 => 'analyze',
                            2 => '-p',
                            3 => $this->config->project,
                            4 => '-T',
                            5 => trim($theme, '"'), // No need to protect anymore, as this is internal
                            6 => '-norefresh',
                            7 => '-u'
                            );
            if ($this->config->quiet === true) {
                $args[] = '-q';
            }

            try {
                $configThema = Config::push($args);

                $analyze = new Analyze($this->gremlin, $configThema, Tasks::IS_SUBTASK);
                $analyze->run();
                unset($analyze);
                $this->logTime('Analyze : '.$theme);

                Config::pop();

                $args = array ( 1 => 'dump',
                                2 => '-p',
                                3 => $this->config->project,
                                4 => '-T',
                                5 => trim($theme, '"'), // No need to protect anymore, as this is internal
                                6 => '-u',
                            );

                $configThema = Config::push($args);

                $audit_end = time();
                $query = "g.V().count()";
                $res = $this->gremlin->query($query);
                $nodes = $res->results[0];
                $query = "g.E().count()";
                $res = $this->gremlin->query($query);
                $links = $res->results[0];

                $this->datastore->addRow('hash', array('audit_end'    => $audit_end,
                                                       'audit_length' => $audit_end - $audit_start,
                                                       'graphNodes'   => $nodes,
                                                       'graphLinks'   => $links));

                $dump = new Dump($this->gremlin, $configThema, Tasks::IS_SUBTASK);
                $dump->run();
                unset($dump);

                Config::pop();
            } catch (\Exception $e) {
                echo "Error while running the Analyze $theme \n",
                     $e->getMessage(),
                     "\nTrying next analysis\n";
                file_put_contents($this->config->projects_root.'/projects/'.$this->config->project.'/log/analyze.'.$themeForFile.'.final.log', $e->getMessage());
            }
        }
    }
}

?>
