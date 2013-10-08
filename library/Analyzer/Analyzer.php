<?php

namespace Analyzer;

use Everyman\Neo4j\Client,
    Everyman\Neo4j\Index\NodeIndex;

class Analyzer {
    private $client = null;
    
    function __construct($client) {
        $this->client = $client;
        $this->methods = array();
        $this->queries = array();

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

    // @doc return the list of dependences that must be prepared before the execution of an analyzer
    // @doc by default, nothing. 
	protected function dependsOn() {
	    return array();
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

    function atomIsNot($atom) {
        if (is_array($atom)) {
            $this->methods[] = 'filter{!(it.atom in [\''.join("', '", $atom).'\']) }';
        } else {
            $this->methods[] = 'hasNot("atom", "'.$atom.'")';
        }
        
        return $this;
    }

    function analyzerIsNot($analyzer) {
        if (is_array($analyzer)) {
            $this->methods[] = 'filter{ it.in("ANALYZED").filter{ not (it.analyzer in [\''.join("', '", $atom).'\'])}.count() == 0}';
        } else {
            $this->methods[] = 'filter{ it.in("ANALYZED").has("analyzer", \''.$analyzer.'\').count() == 0}';
        }
    }

    function out($edge_name) {
        if (is_array($edge_name)) {
            // @todo
            die(" I don't understand arrays in out()");
        } else {
            $this->methods[] = "out('$edge_name')";
        }
        
        return $this;
    }

    function in($edge_name) {
        if (is_array($edge_name)) {
            // @todo
            die(" I don't understand arrays in out()");
        } else {
            $this->methods[] = "in('$edge_name')";
        }
        
        return $this;
    }

    function hasIn($edge_name) {
        if (is_array($edge_name)) {
            // @todo
            die(" I don't understand arrays in out()");
        } else {
            $this->methods[] = 'filter{ it.in("'.$edge_name.'").count() != 0}';
        }
        
        return $this;
    }
    
    function hasNoIn($edge_name) {
        if (is_array($edge_name)) {
            // @todo
            die(" I don't understand arrays in out()");
        } else {
            $this->methods[] = 'filter{ it.in("'.$edge_name.'").count() == 0}';
        }
        
        return $this;
    }
    
    
    function run() {
        $nodes = $this->dependsOn();
        $tocheck = $nodes;
        $edges = array();
        $class = get_class($this);
        foreach($nodes as $n) {
            $edges[] = array($class, $n);
        }
        
        while (count($tocheck) > 0) {
            $class = array_shift($tocheck);
            $x = new $class($this->client);

            foreach($x->dependsOn() as $n) {
                if (!in_array($n, $nodes)) {
                    $nodes[] = $n;
                }
                $edges[] = array($n, $class);
            }
        }
        $nodes[] = get_class($this);

        if(($x = $this->topological_sort($nodes, $edges)) === null) {
            print "There are circular dependencies in the analyzers. Check all dependsOn() and remove cyclic dependencies in ".join(', ', $nodes).". Aborting\n";
            die();
        }
        
        array_pop($nodes); // @doc remove itself
        foreach($nodes as $n) {
            $x = new $n($this->client);
            
            // @warning : run will test again the dependencies! We haven't solved this problem yet here.
            $x->run();
        }

        $this->analyze();
        $this->prepareQuery();

        return $this->execQuery();
    }

    function analyze() { return true; } 
    // @todo log errors when using this ? 

    function printQuery() {
        $this->prepareQuery();
        
        print_r($this->queries);
        die();
    }

    function prepareQuery() {
        // @doc This is when the object is a placeholder for others. 
        if (empty($this->methods)) { return true; }
        
        $this->analyzerIsNot(addslashes(get_class($this)));
        
        $query = join('.', $this->methods);
        
        // search what ? All ? 
        $query = <<<GREMLIN

c = 0;
g.V.{$query}
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

        $this->queries[] = $query;
        $this->methods = array();
        
        return true;
    }
    
    function execQuery() {
        if (empty($this->queries)) { return true; }

        // @todo add a test here ? 
        foreach($this->queries as $query) {
            $r = $this->query($query);
        }

        // reset for the next
        $this->queries = array(); 
        
        // @todo multiple results ? 
        // @todo store result in the object until reading. 
        return $r[0][0];
    }
    
    function topological_sort($nodeids, $edges) {

	// initialize variables
	$L = $S = $nodes = array(); 

	// remove duplicate nodes
	$nodeids = array_unique($nodeids); 	

	// remove duplicate edges
	$hashes = array();
	foreach($edges as $k=>$e) {
		$hash = md5(serialize($e));
		if (in_array($hash, $hashes)) { unset($edges[$k]); }
		else { $hashes[] = $hash; }; 
	}

	// Build a lookup table of each node's edges
	foreach($nodeids as $id) {
		$nodes[$id] = array('in'=>array(), 'out'=>array());
		foreach($edges as $e) {
			if ($id==$e[0]) { $nodes[$id]['out'][]=$e[1]; }
			if ($id==$e[1]) { $nodes[$id]['in'][]=$e[0]; }
		}
	}

	// While we have nodes left, we pick a node with no inbound edges, 
	// remove it and its edges from the graph, and add it to the end 
	// of the sorted list.
	foreach ($nodes as $id=>$n) { if (empty($n['in'])) $S[]=$id; }
	while (!empty($S)) {
		$L[] = $id = array_shift($S);
		foreach($nodes[$id]['out'] as $m) {
			$nodes[$m]['in'] = array_diff($nodes[$m]['in'], array($id));
			if (empty($nodes[$m]['in'])) { $S[] = $m; }
		}
		$nodes[$id]['out'] = array();
	}

	// Check if we have any edges left unprocessed
	foreach($nodes as $n) {
		if (!empty($n['in']) or !empty($n['out'])) {
			return null; // not sortable as graph is cyclic
		}
	}
	return $L;
}
}
?>