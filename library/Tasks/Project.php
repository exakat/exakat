<?php

namespace Tasks;

use Everyman\Neo4j\Client,
	Everyman\Neo4j\Gremlin\Query;

class Project implements Tasks {
    private $client = null;
    private $project_dir = '.';
    private $executable = 'exakat';
    
    public function run(\Config $config) {
        $this->client = new Client();
        $this->project_dir = $config->projects_root.'/projects/'.$config->project;
        if ($config->is_phar) {
            $this->executable = basename(dirname(dirname(__DIR__)));
        } else {
            $this->executable = $_SERVER['SCRIPT_NAME'];
        }

        if ($config->project === null) {
            print "Usage : php {$this->executable} project -p [Project name]\n";
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

        $datastore = new \Datastore($config);
        $datastore->cleanTable('hash');
        $datastore->addRow('hash', array(array('key' => 'audit_start',      'value' => time())));

        $thread = new \Thread();
        print "Running project '$project'\n";

        print "Running files\n";
        $thread->run('php '.$this->executable.' files -p '.$project);
        $this->logTime('Files');
        print "Loading project\n";

        $thread->waitForAll();

        shell_exec('php '.$this->executable.' load -r -d '.$config->projects_root.'/projects/'.$project.'/code/ -p '.$project.'');
        print "Project loaded\n";
        $this->logTime('Loading');

        $res = shell_exec('php '.$this->executable.' build_root -p '.$project.' > '.$config->projects_root.'/projects/'.$project.'/log/build_root.final.log');
        print "Build root\n";
        $this->logTime('Build_root');

        if (file_exists($config->projects_root.'/projects/'.$project.'/log/tokenizer.final.log')) {
            unlink($config->projects_root.'/projects/'.$project.'/log/tokenizer.final.log');
        }
        $res = shell_exec('php '.$this->executable.' tokenizer -p '.$project.' > '.$config->projects_root.'/projects/'.$project.'/log/tokenizer.final.log');
        if (!empty($res) && strpos('javax.script.ScriptException', $res) !== false) {
            file_put_contents($config->projects_root.'/log/tokenizer_error.log', $res);
            die();
        }

        if (file_exists($config->projects_root.'/projects/'.$project.'/log/errors.log')) {
            unlink($config->projects_root.'/projects/'.$project.'/log/errors.log');
        }
        $this->logTime('Tokenizer');
        print "Project tokenized\n";

        $thread->run('php '.$this->executable.' magicnumber -p '.$project);

        $thread->run('php '.$this->executable.' errors > '.$config->projects_root.'/projects/'.$project.'/log/errors.log');
        print "Got the errors (if any)\n";

        $thread->run('php '.$this->executable.' stat -p '.$project.' > '.$config->projects_root.'/projects/'.$project.'/log/stat.log');
        print "Stats\n";

        $thread->run('php '.$this->executable.' log2csv -p '.$project);

        $this->logTime('Stats');

        if (file_exists($config->projects_root.'/projects/'.$project.'/log/analyze.final.log')) {
            unlink($config->projects_root.'/projects/'.$project.'/log/analyze.final.log');
        }

        $themes = array('Analyze', 'Appinfo', '"Coding Conventions"', '"Dead code"', 'Security', 'Custom');
        $processes = array();
        foreach($themes as $theme) {
            $themeForFile = strtolower(str_replace(' ', '_', trim($theme, '"')));
            shell_exec('php '.$this->executable.' analyze -norefresh -p '.$project.' -T '.$theme.' > '.$config->projects_root.'/projects/'.$project.'/log/analyze.'.$themeForFile.'.final.log');
            print "Analyzing $theme\n";
        }

        print "Project analyzed\n";
        $this->logTime('Analyze');

        shell_exec('php '.$this->executable.' report_all -p '.$project);
        $this->logTime('Report');

        print "Project reported\n";

        shell_exec('php '.$this->executable.' stat > '.$config->projects_root.'/projects/'.$project.'/log/stat.log');
        print "Stats 2\n";

        $this->logTime('Final');
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
}

?>