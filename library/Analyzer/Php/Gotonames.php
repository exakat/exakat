<?php

namespace Analyzer\Php;

use Analyzer;

class Gotonames extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Goto")
             ->outIs('LABEL');
    }
}

?>
