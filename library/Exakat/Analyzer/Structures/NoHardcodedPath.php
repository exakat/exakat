<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class NoHardcodedPath extends Analyzer {
    public function analyze() {
        $functions = array('fopen', 
                           'file', 
                           'file_get_contents', 
                           'file_put_contents', 
                           'unlink',
                           'opendir', 
                           'rmdir', 
                           'mkdir',
                           );
                           //'glob',  is a special case, with wild chars

        $regexPhpProtocol = '^php://(input|output|fd|memory|filter|stdin|stdout|stderr)';
        $regexAllowedProtocol = '^(https|http|php|ssh2|ftp):\\\/\\\/';
        
        // string literal fopen('a', 'r');
        // may need some regex to exclude protocol...
        $this->atomFunctionIs($functions)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             ->tokenIs('T_CONSTANT_ENCAPSED_STRING')
             ->regexIsNot('noDelimiter', $regexPhpProtocol)
             ->regexIsNot('noDelimiter', $regexAllowedProtocol)
             ->back('first');
        $this->prepareQuery();

        $this->atomFunctionIs('\\glob')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             ->tokenIs('T_CONSTANT_ENCAPSED_STRING')
             ->regexIsNot('noDelimiter', $regexPhpProtocol)
             ->regexIsNot('noDelimiter', $regexAllowedProtocol)
             ->regexIsNot('noDelimiter', '[\\\?\\\*]')
             ->back('first');
        $this->prepareQuery();

        // string literal fopen("a$b", 'r');
        // may need some regex to exclude http...
        $this->atomFunctionIs($functions)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs(array('String', 'Concatenation'))
             ->tokenIs('T_QUOTE')
             ->outWithRank('CONCAT', 0)
             ->is('constant', true)
             ->tokenIs('T_ENCAPSED_AND_WHITESPACE')
             ->regexIsNot('noDelimiter', $regexPhpProtocol)
             ->regexIsNot('noDelimiter', $regexAllowedProtocol)
             ->back('first');
        $this->prepareQuery();

        // string literal fopen('a'.$b, 'r');
        // may need some regex to exclude http...
        $this->atomFunctionIs($functions)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('Concatenation')
             ->is('constant', true)
             ->outWithRank('CONCAT', 0)
             ->tokenIs('T_CONSTANT_ENCAPSED_STRING')
             ->regexIsNot('noDelimiter', $regexPhpProtocol)
             ->regexIsNot('noDelimiter', $regexAllowedProtocol)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
