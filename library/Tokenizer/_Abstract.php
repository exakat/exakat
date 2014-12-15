<?php

namespace Tokenizer;

class _Abstract extends TokenAuto {
    static public $operators = array('T_ABSTRACT');
    static public $atom = 'Abstract';

    public function _check() {
    // abstract class x { abstract function x() }
        $this->conditions = array( 0 => array('token' => _Abstract::$operators),
                                   1 => array('token' => array('T_CLASS', 'T_FUNCTION')),
                                 );
        $this->actions = array('to_option' => 1,
                               'atom'   => 'Abstract');
        $this->checkAuto(); 

    // abstract class x { abstract public function x() }
        $this->conditions = array( 0 => array('token' => _Abstract::$operators),
                                   1 => array('token' => array('T_PRIVATE', 'T_PROTECTED', 'T_PUBLIC', 'T_STATIC')),
                                   2 => array('token' => 'T_FUNCTION'),
                                 );
        $this->actions = array('to_option' => 2,
                               'atom'   => 'Abstract');
        $this->checkAuto(); 

    // abstract class x { abstract public static function x() }
        $this->conditions = array( 0 => array('token' => _Abstract::$operators),
                                   1 => array('token' => array('T_PRIVATE', 'T_PROTECTED', 'T_PUBLIC', 'T_STATIC')),
                                   2 => array('token' => array('T_PRIVATE', 'T_PROTECTED', 'T_PUBLIC', 'T_STATIC')),
                                   3 => array('token' => 'T_FUNCTION'),
                                 );
        $this->actions = array('to_option' => 3,
                               'atom'   => 'Abstract');
        $this->checkAuto(); 

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN
if (fullcode.out('ABSTRACT').count() == 1) { fullcode.fullcode = 'abstract ' + fullcode.fullcode; }
GREMLIN;
    }

}
?>
