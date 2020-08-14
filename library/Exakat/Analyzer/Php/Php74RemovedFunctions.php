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

use Exakat\Analyzer\Common\PhpFunctionUsage;

class Php74RemovedFunctions extends PhpFunctionUsage {
    public function analyze(): void {
        $this->functions = array('hebrevc',
                                 'convert_cyr_string',
                                 'ezmlm_hash',
                                 'money_format',
                                 'restore_include_path',
                                 'get_magic_quotes_runtime',
                                 'get_magic_quotes',
                                );
        parent::analyze();
    }
}

?>
