<?php

namespace Tokenizer;

class _Clone extends TokenAuto {
    static public $operators = array('T_CLONE');
    static public $atom = 'Clone';

    public function _check() {
        $operands = array('Variable', 'Property', 'Array', 'Staticproperty', 'Staticmethodcall', 'Staticconstant', 'Functioncall', 
                          'Methodcall', 'New', 'Noscream', "Concatenation",  );
        $this->conditions = array(0 => array('token' => _Clone::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' =>  $operands),
                                  2 => array('filterOut' => array('T_OPEN_BRACKET', 'T_OPEN_CURLY', 'T_DOUBLE_COLON', 'T_OBJECT_OPERATOR', 'T_DOT',
                                                                  'T_OPEN_PARENTHESIS',  )),
        );
        
        $this->actions = array('transform'    => array( 1 => 'CLONE'),
                               'atom'         => 'Clone',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();

        $this->conditions = array(0 => array('token' => _Clone::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => 'T_OPEN_PARENTHESIS'), 
                                  2 => array('atom'  =>  $operands),
                                  3 => array('token' => 'T_CLOSE_PARENTHESIS'), 
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'CLONE',
                                                        3 => 'DROP',),
                               'atom'         => 'Clone',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();
        
        return $this->checkRemaining();
    }

    public function fullcode() {
        return 'it.fullcode = "clone " + it.out("CLONE").next().code; ';
    }
}

?>