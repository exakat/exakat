<?php

namespace Tokenizer;

class Heredoc extends TokenAuto {
    static public $operators = array('T_START_HEREDOC');
    
    function _check() {
        $this->conditions = array(0 => array('token' => Heredoc::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => array('String', 'Concatenation', 'Variable', 'Array', 
                                             'Staticproperty','Staticmethodcall','Staticconstant', 'Property', 'Methodcall', 'Functioncall')),
                                  2 => array('token' => 'T_END_HEREDOC',
                                             'atom' => 'none'));
        
        $this->actions = array('transform'    => array( '1' => 'STRING',
                                                        '2' => 'DROP' ),
                               'atom'       => 'Heredoc');
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}

?>