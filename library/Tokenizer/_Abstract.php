<?php

namespace Tokenizer;

class _Abstract extends TokenAuto {
    static public $operators = array('T_ABSTRACT');

    function _check() {
    // abstract class x { abstract function x() }
        $this->conditions = array( 0 => array('token' => _Abstract::$operators),
                                   1 => array('token' => array('T_CLASS', 'T_FUNCTION')),
                                 );
        $this->actions = array('to_option' => 1,
                               'atom'   => 'Abstract');
        $this->checkAuto(); 

        return $this->checkRemaining();
    }

    function fullcode() {
        return <<<GREMLIN
if (fullcode.out('ABSTRACT').count() == 1) { fullcode.fullcode = 'abstract ' + fullcode.fullcode; }
GREMLIN;
    }

}
?>