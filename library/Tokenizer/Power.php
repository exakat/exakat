<?php

namespace Tokenizer;

class Power extends TokenAuto {
    static public $operators = array('T_POW');
    static public $atom = 'Power';

    public function _check() {
        $this->conditions = array(-2 => array('filterOut' => array_merge(Property::$operators,
                                                                         Staticproperty::$operators,
                                                                         Concatenation::$operators,
                                                                         Preplusplus::$operators)),
                                  -1 => array('atom'  => Multiplication::$operands ),
                                   0 => array('token' => Power::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => Multiplication::$operands),
                                   2 => array('filterOut' => array_merge(array('T_OPEN_PARENTHESIS', 'T_OPEN_CURLY', 'T_OPEN_BRACKET',
                                                                               'T_DOUBLE_COLON', 'T_OBJECT_OPERATOR'),
                                                                          Assignation::$operators)),
        );
        
        $this->actions = array('transform'    => array(  1 => 'RIGHT',
                                                        -1 => 'LEFT'),
                               'atom'         => 'Power',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode',  fullcode.out("LEFT").next().getProperty('fullcode') + " ** " +
                                  fullcode.out("RIGHT").next().getProperty('fullcode'));

GREMLIN;

    }
}

?>
