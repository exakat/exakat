<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extpcntl extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'pcntl.ini';

        parent::analyze();
    }
}

?>
