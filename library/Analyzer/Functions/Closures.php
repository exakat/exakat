<?php

namespace Analyzer\Functions;

use Analyzer;

class Closures extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Function")
             ->is('lambda', 'true');
    }
}

?>
