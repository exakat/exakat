<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extzmq extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'zmq.ini';

        parent::analyze();
    }
}

?>
