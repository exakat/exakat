<?php declare(strict_types = 1);
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

use Exakat\Exakat;

class Ambassadornomenu extends Ambassador {
    const CONFIG_YAML    = 'Ambassadornomenu';

    protected function getBasedPage(string $file = ''): string {
        static $baseHTML;

        if (empty($baseHTML)) {
            $baseHTML = file_get_contents("{$this->config->dir_root}/media/devfaceted/data/base.html");
            $project_name = $this->config->project_name;

            $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_VERSION', Exakat::VERSION);
            $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_BUILD', (string) Exakat::BUILD);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT_NAME', $project_name);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT_LETTER', strtoupper($project_name[0]));

            $this->makeMenu();

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

        if (!file_exists("{$this->config->dir_root}/media/devfaceted/data/$file.html")) {
            return '';
        }
        $subPageHTML = file_get_contents("{$this->config->dir_root}/media/devfaceted/data/$file.html");
        $combinePageHTML = $this->injectBloc($baseHTML, 'BLOC-MAIN', $subPageHTML);

        return $combinePageHTML;
    }

    protected function generateIssuesEngine(Section $section, array $issues = array()): void {
        if (empty($issues)) {
            $issues = $this->getIssuesFaceted(makeArray($this->rulesets->getRulesetsAnalyzers(makeArray($section->ruleset))));
        }

        $total = count($issues);
        $issues = implode(', ' . PHP_EOL, $issues);
        $blocjs = <<<JAVASCRIPTCODE
        
  <script>
  "use strict";

    $(document).ready(function() {

      var data_items = [
$issues
];

      var item_template =  
        '<tr>' +
          '<td width="20%"><a href="<%= "analyses_doc.html#" + obj.analyzer_md5 %>" title="Documentation for <%= obj.analyzer %>"><i class="fa fa-book"></i></a> <%= obj.analyzer %></td>' +
          '<td width="20%"><%= obj.file + ":" + obj.line %></td>' +
          '<td width="18%"><%= obj.code %></td>' + 
          '<td width="2%"><%= obj.code_detail %></td>' +
          '<td width="7%" align="center"><%= obj.severity %></td>' +
          '<td width="7%" align="center"><%= obj.complexity %></td>' +
          '<td width="16%"><%= obj.recipe %></td>' +
        '</tr>' +
        '<tr class="fullcode">' +
          '<td colspan="7" width="100%"><div class="analyzer_help"><%= obj.analyzer_help %></div><pre><code><%= obj.code_plus %></code><div class="text-right"><a target="_BLANK" href="<%= "codes.html#file=" + obj.file + "&line=" + obj.line %>" class="btn btn-info">View File</a></div></pre></td>' +
        '</tr>';
      var settings = { 
        items           : data_items,
        facets          : { 
          'analyzer'  : 'Analysis',
          'file'      : 'File',
          'severity'  : 'Severity',
          'complexity': 'Time To Fix',
          'receipt'   : 'Rulesets'
        },
        facetContainer     : '<div class="facetsearch btn-group" id=<%= id %> ></div>',
        facetTitleTemplate : '<button class="facettitle multiselect dropdown-toggle btn btn-default" data-toggle="dropdown" title="None selected"><span class="multiselect-selected-text"><%= title %></span><b class="caret"></b></button>',
        facetListContainer : '<ul class="facetlist multiselect-container dropdown-menu" style="max-height: 450px; overflow: auto;"></ul>',
        listItemTemplate   : '<li class=facetitem id="<%= id %>" data-analyzer="<%= data_analyzer %>" data-file="<%= data_file %>"><span class="check"></span><%= name %><span class=facetitemcount>(<%= count %>)</span></li>',
        bottomContainer    : '<div class=bottomline></div>',  
        resultSelector   : '#results',
        facetSelector    : '#facets',
        resultTemplate   : item_template,
        paginationCount  : 50
      }   
      $.facetelize(settings);
      
      var analyzerParam = window.location.hash.split('analyzer=')[1];
      console.log(analyzerParam);
      var fileParam = window.location.hash.split('file=')[1];
      if(analyzerParam !== undefined) {
        $('#analyzer .facetlist').find("[data-analyzer='" + analyzerParam.toLowerCase() + "']").click();
      }
      if(fileParam !== undefined) {
        $('#file .facetlist').find("[data-file='" + fileParam.toLowerCase() + "']").click();
      }
    });
  </script>
JAVASCRIPTCODE;

        $baseHTML = $this->getBasedPage($section->source);
        $finalHTML = $this->injectBloc($baseHTML, 'BLOC-JS', $blocjs);
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', $section->title);
        $finalHTML = $this->injectBloc($finalHTML, 'TOTAL', (string) $total);
        $this->putBasedPage($section->file, $finalHTML);
    }

    protected function generateCodes(Section $section): void {
        // $this is short-circuited on purpose.
    }
}

?>