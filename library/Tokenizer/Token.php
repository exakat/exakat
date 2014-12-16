<?php

namespace Tokenizer;

class Token {
    protected static $client = null;
    protected static $reserved = array();
    
    // the numeric indices are important for processing order
    protected static $types = array ( 0 => 'Variable',
                                      2 => 'VariableDollar',
                                      3 => 'Boolean',
                                      4 => 'Sign',
                                      19 => '_Break',
                                      21 => 'Label',
                                      22 => '_Goto',
                                      5 => '_Array',
                                      24 => '_Global',
                                      6 => 'String',
                                      59 => 'Shell',
                                      7 => '_Arraydollarcurly',
                                      8 => 'Property',
                                      31 => 'Postplusplus',
                                      30 => 'Preplusplus',
                                      9 => 'Keyvalue',
                                      62 => '_Abstract',
                                      50 => '_Static',
                                      85 => '_Final', 
                                      48 => '_Var',
                                      49 => '_Ppp',
                                      10 => '_Function',
                                      12 => 'Logical',
                                      13 => 'Heredoc',
                                      14 => 'Not',
                                      15 => 'Cast',
                                      16 => 'Reference',
                                      83 => 'Variadic',
                                      17 => 'Arrayappend',
                                      18 => '_Instanceof',
                                      86 => '_Insteadof',
                                      87 => '_As',
                                      20 => '_Continue',
                                      23 => '_New',
                                      25 => 'Nsname',
                                      26 => '_Namespace',
                                      27 => '_Use',
                                      28 => 'ArrayNS',
                                      29 => '_Include',
                                      32 => 'Noscream',
                                      34 => '_Clone',
                                      35 => 'Arguments',
                                      36 => 'ArgumentsNoParenthesis',
                                      37 => 'ArgumentsNoComma',
                                      38 => 'ArgumentsArray',
                                      33 => 'Parenthesis',
                                      1 => 'Halt',
                                      39 => 'Functioncall',
                                      40 => 'FunctioncallArray',
                                      41 => 'Methodcall',
                                      11 => 'Staticproperty',
                                      42 => 'Staticmethodcall',
                                      43 => 'Staticconstant',
                                      90 => 'Staticclass',
                                      82 => 'Power',
                                      44 => 'Multiplication',
                                      45 => 'Addition',
                                      46 => 'Bitshift',
                                      47 => 'Concatenation',
                                      51 => 'Assignation',
                                      52 => 'Comparison',
                                      53 => 'Ternary',
                                      54 => 'Constant',
                                      55 => '_Return',
                                      56 => '_Declare',
                                      57 => '_Const',
                                      58 => 'Block',
                                      84 => '_Trait',
                                      60 => '_Interface',
                                      61 => '_Class',
                                      64 => 'Typehint',
                                      65 => '_Throw',
                                      66 => '_Case',
                                      67 => '_Default',
                                      68 => '_Switch',
                                      70 => 'IfthenElse',
                                      69 => 'Ifthen',
                                      71 => '_Foreach',
                                      72 => '_For',
                                      73 => '_While',
                                      74 => '_Dowhile',
                                      75 => '_Catch',
                                      88 => '_Finally',
                                      89 => '_Yield',
                                      76 => '_Try',
                                      80 => 'Phpcodemiddle',
                                      77 => 'Sequence',
                                      81 => 'Phpcode',
                                    );

    protected $phpVersion = 'Any';

    static public $instructionEnding = array();
    
    public function __construct($client) {
        // @todo typehint ? 
        Token::$client = $client; 
        
        Token::$instructionEnding = array_merge(Preplusplus::$operators, 
                                                Postplusplus::$operators,
                                                Assignation::$operators, 
                                                Addition::$operators, 
                                                Multiplication::$operators,
                                                Preplusplus::$operators,
                                                Concatenation::$operators,
                                                Comparison::$operators,
                                                Bitshift::$operators,
                                                Logical::$operators,
                                                Property::$operators,
                                                Staticproperty::$operators,
                                                _Instanceof::$operators,
                                                array('T_OPEN_BRACKET', 'T_OPEN_PARENTHESIS', 'T_QUESTION'));
    }

    public static function getTokenizers($version = null) {
        if ($version === null) {
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
        if ($this->phpVersion == 'Any') {
            return true;
        }

        // version and above 
        if ((substr($this->phpVersion, -1) == '+') && version_compare($version, $this->phpVersion) >= 0) {
            return true;
        } 

        // up to version  
        if ((substr($this->phpVersion, -1) == '-') && version_compare($version, $this->phpVersion) <= 0) {
            return true;
        } 

        // version range 1.2.3-4.5.6
        if (strpos($this->phpVersion, '-') !== false) {
            list($lower, $upper) = explode('-', $this->phpVersion);
            if (version_compare($version, $lower) >= 0 && version_compare($version, $upper) <= 0) {
                return true;
            } else {
                return false;
            }
        } 
        
        // One version only
        if (version_compare($version, $this->phpVersion) == 0) {
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
    	$parameters = array('type' => 'IN');
    	try {
    	    $query = new \Everyman\Neo4j\Gremlin\Query(Token::$client, $queryTemplate, $parameters);
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
// calculating the full namespaces paths
//////////////////////////////////////////////////////////////////////////////////////////
// const in a namespace (and not a class)
g.idx('atoms')[['atom':'Const']].filter{it.in('ELEMENT').in('BLOCK').any()}.sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Class', 'Trait'])}{it.object.atom =='Namespace'}.each{ 
    if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.out('NAME').next().fullcode.toLowerCase());
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.out('NAME').next().fullcode.toLowerCase());
    }

    g.idx('constants').put('path', fullcode.fullnspath, it)
};

// const without class nor namspace (aka, global)
g.idx('atoms')[['atom':'Const']].filter{it.in('ELEMENT').in('BLOCK').any() == false}.each{ 
    it.setProperty('fullnspath', '\\\\' + it.out('NAME').next().fullcode.toLowerCase());

    g.idx('constants').put('path', it.fullnspath, it)
};
", "
// Const (out of a class) with define
g.idx('atoms')[['atom':'Functioncall']].has('code', 'define').out('ARGUMENTS').out('ARGUMENT').has('rank', 0).as('name')
    .has('atom', 'String').hasNot('noDelimiter', null)
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
g.idx('atoms')[['atom':'Function']].filter{it.out('NAME').next().code != ''}.sideEffect{fullcode = it.out('NAME').next();}
    .filter{it.in('ELEMENT').in('BLOCK').any() == false || !(it.in('ELEMENT').in('BLOCK').next().atom in ['Class', 'Trait', 'Interface'])}
    .in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.code.toLowerCase());
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.code.toLowerCase());
    }

    g.idx('functions').put('path', fullcode.fullnspath.toLowerCase(), fullcode);
};
", "
// use  usage
g.idx('atoms')[['atom':'Use']].out('USE').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.originpath.toLowerCase());
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.originpath.toLowerCase());
    } 
};

", "
// class definitions
g.idx('atoms')[['atom':'Class']].sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.out('NAME').next().code.toLowerCase());
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.out('NAME').next().code.toLowerCase());
    }
};

", "
g.idx('atoms')[['atom':'Class']].out('IMPLEMENTS', 'EXTENDS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode.toLowerCase());
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.code.toLowerCase());
    } else if (it.out('BLOCK', 'FILE').transform{ if (it.out('ELEMENT').has('atom', 'Php').out('CODE').any()) { it.out('ELEMENT').out('CODE').next(); } else { it }}.out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{thealias = it;}.filter{ it.alias == fullcode.code.toLowerCase()}.any() ) {
        fullcode.setProperty('fullnspath', thealias.fullnspath);
    } else {
        isDefault = true;
        if (fullcode.token == 'T_NS_SEPARATOR') {
            fullcodealias = fullcode.out('SUBNAME').has('rank', 0).next().code.toLowerCase();
        } else {
            fullcodealias = fullcode.code.toLowerCase();
        }
        it.out('BLOCK', 'FILE').transform{ if (it.out('ELEMENT').has('atom', 'Php').out('CODE').any()) { it.out('ELEMENT').out('CODE').next(); } else { it }}.out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{alias = it}.filter{it.alias == fullcodealias}.each{
            if (fullcode.token == 'T_NS_SEPARATOR') {
                s=[];
                fullcode.out('SUBNAME').filter{it.rank > 0}.sort{it.rank}._().each{ s.add(it.code);}
                fullcode.setProperty('fullnspath', alias.fullnspath + '\\\\' + s.join('\\\\').toLowerCase());
            } else {
                fullcode.setProperty('fullnspath', alias.fullnspath);
            }
            fullcode.setProperty('aliased', 'true');
            isDefault = false;
        };
        
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
g.idx('atoms')[['atom':'Interface']].out('IMPLEMENTS', 'EXTENDS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode.toLowerCase());
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.code.toLowerCase());
    } else {
        isDefault = true;
        if (fullcode.token == 'T_NS_SEPARATOR') {
            fullcodealias = fullcode.out('SUBNAME').has('rank', 0).next().code.toLowerCase();
        } else {
            fullcodealias = fullcode.code.toLowerCase();
        }
        it.out('BLOCK', 'FILE').transform{ if (it.out('ELEMENT').has('atom', 'Php').out('CODE').any()) { it.out('ELEMENT').out('CODE').next(); } else { it }}.out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{alias = it}.filter{it.alias == fullcodealias}.each{
            if (fullcode.token == 'T_NS_SEPARATOR') {
                s=[];
                fullcode.out('SUBNAME').filter{it.rank > 0}.sort{it.rank}._().each{ s.add(it.code);}
                fullcode.setProperty('fullnspath', alias.fullnspath + '\\\\' + s.join('\\\\').toLowerCase());
            } else {
                fullcode.setProperty('fullnspath', alias.fullnspath);
            }
            fullcode.setProperty('aliased', 'true');
            isDefault = false;
        };
        
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
g.idx('atoms')[['atom':'Trait']].out('IMPLEMENTS', 'EXTENDS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode.toLowerCase());
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.code.toLowerCase());
    } else {
        isDefault = true;
        if (fullcode.token == 'T_NS_SEPARATOR') {
            fullcodealias = fullcode.out('SUBNAME').has('rank', 0).next().code.toLowerCase();
        } else {
            fullcodealias = fullcode.code.toLowerCase();
        }
        it.out('BLOCK', 'FILE').transform{ if (it.out('ELEMENT').has('atom', 'Php').out('CODE').any()) { it.out('ELEMENT').out('CODE').next(); } else { it }}.out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{alias = it}.filter{it.alias == fullcodealias}.each{
            if (fullcode.token == 'T_NS_SEPARATOR') {
                s=[];
                fullcode.out('SUBNAME').filter{it.rank > 0}.sort{it.rank}._().each{ s.add(it.code);}
                fullcode.setProperty('fullnspath', alias.fullnspath + '\\\\' + s.join('\\\\').toLowerCase());
            } else {
                fullcode.setProperty('fullnspath', alias.fullnspath);
            }
            fullcode.setProperty('aliased', 'true');
            isDefault = false;
        };
        
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
// trait definitions
g.idx('atoms')[['atom':'Trait']].sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.out('NAME').next().code.toLowerCase());
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.out('NAME').next().code.toLowerCase());
    }
};

// interfaces definitions
g.idx('atoms')[['atom':'Interface']].sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.out('NAME').next().code.toLowerCase());
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.out('NAME').next().code.toLowerCase());
    }
};

// also add interfaces and Traits and their respective extensions
", "

g.idx('atoms')[['atom':'Functioncall']].filter{it.in('METHOD').any() == false}
                                       .filter{it.in('NEW').any() == false}
                                       .filter{it.token in ['T_STRING', 'T_NS_SEPARATOR']}
                                       .sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}
.each{ 
    if (fullcode.token == 'T_NS_SEPARATOR') {
        s = [];
        fullcode.out('SUBNAME').sort{it.rank}._().each{ 
            s.add(it.getProperty('code')); 
        };
        s = s.join('\\\\').toLowerCase();
    } else { // T_STRING
        s = fullcode.code.toLowerCase();
    }
    
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', '\\\\' + s);
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + s);
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + s);
    } 
};

", "
// function usage
// fallback for functions : if not defined, then fallback to \
g.idx('atoms')[['atom':'Functioncall']]
    .has('token', 'T_STRING')
    .filter{ it.inE('METHOD').any() == false; }
    .filter{ it.in('NEW').any() == false; }
    .filter{ g.idx('functions')[['path':it.fullnspath]].any() == false}
    .each{
        it.setProperty('fullnspath', '\\\\' + it.code.toLowerCase());
    }

", "

// class usage
g.idx('atoms')[['atom':'Staticmethodcall']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode.toLowerCase());
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.fullcode.toLowerCase());
    } else {
        isDefault = true;
        if (fullcode.token == 'T_NS_SEPARATOR') {
            fullcodealias = fullcode.out('SUBNAME').has('rank', 0).next().code.toLowerCase();
        } else {
            fullcodealias = fullcode.code.toLowerCase();
        }
        it.out('BLOCK', 'FILE').transform{ if (it.out('ELEMENT').has('atom', 'Php').out('CODE').any()) { it.out('ELEMENT').out('CODE').next(); } else { it }}.out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{alias = it}.filter{it.alias == fullcodealias}.each{
            fullcode.setProperty('fullnspath', alias.fullnspath);
            fullcode.setProperty('aliased', 'true');
            isDefault = false;
        };
        
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
g.idx('atoms')[['atom':'Staticproperty']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode.toLowerCase());
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.fullcode.toLowerCase());
    } else {
        isDefault = true;
        if (fullcode.token == 'T_NS_SEPARATOR') {
            fullcodealias = fullcode.out('SUBNAME').has('rank', 0).next().code.toLowerCase();
        } else {
            fullcodealias = fullcode.code.toLowerCase();
        }
        it.out('BLOCK', 'FILE').transform{ if (it.out('ELEMENT').has('atom', 'Php').out('CODE').any()) { it.out('ELEMENT').out('CODE').next(); } else { it }}.out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{alias = it}.filter{it.alias == fullcodealias}.each{
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
g.idx('atoms')[['atom':'Staticconstant']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode.toLowerCase());
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.fullcode.toLowerCase());
    } else {
        isDefault = true;
        if (fullcode.token == 'T_NS_SEPARATOR') {
            fullcodealias = fullcode.out('SUBNAME').has('rank', 0).next().code.toLowerCase();
        } else {
            fullcodealias = fullcode.code.toLowerCase();
        }
        it.out('BLOCK', 'FILE').transform{ if (it.out('ELEMENT').has('atom', 'Php').out('CODE').any()) { it.out('ELEMENT').out('CODE').next(); } else { it }}.out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{alias = it}.filter{it.alias == fullcodealias}.each{
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
g.idx('atoms')[['atom':'Staticclass']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode.toLowerCase());
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.fullcode.toLowerCase());
    } else {
        isDefault = true;
        if (fullcode.token == 'T_NS_SEPARATOR') {
            fullcodealias = fullcode.out('SUBNAME').has('rank', 0).next().code.toLowerCase();
        } else {
            fullcodealias = fullcode.code.toLowerCase();
        }
        it.out('BLOCK', 'FILE').transform{ if (it.out('ELEMENT').has('atom', 'Php').out('CODE').any()) { it.out('ELEMENT').out('CODE').next(); } else { it }}.out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{alias = it}.filter{it.alias == fullcodealias}.each{
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
g.idx('atoms')[['atom':'Instanceof']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode.toLowerCase());
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.code.toLowerCase());
    } else {
        isDefault = true;
        if (fullcode.token == 'T_NS_SEPARATOR') {
            fullcodealias = fullcode.out('SUBNAME').has('rank', 0).next().code.toLowerCase();
        } else {
            fullcodealias = fullcode.code.toLowerCase();
        }
        it.out('BLOCK', 'FILE').transform{ if (it.out('ELEMENT').has('atom', 'Php').out('CODE').any()) { it.out('ELEMENT').out('CODE').next(); } else { it }}.out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{alias = it}.filter{it.alias == fullcodealias}.each{
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
g.idx('atoms')[['atom':'Catch']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode.toLowerCase());
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.fullcode.toLowerCase());
    } else {
        isDefault = true;
        if (fullcode.token == 'T_NS_SEPARATOR') {
            fullcodealias = fullcode.out('SUBNAME').has('rank', 0).next().code.toLowerCase();
        } else {
            fullcodealias = fullcode.code.toLowerCase();
        }
        it.out('BLOCK', 'FILE').transform{ if (it.out('ELEMENT').has('atom', 'Php').out('CODE').any()) { it.out('ELEMENT').out('CODE').next(); } else { it }}.out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{alias = it}.filter{it.alias == fullcodealias}.each{
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
g.idx('atoms')[['atom':'Typehint']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{ 
    if (fullcode.absolutens == 'true') { 
        fullcode.setProperty('fullnspath', fullcode.fullcode.toLowerCase());
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.fullcode.toLowerCase());
    } else {
        isDefault = true;
        if (fullcode.token == 'T_NS_SEPARATOR') {
            fullcodealias = fullcode.out('SUBNAME').has('rank', 0).next().code.toLowerCase();
        } else {
            fullcodealias = fullcode.code.toLowerCase();
        }
        it.out('BLOCK', 'FILE').transform{ if (it.out('ELEMENT').has('atom', 'Php').out('CODE').any()) { it.out('ELEMENT').out('CODE').next(); } else { it }}.out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{alias = it}.filter{it.alias == fullcodealias}.each{
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

g.idx('atoms')[['atom':'New']].out('NEW').filter{ it.atom in ['Identifier', 'Nsname', 'Functioncall']}.sideEffect{fullcode = it;}
                              .filter{it.token in ['T_STRING', 'T_NS_SEPARATOR']}
                              .in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}
                              .each{ 
    
    if (fullcode.token == 'T_STRING') {
        isDefault = true;
        if (fullcode.token == 'T_NS_SEPARATOR') {
            fullcodealias = fullcode.out('SUBNAME').has('rank', 0).next().code.toLowerCase();
        } else {
            fullcodealias = fullcode.code.toLowerCase();
        }
        it.out('BLOCK', 'FILE').transform{ if (it.out('ELEMENT').has('atom', 'Php').out('CODE').any()) { it.out('ELEMENT').out('CODE').next(); } else { it }}.out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{alias = it}.filter{it.alias == fullcodealias}.each{
            fullcode.setProperty('fullnspath', alias.fullnspath);
            fullcode.setProperty('fullnspathsuresure', alias.fullnspath);
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
        fullcode.out('SUBNAME').sort{it.rank}._().each{ 
            s.add(it.getProperty('code')); 
        };

        if (fullcode.absolutens == 'true') { 
            fullcode.setProperty('fullnspath', '\\\\' + s.join('\\\\').toLowerCase());
        } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
            fullcode.setProperty('fullnspath', '\\\\' + s.join('\\\\').toLowerCase());
        } else {
            isDefault = true;
            it.out('BLOCK', 'FILE').transform{ if (it.out('ELEMENT').has('atom', 'Php').out('CODE').any()) { it.out('ELEMENT').out('CODE').next(); } else { it }}.out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{alias = it}.has('alias', s[0]).each{
                s.remove(0);
                fullcode.setProperty('fullnspath', alias.fullnspath  + '\\\\' + s.join('\\\\').toLowerCase());
                fullcode.setProperty('aliased', 'true');
                isDefault = false;
            } ;
        
            if (isDefault) {
                if (it.atom == 'File' || it.fullcode == 'namespace Global') {
                    fullcode.setProperty('fullnspath', '\\\\' + s.join('\\\\').toLowerCase());
                } else {
                    fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + s.join('\\\\').toLowerCase());
                }
            };
        } ;
    };
};

", "
// Constant usage (1)
g.idx('atoms')[['atom':'Identifier']].filter{it.in('USE', 'SUBNAME', 'METHOD', 'CLASS', 'NAME', 'CONSTANT', 'NAMESPACE', 'NEW', 'IMPLEMENTS', 'EXTENDS').count() == 0}
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
g.idx('atoms')[['atom':'Nsname']].filter{it.in('USE', 'SUBNAME', 'METHOD', 'CLASS', 'NAME', 'CONSTANT', 'NAMESPACE', 'NEW', 'IMPLEMENTS', 'EXTENDS').any() == false}
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
g.idx('atoms')[['atom':'Class']].each{ 
    g.idx('classes').put('path', it.fullnspath.toLowerCase(), it)
};

", "
// collecting files
g.idx('atoms')[['atom':'Phpcode']].in.loop(1){true}{it.object.atom == 'File'}.each{ 
    g.idx('files').put('path', it.filename, it)
};

", "
// collecting namespaces
g.idx('atoms')[['atom':'Namespace']].each{ 
    // creating namespace's fullnspath
    it.fullnspath = '\\\\' + it.out('NAMESPACE').next().fullcode;
    g.idx('namespaces').put('path', it.fullnspath.toLowerCase(), it)
};

", "
////// Solving classes Namespaces
// NEW + self, static, parent
g.idx('atoms')[['atom':'Functioncall']]
    .filter{ it.token in ['T_STRING', 'T_STATIC']}
    .filter{ it.in('NEW').any()}
    .filter{ it.code.toLowerCase() in ['parent', 'static', 'self']}
    .each{
        if (it.getProperty('code').toLowerCase() == 'self') { // class de definition
            fullnspath = it.in.loop(1){!(it.object.atom in ['Class', 'Trait'])}{it.object.atom in ['Class', 'Trait', 'File']}.next().fullnspath;
            if (fullnspath == null) { fullnspath = it.code;}
            it.setProperty('fullnspath', fullnspath);
        } else if (it.getProperty('code').toLowerCase() == 'static') { // class courante à l'exécution... 
            fullnspath = it.in.loop(1){!(it.object.atom in ['Class', 'Trait'])}{it.object.atom in ['Class', 'Trait', 'File']}.next().fullnspath;
            if (fullnspath == null) { fullnspath = it.code;}
            it.setProperty('fullnspath', fullnspath);
        } else if (it.getProperty('code').toLowerCase() == 'parent') {
            fullnspath = it.in.loop(1){!(it.object.atom in ['Class', 'Trait'])}{it.object.atom in ['Class', 'Trait', 'File']}.next();
            if (fullnspath.out('EXTENDS').any()) { 
                fullnspath = fullnspath.out('EXTENDS').next().fullnspath;
            } else {
                fullnspath = it.code;
            }
            it.setProperty('fullnspath', fullnspath);
        } 
    }; 

", "
// static method call
g.idx('atoms')[['atom':'Staticmethodcall']]
    .out('CLASS')
    .filter{ it.code.toLowerCase() in ['parent', 'static', 'self']}
    .each{
        if (it.getProperty('code').toLowerCase() == 'self') { // class de definition
            fullnspath = it.in.loop(1){!(it.object.atom in ['Class', 'Trait'])}{it.object.atom in ['Class', 'Trait', 'File']}.next().fullnspath;
            if (fullnspath == null) { fullnspath = it.code;}
            it.setProperty('fullnspath', fullnspath);
        } else if (it.getProperty('code').toLowerCase() == 'static') { // class courante à l'exécution... 
            fullnspath = it.in.loop(1){!(it.object.atom in ['Class', 'Trait'])}{it.object.atom in ['Class', 'Trait', 'File']}.next().fullnspath;
            if (fullnspath == null) { fullnspath = it.code;}
            it.setProperty('fullnspath', fullnspath);
        } else if (it.getProperty('code').toLowerCase() == 'parent') {
            fullnspath = it.in.loop(1){!(it.object.atom in ['Class', 'Trait'])}{it.object.atom in ['Class', 'Trait', 'File']}.next();
            if (fullnspath.out('EXTENDS').any()) { 
                fullnspath = fullnspath.out('EXTENDS').next().fullnspath;
            } else {
                fullnspath = it.code;
            }
            it.setProperty('fullnspath', fullnspath);
        } 
    }; 
", "

// static property
g.idx('atoms')[['atom':'Staticproperty']]
    .out('CLASS')
    .filter{ it.code.toLowerCase() in ['parent', 'static', 'self']}
    .each{
        if (it.getProperty('code').toLowerCase() == 'self') { // class de definition
            fullnspath = it.in.loop(1){!(it.object.atom in ['Class', 'Trait'])}{it.object.atom in ['Class', 'Trait', 'File']}.next().fullnspath;
            if (fullnspath == null) { fullnspath = it.code;}
            it.setProperty('fullnspath', fullnspath);
        } else if (it.getProperty('code').toLowerCase() == 'static') { // class courante à l'exécution... 
            fullnspath = it.in.loop(1){!(it.object.atom in ['Class', 'Trait'])}{it.object.atom in ['Class', 'Trait', 'File']}.next().fullnspath;
            if (fullnspath == null) { fullnspath = it.code;}
            it.setProperty('fullnspath', fullnspath);
        } else if (it.getProperty('code').toLowerCase() == 'parent') {
            fullnspath = it.in.loop(1){!(it.object.atom in ['Class', 'Trait'])}{it.object.atom in ['Class', 'Trait', 'File']}.next();
            if (fullnspath.out('EXTENDS').any()) { 
                fullnspath = fullnspath.out('EXTENDS').next().fullnspath;
            } else {
                fullnspath = it.code;
            }
            it.setProperty('fullnspath', fullnspath);
        } 
    }; 

", "
// static constant
g.idx('atoms')[['atom':'Staticconstant']]
    .out('CLASS')
    .filter{ it.code.toLowerCase() in ['parent', 'static', 'self']}
    .each{
        if (it.getProperty('code').toLowerCase() == 'self') { // class de definition
            fullnspath = it.in.loop(1){!(it.object.atom in ['Class', 'Trait'])}{it.object.atom in ['Class', 'Trait', 'File']}.next().fullnspath;
            if (fullnspath == null) { fullnspath = it.code;}
            it.setProperty('fullnspath', fullnspath);
        } else if (it.getProperty('code').toLowerCase() == 'static') { // class courante à l'exécution... 
            fullnspath = it.in.loop(1){!(it.object.atom in ['Class', 'Trait'])}{it.object.atom in ['Class', 'Trait', 'File']}.next().fullnspath;
            if (fullnspath == null) { fullnspath = it.code;}
            it.setProperty('fullnspath', fullnspath);
        } else if (it.getProperty('code').toLowerCase() == 'parent') {
            fullnspath = it.in.loop(1){!(it.object.atom in ['Class', 'Trait'])}{it.object.atom in ['Class', 'Trait', 'File']}.next();
            if (fullnspath.out('EXTENDS').any()) { 
                fullnspath = fullnspath.out('EXTENDS').next().fullnspath;
            } else {
                fullnspath = it.code;
            }
            it.setProperty('fullnspath', fullnspath);
        } 
    }; 

", "
// special case for isset, unset, array, etc.
g.idx('atoms')[['atom':'Functioncall']]
    .filter{ it.token in ['T_ARRAY', 'T_LIST', 'T_UNSET', 'T_EXIT', 'T_DIE', 'T_ISSET', 'T_ECHO', 'T_PRINT', 'T_EMPTY', 'T_EVAL', 'T_STATIC']}
    .each{
        it.setProperty('fullnspath', '\\\\' + it.code.toLowerCase());
    }; 

", "
// local class in its namespace
g.idx('atoms')[['atom':'New']]
    .out('NEW')
    .has('token', 'T_STRING')
    .filter{ uses = []; node = it; it.in.loop(1){true}{it.object.atom == 'File'}.out('NAMESPACE')
                                     .filter{path = '\\\\' + it.fullcode + '\\\\' + node.code; g.idx('classes')[['path':path.toLowerCase()]].any(); }.any();
            }
    .each{
        node.setProperty('fullnspath', path.toLowerCase());
    }; 

g.idx('atoms')[['atom':'Interface']].each{ 
    g.idx('interfaces').put('path', it.fullnspath.toLowerCase(), it)
};

g.idx('atoms')[['atom':'Trait']].each{ 
    g.idx('traits').put('path', it.fullnspath.toLowerCase(), it)
};

","
// apply use statement to all structures
g.idx('atoms')[['atom':'Use']].out('USE').each{ 
    alias = it.alias.toLowerCase();
    fullnspath = it.fullnspath.toLowerCase();

    it.in('USE').in('ELEMENT').out().loop(1){true}{ it.object.fullnspath != null && it.object.atom != 'Use'}.each{ 
        if (alias == it.code.toLowerCase()) {
            it.setProperty('fullnspath', fullnspath);
        } 
    }
};
",
//"g.dropIndex('racines');",
// if there is an error while processing the AST, racines is needed.
// we need some conditional here.
"g.dropIndex('delete');"

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
    
    public static function getInstance($name, $client, $phpVersion = 'Any') {
        if ($analyzer = Token::getClass($name)) {
            $analyzer = new $analyzer($client);
            if ($analyzer->checkPhpVersion($phpVersion)) {
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
        if ($this->phpVersion == 'Any') {
            return true;
        }

        // version and above 
        if ((substr($this->phpVersion, -1) == '+') && version_compare($version, $this->phpVersion) >= 0) {
            return true;
        } 

        // up to version  
        if ((substr($this->phpVersion, -1) == '-') && version_compare($version, $this->phpVersion) <= 0) {
            return true;
        } 

        // version range 1.2.3-4.5.6
        if (strpos($this->phpVersion, '-') !== false) {
            list($lower, $upper) = explode('-', $this->phpVersion);
            if (version_compare($version, $lower) >= 0 && version_compare($version, $upper) <= 0) {
                return true;
            } else {
                return false;
            }
        } 
        
        // One version only
        if (version_compare($version, $this->phpVersion) == 0) {
            return true;
        } 
        
        // Default behavior if we don't understand : 
        return false;
    }
}

?>
