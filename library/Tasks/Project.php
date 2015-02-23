<?php

namespace Tasks;

use Everyman\Neo4j\Client,
	Everyman\Neo4j\Gremlin\Query;

class Project implements Tasks {
    private $client = null;
    private $log = null;
    private $php = null;
    
    public function run(\Config $config) {
        $this->client = new Client();

        if ($config->project === null) {
            print "Usage : php bin/project [Project name]\n";
            die();
        }

        $project = $config->project;
        if (!file_exists('./projects/'.$project)) {
            print "Project '$project' doesn't exist in projects folder. Aborting\n";
            die();
        }

        if (!file_exists('./projects/'.$project.'/config.ini')) {
            print "Project '$project' exists but has no config file. Aborting\n";
            die();
        }

        if (!file_exists('./projects/'.$project.'/code')) {
            print "Project '$project' exists but has no code folder. Aborting\n";
            die();
        }

        if (!file_exists('./projects/'.$project.'/log')) {
            print "Project '$project' exists but has no log folder. Aborting\n";
            die();
        }

        $this->logTime('Start');

        $dbexakat = new \Db();
        $dbexakat->insert('project_runs', array('date_start', 'folder'), array(date('Y-m-d H:i:s'), $project));
        $project_run = $dbexakat->insert_id();

        $this->logTime('Database');

        $db = new \Db('wordpress');

        $progress = 1;
        $total_steps = 9 / 100; // number of usage of logProgress - 1
        $db->logProgress($project, $progress++ / $total_steps);

        $thread = new \Thread();
        $thread->run('sh scripts/clean.sh');

        $db->logProgress($project, $progress++ / $total_steps);
        $this->logTime('Clean');

        print "Running project '$project'\n";

        print "Running files\n";
        $thread->run('php exakat files -p '.$project);
        $this->logTime('Files');
        print "Loading project\n";

        $thread->waitForAll();

        shell_exec('php exakat load -r -q -d ./projects/'.$project.'/code/ -p '.$project.'');
        $db->logProgress($project, $progress++ / $total_steps);
        print "Project loaded\n";
        $this->logTime('Loading');

        $res = shell_exec('php exakat build_root -p '.$project.' > ./projects/'.$project.'/log/build_root.final.log');
        print "Build root\n";
        $this->logTime('Build_root');

        if (file_exists('./projects/'.$project.'/log/tokenizer.final.log')) {
            unlink('./projects/'.$project.'/log/tokenizer.final.log');
        }
        $res = shell_exec('php exakat tokenizer -p '.$project.' > ./projects/'.$project.'/log/tokenizer.final.log');
        if (!empty($res) && strpos('javax.script.ScriptException', $res) !== false) {
            file_put_contents('log/tokenizer_error.log', $res);
            die();
        }

        if (file_exists('./projects/'.$project.'/log/errors.log')) {
            unlink('./projects/'.$project.'/log/errors.log');
        }
        $this->logTime('Tokenizer');
        print "Project tokenized\n";

        $thread->run('php exakat magicnumber -p '.$project);

        $thread->run('rm -rf ./projects/'.$project.'/log/errors.log; php exakat extract_errors > ./projects/'.$project.'/log/errors.log');
        print "Got the errors (if any)\n";

        $thread->run('php bin/stat > ./projects/'.$project.'/log/stat.log');
        $db->logProgress($project, $progress++ / $total_steps);
        print "Stats\n";

        $thread->run('php bin/log2csv; mv log/* ./projects/'.$project.'/log/');

        $this->logTime('Stats');
        $db->logProgress($project, $progress++ / $total_steps);

        if (file_exists('./projects/'.$project.'/log/analyze.final.log')) {
            unlink('./projects/'.$project.'/log/analyze.final.log');
        }

        $themes = array('Analyze', 'Appinfo', '"Coding Conventions"', '"Dead code"', 'Security', 'Custom');
        $processes = array();
        foreach($themes as $theme) {
            $themeForFile = strtolower(str_replace(' ', '_', trim($theme, '"')));
            shell_exec('php exakat analyze -norefresh -T '.$theme.' > ./projects/'.$project.'/log/analyze.'.$themeForFile.'.final.log; mv log/analyze.log ./projects/'.$project.'/log/analyze.'.$themeForFile.'.log');
            print "Analyzing $theme\n";
        }

        $db->logProgress($project, $progress++ / $total_steps);
        print "Project analyzed\n";
        $this->logTime('Analyze');

        shell_exec('php exakat report_all -p '.$project);
        $this->logTime('Report');

        $db->logProgress($project, $progress++ / $total_steps);

        print "Project reported\n";

        shell_exec('php exakat stat > ./projects/'.$project.'/log/stat.log');
        $db->logProgress($project, $progress++ / $total_steps);
        print "Stats 2\n";

        $dbexakat->query('UPDATE project_runs SET `date_finish` = "'.date('Y-m-d H:i:s').'" WHERE id = "'.$project_run.'"');
        $this->logTime('Final');
    }

    private function logTime($step) {
        static $log, $begin, $end, $start;

        if ($log === null) {
            $log = fopen('log/project.timing.csv', 'w+');
        }
        $end = microtime(true);
        if ($begin === null) { 
            $begin = $end; 
            $start = $end;
        }

        fwrite($log, $step."\t".($end - $begin)."\t".($end - $start)."\n");
        $begin = $end;
    }
}

?>