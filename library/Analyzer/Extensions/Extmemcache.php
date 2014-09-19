<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extmemcache extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'memcache.ini';

        parent::analyze();
    }
}

?>