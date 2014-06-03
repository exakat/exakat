<?php

namespace Analyzer\Php;

use Analyzer;

class CloseTags extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Phpcode")
             ->groupFilter("if (it.closing_tag == true) { x2 = 'closed'; } else {x2 = 'opened'; }", 10 / 100);
        $this->prepareQuery();
    }
}

?>