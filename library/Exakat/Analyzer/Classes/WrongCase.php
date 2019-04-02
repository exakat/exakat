<?php
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class WrongCase extends Analyzer {

    public function analyze() {
        // New
        $this->atomIs('New')
             ->outIs('NEW')
             ->codeIsNot(array('static', 'parent', 'self'), self::TRANSLATE, self::CASE_INSENSITIVE)
             ->outIsIE('NAME')
             ->initVariable('classe')
             ->raw(<<<GREMLIN
sideEffect{ 
    if (it.get().value("token") == "T_STRING") {
        classe = it.get().value('fullcode');
    } else { // it is a namespace
        classe = it.get().value('fullcode').tokenize('\\\\').last();
    }
}
GREMLIN
)
             ->inIsIE('NAME')
             ->inIs('DEFINITION')
             ->outIs('NAME')
             ->notSamePropertyAs('fullcode', 'classe', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

// staticMethodcall
        $this->atomIs(array('Staticmethodcall', 'Staticproperty', 'Staticconstant', 'Staticclass'))
             ->outIs('CLASS')
             ->atomIsNot(self::$RELATIVE_CLASS)
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->outIs('DEFINITION')
                             ->atomIs(array('As', 'Nsname', 'Identifier'))
                     )
             )
             ->initVariable('classe')
             ->raw(<<<GREMLIN
sideEffect{ 
    if (it.get().value("token") == "T_STRING") {
        classe = it.get().value('fullcode');
    } else { // it is a namespace
        classe = it.get().value('fullcode').tokenize('\\\\').last();
    }
}
GREMLIN
)
             ->inIs('DEFINITION')
             ->outIs('NAME')
             ->notSamePropertyAs('fullcode', 'classe', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

// Catch
        $this->atomIs('Catch')
             ->outIs('CLASS')
             ->atomIsNot(self::$RELATIVE_CLASS)
             ->initVariable('classe')
             ->raw(<<<GREMLIN
sideEffect{ 
    if (it.get().value("token") == "T_STRING") {
        classe = it.get().value('fullcode');
    } else { // it is a namespace
        classe = it.get().value('fullcode').tokenize('\\\\').last();
    }
}
GREMLIN
)
             ->inIs('DEFINITION')
             ->outIs('NAME')
             ->notSamePropertyAs('fullcode', 'classe', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

// Typehint
        $this->atomIs(self::$FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->outIs('TYPEHINT')
             ->atomIsNot(self::$RELATIVE_CLASS)
             ->initVariable('classe')
             ->raw(<<<GREMLIN
sideEffect{ 
    if (it.get().value("token") == "T_STRING") {
        classe = it.get().value('fullcode');
    } else { // it is a namespace
        classe = it.get().value('fullcode').tokenize('\\\\').last();
    }
}
GREMLIN
)
             ->inIs('DEFINITION')
             ->outIs('NAME')
             ->notSamePropertyAs('fullcode', 'classe', self::CASE_SENSITIVE)
             ->back('first')
             ->outIs('ARGUMENT');
        $this->prepareQuery();

// instance of
        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->atomIsNot(self::$RELATIVE_CLASS)
             ->initVariable('classe')
             ->raw(<<<GREMLIN
sideEffect{ 
    if (it.get().value("token") == "T_STRING") {
        classe = it.get().value('fullcode');
    } else { // it is a namespace
        classe = it.get().value('fullcode').tokenize('\\\\').last();
    }
}
GREMLIN
)
             ->inIs('DEFINITION')
             ->outIs('NAME')
             ->notSamePropertyAs('fullcode', 'classe', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

// use
        $this->atomIs('Usenamespace')
             ->outIs('USE')
             ->outIsIE('NAME')
             ->initVariable('classe')
             ->raw(<<<GREMLIN
sideEffect{ 
    if (it.get().value("token") == "T_STRING") {
        classe = it.get().value('fullcode');
    } else { // it is a namespace
        classe = it.get().value('fullcode').tokenize('\\\\').last();
    }
}
GREMLIN
)
             ->inIs('DEFINITION')
             ->outIs('NAME')
             ->notSamePropertyAs('fullcode', 'classe', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
