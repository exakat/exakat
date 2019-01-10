<?php
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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
use Exakat\Data\Methods;
use Exakat\Exakat;
use Exakat\Phpexec;
use Exakat\Reports\Reports;

class Ambassadornomenu extends Ambassador {
    protected function getBasedPage($file) {
        static $baseHTML;

        if (empty($baseHTML)) {
            $baseHTML = file_get_contents("{$this->config->dir_root}/media/devfaceted/datas/base.html");
            $title = ($file == 'index') ? 'Dashboard' : $file;
            $project_name = $this->config->project_name;

            $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_VERSION', Exakat::VERSION);
            $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_BUILD', Exakat::BUILD);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT_NAME', $project_name);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT_LETTER', strtoupper($project_name{0}));

            $baseHTML = $this->injectBloc($baseHTML, 'SIDEBARMENU', '');
            $patterns = array('#<aside class="main-sidebar">.*?</aside>#is',
                              '#<aside class="control-sidebar control-sidebar-dark">.*?</aside>#is',
                              '#<header class="main-header">.*?</header>#is',
                              '#<footer class="main-footer">.*?</footer>#is',
                              '#class="content-wrapper"#is',
                              );
            $replacements = array('',
                                  '',
                                  '',
                                  '',
                                  'class="content-wrapper" style="margin-left: 0px"',
                                 );
            $baseHTML = preg_replace($patterns, $replacements, $baseHTML);
        }

        $subPageHTML = file_get_contents($this->config->dir_root.'/media/devfaceted/datas/'.$file.'.html');
        $combinePageHTML = $this->injectBloc($baseHTML, 'BLOC-MAIN', $subPageHTML);

        return $combinePageHTML;
    }
}

?>