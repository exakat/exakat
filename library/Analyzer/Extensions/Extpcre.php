<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extpcre extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'mysqli.ini';

        parent::analyze();
    }
}

?>