<?php

namespace Analyzer\Functions;

use Analyzer;

class MultipleReturn extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Function")
             ->raw("filter{ it.out('BLOCK').out.loop(1){it.object.atom != 'Function'}{it.object.atom == 'Return'}.count() > 1}");
        $this->prepareQuery();
    }
}

?>
