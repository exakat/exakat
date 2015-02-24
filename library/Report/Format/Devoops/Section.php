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


namespace Report\Format\Devoops;

class Section extends \Report\Format\Devoops { 
    public function render($output, $data) {
        // todo link to the actual section  ?

        if ($data->getLevel() == 0) {
            die("Processin level 0 ?? ".__METHOD__);
            
        } elseif ($data->getLevel() == 1) {
            $output->reset();
            foreach($data->getContent() as $content) {
                if (get_class($content) != "Report\\Template\\Section") {
                    $content->render($output);
                }
            }
            $output->toFile2($data->getId().".html", $data);
        } elseif ($data->getLevel() == 2) {
            $output->reset();
            foreach($data->getContent() as $content) {
                if (get_class($content) != "Report\\Template\\Section") {
                    $content->render($output);
                }
            }
            $output->toFile2($data->getId().".html", $data);
        } else {
            $output->push("\n								<h{$data->getLevel()} class=\"header smaller lighter blue\">{$data->getName()}</h{$data->getLevel()}>");
        }
    }

}

?>
