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
                                 '_Class',
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
                                 'Typehint',
                                 '_Use', 
                                 'ArrayNS',
                                 '_Insteadof',
                                 '_As',
                                 'Variadic',
                                 '_Trait',
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
        $result = Token::query("g.idx('racines')[['token':'ROOT']].out('NEXT').loop(1){it.object.token != 'T_END'}{true}.count()");
    	
    	return $result[0][0];
    }

    static function countLeftNext() {
        $result = Token::query("g.idx('racines')[['token':'ROOT']].out('INDEXED').out('NEXT').loop(1){it.object.token != 'T_END'}{true}.count()");
    	
    	return $result[0][0] + 1;
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
    	    die(__METHOD__);
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

g.V.has('root', 'true')[0].inE('INDEXED').each{ 
    g.removeEdge(it); 
}

g.idx('racines')[['token':'DELETE']].out('DELETE').each{
    g.removeVertex(it);
}

g.V.has('index', 'true').filter{it.out().count() == 0}.each{
    g.removeVertex(it);
}


// calculating the full namespaces paths
g.idx('Const')[['token':'node']].sideEffect{fullcode = it;}.in.loop(1){it.object.atom != 'Class'}{it.object.atom =='Namespace'}.each{ fullcode.setProperty('fullnspath', it.out('NAMESPACE').next().fullcode + '\\\\' + fullcode.out('NAME').next().fullcode);}

// function definitions
g.idx('Function')[['token':'node']].sideEffect{fullcode = it.out('NAME').next();}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.code);
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode + '\\\\' + fullcode.code);
    }
}

// class definitions
g.idx('Class')[['token':'node']].sideEffect{fullcode = it;}.in.loop(1){it.object.atom != 'Class'}{it.object.atom =='Namespace'}.each{ fullcode.setProperty('fullnspath', it.out('NAMESPACE').next().fullcode + '\\\\' + fullcode.out('NAME').next().fullcode);}

// function usage
g.idx('Functioncall')[['token':'node']].filter{it.in('METHOD').count() == 0}.sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}
.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', '' + fullcode.code);
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.code);
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode + '\\\\' + fullcode.code);
    } 
}

// class usage
g.idx('Staticmethodcall')[['token':'node']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode);
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.code);
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode + '\\\\' + fullcode.code);
    } 
}

g.idx('Staticproperty')[['token':'node']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode);
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.code);
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode + '\\\\' + fullcode.code);
    } 
}

g.idx('Staticconstant')[['token':'node']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode);
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.code);
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode + '\\\\' + fullcode.code);
    } 
}

g.idx('Instanceof')[['token':'node']].out('RIGHT').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode);
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.code);
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode + '\\\\' + fullcode.code);
    } 
}

g.idx('Catch')[['token':'node']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode);
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.code);
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode + '\\\\' + fullcode.code);
    } 
}

g.idx('Typehint')[['token':'node']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode);
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.code);
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode + '\\\\' + fullcode.code);
    } 
}

g.idx('New')[['token':'node']].out('NEW').filter{ it.atom in ['Identifier', 'Nsname']}.sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.atom == 'Nsname') {
        if (fullcode.absolutens == 'true') { 
            fullcode.setProperty('fullnspath', fullcode.fullcode);
        } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
            fullcode.setProperty('fullnspath', '\\\\' + fullcode.code);
        } else {
            fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode + '\\\\' + fullcode.code);
        }
    } else {
        if (it.atom == 'File' || it.fullcode == 'namespace Global') {
            fullcode.setProperty('fullnspath', '\\\\' + fullcode.code);
        } else {
            fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode + '\\\\' + fullcode.code);
        }           
    }
}

// Constant usage
g.idx('Identifier')[['token':'node']].filter{it.in('SUBNAME', 'METHOD', 'CLASS', 'NAME', 'CONSTANT', 'NAMESPACE', 'NEW').count() == 0}
    .filter{it.out('ARGUMENTS').count() == 0}
    .sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 

    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode);
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.code);
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode + '\\\\' + fullcode.code);
    } 
}

g.idx('Nsname')[['token':'node']].filter{it.in('SUBNAME', 'METHOD', 'CLASS', 'NAME', 'CONSTANT', 'NAMESPACE', 'NEW').count() == 0}
    .filter{it.out('ARGUMENTS').count() == 0}
    .sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom =='Namespace'}.each{ 
        if (fullcode.absolutens == 'true') { 
            if (fullcode.atom == 'Functioncall') {
                fullcode.setProperty('fullnspath', fullcode.fullcode.substring(1,fullcode.code.length()));
            } else {
                fullcode.setProperty('fullnspath', fullcode.fullcode.substring(1,fullcode.fullcode.length()));
            }
        } else if (fullcode.atom == 'Functioncall') {
            fullcode.setProperty('fullnspath', it.out('NAMESPACE').next().fullcode + '\\\\' + fullcode.code);
        } else {
            fullcode.setProperty('fullnspath', it.out('NAMESPACE').next().fullcode + '\\\\' + fullcode.fullcode);
        }    
}

// fallback to global NS for functions and constants : but we need to know what is defined! 

/*
g.idx('Functioncall')[['token':'node']].filter{!it.in('METHOD').any()}.each{
    functioncall = it;
    g.idx('Function')[['token':'node']].as('Function').out('NAME').filter{it.'fullnspath'.toLowerCase() == functioncall.fullnspath.toLowerCase()}.back('Function').each{
        g.addEdge(functioncall, it, 'DEFINED');
    }
}
*/




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

    public static function getClass($class) {
        if (class_exists($class)) {
            return $class;
        } else {
            return false;
        }
    }
    
    public static function getInstance($name, $client, $phpversion = 'Any') {
        if ($analyzer = Token::getClass($name)) {
            $analyzer = new $analyzer($client);
            if ($analyzer->checkPhpVersion($phpversion)) {
                return $analyzer;
            } else {
                return null;
            }
        } else {
            print "No such class as '$name'\n";
            return null;
        }
    }

    public function checkPhpVersion($version) {
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
}

?>