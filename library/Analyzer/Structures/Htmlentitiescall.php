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


namespace Analyzer\Structures;

use Analyzer;

class Htmlentitiescall extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING','T_NS_SEPARATOR'))
             ->fullnspath(array('\\htmlentities', '\\htmlspecialchars'))
             ->outIs('ARGUMENTS')
             ->noChildWithRank('ARGUMENT', 1)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING','T_NS_SEPARATOR'))
             ->atomIsNot('Analyzer\\\\Structures\\\\Htmlentitiescall')
             ->fullnspath(array('\\htmlentities', '\\htmlspecialchars'))
             ->outIs('ARGUMENTS')
             ->noChildWithRank('ARGUMENT', 2)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING','T_NS_SEPARATOR'))
             ->atomIsNot('Analyzer\\\\Structures\\\\Htmlentitiescall')
             ->fullnspath(array('\\htmlentities', '\\htmlspecialchars'))
             ->outIs('ARGUMENTS')
             ->rankIs('ARGUMENT', 1)
             ->codeIsNot(array('ENT_COMPAT', 'ENT_QUOTES', 'ENT_NOQUOTES', 'ENT_IGNORE', 'ENT_SUBSTITUTE', 'ENT_DISALLOWED', 'ENT_HTML401', 'ENT_XML1', 'ENT_XHTML', 'ENT_HTML5'), true)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING','T_NS_SEPARATOR'))
             ->atomIsNot('Analyzer\\\\Structures\\\\Htmlentitiescall')
             ->fullnspath(array('\\htmlentities', '\\htmlspecialchars'))
             ->outIs('ARGUMENTS')
             ->rankIs('ARGUMENT', 2)
             ->atomIs('String')
             ->noDelimiterIsNot(array('ISO-8859-1', 'ISO8859-1', 'ISO-8859-5', 'ISO8859-5', 'ISO-8859-15', 'ISO8859-15', 'UTF-8', 'cp866', 
                                      'ibm866', '866', 'cp1251', 'Windows-1251', 'win-1251', '1251', 'cp1252', 'Windows-1252', '1252', 'KOI8-R', 
                                      'koi8-ru', 'koi8r', 'BIG5', '950', 'GB2312', '936', 'BIG5-HKSCS', 'Shift_JIS', 'SJIS', 'SJIS-win', 'cp932', 
                                      '932', 'EUC-JP', 'EUCJP', 'eucJP-win', 'MacRoman', ''), true)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
