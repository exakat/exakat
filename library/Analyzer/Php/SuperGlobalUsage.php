<?php

namespace Analyzer\Php;

use Analyzer;

class SuperGlobalUsage extends Analyzer\Analyzer {
    public function analyze() {
        // PHP super global Usage
        $this->atomIs("Variable")
             ->code(array('$_GET', '$_POST', '$_REQUEST'), true);
        $this->prepareQuery();
    }
}

?>
