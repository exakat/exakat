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

namespace Exakat\Reports;

use Exakat\Analyzer\Analyzer;
use Exakat\Reports\Helpers\Results;
use Exakat\Reports\Helpers\PhpCodeTree;

class Stubs extends Reports {
    const FILE_EXTENSION = 'php';
    const FILE_FILENAME  = 'stubs';
    
    const INDENTATION = '    ';

    public function _generate($analyzerList) {
        $stubCode = array();

        $code = new PhpCodeTree($this->sqlite);
        $code->load();

        $code->map('functions', function ($function) {
            if ($function['function'] === 'Closure') { return '';}
            $phpdoc = ($function['phpdoc'] == ' ') ? '' : self::INDENTATION . $function['phpdoc'] . PHP_EOL;

            $returntype = ($function['returntype'] === null) ? '' : ' : ' . $function['returntype'];
            return "$phpdoc    function $function[function]($function[signature])$returntype { }";
        });
        $code->reduce('functions', function ($carry, $item) {
            return $carry . "\n" . $item;
        });

        $code->map('constants', function ($constant) {
            $phpdoc = ($constant['phpdoc'] == ' ') ? '' : self::INDENTATION . $constant['phpdoc'];

            if ($constant['type'] === 'define') {
                return $phpdoc . self::INDENTATION . "define('$constant[constant]', $constant[value]);";
            } else {
                return $phpdoc . self::INDENTATION . "const $constant[constant] = $constant[value];";
            }
        });
        $code->reduce('constants', function ($carry, $item) {
            return $carry . "\n" . $item;
        });

        $code->map('classconstants', function ($classconstants) {
            $phpdoc = ($classconstants['phpdoc'] == ' ') ? '' : self::INDENTATION . $classconstants['phpdoc'];
            return $phpdoc . self::INDENTATION . self::INDENTATION . ($classconstants['visibility'] ? $classconstants['visibility'] . ' ' : '') . "const $classconstants[constant] = $classconstants[value];";
        });
        $code->reduce('classconstants', function ($carry, $item) {
            return $carry . "\n" . $item;
        });

        $code->map('properties', function ($properties) {
            $phpdoc = ($properties['phpdoc'] == ' ') ? '' : self::INDENTATION . $properties['phpdoc'];

            $default = ($properties['value'] == '' ? '' : ' = ' . $properties['value']);
            $properties['visibility'] = ($properties['visibility'] == '' ? 'var' : $properties['visibility'] );
            return "$phpdoc        $properties[visibility] $properties[property]$default;";
        });
        $code->reduce('properties', function ($carry, $item) {
            return $carry . "\n" . $item;
        });

        $code->map('methods', function ($method) {
            $options = ($method['visibility'] == '' ? '' : $method['visibility'] . ' ') .
                       ($method['static']     == 0  ? '' : 'static ') .
                       ($method['abstract']   == 0  ? '' : 'abstract ') .
                       ($method['final']      == 0  ? '' : 'final ');
            $options = trim($options);
            $options .= !empty($options) ? ' ' : '';
            $returntype = ($method['returntype'] == ' ') ? '' : ' : ' . $method['returntype'];
            $phpdoc = ($method['phpdoc'] == ' ') ? '' : self::INDENTATION . $method['phpdoc'] . PHP_EOL;

            $block = (($method['cit'] === 'interface') || ($method['abstract'] == 1)) ? ';' : ' { }';
            return $phpdoc . self::INDENTATION . self::INDENTATION . "{$options}function $method[method]($method[signature])$returntype $block";
        });
        $code->reduce('methods', function ($carry, $item) {
            return $carry . "\n" . $item;
        });

        $code->map('cits', function ($cit) {
            $abstract = $cit['abstract'] === 1 ? 'abstract ' : '';
            $final = $cit['final'] === 1 ? 'final ' : '';
            $extends = empty($cit['extends']) ? '' : ' extends ' . $cit['extends'] . ' ';
            $implements = empty($cit['implements']) ? '' : ' implements ' . $cit['implements'] . ' ';
            $phpdoc = ($cit['phpdoc'] == ' ') ? '' : self::INDENTATION . $cit['phpdoc'] . PHP_EOL;
            $use = ($cit['use'] === null) ? '' : self::INDENTATION . self::INDENTATION . 'use ' . $cit['use'] . ';' . PHP_EOL;

            return $phpdoc . self::INDENTATION . "{$final}{$abstract}$cit[type] $cit[name]{$extends}{$implements} {\n$use"
                                               . ($cit['classconstants'][$cit['id']]['reduced']     ?? self::INDENTATION . self::INDENTATION . '/* No class constants */ ') . PHP_EOL
                                               . ($cit['properties'][$cit['id']]['reduced']         ?? self::INDENTATION . self::INDENTATION . '/* No properties      */ ') . PHP_EOL
                                               . ($cit['methods'][$cit['id']]['reduced']            ?? self::INDENTATION . self::INDENTATION . '/* No methods         */ ') . PHP_EOL
                                               . self::INDENTATION . "}\n";
        });
        $code->reduce('cits', function ($carry, $item) {
            return $carry . "\n" . $item;
        });

        $code->map('namespaces', function ($namespace) {
            if ($namespace['namespace'] === '\\' &&
                empty($namespace['constants'][$namespace['id']]['reduced']) &&
                empty($namespace['functions'][$namespace['id']]['reduced']) &&
                empty($namespace['cits'][$namespace['id']]['reduced'])) {
                return '';
            }
            
            // empty namspaces are also displayed

            return 'namespace ' . ltrim($namespace['namespace'], '\\') . " {\n"
                                            . ($namespace['constants'][$namespace['id']]['reduced'] ?? self::INDENTATION . '/* No constant definitions */ ') . PHP_EOL
                                            . ($namespace['functions'][$namespace['id']]['reduced'] ?? self::INDENTATION . '/* No function definitions */ ') . PHP_EOL
                                            . ($namespace['cits'][$namespace['id']]['reduced']      ?? self::INDENTATION . '/* No cit      definitions */ ') . PHP_EOL
                                            . "\n}\n";
        });

        $code->reduce('namespaces', function ($carry, $item) {
            return $carry . "\n" . $item;
        });

        return "<?php\n" . $code->get('namespaces') . "\n?>\n";
    }
}

?>