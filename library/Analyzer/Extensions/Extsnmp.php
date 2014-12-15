<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extsnmp extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'snmp.ini';

        parent::analyze();
    }
}

?>
