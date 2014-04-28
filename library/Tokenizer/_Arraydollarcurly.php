<?php

namespace Tokenizer;

class _Arraydollarcurly extends TokenAuto {
    static public $operators = array('T_DOLLAR_OPEN_CURLY_BRACES');
    static public $atom = 'Array';
    
    public function _check() {
        $this->conditions = array( 0 => array('token' => _Arraydollarcurly::$operators),
                                   1 => array('token' => 'T_STRING_VARNAME'),
                                   2 => array('token' => 'T_OPEN_BRACKET'),
                                   3 => array('token' => 'T_CONSTANT_ENCAPSED_STRING'),
                                   4 => array('token' => 'T_CLOSE_BRACKET'),
                                   5 => array('token' => 'T_CLOSE_CURLY'),
                                 );
        
        $this->actions = array('transform' => array(   1 => 'VARIABLE',
                                                       2 => 'DROP',
                                                       3 => 'INDEX',
                                                       4 => 'DROP',
                                                       5 => 'DROP'  ),
                               'atom'      => 'Array',
                               'keepIndexed' => true,
                               'cleanIndex' => true);
        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}

?>