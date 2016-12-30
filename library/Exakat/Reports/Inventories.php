<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
use Exakat\Exakat;

class Inventories extends Reports {
    const FILE_EXTENSION = 'csv';
    
    public function generateFileReport($report) {

    }

    public function generate($folder, $name = null) {
        print "$folder/$name\n";
        mkdir($folder.'/'.$name, 0777);
        
        $this->saveInventory('Constants/Constantnames', "$folder/$name/constants.csv");
        $this->saveInventory('Variables/Variablesnames', "$folder/$name/variables.csv");
        $this->saveInventory('Functions/Functionnames', "$folder/$name/functions.csv");
        $this->saveInventory('Classes/Classnames', "$folder/$name/classes.csv");
        $this->saveInventory('Interfaces/Interfacenames', "$folder/$name/interfaces.csv");
        $this->saveInventory('Traits/Traitnames', "$folder/$name/traits.csv");
        $this->saveInventory('Namespaces/Namespacesnames', "$folder/$name/namespaces.csv");
        $this->saveInventory('Exceptions/DefinedExceptions', "$folder/$name/exceptions.csv");
        $this->saveInventory('Exceptions/DefinedExceptions', "$folder/$name/errorMessages.csv");
        $this->saveInventory('Exceptions/DefinedExceptions', "$folder/$name/comparedLiterals.csv");
        
        $this->saveAtom('Integer', "$folder/$name/integers.csv");
        
    } 
    
    private function saveInventory($analyzer, $file) {
        $res = $this->sqlite->query('SELECT fullcode, file, line FROM results WHERE analyzer="'.$analyzer.'"');
        $fp = fopen($file, 'w+');
        fputcsv($fp, array('Name', 'File', 'Line'));
        while($row = $res->fetchArray()) {
            fputcsv($fp, $row);
        }
        fclose($fp);
    }

    private function saveAtom($atom, $file) {
        $res = $this->sqlite->query('SELECT fullcode, file, line FROM results WHERE analyzer="'.$analyzer.'"');
        $fp = fopen($file, 'w+');
        fputcsv($fp, array('Name', 'File', 'Line'));
        while($row = $res->fetchArray()) {
            fputcsv($fp, $row);
        }
        fclose($fp);
    }

} 

?>