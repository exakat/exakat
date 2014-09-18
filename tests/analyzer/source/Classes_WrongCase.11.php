<?php

namespace a\b;

use a\b\ClassCaseOK as AliasCaseNotOK;
use a\b\ClassCaseOK as AliasCaseOK ;

new aliascasenotok();    // find
new AliasCaseOK();       // not find

print aliascasenotok::x; // find
print AliasCaseOK::x;    // not find

print $x instanceof aliascasenotok; // find
print $x instanceof AliasCaseOK;    // not find

print aliascasenotok::$x; // find
print AliasCaseOK::$x;    // not find

print aliascasenotok::x(); // find
print AliasCaseOK::x();    // not find

function xKO (aliascasenotok $a) {}
function xOK (AliasCaseOK $a) {}

try{}
catch (aliascasenotok $e) {}
catch (AliasCaseOK $e) {}

class ClassCaseOK { const x = 1;}

?>