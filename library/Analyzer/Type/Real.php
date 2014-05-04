<?php

namespace Analyzer\Type;

use Analyzer;

class Real extends Analyzer\Common\Type {

    public function analyze() {
        $this->type = 'Float';

        parent::analyze();
    }
}

?>