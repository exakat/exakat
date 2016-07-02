<?php

//normal and OK
print "OK";
print $OK;
print '$KO';
print <<<'NOWDOC'
nowdoc even with $var is fine.
NOWDOC;

//concatenation 
print "$KO";
print "should$be with comma";
print 'should'.'also'.$be.' with comma';
print <<<HEREDOC
should' too $be with comma
HEREDOC;

// fractal concat
print "should". "really $be with comma";

// OK, with comma
print <<<HEREDOC
should", $be, " all", "ok"
HEREDOC;

?>