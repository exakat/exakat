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


namespace Exakat\Loader;

use Exakat\Tasks\Helpers\Atom;

class Collector extends Loader {
    private $cit        = array();
    private $functions  = array();
    private $constants  = array();

    private $datastore  = null;

    public function __construct(\Sqlite3 $sqlite3, Atom $id0) {
        $this->datastore = exakat('datastore');
    }

    public function finalize(array $relicat): bool {
        $this->datastore->addRow('ignoredCit',       $this->cit);
        $this->datastore->addRow('ignoredFunctions', $this->functions);
        $this->datastore->addRow('ignoredConstants', $this->constants);

        return true;
    }

    public function saveFiles(string $exakatDir, array $atoms, array $links): void {
        $isDefine = false;

        $lastConst = array();
        foreach($atoms as $atom) {
            if (in_array($atom->atom, array('Class', 'Interface', 'Trait'))) {
                $this->cit[] = array('name'        => $atom->fullcode,
                                     'fullnspath'  => $atom->fullnspath,
                                     'fullcode'    => $atom->fullcode,
                                     'type'        => strtolower($atom->atom),
                              );
                continue;
            }

            if (in_array($atom->atom, array('Function'))) {
                $this->functions[] = array('name'        => $atom->fullcode,
                                           'fullnspath'  => $atom->fullnspath,
                                           'fullcode'    => $atom->fullcode
                              );
                continue;
            }

            if (in_array($atom->atom, array('Identifier'))) {
                if ($isDefine === true) {
                    $this->constants[] = array('name'        => $atom->fullcode,
                                               'fullnspath'  => $atom->fullnspath,
                                               'fullcode'    => $atom->fullcode,
                                               'value'       => strtolower($atom->atom),
                                          );
                    $isDefine = false;
                } else {
                    $lastConst = array('name'        => $atom->fullcode,
                                       'fullnspath'  => $atom->fullnspath,
                                       'fullcode'    => $atom->fullcode,
                                       'value'       => strtolower($atom->atom),
                                  );
                }
                continue;
            }

            if (in_array($atom->atom, array('Constant'))) {
                if (!empty($lastConst)) {
                    $this->constants[] = $lastConst;
                }
                $lastConst = array();
                continue;
            }

            if (in_array($atom->atom, array('Defineconstant'))) {
                $isDefine = true;
            }
        }
    }
}

?>
