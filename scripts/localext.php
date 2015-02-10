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


$coded_ext = glob('library/Analyzer/Extensions/*.php');
foreach($coded_ext as $k => $v) {
    $coded_ext[$k] = strtolower(substr(basename($v), 3, -4));
}

$ext = get_loaded_extensions();
foreach($ext as $k => $v) {
    $ext[$k] = strtolower($v);
}

$diff = array_diff($ext, $coded_ext);
print_r($diff);
print count($diff)." missing\n";

//print_r(get_extension_funcs("crypto"));
//print_r(get_defined_constants());
//print_r(get_declared_classes ());


?>