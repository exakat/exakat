<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extwddx extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'wddx.ini';

        parent::analyze();
    }
}

?>
