<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extexif extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'exif.ini';
        
        parent::analyze();
    }
}

?>