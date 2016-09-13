<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
$version = PHP_VERSION[0].substr(PHP_VERSION, 2,1);

print "Creating const file for call version : $version (".PHP_VERSION.")\n";

$x = get_defined_constants(true); 
if (!isset($x['tokenizer'])) { 
    $x['tokenizer'] = array(); 
}; 

$php = <<<'PHP'
<?php 
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

namespace Tasks;

PHP;

$consts = glob('library/Tokens/Const*.php');

foreach($consts as $id => $f) {
    $constFile[$id] = file_get_contents($f);
}

$total = 0;
$missing = [];
foreach($x['tokenizer'] as $name => $value) {
    foreach($constFile as $id => $c) {
        if (strpos($c, $name)  === false) {
            $missing[$consts[$id]][] = "const $name = -1;";
        }
    }

    if (substr($name, 0, 2) != 'T_') { 
        continue; 
    }
    ++$total;

    $php .= "const $name = $value;\n";
}

print_r($missing);

$php .= "\n".'?>';

print "$total constants found for PHP ".PHP_VERSION."\n";

file_put_contents('library/Tokens/Const'.$version.'.php', $php);

?>