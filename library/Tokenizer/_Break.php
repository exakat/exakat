<?php

namespace Tokenizer;

class _Break extends TokenAuto {
    static public $operators = array('T_BREAK');
    
    public function _check() {
        $this->conditions = array(0 => array('token' => _Break::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('filterOut' => array_merge(Addition::$operators, Multiplication::$operators)),
                                  );
        
        $this->actions = array('transform'    => array( 1 => 'LEVEL'),
                               'atom'         => 'Break',
                               'makeSequence' => 'it');
        $this->checkAuto();

        return $this->checkRemaining();
    }

    public function fullcode() {
        return 'it.fullcode = "break " + it.out("LEVEL").next().fullcode; ';
    }

}

?>