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


namespace Analyzer\Functions;

use Analyzer;

class HardcodedPasswords extends Analyzer\Analyzer {
    public function analyze() {
        $passwords = array(
                           'mysql_connect'  => 3,
                           'mysqli_connect' => 3,
                           'ftp_login'      => 3,
                           'mssql_connect'  => 3,
                           'oci_connect'    => 2,
                           'imap_open'      => 3,
                           'cyrus_authenticate' => 8,
                           );
        
        $positions = array();
        foreach($passwords as $function => $position) {
            if (isset($positions[$position - 1])) {
                $positions[$position - 1][] = '\\'.$function;
            } else {
                $positions[$position - 1] = array('\\'.$function);
            }
        }

        foreach($positions as $position => $function) {
            $this->atomIs('Functioncall')
                 ->hasNoIn('METHOD')
                 ->tokenIs(array('T_STRING','T_NS_SEPARATOR'))
                 ->fullnspath($function)
                 ->outIs('ARGUMENTS')
                 ->outIs('ARGUMENT')
                 ->hasRank($position)
                 ->atomIs('String')
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
