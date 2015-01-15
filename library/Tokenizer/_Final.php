<?php

namespace Tokenizer;

class _Final extends TokenAuto {
    static public $operators = array('T_FINAL');
    static public $atom = 'Final';

    public function _check() {
    // final class x { final function x() }
        $this->conditions = array( 0 => array('token' => _Final::$operators),
                                   1 => array('token' => array('T_CLASS', 'T_FUNCTION')),
                                 );
        $this->actions = array('to_option' => 1,
                               'atom'      => 'Final');
        $this->checkAuto();

    // final class x { final private function x() }
        $this->conditions = array( 0 => array('token' => _Final::$operators),
                                   1 => array('token' => array('T_PRIVATE', 'T_PROTECTED', 'T_PUBLIC', 'T_STATIC')),
                                   2 => array('token' => 'T_FUNCTION'),
                                 );
        $this->actions = array('to_option' => 2,
                               'atom'      => 'Final');
        $this->checkAuto();

    // final class x { final private static function x() }
        $this->conditions = array( 0 => array('token' => _Final::$operators),
                                   1 => array('token' => array('T_PRIVATE', 'T_PROTECTED', 'T_PUBLIC', 'T_STATIC')),
                                   2 => array('token' => array('T_PRIVATE', 'T_PROTECTED', 'T_PUBLIC', 'T_STATIC')),
                                   3 => array('token' => 'T_FUNCTION'),
                                 );
        $this->actions = array('to_option' => 3,
                               'atom'      => 'Final');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        $function = new _Function(Token::$client);
        return $function->fullcode();
    }
}
?>
