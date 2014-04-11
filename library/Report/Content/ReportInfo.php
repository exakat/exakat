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
            $this->list['Repository URL'] = $r[1];
        } else {
            $this->list['Repository URL'] = 'Downloaded archive';
        }

        $queryTemplate = "g.V.has('token', 'E_FILE').count()";
        $params = array('type' => 'IN');
        $query = new \Everyman\Neo4j\Gremlin\Query($this->neo4j, $queryTemplate, $params);
        $vertices = $query->getResultSet();

        $this->list['Number of PHP files'] = $vertices[0][0];
        
        $queryTemplate = "g.V.has('token', 'E_FILE').transform{ x = it.out.line.unique().count()}.sum()";
        $params = array('type' => 'IN');
        $query = new \Everyman\Neo4j\Gremlin\Query($this->neo4j, $queryTemplate, $params);
        $vertices = $query->getResultSet();
        $this->list['Number of lines of code'] = $vertices[0][0];

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