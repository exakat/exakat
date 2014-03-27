<?php

$stats = array();

// check PHP
$stats['php']['version'] = phpversion();
$stats['php']['curl'] = extension_loaded('curl') ? 'Yes' : 'No';

// check PHP 5.2
$stats['PHP 5.2']['version'] = shell_exec('php52 -r "echo phpversion();" 2>&1');

// check PHP 5.3
$stats['PHP 5.3']['version'] = shell_exec('php53 -r "echo phpversion();" 2>&1');

// check PHP 5.4
$stats['PHP 5.4']['version'] = shell_exec('php54 -r "echo phpversion();" 2>&1');

// check PHP 5.5
$stats['PHP 5.5']['version'] = shell_exec('php55 -r "echo phpversion();" 2>&1');

// check PHP 5.6
$stats['PHP 5.6']['version'] = shell_exec('php56 -r "echo phpversion();" 2>&1');

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
}

// batch-importer
if (!file_exists('batch-import')) {
    $stats['batch-import']['installed'] = 'No';
} else {
    if (!file_exists('batch-import/target/batch-import-jar-with-dependencies.jar')) {
        $stats['batch-import']['compiled'] = 'No';
        // compile with "mvn clean compile assembly:single"
    } else {
        $stats['batch-import']['installed'] = 'No';
        $stats['batch-import']['compiled'] = 'Yes';
    
        $file = file('batch-import/changelog.txt');
        $stats['batch-import']['version'] = trim($file[0]);
    }
    
    $res = split("\n", shell_exec('mvn -v 2>&1'));
    $stats['batch-import']['maven'] = trim($res[0]);
    
    
}

foreach($stats as $section => $details) {
    print "$section : \n";
    foreach($details as $k => $v) {
        print "    ".$k." : ".$v."\n";
    }
    print "\n";
}

?>