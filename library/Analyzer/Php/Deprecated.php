<?php

namespace Analyzer\Php;

use Analyzer;

class Deprecated extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'deprecated.ini';

        parent::analyze();
    }
}

?>
