<?php

namespace Analyzer\Classes;

use Analyzer;

class ClassUsage extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("New")
             ->out('NEW');
    }
}

?>