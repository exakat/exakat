<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extkdm5 extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'kdm5.ini';

        parent::analyze();
    }
}

?>
