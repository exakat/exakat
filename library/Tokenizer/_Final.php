<?php

namespace Tokenizer;

class _Final extends TokenAuto {
    static public $operators = array('T_FINAL');

    function _check() {
    // final class x { final function x() }
        $this->conditions = array( 0 => array('token' => _Final::$operators),
                                   1 => array('token' => array('T_CLASS', 'T_FUNCTION')),
                                 );
        $this->actions = array('to_option' => 1,
                               'atom'      => 'Final');
        $this->checkAuto(); 

        return $this->checkRemaining();
    }

    function fullcode() {
        $function = new _Function(Token::$client);
        return $function->fullcode();

    }
}
?>