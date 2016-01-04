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


namespace Report\Report;

class Test extends Premier {
    public function __construct($project) { 
        parent::__construct($project);
    }

    public function prepare() {
        $this->createLevel1('By analyze');

            $analyzes2 = ['Php/UnicodeEscapeSyntax'];
            foreach($analyzes2 as $a) {
                $analyzer = \Analyzer\Analyzer::getInstance($a);
//                $analyzes2[$analyzer->getDescription()->getName()] = $analyzer;
                
                if ($analyzer->hasResults()) {
                    $this->createLevel2($analyzer->getDescription()->getName());
                    if (get_class($analyzer) == "Analyzer\\Php\\Incompilable") {
                        $this->addContent('TextLead', $analyzer->getDescription()->getDescription(), 'textLead');
                        $this->addContent('TableForVersions', $analyzer);
                    } elseif (get_class($analyzer) == "Analyzer\\Php\\ShortOpenTagRequired") {
                        $this->addContent('TextLead', $analyzer->getDescription()->getDescription(), 'textLead');
                        $this->addContent('SimpleTable', $analyzer, 'oneColumn');
                    } else {
                        $description = $analyzer->getDescription()->getDescription();
                        if ($description == '') {
                            $description = 'No documentation yet';
                        }
                        if ($clearPHP = $analyzer->getDescription()->getClearPHP()) {
                            $this->addContent('Text', 'clearPHP : <a href="https://github.com/dseguy/clearPHP/blob/master/rules/'.$clearPHP.'.md">'.$clearPHP.'</a><br />', 'textLead');
                        }


                        $this->addContent('TextLead', $description, 'textLead');

                        $this->addContent('Horizontal', $analyzer);
                    }
                }
            }
            
            
        return true;
    }
}

?>
