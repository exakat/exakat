<?php

namespace Analyzer\Structures;

use Analyzer;

class IncludeUsage extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Include")
             ->tokenIs(array('T_REQUIRE', 'T_INCLUDE', 'T_REQUIRE_ONCE', 'T_INCLUDE_ONCE'));
        $this->prepareQuery();
    }
}

?>