<?php

namespace Report\Content;

class ReportInfo extends \Report\Content {
    private $list = array();

    private $project = null;
    private $neo4j = null;
    private $mysql = null;
    
    public function collect() {
        if (file_exists('./projects/'.$this->project.'/code/.git/config')) {
            $config = file_get_contents('./projects/'.$this->project.'/code/.git/config');
            preg_match('#url = (\S+)\s#is', $config, $r);
            $this->list['Git URL'] = $r[1];
            
            $res = shell_exec('cd ./projects/'.$this->project.'/code/; git branch');
            $this->list['Git branch'] = trim($res);

            $res = shell_exec('cd ./projects/'.$this->project.'/code/; git rev-parse HEAD');
            $this->list['Git commit'] = trim($res);
        } else {
            $this->list['Repository URL'] = 'Downloaded archive';
        }

        $db = new \Db();
        $res = $db->query('SELECT * FROM projects WHERE project="'.$this->project.'" ORDER BY ID DESC LIMIT 1')->fetch_assoc();
        
        $this->list['Number of PHP files'] = $res['php'];
        $this->list['Number of lines of code'] = $res['loc'];

        include(dirname(dirname(__DIR__)).'/App.php');
        $this->list['Audit software version'] = $app['version'];
        
        $res = $this->mysql->query("SELECT * FROM project_runs WHERE folder='{$this->project}' ORDER BY date_finish DESC LIMIT 1")->fetch_assoc();
        
        $this->list['Audit execution date'] = date('r', strtotime($res['date_start']));
        $this->list['Report production date'] = date('r', strtotime('now'));
        
        $this->list['PHP version'] = substr(shell_exec('php -v'), 0, 11);

        $this->list['Audit software version'] = $app['version'];
    }
    
    public function setNeo4j($client) {
        $this->neo4j = $client;
    }

    public function setMysql($client) {
        $this->mysql = $client;
    }

    public function setProject($project) {
        $this->project = $project;
    }
    
    public function toArray() {
        $return = array();
        foreach($this->list as $k => $v) {
            $return[] = array($k, $v);
        }
        return $return;
    }
    
    public function getColumnTitles() {
        return array('Label', 'Value');
    }

    public function toHash() {
        return $this->list;
    }
}

?>