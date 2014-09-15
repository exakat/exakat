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
                                 'Power', 
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
                                                 array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_OPEN_BRACKET', 
                                                       'T_OPEN_PARENTHESIS', 'T_INSTANCEOF', 'T_QUESTION'));
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
            $this->remaining = 0;
            $this->done = 0;
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
        $queries = array( " 

// cleans root token
g.idx('racines')[['token':'ROOT']].out('INDEXED').as('root').out('NEXT').hasNot('atom',null).out('NEXT').has('token', 'T_END').each{ 
    g.removeVertex(it.in('NEXT').in('NEXT').next()); 
    g.removeVertex(it.out('NEXT').next()); 
    g.removeVertex(it); 
};

g.V.has('root', 'true')[0].inE('INDEXED').each{ 
    g.removeEdge(it); 
};

g.idx('delete')[['node':'delete']].each{
    g.removeVertex(it);
}

// clean indexed (if no more index...)
g.V.has('index', 'true').filter{it.out().count() == 0}.each{
    g.removeVertex(it);
};
// running twice the query, for better cleaning (why so?)
g.V.has('index', 'true').filter{it.out().count() == 0}.each{
    g.removeVertex(it);
};

", "
//////////////////////////////////////////////////////////////////////////////////////////
// calculating variable namespace
//////////////////////////////////////////////////////////////////////////////////////////
g.idx('Variable')[['token':'node']].sideEffect{fullcode = it;}.in.loop(1){it.object.atom != 'File'}{it.object.atom in ['Function', 'Class', 'Namespace', 'File']}.each{ 
    if (it.atom == 'Namespace') {
        fullcode.setProperty('namespace', it.out('NAMESPACE').next().code);
    } else if (it.atom == 'Class') {
        fullcode.setProperty('class', it.out('NAME').next().code);
    } else if (it.atom == 'Function' && fullcode.function == null) {
        fullcode.setProperty('method', it.out('NAME').next().code);
    } else if (it.atom == 'File') {
        fullcode.setProperty('File', it.code);
    }
};

", "
//////////////////////////////////////////////////////////////////////////////////////////
// calculating the full namespaces paths
//////////////////////////////////////////////////////////////////////////////////////////
// const in a namespace (and not a class)
g.idx('Const')[['token':'node']].filter{it.in('ELEMENT').in('BLOCK').any()}.sideEffect{fullcode = it;}.in.loop(1){it.object.atom != 'Class'}{it.object.atom =='Namespace'}.each{ 
    if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.out('NAME').next().fullcode.toLowerCase());
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.out('NAME').next().fullcode.toLowerCase());
    }
};

// const without class nor namspace (aka, global)
g.idx('Const')[['token':'node']].filter{it.in('ELEMENT').in('BLOCK').any() == false}.each{ 
    it.setProperty('fullnspath', '\\\\' + it.out('NAME').next().fullcode.toLowerCase());
};
", "
// Const (out of a class) with define
g.idx('Functioncall')[['token':'node']].has('code', 'define').out('ARGUMENTS').out('ARGUMENT').has('order', 0).as('name').has('atom', 'String')
    .in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.sideEffect{ ns = it; }.back('name')
.each{ 
    if (ns.atom == 'File') {
        it.setProperty('fullnspath', '\\\\' + it.noDelimiter.toLowerCase());
    } else {
        it.setProperty('fullnspath', '\\\\' + ns.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + it.noDelimiter.toLowerCase());
    }
    g.idx('constants').put('path', it.fullnspath, it)
};
", "
// function definitions
g.idx('Function')[['token':'node']].filter{it.out('NAME').next().code != ''}.sideEffect{fullcode = it.out('NAME').next();}
    .filter{it.in('ELEMENT').in('BLOCK').any() == false || !(it.in('ELEMENT').in('BLOCK').next().atom in ['Class', 'Interface'])}
    .in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.code.toLowerCase());
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.code.toLowerCase());
    }

    g.idx('functions').put('path', fullcode.fullnspath.toLowerCase(), fullcode);
};
", "
// class definitions
g.idx('Class')[['token':'node']].sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.out('NAME').next().code.toLowerCase());
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.out('NAME').next().code.toLowerCase());
    }
};

g.idx('Class')[['token':'node']].out('IMPLEMENTS', 'EXTENDS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode.toLowerCase());
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.code.toLowerCase());
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.fullcode.toLowerCase());
    }
};
", "
// trait definitions
g.idx('Trait')[['token':'node']].sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.out('NAME').next().code.toLowerCase());
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.out('NAME').next().code.toLowerCase());
    }
};

// interfaces definitions
g.idx('Interface')[['token':'node']].sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.out('NAME').next().code.toLowerCase());
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.out('NAME').next().code.toLowerCase());
    }
};

// also add interfaces and Traits and their respective extensions
", "

g.idx('Functioncall')[['token':'node']].filter{it.in('METHOD').any() == false}
                                       .filter{it.in('NEW').any() == false}
                                       .sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}
.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', '' + fullcode.code.toLowerCase());
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.code.toLowerCase());
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.code.toLowerCase());
    } 
};

// function usage
// fallback for functions : if not defined, then fallback to \
g.idx('Functioncall')[['token':'node']]
    .has('token', 'T_STRING')
    .filter{ it.inE('METHOD').any() == false; }
    .filter{ it.in('NEW').any() == false; }
    .filter{ g.idx('functions')[['path':it.fullnspath]].any() == false}
    .each{
        it.setProperty('fullnspath', '\\\\' + it.code);
    }
", "
// use  usage
g.idx('Use')[['token':'node']].out('USE').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.originpath.toLowerCase());
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.originpath.toLowerCase());
    } 
};

", "
// class usage
g.idx('Staticmethodcall')[['token':'node']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode.toLowerCase());
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.fullcode.toLowerCase());
    } else {
        isDefault = true;
        it.out('BLOCK').out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{alias = it}.filter{it.alias.toLowerCase() == fullcode.fullcode.toLowerCase()}.each{
            fullcode.setProperty('fullnspath', alias.fullnspath);
            fullcode.setProperty('aliased', 'true');
            isDefault = false;
        } ;
        
        if (isDefault) {
            if (it.atom == 'File' || it.fullcode == 'namespace Global') {
                fullcode.setProperty('fullnspath', '\\\\' + fullcode.code.toLowerCase());
            } else {
                fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.fullcode.toLowerCase());
            }
        };
    } 
};
", "
g.idx('Staticproperty')[['token':'node']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode.toLowerCase());
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.fullcode.toLowerCase());
    } else {
        isDefault = true;
        it.out('BLOCK').out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{alias = it}.filter{it.alias.toLowerCase() == fullcode.fullcode.toLowerCase()}.each{
            fullcode.setProperty('fullnspath', alias.fullnspath);
            fullcode.setProperty('aliased', 'true');
            isDefault = false;
        } ;
        
        if (isDefault) {
            if (it.atom == 'File' || it.fullcode == 'namespace Global') {
                fullcode.setProperty('fullnspath', '\\\\' + fullcode.code.toLowerCase());
            } else {
                fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.fullcode.toLowerCase());
            }
        };
    } 
};
", "
g.idx('Staticconstant')[['token':'node']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode.toLowerCase());
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.fullcode.toLowerCase());
    } else {
        isDefault = true;
        it.out('BLOCK').out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{alias = it}.filter{it.alias.toLowerCase() == fullcode.fullcode.toLowerCase()}.each{
            fullcode.setProperty('fullnspath', alias.fullnspath);
            fullcode.setProperty('aliased', 'true');
            isDefault = false;
        } ;
        
        if (isDefault) {
            if (it.atom == 'File' || it.fullcode == 'namespace Global') {
                fullcode.setProperty('fullnspath', '\\\\' + fullcode.code.toLowerCase());
            } else {
                fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.fullcode.toLowerCase());
            }
        };
    } 
};

", "
g.idx('Instanceof')[['token':'node']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode.toLowerCase());
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.code.toLowerCase());
    } else {
        isDefault = true;
        it.out('BLOCK').out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{alias = it}.filter{it.alias.toLowerCase() == fullcode.fullcode.toLowerCase()}.each{
            fullcode.setProperty('fullnspath', alias.fullnspath);
            fullcode.setProperty('aliased', 'true');
            isDefault = false;
        } ;
        
        if (isDefault) {
            if (it.atom == 'File' || it.fullcode == 'namespace Global') {
                fullcode.setProperty('fullnspath', '\\\\' + fullcode.code.toLowerCase());
            } else {
                fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.fullcode.toLowerCase());
            }
        };
    } 
};
", "
g.idx('Catch')[['token':'node']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode.toLowerCase());
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.fullcode.toLowerCase());
    } else {
        isDefault = true;
        it.out('BLOCK').out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{alias = it}.filter{it.alias.toLowerCase() == fullcode.fullcode.toLowerCase()}.each{
            fullcode.setProperty('fullnspath', alias.fullnspath);
            fullcode.setProperty('aliased', 'true');
            isDefault = false;
        } ;
        
        if (isDefault) {
            if (it.atom == 'File' || it.fullcode == 'namespace Global') {
                fullcode.setProperty('fullnspath', '\\\\' + fullcode.code.toLowerCase());
            } else {
                fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.fullcode.toLowerCase());
            }
        };
    } 
};

g.idx('Typehint')[['token':'node']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode.toLowerCase());
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.fullcode.toLowerCase());
    } else {
        isDefault = true;
        it.out('BLOCK').out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{alias = it}.filter{it.alias.toLowerCase() == fullcode.fullcode.toLowerCase()}.each{
            fullcode.setProperty('fullnspath', alias.fullnspath);
            fullcode.setProperty('aliased', 'true');
            isDefault = false;
        } ;
        
        if (isDefault) {
            if (it.atom == 'File' || it.fullcode == 'namespace Global') {
                fullcode.setProperty('fullnspath', '\\\\' + fullcode.code.toLowerCase());
            } else {
                fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.fullcode.toLowerCase());
            }
        };
    } 
};

", "
g.idx('New')[['token':'node']].out('NEW').filter{ it.atom in ['Identifier', 'Nsname', 'Functioncall']}.sideEffect{fullcode = it;}
                              .filter{it.token in ['T_STRING', 'T_NS_SEPARATOR']}
                              .in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    
    if (fullcode.token == 'T_STRING') {
        isDefault = true;
        it.out('BLOCK', 'CODE').out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{alias = it}.has('alias', fullcode.code).each{
            fullcode.setProperty('fullnspath', alias.fullnspath);
            fullcode.setProperty('aliased', 'true');
            isDefault = false;
        } ;
        
        if (isDefault) {
            if (it.atom == 'File' || it.fullcode == 'namespace Global') {
                fullcode.setProperty('fullnspath', '\\\\' + fullcode.code.toLowerCase());
            } else {
                fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.code.toLowerCase());
            }
        };
    } else {
        s = [];
        fullcode.out('SUBNAME').sort{it.order}._().each{ 
            s.add(it.getProperty('code')); 
        };
        if (fullcode.absolutens == 'true') {
            s =  '\\\\' + s.join('\\\\').toLowerCase();
        } else {
            s = s.join('\\\\').toLowerCase();
        };

        if (fullcode.absolutens == 'true') { 
            fullcode.setProperty('fullnspath', s);
        } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
            fullcode.setProperty('fullnspath', '\\\\' + s);
        } else {
            fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + s);
        } ;
    };
};

", "
// Constant usage (1)
g.idx('Identifier')[['token':'node']].filter{it.in('USE', 'SUBNAME', 'METHOD', 'CLASS', 'NAME', 'CONSTANT', 'NAMESPACE', 'NEW', 'IMPLEMENTS', 'EXTENDS').count() == 0}
    .filter{it.out('ARGUMENTS').count() == 0}
    .sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 

    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode.toLowerCase());
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.code.toLowerCase());
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.code.toLowerCase());
    } 
};
", "
// Constant usage (2)
g.idx('Nsname')[['token':'node']].filter{it.in('USE', 'SUBNAME', 'METHOD', 'CLASS', 'NAME', 'CONSTANT', 'NAMESPACE', 'NEW', 'IMPLEMENTS', 'EXTENDS').any() == false}
    .filter{it.out('ARGUMENTS').count() == 0}
    .sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
        if (fullcode.absolutens == 'true') { 
            if (fullcode.atom == 'Functioncall') {
            // bizarre...  fullcode but with code length ? 
                fullcode.setProperty('fullnspath', fullcode.fullcode.substring(1,fullcode.code.length()).toLowerCase());
            } else {
                fullcode.setProperty('fullnspath', fullcode.fullcode.toLowerCase());
            }
        } else if (fullcode.atom == 'Functioncall') {
            fullcode.setProperty('fullnspath', it.out('NAME').next().fullcode.toLowerCase() + '\\\\' + fullcode.code.toLowerCase());
        } else if (it.atom == 'File') {
            fullcode.setProperty('fullnspath', '\\\\' + fullcode.fullcode.toLowerCase());
        } else {
            fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.fullcode.toLowerCase());
        }
};
", "
// collecting classes
g.idx('Class')[['token':'node']].each{ 
    g.idx('classes').put('path', it.fullnspath.toLowerCase(), it)
};

", "
// collecting namespaces
g.idx('Namespace')[['token':'node']].each{ 
    // creating namespace's fullnspath
    it.fullnspath = '\\\\' + it.out('NAMESPACE').next().fullcode;
    g.idx('namespaces').put('path', it.fullnspath.toLowerCase(), it)
};

", "
////// Solving classes Namespaces
// NEW + self, static, parent
g.idx('Functioncall')[['token':'node']]
    .filter{it.token in ['T_STRING', 'T_STATIC']}
    .filter{ it.in('NEW').any()}
    .each{
        if (it.getProperty('code').toLowerCase() == 'self') { // class de definition
            it.setProperty('fullnspath', it.in.loop(1){it.object.atom != 'Class'}{it.object.atom == 'Class'}.next().fullnspath);
        } else if (it.getProperty('code').toLowerCase() == 'static') { // class courante à l'exécution... 
            it.setProperty('fullnspath', '\\\\' + it.code.toLowerCase()); // '\static'
        } else if (it.getProperty('code').toLowerCase() == 'parent') {
            it.setProperty('fullnspath', it.in.loop(1){it.object.atom != 'Class'}{it.object.atom == 'Class'}.out('EXTENDS').transform{ g.idx('classes').get('path', it.fullnspath).next(); }.next().fullnspath);
        } 
    }; 

", "
// local class in its namespace
g.idx('New')[['token':'node']]
    .out('NEW')
    .has('token', 'T_STRING')
    .filter{ uses = []; node = it; it.in.loop(1){true}{it.object.atom == 'File'}.out('NAMESPACE')
                                     .filter{path = '\\\\' + it.fullcode + '\\\\' + node.code; g.idx('classes')[['path':path.toLowerCase()]].any(); }.any();
            }
    .each{
        node.setProperty('fullnspath', path.toLowerCase());
    }; 

g.idx('Interface')[['token':'node']].each{ 
    g.idx('interfaces').put('path', it.fullnspath.toLowerCase(), it)
};

g.idx('Trait')[['token':'node']].each{ 
    g.idx('traits').put('path', it.fullnspath.toLowerCase(), it)
};

"
);

        foreach($queries as $query) {
            // @todo make this //
            Token::query($query);
        }
    }

    static public function finishSequence() {
        $query = " 

// remove root token when there are no NEXT to process 
g.idx('racines')[['token':'ROOT']].out('INDEXED').as('root').out('NEXT').hasNot('atom',null).out('NEXT').has('token', 'T_END').each{ 
    g.removeVertex(it.in('NEXT').in('NEXT').next()); 
    g.removeVertex(it.out('NEXT').next()); 
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