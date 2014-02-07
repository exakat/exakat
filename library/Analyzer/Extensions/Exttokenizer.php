<?php

namespace Analyzer\Extensions;

use Analyzer;

class Exttokenizer extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'tokenizer.ini';

        parent::analyze();
    }
}

?>