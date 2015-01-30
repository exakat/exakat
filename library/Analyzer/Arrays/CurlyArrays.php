<?php

namespace Analyzer\Arrays;

use Analyzer;

class CurlyArrays extends Analyzer\Analyzer {
    public function analyze() {
        // $x[3]{3}
        $this->atomIs('Array')
             ->code('{')
             ->hasNoIn('VARIABLE');
        $this->prepareQuery();
    }
}

?>
