<?php

preg_replace('~AAAA~', 'B', $str);
preg_replace('~AAAB~i', 'B', $str);
preg_replace('~AAAC~e', 'B', $str);
preg_replace('/AAAD/ei', 'B', $str);
preg_replace('/AAAE/asdf', 'B', $str);
preg_replace('/AAAF/mixe', 'B', $str);
preg_replace('/AAAG/eximU', 'B', $str);

// with {}
preg_replace('{^\xEF\xBB\xBF|\x1A}', '', $text);
preg_replace('{^\xEF\xBB\xBF|\x1A}ie', '', $text2);

// with ()
preg_replace('(^\xEF\xBB\xBF|\x1A)', '', $text);
preg_replace('(^\xEF\xBB\xBF|\x1A)ie', '', $text2);

// with []
preg_replace('[^\xEF\xBB\xBF|\x1A]', '', $text);
preg_replace('[^\xEF\xBB\xBF|\x1A]ie', '', $text2);


preg_replace('/A'.$x.'HH/ximU', 'B', $str);
preg_replace('/A'.$x.'H/eximU', 'B', $str);

preg_replace("/A$x II/ximU", 'B', $str);
preg_replace("/A$x I/eximU", 'B', $str);
