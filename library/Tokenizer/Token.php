<?php

namespace Tokenizer;

class Token {
    protected static $client = null;
    protected static $reserved = array();
    
    protected static $types = array('Variable', 
                                 'VariableDollar',
                                 'Boolean',  
                                 '_Array', 
                                 '_Arraydollarcurly', 
                                 'Constant',
                                 'Sign',
                                 'Property', 
                                 'Keyvalue', 
                                 '_Function',
                                 '_Include', 
                                 'Cast',
                                 'Bitshift', 
                                 'Arrayappend',  
                                 '_Instanceof',  
                                 '_Break',       
                                 '_Continue',    
                                 'Label',        
                                 '_Goto',        
                                 '_Namespace',        
                                 'Staticproperty',
                                 'Staticconstant',
                                 'Staticmethodcall',
                                 'Functioncall',
                                 'FunctioncallArray',
                                 'Methodcall',
                                 'Not', 
                                 'Multiplication', 
                                 'Addition', 
                                 'Parenthesis', 
                                 'Logical',
                                 'Heredoc',
                                 'Reference',
                                 'Noscream', 
                                 'Preplusplus',
                                 'Postplusplus',
                                 '_Throw',
                                 'ArgumentsNoParenthesis',
                                 'ArgumentsNoComma',
                                 'ArgumentsArray',
                                 'Arguments', 
                                 '_Global',
                                 '_New',
                                 'Nsname',
                                 '_Var',
                                 '_Ppp',
                                 '_Static',
                                 'Assignation',
                                 'Comparison',
                                 'Ternary',
                                 '_Return',
                                 '_Const',
                                 '_Class', // Because to Class_tmp
                                 '_Abstract',
                                 '_Final',
                                 '_Case',   
                                 '_Default',
                                 '_Switch',
                                 '_Try',
                                 '_Catch',
                                 '_Foreach',
                                 '_For',
                                 '_Dowhile',
                                 '_While',
                                 'Phpcode',
                                 'Phpcodemiddle',
                                 'Block',
                                 'Sequence', 
                                 'IfthenElse', 
                                 'Ifthen', 
                                 '_Clone', 
                                 '_Interface', 
                                 'Shell', 
                                 'String', 
                                 '_Declare',
                                 'Halt', 
                                 'Concatenation',
                                 'Void', 
                                 'Typehint',
                                 '_Use', 
                                 'ArrayNS',
                                );

    protected $phpversion = 'Any';

    static public $instruction_ending = array();
    
    public function __construct($client) {
        // @todo typehint ? 
        Token::$client = $client; 
        
        Token::$instruction_ending = array_merge(Preplusplus::$operators, 
                                                 Postplusplus::$operators,
                                                 Assignation::$operators, 
                                                 Addition::$operators, 
                                                 Multiplication::$operators,
                                                 Preplusplus::$operators,
                                                 Concatenation::$operators,
                                                 Comparison::$operators,
                                                 Bitshift::$operators,
                                                 Logical::$operators,
                                                 array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_OPEN_BRACKET', 'T_OPEN_PARENTHESIS'));
    }

    public static function getTokenizers($version = null) {
        if (empty($version)) {
            return Token::$types;
        }
        
        $r = array();
        foreach(Token::$types as $type) {
            $class = "Tokenizer\\$type";
            $x = new $class(null);
            
            if ($x->isCompatible($version)) {
                $r[] = $type;
            }
        }
        
        return $r;
    }
    
    protected function isCompatible($version) {
        // this handles Any version of PHP
        if ($this->phpversion == 'Any') {
            return true;
        }

        // version and above 
        if ((substr($this->phpversion, -1) == '+') && version_compare($version, $this->phpversion) >= 0) {
            return true;
        } 

        // up to version  
        if ((substr($this->phpversion, -1) == '-') && version_compare($version, $this->phpversion) <= 0) {
            return true;
        } 

        // version range 1.2.3-4.5.6
        if (strpos($this->phpversion, '-') !== false) {
            list($lower, $upper) = explode('-', $this->phpversion);
            if (version_compare($version, $lower) >= 0 && version_compare($version, $upper) <= 0) {
                return true;
            } else {
                return false;
            }
        } 
        
        // One version only
        if (version_compare($version, $this->phpversion) == 0) {
            return true;
        } 
        
        // Default behavior if we don't understand : 
        return false;
    }

    final public function check() {
        
        display(get_class($this)." check \n");
        if (!method_exists($this, '_check')) {
            print get_class($this). " has no check yet\n";
        } else {
            return $this->_check();
        }

        return true;
    }
    
    public function reserve() {
        return true;
    }

    public function resetReserve() {
        Token::$reserved = array();
    }

    static function countTotalToken() {
        $result = Token::query("g.V.count()");
    	
    	return $result[0][0];
    }

    static function countLeftToken() {
//        $result = Token::query("g.V.has('atom',null).except([g.v(0)]).hasNot('hidden', true).hasNot('index', 'yes').count()");
//        $result = Token::query("g.V.has('root', 'true').in('NEXT').out('NEXT').loop(1){it.object.token != 'T_END'}{true}.count()");
        $result = Token::query("g.idx('racines')[['token':'ROOT']].out('NEXT').loop(1){it.object.token != 'T_END'}{true}.count()");
    	
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

    static public function queryOne($query) {
        $result = Token::query($query);
    	
    	return $result[0][0];
    }

    public function checkRemaining() {
        $class = str_replace("Tokenizer\\", '', get_class($this));
        if (in_array($class, Token::$types)) {
            $query = "g.idx('racines')[['token':'$class']].out('INDEXED').count()";

            return Token::queryOne($query) > 0;
        } else {
            return true;
        }
    }

    public function checkRemaining2() {
        $class = str_replace("Tokenizer\\", '', get_class($this));
        if (in_array($class, Token::$types)) {
            $query = "g.idx('racines')[['token':'$class']].out('INDEXED').count()";

            return Token::queryOne($query) > 0;
        } else {
            return true;
        }
    }
    
    static public function leftInIndex($class) {
        $query = "g.idx('racines')[['token':'$class']].out('INDEXED').count()";

        return Token::queryOne($query);
    }

    static public function countFileToProcess() {
        $query = "g.idx('racines')[['token':'ROOT']].out('INDEXED').count()";

        return Token::queryOne($query);
    }

    
    static public function cleanHidden() {
        $query = " 
g.idx('racines')[['token':'ROOT']].out('INDEXED').as('root').out('NEXT').hasNot('atom',null).out('NEXT').has('token', 'T_END').each{ 
    g.removeVertex(it.in('NEXT').in('NEXT').next()); 
    g.removeVertex(it.out('NEXT').next()); 
    g.removeVertex(it); 
}

g.idx('racines')[['token':'DELETE']].out('DELETE').each{
    g.removeVertex(it);
}

g.V.has('index', 'true').filter{it.out().count() == 0}.each{
    g.removeVertex(it);
}

";
        Token::query($query);
    }

    static public function finishSequence() {
        $query = " 
g.idx('racines')[['token':'ROOT']].out('INDEXED').as('root').out('NEXT').hasNot('atom',null).out('NEXT').has('token', 'T_END').each{ 
    g.removeVertex(it.in('NEXT').in('NEXT').next()); 
    g.removeVertex(it.out('NEXT').next()); 
    g.removeVertex(it); 
}

g.idx('racines')[['token':'DELETE']].out('DELETE').each{
    g.removeVertex(it);
}


";
        Token::query($query);
    }
    
    static public function finishSequenceOld() {
        $query = "
g.idx('racines')[['token':'ROOT']].as('root').out('NEXT').hasNot('token', 'T_END').back('root').each{ 
    x = g.addVertex(null, [code:'Final sequence', atom:'Sequence', token:'T_SEMICOLON', file:it.file]);

    a = it.in('NEXT').next();
  
    g.V.hasNot('hidden', true).has('file', it.file).as('o').in('NEXT').back('o').each{
        g.addEdge(x, it, 'ELEMENT');
        y = it.out('NEXT').next();
        g.removeEdge(it.inE('NEXT').next());
    }

    g.addEdge(x, y, 'NEXT');
    g.addEdge(a, x, 'NEXT');
    x.setProperty('root', true);
    g.addEdge(g.idx('racines')[['token':'ROOT']].next(), it, 'INDEXED');   

}


       ";
        Token::query($query);
    }
}

?>