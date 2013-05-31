<?php
use Everyman\Neo4j\Client,
	Everyman\Neo4j\Index\NodeIndex,
	Everyman\Neo4j\Relationship,
	Everyman\Neo4j\Node,
	Everyman\Neo4j\Gremlin;

class Phpcode extends TokenAuto {
    function check() {


        $this->conditions = array(0 => array('token' => 'T_OPEN_TAG',
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('token' => 'T_CLOSE_TAG'),
                                  
                                  
        );
        
        $this->actions = array('addEdge'    => array( '1' => 'CODE'),
                               'changeNext' => array(1),
                               'dropNext'   => array(1), 
                               'atom'       => 'Phpcode');
    
        return $this->checkAuto();
    }
}

?>