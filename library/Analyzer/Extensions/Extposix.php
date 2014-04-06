<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extposix extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'posix.ini';

        parent::analyze();
    }
}

?>