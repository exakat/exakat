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


$projects = glob('projects/*');
$projects = array_map('basename', $projects);

unlink('mergedReport.sqlite');
$db = new SQLite3('mergedReport.sqlite');

$results = $db->query('CREATE TABLE reports (id INTEGER PRIMARY KEY AUTOINCREMENT, project TEXT, analyzer TEXT, value TEXT, count INT)');

foreach($projects as $project) {
    print "$project\n";
    
    if ($project == 'default') { continue; }
    if ($project == 'tests') { continue; }
    if ($project == 'progress.log') { continue; }
    $path = dirname(__DIR__).'/projects/'.$project.'/report.sqlite';
    if (!file_exists($path)) {
        print "No report for '$project'\n";
        continue;
    }

    $db->query("ATTACH '$path' AS toMerge;");

    $db->query("INSERT INTO reports SELECT null, '$project', analyzer, value, count FROM toMerge.reports; ");

    $db->query("DETACH toMerge;");
}

$results = $db->query('CREATE TABLE stats (id INTEGER PRIMARY KEY AUTOINCREMENT, analyzer TEXT, total INT, count INT)');
print $db->lastErrorMsg();

$exts = array('mcrypt', 'kdm5', 'bzip2', 'bcmath', 'pcre', 'mysqli', 'calendar', 'ctype', 'curl', 'dom', 'ssh2', 
              'ereg', 'ftp', 'fileinfo', 'filter', 'exif', 'pgsql', 'enchant', 'sqlite3', 'gd', 'gmp', 'hash', 
              'iconv', 'json', 'ldap', 'libxml', 'mysql', 'odbc', 'openssl', 'sqlite', 'xdebug',);
foreach($exts as $ext) {
    $results = $db->query('INSERT INTO stats SELECT NULL, "'.$ext.'", COUNT(*), SUM(CASE count WHEN "Yes" THEN 1 ELSE 0 END) FROM reports WHERE analyzer="Appinfo" AND value="ext/'.$ext.'" ');
}

$results = $db->query('INSERT INTO stats SELECT NULL, value, COUNT(project), COUNT(project) FROM reports WHERE analyzer="Analyzer\\Type\\UnicodeBlock" GROUP BY value ');

?>