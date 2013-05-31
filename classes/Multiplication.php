<?php
use Everyman\Neo4j\Client,
	Everyman\Neo4j\Index\NodeIndex,
	Everyman\Neo4j\Relationship,
	Everyman\Neo4j\Node,
	Everyman\Neo4j\Gremlin;

class Multiplication extends TokenAuto {
    function check() {
        $this->conditions = array(0 => array('code' => array('*','/','%'),
                                             'atom' => 'none'),
                                  1 => array('atom' => array('Integer','Multiplication')),
//                                  2 => array('notcode' => array('*', '/', '%')),
                                  
        );
        
        $this->actions = array('addEdge'    => array( '1' => 'RIGHT',
                                                      '-1' => 'LEFT'),
                               'changeNext' => array(1, -1),
                               'atom'       => 'Multiplication',
                               'cleansemicolon' => 1);
    
//        $this->printQuery();
        return $this->checkAuto();
    }
}

?>