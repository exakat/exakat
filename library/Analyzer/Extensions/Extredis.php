<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extredis extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'redis.ini';

        parent::analyze();
    }
}

?>