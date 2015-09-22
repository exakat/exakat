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


namespace Report\Content;

class ExternalLibraries extends \Report\Content {
    protected $name = 'External libraries';
    
    public function collect() {
        $config = \Config::factory();
        $datastore = new \Datastore($config);
        
        $externallibraries = $this->loadJson('externallibraries');
        
        $this->array = $datastore->getRow('externallibraries');
        foreach($this->array as &$row) {
            unset($row['id']);
            $row = [$row['library'], $row['file'], "<a href=\"".$externallibraries->{strtolower($row['library'])}->homepage."\">".$row['library']." <i class=\"fa fa-sign-out\"></i></a>"];
        }
        unset($row);

        $this->hasResults = (boolean) count($this->array);
    }
}

?>
