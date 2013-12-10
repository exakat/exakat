<?php

namespace Analyzer\Type;

use Analyzer;

class String extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs(array('String', 'HereDoc', 'NowDoc'))
             ->tokenIsNot('T_QUOTE');
    }
}

?>