<?php

namespace Analyzer\Php;

use Analyzer;

class TryCatchUsage extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs('Catch')
             ->outIs ('CLASS');
    }
}

?>