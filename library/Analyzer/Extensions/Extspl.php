<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extspl extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'spl.ini';

        parent::analyze();
    }
}

?>