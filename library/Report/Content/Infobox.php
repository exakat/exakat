<?php

namespace Report\Content;

class Infobox extends \Report\Content {
    private $infobox = array();
    private $severities = array();

    private $project = null;
    private $neo4j = null;
    private $mysql = null;
    
    public function collect() {
        $queryTemplate = "g.V.has('token', 'T_FILENAME').count()";
        $params = array('type' => 'IN');
        $query = new \Everyman\Neo4j\Gremlin\Query($this->neo4j, $queryTemplate, $params);
        $vertices = $query->getResultSet();

        $this->infobox[] = array('icon'   => 'ok',
                                 'number' => $vertices[0][0],
                                 'content' => 'PHP files');
        
        $queryTemplate = "g.V.has('token', 'T_FILENAME').out('FILE').transform{ x = it.out.loop(1){true}{true}.line.unique().count()}.sum()";
        $params = array('type' => 'IN');
        $query = new \Everyman\Neo4j\Gremlin\Query($this->neo4j, $queryTemplate, $params);
        $vertices = $query->getResultSet();
        
        $this->infobox[] = array('icon'   => 'leaf',
                                 'number' => $vertices[0][0],
                                 'content' => 'Lines of code');

        $this->infobox[] = array('icon'   => 'wrench',
                                 'number' => $this->severities['Critical'],
                                 'content' => 'Critical');

        $this->infobox[] = array('icon'   => 'beaker',
                                 'number' => $this->severities['Major'],
                                 'content' => 'Major');

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

    public function setSeverities($severities) {
        $this->severities = $severities;
    }

    public function toInfoBox() {
        return $this->infobox;
    }
}

?>