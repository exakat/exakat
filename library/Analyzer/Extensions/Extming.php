<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extming extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'ming.ini';

        parent::analyze();
    }
}

?>