<?php

namespace Tokenizer;

class _Class extends TokenAuto {
    static public $operators = array('T_CLASS');
    static public $atom = 'Class';

    public function _check() {
    
    // class x {}
        $this->conditions = array( 0 => array('token' => _Class::$operators),
                                   1 => array('atom'  => array('Identifier', 'Null', 'Boolean'))
                                 );
        
        $this->actions = array('transform'   => array(   1 => 'NAME'),
                               'keepIndexed' => true,
                               'atom'        => 'Class',
                               'cleanIndex'  => true);
        $this->checkAuto();

    // class x extends y {}
        $this->conditions = array( 0 => array('token' => _Class::$operators),
                                   1 => array('token' => 'T_EXTENDS'),
                                   2 => array('atom'  => array('Identifier', 'Nsname')),
                                   3 => array('filterOut2' => 'T_NS_SEPARATOR'),
                                 );
        
        $this->actions = array('transform'   => array( 1 => 'DROP',
                                                       2 => 'EXTENDS'),
                               'keepIndexed' => true,
                               'cleanIndex'  => true
                               );
        $this->checkAuto();

    // class x implements a {}
        $this->conditions = array( 0 => array('token'     => _Class::$operators),
                                   1 => array('token'     => 'T_IMPLEMENTS'),
                                   2 => array('atom'      => array('Identifier', 'Nsname', 'Arguments')),
                                   3 => array('filterOut' => array('T_COMMA', 'T_NS_SEPARATOR'))
                                 );
        
        $this->actions = array('transform'     => array( 1 => 'DROP',
                                                         2 => 'IMPLEMENTS'),
                               'property'      => array('rank' => 0),
                               'arg2implement' => 'true',
                               'keepIndexed'   => true,
                               'cleanIndex'    => true );
        $this->checkAuto();

    // class x { // some real code}
        $this->conditions = array( 0 => array('token' => _Class::$operators),
                                   1 => array('atom'  => 'Sequence',
                                              'property' => array('block' => 'true'))
                                 );
        
        $this->actions = array('transform'    => array(1 => 'BLOCK'),
                               'atom'         => 'Class',
                               'makeSequence' => 'it',
                               'cleanIndex'   => true);
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN
fullcode.fullcode = "class " + it.out("NAME").next().code;

// abstract
fullcode.out("ABSTRACT").each{ fullcode.fullcode = 'abstract ' + fullcode.fullcode;}

// final
fullcode.out("FINAL").each{ fullcode.fullcode = 'final ' + fullcode.fullcode;}

// extends
fullcode.out("EXTENDS").each{ fullcode.fullcode = fullcode.fullcode + " extends " + it.fullcode;}

// implements
if (fullcode.out("IMPLEMENTS").count() > 0) {
    s = [];
    fullcode.out("IMPLEMENTS").sort{it.rank}._().each{ s.add(it.fullcode); };
    fullcode.fullcode = fullcode.fullcode + " implements " + s.join(", ");
}

GREMLIN;
    }

}
?>
