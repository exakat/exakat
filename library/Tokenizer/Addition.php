<?php

namespace Tokenizer;

class Addition extends TokenAuto {
    static public $operators = array('T_PLUS','T_MINUS');
    static public $atom = 'Addition';
    
    public function _check() {
        // note : Multiplication:: and Addition:: operators are the same!
        $this->conditions = array(-2 => array('filterOut' => array_merge(Property::$operators,      Staticproperty::$operators, 
                                                                         Concatenation::$operators, Sign::$operators,
                                                                         Multiplication::$operators)),
                                  -1 => array('atom' => Multiplication::$operands ),
                                   0 => array('token' => Addition::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => Multiplication::$operands),
                                   2 => array('filterOut' => array_merge(array('T_OPEN_PARENTHESIS', 'T_OPEN_CURLY', 'T_OPEN_BRACKET'),
                                                                         Property::$operators,      Staticproperty::$operators, 
                                                                         Multiplication::$operators, Power::$operators,
                                                                         Assignation::$operators)
                                   ),
        );
        
        $this->actions = array('transform'    => array( 1 => 'RIGHT',
                                                       -1 => 'LEFT'),
                               'atom'         => 'Addition',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.out("LEFT").next().getProperty('fullcode') + " " + fullcode.getProperty('code') + " " +
                                 fullcode.out("RIGHT").next().getProperty('fullcode'));

GREMLIN;
    }

}
?>
