<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class HardcodedPasswords extends Analyzer {
    public function analyze() {
        // Position is 0 based
        $passwords = array(
                           'mysql_connect'            => 2,
                           'mysqli_connect'           => 2,
                           'ftp_login'                => 2,
                           'mssql_connect'            => 2,
                           'oci_connect'              => 1,
                           'imap_open'                => 2,
                           'cyrus_authenticate'       => 7,
                           'ssh2_auth_password'       => 1,
                           'hash_hmac'                => 2,
                           'hash_hmac_file'           => 2,
                           'hash_pbkdf2'              => 1,
                           'kadm5_create_principal'   => 2,
                           'kadm5_chpass_principal'   => 2,
                           'kadm5_init_with_password' => 3,
                           );
        
        $positions = array();
        foreach($passwords as $function => $position) {
            if (isset($positions[$position])) {
                $positions[$position][] = '\\'.$function;
            } else {
                $positions[$position] = array('\\'.$function);
            }
        }

        foreach($positions as $position => $function) {
            $this->atomFunctionIs($function)
                 ->outWithRank('ARGUMENT', $position)
                 ->atomIs('String')
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
