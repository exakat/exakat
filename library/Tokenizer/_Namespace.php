<?php

namespace Tokenizer;

class _Namespace extends TokenAuto {
    static public $operators = array('T_NAMESPACE');

    function _check() {
        // namespace {} 
        $this->conditions = array(0 => array('token' => _Namespace::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => 'Block'),
        );
        
        $this->actions = array('insert_global_ns' => 1,
                               'keepIndexed'      => true);
        $this->checkAuto();

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
        return <<<GREMLIN

token = it;
it.out("NAMESPACE").each{ token.fullcode = "namespace " + it.fullcode;} 
it.filter{ it.out('NAMESPACE').count() == 0}.each{ token.fullcode = "namespace Global";} 

GREMLIN;
    }
}

?>