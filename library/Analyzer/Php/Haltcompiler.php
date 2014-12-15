<?php

namespace Analyzer\Php;

use Analyzer;

class Haltcompiler extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Halt");
    }
}

?>
