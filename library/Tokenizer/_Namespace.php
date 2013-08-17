<?php

namespace Tokenizer;

class _Namespace extends TokenAuto {
    static public $operators = array('T_NAMESPACE');

    function _check() {
        $this->conditions = array(0 => array('token' => _Namespace::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => array('String', 'Nsname')),
                                  2 => array('filterOut' => array('T_NS_SEPARATOR')),
        );
        
        $this->actions = array('transform'   => array( 1 => 'NAMESPACE'),
                               'atom'       => 'Namespace',
                               'cleanIndex' => true);
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}

?>