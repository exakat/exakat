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

namespace Exakat\Analyzer\Portability;

use Exakat\Analyzer\Analyzer;

class WindowsOnlyConstants extends Analyzer {
    public function analyze() {
        $constants = array( 'PHP_WINDOWS_VERSION_MAJOR',
                            'PHP_WINDOWS_VERSION_MINOR',
                            'PHP_WINDOWS_VERSION_BUILD',
                            'PHP_WINDOWS_VERSION_PLATFORM',
                            'PHP_WINDOWS_VERSION_SP_MAJOR',
                            'PHP_WINDOWS_VERSION_SP_MINOR',
                            'PHP_WINDOWS_VERSION_SUITEMASK',
                            'PHP_WINDOWS_VERSION_PRODUCTTYPE',
                            'PHP_WINDOWS_NT_DOMAIN_CONTROLLER',
                            'PHP_WINDOWS_NT_SERVER',
                            'PHP_WINDOWS_NT_WORKSTATION',
                );
        
        $fnp = makeFullnspath($constants, \FNP_CONSTANT);
        
        $this->atomIs(array('Identifier', 'Nsname'))
             ->fullnspathIs($fnp, Analyzer::CASE_SENSITIVE);
        $this->prepareQuery();
    }
}

?>
