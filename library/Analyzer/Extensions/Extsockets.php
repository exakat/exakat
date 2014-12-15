<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extsockets extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'sockets.ini';

        parent::analyze();
    }
}

?>
