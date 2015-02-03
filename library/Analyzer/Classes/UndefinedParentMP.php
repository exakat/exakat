<?php

namespace Analyzer\Classes;

use Analyzer;

class UndefinedParentMP extends Analyzer\Analyzer {
    public function analyze() {
        // parent::method()
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->code('parent')
             ->back('first')
             ->outIs('METHOD')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->raw('filter{ it.out("IMPLEMENTS", "EXTENDS").transform{ g.idx("classes")[["path":it.fullnspath]].next(); }.loop(2){true}{it.object.atom == "Class"}.out("BLOCK").out("ELEMENT").has("atom", "Function").filter{ it.out("PRIVATE").any() == false}.out("NAME").has("code", name).any() == false}')
             ->back('first');
      $this->prepareQuery();

        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->code('parent')
             ->back('first')
             ->outIs('PROPERTY')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->raw('filter{ it.out("IMPLEMENTS", "EXTENDS").transform{ g.idx("classes")[["path":it.fullnspath]].next(); }.loop(2){true}{it.object.atom == "Class"}.out("BLOCK").out("ELEMENT").has("atom", "Ppp").filter{ it.out("PRIVATE").any() == false}.out("DEFINE").has("code", name).any() == false}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
