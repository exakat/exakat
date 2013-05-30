<?php
use Everyman\Neo4j\Client,
	Everyman\Neo4j\Index\NodeIndex,
	Everyman\Neo4j\Relationship,
	Everyman\Neo4j\Node,
	Everyman\Neo4j\Gremlin;

class Phpcode {
    function check() {
        
        $result = Token::query("g.V.has('token','T_OPEN_TAG').as('init').out('NEXT').hasNot('class',null).out('NEXT').has('token', 'T_CLOSE_TAG').back('init').each{
            f = it.out('NEXT').out('NEXT').next();
            g.removeEdge(it.out('NEXT').outE('NEXT').next());
            g.removeVertex(f);

            g.addEdge(it, it.out('NEXT').next(), 'CODE');
            g.removeEdge(it.outE('NEXT').next());
            
            it.setProperty('class', 'Phpcode');        
        }");

        return true;
    }
}

?>