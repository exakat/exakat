<?php

namespace Tokenizer;

class _Instanceof extends TokenAuto {
    static public $operators = array('T_INSTANCEOF');
    static public $atom = 'Instanceof';

    public function _check() {
        $this->conditions = array(-2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON')),
                                  -1 => array('atom'      => 'yes',
                                              'notAtom'   => 'Sequence'),
                                   0 => array('token'     => _Instanceof::$operators,
                                              'atom'      => 'none'),
                                   1 => array('atom'      => 'yes',
                                              'notAtom'   => 'Sequence'),
                                   2 => array('filterOut' => array('T_OPEN_BRACKET', 'T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_NS_SEPARATOR')),
                                  
        );
        
        $this->actions = array('makeEdge'     => array(  1 => 'VARIABLE',
                                                        -1 => 'CLASS'),
                               'atom'         => 'Instanceof',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it' );
        $this->checkAuto();
        
        return $this->checkRemaining();
    } 
    
    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.out("VARIABLE").next().getProperty('fullcode') + " instanceof " + fullcode.out("CLASS").next().getProperty('fullcode')); 

GREMLIN;

    }
}
?>