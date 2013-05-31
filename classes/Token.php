<?php
use Everyman\Neo4j\Client,
	Everyman\Neo4j\Index\NodeIndex,
	Everyman\Neo4j\Relationship,
	Everyman\Neo4j\Node,
	Everyman\Neo4j\Gremlin
	;

class Token {
    protected static $client = null;
    
    function __construct($client) {
        // @todo typehint ? 
        Token::$client = $client; 
    }
    
    function check() {
        
        print get_class($this). " has no check yet\n";

        return true;
    }

    static function countTotalToken() {
        $result = Token::query("g.V.count()");
    	
    	return $result[0][0];
    }

    static function countLeftToken() {
        $result = Token::query("g.V.has('atom',null).except([g.v(0)]).count()");
    	
    	return $result[0][0];
    }

    static public function query($query) {
    	$queryTemplate = $query;
    	$params = array('type' => 'IN');
	    $query = new Gremlin\Query(Token::$client, $queryTemplate, $params);
    	return $query->getResultSet();
    }
}

?>