<?php
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

declare(strict_types=1);

use Exakat\Exceptions\NoSuchDir;
use Exakat\Container;
use Exakat\Exceptions\WrongParameterType;

const INI_PROCESS_SECTIONS      = true;
const INI_DONT_PROCESS_SECTIONS = false;

const STRICT_COMPARISON = true;
const LOOSE_COMPARISON  = false;

const JSON_ASSOCIATIVE = true;
const JSON_OBJECT      = false;

const TIME_AS_ARRAY  = false;
const TIME_AS_STRING = false;
const TIME_AS_NUMBER = true;

const DISPLAY_TO_STDOUT = false;
const RETURN_VALUE      = true;

const MAX_ARGS = 100;

const SQLITE_CHUNK_SIZE = 490;

const SQLITE3_BUSY_TIMEOUT = 5000; // ms

function display(string $text) : void {
    global $VERBOSE;
    
    if ($VERBOSE) {
        echo trim($text), PHP_EOL;
    }
}

function rmdirRecursive(string $dir) : int {
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
        $path = "$dir/$file";
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

function copyDir(string $src, string $dst) : int {
    if (!file_exists($src)) {
        throw new NoSuchDir("Can't find dir : '$src'");
    }
    $dir = opendir($src);
    if (!is_resource($dir)) {
        throw new NoSuchDir("Can't open dir : '$src' : " . error_get_last()[0]);
    }

    $total = 0;
    mkdir($dst, 0755);
    while (is_string($file = readdir($dir))) {
        if ($file === '.' || $file === '..' ) {
            continue;
        }

        if ( is_dir("$src/$file") ) {
            $total += copyDir("$src/$file", "$dst/$file");
        } else {
            copy("$src/$file", "$dst/$file");
            ++$total;
        }
    }

    closedir($dir);

    return $total;
}

function rglob(string $pattern, int $flags = \GLOB_NOSORT) : array {
    $pattern = str_replace('\\', '\\\\', $pattern);
    $files = glob("$pattern/*", $flags);
    $dirs  = glob("$pattern/*", \GLOB_ONLYDIR | \GLOB_NOSORT);
    $files = array_diff($files, $dirs);

    $subdirs = array($files);
    foreach ($dirs as $dir) {
        $f = rglob($dir, $flags);
        if (!empty($f)) {
            $subdirs[] = $f;
        }
    }

    return array_merge(...$subdirs);
}

function duration(int $seconds) : string {
    if ($seconds < 60) {
        return "$seconds s";
    }

    $minuts = floor($seconds / 60);
    $seconds %= 60;
    if ($minuts < 60) {
        return "$minuts min $seconds s";
    }

    $hours = floor($minuts / 60);
    $minuts %= 60;
    if ($minuts < 24 ) {
        return "$hours h $minuts min $seconds s";
    }

    $days = floor($hours / 24);
    $hours %= 24;
    return "$days d $hours h $minuts min $seconds s";
}

function unparse_url(array $parsed_url) : string {
    $scheme   = empty($parsed_url['scheme'])   ?  '' : $parsed_url['scheme'].'://';
    $host     = $parsed_url['host']            ?? '';
    $port     = isset($parsed_url['port'])     ?  ":$parsed_url[port]"          : '';

    $user     = empty($parsed_url['user'])     ?  '' : $parsed_url['user'];
    $pass     = empty($parsed_url['pass'])     ?  '' : ":$parsed_url[pass]";
    $userpass = ($user || $pass)               ?  "$user$pass@"                 : '';

    $path     = $parsed_url['path']            ?? '';
    $query    = isset($parsed_url['query'])    ?  "?$parsed_url[query]"         : '';
    $fragment = isset($parsed_url['fragment']) ?  "#$parsed_url[fragment]"      : '';

    return "$scheme$userpass$host$port$path$query$fragment";
}

// Returns a list of unique values, when all values are arrays
function array_array_unique(array $array) : array {
    $return = array();
    
    foreach ($array as $a) {
        sort($a);
        $key = crc32(implode('', $a));
        
        $return[$key] = $a;
    }

    return array_values($return);
}

// [a => b, ...] to [ b => [a1, a2, ...]]
function array_groupby(array $array) : array {
    $return = array();
    foreach ($array as $k => $v) {
        if (isset($return[$v])) {
            $return[$v][] = $k;
        } else {
            $return[$v] = array($k);
        }
    }
    
    return $return;
}

function array_ungroupby(array $array) : array {
    $return = array();
    foreach ($array as $k => $v) {
        foreach ($v as $w) {
            $return[$w] = $k;
        }
    }
    
    return $return;
}

function makeList(array $array, string $delimiter = '"') : string {
    return $delimiter . implode("$delimiter, $delimiter", $array) . $delimiter;
}

function unicode_blocks(string $string) : array {
    $c = trim(mb_encode_numericentity ($string, array (0x0, 0xffff, 0, 0xffff), 'UTF-8'), '&#;x');
    $characters = explode(';&#', $c);
    
    static $ranges = array(
        0x0020 => 'Basic Latin',
        0x00A0 => 'Latin-1 Supplement',
        0x0100 => 'Latin Extended-A',
        0x0180 => 'Latin Extended-B',
        0x0250 => 'IPA Extensions',
        0x02B0 => 'Spacing Modifier Letters',
        0x0300 => 'Combining Diacritical Marks',
        0x0370 => 'Greek and Coptic',
        0x0400 => 'Cyrillic',
        0x0500 => 'Cyrillic Supplementary',
        0x0530 => 'Armenian',
        0x0590 => 'Hebrew',
        0x0600 => 'Arabic',
        0x0700 => 'Syriac',
        0x0780 => 'Thaana',
        0x0900 => 'Devanagari',
        0x0980 => 'Bengali',
        0x0A00 => 'Gurmukhi',
        0x0A80 => 'Gujarati',
        0x0B00 => 'Oriya',
        0x0B80 => 'Tamil',
        0x0C00 => 'Telugu',
        0x0C80 => 'Kannada',
        0x0D00 => 'Malayalam',
        0x0D80 => 'Sinhala',
        0x0E00 => 'Thai',
        0x0E80 => 'Lao',
        0x0F00 => 'Tibetan',
        0x1000 => 'Myanmar',
        0x10A0 => 'Georgian',
        0x1100 => 'Hangul Jamo',
        0x1200 => 'Ethiopic',
        0x13A0 => 'Cherokee',
        0x1400 => 'Unified Canadian Aboriginal Syllabics',
        0x1680 => 'Ogham',
        0x16A0 => 'Runic',
        0x1700 => 'Tagalog',
        0x1720 => 'Hanunoo',
        0x1740 => 'Buhid',
        0x1760 => 'Tagbanwa',
        0x1780 => 'Khmer',
        0x1800 => 'Mongolian',
        0x1900 => 'Limbu',
        0x1950 => 'Tai Le',
        0x19E0 => 'Khmer Symbols',
        0x1D00 => 'Phonetic Extensions',
        0x1E00 => 'Latin Extended Additional',
        0x1F00 => 'Greek Extended',
        0x2000 => 'General Punctuation',
        0x2070 => 'Superscripts and Subscripts',
        0x20A0 => 'Currency Symbols',
        0x20D0 => 'Combining Diacritical Marks for Symbols',
        0x2100 => 'Letterlike Symbols',
        0x2150 => 'Number Forms',
        0x2190 => 'Arrows',
        0x2200 => 'Mathematical Operators',
        0x2300 => 'Miscellaneous Technical',
        0x2400 => 'Control Pictures',
        0x2440 => 'Optical Character Recognition',
        0x2460 => 'Enclosed Alphanumerics',
        0x2500 => 'Box Drawing',
        0x2580 => 'Block Elements',
        0x25A0 => 'Geometric Shapes',
        0x2600 => 'Miscellaneous Symbols',
        0x2700 => 'Dingbats',
        0x27C0 => 'Miscellaneous Mathematical Symbols-A',
        0x27F0 => 'Supplemental Arrows-A',
        0x2800 => 'Braille Patterns',
        0x2900 => 'Supplemental Arrows-B',
        0x2980 => 'Miscellaneous Mathematical Symbols-B',
        0x2A00 => 'Supplemental Mathematical Operators',
        0x2B00 => 'Miscellaneous Symbols and Arrows',
        0x2E80 => 'CJK Radicals Supplement',
        0x2F00 => 'Kangxi Radicals',
        0x2FF0 => 'Ideographic Description Characters',
        0x3000 => 'CJK Symbols and Punctuation',
        0x3040 => 'Hiragana',
        0x30A0 => 'Katakana',
        0x3100 => 'Bopomofo',
        0x3130 => 'Hangul Compatibility Jamo',
        0x3190 => 'Kanbun',
        0x31A0 => 'Bopomofo Extended',
        0x31F0 => 'Katakana Phonetic Extensions',
        0x3200 => 'Enclosed CJK Letters and Months',
        0x3300 => 'CJK Compatibility',
        0x3400 => 'CJK Unified Ideographs Extension A',
        0x4DC0 => 'Yijing Hexagram Symbols',
        0x4E00 => 'CJK Unified Ideographs',
        0xA000 => 'Yi Syllables',
        0xA490 => 'Yi Radicals',
        0xAC00 => 'Hangul Syllables',
        0xD800 => 'High Surrogates',
        0xDB80 => 'High Private Use Surrogates',
        0xDC00 => 'Low Surrogates',
        0xE000 => 'Private Use Area',
        0xF900 => 'CJK Compatibility Ideographs',
        0xFB00 => 'Alphabetic Presentation Forms',
        0xFB50 => 'Arabic Presentation Forms-A',
        0xFE00 => 'Variation Selectors',
        0xFE20 => 'Combining Half Marks',
        0xFE30 => 'CJK Compatibility Forms',
        0xFE50 => 'Small Form Variants',
        0xFE70 => 'Arabic Presentation Forms-B',
        0xFF00 => 'Halfwidth and Fullwidth Forms',
        0xFFF0 => 'Specials',
        0x10000 => 'Linear B Syllabary',
        0x10080 => 'Linear B Ideograms',
        0x10100 => 'Aegean Numbers',
        0x10300 => 'Old Italic',
        0x10330 => 'Gothic',
        0x10380 => 'Ugaritic',
        0x10400 => 'Deseret',
        0x10450 => 'Shavian',
        0x10480 => 'Osmanya',
        0x10800 => 'Cypriot Syllabary',
        0x1D000 => 'Byzantine Musical Symbols',
        0x1D100 => 'Musical Symbols',
        0x1D300 => 'Tai Xuan Jing Symbols',
        0x1D400 => 'Mathematical Alphanumeric Symbols',
        0x20000 => 'CJK Unified Ideographs Extension B',
        0x2F800 => 'CJK Compatibility Ideographs Supplement',
        0xE0000 => 'Tags',
    );
    
    $return = array();
    foreach ($characters as $e) {
        $previous = '';
        foreach ($ranges as $low => $name) {
            if ($low > $e) {
                if (isset($return[$previous])) {
                    ++$return[$previous];
                } else {
                    $return[$previous] = 1;
                }
                break 1;
            }
            $previous = $name;
        }
    }
    
    arsort($return);

    return $return;
}

function PHPSyntax(string $code) : string {
    static $cache;
    
    if (!isset($cache)) {
        $cache = array();
    }
    
    if (isset($cache[$code])) {
        return $cache[$code];
    }

    $code = trim($code);
    $php = highlight_string("<?php \n{$code}\n ?>", true);
    $php = substr($php, 85, -40);
    if (substr($php, 0, 7) === '</span>') {
        $php = substr($php, 7, 10000);
    } else {
        $php = '<span style="color: #0000BB">' . $php;
    }
    if (substr($php, -17) === '<span style="colo') {
        //<br /></span><span style="colo
        $php = substr($php, 0, -30);
        $php .= '</span>';
    } else {
        $php .= '</span>';
    }
    $cache[$code] = $php;
    
    return $cache[$code];
}

function makeArray($value) : array {
    if (is_array($value)) {
        return array_values($value);
    } else {
        return array($value);
    }
}

const FNP_CONSTANT     = true;
const FNP_NOT_CONSTANT = false;

function makeFullNsPath($functions, bool $constant = \FNP_NOT_CONSTANT) {
    // case for classes and functions
    if ($constant === \FNP_NOT_CONSTANT) {
        $cb = function (string $x) : string {
            $r = mb_strtolower($x);
            if (strpos($r, '\\\\') !== false) {
                $r = stripslashes($r);
            }
            if (isset($r[0]) && $r[0] != '\\') {
                $r = "\\$r";
            }
            return $r;
        };
    } else {
        // case for constant
        $cb = function (string $r) : string {
            $r2 = str_replace('\\\\', '\\', $r);

            if (strpos($r2, '::') !== false) {
                $d = explode('::', $r2);
                $glue = '::';
            } else {
                $d = explode('\\', $r2);
                $glue = '\\';
            }
            $last = array_pop($d);
            $r = mb_strtolower(implode('\\', $d)) . $glue.$last;
            if (isset($r[0]) && $r[0] != '\\') {
                $r = "\\$r";
            }
            return $r;
        };
    }
    
    if (is_string($functions) || is_int($functions)) {
        return $cb($functions);
    } elseif (is_array($functions)) {
        return array_map($cb, $functions);
    }

    throw new WrongParameterType(gettype($functions), __METHOD__);
}

function trimOnce(string $string, string $trim = '\'"') : string {
    $length = strlen($string);
    if ($length < 2) {
        return $string;
    }

    if ($string[0] === $string[$length -1] &&
        strpos($trim, $string[0]) !== false &&
        strpos($trim, $string[$length -1]) !== false
         ) {
        return substr($string, 1, -1);
    }

    return $string;
}

function makeHtml(string $string) : string {
    return htmlentities($string, ENT_COMPAT | ENT_HTML401, 'UTF-8');
}

function rst2quote(string $text) : string {
    return preg_replace('/``+(.+?)``+/s', ' <span style="border: 1px solid #ddd; background-color: #f5f5f5">$1</span> ', $text);
}

function rst2htmlLink(string $text) : string {
    // `title <url>`_ => <a href="url">title</a>
    // `anchor`_ => <a href="#anchor">anchor</a>
    
    return preg_replace('/`([^<]+?) <([^>]+?)>`_+/s', '<a href="$2" alt="$1">$1</a>', $text);
}

function rst2literal(string $text) : string {
    $return = preg_replace_callback("#<\?literal(.*?)\n\?>#is", function (array $x) : string {
        $return = '<pre style="border: 1px solid #ddd; background-color: #f5f5f5;">&lt;?php ' . PHP_EOL . str_replace('<br />', '', $x[1]) . '?&gt;</pre>';
        return $return;
    }, $text);

    return $return;
}

function rsttable2html(string $raw) : string {
    $html = array();
    
    $lines = explode("\n", $raw);
    $table = false;
    
    foreach ($lines as $line) {
        if (preg_match('/^[\+-]+<br \/>$/', $line, $r)) {
            if ($table !== true) {
                $table = true;
                $html []= '<table style="border: solid 1px black;">';
            }
            continue;
        } elseif ($table === true) {
            if (preg_match('/^[\+-]+$/', $line, $r)) {
                $html[] = '<tr>' . str_repeat('<td></td>', substr_count('+', $r[0])) . "</tr>\n";
            } elseif (strpos($line, '|') === false) {
                $table = false;
                $html []= '</table>';
                $html []= '';
            } elseif (!empty($td = explode('|', str_replace('<br />', '', $line)))) {
                $td = array_map('trim', $td);
                
                $html[] = '<tr><td>' . implode('</td><td>', $td) . '</td></tr>';
            }
        } else {
            $html []= $line;
        }
    }
    
    return implode(PHP_EOL, $html);
}

function rstlist2html(string $raw) : string {
    $html = array();
    
    $lines = explode("\n", $raw);
    $list = false;
    
    foreach ($lines as $line ) {
        if (preg_match('/^\*\s+([^\*]+)\s*<br \/>$/', $line, $r)) {
            if ($list === true) {
                $html [] = "<li>$r[1]</li>";
            } else {
                $list = true;
                $html []= '<ul>';
                $html [] = "<li>$r[1]</li>";
            }
            continue;
        } else {
            if ($list === true) {
                $list = false;
                $html []= '</ul>';
            }
            $html []= $line;
        }
    }
    
    return implode(PHP_EOL, $html);
}

// split a string into an array, based on delimiter, then apply trim to clean hidden spaces
function str2array(string $string, string $delimiter = ',') : array {
    $array = explode($delimiter, $string);
    
    return array_map('trim', $array);
}

// convert a number into its English ordinal name
function ordinal(int $number) : string {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if (($number % 100 >= 11) && ($number % 100 <= 13)) {
        return "{$number}th";
    } else {
        return $number . $ends[$number % 10];
    }
}

/*
array('a/b' => 1 ) to Array
(
    [a] => Array
        (
            [b] => 1
        )

)
*/
function raiseDimensions($array, $split='/') : array {
    $return = array();
    
    foreach ($array as $k => $value) {
        $kr = trim($k, $split);
        $d = explode($split, $kr);
        
        $last = array_pop($d);
        $sub = &$return;
        foreach ($d as $e) {
            if (isset($sub[$e]) && is_array($sub[$e])) {
                $sub = &$sub[$e];
            } else {
                $sub[$e] = array();
                $sub = &$sub[$e];
            }
        }
        $sub[$last] = $value;
    }
    
    return $return;
}

function sort_dependencies(array $array, int $level = 0) : array {
    $return = array();
    $next = array();
    
    foreach ($array as $a => $b) {
        if (empty($b)) {
            $return[] = $a;
        } else {
            $next[$a] = $b;
        }
    }
    
    if (!empty($next)) {
        $keys = array_keys($next);
        foreach ($next as $a => &$b) {
            $b = array_diff($b, $return);
            
            if (empty(array_intersect($b, $keys))) {
                $return = array_merge($return, $b);
                $b = array();
            }
        }
        
        assert($level < 10, 'Too many levels in dependencies. Aborting');
        $return = array_merge($return, sort_dependencies($next, ++$level));
    }
    
    return $return;
}

function filter_analyzer(string $analyzer) : int {
    return preg_match('#^\w+/\w+$#', $analyzer);
}

function array_sub_sort(array &$list) : void {
    foreach ($list as &$l) {
        sort($l);
    }
    unset($l);
}

function array_collect_by(array &$array, $key, $value) : void {
    if (isset($array[$key])) {
        $array[$key][] = $value;
    } else {
        $array[$key] = array($value);
    }
}

function readIniPercentage(string $value) : float {
    $return = abs((int) $value);
    $return = max(0, $return);
    $return = min(100, $return);
    $return /= 100;

    return $return;
}

function listToArray(string $string, string $separator = ',') : array {
    $list = explode($separator, $string);
    $list = array_map('trim', $list);
    $list = array_unique($list);
    
    return $list;
}

function exakat(string $what) {
    static $container;
    
    if ($container === null) {
        $container = new Container();
        
        $container->init($GLOBALS['argv']);
    }

    return $container->$what;
}

?>