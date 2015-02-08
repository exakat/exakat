<?php

namespace Report\Report;

use Report\Report;

class Counts extends Report {
    private $projectUrl    = null;

    public function __construct($project, $client, $db) {
        parent::__construct($project, $client, $db);
    }
    
    public function setProject($project) {
        $this->project = $project;
    }

    public function setProjectUrl($projectUrl) {
        $this->projectUrl = $projectUrl;
    }
    
    public function prepare() {
        $this->createLevel1('Report presentation');

        $this->createLevel1('Detailled');
        $counts = new \Report\Content\Counts();
        $counts->setNeo4j($this->client);
        $counts->collect();

        $this->addContent('SimpleTable', $counts, 'oneColumn');
    }
}

?>
