<?php

namespace Analyzer\Php;

use Analyzer;

class CastingUsage extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs("Cast");
    }
}

?>
