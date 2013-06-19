<?php

namespace Tokenizer;

class Arguments extends TokenAuto {
    function _check() {
        $operands_wa = array('Addition', 'Multiplication', 'Sequence', 'String', 
                             'Integer', 'Float', 'Not', 'Variable','_Array','Concatenation', 'Sign',
                             'Functioncall', 'Boolean', 'Comparison', 'Parenthesis', 'Constant', 'Array' );
        $operands = $operands_wa;
        $operands[] = 'Arguments';
        
        // @note instructions separated by ; 
        $this->conditions = array(-2 => array('filterOut' => array('T_DOT', 'T_AT', 'T_NOT') ),
                                  -1 => array('atom' => $operands ),
                                   0 => array('code' => ',',
                                              'atom' => 'none'),
                                   1 => array('atom' => $operands),
        );
        
        $this->actions = array('makeEdge'    => array( 1 => 'ARGUMENT',
                                                      -1 => 'ARGUMENT'
                                                      ),
                               'order'    => array('1'  => '2',
                                                   '-1' => '1'
                                                      ),
                               'mergeNext'  => array('Arguments', 'ARGUMENT'), 
                               'atom'       => 'Arguments',
                               );
        $r = $this->checkAuto();

        // @note End of )
        $this->conditions = array(-1 => array('atom' => $operands),
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
        $this->conditions = array(-1 => array('atom' => 'none',
                                              'token' => array('T_STRING', 'T_ECHO')),
                                   0 => array('code' => '(',
                                             'atom' => 'none'),
                                   1 => array('atom' => $operands_wa),
                                   2 => array('code' => ')',
                                              'atom'  => 'none'),
        );
        
        $this->actions = array('insertEdge'   => array(0 => array('Arguments' => 'ARGUMENT')));

        $r = $this->checkAuto();        

        // @note f() : no argument
        $this->conditions = array(-1 => array('atom' => 'none',
                                              'token' => 'T_STRING'),
                                   0 => array('code' => '(',
                                             'atom' => 'none'),
                                   1 => array('code' => ')',
                                              'atom'  => 'none'),
        );
        
        $this->actions = array('addEdge'   => array(0 => array('Arguments' => 'ARGUMENT')));

        $r = $this->checkAuto();        


        // @note echo 's' : no parenthesis
        $this->conditions = array( 0 => array('atom' => 'none',
                                              'token' => 'T_ECHO'),
                                   1 => array('atom'  => 'yes'),
                                   2 => array('filterOut' => array('T_DOT', 'T_DOUBLE_COLON' )) //, '->','[','+','-','*','/','%', '='
        );
        
        $this->actions = array('insertEdge'   => array(0 => array('Arguments' => 'ARGUMENT')));

        $r = $this->checkAuto();        

        return $r;
    }
}
?>