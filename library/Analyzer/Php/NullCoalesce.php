<?php

namespace Analyzer\Php;

use Analyzer;

class NullCoalesce extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Coalesce');
        $this->prepareQuery();
    }
}

?>
