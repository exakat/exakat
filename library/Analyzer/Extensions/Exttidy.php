<?php

namespace Analyzer\Extensions;

use Analyzer;

class Exttidy extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'tidy.ini';

        parent::analyze();
    }
}

?>