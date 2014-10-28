<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extimagick extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'imagick.ini';

        parent::analyze();
    }
}

?>