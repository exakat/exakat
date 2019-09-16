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

    public function _generate($analyzerList) {
        $stubCode = array();

        $code = new PhpCodeTree($this->sqlite);
        $code->load();

        $code->map('classconstants', function ($classconstants) {
            return '        ' . ($classconstants['visibility'] ?? '') . "const $classconstants[constant] = $classconstants[value];";
        });
        $code->reduce('classconstants', function ($carry, $item) {
            return $carry . "\n" . $item;
        });

        $code->map('properties', function ($properties) {
            return "        $properties[visibility] $properties[property];";
        });
        $code->reduce('properties', function ($carry, $item) {
            return $carry . "\n" . $item;
        });

        $code->map('methods', function ($method) {
            return "        $method[method]";
        });
        $code->reduce('methods', function ($carry, $item) {
            return $carry . "\n" . $item;
        });

        $code->map('cits', function ($cit) {
            $abstract = $cit['abstract'] === 1 ? 'abstract ' : '';
            $final = $cit['final'] === 1 ? 'final ' : '';
            $extends = empty($cit['extends']) ? '' : ' extends ' . $cit['extends'] . ' ';
            $implements = empty($cit['implements']) ? '' : ' implements ' . $cit['implements'] . ' ';

            return "    {$final}{$abstract}$cit[type] $cit[name]{$extends}{$implements} {\n"
                                               . ($cit['classconstants'][$cit['id']]['reduced']     ?? '        /* No class constants */ ') . PHP_EOL
                                               . ($cit['properties'][$cit['id']]['reduced']         ?? '        /* No properties */ '     ) . PHP_EOL
                                               . ($cit['methods'][$cit['id']]['reduced']            ?? '        /* No methods */ '        ) . PHP_EOL
                                               . "    }\n";
        });
        $code->reduce('cits', function ($carry, $item) {
            return $carry . "\n" . $item;
        });

        $code->map('namespaces', function ($namespace) {
            return 'namespace ' . ltrim($namespace['namespace'], '\\') . " {\n"
                                            . ($namespace['constants'][$namespace['id']]['reduced'] ?? '    /* No constant definitions */ ') . PHP_EOL
                                            . ($namespace['functions'][$namespace['id']]['reduced'] ?? '    /* No function definitions */ ') . PHP_EOL
                                            . ($namespace['cits'][$namespace['id']]['reduced']      ?? '    /* No cit definitions */ ') . PHP_EOL
                                            . " \n}\n";
        });

        $code->reduce('namespaces', function ($carry, $item) {
            return $carry . "\n" . $item;
        });

        print "<?php\n" . $code->get('namespaces') . "\n?>\n";
        return "<?php\n" . $code->get('namespaces') . "\n?>\n";
    }
}

?>