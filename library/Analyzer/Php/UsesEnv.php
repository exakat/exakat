<?php

namespace Analyzer\Php;

use Analyzer;

class UsesEnv extends Analyzer\Analyzer {
    public function analyze() {
        // Using putenv or getenv
        $this->atomFunctionIs(array('\\getenv', '\\putenv'))
             ->back('first');
        $this->prepareQuery();

        // Using $_ENV variable
        $this->atomIs('Variable')
             ->code('$_ENV')
             ->inIsIE('VARIABLE')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
