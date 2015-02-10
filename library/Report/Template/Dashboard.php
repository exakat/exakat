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

class Dashboard  extends \Report\Template {

    public function render($output) {
        $data = $this->data->getArray();

        $row = new \Report\Template\Row();
        $row->setCss($this->css);

        $relay = array();

        $left = new \Report\Template\Camembert();
        $left->setContent($data['upLeft']);
        $left->setCss($this->css);
        $relay['left'] = $left;

        $right = new \Report\Template\Infobox();
        $right->setContent($data['upRight']);
        $right->setCss($this->css);
        $relay['right'] = $right;

        $row->render($output, $relay);

        // second row

        $row = new \Report\Template\Row();
        $row->setCss($this->css);

        $relay = array();

//        Top 5 errors
        $left = new \Report\Template\Top5();
        $left->setContent($data['downLeft']);
        $left->setCss('top5errors');
        $relay['left'] = $left;

//        Top 5 files
        $right = new \Report\Template\Top5();
        $right->setContent($data['downRight']);
        $right->setCss('top5files');
        $relay['right'] = $right;

        $row->render($output, $relay);
    }
}

?>
