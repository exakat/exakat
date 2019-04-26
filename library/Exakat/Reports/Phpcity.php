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

class Phpcity extends Reports {
    const FILE_EXTENSION = 'json';
    const FILE_FILENAME  = 'exakat.phpcity';

    public function _generate($analyzerList) {
        $results = array();
        
        $query = <<<'SQL'
SELECT
     cit.id,
     files.file AS file,
     namespaces.namespace AS namespace,
     name,
     extends,
     (SELECT GROUP_CONCAT(implements) FROM cit_implements WHERE cit_implements.implementing = cit.id) AS implements,
     end - begin AS no_lines,
     (SELECT COUNT(*) FROM properties WHERE properties.citId = cit.id) AS no_attrs,
     (SELECT COUNT(*) FROM methods WHERE methods.citId = cit.id) AS no_methods,
     CASE type 
           WHEN 'trait' 
               THEN 1 
           ELSE 0 END AS trait,
     abstract,
     final,
     'class' AS type
        
     FROM cit
     JOIN namespaces
        ON namespaces.id = cit.namespaceId
     JOIN files
       ON cit.file = files.id
SQL;
        $res = $this->sqlite->query($query);
        /*
{
    "file": "\/Users\/famille\/Desktop\/analyzeG3\/projects\/phpmyadmin\/code\/test\/classes\/DatabaseInterfaceTest.php",
    "namespace": "PhpMyAdmin\\Tests",
    "name": "DatabaseInterfaceTest",
    "extends": "PmaTestCase",
    "implements": null,
    "no_lines": 498,
    "no_attrs": 1,
    "no_methods": 17,
    "abstract": false,
    "final": false,
    "trait": false,
    "type": "class",
    "anonymous": false
}
*/
        
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $row['implements'] = null;
            $row['anonymous'] = null;
            $row['abstract'] = (bool) $row['abstract'];
            $row['final'] = (bool) $row['final'];
            $row['trait'] = (bool) $row['trait'];
//            $row['no_attrs'] = rand(0, 45);
//            $row['no_methods'] = rand(0, 25);
            
            $this->count();
            $results[] = $row;
        }

        return json_encode($results);
    }
}

?>