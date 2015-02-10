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


namespace Analyzer\Security;

use Analyzer;

class AvoidThoseCrypto extends Analyzer\Analyzer {
    public function analyze() {
        // in hashing functions
        $this->atomFunctionIs(Analyzer\Php\HashAlgos::$functions)
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->noDelimiter(array('md2', 'md4', 'md5', 'crc32', 'crc32b', 'sha0', 'sha1'));
        $this->prepareQuery();

        // in hashing functions
        $this->atomFunctionIs(array('crypt', 'md5', 'md5_file', 'sha1_file', 'sha1', 'crc32'));
        $this->prepareQuery();
    }
}

?>
