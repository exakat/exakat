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

    public function _generate(array $analyzerList): string {
        $data = array('versions' => array());

        // namespaces
        $res = $this->dump->fetchTable('namespaces');
        foreach($res->toArray() as $namespace) {
            $data['versions'][$namespace['namespace']] = array();

            $namespaces[$namespace['id']] = $namespace['namespace'];
        }

        // constants
        $res = $this->dump->fetchTable('constants');
        foreach($res->toArray() as $constant) {
            $details = array('type' => $constant['type'],
                             'value'   => $constant['value'],
                             );
            $data['versions'][$namespaces[$constant['namespaceId']]]['constants'][$constant['constant']] = $details;
        }


        $methods = array();
        $function2ns = array();
        // functions
        $res = $this->dump->fetchTable('functions');
        foreach($res->toArray() as $function) {
            if ($function['type'] === 'closure') { continue; }

            $details = array('returntypes' => explode('|', $function['returntype']),
                             'reference'   => $function['reference'] === 1,
                                );
            $data['versions'][$namespaces[$function['namespaceId']]]['functions'][$function['function']] = $details;

            $methods[$function['id']] = $function['function'];
            $function2ns[$function['id']] = $function['namespaceId'];
        }

        // classes, interfaces, traits
        $cits = array();
        $cits2ns = array();
        $cits2type = array();
        $res = $this->dump->fetchTable('cit');
//        print_r($res->toArray());die();
        foreach($res->toArray() as $cit) {
            $details = array('abstract'   => $cit['abstract'] === 1,
                             'final'   => $cit['final'] === 1,
                             );
            $data['versions'][$namespaces[$cit['namespaceId']]][$cit['type']][$cit['name']] = $details;

            $cits[$cit['id']] = $cit['name'];
            $cits2ns[$cit['id']] = $cit['namespaceId'];
            $cits2type[$cit['id']] = $cit['type'];
        }
//        print_r($cits);

        // class constants
        $res = $this->dump->fetchTable('classconstants');
//        print_r($res->toArray());die();
        foreach($res->toArray() as $classconstant) {
            $details = array('value'        => $classconstant['value'],
                             'visibility'   => $classconstant['visibility'],
                             'phpdoc'       => $classconstant['phpdoc'] ?? '',
                             );

            $data['versions'][$namespaces[$cits2ns[$classconstant['citId']]]][$cits2type[$classconstant['citId']]][$cits[$classconstant['citId']]]['constants'][$classconstant['constant']] = $details;
        }

        // properties
        $res = $this->dump->fetchTable('properties');
//        print_r($res->toArray());die();
        foreach($res->toArray() as $property) {
            $details = array('value'        => $property['value'],
                             'visibility'   => $property['visibility'],
                             'static'       => $property['static'] === 1,
                             'typehint'     => explode('|', $property['typehint']),
                             'phpdoc'       => $property['phpdoc'] ?? '',
                             );

            $data['versions'][$namespaces[$cits2ns[$property['citId']]]][$cits2type[$property['citId']]][$cits[$property['citId']]]['properties'][$property['property']] = $details;
        }

        $res = $this->dump->fetchTable('methods');
//        print_r($res->toArray());die();
        foreach($res->toArray() as $method) {
            $details = array('visibility'   => $method['visibility'],
                             'static'       => $method['static'] === 1,
                             'reference'    => $method['reference'] === 1,
                             'returntypes'  => explode('|', $method['returntype']),
                             );

            $data['versions'][$namespaces[$cits2ns[$method['citId']]]][$cits2type[$method['citId']]][$cits[$method['citId']]]['methods'][$method['method']] = $details;

            $methods[$method['id']] = $method['method'];
        }

        $res = $this->dump->fetchTable('arguments');
//        print_r($res->toArray());die();
        foreach($res->toArray() as $argument) {
            $details = array('name'         => $argument['name'],
                             'reference'    => $argument['reference'] === 1,
                             'typehint'     => explode('|', $argument['typehint']),
                             'value'        => $argument['init'],
                             );
            if ($argument['citId'] == 0) {
                $data['versions'][$namespaces[$function2ns[$argument['methodId']]]]['functions'][$methods[$argument['methodId']]]['arguments'][$argument['rank']] = $details;
            } else {
                $data['versions'][$namespaces[$cits2ns[$argument['citId']]]][$cits2type[$argument['citId']]][$cits[$argument['citId']]]['methods'][$methods[$argument['methodId']]]['arguments'][$argument['rank']] = $details;
            }
        }

        return json_encode($data, JSON_PRETTY_PRINT);
    }
}

?>