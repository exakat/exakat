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


namespace Report\Format\Ace;

class SummarySidebar extends \Report\Format\Ace { 
    public function render($output, $data) {
        $text = $this->render2($data);

        $text = <<<TEXT
$text

TEXT;

        $output->push($text);
    }


//class="active" class="active open"

    private function render2($data) {
        $text = '';
        foreach($data as $row) {
            if (get_class($row) != "Report\\Template\\Section") { continue; }
            
            $contents = $row->getContent();

            foreach($contents as $id => $c) {
                if (get_class($c) != "Report\\Template\\Section") { 
                    unset($contents[$id]); 
                } 
            }

            if (count($contents) == 0) {
                $text .= <<<HTML
					<li>
						<a href="{$row->getId()}.html">
    						<i class="icon-dashboard"></i>
							<span class="menu-text"> {$row->getName()} </span>
						</a>
					</li>
HTML;
            } else {
                $class = ($row->isCurrent()) ? ' class="active open"' : '';
                
                $text .= <<<HTML
					<li$class>
						<a href="#" class="dropdown-toggle">
    						<i class="icon-cog"></i>
							<span class="menu-text"> {$row->getName()} </span>
						</a>
    					<ul  class="submenu">
HTML;

                foreach($contents as $content) {
                    $class = ($content->isCurrent()) ? ' class="active"' : '';

                    $text .= <<<HTML
        					<li$class>
	        					<a href="{$content->getId()}.html">
    	        					<i class="icon-legal"></i>
    			    				<span class="menu-text"> {$content->getName()} </span>
	    			    		</a>
		    			    </li>
HTML;
                }
                $text .= <<<HTML
			    		</ul>
					</li>
HTML;
            }
        }
        
        return '				<ul class="nav nav-list">
'.$text.'</ul>'; 
    }
}

?>
