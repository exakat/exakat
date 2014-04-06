<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extreadline extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'readline.ini';

        parent::analyze();
    }
}

?>