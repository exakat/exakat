<?php

namespace Tokenizer;

class Token {
    protected static $client = null;
    protected static $reserved = array();
    
    function __construct($client) {
        // @todo typehint ? 
        Token::$client = $client; 
    }
    
    final function check() {
        
        display(get_class($this)." check \n");
        if (!method_exists($this, '_check')) {
            print get_class($this). " has no check yet\n";
        } else {
            return $this->_check();
        }

        return true;
    }
    
    function reserve() {
        return true;
    }

    function resetReserve() {
        Token::$reserved = array();
    }

    static function countTotalToken() {
        $result = Token::query("g.V.count()");
    	
    	return $result[0][0];
    }

    static function countLeftToken() {
        $result = Token::query("g.V.has('atom',null).except([g.v(0)]).hasNot('hidden', true).count()");
    	
    	return $result[0][0];
    }

    static function countLeftNext() {
        $result = Token::query("g.E.has('label','NEXT').count()");
    	
    	return $result[0][0];
    }

    static function countNextEdge() {
        $result = Token::query("g.E.has('label','NEXT').count()");
    	
    	return $result[0][0];
    }

    static public function query($query) {
    	$queryTemplate = $query;
    	$params = array('type' => 'IN');
    	try {
    	    $query = new \Everyman\Neo4j\Gremlin\Query(Token::$client, $queryTemplate, $params);
        	return $query->getResultSet();
    	} catch (\Exception $e) {
    	    $message = $e->getMessage();
    	    $message = preg_replace('#^.*\[message\](.*?)\[exception\].*#is', '\1', $message);
    	    print "Exception : ".$message."\n";
    	    
    	    print $queryTemplate."\n";
    	    die();
    	}
    	return $query->getResultSet();
    }

    static public function cleanHidden() {
        $query = " g.V.has('token','T_ROOT').out('NEXT').hasNot('atom',null).out('NEXT').has('token', 'T_END').each{ 
    g.removeVertex(it.in('NEXT').in('NEXT').next()); 
    g.removeVertex(it.out('NEXT').next()); 
    g.removeVertex(it); 
}";
        Token::query($query);
    }

    static public function finishSequence() {
        $query = "
g.V.has('token', 'T_ROOT').each{ 
    x = g.addVertex(null, [code:'Final sequence', atom:'Sequence', token:'T_SEMICOLON'], file:it.file);

    g.removeEdge(it.outE('NEXT').next());
    g.addEdge(it, x, 'NEXT');

    g.V.as('o').out('NEXT').back('o').hasNot('hidden', true).each{
        g.addEdge(x, it, 'ELEMENT');
        y = it.out('NEXT').next();
        g.removeEdge(it.outE('NEXT').next());
    }

    g.addEdge(x, y, 'NEXT');
    g.V.has('file', y.file).has('root', true).each{ it.setProperty('root', false); }
    x.setProperty('root', true);
}
       ";
        Token::query($query);
    }
}

?>