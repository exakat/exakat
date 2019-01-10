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

namespace Exakat\Analyzer\Security;

use Exakat\Analyzer\Analyzer;

class NoNetForXmlLoad extends Analyzer {
    public function analyze() {
        $methods = array('loadXML', 'loadHTML', 'loadHTMLFile', 'load');
    
        // $dom->loadXml($uri); // No options, so default options
        $this->atomIs('Methodcall')
             ->outIs('OBJECT')
             ->atomIsNot('This')
             ->inIs('OBJECT')
             ->outIs('METHOD')
             ->codeIs($methods)
             ->hasChildWithRank('ARGUMENT', 0)
             ->noChildWithRank('ARGUMENT', 1)
             ->back('first');
        $this->prepareQuery();

        // $dom->loadXml($uri, LIBXML_NOENT)
        $this->atomIs('Methodcall')
             ->outIs('OBJECT')
             ->atomIsNot('This')
             ->inIs('OBJECT')
             ->outIs('METHOD')
             ->codeIs($methods)
             ->outWithRank('ARGUMENT', 1)
             ->atomIsNot(self::$CONTAINERS)
             ->noAtomPropertyInside(array('Identifier', 'Nsname'), 'fullnspath', '\LIBXML_NONET')
             ->back('first');
        $this->prepareQuery();

        // simplexml_load_string($uri); // No options, so default options
        $this->atomFunctionIs(array('\\simplexml_load_string', '\\simplexml_load_file'))
             ->noChildWithRank('ARGUMENT', 2)
             ->back('first');
        $this->prepareQuery();

        // $simplexml_load_string($string, LIBXML_NOENT)
        $this->atomFunctionIs(array('\\simplexml_load_string', '\\simplexml_load_file'))
             ->outWithRank('ARGUMENT', 2)
             ->atomIsNot(self::$CONTAINERS)
             ->noAtomPropertyInside(array('Identifier', 'Nsname'), 'fullnspath', '\LIBXML_NONET')
             ->back('first');
        $this->prepareQuery();

        // new simplexml($uri); // No options, so default options
        $this->atomIs('New')
             ->outIs('NEW')
             ->atomIs('Newcall')
             ->fullnspathIs('\\simplexml')
             ->noChildWithRank('ARGUMENT', 1)
             ->back('first');
        $this->prepareQuery();

        // $simplexml_load_string($string, LIBXML_NOENT)
        $this->atomIs('New')
             ->outIs('NEW')
             ->atomIs('Newcall')
             ->fullnspathIs(array('\\simplexml', '\SimpleXMLIterator'))
             ->outWithRank('ARGUMENT', 1)
             ->atomIsNot(self::$CONTAINERS)
             ->noAtomPropertyInside(array('Identifier', 'Nsname'), 'fullnspath', '\LIBXML_NONET')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
