<?php

namespace Report\Format\Ace;

class SummarySidebar extends \Report\Format\Ace { 
    public function render($output, $data) {
        $text = $this->render2($data);

        $text = <<<TEXT
$text

TEXT;

        $output->push($text);
    }

    private function render2($data) {
        $text = '';
        foreach($data as $row) {
            if (get_class($row) != "Report\\Template\\Section") { continue; }
            
            $contents = $row->getContent();

            print count($contents)." content\n";
            foreach($contents as $id => $c) {
                print get_class($c)."\n";
                if (get_class($c) != "Report\\Template\\Section") { unset($contents[$id]); } else { print $c->getName()."\n";}
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
                $text .= <<<HTML
					<li>
						<a href="#" class="dropdown-toggle">
    						<i class="icon-cog"></i>
							<span class="menu-text"> {$row->getName()} </span>
						</a>
    					<ul  class="submenu">
HTML;

                foreach($contents as $content) {
                    $text .= <<<HTML
        					<li>
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
            print "\nEnd cycle\n";
        }
        
//        die(__METHOD__);
        return '				<ul class="nav nav-list">
'.$text.'</ul>'; 
    }
}

?>