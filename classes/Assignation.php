<?php
use Everyman\Neo4j\Client,
	Everyman\Neo4j\Index\NodeIndex,
	Everyman\Neo4j\Relationship,
	Everyman\Neo4j\Node,
	Everyman\Neo4j\Gremlin;

class Assignation {
    function check() {
        
        $result = Token::query("g.V.has('code','=').has('class',null).as('o').out('NEXT').has('class','Integer').back(2).in('NEXT').has('class','Variable').back(2).each{ 
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
        
        it.setProperty('class', 'Assignation');
        
        }");

        return true;
    }
}

/*

        


        

            g.addEdge(it.in('NEXT').next(); it.out('NEXT').next(), 'NEXT'); 
            g.removeEdge(it.inE('NEXT').next()); 
            g.removeEdge(it.outE('NEXT').next()); 
            

*/
?>