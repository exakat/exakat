<?php

namespace Report;

class ReportInfo {
    private $list = array();
    
    function __construct($project) {
        $this->setProject($project);
    }

    function setProject($project) {
        include(dirname(__DIR__).'/App.php');
        $this->list['Audit software version'] = $app['version'];
        
        $mysqli = new \mysqli('localhost', 'root', '', 'exakat');
        $res = $mysqli->query("SELECT * FROM project_runs WHERE folder='$project' ORDER BY date_finish DESC LIMIT 1")->fetch_assoc();
        
        $this->list['Audit execution date'] = date('r', strtotime($res['date_start']));
        $this->list['Report production date'] = date('r', strtotime('now'));
    }
    
    function toMarkdown() {
    }
    
    function toArray() {
        return $this->list;
    }
}

?>