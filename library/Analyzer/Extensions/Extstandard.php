<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extstandard extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'standard.ini';

        parent::analyze();
    }
}

?>