<?php

namespace Analyzer;

use Everyman\Neo4j\Client,
    Everyman\Neo4j\Index\NodeIndex;

class Analyzer {
    private $client = null;
    
    function __construct($client) {
        $this->client = $client;
        $this->methods = array();

        $result = $this->query("g.getRawGraph().index().existsForNodes('analyzers');");
        if ($result[0][0] == 'false') {
            $this->query("g.createManualIndex('analyzers', Vertex)");
        }
        
        $analyzer = str_replace('\\', '\\\\', get_class($this));
        $query = "g.idx('analyzers')[['analyzer':'$analyzer']]";
        $res = $this->query($query);
        
        if (isset($res[0]) && count($res[0]) == 1) {
            print "cleaning $analyzer\n";
            $query = <<<GREMLIN
g.idx('analyzers')[['analyzer':'$analyzer']].outE('ANALYZED').each{
    g.removeEdge(it);
}

GREMLIN;
            $this->query($query);
        } else {
            print "new $analyzer\n";
            $query = <<<GREMLIN
x = g.addVertex(null, [analyzer:'$analyzer', index:'true', code:'Analyzer index for $analyzer']);

g.idx('analyzers').put('analyzer', '$analyzer', x);

GREMLIN;
            $this->query($query);
        }
    }

    public function query($query) {
    	$queryTemplate = $query;
    	$params = array('type' => 'IN');
    	try {
    	    $query = new \Everyman\Neo4j\Gremlin\Query($this->client, $queryTemplate, $params);
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
    
    function atomIs($atom) {
        if (is_array($atom)) {
            $this->methods[] = 'filter{it.atom in [\''.join("', '", $atom).'\']}';
        } else {
            $this->methods[] = 'has("atom", "'.$atom.'")';
        }
        
        return $this;
    }
    
    function collect() {
        return $this;
    }

    function run() {
        $query = $this->prepareQuery();
        
        return $this->execQuery($query);
    }

    function printQuery() {
        $query = $this->prepareQuery();
        
        print $query;
        die();
    }

    function prepareQuery() {
        $query = join('.', $this->methods);
        
        // search what ? All ? 
        $query = <<<GREMLIN

c = 0;
g.V.$query
GREMLIN;

        // Indexed results
        $analyzer = str_replace('\\', '\\\\', get_class($this));
        $query .= <<<GREMLIN
.each{
    g.addEdge(g.idx('analyzers')[['analyzer':'$analyzer']].next(), it, 'ANALYZED');
    c = c + 1;
}
c;

GREMLIN;
        return $query;
    }
    
    function execQuery($query) {
        $r = $this->query($query);
        return $r[0][0];

    }
}
?>