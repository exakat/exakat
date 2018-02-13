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


namespace Exakat\Analyzer\Security;

use Exakat\Analyzer\Analyzer;

class CompareHash extends Analyzer {
    public function analyze() {
        // md5() == something
        $this->atomIs('Comparison')
             ->codeIs(array('==', '!='))
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs('Functioncall')
             ->codeIs(array('hash', 'md5', 'sha1', 'md5_file', 'sha1_file', 'crc32','crypt'))
             ->back('first');
        $this->prepareQuery();

        // if (hash())
        $this->atomIs('Ifthen')
             ->outIs('CONDITION')
             ->atomIs('Functioncall')
             ->codeIs(array('hash', 'md5', 'sha1', 'md5_file', 'sha1_file', 'crc32','crypt'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
