<?php

namespace Analyzer\Structures;

use Analyzer;

class MailUsage extends Analyzer\Analyzer {
    public function analyze() {
        $mailerClasses = array('\\Swift', '\\PHPMailer');

        $this->atomFunctionIs('mail')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('New')
             ->outis('NEW')
             ->fullnspath($mailerClasses);
        $this->prepareQuery();

        $this->atomIs('Staticmethodcall')
             ->outis('CLASS')
             ->fullnspath($mailerClasses);
        $this->prepareQuery();

        $this->atomIs('Staticproperty')
             ->outis('CLASS')
             ->fullnspath($mailerClasses);
        $this->prepareQuery();

        $this->atomIs('Staticconstant')
             ->outis('CLASS')
             ->fullnspath($mailerClasses);
        $this->prepareQuery();
    }
}

?>
