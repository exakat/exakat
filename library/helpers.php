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


function display($text) {
    global $VERBOSE;
    
    if ($VERBOSE) {
        echo trim($text), PHP_EOL;
    }
}

function display_r($object) {
    global $VERBOSE;
    
    if ($VERBOSE) {
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
        /*
        if (substr($dir, -1) == '\\') { 
            $dir .= '\\\\';
            print $dir;
        }
        */
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
    $pass     = ($user || $pass)               ? $pass.'@'                      : '';
    $path     = isset($parsed_url['path'])     ? $parsed_url['path']           : '';
    $query    = isset($parsed_url['query'])    ? '?'.$parsed_url['query']    : '';
    $fragment = isset($parsed_url['fragment']) ? '#'.$parsed_url['fragment'] : '';
    return $scheme.$user.$pass.$host.$port.$path.$query.$fragment;
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

function makeList($array, $delimiter = '"') {
    return $delimiter.implode($delimiter.', '.$delimiter, $array).$delimiter;
}

function unicode_blocks($string) {
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
    foreach($characters as $i => $e) {
        $previous = '';
        foreach($ranges as $low => $name) {
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

function PHPSyntax($code) {
    $php = highlight_string('<?php |'.$code.'|; ?>', true);
    $php = substr($php, strpos($php, '|') + 1);
    $php = substr($php, 0, strrpos($php, '|'));
    return $php;
}

function makeArray($value) {
    if (is_array($value)) {
        return $value;
    } else {
        return array($value);
    }
}

function makeFullnspath($functions, $constant = false) {
    // case for classes and functions
    if ($constant === false) {
        $cb = function ($x) {
            $r = mb_strtolower($x);
            if (strpos($r, '\\\\') !== false) {
                $r = stripslashes($r);
            }
            if (isset($r[0]) && $r[0] != '\\') {
                $r = '\\' . $r;
            }
            return $r;
        };
    } else {
        // case for constants
        $cb = function ($r) {
            $r2 = str_replace('\\\\', '\\', $r);

            $d = explode('\\', $r2);
            $last = array_pop($d);
            $r = mb_strtolower(implode('\\', $d)).'\\'.$last;
            if (isset($r[0]) && $r[0] != '\\') {
                $r = '\\' . $r;
            }
            return $r;
        };
    }
    
    if (is_string($functions)) {
        return $cb($functions);
    } elseif (is_array($functions)) {
        $r = array_map($cb, $functions);
    } else {
        throw new \Exception('Function is of the wrong type : '.var_export($functions));
    }
    return $r;
}

function trimOnce($string, $trim = '\'"'){
    if (strpos($trim, $string[0]) !== false) {
        $string = substr($string, 1);
    }

    if (strpos($trim, $string[strlen($string) - 1]) !== false) {
        $string = substr($string, 0, -1);
    }
    
    return $string;
}

?>