<?php declare(strict_types = 1);
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


class StubsJson extends Reports {
    const FILE_EXTENSION = 'json';
    const FILE_FILENAME  = 'stubs';

    const INDENTATION = '    ';

    private $phpFunctions = array();
    private $phpCIT       = array();

    public function _generate(array $analyzerList): string {
        $this->phpFunctions = parse_ini_file("{$this->config->dir_root}/data/php_functions.ini")['functions'];
        $this->phpFunctions = array_map('strtolower', $this->phpFunctions);
        $this->phpCIT       = array_merge( parse_ini_file("{$this->config->dir_root}/data/php_classes.ini")['classes'],
                                           parse_ini_file("{$this->config->dir_root}/data/php_interfaces.ini")['interfaces'],
                                           parse_ini_file("{$this->config->dir_root}/data/php_traits.ini")['traits']
                                         );
        $this->phpCIT = array_map('strtolower', $this->phpCIT);

        $data = array('headers'  => array('generation'       => date('c'),
                                          'php'              => $this->dump->fetchHash('php_version')->toString(),
                                          'exakat_version'   => $this->dump->fetchHash('exakat_version')->toString(),
                                          'exakat_build'     => $this->dump->fetchHash('exakat_build')->toString(),
                                          'vcs_url'          => $this->dump->fetchHash('vcs_url')->toString() ?: '',
                                          'vcs_branch'       => $this->dump->fetchHash('vcs_branch')->toString() ?: '',
                                          'vcs_revision'     => $this->dump->fetchHash('vcs_revision')->toString() ?: '',
                                          'code_last_commit' => $this->dump->fetchHash('vcs_url')->toInt() != 0 ? date('c', $this->dump->fetchHash('vcs_url')->toInt()) : '',
                                          ),
                      'versions' => array());

        // namespaces
        $namespaces = array();
        $res = $this->dump->fetchTable('namespaces');
        foreach($res->toArray() as $namespace) {
            $data['versions'][$namespace['namespace']] = array();

            $namespaces[$namespace['id']] = $namespace['namespace'];
        }

        // constants
        $res = $this->dump->fetchTable('constants');
        foreach($res->toArray() as $constant) {
            $details = array('type'       => $constant['type'],
                             'value'      => $constant['value'],
                             'phpdoc'     => $constant['phpdoc'] ?? '',
//                             'attributes' => $this->normalizeAttributes($constant['attributes'] ?? ''),
                             );
            $data['versions'][$namespaces[$constant['namespaceId']]]['constants'][$constant['constant']] = $details;
        }

        $res = $this->dump->fetchTable('attributes');
        $attributes = array();
        foreach($res->toArray() as $attribute) {
            $attributes[$attribute['type']] ??= array();
            array_collect_by($attributes[$attribute['type']], $attribute['type_id'], $attribute['attribute']);
        }

        $methods = array();
        $function2ns = array();
        // functions
        $res = $this->dump->fetchTable('functions');
        foreach($res->toArray() as $function) {
            if (in_array($function['type'], array('closure', 'arrowfunction'), \STRICT_COMPARISON)) { continue; }

            $details = array('returntypes' => explode('|', $function['returntype']),
                             'reference'   => $function['reference'] === 1,
                             'phpdoc'      => $function['phpdoc'] ?? '',
                             'attributes'  => $this->normalizeAttributes($function['attributes'] ?? ''),
                             'php'         => $function['namespaceId'] === 1 ? in_array(mb_strtolower($function['function']), $this->phpFunctions, \STRICT_COMPARISON) : false,
                             'attributes'  => $attributes['function'][$function['id']] ?? array(),
                             );
            $data['versions'][$namespaces[$function['namespaceId']]]['functions'][$function['function']] = $details;

            $methods[$function['id']] = $function['function'];
            $function2ns[$function['id']] = $function['namespaceId'];
        }

        // classes, interfaces, traits
        $cits = array();
        $cits2ns = array();
        $cits2type = array();
        $citsFqn = array();
        $res = $this->dump->fetchTable('cit');

        foreach($res->toArray() as $cit) {
            $cits[$cit['id']]         = $cit['name'];
            $citsFqn[$cit['id']]      = strtolower($namespaces[$cit['namespaceId']] . $cit['name']);
        }

        foreach($res->toArray() as $cit) {
            $extendsId = ((int) $cit['extends'] > 0) ? $citsFqn[$cit['extends']] ?? '\Unkown' : $cit['extends'];

            $section = $cit['type'] === 'class' ? 'classes' : $cit['type'].'s';

            $details = array('abstract'   => $cit['abstract'] === 1,
                             'final'      => $cit['final'] === 1,
                             'extends'    => $extendsId,
                             'implements' => array(),
                             'use'        => array(),
                             'useoptions' => array(),
                             'phpdoc'     => $cit['phpdoc'] ?? '',
                             'attributes' => $this->normalizeAttributes($cit['attributes'] ?? ''),
                             'php'        => $cit['namespaceId'] === 1 ? in_array(mb_strtolower($cit['name']), $this->phpCIT, \STRICT_COMPARISON) : false,
                             'attributes' => $attributes[$cit['type']][$cit['id']] ?? array(),
                             );
            $data['versions'][$namespaces[$cit['namespaceId']]][$section][$cit['name']] = $details;

            $cits2ns[$cit['id']]   = $cit['namespaceId'];
            $cits2type[$cit['id']] = $section;
        }

        // extensions
        $res = $this->dump->fetchTable('cit_implements');
        foreach($res->toArray() as $cit) {
            $implementsId = ((int) $cit['implements'] > 0) ? $citsFqn[$cit['implements']] ?? '\Unkown' : $cit['implements'];

            $data['versions'][$namespaces[$cits2ns[$cit['implementing']]]][$cits2type[$cit['implementing']]][$cits[$cit['implementing']]][$cit['type']][] = $implementsId;
            if ($cit['type'] === 'use') {
                $data['versions'][$namespaces[$cits2ns[$cit['implementing']]]][$cits2type[$cit['implementing']]][$cits[$cit['implementing']]]['useoptions'] = explode(';', $cit['options']);
            }
        }

        // class constants
        $res = $this->dump->fetchTable('classconstants');
        foreach($res->toArray() as $classconstant) {
            $details = array('value'        => $classconstant['value'],
                             'visibility'   => $classconstant['visibility'],
                             'phpdoc'       => $classconstant['phpdoc'] ?? '',
                             'attributes'   => $attributes['classconstant'][$classconstant['id']] ?? array(),
                             );

            $data['versions'][$namespaces[$cits2ns[$classconstant['citId']]]][$cits2type[$classconstant['citId']]][$cits[$classconstant['citId']]]['constants'][$classconstant['constant']] = $details;
        }

        // properties
        $res = $this->dump->fetchTable('properties');
        foreach($res->toArray() as $property) {
            $details = array('value'        => $property['value'],
                             'visibility'   => $property['visibility'],
                             'static'       => $property['static'] === 1,
                             'typehint'     => explode('|', $property['typehint']),
                             'phpdoc'       => $property['phpdoc'] ?? '',
                             'attributes'   => $attributes['property'][$property['id']] ?? array(),
                             );

            $data['versions'][$namespaces[$cits2ns[$property['citId']]]][$cits2type[$property['citId']]][$cits[$property['citId']]]['properties'][$property['property']] = $details;
        }

        $res = $this->dump->fetchTable('methods');
        foreach($res->toArray() as $method) {
            $details = array('visibility'   => $method['visibility'],
                             'static'       => $method['static']     === 1,
                             'abstract'     => $method['abstract']   === 1,
                             'reference'    => $method['reference']  === 1,
                             'returntypes'  => explode('|', $method['returntype']),
                             'phpdoc'       => $method['phpdoc'],
                             'attributes'   => $attributes['method'][$method['id']] ?? array(),
                             );

            $data['versions'][$namespaces[$cits2ns[$method['citId']]]][$cits2type[$method['citId']]][$cits[$method['citId']]]['methods'][$method['method']] = $details;

            $methods[$method['id']] = $method['method'];
        }

        $res = $this->dump->fetchTable('arguments');
        foreach($res->toArray() as $argument) {
            $details = array('name'         => $argument['name'],
                             'reference'    => $argument['reference'] === 1,
                             'typehint'     => explode('|', $argument['typehint']),
                             'value'        => $argument['init'],
                             'phpdoc'       => $argument['phpdoc'] ?? '',
                             'attributes'   => $attributes['argument'][$argument['id']] ?? array(),
                             );
            if ($argument['citId'] == 0) {
                if (isset($function2ns[$argument['methodId']])) {
                    $data['versions'][$namespaces[$function2ns[$argument['methodId']]]]['functions'][$methods[$argument['methodId']]]['arguments'][$argument['rank']] = $details;
                }
            } elseif (isset($data['versions'][$namespaces[$cits2ns[$argument['citId']]]][$cits2type[$argument['citId']]][$cits[$argument['citId']]]['methods'][$methods[$argument['methodId']]])) {
                $data['versions'][$namespaces[$cits2ns[$argument['citId']]]][$cits2type[$argument['citId']]][$cits[$argument['citId']]]['methods'][$methods[$argument['methodId']]]['arguments'][$argument['rank']] = $details;
            } else {
                display("Undefined method : $argument[citId] (Ignoring. Possible double definition)\n");
            }
        }

        // JSON compact
        return json_encode($data);
    }

    private function normalizeAttributes(string $attributes): array {
        return array_filter(explode(';', $attributes ?? ''));
    }
}

?>