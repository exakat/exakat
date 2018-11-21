<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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
    public function analyze() {
        // $a = $b << $c; (No try... )
        $this->atomIs('Bitshift')
             ->hasNoTryCatch();
        $this->prepareQuery();

        $this->atomIs('Multiplication')
             ->codeIs(array('%', '/'))
             ->hasNoTryCatch();
        $this->prepareQuery();

        $this->atomFunctionIs('\\intdiv')
             ->hasNoTryCatch();
        $this->prepareQuery();
        
        // All reflection classes must catch ReflectionExpression
        $reflectionClasses = $this->loadIni('reflection.ini', 'classes');
        $reflectionFNP = makeFullNsPath($reflectionClasses);
        $this->atomIs('New')
             ->outIs('NEW')
             ->has('fullnspath', $reflectionFNP)
             ->hasNoTryCatch();
        $this->prepareQuery();

        //Phar::mungServer()
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->is('fullnspath', '\\phar')
             ->inIs('CLASS')
             ->outIs('METHOD')
             ->codeIs(array('mungserver', 'webphar'), Analyzer::TRANSLATE, Analyzer::CASE_INSENSITIVE)
             ->hasNoTryCatch()
             ->back('first');
        $this->prepareQuery();
    }
}

?>
