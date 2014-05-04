<?php

namespace Analyzer\Php;

use Analyzer;

class ThrowUsage extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Throw");
    }
}

?>