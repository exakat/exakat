<?php declare(strict_types = 1);
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

namespace Exakat\Analyzer\Exceptions;

use Exakat\Analyzer\Analyzer;

class CouldUseTry extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/PropagateConstants',
                    );
    }

    public function analyze() {
        // $a = $b << $c; (No try... )
        $this->atomIs('Bitshift')
             ->outIs('RIGHT')
             ->has('intval')
             ->isLess('intval', 0)
             ->hasNoTryCatch()
             ->back('first');
        $this->prepareQuery();

        // $a = $b << $c; (No try... )
        $this->atomIs('Bitshift')
             ->outIs('RIGHT')
             ->hasNo('intval')
             ->hasNoTryCatch()
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Multiplication')
             ->codeIs(array('%', '/'))
             ->outIs('RIGHT')
             ->hasNo('intval')
             ->hasNoTryCatch()
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Multiplication')
             ->codeIs(array('%', '/'))
             ->outIs('RIGHT')
             ->has('intval')
             ->is('intval', 0)
             ->hasNoTryCatch()
             ->back('first');
        $this->prepareQuery();

        $this->atomFunctionIs('\\intdiv')
             ->outWithRank('ARGUMENT', 1)
             ->hasNo('intval')
             ->hasNoTryCatch()
             ->back('first');
        $this->prepareQuery();

        $throws = $this->loadJson('throw_exceptions.json');
        $functions = array();
        $news = array();
        $methods = array();

        foreach($throws as $throw) {
            if (isset($throw->function)) {
                $functions[] = makeFullnspath($throw->function);
                continue;
            }

            if (isset($throw->class) && $throw->method === '__construct') {
                $news[] = makeFullnspath($throw->class);
                continue;
            }

            array_collect_by($methods, makeFullnspath($throw->class),  $throw->method);
        }

        $this->atomIs('New')
             ->outIs('NEW')
             ->is('fullnspath', $news)
             ->hasNoTryCatch();
        $this->prepareQuery();

        $this->atomFunctionIs($functions)
             ->hasNoTryCatch();
        $this->prepareQuery();

        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->is('fullnspath', array_keys($methods))
             ->savePropertyAs('fullnspath', 'fqn')
             ->back('first')

             ->outIs('METHOD')
             ->outIs('NAME')
             ->isHash('fullcode', $methods, 'fqn', self::CASE_INSENSITIVE)
             ->hasNoTryCatch()
             ->back('first');
        $this->prepareQuery();

        /* Not yet available : No DEFINITION link to the native PHP classes
        $this->atomIs('Methodcall')
             
             ->outIs('CLASS')
             ->is('fullnspath', '\\phar')
             ->inIs('CLASS')
             ->outIs('METHOD')
             ->codeIs(array('mungserver', 'webphar'), self::TRANSLATE, self::CASE_INSENSITIVE)
             ->hasNoTryCatch()
             ->back('first');
        $this->prepareQuery();
        */
    }
}

?>
