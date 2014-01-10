<?php

namespace Tokenizer;

class _Global extends TokenAuto {
    static public $operators = array('T_GLOBAL');

    function _check() {
        $values = array('T_EQUAL', 'T_COMMA');

    // global $x; (nothing more)
        $this->conditions = array( 0 => array('token' => _Global::$operators),
                                   1 => array('atom' => array('Variable', 'String', 'Staticconstant', 'Static' )),
                                   2 => array('token' => 'T_SEMICOLON'),
                                 );
        
        $this->actions = array('transform'  => array( 1 => 'NAME'),
                               'atom'       => 'Global',
                               'cleanIndex' => true
                               );
        $this->checkAuto(); 

    // global $x, $y, $z;
        $this->conditions = array( 0 => array('token' => _Global::$operators),
                                   1 => array('atom' => 'Arguments'),
                                   2 => array('filterOut' => array('T_COMMA')),
                                 );
        
        $this->actions = array('to_global'   => 'Global',
                               'keepIndexed' => true);
        $this->checkAuto(); 

        return $this->checkRemaining();
    }

    function fullcode() {
        return '
it.fullcode = "global " + it.out("NAME").next().fullcode;

';
    }

}
?>