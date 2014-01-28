<?php

namespace Analyzer\Structures;

use Analyzer;

class OnceUsage extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs("Include")
             ->tokenIs(array('T_REQUIRE_ONCE', 'T_INCLUDE_ONCE'));
        $this->prepareQuery();
    }
}

?>