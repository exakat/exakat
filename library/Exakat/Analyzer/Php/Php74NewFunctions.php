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


namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Common\FunctionDefinition;

class Php74NewFunctions extends FunctionDefinition {
    public function analyze(): void {
        $this->functions = array('mb_str_split',
                                 'password_algos',
                                 'get_mangled_object_vars',
                                 'openssl_x509_verify',
                                 'pcntl_unshare',
                                 'chroot',
                                 'sapi_windows_set_ctrl_handler',
                                 'sapi_windows_generate_ctrl_event',
                                 'fn', // fn is not a function, but a reserved keyword
                                );
        parent::analyze();
    }
}

?>
