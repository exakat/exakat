<?php
use Everyman\Neo4j\Client,
	Everyman\Neo4j\Index\NodeIndex,
	Everyman\Neo4j\Relationship,
	Everyman\Neo4j\Node,
	Everyman\Neo4j\Gremlin;

class Assignation extends TokenAuto {
    function check() {

        $this->conditions = array(0 => array('code' => array('='),
                                             'atom' => 'none'),
                                  1 => array('atom' => array('Integer', 'Multiplication')),
                                  
        );
        
        $this->actions = array('addEdge'    => array( '1' => 'RIGHT',
                                                      '-1' => 'LEFT'),
                               'changeNext' => array(1, -1),
                               'atom'       => 'Assignation',
                               'cleansemicolon' => 1);

        return $this->checkAuto();
/*
        
        $result = Token::query("g.V.has('code','=').has('atom',null).as('o').out('NEXT').has('atom','Integer').back(2).in('NEXT').has('atom','Variable').back(2).each{ 
        g.addEdge(it, it.in('NEXT').next(), 'LEFT'); 
        g.addEdge(it, it.out('NEXT').next(), 'RIGHT');

        g.addEdge(it.in('NEXT').in('NEXT').next(), it, 'NEXT'); 
        g.addEdge(it, it.out('NEXT').out('NEXT').next(), 'NEXT');

        g.removeEdge(it.out('NEXT').outE('NEXT').next());
        g.removeEdge(it.outE('NEXT').next());
        g.removeEdge(it.in('NEXT').inE('NEXT').next());
        g.removeEdge(it.inE('NEXT').next());
        
        it.out('NEXT').has('code',';').each{ 
            g.addEdge(it.in('NEXT').next(), it.out('NEXT').next(), 'NEXT');
            g.removeEdge(it.inE('NEXT').next());
            g.removeEdge(it.outE('NEXT').next());

            g.removeVertex(it);
        }
        
        it.setProperty('atom', 'Assignation');
        
        }");

        return true;*/
    } 
}
?>