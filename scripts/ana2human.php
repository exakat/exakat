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


include(dirname(__DIR__).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_library');

$analyzers = glob('library/Analyzer/*/*');

$documented = 0;
foreach($analyzers as $analyzer) {
    $analyzer = substr($analyzer, 8, -4);
    $analyzer = str_replace('/', '\\', $analyzer);
    if (strpos($analyzer, "\\Common\\" ) !== false) {
        continue;
    }
    $x = new $analyzer(null);
    if ( $x->getDescription() == '' ) {
        print "$analyzer has no human version\n";
    } else {
        $documented++;
    }
}

print "\n\n";
print count($analyzers)." analyzers\n";
print "$documented analyzers are documented ( ".number_format($documented / count($analyzers) * 100, 2)." % )\n";


?>