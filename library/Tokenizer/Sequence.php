<?php

namespace Tokenizer;

class Sequence extends TokenAuto {
    function _check() {
 
        $operands = array('Addition', 'Multiplication', 'Sequence', 'String', 'Integer', 
                          'Float', 'Not', 'Variable','Array','Concatenation', 'Sign',
                          'Functioncall', 'Constant', 'Parenthesis', 'Comparison', 'Assignation',
                          'Noscream', 'Staticproperty', 'Property', 'Ternary', 'New', 'Return',
                          'Instanceof', 'Magicconstant', 'Staticconstant', 'Methodcall', 'Logical',
                          'Var', 'Const', 'Ppp', 'Postplusplus', 'Preplusplus', 'Global', 'Nsname',
                          'Ifthen', 'Include', 'Function',
                           );
        
        $yield_operator = array('T_ECHO', 'T_PRINT', 'T_DOT', 'T_AT', 'T_OBJECT_OPERATOR', 'T_BANG',
                                'T_DOUBLE_COLON', 'T_COLON', 'T_NEW', 'T_INSTANCEOF', 
                                'T_AND', 'T_QUOTE', 'T_DOLLAR', 'T_VAR', 'T_CONST', 'T_COMMA',
                                'T_PROTECTED', 'T_PRIVATE', 'T_PUBLIC', 'T_INC', 'T_DEC', 'T_GLOBAL', 'T_NS_SEPARATOR',
                                 );
        $yield_operator = array_merge($yield_operator, Assignation::$operators);
        $next_operator = array_merge(array('T_OPEN_PARENTHESIS'), Assignation::$operators);
        
        // @note instructions separated by ; 
        $this->conditions = array(-2 => array('filterOut' => $yield_operator), 
                                  -1 => array('atom' => $operands ),
                                   0 => array('code' => ';'),
                                   1 => array('atom' => $operands),
                                   2 => array('filterOut' => $next_operator),
        );
        
        $this->actions = array('makeEdge'    => array( 1 => 'ELEMENT',
                                                      -1 => 'ELEMENT'
                                                      ),
                               'order'    => array('1'  => '2',
                                                   '-1' => '1'
                                                      ),
                               'mergeNext'  => array('Sequence' => 'ELEMENT'), 
                               'atom'       => 'Sequence',
                               );
        $r = $this->checkAuto();

        // @note instructions not separated by ; 
        $operands2 = array('Function',);
        $this->conditions = array( 0 => array('atom' => $operands2),
                                   1 => array('atom' => $operands2),
        );
        
        $this->actions = array('insertSequence'  => true,
//                               'mergeNext'  => array('Sequence' => 'ELEMENT'), 
                               'atom'       => 'Sequence',
                               );
        $r = $this->checkAuto();
        
        // @note sequence next to another instruction
        $this->conditions = array(-2 => array('filterOut' => $yield_operator), 
                                  -1 => array('atom' => $operands ),
                                   0 => array('atom' => 'Sequence')
        );
        
        $this->actions = array('transform'   => array(-1 => 'ELEMENT'),
                               'order'      => array(-1 =>  1),
                               'mergeNext'  => array('Sequence' => 'ELEMENT'), 
                               'atom'       => 'Sequence',
                               );
        $r = $this->checkAuto();

        // @note sequence next to another instruction
        $this->conditions = array(-1 => array('filterOut' => $yield_operator), 
                                   0 => array('atom' => 'Sequence'),
                                   1 => array('atom' => $operands)
        );
        
        $this->actions = array('transform'   => array(1 => 'ELEMENT'),
                               'order'      => array(1 =>  1),
                               'mergeNext'  => array('Sequence' => 'ELEMENT'), 
                               'atom'       => 'Sequence',
                               );
        $r = $this->checkAuto();


        // @note End of PHP script
        $this->conditions = array(-2 => array('filterOut' => $yield_operator), 
                                  -1 => array('atom' => $operands ),
                                   0 => array('code' => ';',
                                              'atom' => 'none'),
                                   1 => array('token' => array('T_CLOSE_TAG', 'T_CLOSE_CURLY', 'T_END'),
                                              'atom'  => 'none'),
        );
        
        $this->actions = array('makeEdge'    => array(-1 => 'ELEMENT'),
                               'order'    => array('-1' => '1'),
                               'atom'       => 'Sequence',
                               );

        $r = $this->checkAuto();
        
        return $r;
    }
}
?>