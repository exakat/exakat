<?php

namespace Tokenizer;

class _Goto extends TokenAuto {
    static public $operators = array('T_GOTO');
    static public $atom = 'Goto';
    
    public function _check() {
        $this->conditions = array(0 => array('token' => _Goto::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => 'Identifier')
                                  );
        
        $this->actions = array('transform'    => array(1 => 'LABEL'),
                               'atom'         => 'Goto',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.fullcode = "goto " + fullcode.out('LABEL').next().fullcode;

GREMLIN;
    }
}

?>
