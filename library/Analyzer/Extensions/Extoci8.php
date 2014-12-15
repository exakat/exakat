<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extoci8 extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'oci8.ini';

        parent::analyze();
    }
}

?>
