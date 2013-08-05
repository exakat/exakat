<?php

namespace Tokenizer;

class Token {
    protected static $client = null;
    protected static $reserved = array();

//  Cannot be used, because T_QUOTE is used both for opening and closing. 
//                                 'String', 
    
    public static $types = array(//'Variable', conflict between transform and indexes. 
                                 'Variabledollar',
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
                                 'Staticproperty',
                                 'Staticconstant',
                                 'Staticmethodcall',
                                 'Functioncall',
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
                                 'Argumentsnocomma',
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
                                 //'_Class' Because to Class_tmp
                                 '_Abstract',
                                 '_Final',
                                 '_Case', //Check index in the regex
                                 // '_Default', Check index in the regex
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
                                 //'Blockspecial', // conflict with 0th element.
                                 'Sequence', 
//                                 'SequenceAtom', 
                                 'Ifthen', 
                                );
    
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
        $result = Token::query("g.V.has('atom',null).except([g.v(0)]).hasNot('hidden', true).hasNot('index', 'yes').count()");
    	
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

    static public function cleanHidden() {
        $query = " g.V.has('token','T_ROOT').out('NEXT').hasNot('atom',null).out('NEXT').has('token', 'T_END').each{ 
    g.removeVertex(it.in('NEXT').in('NEXT').next()); 
    g.removeVertex(it.out('NEXT').next()); 
    g.removeVertex(it); 
}

g.V.has('index', 'yes').filter{it.out('INDEXED').count() == 0}.each{
    g.removeVertex(it);
}
";
        Token::query($query);
    }

    static public function finishSequence() {
        $query = "
g.V.has('root', 'true').as('root').out('NEXT').hasNot('token', 'T_END').back('root').each{ 
    x = g.addVertex(null, [code:'Final sequence', atom:'Sequence', token:'T_SEMICOLON', file:it.file]);

//    g.removeEdge(it.outE('NEXT').next());
    a = it.in('NEXT').next();
  
    g.V.hasNot('hidden', true).has('file', it.file).as('o').in('NEXT').back('o').each{
        g.addEdge(x, it, 'ELEMENT');
        y = it.out('NEXT').next();
        g.removeEdge(it.inE('NEXT').next());
    }

    g.addEdge(x, y, 'NEXT');
    g.addEdge(a, x, 'NEXT');
    x.setProperty('root', true);
}


       ";
        Token::query($query);
    }
}

?>