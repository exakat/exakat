<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extshmop extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'shmop.ini';

        parent::analyze();
    }
}

?>