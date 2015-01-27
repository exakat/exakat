<?php

namespace Analyzer\Classes;

use Analyzer;

class MultipleClassesInFile extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('File')
             ->savePropertyAs('code', 'filename')
             ->outIs('FILE')
             ->atomInside('Class')
             ->eachCounted('filename', 2, '>=');
        $this->prepareQuery();
    }
}

?>
