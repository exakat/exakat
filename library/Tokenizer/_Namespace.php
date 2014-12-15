<?php

namespace Tokenizer;

class _Namespace extends TokenAuto {
    static public $operators = array('T_NAMESPACE');
    static public $atom = 'Namespace';

    public function _check() {
        // namespace {} 
        $this->conditions = array(0 => array('token' => _Namespace::$operators,
                                             'atom'  => 'none'),
                                  1 => array('atom'  => 'Sequence',
                                             'property' => array('block' => 'true')),
                                  2 => array('token' => array('T_NAMESPACE', 'T_CLOSE_TAG', 'T_END', 'T_SEMICOLON')),
        );
        
        $this->actions = array('insert_global_ns' => 1,
                               'keepIndexed'      => true);
        $this->checkAuto();

        // namespace myproject {} 
        $this->conditions = array(0 => array('token' => _Namespace::$operators,
                                             'atom'  => 'none'),
                                  1 => array('atom'  => array('Identifier', 'Nsname')),
                                  2 => array('atom'  => 'Sequence'),
                                  3 => array('token' => array('T_NAMESPACE', 'T_CLOSE_TAG', 'T_END', 'T_SEMICOLON')),
        );
        
        $this->actions = array('transform'    => array( 1 => 'NAMESPACE',
                                                        2 => 'BLOCK'),
                               'atom'         => 'Namespace',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();

        // namespace myproject ; 
        $this->conditions = array(0 => array('token' => _Namespace::$operators,
                                             'atom'  => 'none'),
                                  1 => array('atom'  => array('Identifier', 'Nsname')),
                                  2 => array('token' => 'T_SEMICOLON',
                                             'atom'  => 'none'),
                                  3 => array('token' => array('T_CLOSE_TAG', 'T_END', 'T_SEMICOLON'),
                                             'atom'  => 'none')
        );
        
        $this->actions = array('insert_ns_void' => true,
                               'atom'           => 'Namespace',
                               'cleanIndex'     => true,
                               'makeSequence'   => 'it');
        $this->checkAuto();

        // namespace A; <Sequence> ? >
        $this->conditions = array(0 => array('token' => _Namespace::$operators,
                                             'atom'  => 'none'),
                                  1 => array('atom'  => array('Identifier', 'Nsname')),
                                  2 => array('token' => 'T_SEMICOLON'),
                                  3 => array('atom'  => 'Sequence'),
                                  4 => array('token' => array('T_CLOSE_TAG', 'T_END'))
        );
        
        $this->actions = array('insert_ns'    => true,
                               'atom'         => 'Namespace',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();

        // namespace\Another : using namespace to build a namespace
        $this->conditions = array(0 => array('token' => _Namespace::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => 'T_NS_SEPARATOR',
                                             'atom'  => 'none')
        );
        
        $this->actions = array('atom'         => 'Identifier',
                               'cleanIndex'   => true);
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.out("NAMESPACE").each{ fullcode.setProperty('fullcode', "namespace " + it.getProperty('fullcode'));} 

fullcode.has('atom', 'Identifier').each{ fullcode.setProperty('fullcode', "namespace"); }

fullcode.has('fullcode', null).filter{ it.out('NAMESPACE').count() == 0}.each{ fullcode.setProperty('fullcode', "namespace Global");} 

GREMLIN;
    }
}

?>
