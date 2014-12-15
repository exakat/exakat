<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extimap extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'imap.ini';

        parent::analyze();
    }
}

?>
