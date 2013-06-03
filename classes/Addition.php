<?php
use Everyman\Neo4j\Client,
	Everyman\Neo4j\Index\NodeIndex,
	Everyman\Neo4j\Relationship,
	Everyman\Neo4j\Node,
	Everyman\Neo4j\Gremlin;

class Addition extends TokenAuto{
    function check() {
        $operands = array('Integer', 'Addition', 'Variable');
        
        $this->conditions = array(-1 => array('atom' => $operands ),
                                  0 => array('code' => array('+','-'),
                                             'atom' => 'none'),
                                  1 => array('atom' => $operands),
        );
        
        $this->actions = array('addEdge'    => array( '1' => 'RIGHT',
                                                      '-1' => 'LEFT'),
                               'changeNext' => array(1, -1),
                               'atom'       => 'Addition',
                               'cleansemicolon' => 1);
        return $this->checkAuto();
    }
}
?>