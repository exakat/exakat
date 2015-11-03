<?php

// searching for Classes, not aliases

namespace a\b;

use a\b\ClassCaseNotOK as AliasCaseNotOK;
use a\b\ClassCaseOK as AliasCaseOK ;

new aliascasenotok();    // find
new AliasCaseOK();       // not find

new classcasenotok();    // find
new ClassCaseOK();       // not find

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
class ClassCaseNotok{ const x = 2; }

?>