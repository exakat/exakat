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


$stats = array();

// check PHP
$stats['php']['version'] = phpversion();
$stats['php']['curl'] = extension_loaded('curl') ? 'Yes' : 'No';
$stats['php']['sqlite3'] = extension_loaded('sqlite3') ? 'Yes' : 'No';

// check PHP 5.2
$version = shell_exec('php52 -r "echo phpversion();" 2>&1');
if (strpos($version, 'not found') !== false) {
    $stats['PHP 5.2']['installed'] = 'No';
} else {
    $stats['PHP 5.2']['version'] = $version;
    $stats['PHP 5.2']['short_open_tags'] = shell_exec('php52 -r "echo ini_get(\'short_open_tags\') ? \'On (Should be Off)\' : \'Off\';" 2>&1');
    $stats['PHP 5.2']['timezone'] = shell_exec('php52 -r "echo ini_get(\'date.timezone\');" 2>&1');
}

// check PHP 5.3
$stats['PHP 5.3']['version'] = shell_exec('php53 -r "echo phpversion();" 2>&1');
$stats['PHP 5.3']['short_open_tags'] = shell_exec('php53 -r "echo ini_get(\'short_open_tags\') ? \'On (Should be Off)\' : \'Off\';" 2>&1');
$stats['PHP 5.3']['timezone'] = shell_exec('php53 -r "echo ini_get(\'date.timezone\');" 2>&1');

// check PHP 5.4
$stats['PHP 5.4']['version'] = shell_exec('php54 -r "echo phpversion();" 2>&1');
$stats['PHP 5.4']['short_open_tags'] = shell_exec('php54 -r "echo ini_get(\'short_open_tags\') ? \'On (Should be Off)\' : \'Off\';" 2>&1');
$stats['PHP 5.4']['timezone'] = shell_exec('php54 -r "echo ini_get(\'date.timezone\');" 2>&1');

// check PHP 5.5
$stats['PHP 5.5']['version'] = shell_exec('php55 -r "echo phpversion();" 2>&1');
$stats['PHP 5.5']['short_open_tags'] = shell_exec('php55 -r "echo ini_get(\'short_open_tags\') ? \'On (Should be Off)\' : \'Off\';" 2>&1');
$stats['PHP 5.5']['timezone'] = shell_exec('php55 -r "echo ini_get(\'date.timezone\');" 2>&1');

// check PHP 5.6
$stats['PHP 5.6']['version'] = shell_exec('php56 -r "echo phpversion();" 2>&1');
$stats['PHP 5.6']['short_open_tags'] = shell_exec('php56 -r "echo ini_get(\'short_open_tags\') ? \'On (Should be Off)\' : \'Off\';" 2>&1');
$stats['PHP 5.6']['timezone'] = shell_exec('php56 -r "echo ini_get(\'date.timezone\');" 2>&1');

// check PHP 7
$version = shell_exec('php70 -r "echo phpversion();" 2>&1');
if (strpos($version, 'not found') !== false) {
    $stats['PHP 7.0']['installed'] = 'No';
} else {
    $stats['PHP 7.0']['version'] = $version;
    $stats['PHP 7.0']['short_open_tags'] = shell_exec('php70 -r "echo ini_get(\'short_open_tags\') ? \'On (Should be Off)\' : \'Off\';" 2>&1');
    $stats['PHP 7.0']['timezone'] = shell_exec('php70 -r "echo ini_get(\'date.timezone\');" 2>&1');
}

// wkhtmltopdf
$res = shell_exec('wkhtmltopdf --version 2>&1');
if (preg_match('/command not found/is', $res)) {
    $stats['wkhtmltopdf']['installed'] = 'No';
} elseif (preg_match('/wkhtmltopdf\s+([0-9\.]+)/is', $res, $r)) {
    $stats['wkhtmltopdf']['installed'] = 'Yes';
    $stats['wkhtmltopdf']['version'] = $r[1];
} else {
    $stats['wkhtmltopdf']['error'] = $res;
}

// zip
$res = shell_exec('zip -v');
if (preg_match('/command not found/is', $res)) {
    $stats['zip']['installed'] = 'No';
} elseif (preg_match('/Zip\s+([0-9\.]+)/is', $res, $r)) {
    $stats['zip']['installed'] = 'Yes';
    $stats['zip']['version'] = $r[1];
} else {
    $stats['zip']['error'] = $res;
}

// phploc
$res = shell_exec('phploc --version 2>&1');
if (preg_match('/command not found/is', $res)) {
    $stats['phploc']['installed'] = 'No';
} elseif (preg_match('/phploc\s+([0-9\.]+)/is', $res, $r)) {
    $stats['phploc']['installed'] = 'Yes';
    $stats['phploc']['version'] = $r[1];
} else {
    $stats['phploc']['error'] = $res;
}

// phpunit
$res = shell_exec('phpunit --version 2>&1');
if (preg_match('/command not found/is', $res)) {
    $stats['phpunit']['installed'] = 'No';
} elseif (preg_match('/PHPUnit\s+([0-9\.]+)/is', $res, $r)) {
    $stats['phpunit']['installed'] = 'Yes';
    $stats['phpunit']['version'] = $r[1];
} else {
    $stats['phpunit']['error'] = $res;
}

// java
$res = shell_exec('java -version 2>/tmp/javaversion.txt; cat /tmp/javaversion.txt; rm /tmp/javaversion.txt');
if (preg_match('/command not found/is', $res)) {
    $stats['java']['installed'] = 'No';
} elseif (preg_match('/java version "(.*)"/is', $res, $r)) {
    $stats['java']['installed'] = 'Yes';
    $stats['java']['version'] = $r[1];
} else {
    $stats['java']['error'] = $res;
}

// neo4j
if (!file_exists('neo4j')) {
    $stats['neo4j']['installed'] = 'No';
} else {
    $file = file('neo4j/README.txt');
    $stats['neo4j']['version'] = trim($file[0]);

    $file = file_get_contents('neo4j/conf/neo4j-wrapper.conf');
    if (!preg_match('/wrapper.java.additional=-XX:MaxPermSize=(\d+\w)/is', $file, $r)) {
        $stats['neo4j']['MaxPermSize'] = 'Unset (64M)';
    } else {
        $stats['neo4j']['MaxPermSize'] = $r[1];
    }

    $file = file_get_contents('neo4j/conf/neo4j-server.properties');
    if (!preg_match('/org.neo4j.server.webserver.port=(\d+)/is', $file, $r)) {
        $stats['neo4j']['port'] = 'Unset (7474)';
    } else {
        $stats['neo4j']['port'] = $r[1];
    }
    
    $json = file_get_contents('http://127.0.0.1:7474/db/data/');
    $json = json_decode($json);
    if (isset($json->extensions->GremlinPlugin)) {
        $stats['neo4j']['gremlin'] = 'Yes';
        $stats['neo4j']['gremlin-url'] = $json->extensions->GremlinPlugin->execute_script;
    } else {
        $stats['neo4j']['gremlin'] = 'No';
    }
}

// batch-importer
if (!file_exists('batch-import')) {
    $stats['batch-import']['installed'] = 'No';
} else {
    if (!file_exists('./batch-import/target/batch-import-jar-with-dependencies.jar')) {
        $stats['batch-import']['compiled'] = 'No';
        // compile with "mvn clean compile assembly:single"
    } else {
        $stats['batch-import']['installed'] = 'Yes';
        $stats['batch-import']['compiled'] = 'Yes';
    
        $file = file('batch-import/changelog.txt');
        $stats['batch-import']['version'] = trim($file[0]);
    }
    
    if (!file_exists('./batch-import/sampleme/')) {
        $stats['batch-import']['sampleme'] = 'No';
    } else {
        $stats['batch-import']['sampleme'] = 'Yes';
    }
    
    $res = explode("\n", shell_exec('mvn -v 2>&1'));
    $stats['batch-import']['maven'] = trim($res[0]);
}

// screen
$res = shell_exec('screen -v');
if (preg_match('/Screen version (\d+.\d+.\d+)/is', $res, $r)) {
    $stats['screen']['installed'] = 'Yes';
    $stats['screen']['version'] = $r[1];
} else {
    $stats['screen']['installed'] = 'No';
}

// projects
if (!file_exists('./projects/')) {
    $stats['projects']['created'] = 'Yes';
} else {
    $stats['projects']['created'] = 'No';
}

// projects
if (!file_exists('./log/')) {
    $stats['log']['created'] = 'Yes';
} else {
    $stats['log']['created'] = 'No';
}

// composer
$res = trim(shell_exec('composer about --version'));
// remove colors from shell syntax
$res = preg_replace('/\e\[[\d;]*m/', '', $res);
if (preg_match('/ version ([0-9\.a-z\-]+)/', $res, $r)) {//
    $stats['composer']['installed'] = 'Yes';
    $stats['composer']['version'] = $r[1];
} else {
    $stats['composer']['installed'] = 'No';
}

// svn
$res = trim(shell_exec('svn --version'));
if (preg_match('/svn, version ([0-9\.]+) /', $res, $r)) {//
    $stats['svn']['installed'] = 'Yes';
    $stats['svn']['version'] = $r[1];
} else {
    $stats['svn']['installed'] = 'No';
}

// hg
$res = trim(shell_exec('hg --version'));
if (preg_match('/Mercurial Distributed SCM \(version ([0-9\.]+)\)/', $res, $r)) {//
    $stats['hg']['installed'] = 'Yes';
    $stats['hg']['version'] = $r[1];
} else {
    $stats['hg']['installed'] = 'No';
}


foreach($stats as $section => $details) {
    print "$section : \n";
    foreach($details as $k => $v) {
        print '    '.$k.' : '.$v."\n";
    }
    print "\n";
}

?>