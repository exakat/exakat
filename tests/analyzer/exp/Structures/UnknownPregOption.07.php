<?php

$expected     = array('preg_match(\'{[a-f0-9]{40}}B\', $identifier)',
                     );

$expected_not = array('preg_match("!^[A-Z]+://! m  i", $uri, $r)',
                      'preg_match(\'{[a-f0-9]{40}}i\', $identifier)',
                      'preg_match(\'(^([^\\s]+)\\s+\\d+:([a-f0-9]+))\', $branch, $match)',
                      'preg_replace(\'/[!=<> ]/ ',
                      '\', $field)',
                      'preg_replace(\'{{}}\', \'b\', $c)',
                      'preg_match_all("[[\\-\\-readmore\\-\\-]]", $post, $more)',
                      'preg_replace("\'<xre([^\\>]*)>(.*?)" . $this->re_space[\'p\'] . "(.*?)</pre>\'" . $this->re_space[\'m\'], "<xre\\1>\\2&nbsp;\\3</pre>", $html_b)',
                      'preg_match("/$regexpMatch/$caseFlag", $file)',
                      'preg_match("/$regexpMatch/".$caseFlag, $file)',
                     );

?>