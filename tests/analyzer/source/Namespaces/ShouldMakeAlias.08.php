<?php



namespace B\C\D;

use B\C\G\H;
use B\C\G\L;
use B\C\G\L as Q;

class R extends H {
    public function T() {
        $this->U = array('V',
                               'W');

        return X::T();
    }
}

class Z extends \L {
    public function T() {
        $this->U = array('V',
                               'W');

        return X::T();
    }
}

class Z extends Q\L {
    public function T() {
        $this->U = array('V',
                               'W');

        return X::T();
    }
}

?>
