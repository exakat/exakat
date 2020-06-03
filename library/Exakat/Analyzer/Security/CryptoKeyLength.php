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

namespace Exakat\Analyzer\Security;

use Exakat\Analyzer\Analyzer;

class CryptoKeyLength extends Analyzer {
    public function analyze() {
        $lengths = array('\\OPENSSL_KEYTYPE_RSA' => 3072,
                         '\\OPENSSL_KEYTYPE_DSA' => 2048,
                         '\\OPENSSL_KEYTYPE_DH'  => 2048,
                         '\\OPENSSL_KEYTYPE_EC'  => 512,
                        );

        // to be used with openssl_pkey_new and openssl_csr_new
    /*
      const CERT_CONFIG = [
        "private_key_bits" => 4096,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    */
        $this->atomIs('Arrayliteral')

             ->outIs('ARGUMENT')
             ->atomIs('Keyvalue')
             ->outIs('INDEX')
             ->noDelimiterIs('private_key_type', self::WITH_CONSTANTS)
             ->inIs('INDEX')

             ->outIs('VALUE')
             ->fullnspathIs(array_keys($lengths), self::CASE_SENSITIVE)
             ->savePropertyAs('fullnspath', 'fqn')
             ->back('first')

             ->outIs('ARGUMENT')
             ->atomIs('Keyvalue')
             ->outIs('INDEX')
             ->noDelimiterIs('private_key_bits', self::WITH_CONSTANTS)
             ->inIs('INDEX')

             ->outIs('VALUE')
             ->isLessHash('intval', $lengths, 'fqn')

             ->back('first');
        $this->prepareQuery();


    /*
      class x {
        private $private_key_bits" = 4096;
        private $private_key_type" = OPENSSL_KEYTYPE_RSA;
      }
    */
        $this->atomIs(self::CLASSES_TRAITS)

             ->outIs('PPP')
             ->outIs('PPP')
             ->atomIs('Propertydefinition')
             ->codeIs('$private_key_type')
             ->outIs('DEFAULT')
             // hardcoded default, or dynamic, but only openssl constants
             ->fullnspathIs(array_keys($lengths), self::CASE_SENSITIVE)
             ->savePropertyAs('fullnspath', 'fqn')
             ->back('first')

             ->outIs('PPP')
             ->outIs('PPP')
             ->atomIs('Propertydefinition')
             ->codeIs('$private_key_bits')
             ->outIs('DEFAULT')
             ->isLessHash('intval', $lengths, 'fqn')

             ->back('first');
        $this->prepareQuery();
    }
}

?>
