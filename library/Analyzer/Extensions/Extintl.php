<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extintl extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'intl.ini';

        parent::analyze();
    }
}

?>
