<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extzip extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'zip.ini';

        parent::analyze();
    }
}

?>