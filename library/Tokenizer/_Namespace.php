<?php

namespace Tokenizer;

class _Namespace extends TokenAuto {
    static public $operators = array('T_NAMESPACE');

    function _check() {
        // namespace myproject {} 
        $this->conditions = array(0 => array('token' => _Namespace::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => array('Identifier', 'Nsname')),
                                  2 => array('atom' => 'Block'),
        );
        
        $this->actions = array('transform'   => array( 1 => 'NAMESPACE',
                                                       2 => 'BLOCK'),
                               'atom'       => 'Namespace',
                               'cleanIndex' => true);
        $this->checkAuto();

        // namespace myproject {} 
        $this->conditions = array(0 => array('token' => _Namespace::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => 'Block'),
        );
        
        $this->actions = array('transform'   => array( 1 => 'BLOCK'),
                               'atom'       => 'Namespace',
                               'cleanIndex' => true);
        $this->checkAuto();

        // namespace myproject ; 
        $this->conditions = array(0 => array('token' => _Namespace::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => array('Identifier', 'Nsname')),
                                  2 => array('filterOut' => array('T_NS_SEPARATOR')),
        );
        
        $this->actions = array('transform'   => array( 1 => 'NAMESPACE'),
                               'atom'       => 'Namespace',
                               'cleanIndex' => true);
        $this->checkAuto();
        
        return $this->checkRemaining();
    }

    function fullcode() {
        return 'it.fullcode = "namespace " + it.out("NAMESPACE").next().code; ';
    }
}

?>