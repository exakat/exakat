<?php

namespace Analyzer\Type;

use Analyzer;

class Real extends Analyzer\Type {

    function analyze() {
        $this->type = 'Float';

        parent::analyze();
    }
}

?>