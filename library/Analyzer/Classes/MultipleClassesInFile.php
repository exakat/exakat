<?php

namespace Analyzer\Classes;

use Analyzer;

class MultipleClassesInFile extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("File")
             ->outIs('FILE')
             ->atomInside('Class')
             ->eachNotCounted('code', 1);
        $this->prepareQuery();
    }
}

?>