<?php

namespace Tokenizer;

class _Namespace extends TokenAuto {
    static public $operators = array('T_NAMESPACE');

    public function _check() {
        // namespace {} 
        $this->conditions = array(0 => array('token' => _Namespace::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => 'Sequence'),
        );
        
        $this->actions = array('insert_global_ns' => 1,
                               'keepIndexed'      => true);
        $this->checkAuto();

        // namespace myproject {} 
        $this->conditions = array(0 => array('token' => _Namespace::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => array('Identifier', 'Nsname')),
                                  2 => array('atom' => 'Sequence'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'NAMESPACE',
                                                        2 => 'BLOCK'),
                               'atom'         => 'Namespace',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();

        // namespace myproject ; 
        $this->conditions = array(0 => array('token' => _Namespace::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => array('Identifier', 'Nsname')),
                                  2 => array('filterOut' => array('T_NS_SEPARATOR')),
        );
        
        $this->actions = array('transform'    => array( 1 => 'NAMESPACE'),
                               'atom'         => 'Namespace',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();
        
        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.out("NAMESPACE").each{ fullcode.fullcode = "namespace " + it.fullcode;} 
fullcode.filter{ it.out('NAMESPACE').count() == 0}.each{ fullcode.fullcode = "namespace Global";} 

GREMLIN;
    }
}

?>