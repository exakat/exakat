<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extzlib extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'zlib.ini';

        parent::analyze();
    }
}

?>
