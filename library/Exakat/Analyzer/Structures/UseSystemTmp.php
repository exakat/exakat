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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class UseSystemTmp extends Analyzer {
    public function analyze(): void {
        $functions = array('\\glob',
                           '\\fopen',
                           '\\file',
                           '\\file_get_contents',
                           '\\file_put_contents',
                           '\\unlink',
                           '\\opendir',
                           '\\rmdir',
                           '\\mkdir',
                           );
        $regexStartWithTmp = '^(/tmp/|C:\\\\\\\\WINDOWS\\\\\\\\TEMP|C:\\\\\\\\WINDOWS)';

        // string literal fopen('a', 'r');
        $this->atomFunctionIs($functions)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             ->regexIs('noDelimiter', $regexStartWithTmp)
             ->back('first');
        $this->prepareQuery();

        // string literal fopen("a$b", 'r');
        // may need some regex to exclude http...
        $this->atomFunctionIs($functions)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             ->is('constant', true)
             ->outWithRank('CONCAT', 0)
             ->regexIs('noDelimiter', $regexStartWithTmp)
             ->back('first');
        $this->prepareQuery();

        // string literal fopen('a'.$b, 'r');
        // may need some regex to exclude http...
        $this->atomFunctionIs($functions)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('Concatenation')
             ->is('constant', true)
             ->outWithRank('CONCAT', 0)
             ->regexIs('noDelimiter', $regexStartWithTmp)
             ->back('first');
        $this->prepareQuery();
    }
}

?>