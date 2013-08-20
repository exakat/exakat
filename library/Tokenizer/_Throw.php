<?php

namespace Tokenizer;

class _Throw extends TokenAuto {
    static public $operators = array('T_THROW');
    
    function _check() {
        $this->conditions = array(0 => array('token'     => _Throw::$operators,
                                             'atom'      => 'none'),
                                  1 => array('atom'      => array('New', 'Variable', 'Functioncall', 'Property', 'Array', 'Methodcall', 
                                                                  'Staticmethodcall', 'Staticproperty', 'String')),
                                  2 => array('filterOut' => Token::$instruction_ending),
                                  );
        
        $this->actions = array('transform'  => array( 1 => 'THROW'),
                               'atom'       => 'Throw',
                               'cleanIndex' => true);
                               
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}

?>