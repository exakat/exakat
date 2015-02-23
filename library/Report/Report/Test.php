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


namespace Report\Report;

class Test extends Premier {
    public function __construct($project, $client) {
        parent::__construct($project, $client);
    }

    public function prepare() {
/////////////////////////////////////////////////////////////////////////////////////
/// Custom analyzers
/////////////////////////////////////////////////////////////////////////////////////
        
        $this->createLevel1('Custom');
        $this->createLevel2('Classes');
        $this->addContent('Text', <<<TEXT
This is a list of classes and their usage in the code. 

TEXT
);
        $content = $this->getContent('AnalyzerConfig');
        $content->setAnalyzer('Classes/AvoidUsing');
        $content->collect();
        
        $this->addContent('SimpleTable', $content, 'oneColumn'); 

        $analyzer = \Analyzer\Analyzer::getInstance('Analyzer\\Classes\\AvoidUsing', $this->client);
        $this->addContent('Horizontal', $analyzer);
        
        return true;
    }
}

?>
