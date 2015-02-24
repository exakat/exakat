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


namespace Report\Format\Devoops;

class Tree extends \Report\Format\Devoops { 
    static public $tree_counter = 0;
    
    public function render($output, $data) {

        $text = <<<HTML

HTML;
        
        $output->push($text);
    }

    private function renderTreeData($data) {
/*
var tree_data = {
	'for-sale' : {name: 'For Sale', type: 'folder'}	,
	'vehicles' : {name: 'Vehicles', type: 'folder'}	,
	'rentals' : {name: 'Rentals', type: 'folder'}	,
	'real-estate' : {name: 'Real Estate', type: 'folder'}	,
	'pets' : {name: 'Pets', type: 'folder'}	,
	'tickets' : {name: 'Tickets', type: 'item'}	,
	'services' : {name: 'Services', type: 'item'}	,
	'personals' : {name: 'Personals', type: 'item'}
}

*/
        $return = "var tree_data = {\n";
        $end = '';

        foreach($data as $key => $value) {
            $id = $this->makeId($key);
            if (is_array($value)) {
                $return .= "	'$id' : {name: '$key', type: 'folder'}	,\n";
                $end .= $this->renderTreeData2($value, $key);
            } else {
                $return .= "	'$id' : {name: '$key', type: 'item'}	,\n";
            }
        }
        $return .= "}\n$end";

        return $return;
    }

    private function renderTreeData2($data, $name) {
/*

tree_data['rentals']['additionalParameters'] = {
	'children' : {
		'apartments-rentals' : {name: 'Apartments', type: 'item'},
		'office-space-rentals' : {name: 'Office Space', type: 'item'},
		'vacation-rentals' : {name: 'Vacation Rentals', type: 'item'}
	}
}

*/
        $return = "tree_data['".$this->makeId($name)."']['additionalParameters']= {\n	'children' : {\n";
        $end = '';

        foreach($data as $key => $value) {
            $id = $this->makeId($key);
                $return .= "	'$id' : {name: '$key <i class=\"".($value == 'Yes' ? 'icon-ok' : 'icon-ko')."\"></i>', type: 'item'},\n";
        }
        $return .= "	}\n}\n$end";

        return $return;
    }
    
    private function makeId($name) {
        return str_replace(' ', '-', strtolower($name));
    }
}

?>
