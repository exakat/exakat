<?php

namespace Analyzer\Php;

use Analyzer;

class IsnullVsEqualNull extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Functioncall")
             ->fullnspath('\\is_null');
        $this->prepareQuery();
    }
}

?>