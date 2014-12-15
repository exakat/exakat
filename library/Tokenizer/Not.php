<?php

namespace Tokenizer;

class Not extends TokenAuto {
    static public $operators = array('T_BANG', 'T_TILDE');
    static public $atom = 'Not';

    public function _check() {
        $this->conditions = array(0 => array('token' => Not::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_DOT', 'T_DOUBLE_COLON',
                                                                  'T_OPEN_BRACKET', 'T_OPEN_CURLY', 'T_NS_SEPARATOR', 'T_INC', 'T_DEC' )),
        );
        
        $this->actions = array('transform'    => array( 1 => 'NOT'),
                               'atom'         => 'Not',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode',  fullcode.code + fullcode.out("NOT").next().getProperty('fullcode') );

GREMLIN;
    }
}

?>
