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


namespace Report\Content\Directives;

use Everyman\Neo4j\Client;

class Intl extends Directives {
    function __construct(Client $neo4j) {
        parent::__construct($neo4j);
        $this->name         = 'Intl';

        if ($this->checkPresence('Extensions\\Extintl')) {
            $this->directives[] = array('name' => 'intl.default_locale',
                                        'suggested' => '<Your ICU Locale>',
                                        'documentation' => 'The locale that will be used in intl functions when none is specified (either by omitting the corresponding argument or by passing NULL). These are ICU locales, not system locales. ');

            $this->directives[] = array('name' => 'intl.error_level',
                                        'suggested' => 'E_WARNING',
                                        'documentation' => 'The level of the error messages generated when an error occurs in ICU functions. This is a PHP error level, such as E_WARNING. It can be set to 0 in order to inhibit the messages. This does not affect the return values indicating error or the values returned by intl_get_error_code() or by the class specific methods for retrieving error codes and messages. Choosing E_ERROR will terminate the script whenever an error condition is found on intl classes.');

            $this->directives[] = array('name' => 'intl.use_exceptions',
                                        'suggested' => 'false',
                                        'documentation' => 'If set to true, an exception will be raised whenever an error occurs in an intl function. The exception will be of type IntlException. This is possibly in addition to the error message generated due to intl.error_level.');

            $this->directives[] = $this->extraConfiguration($this->name, 'intl');
        }
    }
}

?>