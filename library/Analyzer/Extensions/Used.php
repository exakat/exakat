<?php

namespace Analyzer\Extensions;

use Analyzer;

class Used extends Analyzer\Analyzer {

    public function dependsOn() {
        return array('Analyzer\\Extensions\\Extmcrypt', 
                     'Analyzer\\Extensions\\Extpcre',
                     'Analyzer\\Extensions\\Extmysqli',
                     'Analyzer\\Extensions\\Extkdm5',
                     'Analyzer\\Extensions\\Extbcmath',
                     'Analyzer\\Extensions\\Extbzip2',
                     );
    }

    public function analyze() {
        return true;
        $depends = $this->dependsOn();

        $this->atomIs('Index')
             ->code($depends, true)
             ->outIs('ANALYZED')
             ->back('first');
    }
}

?>