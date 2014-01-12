<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extmcrypt extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'mcrypt.ini';

        parent::analyze();
    }
}

?>