<?php

namespace Tokenizer;

class Methodcall extends TokenAuto {
    static public $operators = array('T_OBJECT_OPERATOR');

    function _check() {
        $operands = array('Variable', 'Property', 'Array', 'Functioncall', 'Methodcall', 'Staticmethodcall', 'Staticproperty' );

        // $this->x($args);
        $this->conditions = array( -2 => array('filterOut' => array('T_DOUBLE_COLON')),
                                   -1 => array('atom' => $operands), 
                                    0 => array('token' => Methodcall::$operators),
                                    1 => array('atom' => array('Functioncall', 'Methodcall'))
                                 );
        
        $this->actions = array('makeEdge'   => array( -1 => 'OBJECT',
                                                       1 => 'METHOD'),
                               'atom'       => 'Methodcall',
                               'cleanIndex' => true);
        $this->checkAuto(); 

        // $this->{$x}($args);
        $this->conditions = array( -2 => array('filterOut' => array('T_DOUBLE_COLON')),
                                   -1 => array('atom'      => $operands), 
                                    0 => array('token'     => Methodcall::$operators),
                                    1 => array('atom'      => 'Block'),
                                    2 => array('token'     => 'T_OPEN_PARENTHESIS'),
                                    3 => array('atom'      => array('Arguments', 'Void')),
                                    4 => array('token'     => 'T_CLOSE_PARENTHESIS')
                                 );
        
        $this->actions = array('transform'   => array( -1 => 'OBJECT',
                                                        1 => 'METHOD',
                                                        2 => 'DROP',
                                                        3 => 'ARGUMENTS',
                                                        4 => 'DROP'),
                               'atom'       => 'Methodcall',
                               'cleanIndex' => true);
        $this->checkAuto(); 
        
        return $this->checkRemaining();
    }
}

?>