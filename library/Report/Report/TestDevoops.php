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

use Report\Report;

class TestDevoops extends Report {
    public function __construct($project, $client) {
        parent::__construct($project, $client);
    }

    public function prepare() {
        $this->createLevel1('Report presentation');

/////////////////////////////////////////////////////////////////////////////////////
/// Annexes
/////////////////////////////////////////////////////////////////////////////////////
        $this->createLevel1('Annexes');

        $this->createLevel2('Documentation');
        $analyzes = array_merge(\Analyzer\Analyzer::getThemeAnalyzers('Analyze'),
                                \Analyzer\Analyzer::getThemeAnalyzers('Coding Conventions'));
            $definitions = new \Report\Content\Definitions($this->client);
            $definitions->setAnalyzers($analyzes);
        $this->addContent('Definitions', $definitions, 'annexes');

        $this->createLevel2('Processed files');
        $this->addContent('Text', 'This is the list of processed files. Any file that is in the project, but not in the list below was omitted in the analyze. 
        
This may be due to configuration file, compilation error, wrong extension (including no extension). ', 'textLead');

        $this->addContent('SimpleTable', 'ProcessedFileList', 'oneColumn');

        // List of dynamic calls
        $analyzer = \Analyzer\Analyzer::getInstance('Structures/DynamicCalls', $this->client);
        if ($analyzer->hasResults()) {
            $this->createLevel2('Dynamic code');
            $this->addContent('Text', 'This is the list of dynamic call. They are not checked by the static analyzer, and the analysis may be completed with a manual check of that list.', 'textLead');
            $this->addContent('Horizontal', $analyzer);
        }

        return true;
    }
}

?>
