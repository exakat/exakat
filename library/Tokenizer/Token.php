<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/


namespace Tokenizer;

class Token {
    protected static $reserved = array();
    
    // the numeric indices are NOT important for processing order
    // the order of this array is important for processing and optimization
    protected static $types = array ( 0  => 'Variable',
                                      2  => 'VariableDollar',
                                      3  => 'Boolean',
                                      4  => 'Sign',
                                      19 => '_Break',
                                      21 => 'Label',
                                      22 => '_Goto',
                                      5  => '_Array',
                                      24 => '_Global',
                                      6  => '_String',
                                      59 => 'Shell',
                                      7  => '_Arraydollarcurly',
                                      8  => 'Property',
                                      80 => 'Phpcodemiddle',
                                      31 => 'Postplusplus',
                                      30 => 'Preplusplus',
                                      9  => 'Keyvalue',
                                      62 => '_Abstract',
                                      50 => '_Static',
                                      85 => '_Final',
                                      10 => '_Function',
                                      48 => '_Var',
                                      49 => '_Ppp',
                                      16 => 'Reference',
                                      12 => 'Logical',
                                      92 => 'Spaceship',
                                      93 => 'Coalesce',
                                      13 => 'Heredoc',
                                      14 => 'Not',
                                      15 => 'Cast',
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
                                      64 => 'Typehint',
                                      35 => 'Arguments',
                                      36 => 'ArgumentsNoParenthesis',
                                      37 => 'ArgumentsNoComma',
                                      33 => 'Parenthesis',
                                      1  => 'Halt',
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
                                      65 => '_Throw',
                                      66 => '_Case',
                                      67 => '_Default',
                                      68 => '_Switch',
                                      69 => 'Ifthen',
                                      71 => '_Foreach',
                                      72 => '_For',
                                      73 => '_While',
                                      74 => '_Dowhile',
                                      75 => '_Catch',
                                      88 => '_Finally',
                                      89 => '_Yield',
                                      94 => '_Yieldfrom',
                                      76 => '_Try',
                                      77 => 'Sequence',
                                      81 => 'Phpcode',
                                    );

    protected static $alternativeEnding = array('T_ENDFOR',
                                                'T_ENDSWITCH',
                                                'T_ENDFOREACH',
                                                'T_ENDWHILE',
                                                'T_ENDIF',
                                                'T_ENDDECLARE');
    protected $phpVersion = 'Any';
    protected static $phpExecVersion = PHP_VERSION;

    static public $instructionEnding = array();
    
    public function __construct() {
        
        self::$instructionEnding = array_merge(Preplusplus::$operators,
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
                                               Ternary::$operators,
                                               array('T_OPEN_BRACKET', 'T_OPEN_PARENTHESIS')); //'T_ELSE', ,  'T_ELSEIF'

        $config = \Config::factory();
        self::$phpExecVersion = $config->phpversion;
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
        if (empty($this->queries)) {
            $this->_check();
        }
        
        $this->execQueries();
    }
    
    public function reserve() {
        return true;
    }

    public function resetReserve() {
        Token::$reserved = array();
    }

    static public function countTotalToken() {
        return gremlin_queryOne('g.V.count()');
    }

    static public function countLeftToken() {
        return gremlin_queryOne("g.idx('racines')[['token':'ROOT']].out('NEXT').loop(1){it.object.token != 'T_END'}{true}.count()");
    }

    static public function countLeftNext() {
        return 1 + gremlin_queryOne("g.idx('racines')[['token':'ROOT']].out('INDEXED').out('NEXT').loop(1){it.object.token != 'T_END'}{true}.count()");
    }

    static public function countNextEdge() {
        return gremlin_queryOne("g.E.has('label','NEXT').count()");
    }

    static public function query($query) {
        $res = gremlin_query($query);
        $res = (array) $res->results;
        
        return $res;
    }

    public function checkRemaining() {
        $class = str_replace("Tokenizer\\", '', get_class($this));
        if (in_array($class, array('Staticconstant','Staticmethodcall','Staticproperty'))) {
            $query = "g.idx('racines')[['token':'Staticproperty']].out('INDEXED').any()";
            return gremlin_queryOne($query);
        } elseif (in_array($class, array('Property','Methodcall'))) {
            $query = "g.idx('racines')[['token':'Property']].out('INDEXED').any()";
            return gremlin_queryOne($query);
        } elseif (in_array($class, Token::$types)) {
            $query = "g.idx('racines')[['token':'$class']].out('INDEXED').any()";
            return gremlin_queryOne($query);
        } else {
            return true;
        }
    }

    static public function leftInIndex($class) {
        return gremlin_queryOne("g.idx('racines')[['token':'$class']].out('INDEXED').count()");
    }

    static public function countFileToProcess() {
        return gremlin_queryOne("g.idx('racines')[['token':'ROOT']].out('INDEXED').count()");
    }

    
    static public function cleanHidden() {
        $solvingClassNames = <<<GREMLIN
    if (fullcode.absolutens == true) {
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

        it.out('BLOCK', 'FILE').transform{ if (it.out('ELEMENT').has('atom', 'Php').out('CODE').any()) { it.out('ELEMENT').next(); } else { it }}.out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{alias = it}.filter{it.alias == fullcodealias}.each{
            if (fullcode.token == 'T_NS_SEPARATOR') {
                fullcode.setProperty('fullnspath', alias.fullnspath + '\\\\' + fullcode.out('SUBNAME').has('rank', 1).next().code.toLowerCase());
            } else {
                fullcode.setProperty('fullnspath', alias.fullnspath);
            }
            fullcode.setProperty('aliased', true);
            fullcode.setProperty('alias', fullcodealias);
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
GREMLIN;
    
        $queries = array( "

// cleans root token
g.idx('racines')[['token':'ROOT']].out('INDEXED').as('root').out('NEXT').hasNot('atom',null).out('NEXT').has('token', 'T_END').each{
    g.removeVertex(it.in('NEXT').in('NEXT').next());
    g.removeVertex(it.out('NEXT').next());
    g.removeVertex(it);
};

g.V.has('root', true)[0].inE('INDEXED').each{
    g.removeEdge(it);
};

// clean indexed (if no more index...)
g.V.has('index', true).filter{it.out().count() == 0}.each{
    g.removeVertex(it);
};

", "
//////////////////////////////////////////////////////////////////////////////////////////
// calculating the full namespaces paths
//////////////////////////////////////////////////////////////////////////////////////////
// const without class nor namspace (aka, global)

g.idx('atoms')[['atom':'Const']].filter{it.in('ELEMENT').in('BLOCK').filter{ it.atom in ['Trait', 'Class'] }.any() == false}.out('CONST').out('LEFT')
    .sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{
    
    if (it.atom == 'File') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.fullcode.toLowerCase());
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.fullcode.toLowerCase());
    }
    g.idx('constants').put('path', fullcode.fullnspath, it)
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
    .in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}
    .each{
    namespace = it;
    if (namespace.atom == 'File' || namespace.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.code.toLowerCase());
    } else {
        fullcode.setProperty('fullnspath', '\\\\' + namespace.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.code.toLowerCase());
    }

    g.idx('functions').put('path', fullcode.fullnspath.toLowerCase(), fullcode);
};
", "
// use  usage inside Trait or class
g.idx('atoms')[['atom':'Use']].filter{ it.in('ELEMENT').in('BLOCK').filter{ it.atom in ['Trait', 'Class']}.any() }
    .sideEffect{theUse = it;}.out('USE').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{
    if (fullcode.absolutens == true) {
        fullcode.setProperty('fullnspath', fullcode.originpath.toLowerCase());
    } else if (theUse.groupedUse == true) {
        fullcode.setProperty('fullnspath', theUse.fullnsprefix + fullcode.originpath.toLowerCase());
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + fullcode.originpath.toLowerCase());
    } else {
        fullcode.setProperty('fullnspath',  '\\\\' + it.out('NAMESPACE').next().code.toLowerCase() + '\\\\' +  fullcode.originpath.toLowerCase());
    }
};

", "
// use  usage in a namespace
g.idx('atoms')[['atom':'Use']].filter{ it.in('ELEMENT').in('BLOCK').filter{ it.atom in ['Trait', 'Class']}.any() == false}
.sideEffect{theUse = it;}.out('USE').each{
    fullcode = it;
    if (fullcode.absolutens == true) {
        fullcode.setProperty('fullnspath', fullcode.originpath.toLowerCase());
    } else if (fullcode.out('NAME').any() && fullcode.out('NAME').next().absolutens == true) {
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
    if (fullcode.absolutens == true) {
        fullcode.setProperty('fullnspath', fullcode.fullcode.toLowerCase());
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
            fullcode.setProperty('aliased', true);
            fullcode.setProperty('alias', fullcodealias);
            isDefault = false;
        };
        
        if (isDefault) {
            if (it.atom == 'File' || it.fullcode == 'namespace Global') {
                fullcode.setProperty('fullnspath', '\\\\' + fullcode.fullcode.toLowerCase());
            } else {
                fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + fullcode.fullcode.toLowerCase());
            }
        };
    }
};
", "

g.idx('atoms')[['atom':'Interface']].out('IMPLEMENTS', 'EXTENDS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{
    if (fullcode.absolutens == true) {
        fullcode.setProperty('fullnspath', fullcode.fullcode.toLowerCase());
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
            fullcode.setProperty('aliased', true);
            fullcode.setProperty('alias', fullcodealias);
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
    if (fullcode.absolutens == true) {
        fullcode.setProperty('fullnspath', fullcode.fullcode.toLowerCase());
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
            fullcode.setProperty('aliased', true);
            fullcode.setProperty('alias', fullcodealias);
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

// case for [1,2,3] : all are \array
g.idx('atoms')[['atom':'Functioncall']].has('token', 'T_OPEN_BRACKET').each{
    it.setProperty('fullnspath', '\\\\array');
};

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
    
    if (it.atom == 'Namespace') {
        npath = '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + s;
    } else {
        npath = '\\\\' + s;
    }
    if (fullcode.absolutens == true) {
        fullcode.setProperty('fullnspath', '\\\\' + s);
    } else if (it.atom == 'File' || it.fullcode == 'namespace Global') {
        fullcode.setProperty('fullnspath', '\\\\' + s);
    } else if ( g.idx('functions')[['path':npath]].any() ) {
        fullcode.setProperty('fullnspath', npath);
    } else {
        // if we don't find it defined, we rely on the global namespace
        fullcode.setProperty('fullnspath', '\\\\' + s);
    }
};
", "

// special case for isset, unset, array, etc. Except for static.
g.idx('atoms')[['atom':'Functioncall']]
    .filter{ it.token in ['T_ARRAY', 'T_LIST', 'T_UNSET', 'T_EXIT', 'T_DIE', 'T_ISSET', 'T_ECHO', 'T_PRINT', 'T_EMPTY', 'T_EVAL']}
    .each{
        it.setProperty('fullnspath', '\\\\' + it.code.toLowerCase());
    };

", "

// class usage
g.idx('atoms')[['atom':'Staticmethodcall']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{
    $solvingClassNames
};

", "
g.idx('atoms')[['atom':'Staticproperty']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{
    $solvingClassNames
};

", "
g.idx('atoms')[['atom':'Staticconstant']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{
    $solvingClassNames
};

", "
g.idx('atoms')[['atom':'Staticclass']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{
    $solvingClassNames
};

", "
g.idx('atoms')[['atom':'Instanceof']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{
    $solvingClassNames
};
", "
g.idx('atoms')[['atom':'Catch']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{
    $solvingClassNames};

", "
g.idx('atoms')[['atom':'Typehint']].out('CLASS').sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{
    $solvingClassNames
};

", "
// Solving fullnspath for New
g.idx('atoms')[['atom':'New']].out('NEW').filter{ it.atom in ['Identifier', 'Nsname', 'Functioncall']}.sideEffect{fullcode = it;}
                              .filter{it.token in ['T_STRING', 'T_NS_SEPARATOR']}
                              .in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}
                              .each{
    
    if (fullcode.token == 'T_STRING') {
        fullcodealias = fullcode.code.toLowerCase();
        isDefault = true;
        it.out('BLOCK', 'FILE').transform{ if (it.out('ELEMENT').has('atom', 'Php').out('CODE').any()) { it.out('ELEMENT').out('CODE').next(); } else { it; }}.out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{alias = it}.filter{it.alias == fullcodealias}.each{
            fullcode.setProperty('fullnspath', alias.fullnspath);
            fullcode.setProperty('aliased', true);
            fullcode.setProperty('alias', fullcodealias);
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
        fullcodealias = s[0].toLowerCase();
        fullcode.setProperty('fullcodealias', fullcodealias);

        if (fullcode.absolutens == true) {
            fullcode.setProperty('fullnspath', '\\\\' + s.join('\\\\').toLowerCase());
        } else {
            isDefault = true;
            it.out('BLOCK', 'FILE').transform{ if (it.out('ELEMENT').has('atom', 'Php').out('CODE').any()) { it.out('ELEMENT').out('CODE').next(); } else { it; }}.out('ELEMENT').has('atom', 'Use').out('USE').sideEffect{alias = it}.filter{it.alias == fullcodealias}.each{
                fullcode.setProperty('fullnspath', alias.fullnspath + '\\\\' + fullcode.out('SUBNAME').has('rank', 1).next().code.toLowerCase());
                fullcode.setProperty('aliased', true);
                fullcode.setProperty('noDefault', true);
                fullcode.setProperty('alias', fullcodealias);
                isDefault = false;
            }
        
            if (isDefault) {
                if (it.atom == 'File' || it.fullcode == 'namespace Global') {
                    fullcode.setProperty('fullnspath', '\\\\' + s.join('\\\\').toLowerCase());
                    fullcode.setProperty('default File', true);
                } else {
                    fullcode.setProperty('fullnspath', '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase() + '\\\\' + s.join('\\\\').toLowerCase());
                    fullcode.setProperty('default File else', true);
                }
            };
        } ;
    };
};

", "
// Constant usage (simple resolution of the namespaces)
g.idx('atoms')[['atom':'Identifier']].filter{it.in('USE', 'SUBNAME', 'METHOD', 'CLASS', 'NAME', 'CONSTANT', 'NAMESPACE', 'NEW', 'IMPLEMENTS', 'EXTENDS').count() == 0}
    .filter{it.out('ARGUMENTS').any() == false}
    .filter{it.in('LEFT').in('CONST').any() == false}
    .sideEffect{fullcode = it;}.in.loop(1){!(it.object.atom in ['Namespace', 'File'])}{it.object.atom in ['Namespace', 'File']}.each{

    if (fullcode.absolutens == true) {
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
        if (fullcode.absolutens == true) {
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
    it.fullnspath = '\\\\' + it.out('NAMESPACE').next().fullcode.toLowerCase();
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
            it.setProperty('aliased', true);
        }
    }
};
",
"// Build the classes hierarchy

g.idx('atoms')[['atom':'Class']]
.sideEffect{
    s = [];
    s.add(it.fullnspath);
    it.as('a').out('EXTENDS')
      .sideEffect{ s.add(it.fullnspath); }
      .transform{ g.idx('classes')[['path':it.fullnspath]].next(); }
      // it.loops is arbitrary : avoid circular reference loop.
      .loop('a'){it.object.out('EXTENDS').any() && it.loops < 10}.iterate();
      true;
}
.each{
    it.setProperty('classTree', s);
};

"

);

        $begin = microtime(true);
        foreach($queries as $query) {
            // @todo make this //
            $res = gremlin_query($query);
        }
        $end = microtime(true);
        display('CleanHidden : '.number_format(1000 * ($end - $begin), 0)."ms\n");
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
        gremlin_query($query);
    }

    public static function getClass($class) {
        if (class_exists($class)) {
            return $class;
        } else {
            return false;
        }
    }
    
    public static function getInstance($name, $phpVersion = 'Any') {
        if ($analyzer = Token::getClass($name)) {
            $analyzer = new $analyzer();
            if ($analyzer->checkPhpVersion($phpVersion)) {
                return $analyzer;
            } else {
                return null;
            }
        } else {
            throw new \Exceptions\NoSuchTokenizer($name);
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

    public function getPhpversion() {
        return $this->phpVersion;
    }

}

?>
