<?php

namespace Tokenizer;

class Bitshift extends TokenAuto {
    static public $operators = array('T_SR','T_SL');
    static public $atom = 'Bitshift';
        
    public function _check() {
        // note : Multiplication:: and Bitshift:: operators are the same!
        $this->conditions = array(-2 => array('filterOut' => array_merge(Property::$operators,      Staticproperty::$operators,
                                                                         Concatenation::$operators, Bitshift::$operators)),
                                  -1 => array('atom'      => array_merge(array('Bitshift'), Multiplication::$operands)),
                                   0 => array('token'     => Bitshift::$operators,
                                             'atom'       => 'none'),
                                   1 => array('atom'      => Multiplication::$operands),
                                   2 => array('filterOut' => array_merge(Functioncall::$operators, Block::$operators,
                                                                         _Array::$operators,       Concatenation::$operators,
                                                                         Property::$operators,     Staticproperty::$operators))
        );
        
        $this->actions = array('transform'    => array( 1 => 'RIGHT',
                                                       -1 => 'LEFT'),
                               'atom'         => 'Bitshift',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it'
                               );
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode',  fullcode.out("LEFT").next().getProperty('fullcode') + " " + fullcode.getProperty('code')
                                    + " " + fullcode.out("RIGHT").next().getProperty('fullcode') );

GREMLIN;

    }
}
?>
