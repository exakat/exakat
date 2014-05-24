<?php

namespace Analyzer\Php;

use Analyzer;

class IsnullVsEqualNull extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Functioncall")
             ->code('is_null');
        $this->prepareQuery();
    }
}

?>