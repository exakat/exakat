<?php

namespace Analyzer\Type;

use Analyzer;

class Integer extends Analyzer\Common\Type {

    public function analyze() {
        $this->type = 'Integer';

        parent::analyze();
    }
}

?>