<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Tasks;

class Project extends Tasks {
    private $project_dir = '.';
    private $config = null;
    
    protected $themes = array('CompatibilityPHP53', 'CompatibilityPHP54', 'CompatibilityPHP55', 'CompatibilityPHP56', 'CompatibilityPHP70',
                              'Appinfo', '"Dead code"', 'Security', 'Custom',
                              'Analyze');

    protected $reports = array('Premier' => array(//'Markdown' => 'report',
                                                  //'Sqlite'   => 'report',
                                                  'Devoops'  => 'report',
                                                  //'Html'     => 'report',
                                                  //'Text'     => 'report'
                                                  ),
                               'Counts'  => array('Sqlite'   => 'counts'));
    const TOTAL_STEPS = 23; // 2 Reports + 10 Analyzes + 10 other steps

    public function run(\Config $config) {
        $this->config = $config;
        
        $progress = 0;

        $project = $config->project;

        $this->project_dir = $config->projects_root.'/projects/'.$project;

        if ($config->project === 'default') {
            die("Usage : php {$config->executable} project -p [Project name]\n");
        }

        if (!file_exists($config->projects_root.'/projects/'.$project)) {
            die("Project '$project' doesn't exist in projects folder. Aborting\n");
        }

        if (!file_exists($config->projects_root.'/projects/'.$project.'/config.ini')) {
            die("Project '$project' exists but has no config file. Aborting\n");
        }

        if (!file_exists($config->codePath)) {
            die("Project '$project' exists but has no code folder. Aborting\n");
        }

        if (!file_exists($config->projects_root.'/projects/'.$project.'/log')) {
            die("Project '$project' exists but has no log folder. Aborting\n");
        }

        // cleaning log directory (possibly logs)
        $logs = glob($config->projects_root.'/projects/'.$project.'/log/*');
        foreach($logs as $log) {
            unlink($log);
        }

        $this->logTime('Start');

        // cleaning datastore
        $this->datastore = new \Datastore($config, \Datastore::CREATE);
        
        $audit_start = time();
        $this->datastore->addRow('hash', array('audit_start' => $audit_start,
                                               'exakat_version' => \Exakat::VERSION,
                                               'exakat_build' => \Exakat::BUILD,
                                         ));

        $thread = new \Thread();
        display("Running project '$project'\n");

        display("Cleaning DB\n");
        shell_exec('php '.$config->executable.' cleandb -v');
        $this->logTime('Files');
        $this->updateProgress($progress++);

        display("Search for external libraries\n");
        shell_exec('php '.$config->executable.' findextlib -p '.$project.' -v -u > '.$config->projects_root.'/projects/'.$project.'/log/findExtlib.log');
        $this->logTime('Find External Libraries');
        $thread->waitForAll();
        $this->updateProgress($progress++);

        display("Running files\n");
        shell_exec('php '.$config->executable.' files -p '.$project.' > '.$config->projects_root.'/projects/'.$project.'/log/files.final.log');
        $this->logTime('Files');
        $thread->waitForAll();
        $this->updateProgress($progress++);

        display("waited For All\n");
        $this->checkTokenLimit();

        shell_exec('php '.$config->executable.' load -v -p '.$project. ' > '.$config->projects_root.'/projects/'.$project.'/log/load.final.log' );
        display("Project loaded\n");
        $this->logTime('Loading');
        if (!$this->checkFinalLog($config->projects_root.'/projects/'.$project.'/log/load.final.log')) {
            return false;
        }
        $this->updateProgress($progress++);

        $res = shell_exec('php '.$config->executable.' build_root -v -p '.$project.' > '.$config->projects_root.'/projects/'.$project.'/log/build_root.final.log');
        display("Build root\n");
        $this->logTime('Build_root');
        if (!$this->checkFinalLog($config->projects_root.'/projects/'.$project.'/log/build_root.final.log')) {
            return false;
        }
        $this->updateProgress($progress++);

        $res = shell_exec('php '.$config->executable.' tokenizer -p '.$project.' > '.$config->projects_root.'/projects/'.$project.'/log/tokenizer.final.log');
        $this->logTime('Tokenizer');
        if (!$this->checkFinalLog($config->projects_root.'/projects/'.$project.'/log/tokenizer.final.log')) {
            return false;
        }
        display("Project tokenized\n");
        $this->updateProgress($progress++);

        $thread->run('php '.$config->executable.' magicnumber -p '.$project);
        $this->updateProgress($progress++);

        $thread->run('php '.$config->executable.' errors > '.$config->projects_root.'/projects/'.$project.'/log/errors.log');
        display("Got the errors (if any)\n");
        $this->updateProgress($progress++);

        $thread->run('php '.$config->executable.' stat -p '.$project.' > '.$config->projects_root.'/projects/'.$project.'/log/stat.log');
        display("Stats\n");
        $this->updateProgress($progress++);

        $thread->run('php '.$config->executable.' log2csv -p '.$project);

        $this->logTime('Stats');

        exec('php exakat dump -p '.$config->project.'   > /dev/null &');
        display('Started dump process');

        $processes = array();
        foreach($this->themes as $theme) {
            $themeForFile = strtolower(str_replace(' ', '_', trim($theme, '"')));

                $args = array ( 1 => 'analyze',
                                2 => '-p',
                                3 => $config->project,
                                4 => '-T',
                                5 => trim($theme, '"'), // No need to protect anymore, as this is internal
                                6 => '-norefresh',
                                );
            
                try {
                    $configThema = \Config::push($args);

                    $analyze = new Analyze();
                    $analyze->run($configThema);
                    unset($report);
                    
                    rename($config->projects_root.'/projects/'.$project.'/log/analyze.log', $config->projects_root.'/projects/'.$project.'/log/analyze.'.$themeForFile.'.log');
                    $this->updateProgress($progress++);

                    \Config::pop();
                } catch (\Exception $e) {
                    echo "Error while running the Analyze $theme \n",
                         $e->getMessage();
                    file_put_contents($config->projects_root.'/projects/'.$project.'/log/analyze.'.$themeForFile.'.final.log', $e->getMessage());
                    die();
                }

            if (!$this->checkFinalLog($config->projects_root.'/projects/'.$project.'/log/analyze.'.$themeForFile.'.log')) {
                return false;
            }
        }

        display("Analyzed project\n");
        $this->updateProgress($progress++);
        $this->logTime('Analyze');

/*
        check on dump ? 
*/
//        $thread->run('php '.$config->executable.' dump -p '.$project);

        $this->updateProgress($progress++);
        $this->logTime('Analyze');

        $oldConfig = \Config::factory();
        foreach($this->reports as $reportName => $formats) {
            foreach($formats as $format => $fileName) {
                display("Reporting $reportName in $format\n");
                $this->updateProgress($progress++);
                
                $args = array ( 1 => 'report',
                                2 => '-p',
                                3 => $config->project,
                                4 => '-f',
                                5 => $fileName,
                                6 => '-format',
                                7 => $format,
                                8 => '-report',
                                9 => $reportName,
                                );
                $config = \Config::factory($args);
            
                try {
                    $report = new Report();
                    $report->run($config);
                    unset($report);
                } catch (\Exception $e) {
                    print "Error while building $reportName in $format \n";
                    print $e->getMessage();
                    die();
                }
            }
        }
        \Config::factory($oldConfig);

        display("Reported project\n");

        shell_exec('php '.$config->executable.' stat -p '.$project.' > '.$config->projects_root.'/projects/'.$project.'/log/stat.log');
        display("Stats 2\n");
        
        $audit_end = time();
        $this->datastore->addRow('hash', array('audit_end'    => $audit_end,
                                         'audit_length' => $audit_end - $audit_start));

        $this->logTime('Final');
        display("End 2\n");
        $this->updateProgress($progress++);
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

    protected function checkFinalLog($log) {
        $logRes = file_get_contents($log);
        if (preg_match('#Exception :  => PermGen space#is', $logRes)) {
            print "Neo4j hadn't sufficient memory. Please, increase it by changing ./neoj/config/neo4j-wrapper.conf, and adding 'wrapper.java.additional=-XX:MaxPermSize=512m' or changing 512 to more.\n Aborting analyze\n";
            return false;
        }

        if (preg_match('#\[message\] => PermGen space#is', $logRes)) {
            print "Neo4j hadn't sufficient memory. Please, increase it by changing ./neoj/config/neo4j-wrapper.conf, and adding 'wrapper.java.additional=-XX:MaxPermSize=512m' or changing 512 to more.\n Aborting analyze\n";
            return false;
        }

        if (preg_match('#Exception : Can\'t open connection to http://#is', $logRes)) {
            print "Neo4j can't be reached. It may have stopped working, or is stuck in a long transaction. Either way, it should be killed (killall) and restarted. Try again.\n Aborting analyze\n";
            return false;
        }

        if (preg_match('#Exception :  => Cannot invoke method toLowerCase\(\) on null object#is', $logRes)) {
            print "An error happened while processing the code (toLowerCase()). Please, send the log folder to exakat@gmail.com for analyzis.\n Aborting analyze\n";
            return false;
        }

        if (preg_match('#Exception :  => Cannot invoke method plus\(\) on null object#is', $logRes)) {
            print "An error happened while processing the code (plus()). Please, send the log folder to exakat@gmail.com for analyzis.\n Aborting analyze\n";
            return false;
        }

//Exception : Unable to retrieve server info [500]:

        // checked it all. All is fine.
        return true;
    }

    private function updateProgress($status) {
        $progress = json_decode(file_get_contents($this->config->projects_root.'/progress/jobqueue.exakat'));
        $progress->progress = number_format(100 * $status / self::TOTAL_STEPS, 0);
        file_put_contents($this->config->projects_root.'/progress/jobqueue.exakat', json_encode($progress));
    }

}

?>
