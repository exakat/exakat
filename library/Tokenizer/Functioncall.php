<?php

namespace Tokenizer;

class Functioncall extends TokenAuto {
    static $operators =              array('T_VARIABLE', 'T_STRING', 'T_UNSET', 'T_EMPTY', 'T_ARRAY', 
                                           'T_NS_SEPARATOR', 'T_PRINT', 'T_ISSET', 'T_LIST', 'T_EVAL', 
                                           'T_EXIT', 'T_DIE', 'T_STATIC', 'T_ECHO', );
    static $operators_without_echo = array('T_VARIABLE', 'T_STRING', 'T_UNSET', 'T_EMPTY', 'T_ARRAY', 
                                           'T_NS_SEPARATOR', 'T_PRINT', 'T_ISSET', 'T_LIST', 'T_EVAL', 
                                           'T_EXIT', 'T_DIE', 'T_STATIC', 'T_HALT_COMPILER');       
    static public $atom = 'Functioncall';

    public function _check() {
        // $functioncall(with arguments or void) with a variable as name
        $this->conditions = array(  -2 => array('filterOut' => array('T_FUNCTION')),
                                    -1 => array('filterOut' => array('T_FUNCTION', 'T_NS_SEPARATOR')),
                                     0 => array('token' => 'T_VARIABLE'),
                                     1 => array('atom'  => 'none',
                                                'token' => 'T_OPEN_PARENTHESIS' ),
                                     2 => array('atom'  =>  array('Arguments', 'Void')),
                                     3 => array('atom'  => 'none',
                                                'token' => 'T_CLOSE_PARENTHESIS')
        );
        
        $this->actions = array('variable_to_functioncall'   => 1,
                               'keepIndexed'                => true,
                               'property'                   => array('parenthesis' => 'false'),
                               );
        $this->checkAuto();
        
        // functioncall(with arguments or void) that will be in a sequence
        $this->conditions = array(  -2 => array('filterOut' => array('T_FUNCTION')),
                                    -1 => array('filterOut' => array('T_FUNCTION', 'T_NS_SEPARATOR')),
                                     0 => array('token' => Functioncall::$operators_without_echo),
                                     1 => array('atom'  => 'none',
                                                'token' => 'T_OPEN_PARENTHESIS' ),
                                     2 => array('atom'  =>  array('Arguments', 'Void')),
                                     3 => array('atom'  => 'none',
                                                'token' => 'T_CLOSE_PARENTHESIS')
        );
        
        $this->actions = array('makeEdge'     => array(2 => 'ARGUMENTS'),
                               'dropNext'     => array(1),
                               'atom'         => 'Functioncall',
                               'makeSequence' => 'it',
                               'property'     => array('parenthesis' => 'true'),
                               );
        $this->checkAuto();

        // functioncall special case for Echo
        $this->conditions = array(  -2 => array('filterOut' => array('T_FUNCTION')),
                                    -1 => array('filterOut' => array('T_FUNCTION', 'T_NS_SEPARATOR')),
                                     0 => array('token' => 'T_ECHO'),
                                     1 => array('atom'  => 'none',
                                                'token' => 'T_OPEN_PARENTHESIS' ),
                                     2 => array('atom'  =>  array('Arguments', 'Void')),
                                     3 => array('atom'  => 'none',
                                                'token' => 'T_CLOSE_PARENTHESIS'),
                                     4 => array('filterOut2' => array('T_COMMA', 'T_QUESTION', 'T_COLON')),
        );
        
        $this->actions = array('makeEdge'     => array(2 => 'ARGUMENTS'),
                               'dropNext'     => array(1),
                               'atom'         => 'Functioncall',
                               'makeSequence' => 'it',
                               'property'     => array('parenthesis' => 'true'),
                               );
        $this->checkAuto();

        // functioncall(with arguments but without parenthesis)
        $this->conditions = array(-1 => array('filterOut' => _Ppp::$operators),
                                   0 => array('token' => array('T_ECHO', 'T_PRINT', 'T_EXIT', ),
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'Arguments'),
                                   2 => array('filterOut2' => array_merge( array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_COMMA', 'T_QUESTION'),
                                                                           Addition::$operators, Multiplication::$operators, 
                                                                           Bitshift::$operators, Logical::$operators, array('T_COMMA'))),
        );
        
        $this->actions = array('makeEdge'     => array('1' => 'ARGUMENTS'),
                               'atom'         => 'Functioncall',
                               'makeSequence' => 'it',
                               'property'     => array('parenthesis' => 'false'),
                               );
        $this->checkAuto();

        // special case for new static with arguments or void
        $this->conditions = array(-1 => array('token' => 'T_NEW'),
                                   0 => array('token' => 'T_STATIC',
                                              'atom'  => 'none'),
                                   1 => array('atom'  => array('Arguments', 'Void')), // actually, T_VOID
                                   2 => array('token' => array('T_SEMICOLON', 'T_COMMA', 'T_CLOSE_BRACKET', 'T_CLOSE_PARENTHESIS')),
        );
        
        $this->actions = array('makeEdge'     => array('1' => 'ARGUMENTS'),
                               'atom'         => 'Functioncall',
                               'makeSequence' => 'it'
                               );
        $this->checkAuto();

        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN

if (fullcode.getProperty('token') == 'T_NS_SEPARATOR') {
    s = []; 
    fullcode.out("SUBNAME").sort{it.order}._().each{ s.add(it.fullcode); };

    if (fullcode.absolutens == 'true') {
        fullcode.setProperty('fullcode', "\\\\" + s.join("\\\\"));
        fullcode.setProperty('code', "\\\\" + s.join("\\\\"));
    } else {
        fullcode.setProperty('fullcode', s.join("\\\\"));
        fullcode.setProperty('code', s.join("\\\\"));
    }
} else {
    fullcode.setProperty('fullcode', it.getProperty('code'));
}

if (fullcode.getProperty('parenthesis') == 'true') {
    fullcode.setProperty('fullcode', it.getProperty('fullcode') + "(" + it.out("ARGUMENTS").next().getProperty('fullcode') + ")");
} else {
    fullcode.setProperty('fullcode', it.getProperty('fullcode') + " " + it.out("ARGUMENTS").next().getProperty('fullcode') + "");
}

// count the number of arguments
// filter out void ? 
fullcode.setProperty("count", fullcode.out("ARGUMENTS").out("ARGUMENT").count()); 

GREMLIN;
    }

}
?>