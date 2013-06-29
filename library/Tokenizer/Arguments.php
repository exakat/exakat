<?php

namespace Tokenizer;

class Arguments extends TokenAuto {
    function _check() {
        
        // @note End of )
        $this->conditions = array( 0 => array('token' => array('T_OPEN_PARENTHESIS', 'T_COMMA'),
                                             'atom' => 'none'),
                                   1 => array('token' => array('T_CLOSE_PARENTHESIS', 'T_COMMA'),
                                              'atom'  => 'none'),
        );
        
        $this->actions = array('addEdge'   => array(0 => array('Void' => 'ARGUMENT')));
        $r = $this->checkAuto();

        $operands_wa = array('Addition', 'Multiplication', 'Sequence', 'String', 
                             'Integer', 'Float', 'Not', 'Variable','_Array','Concatenation', 'Sign',
                             'Functioncall', 'Boolean', 'Comparison', 'Parenthesis', 'Constant', 'Array',
                             'Magicconstant', 'Ternary', 'Assignation', 'Logical', 'Keyvalue', 'Void', 
                             'Property', 'Staticconstant', 'Staticproperty', 'Nsname', 'Methodcall', 'Staticmethodcall' );
        $operands = $operands_wa;
        $operands[] = 'Arguments';
        
        // @note arguments separated by ,
        $this->conditions = array(-2 => array('filterOut' => array_merge(array('T_DOT', 'T_AT', 'T_NOT', 'T_EQUAL', 'T_MINUS', 'T_PLUS','T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_NS_SEPARATOR',),
                                                                         Comparison::$operators) ),
                                  -1 => array('atom' => $operands ),
                                   0 => array('code' => ',',
                                              'atom' => 'none'),
                                   1 => array('atom' => $operands),
                                   2 => array('filterOut2' => array('T_OPEN_PARENTHESIS', 'T_EQUAL', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_DOT', 'T_QUOTE', 'T_NS_SEPARATOR') ),
                            );
        
        $this->actions = array('makeEdge'    => array( 1 => 'ARGUMENT',
                                                      -1 => 'ARGUMENT'
                                                      ),
                               'order'    => array('1'  => '2',
                                                   '-1' => '1'
                                                      ),
                               'mergeNext'  => array('Arguments' => 'ARGUMENT'), 
                               'atom'       => 'Arguments',
                               );
        $r = $this->checkAuto();


        // @note End of )
        $this->conditions = array(-2 => array('filterOut' => array("T_NS_SEPARATOR")),
                                  -1 => array('atom' => $operands),
                                   0 => array('code' => ',',
                                             'atom' => 'none'),
                                   1 => array('code' => ')',
                                              'atom'  => 'none'),
        );
        
        $this->actions = array('makeEdge'    => array(-1 => 'ARGUMENT'
                                                      ),
                               'order'    => array('-1' => '1'),
                               'atom'       => 'Arguments',
                               );

        $r = $this->checkAuto();
        
        // @note f(1) : no , 
        $this->conditions = array(-1 => array('token' => array('T_STRING', 'T_ECHO', 'T_UNSET', 'T_EMPTY', 'T_ARRAY', 'T_PRINT', 'T_VARIABLE', 'T_ISSET')),
                                   0 => array('code' => '(',
                                             'atom' => 'none'),
                                   1 => array('atom' => $operands_wa),
                                   2 => array('code' => ')',
                                              'atom'  => 'none'),
        );
        
        $this->actions = array('insertEdge'   => array(0 => array('Arguments' => 'ARGUMENT')));

        $r = $this->checkAuto();        

        // @note f() : no argument
        $this->conditions = array(-2 => array('filterOut' => array('T_NS_SEPARATOR')),
                                  -1 => array('token' => array('T_STRING', 'T_ECHO', 'T_UNSET','T_PRINT', 'T_ARRAY', 'T_VARIABLE', 'T_NS_SEPARATOR')),
                                   0 => array('code' => '(',
                                             'atom' => 'none'),
                                   1 => array('code' => ')',
                                              'atom'  => 'none'),
                                   2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLECOLON')),
        );
        
        $this->actions = array('addEdge'   => array(0 => array('Arguments' => 'ARGUMENT')));

        $r = $this->checkAuto();        


        // @note echo 's' : no parenthesis
        $this->conditions = array( 0 => array('atom' => 'none',
                                              'token' => array('T_ECHO', 'T_PRINT', 'T_INCLUDE_ONCE', 'T_INCLUDE', 'T_REQUIRE_ONCE', 'T_REQUIRE',)),
                                   1 => array('atom'  => 'yes'),
                                   2 => array('filterOut' => array('T_DOT', 'T_DOUBLE_COLON', 'T_OBJECT_OPERATOR', 'T_EQUAL', )) //, '->','[','+','-','*','/','%', '='
        );
        
        $this->actions = array('insertEdge'   => array(0 => array('Arguments' => 'ARGUMENT')));

        $r = $this->checkAuto();        

        return $r;
    }
}
?>