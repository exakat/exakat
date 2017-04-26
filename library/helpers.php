<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


function display($text) {
    $config = \Exakat\Config::factory();

    if ($config->verbose) {
        echo trim($text), "\n";
    }
}

function display_r($object) {
    static $config;

    if ($config === null) {
        $config = \Exakat\Config::factory();
    }

    if ($config->verbose) {
        print_r( $object );
    }
}

function rmdirRecursive($dir) {
    if (!file_exists($dir)) {
        // Do nothing
        return 0;
    }

    // Remove symlink, but not their content
    if (is_link($dir)) {
        unlink($dir);
        return 0;
    }

    if (empty($dir)) {
        return 0;
    }

    $total = 0;
    $files = array_diff(scandir($dir), array('.','..'));

    foreach ($files as $file) {
        $path = $dir.'/'.$file;
        if (is_dir($path)) {
            $total += rmdirRecursive($path);
        } else {
            unlink($path);
            ++$total;
        }
    }

    rmdir($dir);
    ++$total;

    return $total;
}

function copyDir($src, $dst) {
    if (!file_exists($src)) {
        throw new \Exakat\Exceptions\NoSuchDir('Can\'t find dir : "'.$src.'"');
    }
    $dir = opendir($src);
    if (!$dir) {
        throw new \Exakat\Exceptions\NoSuchDir('Can\'t open dir : "'.$src.'"');
    }

    $total = 0;
    mkdir($dst, 0755);
    while(false !==  $file = readdir($dir) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src.'/'.$file) ) {
                $total += copyDir($src.'/'.$file,$dst.'/'.$file);
            } else {
                copy($src.'/'.$file, $dst.'/'.$file);
                ++$total;
            }
        }
    }

    closedir($dir);

    return $total;
}

function rglob($pattern, $flags = 0) {
    $files = glob($pattern.'/*', $flags);
    $dirs  = glob($pattern.'/*', GLOB_ONLYDIR | GLOB_NOSORT);
    $files = array_diff($files, $dirs);

    $subdirs = array($files);
    foreach ($dirs as $dir) {
        $f = rglob($dir, $flags);
        if (!empty($f)) {
            $subdirs[] = $f;
        }
    }

    return call_user_func_array('array_merge', $subdirs);
}

function duration($seconds) {
    if ($seconds < 60) {
        return $seconds.' s';
    }

    $minuts = floor($seconds / 60);
    $seconds %= 60;
    if ($minuts < 60) {
        return $minuts.' min '.$seconds.' s';
    }

    $hours = floor($minuts / 60);
    $minuts %= 60;
    if ($minuts < 24 ) {
        return $hours.' h '.$minuts.' min '.$seconds.' s';
    }

    $days = floor($hours / 24);
    $hours %= 24;
    return $days.' d '.$hours.' h '.$minuts.' min '.$seconds.' s';
}

function unparse_url($parsed_url) {
    $scheme   = isset($parsed_url['scheme'])   ? $parsed_url['scheme'].'://' : '';
    $host     = isset($parsed_url['host'])     ? $parsed_url['host']           : '';
    $port     = isset($parsed_url['port'])     ? ':'.$parsed_url['port']     : '';
    $user     = isset($parsed_url['user'])     ? $parsed_url['user']           : '';
    $pass     = isset($parsed_url['pass'])     ? ':'.$parsed_url['pass']     : '';
    $pass     = ($user || $pass)               ? "$pass@"                      : '';
    $path     = isset($parsed_url['path'])     ? $parsed_url['path']           : '';
    $query    = isset($parsed_url['query'])    ? '?'.$parsed_url['query']    : '';
    $fragment = isset($parsed_url['fragment']) ? '#'.$parsed_url['fragment'] : '';
    return "$scheme$user$pass$host$port$path$query$fragment";
}

function array_array_unique($array) {
    $return = array();
    
    foreach($array as $a) {
        sort($a);
        $key = crc32(implode('', $a));
        
        $return[$key] = $a;
    }

    return array_values($return);
}


?>