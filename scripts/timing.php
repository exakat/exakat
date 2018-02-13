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


include '/Users/famille/.composer/vendor/autoload.php';

use mcordingley\Regression\Regression;
use mcordingley\Regression\RegressionAlgorithm\LinearLeastSquares;


$files = glob('projects/*');
$fp = fopen('timing.csv', 'w+');
if ($fp === false) {
    die('Could not open timing.csv');
}
fputcsv($fp, array('project', 'initialSize', 'size', 'buildRoot', 'tokenizer', 'analyze', 'final'));

$total = 0;
//$regression = new Regression(new LinearLeastSquares);
$regression = new Regression();


foreach($files as $id => $file) {
    unset($project, $initialSize, $size, $buildRoot, $tokenizer, $analyze, $final);
    list(, $project) = explode('/', $file);

    if (in_array($project, array('test'))) { continue; }
    
    if (!file_exists("projects/$project/log/project.timing.csv")) { 
        print "$project\n";
        continue; 
    }
    $content = file_get_contents("projects/$project/log/project.timing.csv");

    if (!preg_match("#Tokenizer\t([0-9\.]+)#is", $content, $r)) { continue; }
    $tokenizer = $r[1];
    if (!preg_match("#Analyze\t([0-9\.]+)#is", $content, $r)) { continue; }
    $analyze = $r[1];
    if (!preg_match("#Build_root\t([0-9\.]+)#is", $content, $r)) { continue; }
    $buildRoot = $r[1];
    if (!preg_match("#Final\t([0-9\.]+)\t([0-9\.]+)#is", $content, $r)) { continue; }
    $final = $r[2];

    
    if (!file_exists("projects/$project/log/stat.log")) { continue; }
    $content = file_get_contents("projects/$project/log/stat.log");
    if (!preg_match("#tokens_count : (\d+)#is", $content, $r)) { continue; }
    $size = (int) $r[1];

    if (!file_exists("projects/$project/datastore.sqlite")) { continue; }
    $sqlite = new sqlite3("projects/$project/datastore.sqlite");
    $res = $sqlite->query('SELECT * FROM hash WHERE key = "tokens"');
    $initialSize = $res->fetchArray()['value'];
    
    if ($initialSize == 0) {
        print "php exakat project -v -p $project\n";
        continue;
    }

    $res = array($project, $initialSize, $size, $buildRoot, $tokenizer, $analyze, $final);
    $regression->addData(floor($final), array(floor($size)));
    
    if ($initialSize < $size) {
        print "Tokens grown : $project\n";
    }
    
    fputcsv($fp, $res);
    ++$total;
}

print "Did $total files\n";

//        print "predict : ".."\n";
        $coefficients = $regression->getCoefficients();
        print_r($coefficients);
        $intercept = round($regression->predict(array(0)), 2);
        print "$intercept\n";

?>