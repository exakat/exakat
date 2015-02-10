<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/


namespace Analyzer\Classes;

use Analyzer;

class NonStaticMethodsCalledStatic extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Classes\\MethodDefinition",
                     "Analyzer\\Classes\\StaticMethods"
        );
    }

    public function analyze() {
        // check outside the class
        $this->atomIs('Staticmethodcall')
             ->notInClass()
             ->outIs('CLASS')
             ->codeIsNot(array('parent', 'self', 'static'))
             ->inIs('CLASS')
             ->raw("filter{ x = it;

                        g.idx('atoms')[['atom':'Function']]
                           .filter{ it.out('NAME').next().code.toLowerCase() == x.out('METHOD').next().code.toLowerCase()}.
                            filter{ it.in('ELEMENT').in('BLOCK').out('NAME').next().code.toLowerCase() == x.out('CLASS').next().code.toLowerCase()}.
                            filter{ it.out('NAME').in('ANALYZED').has('code', 'Analyzer\\\\Classes\\\\MethodDefinition').any()}.
                            filter{ it.out('NAME').in('ANALYZED').has('code', 'Analyzer\\\\Classes\\\\StaticMethods').count() == 0}
                           .any() }");
        $this->prepareQuery();

        // check inside the class
        $this->atomIs('Staticmethodcall')
             ->raw("filter{ x = it;
                        g.idx('atoms')[['atom':'Function']]
                           .filter{ it.out('NAME').next().code.toLowerCase() == x.out('METHOD').next().code.toLowerCase()}.
                            filter{ it.in('ELEMENT').in('BLOCK').out('NAME').next().code.toLowerCase() == x.out('CLASS').next().code.toLowerCase()}.
                            filter{ it.out('NAME').in('ANALYZED').has('code', 'Analyzer\\\\Classes\\\\MethodDefinition').any()}.
                            filter{ it.out('NAME').in('ANALYZED').has('code', 'Analyzer\\\\Classes\\\\StaticMethods').count() == 0}
                           .any() }")
             ->outIs('CLASS')
             ->codeIsNot(array('parent', 'self', 'static'))
             ->isNotGrandParent()

             ->savePropertyAs('fullnspath', 'fns')
             ->goToClass()
             ->notSamePropertyAs('fullnspath', 'fns')
// back to initial
             ->back('first');
        $this->prepareQuery();
    }
}

?>
