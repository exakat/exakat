<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extsimplexml extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'simplexml.ini';

        parent::analyze();
    }
}

?>
