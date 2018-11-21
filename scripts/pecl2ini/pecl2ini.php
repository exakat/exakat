<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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


$pecl = $argv[1];

print "Running for '$pecl'\n";
$http = file_get_contents('http://pecl.php.net/package/'.$pecl);
if (empty($http)) {
    print "Can't load http://pecl.php.net/package/$pecl. Aborting\n";
    die();
}
preg_match_all('#href="(/get/('.$pecl.'-[0-9RC\.]+\.tgz))"#is', $http, $versions);

//print_r($versions);
$version = $versions[2][0];

if (!file_exists($version)) {
    file_put_contents($version, file_get_contents("http://pecl.php.net".$versions[1][0]));
    if (!file_exists($version)) {
        print "Can't load source code for {$versions[1][0]}. Aborting\n";
        die();
    }
} else {
    print "$version est deja ici\n";
}

shell_exec('tar -xf '.$version);

// functions
$res = shell_exec('grep -r PHP_FUNCTION '.substr($version, 0, -4));
if (empty($res)) {
    $classes = array(); 
    print "No functions\n\n";
} else {
    preg_match_all('/PHP_FUNCTION\(([a-z0-9_]+)\)/is', $res, $functions);
    $functions = $functions[1];
    print count($functions)." functions\n  ";
    print implode("\n  ", $functions);
    print "\n\n";
}

// functions
$res = shell_exec('grep -r INIT_CLASS_ENTRY '.substr($version, 0, -4));
if (empty($res)) {
    $res = shell_exec('grep -r PHP_EVENT_REGISTER_CLASS '.substr($version, 0, -4));
    if (empty($res)) {
        $classes = array(); 
        print "No classes\n\n";
    } else {
        preg_match_all('/PHP_EVENT_REGISTER_CLASS\("([a-z0-9_]+)", /is', $res, $classes);
        $classes = $classes[1];
        print count($classes)." classes\n  ";
        print implode("\n  ", $classes);
        print "\n\n";
    
    }
} elseif (preg_match_all('/INIT_CLASS_ENTRY\([a-z_]+, "([a-zA-Z0-9_]+)"/is', $res, $classes)) {
    $classes = $classes[1];
    print count($classes)." classes\n  "; 
    print implode("\n  ", $classes);
    print "\n\n";
} elseif (preg_match_all('/INIT_CLASS_ENTRY\([a-z_]+,\s*([a-zA-Z0-9_]+)/is', $res, $classesDefine)) {
    $classesDefine = $classesDefine[1];
    $res = shell_exec('grep -r "('.implode('\\|', $classesDefine).')" '.substr($version, 0, -4));
    preg_match_all("/(".implode('|', $classesDefine).") \"([a-zA-Z0-9_]+)\"/is", $res, $classes);
    $classes = $classes[2];
    print count($classes)." classes\n  ";
    print implode("\n  ", $classes);
    print "\n\n";
} else {
    print "Nothing found, but having res : $res\n";
}

// constants
$res = shell_exec('grep -r REGISTER_CAIRO_STATUS_LONG_CONST '.substr($version, 0, -4));
if (empty($res)) {
$res = shell_exec('grep -r REGISTER_LONG_CONSTANT '.substr($version, 0, -4));
    if (empty($res)) {  
        $constants = array(); 
        print "No constants\n\n";
    } else {
        preg_match_all('/REGISTER_LONG_CONSTANT\(\s*"([a-zA-Z0-9_]+)"/is', $res, $constants);
        $constants = $constants[1];
        print count($constants)." constants\n  ";
        print implode("\n  ", $constants);
        print "\n\n";
    }
} else {
   preg_match_all('/REGISTER_CAIRO_STATUS_LONG_CONST\(\".*?\",\s*([a-zA-Z0-9_]+)/is', $res, $constants);
   $constants = $constants[1];
   print count($constants)." constants\n  ";
   print implode("\n  ", $constants);
   print "\n\n";
}

print_r($functions);
print_r($classes);
print_r($constants);

// interfaces ? Namespaces ? error messages ? 
?>