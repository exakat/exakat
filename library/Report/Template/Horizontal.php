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


namespace Report\Template;

class Horizontal extends \Report\Template {
    public function render($output) {
        $renderer = $output->getRenderer('Horizontal');
        
        if ($this->data instanceof \Analyzer\Analyzer) {
            $renderer->setAnalyzer($this->data->getDescription()->getName());
        } elseif ($this->data instanceof \Report\Content\ComposerList) {
            $renderer->setAnalyzer('Composer');
        } elseif ($this->data instanceof \Report\Content\Compilations) {
            $renderer->setAnalyzer('Compilations');
        } elseif ($this->data instanceof \Report\Content) {
            $renderer->setAnalyzer($this->data->getFilename());
        } else {
            echo __METHOD__,
                "Horizontal don't know what kind of description is needed\n",
                print_r(get_class($this->data), true),
                print_r($this->data instanceof \Analyzer\Analyzer, true);
            die();
        }
        $renderer->setCss($this->css);
        
        $renderer->render($output, $this->data->getArray());
    }
}

?>
