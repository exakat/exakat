<?php

namespace Analyzer\Arrays;

use Analyzer;

class Arrayindex extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Array")
             ->outIs('INDEX');
    }
}

?>
