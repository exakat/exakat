<?php

namespace Report\Content;

class Infobox extends \Report\Content {
    protected $array = array();
    public $severities = array();
    
    public function setSeverities($array) {
        $this->severities = $array;
    }
    
    public function collect() {
        $queryTemplate = "g.V.has('token', 'T_FILENAME').count()";
        $params = array('type' => 'IN');
        $query = new \Everyman\Neo4j\Gremlin\Query($this->neo4j, $queryTemplate, $params);
        $vertices = $query->getResultSet();

        $this->array[] = array('icon'    => 'ok',
                               'number'  => $vertices[0][0],
                               'content' => 'PHP files');
        
        $queryTemplate = "g.V.has('token', 'T_FILENAME').out('FILE').transform{ x = it.out.loop(1){true}{true}.line.unique().count()}.sum()";
        $params = array('type' => 'IN');
        $query = new \Everyman\Neo4j\Gremlin\Query($this->neo4j, $queryTemplate, $params);
        $vertices = $query->getResultSet();
        
        $this->array[] = array('icon'    => 'leaf',
                               'number'  => $vertices[0][0],
                               'content' => 'Lines of code');

        $this->array[] = array('icon'    => 'wrench',
                               'number'  => $this->severities['Critical'],
                               'content' => 'Critical');

        $this->array[] = array('icon'    => 'beaker',
                               'number'  => $this->severities['Major'],
                               'content' => 'Major');

    }
}

?>