<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extmbstring extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'mbstring.ini';

        parent::analyze();
    }
}

?>
