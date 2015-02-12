<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Report\Format;

class Html extends \Report\Format { 
    private $output = '';
    protected static $analyzer = null;
    private $summary = null;

    protected $fileExtension ='html';

    public function __construct() {
        parent::__construct();
        
        $this->format = 'Html';
    }
    
    public function render($output, $data) {
        $output->push(" Text for ".get_class($this)."\n");
    }
    
    public function push($render) {
        $this->output .= $render;
    }
    
    public function toFile($filename) {
        file_put_contents($filename, <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
    <head>
        <meta charset="utf-8">
        <title>Exakat report</title>
    </head>
    <body>
    {$this->output}
    </body>
</html>

HTML
);
        
        return true;
    }

    public function setAnalyzer($name) {
        \Report\Format\Html::$analyzer = $name;
    }

    public function setSummaryData($data) {
        $this->summary = $data;
    }

    public function setCss() {
        // nothing to do
    }

}

?>
