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
$docs = glob('human/en/*/*.ini');
$clearphp = 0;
$total = 0;
foreach($docs as $iniFile) {
    ++$total;
    $ini = parse_ini_file($iniFile);
    $offset = strpos($ini['description'], '.');
    
    $clearphp += empty($ini['clearphp']);
    if (!$offset) {
        print "$iniFile is missing a sentence with a dot.\n";
        continue;
    }
    $message = substr($ini['description'], 0, $offset);

    $messageNoPHPCode = preg_replace('/<\?php.*?\?>/is', '', $ini['description']);
    $messageNoPHPCode = preg_replace('/`.*?<.*?>`_/is', '', $messageNoPHPCode);
    $words = count(explode(' ', $messageNoPHPCode));

    if (empty($ini['clearphp']) && !preg_match('/`.*?<.*?>`/is', $ini['description'])) {
//        print "$iniFile has no external link.\n";
        continue;
    }

    if ($words < 30) { 
//        print "Doc is too short in $iniFile ($words words)\n";
        
        continue; 
    }
    print $iniFile." ($words words): ".$message."\n";
}

print "\n";
print $clearphp.'/'.$total." have clearphp link\n";

?>