<?php

namespace Analyzer\Namespaces;

use Analyzer;

class Alias extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Use")
             ->outIs('USE')
             ->outIs('AS');
    }
}

?>
