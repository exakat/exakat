<?php

namespace Analyzer\Extensions;

use Analyzer;

class Used extends Analyzer\Analyzer {

    function dependsOn() {
        return array('Analyzer\\Extensions\\Extmcrypt', 
                     'Analyzer\\Extensions\\Extpcre',
                     'Analyzer\\Extensions\\Extmysqli',
                     'Analyzer\\Extensions\\Extkdm5',
                     'Analyzer\\Extensions\\Extbcmath',
                     'Analyzer\\Extensions\\Extbzip2',
                     );
    }

    function analyze() {
        $depends = $this->dependsOn();

        $this->atomIs('Index')
             ->code($depends, true)
             ->outIs('ANALYZED')
             ->back('first');
    }
}

?>