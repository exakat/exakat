<?php

namespace Tokenizer;

class Phpcode extends TokenAuto {
    static public $operators = array('T_OPEN_TAG');

    public function _check() {
        $this->conditions = array(0 => array('token' => Phpcode::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('token' => 'T_CLOSE_TAG'),
        );
        
        $this->actions = array('transform'     => array( 1 => 'CODE',
                                                         2 => 'DROP'),
                               'makeSequence'  => 'it',
                               'atom'          => 'Phpcode',
                               'cleanIndex'    => true);
        $this->checkAuto();

// <?php echo 3 ( No closing tag)
        $this->conditions = array(0 => array('token' => Phpcode::$operators,
                                             'atom'  => 'none'),
                                  1 => array('atom'  => 'yes'),
                                  2 => array('token' => 'T_END'),
        );
        
        $this->actions = array('transform'     => array( 1 => 'CODE'),
                               'atom'          => 'Phpcode',
                               'property'      => array('closing_tag' => 'false'),
                               'cleanIndex'    => true);
        $this->checkAuto();

// <?php ? > (empty script 
        $this->conditions = array(0 => array('token' => Phpcode::$operators,
                                             'atom' => 'none'),
                                  1 => array('token' => 'T_CLOSE_TAG'),
        );
        
        $this->actions = array('transform'     => array( 1 => 'DROP',
                                                         0 => 'DROP'), // Yes, 0 must be last.
                               'atom'          => 'Phpcode',
                               'transfert'     => array('root' => 2));
        $this->checkAuto();
        
        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN
if (fullcode.code == '<script language=\\"php\\">') {
    fullcode.fullcode = "<script language=\\"php\\">" + fullcode.out("CODE").next().fullcode + "</script>";
} else if (fullcode.code in ['<%', '<%=']) {
    fullcode.fullcode = fullcode.code.trim() + " " + fullcode.out("CODE").next().fullcode + "%>";
} else {
    fullcode.fullcode = fullcode.code.trim() + " " + fullcode.out("CODE").next().fullcode + "?>";
}

GREMLIN;
    }
}

?>