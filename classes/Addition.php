<?php
use Everyman\Neo4j\Client,
	Everyman\Neo4j\Index\NodeIndex,
	Everyman\Neo4j\Relationship,
	Everyman\Neo4j\Node,
	Everyman\Neo4j\Gremlin;

class Addition extends TokenAuto{
    function check() {
        $this->conditions = array(0 => array('code' => array('+','-'),
                                             'atom' => 'none'),
                                  1 => array('atom' => array('Integer', 'Multiplication')),
                                  
        );
        
        $this->actions = array('addEdge'    => array( '1' => 'LEFT',
                                                      '-1' => 'RIGHT'),
                               'changeNext' => array(1, -1),
                               'atom'       => 'Addition',
                               'cleansemicolon' => 1);
    
        return $this->checkAuto();
    }
}
?>