<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extmemcached extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'memcached.ini';

        parent::analyze();
    }
}

?>