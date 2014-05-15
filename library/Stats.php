<?php

use Everyman\Neo4j\Client,
	Everyman\Neo4j\Index\NodeIndex,
	Everyman\Neo4j\Relationship,
	Everyman\Neo4j\Node,
	Everyman\Neo4j\Gremlin,
	Tokenizer\Token;

class Stats {
    private $client = null;
    private $stats = array();
    private $file_filter = '';
    
    public function __construct($client = null) {
        $this->client = $client;
    }

    public function toArray() {
        return $this->stats;
    }

    public function setFileFilter($file) {
        $this->file_filter = ".has('file', '$file')";
        
        return true;
    }
    
    public function __get($name) {
        if (isset($this->stats[$name])) {
            return $this->stats[$name];
        } else {
            return null;
        }
    }

    function collect($type = null) {
        $this->stats['tokens_count']    = $this->queryOne("g.V.except([g.v(0)]){$this->file_filter}.count()");
        $this->stats['relations_count'] = $this->queryOne("g.E.except([g.v(0)]){$this->file_filter}.count()");
        $this->stats['atoms_count']     = $this->queryOne("g.V.except([g.v(0)]).hasNot('atom', 'null'){$this->file_filter}.count()");
        $this->stats['NEXT_count']      = $this->queryOne("g.E.has('label', 'NEXT').inV{$this->file_filter}.count()");
        $this->stats['INDEXED_count']   = $this->queryOne("g.E.has('label', 'INDEXED').inV{$this->file_filter}.count()");
        $this->stats['file_count']      = $this->queryOne("m = [:]; g.V.inE('FILE').file.groupCount(m).iterate(); m.size();");
        $this->stats['no_fullcode']     = $this->queryOne("g.V.except([g.v(0)]).has('fullcode', null).hasNot('index', 'true').filter{!(it.token in ['E_FILE', 'E_NAMESPACE', 'E_CLASS', 'E_FUNCTION'])}.count();");
        $this->stats['lone_token']      = $this->queryOne("g.V.hasNot('atom', null).hasNot('atom', 'File').filter{ it.in.count() == 0}.count()");
    }
    
    function queryOne($query) {
        $r = $this->query($query);
        return $r[0][0];
    }

    function query($query) {
        $params = array('type' => 'IN');
        $query = new Gremlin\Query($this->client, $query, $params);
        return $query->getResultSet();
    }
}

?>