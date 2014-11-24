<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extpspell extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'pspell.ini';

        parent::analyze();
    }
}

?>