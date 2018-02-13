<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


$git = shell_exec('git status | grep modified | grep Analyzer | grep library');

$rows = explode("\n", trim($git));

$test = '';
$total = 0;
foreach($rows as $row) {
    if (preg_match('#Analyzer/(.+)/(.+)\.php#is', $row, $r)) {
        $test .= "phpunit Test/{$r[1]}_{$r[2]}.php\n";
        ++$total;
    } else {
        var_dump($row);
    }
}

file_put_contents('tests/analyzer/phpunit.sh', $test);
print "Generated $total tests\n";

?>