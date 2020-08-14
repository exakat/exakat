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

class KeepFilesRestricted extends Analyzer {
    protected $filePrivileges = array(0777);

    public function analyze(): void {
        if (is_string($this->filePrivileges)) {
            $this->filePrivileges = str2array($this->filePrivileges);
            // todo : interpret values from ini : 0777 will be 777, not 0777;
        }

        // chmod($file, 0777);
        $this->atomFunctionIs(array('\\chmod', '\\mkdir'))
             ->outWithRank('ARGUMENT', 1)
             ->atomIs('Integer', self::WITH_CONSTANTS)
             ->raw('filter{ x = ' . implode(', ', $this->filePrivileges) . '; (it.get().value("intval") & 0777) in x;}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
