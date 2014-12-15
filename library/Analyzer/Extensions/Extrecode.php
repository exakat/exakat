<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extrecode extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'recode.ini';

        parent::analyze();
    }
}

?>
