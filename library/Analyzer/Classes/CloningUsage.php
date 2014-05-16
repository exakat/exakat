<?php

namespace Analyzer\Classes;

use Analyzer;

class CloningUsage extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Clone");
    }
}

?>