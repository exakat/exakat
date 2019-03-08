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


namespace Exakat\Loader;

use Exakat\Datastore;

class Collector extends Loader {
    private $cit        = array();

    private $datastore = null;

    public function __construct($gremlin, $config, \Sqlite3 $sqlite3) {
        $this->datastore = new Datastore($config);
    }
    
    public function finalize() {
        $this->datastore->addRow('ignoredCit', $this->cit);
    }

    public function saveFiles($exakatDir, $atoms, $links, $id0) { 
        foreach($atoms as $atom) {
            if (in_array($atom->atom, array('Class', 'Interface', 'Trait'))) {
                $this->cit[] = array('name'        => $atom->fullcode,
                                     'fullnspath'  => $atom->fullnspath,
                                     'fullcode'    => $atom->fullcode,
                                     'type'        => strtolower($atom->atom),
                              );
            }
        }
    }

}

?>
