<?php

namespace Tokenizer;

class File extends TokenAuto {
    static public $operators = array('T_FILENAME');
    static public $atom = 'File';

    public function _check() {
        // dummy class to File. Shouldn't be used.
        die(__METHOD__);
    }

    public function fullcode() {
        return '';
    }
}

?>