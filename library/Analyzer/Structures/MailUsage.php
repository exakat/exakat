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
             ->outIs('NEW')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->atomIsNot(array('Variable', 'Array', 'Property', 'Staticproperty', 'Methodcall', 'Staticmethodcall'))
             ->fullnspath($mailerClasses);
        $this->prepareQuery();

        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->fullnspath($mailerClasses);
        $this->prepareQuery();

        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->fullnspath($mailerClasses);
        $this->prepareQuery();

        $this->atomIs('Staticconstant')
             ->outIs('CLASS')
             ->fullnspath($mailerClasses);
        $this->prepareQuery();
    }
}

?>
