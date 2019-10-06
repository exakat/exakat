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

use Exakat\Analyzer\Analyzer;
use Exakat\Analyzer\Rulesets;
use Exakat\Config;
use Exakat\Datastore;
use Exakat\Exakat;
use Exakat\Exceptions\MissingGremlin;
use Exakat\Exceptions\InvalidProjectName;
use Exakat\Exceptions\NoCodeInProject;
use Exakat\Exceptions\NoFileToProcess;
use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\NoSuchReport;
use Exakat\Exceptions\ProjectNeeded;
use Exakat\Project as Projectname;
use Exakat\Tasks\Helpers\BaselineStash;
use Exakat\Tasks\Helpers\ReportConfig;

use Exakat\Vcs\Vcs;

class Project extends Tasks {
    const CONCURENCE = self::NONE;

    protected $rulesetsToRun = array('Analyze',
                                     'Preferences',
                                    );

    protected $reports       = array();
    protected $reportConfigs = array();

    public function __construct($gremlin, $config, $subTask = self::IS_NOT_SUBTASK) {
        parent::__construct($gremlin, $config, $subTask);

        if (empty($this->reports)) {
            $this->reports = makeArray($config->project_reports);
        }
    }
    
    public function run() {
        if ($this->config->project === null) {
            throw new ProjectNeeded();
        }

        $project = new Projectname($this->config->project);

        if (!$project->validate()) {
            throw new InvalidProjectName($project->getError());
        }

        if ($this->config->gremlin === 'NoGremlin') {
            throw new MissingGremlin();
        }

        if (!file_exists($this->config->project_dir)) {
            throw new NoSuchProject($this->config->project);
        }

        if (!file_exists($this->config->code_dir)) {
            throw new NoCodeInProject($this->config->project);
        }

        // Baseline is always the previous audit done, not the current one!
        $baselinestash = new BaselineStash($this->config);
        $baselinestash->copyPrevious($this->config->dump);

        display("Cleaning project\n");
        $clean = new Clean($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        $clean->run();
        $this->datastore = Datastore::getDatastore($this->config);
        // Reset datastore for the others

        display('Search for external libraries' . PHP_EOL);
        $pathCache = "{$this->config->project_dir}/config.cache";
        if (file_exists($pathCache)) {
            unlink($pathCache);
        }

        $configThema = $this->config->duplicate(array('update' => true));

        $analyze = new FindExternalLibraries($this->gremlin, $configThema, Tasks::IS_SUBTASK);
        $analyze->run();
        unset($configThema);

        $this->addSnitch(array('step'    => 'External lib',
                               'project' => $this->config->project));
        unset($analyze);

        $this->logTime('Start');
        $this->addSnitch(array('step'    => 'Start',
                               'project' => $this->config->project));

        $audit_start = time();
        $this->datastore->addRow('hash', array('audit_start'    => $audit_start,
                                               'exakat_version' => Exakat::VERSION,
                                               'exakat_build'   => Exakat::BUILD,
                                               'php_version'    => $this->config->phpversion,
                                               'audit_name'     => $this->generateName(),
                                         ));

        $info = array();
        if (($vcsClass = Vcs::getVcs($this->config)) === 'None') {
            $info['vcs_type'] = 'Standalone archive';
        } else {
            $info['vcs_type'] = strtolower($vcsClass);
            $info['vcs_url']  = $this->config->project_url;
            
            $vcs = new $vcsClass($this->config->project, $this->config->code_dir);
            if (method_exists($vcs, 'getBranch')) {
                $info['vcs_branch']      = $vcs->getBranch();
            }
            if (method_exists($vcs, 'getRevision')) {
                $info['vcs_revision']      = $vcs->getRevision();
                $this->getLineDiff($info['vcs_revision'], $vcs);
            }
        }
        $this->datastore->addRow('hash', $info);

        $rulesetsToRun = array($this->config->project_rulesets);
        $reportToRun = array();
        $namesToRun  = array();

        foreach($this->reports as $format) {
            try {
                $report = new ReportConfig($format, $this->config);
            } catch (NoSuchReport $e) {
                // Simple ignore
                display($e->getMessage());
                continue;
            }
            $this->reportConfigs[$report->getName()] = $report;

            $rulesets = $report->getRulesets();
            if (empty($rulesets)) {
                $rulesets = $report->getRulesets();
            }
            $rulesetsToRun[] = $rulesets;
            $namesToRun[] = $report->getName();

            unset($report);
            gc_collect_cycles();
        }

        $rulesetsToRun = array_merge(...$rulesetsToRun);
        $rulesetsToRun = array_unique($rulesetsToRun);

        $availableRulesets = $this->rulesets->listAllRulesets();

        $diff = array_diff($rulesetsToRun, $availableRulesets);
        if (!empty($diff)) {
            display('Ignoring the following unknown rulesets : ' . implode(', ', $diff) . PHP_EOL);
        }
        $rulesetsToRun = array_diff($rulesetsToRun, $diff);

        $reportToRun = array_unique($reportToRun);

        if (empty($rulesetsToRun)) {
            // Default values
            $rulesetsToRun = $this->rulesetsToRun;
        }

        display("Running project '$project'" . PHP_EOL);
        display('Running the following analysis : ' . implode(', ', $rulesetsToRun));
        display('Producing the following reports : ' . implode(', ', $namesToRun));

        display('Running files' . PHP_EOL);
        $analyze = new Files($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        $analyze->run();
        unset($analyze);
        $this->logTime('Files');
        $this->addSnitch(array('step'    => 'Files',
                               'project' => $this->config->project));

        $nb_files = $this->datastore->getHash('files');
        if ($nb_files === '0') {
            throw new NoCodeInProject($this->config->project);
        }

        display('Cleaning DB' . PHP_EOL);
        $analyze = new CleanDb($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        $analyze->run();
        unset($analyze);
        $this->logTime('CleanDb');
        $this->addSnitch(array('step'    => 'Clean DB',
                               'project' => $this->config->project));
        $this->gremlin->resetConnection();

        $this->checkTokenLimit();

        $load = new Load($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        try {
            $load->run();
        } catch (NoFileToProcess $e) {
            $this->datastore->addRow('hash', array('init error' => $e->getMessage(),
                                                   'status'     => 'Error',
                                           ));
        }
        unset($load);
        display("Project loaded\n");
        $this->logTime('Loading');

        // Always run this one first
        $this->analyzeRulesets(array('First'), $audit_start, $this->config->verbose);

        // Dump is a child process
        // initialization and first collection (action done once)
        display('Initial dump');
        $dumpConfig = $this->config->duplicate(array('collect'            => true,
                                                     'project_rulesets'   => array('First')));
        $firstDump = new Dump($this->gremlin, $dumpConfig, Tasks::IS_SUBTASK);
        $firstDump->run();
        unset($firstDump);
        $this->logTime('Initial dump');

        // Dumps, when moved to the Analyzer category
        $this->analyzeRulesets(array('Dump'), $audit_start, $this->config->verbose);

        if (empty($this->config->program)) {
            $this->analyzeRulesets($rulesetsToRun, $audit_start, $this->config->verbose);
        } else {
            $this->analyzeOne($this->config->program, $audit_start, $this->config->verbose);
        }

        display('Analyzed project' . PHP_EOL);
        $this->logTime('Analyze');
        $this->addSnitch(array('step'    => 'Analyzed',
                               'project' => $this->config->project));

        $this->logTime('Analyze');

        $dump = new Dump($this->gremlin, $this->config, Tasks::IS_SUBTASK);
        foreach($this->config->rulesets as $name => $analyzers) {
            $dump->checkRulesets($name, $analyzers);
        }

        $this->logTime('Reports');
        try {
            $report = new Report($this->gremlin, $this->config, Tasks::IS_SUBTASK);

            $report->run();
        } catch (\Throwable $e) {
            display( "Error while building $format : " . $e->getMessage() . "\n");
        }
        display('Reported project' . PHP_EOL);

        // Reset cache from Rulesets
        Rulesets::resetCache();
        $this->logTime('Final');
        $this->removeSnitch();
        display('End' . PHP_EOL);
    }

    private function logTime($step) {
        static $log, $begin, $end, $start;

        if ($log === null) {
            $log = fopen("{$this->config->log_dir}/project.timing.csv", 'w+');
        }

        $end = microtime(true);
        if ($begin === null) {
            $begin = $end;
            $start = $end;
        }

        fwrite($log, $step . "\t" . ($end - $begin) . "\t" . ($end - $start) . PHP_EOL);
        $begin = $end;
    }

    private function analyzeOne($analyzers, $audit_start, $verbose) {
        $this->addSnitch(array('step'    => 'Analyzer',
                               'project' => $this->config->project));

        try {
            $analyzeConfig = $this->config->duplicate(array('noRefresh' => true,
                                                            'update'    => true,
                                                            'program'   => $analyzers,
                                                            'verbose'   => $verbose,
                                                            'quiet'     => !$verbose,
                                                            ));

            $analyze = new Analyze($this->gremlin, $analyzeConfig, Tasks::IS_SUBTASK);
            $analyze->run();
            unset($analyze);
            unset($analyzeConfig);
            $this->logTime('Analyze : ' . (is_array($analyzers) ? implode(', ', $analyzers) : $analyzers));

            $dumpConfig = $this->config->duplicate(array('update'    => true,
                                                         'program'   => $analyzers,
                                                         ));
 
            $audit_end = time();
            $query = 'g.V().count()';
            $res = $this->gremlin->query($query);
            if ($res instanceof \stdClass) {
                $nodes = $res->results[0];
            } else {
                $nodes = $res[0];
            }
            $query = 'g.E().count()';
            $res = $this->gremlin->query($query);
            if ($res instanceof \stdClass) {
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
            echo "Error while running the Analyzer {$this->config->project}.\nTrying next analysis.\n";
            file_put_contents("{$this->config->log_dir}/analyze.final.log", $e->getMessage());
        }
    }

    private function analyzeRulesets($rulesets, $audit_start, $verbose) {
        if (empty($rulesets)) {
            $rulesets = $this->config->project_rulesets;
        }

        if (!is_array($rulesets)) {
            $rulesets = array($rulesets);
        }

        display('Running the following rulesets : ' . implode(', ', $rulesets) . PHP_EOL);

        global $VERBOSE;
        $oldVerbose = $VERBOSE;
        $VERBOSE = false;
        foreach($rulesets as $ruleset) {
            $this->addSnitch(array('step'    => 'Analyze : ' . $ruleset,
                                   'project' => $this->config->project));
            $rulesetForFile = strtolower(str_replace(' ', '_', trim($ruleset, '"')));

            try {
                $analyzeConfig = $this->config->duplicate(array('noRefresh'        => true,
                                                                'update'           => true,
                                                                'project_rulesets' => array($ruleset),
                                                                'verbose'          => $verbose,
                                                                'quiet'            => !$verbose,
                                                                ));

                $analyze = new Analyze($this->gremlin, $analyzeConfig, Tasks::IS_SUBTASK);
                $analyze->run();
                unset($analyze);
                unset($analyzeConfig);
                $this->logTime("Analyze : $ruleset");

                $dumpConfig = $this->config->duplicate(array('update'               => true,
                                                             'project_rulesets'     => array($ruleset),
                                                             'verbose'              => false,
                                                             ));

                $audit_end = time();
                $query = 'g.V().count()';
                $res = $this->gremlin->query($query);
                if (isset($res->results)) {
                    $nodes = $res->results[0];
                } else {
                    $nodes = $res[0];
                }
                $query = 'g.E().count()';
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
                
                // Skip Dump, as it is auto-saving itself.
                if ($ruleset === 'Dump') { continue; }

                $dump = new Dump($this->gremlin, $dumpConfig, Tasks::IS_SUBTASK);
                $dump->run();
                $dump->finalMark($finalMark);
                unset($dump);
                unset($dumpConfig);
                gc_collect_cycles();
                $this->logTime("Dumped : $ruleset");
                
            } catch (\Exception $e) {
                echo "Error while running the ruleset $ruleset.\nTrying next ruleset.\n";
                file_put_contents("{$this->config->log_dir}/analyze.$rulesetForFile.final.log", $e->getMessage());
            }
        }
        $VERBOSE = $oldVerbose;
    }
    
    private function generateName() {
        $ini = parse_ini_file("{$this->config->dir_root}/data/audit_names.ini");
        
        $names = $ini['names'];
        $adjectives = $ini['adjectives'];
        
        shuffle($names);
        shuffle($adjectives);
        
        $x = random_int(0, PHP_INT_MAX);
        
        $name = $names[ $x % (count($names) - 1)];
        $adjective = $adjectives[ $x % (count($adjectives) - 1)];

        return ucfirst($adjective) . ' ' . $name;
    }
    
    private function getLineDiff($current, $vcs) {
        if (!file_exists($this->config->dump_previous)) {
            return ;
        }
        
        $sqlite = new \Sqlite3($this->config->dump_previous);
        $res = $sqlite->query('SELECT name FROM sqlite_master WHERE type="table" AND name="hash"');
        if ($res === false || !$res->numColumns() || $res->columnType(0) == SQLITE3_NULL) {
            return;
        }

        $res = $sqlite->query('SELECT value FROM hash WHERE key="vcs_revision"');
        if (!$res->numColumns() || $res->columnType(0) == SQLITE3_NULL) {
            return;
        }
        $revision = $res->fetchArray(\SQLITE3_ASSOC)['value'];
        
        $diff = $vcs->getDiffLines($revision, $current);
        if (!empty($diff)) {
            $this->datastore->addRow('linediff', $diff);
        }
    }
}

?>
