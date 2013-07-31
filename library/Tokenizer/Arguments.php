<?php

namespace Tokenizer;

class Arguments extends TokenAuto {
    static public $operators = array('T_OPEN_PARENTHESIS', 'T_COMMA');

    function _check() {
        
        // @note End of )
        $this->conditions = array( 0 => array('token' => 'T_COMMA',
                                              'atom' => 'none'),
                                   1 => array('token' => 'T_COMMA',
                                              'atom'  => 'none'),
        );
        $this->actions = array('addEdge'   => array(0 => array('Void' => 'ARGUMENT')));
        $this->checkAuto();

        $this->conditions = array( 0 => array('token' => 'T_COMMA',
                                              'atom' => 'none'),
                                   1 => array('token' => 'T_CLOSE_PARENTHESIS',
                                              'atom'  => 'none'),
        );
        $this->actions = array('addEdge'   => array(0 => array('Void' => 'ARGUMENT')));
        $this->checkAuto();

        $this->conditions = array( 0 => array('token' => 'T_OPEN_PARENTHESIS',
                                              'atom' => 'none'),
                                   1 => array('token' => 'T_COMMA',
                                              'atom'  => 'none'),
        );
        $this->actions = array('addEdge'   => array(0 => array('Void' => 'ARGUMENT')));
        $this->checkAuto();

        $operands_wa = array('Addition', 'Multiplication', 'Sequence', 'String', 
                             'Integer', 'Float', 'Not', 'Variable','Array','Concatenation', 'Sign',
                             'Functioncall', 'Boolean', 'Comparison', 'Parenthesis', 'Constant', 'Array',
                             'Magicconstant', 'Ternary', 'Assignation', 'Logical', 'Keyvalue', 'Void', 
                             'Property', 'Staticconstant', 'Staticproperty', 'Nsname', 'Methodcall', 'Staticmethodcall',
                             'Reference', 'Cast', 'Postplusplus', 'Preplusplus', 'Typehint', 'Bitshift', 'Noscream', );
        $operands = $operands_wa;
        $operands[] = 'Arguments';
        
        // @note arguments separated by ,
        $this->conditions = array(-2 => array('filterOut2' => array_merge(array('T_DOT', 'T_AT', 'T_NOT', 'T_EQUAL', 'T_MINUS', 'T_PLUS','T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_NS_SEPARATOR', 'T_STRING'),
                                                                         Comparison::$operators, Addition::$operators, Multiplication::$operators) ),
                                  -1 => array('atom' => $operands ),
                                   0 => array('token' => 'T_COMMA',
                                              'atom' => 'none'),
                                   1 => array('atom' => $operands),
                                   2 => array('filterOut2' => array_merge(array('T_DOT', 'T_AT', 'T_NOT', 'T_EQUAL', 'T_MINUS', 'T_PLUS','T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_NS_SEPARATOR','T_OPEN_PARENTHESIS', 'T_OPEN_BRACKET', 'T_OPEN_CURLY', 'T_VARIABLE',),
                                                                         Comparison::$operators, Addition::$operators, Multiplication::$operators) ),
                            );
        
        $this->actions = array('makeEdge'    => array( 1 => 'ARGUMENT',
                                                      -1 => 'ARGUMENT'
                                                      ),
                               'order'    => array( 1 => '2',
                                                   -1 => '1'),
                               'mergeNext'  => array('Arguments' => 'ARGUMENT'), 
                               'atom'       => 'Arguments',
                               );
        $this->checkAuto();

        // @note implements a,b (two only)
        $this->conditions = array(-2 => array('token' => 'T_IMPLEMENTS' ),
                                  -1 => array('token' => 'T_STRING'),
                                   0 => array('token' => 'T_COMMA',
                                              'atom' => 'none'),
                                   1 => array('token' => 'T_STRING')
                            );
        
        $this->actions = array('makeEdge'    => array( 1 => 'ARGUMENT',
                                                      -1 => 'ARGUMENT'
                                                      ),
                               'order'    => array( 1 => '2',
                                                   -1 => '1'),
                               'mergeNext'  => array('Arguments' => 'ARGUMENT'), 
                               'atom'       => 'Arguments',
                               );
        $this->checkAuto();

        // @note implements a,b,c (three or more)
        $this->conditions = array(-2 => array('token' => 'T_IMPLEMENTS' ),
                                  -1 => array('atom' => 'Arguments'),
                                   0 => array('token' => 'T_COMMA',
                                              'atom' => 'none'),
                                   1 => array('token' => 'T_STRING')
                            );
        
        $this->actions = array('makeEdge'    => array( 1 => 'ARGUMENT',
                                                      -1 => 'ARGUMENT'
                                                      ),
                               'order'    => array( 1 => '2',
                                                   -1 => '1'),
                               'mergeNext'  => array('Arguments' => 'ARGUMENT'), 
                               'atom'       => 'Arguments',
                               );
        $this->checkAuto();

        // @note End of )
        $this->conditions = array(-2 => array('filterOut' => array("T_NS_SEPARATOR")),
                                  -1 => array('atom' => $operands),
                                   0 => array('token' => 'T_COMMA',
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
        
        // @note f(1) : no , 
        $this->conditions = array(-1 => array('token' => Functioncall::$operators),
                                   0 => array('token' => 'T_OPEN_PARENTHESIS',
                                              'atom' => 'none'),
                                   1 => array('atom' => $operands_wa),
                                   2 => array('token' => 'T_CLOSE_PARENTHESIS',
                                              'atom'  => 'none'),
                                   3 => array('filterOut' => array('T_DOUBLECOLON', 'T_OPEN_PARENTHESIS')),
        );
        
        $this->actions = array('insertEdge'   => array(0 => array('Arguments' => 'ARGUMENT')));
        $this->checkAuto();

        // @note f() : no argument
        $this->conditions = array(-2 => array('filterOut' => array('T_NS_SEPARATOR')),
                                  -1 => array('token' => array('T_STRING', 'T_ECHO', 'T_UNSET', 'T_EVAL', 'T_PRINT', 'T_ARRAY', 'T_VARIABLE', 'T_NS_SEPARATOR')),
                                   0 => array('token' => 'T_OPEN_PARENTHESIS',
                                              'atom' => 'none'),
                                   1 => array('token' => 'T_CLOSE_PARENTHESIS',
                                              'atom'  => 'none'),
                                   2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLECOLON')),
        );
        
        $this->actions = array('addEdge'   => array(0 => array('Arguments' => 'ARGUMENT')));
        $this->checkAuto();

        return $this->checkRemaining();
    }
}
?>