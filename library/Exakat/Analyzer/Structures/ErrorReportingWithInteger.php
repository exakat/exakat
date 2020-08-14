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
declare(strict_types = 1);

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class ErrorReportingWithInteger extends Analyzer {
    public function analyze(): void {
        $allowedIntegers = array('-1', '0');

        $this->atomFunctionIs('\\error_reporting')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs(array('Integer', 'Addition'))
             ->codeIsNot($allowedIntegers)
             ->back('first');
        $this->prepareQuery();

        $this->atomFunctionIs('\\ini_set')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             ->tokenIsNot('T_QUOTE')
             ->noDelimiterIs('error_reporting')
             ->inIs('ARGUMENT')
             ->outWithRank('ARGUMENT', 1)
             ->atomIs('Integer')
             ->codeIsNot('0')
             ->codeIsNot($allowedIntegers)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
