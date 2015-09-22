<?php

namespace Analyzer\Classes;

use Analyzer;

class Anonymous extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Class')
             ->hasOut('ARGUMENTS');
        $this->prepareQuery();
    }
}

?>
