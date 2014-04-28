<?php

namespace Tokenizer;

class _Throw extends TokenAuto {
    static public $operators = array('T_THROW');
    static public $atom = 'Throw';
    
    public function _check() {
        $this->conditions = array(0 => array('token'     => _Throw::$operators,
                                             'atom'      => 'none'),
                                  1 => array('atom'      => array('New', 'Variable', 'Functioncall', 'Property', 'Array', 'Methodcall', 
                                                                  'Staticmethodcall', 'Staticproperty', 'Identifier', 'Assignation')),
                                  2 => array('filterOut' => Token::$instruction_ending),
                                  );
        
        $this->actions = array('transform'    => array( 1 => 'THROW'),
                               'atom'         => 'Throw',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
                               
        $this->checkAuto();

        $this->conditions = array(0 => array('token' => _Throw::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  2 => array('atom'  => array('New', 'Variable', 'Functioncall', 'Property', 'Array', 'Methodcall', 
                                                              'Staticmethodcall', 'Staticproperty', 'Identifier', 'Assignation')),
                                  3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'THROW',
                                                        3 => 'DROP',),
                               'atom'         => 'Throw',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
                               
        $this->checkAuto();
        
        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.fullcode = "throw " + fullcode.out("THROW").next().fullcode; 
GREMLIN;
    }
}

?>