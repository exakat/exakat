<?php

namespace Tokenizer;

class Arguments extends TokenAuto {
    static public $operators = array('T_COMMA');

    static public $operands_wa = array('Addition', 'Multiplication', 'Sequence', 'String', 'Identifier', 
                                       'Integer', 'Float', 'Not', 'Variable', 'Array', 'Concatenation', 'Sign',
                                       'Functioncall', 'Boolean', 'Comparison', 'Parenthesis', 'Constant', 'Array',
                                       'Magicconstant', 'Ternary', 'Assignation', 'Logical', 'Keyvalue', 'Void', 
                                       'Property', 'Staticconstant', 'Staticproperty', 'Nsname', 'Methodcall', 'Staticmethodcall',
                                       'Reference', 'Cast', 'Postplusplus', 'Preplusplus', 'Typehint', 'Bitshift', 'Noscream',
                                       'Clone', 'New', 'Arrayappend', 'Include', 'Instanceof', 'Function', 'Keyvalue', 
                                       'ArrayNS', 'Shell', 'Heredoc', );

    function _check() {
        $operands = Arguments::$operands_wa;
        $operands[] = 'Arguments';
        
        // @note arguments separated by ,
        $this->conditions = array(-2 => array('token' => array_merge(array('T_COMMA', 'T_OPEN_PARENTHESIS', 'T_OPEN_BRACKET', 'T_ECHO', 
                                                                           'T_GLOBAL', 'T_USE', 'T_IMPLEMENTS', 'T_EXTENDS', 'T_VAR', 
                                                                           'T_SEMICOLON', 'T_STATIC', 'T_DECLARE', 'T_CONST' ), 
                                                                     _Ppp::$operators)),
                                  -1 => array('atom' => $operands),
                                   0 => array('token' => Arguments::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => $operands),
                                   2 => array('token' => array('T_COMMA', 'T_CLOSE_PARENTHESIS', 'T_CLOSE_BRACKET', 'T_SEMICOLON', 
                                                               )),
                                                               //'T_OPEN_CURLY',  open curly are capture in another regex. One must avoic $a, $c{d} that let {d} alone.
                                 );
        
        $this->actions = array('makeEdge'    => array( 1 => 'ARGUMENT',
                                                      -1 => 'ARGUMENT'
                                                      ),
                               'order'       => array( 1 => '2',
                                                      -1 => '1'),
                               'mergeNext'   => array('Arguments' => 'ARGUMENT'), 
                               'atom'        => 'Arguments',
                               'cleanIndex' => true
                               );
        $this->checkAuto();

        // @note arguments separated by , (interface), ending on a {
        $this->conditions = array(-1 => array('atom' => array('Identifier', 'Arguments', 'Nsname') ),
                                   0 => array('token' => Arguments::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => array('Identifier', 'Nsname')),
                                   2 => array('token' => 'T_OPEN_CURLY')
                                 );
        
        $this->actions = array('makeEdge'    => array( 1 => 'ARGUMENT',
                                                      -1 => 'ARGUMENT'
                                                      ),
                               'order'       => array( 1 => '2',
                                                      -1 => '1'),
                               'mergeNext'   => array('Arguments' => 'ARGUMENT'), 
                               'atom'        => 'Arguments',
                               'cleanIndex' => true
                               );
        $this->checkAuto();

        // @note End of )
        $this->conditions = array(-2 => array('filterOut' => array("T_NS_SEPARATOR")),
                                  -1 => array('atom' => $operands),
                                   0 => array('token' => Arguments::$operators,
                                              'atom' => 'none'),
                                   1 => array('token' => 'T_CLOSE_PARENTHESIS',
                                              'atom'  => 'none'),
        );
        
        $this->actions = array('makeEdge'    => array(-1 => 'ARGUMENT'
                                                      ),
                               'order'    => array('-1' => '1'),
                               'atom'       => 'Arguments',
                               );
        $this->checkAuto();

        return $this->checkRemaining();
    }

    function fullcode() {
        return 's = []; it.out("ARGUMENT").each{ s.add(it.fullcode); } ;it.fullcode = "(" + s.join(", ") + " )";';
    }
}
?>