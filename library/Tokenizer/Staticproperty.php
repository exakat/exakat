<?php

namespace Tokenizer;

class Staticproperty extends TokenAuto {
    static public $operators = array('T_DOUBLE_COLON');
    static public $atom = 'Staticproperty';

    public function _check() {
        $operands = array('Constant', 'Identifier', 'Variable', 'Array', 'Static', 'Nsname', );
        
        $this->conditions = array( -2 => array('filterOut2' => array('T_NS_SEPARATOR')),
                                   -1 => array('atom' => $operands),
                                    0 => array('token' => Staticproperty::$operators),
                                    1 => array('atom' => array('Variable', 'Array', 'Property', )),
                                    2 => array('filterOut' => array('T_OPEN_PARENTHESIS')));
        
        $this->actions = array('transform'    => array( -1 => 'CLASS',
                                                         1 => 'PROPERTY'),
                               'atom'         => 'Staticproperty',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.out("CLASS").next().getProperty('fullcode') + "::" + fullcode.out("PROPERTY").next().getProperty('fullcode'));

GREMLIN;
    }
}

?>
