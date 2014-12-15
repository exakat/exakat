<?php

namespace Analyzer\Php;

use Analyzer;

class TryCatchUsage extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs('Catch')
             ->outIs ('CLASS');
    }
}

?>
