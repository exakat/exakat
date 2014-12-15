<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extsoap extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'soap.ini';

        parent::analyze();
    }
}

?>
