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

class SafeHttpHeaders extends Analyzer {
    public function analyze() : void {
        //Some docs : https://www.keycdn.com/blog/http-security-headers

        //header('X-Xss-Protection: 0');
        $this->atomIs('String')
             ->has('noDelimiter')
             ->noDelimiterIs(array('x-xss-protection: 0', 'access-control-allow-origin: *'), self::CASE_INSENSITIVE)
             ->back('first');
        $this->prepareQuery();

        //header('X-Xss-Protection', '0');
        $this->atomIs(self::FUNCTIONS_CALLS)
             ->outIs('ARGUMENT')
             ->has('noDelimiter')
             ->noDelimiterIs(array('x-xss-protection', 'access-control-allow-origin'), self::CASE_INSENSITIVE)
             ->nextSibling('ARGUMENT')
             ->has('noDelimiter')
             ->noDelimiterIs(array('*', '0'), self::CASE_INSENSITIVE)
             ->back('first');
        $this->prepareQuery();

        //header(['X-Xss-Protectino' => '0']);
        $this->atomIs('Keyvalue')
             ->outIs('INDEX')
             ->has('noDelimiter')
             ->noDelimiterIs(array('x-xss-protection', 'access-control-allow-origin'), self::CASE_INSENSITIVE)
             ->back('first')
             ->outIs('VALUE')
             ->has('noDelimiter')
             ->noDelimiterIs(array('*', '0'), self::CASE_INSENSITIVE)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
