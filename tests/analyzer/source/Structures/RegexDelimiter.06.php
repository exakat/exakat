<?php

if (false === @preg_replace(' b e', null, $r)) { print "Error with 0 :  - \n"; }
if (false === @preg_replace('  b e', null, $r)) { print "Error with 0 :  - \n"; }
if (false === @preg_replace('   b e', null, $r)) { print "Error with 0 :  - \n"; }
if (false === @preg_replace('ce', null, $r)) { print "Error with 1 : -\n"; }
if (false === @preg_replace(' ce', null, $r)) { print "Error with 1 : -\n"; }
if (false === @preg_replace('  ce', null, $r)) { print "Error with 1 : -\n"; }
if (false === @preg_replace('de', null, $r)) { print "Error with 2 : -\n"; }
if (false === @preg_replace(' de', null, $r)) { print "Error with 2 : -\n"; }
if (false === @preg_replace('  de', null, $r)) { print "Error with 2 : -\n"; }
if (false === @preg_replace('ee', null, $r)) { print "Error with 3 : -\n"; }
if (false === @preg_replace(' ee', null, $r)) { print "Error with 3 : -\n"; }
if (false === @preg_replace('  ee', null, $r)) { print "Error with 3 : -\n"; }
if (false === @preg_replace('fe', null, $r)) { print "Error with 4 : -\n"; }
if (false === @preg_replace(' fe', null, $r)) { print "Error with 4 : -\n"; }
if (false === @preg_replace('  fe', null, $r)) { print "Error with 4 : -\n"; }
if (false === @preg_replace('ge', null, $r)) { print "Error with 5 : -\n"; }
if (false === @preg_replace(' ge', null, $r)) { print "Error with 5 : -\n"; }
if (false === @preg_replace('  ge', null, $r)) { print "Error with 5 : -\n"; }
if (false === @preg_replace('he', null, $r)) { print "Error with 6 : -\n"; }
if (false === @preg_replace(' he', null, $r)) { print "Error with 6 : -\n"; }
if (false === @preg_replace('  he', null, $r)) { print "Error with 6 : -\n"; }

/* Removed from the tests
if (false === @preg_replace('ie', null, $r)) { print "Error with 7 : -\n"; }
if (false === @preg_replace(' ie', null, $r)) { print "Error with 7 : -\n"; }
if (false === @preg_replace('  ie', null, $r)) { print "Error with 7 : -\n"; }
*/

if (false === @preg_replace('je', null, $r)) { print "Error with 8 : -\n"; }
if (false === @preg_replace(' je', null, $r)) { print "Error with 8 : -\n"; }
if (false === @preg_replace('  je', null, $r)) { print "Error with 8 : -\n"; }
if (false === @preg_replace('	k	e', null, $r)) { print "Error with 9 : 	-	\n"; }
if (false === @preg_replace(' 	k	e', null, $r)) { print "Error with 9 : 	-	\n"; }
if (false === @preg_replace('  	k	e', null, $r)) { print "Error with 9 : 	-	\n"; }
if (false === @preg_replace('
l
e', null, $r)) { print "Error with 10 : 
-
\n"; }
if (false === @preg_replace(' 
l
e', null, $r)) { print "Error with 10 : 
-
\n"; }
if (false === @preg_replace('  
l
e', null, $r)) { print "Error with 10 : 
-
\n"; }

if (false === @preg_replace('me', null, $r)) { print "Error with 11 : -\n"; }
if (false === @preg_replace(' me', null, $r)) { print "Error with 11 : -\n"; }
if (false === @preg_replace('  me', null, $r)) { print "Error with 11 : -\n"; }
if (false === @preg_replace('ne', null, $r)) { print "Error with 12 : -\n"; }
if (false === @preg_replace(' ne', null, $r)) { print "Error with 12 : -\n"; }
if (false === @preg_replace('  ne', null, $r)) { print "Error with 12 : -\n"; }
if (false === @preg_replace('oe', null, $r)) { print "Error with 13 : -\n"; }
if (false === @preg_replace(' oe', null, $r)) { print "Error with 13 : -\n"; }
if (false === @preg_replace('  oe', null, $r)) { print "Error with 13 : -\n"; }
if (false === @preg_replace('pe', null, $r)) { print "Error with 14 : -\n"; }
if (false === @preg_replace(' pe', null, $r)) { print "Error with 14 : -\n"; }
if (false === @preg_replace('  pe', null, $r)) { print "Error with 14 : -\n"; }
if (false === @preg_replace('qe', null, $r)) { print "Error with 15 : -\n"; }
if (false === @preg_replace(' qe', null, $r)) { print "Error with 15 : -\n"; }
if (false === @preg_replace('  qe', null, $r)) { print "Error with 15 : -\n"; }
if (false === @preg_replace('re', null, $r)) { print "Error with 16 : -\n"; }
if (false === @preg_replace(' re', null, $r)) { print "Error with 16 : -\n"; }
if (false === @preg_replace('  re', null, $r)) { print "Error with 16 : -\n"; }
if (false === @preg_replace('se', null, $r)) { print "Error with 17 : -\n"; }
if (false === @preg_replace(' se', null, $r)) { print "Error with 17 : -\n"; }
if (false === @preg_replace('  se', null, $r)) { print "Error with 17 : -\n"; }
if (false === @preg_replace('te', null, $r)) { print "Error with 18 : -\n"; }
if (false === @preg_replace(' te', null, $r)) { print "Error with 18 : -\n"; }
if (false === @preg_replace('  te', null, $r)) { print "Error with 18 : -\n"; }
if (false === @preg_replace('ue', null, $r)) { print "Error with 19 : -\n"; }
if (false === @preg_replace(' ue', null, $r)) { print "Error with 19 : -\n"; }
if (false === @preg_replace('  ue', null, $r)) { print "Error with 19 : -\n"; }
if (false === @preg_replace('ve', null, $r)) { print "Error with 20 : -\n"; }
if (false === @preg_replace(' ve', null, $r)) { print "Error with 20 : -\n"; }
if (false === @preg_replace('  ve', null, $r)) { print "Error with 20 : -\n"; }
if (false === @preg_replace('we', null, $r)) { print "Error with 21 : -\n"; }
if (false === @preg_replace(' we', null, $r)) { print "Error with 21 : -\n"; }
if (false === @preg_replace('  we', null, $r)) { print "Error with 21 : -\n"; }
if (false === @preg_replace('xe', null, $r)) { print "Error with 22 : -\n"; }
if (false === @preg_replace(' xe', null, $r)) { print "Error with 22 : -\n"; }
if (false === @preg_replace('  xe', null, $r)) { print "Error with 22 : -\n"; }
if (false === @preg_replace('ye', null, $r)) { print "Error with 23 : -\n"; }
if (false === @preg_replace(' ye', null, $r)) { print "Error with 23 : -\n"; }
if (false === @preg_replace('  ye', null, $r)) { print "Error with 23 : -\n"; }
if (false === @preg_replace('ze', null, $r)) { print "Error with 24 : -\n"; }
if (false === @preg_replace(' ze', null, $r)) { print "Error with 24 : -\n"; }
if (false === @preg_replace('  ze', null, $r)) { print "Error with 24 : -\n"; }
if (false === @preg_replace('aae', null, $r)) { print "Error with 25 : -\n"; }
if (false === @preg_replace(' aae', null, $r)) { print "Error with 25 : -\n"; }
if (false === @preg_replace('  aae', null, $r)) { print "Error with 25 : -\n"; }
if (false === @preg_replace('abe', null, $r)) { print "Error with 26 : -\n"; }
if (false === @preg_replace(' abe', null, $r)) { print "Error with 26 : -\n"; }
if (false === @preg_replace('  abe', null, $r)) { print "Error with 26 : -\n"; }
if (false === @preg_replace('ace', null, $r)) { print "Error with 27 : -\n"; }
if (false === @preg_replace(' ace', null, $r)) { print "Error with 27 : -\n"; }
if (false === @preg_replace('  ace', null, $r)) { print "Error with 27 : -\n"; }
if (false === @preg_replace('ade', null, $r)) { print "Error with 28 : -\n"; }

if (false === @preg_replace(' ade', null, $r)) { print "Error with 28 : -\n"; }
if (false === @preg_replace('  ade', null, $r)) { print "Error with 28 : -\n"; }
if (false === @preg_replace('aee', null, $r)) { print "Error with 29 : -\n"; }
if (false === @preg_replace(' aee', null, $r)) { print "Error with 29 : -\n"; }
if (false === @preg_replace('  aee', null, $r)) { print "Error with 29 : -\n"; }
if (false === @preg_replace('afe', null, $r)) { print "Error with 30 : -\n"; }
if (false === @preg_replace(' afe', null, $r)) { print "Error with 30 : -\n"; }
if (false === @preg_replace('  afe', null, $r)) { print "Error with 30 : -\n"; }
if (false === @preg_replace('age', null, $r)) { print "Error with 31 : -\n"; }
if (false === @preg_replace(' age', null, $r)) { print "Error with 31 : -\n"; }
if (false === @preg_replace('  age', null, $r)) { print "Error with 31 : -\n"; }
if (false === @preg_replace(' ah e', null, $r)) { print "Error with 32 :  - \n"; }
if (false === @preg_replace('  ah e', null, $r)) { print "Error with 32 :  - \n"; }
if (false === @preg_replace('   ah e', null, $r)) { print "Error with 32 :  - \n"; }
if (false === @preg_replace('!ai!e', null, $r)) { print "Error with 33 : !-!\n"; }
if (false === @preg_replace(' !ai!e', null, $r)) { print "Error with 33 : !-!\n"; }
if (false === @preg_replace('  !ai!e', null, $r)) { print "Error with 33 : !-!\n"; }
if (false === @preg_replace('"aj"e', null, $r)) { print "Error with 34 : "-"\n"; }
if (false === @preg_replace(' "aj"e', null, $r)) { print "Error with 34 : "-"\n"; }
if (false === @preg_replace('  "aj"e', null, $r)) { print "Error with 34 : "-"\n"; }
if (false === @preg_replace('#ak#e', null, $r)) { print "Error with 35 : #-#\n"; }
if (false === @preg_replace(' #ak#e', null, $r)) { print "Error with 35 : #-#\n"; }
if (false === @preg_replace('  #ak#e', null, $r)) { print "Error with 35 : #-#\n"; }
if (false === @preg_replace('$al$e', null, $r)) { print "Error with 36 : $-$\n"; }
if (false === @preg_replace(' $al$e', null, $r)) { print "Error with 36 : $-$\n"; }
if (false === @preg_replace('  $al$e', null, $r)) { print "Error with 36 : $-$\n"; }
if (false === @preg_replace('%am%e', null, $r)) { print "Error with 37 : %-%\n"; }
if (false === @preg_replace(' %am%e', null, $r)) { print "Error with 37 : %-%\n"; }
if (false === @preg_replace('  %am%e', null, $r)) { print "Error with 37 : %-%\n"; }
if (false === @preg_replace('&an&e', null, $r)) { print "Error with 38 : &-&\n"; }
if (false === @preg_replace(' &an&e', null, $r)) { print "Error with 38 : &-&\n"; }
if (false === @preg_replace('  &an&e', null, $r)) { print "Error with 38 : &-&\n"; }
if (false === @preg_replace('(ap)e', null, $r)) { print "Error with 40 : (-)\n"; }
if (false === @preg_replace(' (ap)e', null, $r)) { print "Error with 40 : (-)\n"; }
if (false === @preg_replace('  (ap)e', null, $r)) { print "Error with 40 : (-)\n"; }
if (false === @preg_replace(')aq)e', null, $r)) { print "Error with 41 : )-)\n"; }
if (false === @preg_replace(' )aq)e', null, $r)) { print "Error with 41 : )-)\n"; }
if (false === @preg_replace('  )aq)e', null, $r)) { print "Error with 41 : )-)\n"; }
if (false === @preg_replace('*ar*e', null, $r)) { print "Error with 42 : *-*\n"; }
if (false === @preg_replace(' *ar*e', null, $r)) { print "Error with 42 : *-*\n"; }
if (false === @preg_replace('  *ar*e', null, $r)) { print "Error with 42 : *-*\n"; }
if (false === @preg_replace('+as+e', null, $r)) { print "Error with 43 : +-+\n"; }
if (false === @preg_replace(' +as+e', null, $r)) { print "Error with 43 : +-+\n"; }
if (false === @preg_replace('  +as+e', null, $r)) { print "Error with 43 : +-+\n"; }
if (false === @preg_replace(',at,e', null, $r)) { print "Error with 44 : ,-,\n"; }
if (false === @preg_replace(' ,at,e', null, $r)) { print "Error with 44 : ,-,\n"; }
if (false === @preg_replace('  ,at,e', null, $r)) { print "Error with 44 : ,-,\n"; }
if (false === @preg_replace('-au-e', null, $r)) { print "Error with 45 : ---\n"; }
if (false === @preg_replace(' -au-e', null, $r)) { print "Error with 45 : ---\n"; }
if (false === @preg_replace('  -au-e', null, $r)) { print "Error with 45 : ---\n"; }
if (false === @preg_replace('.av.e', null, $r)) { print "Error with 46 : .-.\n"; }
if (false === @preg_replace(' .av.e', null, $r)) { print "Error with 46 : .-.\n"; }
if (false === @preg_replace('  .av.e', null, $r)) { print "Error with 46 : .-.\n"; }
if (false === @preg_replace('/aw/e', null, $r)) { print "Error with 47 : /-/\n"; }
if (false === @preg_replace(' /aw/e', null, $r)) { print "Error with 47 : /-/\n"; }
if (false === @preg_replace('  /aw/e', null, $r)) { print "Error with 47 : /-/\n"; }
if (false === @preg_replace('0ax0e', null, $r)) { print "Error with 48 : 0-0\n"; }
if (false === @preg_replace(' 0ax0e', null, $r)) { print "Error with 48 : 0-0\n"; }
if (false === @preg_replace('  0ax0e', null, $r)) { print "Error with 48 : 0-0\n"; }
if (false === @preg_replace('1ay1e', null, $r)) { print "Error with 49 : 1-1\n"; }
if (false === @preg_replace(' 1ay1e', null, $r)) { print "Error with 49 : 1-1\n"; }
if (false === @preg_replace('  1ay1e', null, $r)) { print "Error with 49 : 1-1\n"; }
if (false === @preg_replace('2az2e', null, $r)) { print "Error with 50 : 2-2\n"; }
if (false === @preg_replace(' 2az2e', null, $r)) { print "Error with 50 : 2-2\n"; }
if (false === @preg_replace('  2az2e', null, $r)) { print "Error with 50 : 2-2\n"; }
if (false === @preg_replace('3ba3e', null, $r)) { print "Error with 51 : 3-3\n"; }
if (false === @preg_replace(' 3ba3e', null, $r)) { print "Error with 51 : 3-3\n"; }
if (false === @preg_replace('  3ba3e', null, $r)) { print "Error with 51 : 3-3\n"; }
if (false === @preg_replace('4bb4e', null, $r)) { print "Error with 52 : 4-4\n"; }
if (false === @preg_replace(' 4bb4e', null, $r)) { print "Error with 52 : 4-4\n"; }
if (false === @preg_replace('  4bb4e', null, $r)) { print "Error with 52 : 4-4\n"; }
if (false === @preg_replace('5bc5e', null, $r)) { print "Error with 53 : 5-5\n"; }
if (false === @preg_replace(' 5bc5e', null, $r)) { print "Error with 53 : 5-5\n"; }
if (false === @preg_replace('  5bc5e', null, $r)) { print "Error with 53 : 5-5\n"; }
if (false === @preg_replace('6bd6e', null, $r)) { print "Error with 54 : 6-6\n"; }
if (false === @preg_replace(' 6bd6e', null, $r)) { print "Error with 54 : 6-6\n"; }
if (false === @preg_replace('  6bd6e', null, $r)) { print "Error with 54 : 6-6\n"; }
if (false === @preg_replace('7be7e', null, $r)) { print "Error with 55 : 7-7\n"; }
if (false === @preg_replace(' 7be7e', null, $r)) { print "Error with 55 : 7-7\n"; }
if (false === @preg_replace('  7be7e', null, $r)) { print "Error with 55 : 7-7\n"; }
if (false === @preg_replace('8bf8e', null, $r)) { print "Error with 56 : 8-8\n"; }
if (false === @preg_replace(' 8bf8e', null, $r)) { print "Error with 56 : 8-8\n"; }
if (false === @preg_replace('  8bf8e', null, $r)) { print "Error with 56 : 8-8\n"; }
if (false === @preg_replace('9bg9e', null, $r)) { print "Error with 57 : 9-9\n"; }
if (false === @preg_replace(' 9bg9e', null, $r)) { print "Error with 57 : 9-9\n"; }
if (false === @preg_replace('  9bg9e', null, $r)) { print "Error with 57 : 9-9\n"; }
if (false === @preg_replace(':bh:e', null, $r)) { print "Error with 58 : :-:\n"; }
if (false === @preg_replace(' :bh:e', null, $r)) { print "Error with 58 : :-:\n"; }
if (false === @preg_replace('  :bh:e', null, $r)) { print "Error with 58 : :-:\n"; }
if (false === @preg_replace(';bi;e', null, $r)) { print "Error with 59 : ;-;\n"; }
if (false === @preg_replace(' ;bi;e', null, $r)) { print "Error with 59 : ;-;\n"; }
if (false === @preg_replace('  ;bi;e', null, $r)) { print "Error with 59 : ;-;\n"; }
if (false === @preg_replace('<bj<e', null, $r)) { print "Error with 60 : <-<\n"; }
if (false === @preg_replace(' <bj<e', null, $r)) { print "Error with 60 : <-<\n"; }
if (false === @preg_replace('  <bj<e', null, $r)) { print "Error with 60 : <-<\n"; }
if (false === @preg_replace('=bk=e', null, $r)) { print "Error with 61 : =-=\n"; }
if (false === @preg_replace(' =bk=e', null, $r)) { print "Error with 61 : =-=\n"; }
if (false === @preg_replace('  =bk=e', null, $r)) { print "Error with 61 : =-=\n"; }
if (false === @preg_replace('>bl>e', null, $r)) { print "Error with 62 : >->\n"; }
if (false === @preg_replace(' >bl>e', null, $r)) { print "Error with 62 : >->\n"; }

if (false === @preg_replace('  >bl>e', null, $r)) { print "Error with 62 : >->\n"; }
if (false === @preg_replace('?bm?e', null, $r)) { print "Error with 63 : ?-?\n"; }
if (false === @preg_replace(' ?bm?e', null, $r)) { print "Error with 63 : ?-?\n"; }
if (false === @preg_replace('  ?bm?e', null, $r)) { print "Error with 63 : ?-?\n"; }
if (false === @preg_replace('@bn@e', null, $r)) { print "Error with 64 : @-@\n"; }
if (false === @preg_replace(' @bn@e', null, $r)) { print "Error with 64 : @-@\n"; }
if (false === @preg_replace('  @bn@e', null, $r)) { print "Error with 64 : @-@\n"; }
if (false === @preg_replace('AboAe', null, $r)) { print "Error with 65 : A-A\n"; }
if (false === @preg_replace(' AboAe', null, $r)) { print "Error with 65 : A-A\n"; }
if (false === @preg_replace('  AboAe', null, $r)) { print "Error with 65 : A-A\n"; }
if (false === @preg_replace('BbpBe', null, $r)) { print "Error with 66 : B-B\n"; }
if (false === @preg_replace(' BbpBe', null, $r)) { print "Error with 66 : B-B\n"; }
if (false === @preg_replace('  BbpBe', null, $r)) { print "Error with 66 : B-B\n"; }
if (false === @preg_replace('CbqCe', null, $r)) { print "Error with 67 : C-C\n"; }
if (false === @preg_replace(' CbqCe', null, $r)) { print "Error with 67 : C-C\n"; }
if (false === @preg_replace('  CbqCe', null, $r)) { print "Error with 67 : C-C\n"; }
if (false === @preg_replace('DbrDe', null, $r)) { print "Error with 68 : D-D\n"; }
if (false === @preg_replace(' DbrDe', null, $r)) { print "Error with 68 : D-D\n"; }
if (false === @preg_replace('  DbrDe', null, $r)) { print "Error with 68 : D-D\n"; }
if (false === @preg_replace('EbsEe', null, $r)) { print "Error with 69 : E-E\n"; }
if (false === @preg_replace(' EbsEe', null, $r)) { print "Error with 69 : E-E\n"; }
if (false === @preg_replace('  EbsEe', null, $r)) { print "Error with 69 : E-E\n"; }
if (false === @preg_replace('FbtFe', null, $r)) { print "Error with 70 : F-F\n"; }
if (false === @preg_replace(' FbtFe', null, $r)) { print "Error with 70 : F-F\n"; }
if (false === @preg_replace('  FbtFe', null, $r)) { print "Error with 70 : F-F\n"; }
if (false === @preg_replace('GbuGe', null, $r)) { print "Error with 71 : G-G\n"; }
if (false === @preg_replace(' GbuGe', null, $r)) { print "Error with 71 : G-G\n"; }
if (false === @preg_replace('  GbuGe', null, $r)) { print "Error with 71 : G-G\n"; }
if (false === @preg_replace('HbvHe', null, $r)) { print "Error with 72 : H-H\n"; }
if (false === @preg_replace(' HbvHe', null, $r)) { print "Error with 72 : H-H\n"; }
if (false === @preg_replace('  HbvHe', null, $r)) { print "Error with 72 : H-H\n"; }
if (false === @preg_replace('IbwIe', null, $r)) { print "Error with 73 : I-I\n"; }
if (false === @preg_replace(' IbwIe', null, $r)) { print "Error with 73 : I-I\n"; }
if (false === @preg_replace('  IbwIe', null, $r)) { print "Error with 73 : I-I\n"; }
if (false === @preg_replace('JbxJe', null, $r)) { print "Error with 74 : J-J\n"; }
if (false === @preg_replace(' JbxJe', null, $r)) { print "Error with 74 : J-J\n"; }
if (false === @preg_replace('  JbxJe', null, $r)) { print "Error with 74 : J-J\n"; }
if (false === @preg_replace('KbyKe', null, $r)) { print "Error with 75 : K-K\n"; }
if (false === @preg_replace(' KbyKe', null, $r)) { print "Error with 75 : K-K\n"; }
if (false === @preg_replace('  KbyKe', null, $r)) { print "Error with 75 : K-K\n"; }
if (false === @preg_replace('LbzLe', null, $r)) { print "Error with 76 : L-L\n"; }
if (false === @preg_replace(' LbzLe', null, $r)) { print "Error with 76 : L-L\n"; }
if (false === @preg_replace('  LbzLe', null, $r)) { print "Error with 76 : L-L\n"; }
if (false === @preg_replace('McaMe', null, $r)) { print "Error with 77 : M-M\n"; }
if (false === @preg_replace(' McaMe', null, $r)) { print "Error with 77 : M-M\n"; }
if (false === @preg_replace('  McaMe', null, $r)) { print "Error with 77 : M-M\n"; }
if (false === @preg_replace('NcbNe', null, $r)) { print "Error with 78 : N-N\n"; }
if (false === @preg_replace(' NcbNe', null, $r)) { print "Error with 78 : N-N\n"; }
if (false === @preg_replace('  NcbNe', null, $r)) { print "Error with 78 : N-N\n"; }
if (false === @preg_replace('OccOe', null, $r)) { print "Error with 79 : O-O\n"; }
if (false === @preg_replace(' OccOe', null, $r)) { print "Error with 79 : O-O\n"; }
if (false === @preg_replace('  OccOe', null, $r)) { print "Error with 79 : O-O\n"; }
if (false === @preg_replace('PcdPe', null, $r)) { print "Error with 80 : P-P\n"; }
if (false === @preg_replace(' PcdPe', null, $r)) { print "Error with 80 : P-P\n"; }
if (false === @preg_replace('  PcdPe', null, $r)) { print "Error with 80 : P-P\n"; }
if (false === @preg_replace('QceQe', null, $r)) { print "Error with 81 : Q-Q\n"; }
if (false === @preg_replace(' QceQe', null, $r)) { print "Error with 81 : Q-Q\n"; }
if (false === @preg_replace('  QceQe', null, $r)) { print "Error with 81 : Q-Q\n"; }
if (false === @preg_replace('RcfRe', null, $r)) { print "Error with 82 : R-R\n"; }
if (false === @preg_replace(' RcfRe', null, $r)) { print "Error with 82 : R-R\n"; }
if (false === @preg_replace('  RcfRe', null, $r)) { print "Error with 82 : R-R\n"; }
if (false === @preg_replace('ScgSe', null, $r)) { print "Error with 83 : S-S\n"; }
if (false === @preg_replace(' ScgSe', null, $r)) { print "Error with 83 : S-S\n"; }
if (false === @preg_replace('  ScgSe', null, $r)) { print "Error with 83 : S-S\n"; }
if (false === @preg_replace('TchTe', null, $r)) { print "Error with 84 : T-T\n"; }
if (false === @preg_replace(' TchTe', null, $r)) { print "Error with 84 : T-T\n"; }
if (false === @preg_replace('  TchTe', null, $r)) { print "Error with 84 : T-T\n"; }
if (false === @preg_replace('UciUe', null, $r)) { print "Error with 85 : U-U\n"; }
if (false === @preg_replace(' UciUe', null, $r)) { print "Error with 85 : U-U\n"; }
if (false === @preg_replace('  UciUe', null, $r)) { print "Error with 85 : U-U\n"; }
if (false === @preg_replace('VcjVe', null, $r)) { print "Error with 86 : V-V\n"; }
if (false === @preg_replace(' VcjVe', null, $r)) { print "Error with 86 : V-V\n"; }
if (false === @preg_replace('  VcjVe', null, $r)) { print "Error with 86 : V-V\n"; }
if (false === @preg_replace('WckWe', null, $r)) { print "Error with 87 : W-W\n"; }
if (false === @preg_replace(' WckWe', null, $r)) { print "Error with 87 : W-W\n"; }
if (false === @preg_replace('  WckWe', null, $r)) { print "Error with 87 : W-W\n"; }
if (false === @preg_replace('XclXe', null, $r)) { print "Error with 88 : X-X\n"; }
if (false === @preg_replace(' XclXe', null, $r)) { print "Error with 88 : X-X\n"; }
if (false === @preg_replace('  XclXe', null, $r)) { print "Error with 88 : X-X\n"; }
if (false === @preg_replace('YcmYe', null, $r)) { print "Error with 89 : Y-Y\n"; }
if (false === @preg_replace(' YcmYe', null, $r)) { print "Error with 89 : Y-Y\n"; }
if (false === @preg_replace('  YcmYe', null, $r)) { print "Error with 89 : Y-Y\n"; }
if (false === @preg_replace('ZcnZe', null, $r)) { print "Error with 90 : Z-Z\n"; }
if (false === @preg_replace(' ZcnZe', null, $r)) { print "Error with 90 : Z-Z\n"; }
if (false === @preg_replace('  ZcnZe', null, $r)) { print "Error with 90 : Z-Z\n"; }
if (false === @preg_replace('[co]e', null, $r)) { print "Error with 91 : [-]\n"; }
if (false === @preg_replace(' [co]e', null, $r)) { print "Error with 91 : [-]\n"; }
if (false === @preg_replace('  [co]e', null, $r)) { print "Error with 91 : [-]\n"; }
if (false === @preg_replace('\\cp\\e', null, $r)) { print "Error with 92 : \\-\\\n"; }
if (false === @preg_replace(' \\cp\\e', null, $r)) { print "Error with 92 : \\-\\\n"; }
if (false === @preg_replace('  \\cp\\e', null, $r)) { print "Error with 92 : \\-\\\n"; }
if (false === @preg_replace(']cq]e', null, $r)) { print "Error with 93 : ]-]\n"; }
if (false === @preg_replace(' ]cq]e', null, $r)) { print "Error with 93 : ]-]\n"; }
if (false === @preg_replace('  ]cq]e', null, $r)) { print "Error with 93 : ]-]\n"; }
if (false === @preg_replace('^cr^e', null, $r)) { print "Error with 94 : ^-^\n"; }
if (false === @preg_replace(' ^cr^e', null, $r)) { print "Error with 94 : ^-^\n"; }
if (false === @preg_replace('  ^cr^e', null, $r)) { print "Error with 94 : ^-^\n"; }
if (false === @preg_replace('_cs_e', null, $r)) { print "Error with 95 : _-_\n"; }
if (false === @preg_replace(' _cs_e', null, $r)) { print "Error with 95 : _-_\n"; }
if (false === @preg_replace('  _cs_e', null, $r)) { print "Error with 95 : _-_\n"; }
if (false === @preg_replace('`ct`e', null, $r)) { print "Error with 96 : `-`\n"; }
if (false === @preg_replace(' `ct`e', null, $r)) { print "Error with 96 : `-`\n"; }
if (false === @preg_replace('  `ct`e', null, $r)) { print "Error with 96 : `-`\n"; }
if (false === @preg_replace('acuae', null, $r)) { print "Error with 97 : a-a\n"; }
if (false === @preg_replace(' acuae', null, $r)) { print "Error with 97 : a-a\n"; }
if (false === @preg_replace('  acuae', null, $r)) { print "Error with 97 : a-a\n"; }
if (false === @preg_replace('bcvbe', null, $r)) { print "Error with 98 : b-b\n"; }
if (false === @preg_replace(' bcvbe', null, $r)) { print "Error with 98 : b-b\n"; }
if (false === @preg_replace('  bcvbe', null, $r)) { print "Error with 98 : b-b\n"; }
if (false === @preg_replace('ccwce', null, $r)) { print "Error with 99 : c-c\n"; }
if (false === @preg_replace(' ccwce', null, $r)) { print "Error with 99 : c-c\n"; }
if (false === @preg_replace('  ccwce', null, $r)) { print "Error with 99 : c-c\n"; }
if (false === @preg_replace('dcxde', null, $r)) { print "Error with 100 : d-d\n"; }
if (false === @preg_replace(' dcxde', null, $r)) { print "Error with 100 : d-d\n"; }
if (false === @preg_replace('  dcxde', null, $r)) { print "Error with 100 : d-d\n"; }
if (false === @preg_replace('ecyee', null, $r)) { print "Error with 101 : e-e\n"; }
if (false === @preg_replace(' ecyee', null, $r)) { print "Error with 101 : e-e\n"; }
if (false === @preg_replace('  ecyee', null, $r)) { print "Error with 101 : e-e\n"; }
if (false === @preg_replace('fczfe', null, $r)) { print "Error with 102 : f-f\n"; }
if (false === @preg_replace(' fczfe', null, $r)) { print "Error with 102 : f-f\n"; }
if (false === @preg_replace('  fczfe', null, $r)) { print "Error with 102 : f-f\n"; }
if (false === @preg_replace('gdage', null, $r)) { print "Error with 103 : g-g\n"; }
if (false === @preg_replace(' gdage', null, $r)) { print "Error with 103 : g-g\n"; }
if (false === @preg_replace('  gdage', null, $r)) { print "Error with 103 : g-g\n"; }
if (false === @preg_replace('hdbhe', null, $r)) { print "Error with 104 : h-h\n"; }
if (false === @preg_replace(' hdbhe', null, $r)) { print "Error with 104 : h-h\n"; }
if (false === @preg_replace('  hdbhe', null, $r)) { print "Error with 104 : h-h\n"; }
if (false === @preg_replace('idcie', null, $r)) { print "Error with 105 : i-i\n"; }
if (false === @preg_replace(' idcie', null, $r)) { print "Error with 105 : i-i\n"; }
if (false === @preg_replace('  idcie', null, $r)) { print "Error with 105 : i-i\n"; }
if (false === @preg_replace('jddje', null, $r)) { print "Error with 106 : j-j\n"; }
if (false === @preg_replace(' jddje', null, $r)) { print "Error with 106 : j-j\n"; }
if (false === @preg_replace('  jddje', null, $r)) { print "Error with 106 : j-j\n"; }
if (false === @preg_replace('kdeke', null, $r)) { print "Error with 107 : k-k\n"; }
if (false === @preg_replace(' kdeke', null, $r)) { print "Error with 107 : k-k\n"; }
if (false === @preg_replace('  kdeke', null, $r)) { print "Error with 107 : k-k\n"; }
if (false === @preg_replace('ldfle', null, $r)) { print "Error with 108 : l-l\n"; }
if (false === @preg_replace(' ldfle', null, $r)) { print "Error with 108 : l-l\n"; }
if (false === @preg_replace('  ldfle', null, $r)) { print "Error with 108 : l-l\n"; }
if (false === @preg_replace('mdgme', null, $r)) { print "Error with 109 : m-m\n"; }
if (false === @preg_replace(' mdgme', null, $r)) { print "Error with 109 : m-m\n"; }
if (false === @preg_replace('  mdgme', null, $r)) { print "Error with 109 : m-m\n"; }
if (false === @preg_replace('ndhne', null, $r)) { print "Error with 110 : n-n\n"; }
if (false === @preg_replace(' ndhne', null, $r)) { print "Error with 110 : n-n\n"; }
if (false === @preg_replace('  ndhne', null, $r)) { print "Error with 110 : n-n\n"; }
if (false === @preg_replace('odioe', null, $r)) { print "Error with 111 : o-o\n"; }
if (false === @preg_replace(' odioe', null, $r)) { print "Error with 111 : o-o\n"; }
if (false === @preg_replace('  odioe', null, $r)) { print "Error with 111 : o-o\n"; }
if (false === @preg_replace('pdjpe', null, $r)) { print "Error with 112 : p-p\n"; }
if (false === @preg_replace(' pdjpe', null, $r)) { print "Error with 112 : p-p\n"; }
if (false === @preg_replace('  pdjpe', null, $r)) { print "Error with 112 : p-p\n"; }
if (false === @preg_replace('qdkqe', null, $r)) { print "Error with 113 : q-q\n"; }
if (false === @preg_replace(' qdkqe', null, $r)) { print "Error with 113 : q-q\n"; }
if (false === @preg_replace('  qdkqe', null, $r)) { print "Error with 113 : q-q\n"; }
if (false === @preg_replace('rdlre', null, $r)) { print "Error with 114 : r-r\n"; }
if (false === @preg_replace(' rdlre', null, $r)) { print "Error with 114 : r-r\n"; }
if (false === @preg_replace('  rdlre', null, $r)) { print "Error with 114 : r-r\n"; }
if (false === @preg_replace('sdmse', null, $r)) { print "Error with 115 : s-s\n"; }
if (false === @preg_replace(' sdmse', null, $r)) { print "Error with 115 : s-s\n"; }
if (false === @preg_replace('  sdmse', null, $r)) { print "Error with 115 : s-s\n"; }
if (false === @preg_replace('tdnte', null, $r)) { print "Error with 116 : t-t\n"; }
if (false === @preg_replace(' tdnte', null, $r)) { print "Error with 116 : t-t\n"; }
if (false === @preg_replace('  tdnte', null, $r)) { print "Error with 116 : t-t\n"; }
if (false === @preg_replace('udoue', null, $r)) { print "Error with 117 : u-u\n"; }
if (false === @preg_replace(' udoue', null, $r)) { print "Error with 117 : u-u\n"; }
if (false === @preg_replace('  udoue', null, $r)) { print "Error with 117 : u-u\n"; }
if (false === @preg_replace('vdpve', null, $r)) { print "Error with 118 : v-v\n"; }
if (false === @preg_replace(' vdpve', null, $r)) { print "Error with 118 : v-v\n"; }
if (false === @preg_replace('  vdpve', null, $r)) { print "Error with 118 : v-v\n"; }
if (false === @preg_replace('wdqwe', null, $r)) { print "Error with 119 : w-w\n"; }
if (false === @preg_replace(' wdqwe', null, $r)) { print "Error with 119 : w-w\n"; }
if (false === @preg_replace('  wdqwe', null, $r)) { print "Error with 119 : w-w\n"; }
if (false === @preg_replace('xdrxe', null, $r)) { print "Error with 120 : x-x\n"; }
if (false === @preg_replace(' xdrxe', null, $r)) { print "Error with 120 : x-x\n"; }
if (false === @preg_replace('  xdrxe', null, $r)) { print "Error with 120 : x-x\n"; }
if (false === @preg_replace('ydsye', null, $r)) { print "Error with 121 : y-y\n"; }
if (false === @preg_replace(' ydsye', null, $r)) { print "Error with 121 : y-y\n"; }
if (false === @preg_replace('  ydsye', null, $r)) { print "Error with 121 : y-y\n"; }
if (false === @preg_replace('zdtze', null, $r)) { print "Error with 122 : z-z\n"; }
if (false === @preg_replace(' zdtze', null, $r)) { print "Error with 122 : z-z\n"; }
if (false === @preg_replace('  zdtze', null, $r)) { print "Error with 122 : z-z\n"; }
if (false === @preg_replace('{du}e', null, $r)) { print "Error with 123 : {-}\n"; }
if (false === @preg_replace(' {du}e', null, $r)) { print "Error with 123 : {-}\n"; }
if (false === @preg_replace('  {du}e', null, $r)) { print "Error with 123 : {-}\n"; }
if (false === @preg_replace('|dv|e', null, $r)) { print "Error with 124 : |-|\n"; }
if (false === @preg_replace(' |dv|e', null, $r)) { print "Error with 124 : |-|\n"; }
if (false === @preg_replace('  |dv|e', null, $r)) { print "Error with 124 : |-|\n"; }
if (false === @preg_replace('}dw}e', null, $r)) { print "Error with 125 : }-}\n"; }
if (false === @preg_replace(' }dw}e', null, $r)) { print "Error with 125 : }-}\n"; }
if (false === @preg_replace('  }dw}e', null, $r)) { print "Error with 125 : }-}\n"; }
if (false === @preg_replace('~dx~e', null, $r)) { print "Error with 126 : ~-~\n"; }
if (false === @preg_replace(' ~dx~e', null, $r)) { print "Error with 126 : ~-~\n"; }
if (false === @preg_replace('  ~dx~e', null, $r)) { print "Error with 126 : ~-~\n"; }
if (false === @preg_replace('dye', null, $r)) { print "Error with 127 : -\n"; }
if (false === @preg_replace(' dye', null, $r)) { print "Error with 127 : -\n"; }
if (false === @preg_replace('  dye', null, $r)) { print "Error with 127 : -\n"; }
if (false === @preg_replace('ÄdzÄe', null, $r)) { print "Error with 128 : Ä-Ä\n"; }
if (false === @preg_replace(' ÄdzÄe', null, $r)) { print "Error with 128 : Ä-Ä\n"; }
if (false === @preg_replace('  ÄdzÄe', null, $r)) { print "Error with 128 : Ä-Ä\n"; }
if (false === @preg_replace('ÅeaÅe', null, $r)) { print "Error with 129 : Å-Å\n"; }

if (false === @preg_replace(' ÅeaÅe', null, $r)) { print "Error with 129 : Å-Å\n"; }
if (false === @preg_replace('  ÅeaÅe', null, $r)) { print "Error with 129 : Å-Å\n"; }
if (false === @preg_replace('ÇebÇe', null, $r)) { print "Error with 130 : Ç-Ç\n"; }
if (false === @preg_replace(' ÇebÇe', null, $r)) { print "Error with 130 : Ç-Ç\n"; }
if (false === @preg_replace('  ÇebÇe', null, $r)) { print "Error with 130 : Ç-Ç\n"; }
if (false === @preg_replace('ÉecÉe', null, $r)) { print "Error with 131 : É-É\n"; }
if (false === @preg_replace(' ÉecÉe', null, $r)) { print "Error with 131 : É-É\n"; }
if (false === @preg_replace('  ÉecÉe', null, $r)) { print "Error with 131 : É-É\n"; }
if (false === @preg_replace('ÑedÑe', null, $r)) { print "Error with 132 : Ñ-Ñ\n"; }
if (false === @preg_replace(' ÑedÑe', null, $r)) { print "Error with 132 : Ñ-Ñ\n"; }
if (false === @preg_replace('  ÑedÑe', null, $r)) { print "Error with 132 : Ñ-Ñ\n"; }
if (false === @preg_replace('ÖeeÖe', null, $r)) { print "Error with 133 : Ö-Ö\n"; }
if (false === @preg_replace(' ÖeeÖe', null, $r)) { print "Error with 133 : Ö-Ö\n"; }
if (false === @preg_replace('  ÖeeÖe', null, $r)) { print "Error with 133 : Ö-Ö\n"; }
if (false === @preg_replace('ÜefÜe', null, $r)) { print "Error with 134 : Ü-Ü\n"; }
if (false === @preg_replace(' ÜefÜe', null, $r)) { print "Error with 134 : Ü-Ü\n"; }
if (false === @preg_replace('  ÜefÜe', null, $r)) { print "Error with 134 : Ü-Ü\n"; }
if (false === @preg_replace('áegáe', null, $r)) { print "Error with 135 : á-á\n"; }
if (false === @preg_replace(' áegáe', null, $r)) { print "Error with 135 : á-á\n"; }
if (false === @preg_replace('  áegáe', null, $r)) { print "Error with 135 : á-á\n"; }
if (false === @preg_replace('àehàe', null, $r)) { print "Error with 136 : à-à\n"; }
if (false === @preg_replace(' àehàe', null, $r)) { print "Error with 136 : à-à\n"; }
if (false === @preg_replace('  àehàe', null, $r)) { print "Error with 136 : à-à\n"; }
if (false === @preg_replace('âeiâe', null, $r)) { print "Error with 137 : â-â\n"; }
if (false === @preg_replace(' âeiâe', null, $r)) { print "Error with 137 : â-â\n"; }
if (false === @preg_replace('  âeiâe', null, $r)) { print "Error with 137 : â-â\n"; }
if (false === @preg_replace('äejäe', null, $r)) { print "Error with 138 : ä-ä\n"; }
if (false === @preg_replace(' äejäe', null, $r)) { print "Error with 138 : ä-ä\n"; }
if (false === @preg_replace('  äejäe', null, $r)) { print "Error with 138 : ä-ä\n"; }
if (false === @preg_replace('ãekãe', null, $r)) { print "Error with 139 : ã-ã\n"; }
if (false === @preg_replace(' ãekãe', null, $r)) { print "Error with 139 : ã-ã\n"; }
if (false === @preg_replace('  ãekãe', null, $r)) { print "Error with 139 : ã-ã\n"; }
if (false === @preg_replace('åelåe', null, $r)) { print "Error with 140 : å-å\n"; }
if (false === @preg_replace(' åelåe', null, $r)) { print "Error with 140 : å-å\n"; }
if (false === @preg_replace('  åelåe', null, $r)) { print "Error with 140 : å-å\n"; }
if (false === @preg_replace('çemçe', null, $r)) { print "Error with 141 : ç-ç\n"; }
if (false === @preg_replace(' çemçe', null, $r)) { print "Error with 141 : ç-ç\n"; }
if (false === @preg_replace('  çemçe', null, $r)) { print "Error with 141 : ç-ç\n"; }
if (false === @preg_replace('éenée', null, $r)) { print "Error with 142 : é-é\n"; }
if (false === @preg_replace(' éenée', null, $r)) { print "Error with 142 : é-é\n"; }
if (false === @preg_replace('  éenée', null, $r)) { print "Error with 142 : é-é\n"; }
if (false === @preg_replace('èeoèe', null, $r)) { print "Error with 143 : è-è\n"; }
if (false === @preg_replace(' èeoèe', null, $r)) { print "Error with 143 : è-è\n"; }
if (false === @preg_replace('  èeoèe', null, $r)) { print "Error with 143 : è-è\n"; }
if (false === @preg_replace('êepêe', null, $r)) { print "Error with 144 : ê-ê\n"; }
if (false === @preg_replace(' êepêe', null, $r)) { print "Error with 144 : ê-ê\n"; }
if (false === @preg_replace('  êepêe', null, $r)) { print "Error with 144 : ê-ê\n"; }
if (false === @preg_replace('ëeqëe', null, $r)) { print "Error with 145 : ë-ë\n"; }
if (false === @preg_replace(' ëeqëe', null, $r)) { print "Error with 145 : ë-ë\n"; }
if (false === @preg_replace('  ëeqëe', null, $r)) { print "Error with 145 : ë-ë\n"; }
if (false === @preg_replace('íeríe', null, $r)) { print "Error with 146 : í-í\n"; }
if (false === @preg_replace(' íeríe', null, $r)) { print "Error with 146 : í-í\n"; }
if (false === @preg_replace('  íeríe', null, $r)) { print "Error with 146 : í-í\n"; }
if (false === @preg_replace('ìesìe', null, $r)) { print "Error with 147 : ì-ì\n"; }
if (false === @preg_replace(' ìesìe', null, $r)) { print "Error with 147 : ì-ì\n"; }
if (false === @preg_replace('  ìesìe', null, $r)) { print "Error with 147 : ì-ì\n"; }
if (false === @preg_replace('îetîe', null, $r)) { print "Error with 148 : î-î\n"; }
if (false === @preg_replace(' îetîe', null, $r)) { print "Error with 148 : î-î\n"; }
if (false === @preg_replace('  îetîe', null, $r)) { print "Error with 148 : î-î\n"; }
if (false === @preg_replace('ïeuïe', null, $r)) { print "Error with 149 : ï-ï\n"; }
if (false === @preg_replace(' ïeuïe', null, $r)) { print "Error with 149 : ï-ï\n"; }
if (false === @preg_replace('  ïeuïe', null, $r)) { print "Error with 149 : ï-ï\n"; }
if (false === @preg_replace('ñevñe', null, $r)) { print "Error with 150 : ñ-ñ\n"; }
if (false === @preg_replace(' ñevñe', null, $r)) { print "Error with 150 : ñ-ñ\n"; }
if (false === @preg_replace('  ñevñe', null, $r)) { print "Error with 150 : ñ-ñ\n"; }
if (false === @preg_replace('óewóe', null, $r)) { print "Error with 151 : ó-ó\n"; }
if (false === @preg_replace(' óewóe', null, $r)) { print "Error with 151 : ó-ó\n"; }
if (false === @preg_replace('  óewóe', null, $r)) { print "Error with 151 : ó-ó\n"; }
if (false === @preg_replace('òexòe', null, $r)) { print "Error with 152 : ò-ò\n"; }
if (false === @preg_replace(' òexòe', null, $r)) { print "Error with 152 : ò-ò\n"; }
if (false === @preg_replace('  òexòe', null, $r)) { print "Error with 152 : ò-ò\n"; }
if (false === @preg_replace('ôeyôe', null, $r)) { print "Error with 153 : ô-ô\n"; }
if (false === @preg_replace(' ôeyôe', null, $r)) { print "Error with 153 : ô-ô\n"; }
if (false === @preg_replace('  ôeyôe', null, $r)) { print "Error with 153 : ô-ô\n"; }
if (false === @preg_replace('öezöe', null, $r)) { print "Error with 154 : ö-ö\n"; }
if (false === @preg_replace(' öezöe', null, $r)) { print "Error with 154 : ö-ö\n"; }
if (false === @preg_replace('  öezöe', null, $r)) { print "Error with 154 : ö-ö\n"; }
if (false === @preg_replace('õfaõe', null, $r)) { print "Error with 155 : õ-õ\n"; }
if (false === @preg_replace(' õfaõe', null, $r)) { print "Error with 155 : õ-õ\n"; }
if (false === @preg_replace('  õfaõe', null, $r)) { print "Error with 155 : õ-õ\n"; }
if (false === @preg_replace('úfbúe', null, $r)) { print "Error with 156 : ú-ú\n"; }
if (false === @preg_replace(' úfbúe', null, $r)) { print "Error with 156 : ú-ú\n"; }
if (false === @preg_replace('  úfbúe', null, $r)) { print "Error with 156 : ú-ú\n"; }
if (false === @preg_replace('ùfcùe', null, $r)) { print "Error with 157 : ù-ù\n"; }
if (false === @preg_replace(' ùfcùe', null, $r)) { print "Error with 157 : ù-ù\n"; }
if (false === @preg_replace('  ùfcùe', null, $r)) { print "Error with 157 : ù-ù\n"; }
if (false === @preg_replace('ûfdûe', null, $r)) { print "Error with 158 : û-û\n"; }
if (false === @preg_replace(' ûfdûe', null, $r)) { print "Error with 158 : û-û\n"; }
if (false === @preg_replace('  ûfdûe', null, $r)) { print "Error with 158 : û-û\n"; }
if (false === @preg_replace('üfeüe', null, $r)) { print "Error with 159 : ü-ü\n"; }
if (false === @preg_replace(' üfeüe', null, $r)) { print "Error with 159 : ü-ü\n"; }
if (false === @preg_replace('  üfeüe', null, $r)) { print "Error with 159 : ü-ü\n"; }
if (false === @preg_replace('†ff†e', null, $r)) { print "Error with 160 : †-†\n"; }
if (false === @preg_replace(' †ff†e', null, $r)) { print "Error with 160 : †-†\n"; }
if (false === @preg_replace('  †ff†e', null, $r)) { print "Error with 160 : †-†\n"; }
if (false === @preg_replace('°fg°e', null, $r)) { print "Error with 161 : °-°\n"; }
if (false === @preg_replace(' °fg°e', null, $r)) { print "Error with 161 : °-°\n"; }
if (false === @preg_replace('  °fg°e', null, $r)) { print "Error with 161 : °-°\n"; }
if (false === @preg_replace('¢fh¢e', null, $r)) { print "Error with 162 : ¢-¢\n"; }
if (false === @preg_replace(' ¢fh¢e', null, $r)) { print "Error with 162 : ¢-¢\n"; }
if (false === @preg_replace('  ¢fh¢e', null, $r)) { print "Error with 162 : ¢-¢\n"; }
if (false === @preg_replace('£fi£e', null, $r)) { print "Error with 163 : £-£\n"; }
if (false === @preg_replace(' £fi£e', null, $r)) { print "Error with 163 : £-£\n"; }
if (false === @preg_replace('  £fi£e', null, $r)) { print "Error with 163 : £-£\n"; }
if (false === @preg_replace('§fj§e', null, $r)) { print "Error with 164 : §-§\n"; }
if (false === @preg_replace(' §fj§e', null, $r)) { print "Error with 164 : §-§\n"; }
if (false === @preg_replace('  §fj§e', null, $r)) { print "Error with 164 : §-§\n"; }
if (false === @preg_replace('•fk•e', null, $r)) { print "Error with 165 : •-•\n"; }
if (false === @preg_replace(' •fk•e', null, $r)) { print "Error with 165 : •-•\n"; }
if (false === @preg_replace('  •fk•e', null, $r)) { print "Error with 165 : •-•\n"; }
if (false === @preg_replace('¶fl¶e', null, $r)) { print "Error with 166 : ¶-¶\n"; }
if (false === @preg_replace(' ¶fl¶e', null, $r)) { print "Error with 166 : ¶-¶\n"; }
if (false === @preg_replace('  ¶fl¶e', null, $r)) { print "Error with 166 : ¶-¶\n"; }
if (false === @preg_replace('ßfmße', null, $r)) { print "Error with 167 : ß-ß\n"; }
if (false === @preg_replace(' ßfmße', null, $r)) { print "Error with 167 : ß-ß\n"; }
if (false === @preg_replace('  ßfmße', null, $r)) { print "Error with 167 : ß-ß\n"; }
if (false === @preg_replace('®fn®e', null, $r)) { print "Error with 168 : ®-®\n"; }
if (false === @preg_replace(' ®fn®e', null, $r)) { print "Error with 168 : ®-®\n"; }
if (false === @preg_replace('  ®fn®e', null, $r)) { print "Error with 168 : ®-®\n"; }
if (false === @preg_replace('©fo©e', null, $r)) { print "Error with 169 : ©-©\n"; }
if (false === @preg_replace(' ©fo©e', null, $r)) { print "Error with 169 : ©-©\n"; }
if (false === @preg_replace('  ©fo©e', null, $r)) { print "Error with 169 : ©-©\n"; }
if (false === @preg_replace('™fp™e', null, $r)) { print "Error with 170 : ™-™\n"; }
if (false === @preg_replace(' ™fp™e', null, $r)) { print "Error with 170 : ™-™\n"; }
if (false === @preg_replace('  ™fp™e', null, $r)) { print "Error with 170 : ™-™\n"; }
if (false === @preg_replace('´fq´e', null, $r)) { print "Error with 171 : ´-´\n"; }
if (false === @preg_replace(' ´fq´e', null, $r)) { print "Error with 171 : ´-´\n"; }
if (false === @preg_replace('  ´fq´e', null, $r)) { print "Error with 171 : ´-´\n"; }
if (false === @preg_replace('¨fr¨e', null, $r)) { print "Error with 172 : ¨-¨\n"; }
if (false === @preg_replace(' ¨fr¨e', null, $r)) { print "Error with 172 : ¨-¨\n"; }
if (false === @preg_replace('  ¨fr¨e', null, $r)) { print "Error with 172 : ¨-¨\n"; }
if (false === @preg_replace('≠fs≠e', null, $r)) { print "Error with 173 : ≠-≠\n"; }
if (false === @preg_replace(' ≠fs≠e', null, $r)) { print "Error with 173 : ≠-≠\n"; }
if (false === @preg_replace('  ≠fs≠e', null, $r)) { print "Error with 173 : ≠-≠\n"; }
if (false === @preg_replace('ÆftÆe', null, $r)) { print "Error with 174 : Æ-Æ\n"; }
if (false === @preg_replace(' ÆftÆe', null, $r)) { print "Error with 174 : Æ-Æ\n"; }
if (false === @preg_replace('  ÆftÆe', null, $r)) { print "Error with 174 : Æ-Æ\n"; }
if (false === @preg_replace('ØfuØe', null, $r)) { print "Error with 175 : Ø-Ø\n"; }
if (false === @preg_replace(' ØfuØe', null, $r)) { print "Error with 175 : Ø-Ø\n"; }
if (false === @preg_replace('  ØfuØe', null, $r)) { print "Error with 175 : Ø-Ø\n"; }
if (false === @preg_replace('∞fv∞e', null, $r)) { print "Error with 176 : ∞-∞\n"; }
if (false === @preg_replace(' ∞fv∞e', null, $r)) { print "Error with 176 : ∞-∞\n"; }
if (false === @preg_replace('  ∞fv∞e', null, $r)) { print "Error with 176 : ∞-∞\n"; }
if (false === @preg_replace('±fw±e', null, $r)) { print "Error with 177 : ±-±\n"; }
if (false === @preg_replace(' ±fw±e', null, $r)) { print "Error with 177 : ±-±\n"; }
if (false === @preg_replace('  ±fw±e', null, $r)) { print "Error with 177 : ±-±\n"; }
if (false === @preg_replace('≤fx≤e', null, $r)) { print "Error with 178 : ≤-≤\n"; }
if (false === @preg_replace(' ≤fx≤e', null, $r)) { print "Error with 178 : ≤-≤\n"; }
if (false === @preg_replace('  ≤fx≤e', null, $r)) { print "Error with 178 : ≤-≤\n"; }
if (false === @preg_replace('≥fy≥e', null, $r)) { print "Error with 179 : ≥-≥\n"; }
if (false === @preg_replace(' ≥fy≥e', null, $r)) { print "Error with 179 : ≥-≥\n"; }
if (false === @preg_replace('  ≥fy≥e', null, $r)) { print "Error with 179 : ≥-≥\n"; }
if (false === @preg_replace('¥fz¥e', null, $r)) { print "Error with 180 : ¥-¥\n"; }
if (false === @preg_replace(' ¥fz¥e', null, $r)) { print "Error with 180 : ¥-¥\n"; }
if (false === @preg_replace('  ¥fz¥e', null, $r)) { print "Error with 180 : ¥-¥\n"; }
if (false === @preg_replace('µgaµe', null, $r)) { print "Error with 181 : µ-µ\n"; }
if (false === @preg_replace(' µgaµe', null, $r)) { print "Error with 181 : µ-µ\n"; }
if (false === @preg_replace('  µgaµe', null, $r)) { print "Error with 181 : µ-µ\n"; }
if (false === @preg_replace('∂gb∂e', null, $r)) { print "Error with 182 : ∂-∂\n"; }
if (false === @preg_replace(' ∂gb∂e', null, $r)) { print "Error with 182 : ∂-∂\n"; }
if (false === @preg_replace('  ∂gb∂e', null, $r)) { print "Error with 182 : ∂-∂\n"; }
if (false === @preg_replace('∑gc∑e', null, $r)) { print "Error with 183 : ∑-∑\n"; }
if (false === @preg_replace(' ∑gc∑e', null, $r)) { print "Error with 183 : ∑-∑\n"; }
if (false === @preg_replace('  ∑gc∑e', null, $r)) { print "Error with 183 : ∑-∑\n"; }
if (false === @preg_replace('∏gd∏e', null, $r)) { print "Error with 184 : ∏-∏\n"; }
if (false === @preg_replace(' ∏gd∏e', null, $r)) { print "Error with 184 : ∏-∏\n"; }
if (false === @preg_replace('  ∏gd∏e', null, $r)) { print "Error with 184 : ∏-∏\n"; }
if (false === @preg_replace('πgeπe', null, $r)) { print "Error with 185 : π-π\n"; }
if (false === @preg_replace(' πgeπe', null, $r)) { print "Error with 185 : π-π\n"; }
if (false === @preg_replace('  πgeπe', null, $r)) { print "Error with 185 : π-π\n"; }
if (false === @preg_replace('∫gf∫e', null, $r)) { print "Error with 186 : ∫-∫\n"; }
if (false === @preg_replace(' ∫gf∫e', null, $r)) { print "Error with 186 : ∫-∫\n"; }
if (false === @preg_replace('  ∫gf∫e', null, $r)) { print "Error with 186 : ∫-∫\n"; }
if (false === @preg_replace('ªggªe', null, $r)) { print "Error with 187 : ª-ª\n"; }
if (false === @preg_replace(' ªggªe', null, $r)) { print "Error with 187 : ª-ª\n"; }
if (false === @preg_replace('  ªggªe', null, $r)) { print "Error with 187 : ª-ª\n"; }
if (false === @preg_replace('ºghºe', null, $r)) { print "Error with 188 : º-º\n"; }
if (false === @preg_replace(' ºghºe', null, $r)) { print "Error with 188 : º-º\n"; }
if (false === @preg_replace('  ºghºe', null, $r)) { print "Error with 188 : º-º\n"; }
if (false === @preg_replace('ΩgiΩe', null, $r)) { print "Error with 189 : Ω-Ω\n"; }
if (false === @preg_replace(' ΩgiΩe', null, $r)) { print "Error with 189 : Ω-Ω\n"; }
if (false === @preg_replace('  ΩgiΩe', null, $r)) { print "Error with 189 : Ω-Ω\n"; }
if (false === @preg_replace('ægjæe', null, $r)) { print "Error with 190 : æ-æ\n"; }
if (false === @preg_replace(' ægjæe', null, $r)) { print "Error with 190 : æ-æ\n"; }
if (false === @preg_replace('  ægjæe', null, $r)) { print "Error with 190 : æ-æ\n"; }
if (false === @preg_replace('øgkøe', null, $r)) { print "Error with 191 : ø-ø\n"; }
if (false === @preg_replace(' øgkøe', null, $r)) { print "Error with 191 : ø-ø\n"; }
if (false === @preg_replace('  øgkøe', null, $r)) { print "Error with 191 : ø-ø\n"; }
if (false === @preg_replace('¿gl¿e', null, $r)) { print "Error with 192 : ¿-¿\n"; }
if (false === @preg_replace(' ¿gl¿e', null, $r)) { print "Error with 192 : ¿-¿\n"; }
if (false === @preg_replace('  ¿gl¿e', null, $r)) { print "Error with 192 : ¿-¿\n"; }
if (false === @preg_replace('¡gm¡e', null, $r)) { print "Error with 193 : ¡-¡\n"; }
if (false === @preg_replace(' ¡gm¡e', null, $r)) { print "Error with 193 : ¡-¡\n"; }
if (false === @preg_replace('  ¡gm¡e', null, $r)) { print "Error with 193 : ¡-¡\n"; }
if (false === @preg_replace('¬gn¬e', null, $r)) { print "Error with 194 : ¬-¬\n"; }
if (false === @preg_replace(' ¬gn¬e', null, $r)) { print "Error with 194 : ¬-¬\n"; }
if (false === @preg_replace('  ¬gn¬e', null, $r)) { print "Error with 194 : ¬-¬\n"; }
if (false === @preg_replace('√go√e', null, $r)) { print "Error with 195 : √-√\n"; }
if (false === @preg_replace(' √go√e', null, $r)) { print "Error with 195 : √-√\n"; }
if (false === @preg_replace('  √go√e', null, $r)) { print "Error with 195 : √-√\n"; }
if (false === @preg_replace('ƒgpƒe', null, $r)) { print "Error with 196 : ƒ-ƒ\n"; }
if (false === @preg_replace(' ƒgpƒe', null, $r)) { print "Error with 196 : ƒ-ƒ\n"; }
if (false === @preg_replace('  ƒgpƒe', null, $r)) { print "Error with 196 : ƒ-ƒ\n"; }
if (false === @preg_replace('≈gq≈e', null, $r)) { print "Error with 197 : ≈-≈\n"; }
if (false === @preg_replace(' ≈gq≈e', null, $r)) { print "Error with 197 : ≈-≈\n"; }
if (false === @preg_replace('  ≈gq≈e', null, $r)) { print "Error with 197 : ≈-≈\n"; }
if (false === @preg_replace('∆gr∆e', null, $r)) { print "Error with 198 : ∆-∆\n"; }
if (false === @preg_replace(' ∆gr∆e', null, $r)) { print "Error with 198 : ∆-∆\n"; }
if (false === @preg_replace('  ∆gr∆e', null, $r)) { print "Error with 198 : ∆-∆\n"; }
if (false === @preg_replace('«gs«e', null, $r)) { print "Error with 199 : «-«\n"; }
if (false === @preg_replace(' «gs«e', null, $r)) { print "Error with 199 : «-«\n"; }
if (false === @preg_replace('  «gs«e', null, $r)) { print "Error with 199 : «-«\n"; }
if (false === @preg_replace('»gt»e', null, $r)) { print "Error with 200 : »-»\n"; }
if (false === @preg_replace(' »gt»e', null, $r)) { print "Error with 200 : »-»\n"; }
if (false === @preg_replace('  »gt»e', null, $r)) { print "Error with 200 : »-»\n"; }
if (false === @preg_replace('…gu…e', null, $r)) { print "Error with 201 : …-…\n"; }
if (false === @preg_replace(' …gu…e', null, $r)) { print "Error with 201 : …-…\n"; }
if (false === @preg_replace('  …gu…e', null, $r)) { print "Error with 201 : …-…\n"; }
if (false === @preg_replace(' gv e', null, $r)) { print "Error with 202 :  - \n"; }
if (false === @preg_replace('  gv e', null, $r)) { print "Error with 202 :  - \n"; }
if (false === @preg_replace('   gv e', null, $r)) { print "Error with 202 :  - \n"; }
if (false === @preg_replace('ÀgwÀe', null, $r)) { print "Error with 203 : À-À\n"; }
if (false === @preg_replace(' ÀgwÀe', null, $r)) { print "Error with 203 : À-À\n"; }
if (false === @preg_replace('  ÀgwÀe', null, $r)) { print "Error with 203 : À-À\n"; }
if (false === @preg_replace('ÃgxÃe', null, $r)) { print "Error with 204 : Ã-Ã\n"; }
if (false === @preg_replace(' ÃgxÃe', null, $r)) { print "Error with 204 : Ã-Ã\n"; }
if (false === @preg_replace('  ÃgxÃe', null, $r)) { print "Error with 204 : Ã-Ã\n"; }
if (false === @preg_replace('ÕgyÕe', null, $r)) { print "Error with 205 : Õ-Õ\n"; }
if (false === @preg_replace(' ÕgyÕe', null, $r)) { print "Error with 205 : Õ-Õ\n"; }
if (false === @preg_replace('  ÕgyÕe', null, $r)) { print "Error with 205 : Õ-Õ\n"; }
if (false === @preg_replace('ŒgzŒe', null, $r)) { print "Error with 206 : Œ-Œ\n"; }
if (false === @preg_replace(' ŒgzŒe', null, $r)) { print "Error with 206 : Œ-Œ\n"; }
if (false === @preg_replace('  ŒgzŒe', null, $r)) { print "Error with 206 : Œ-Œ\n"; }
if (false === @preg_replace('œhaœe', null, $r)) { print "Error with 207 : œ-œ\n"; }
if (false === @preg_replace(' œhaœe', null, $r)) { print "Error with 207 : œ-œ\n"; }
if (false === @preg_replace('  œhaœe', null, $r)) { print "Error with 207 : œ-œ\n"; }
if (false === @preg_replace('–hb–e', null, $r)) { print "Error with 208 : –-–\n"; }
if (false === @preg_replace(' –hb–e', null, $r)) { print "Error with 208 : –-–\n"; }
if (false === @preg_replace('  –hb–e', null, $r)) { print "Error with 208 : –-–\n"; }
if (false === @preg_replace('—hc—e', null, $r)) { print "Error with 209 : —-—\n"; }
if (false === @preg_replace(' —hc—e', null, $r)) { print "Error with 209 : —-—\n"; }
if (false === @preg_replace('  —hc—e', null, $r)) { print "Error with 209 : —-—\n"; }
if (false === @preg_replace('“hd“e', null, $r)) { print "Error with 210 : “-“\n"; }
if (false === @preg_replace(' “hd“e', null, $r)) { print "Error with 210 : “-“\n"; }
if (false === @preg_replace('  “hd“e', null, $r)) { print "Error with 210 : “-“\n"; }
if (false === @preg_replace('”he”e', null, $r)) { print "Error with 211 : ”-”\n"; }
if (false === @preg_replace(' ”he”e', null, $r)) { print "Error with 211 : ”-”\n"; }
if (false === @preg_replace('  ”he”e', null, $r)) { print "Error with 211 : ”-”\n"; }
if (false === @preg_replace('‘hf‘e', null, $r)) { print "Error with 212 : ‘-‘\n"; }
if (false === @preg_replace(' ‘hf‘e', null, $r)) { print "Error with 212 : ‘-‘\n"; }
if (false === @preg_replace('  ‘hf‘e', null, $r)) { print "Error with 212 : ‘-‘\n"; }
if (false === @preg_replace('’hg’e', null, $r)) { print "Error with 213 : ’-’\n"; }
if (false === @preg_replace(' ’hg’e', null, $r)) { print "Error with 213 : ’-’\n"; }
if (false === @preg_replace('  ’hg’e', null, $r)) { print "Error with 213 : ’-’\n"; }
if (false === @preg_replace('÷hh÷e', null, $r)) { print "Error with 214 : ÷-÷\n"; }
if (false === @preg_replace(' ÷hh÷e', null, $r)) { print "Error with 214 : ÷-÷\n"; }
if (false === @preg_replace('  ÷hh÷e', null, $r)) { print "Error with 214 : ÷-÷\n"; }
if (false === @preg_replace('◊hi◊e', null, $r)) { print "Error with 215 : ◊-◊\n"; }
if (false === @preg_replace(' ◊hi◊e', null, $r)) { print "Error with 215 : ◊-◊\n"; }
if (false === @preg_replace('  ◊hi◊e', null, $r)) { print "Error with 215 : ◊-◊\n"; }
if (false === @preg_replace('ÿhjÿe', null, $r)) { print "Error with 216 : ÿ-ÿ\n"; }
if (false === @preg_replace(' ÿhjÿe', null, $r)) { print "Error with 216 : ÿ-ÿ\n"; }
if (false === @preg_replace('  ÿhjÿe', null, $r)) { print "Error with 216 : ÿ-ÿ\n"; }
if (false === @preg_replace('ŸhkŸe', null, $r)) { print "Error with 217 : Ÿ-Ÿ\n"; }
if (false === @preg_replace(' ŸhkŸe', null, $r)) { print "Error with 217 : Ÿ-Ÿ\n"; }
if (false === @preg_replace('  ŸhkŸe', null, $r)) { print "Error with 217 : Ÿ-Ÿ\n"; }
if (false === @preg_replace('⁄hl⁄e', null, $r)) { print "Error with 218 : ⁄-⁄\n"; }
if (false === @preg_replace(' ⁄hl⁄e', null, $r)) { print "Error with 218 : ⁄-⁄\n"; }
if (false === @preg_replace('  ⁄hl⁄e', null, $r)) { print "Error with 218 : ⁄-⁄\n"; }
if (false === @preg_replace('€hm€e', null, $r)) { print "Error with 219 : €-€\n"; }
if (false === @preg_replace(' €hm€e', null, $r)) { print "Error with 219 : €-€\n"; }
if (false === @preg_replace('  €hm€e', null, $r)) { print "Error with 219 : €-€\n"; }
if (false === @preg_replace('‹hn‹e', null, $r)) { print "Error with 220 : ‹-‹\n"; }
if (false === @preg_replace(' ‹hn‹e', null, $r)) { print "Error with 220 : ‹-‹\n"; }
if (false === @preg_replace('  ‹hn‹e', null, $r)) { print "Error with 220 : ‹-‹\n"; }
if (false === @preg_replace('›ho›e', null, $r)) { print "Error with 221 : ›-›\n"; }
if (false === @preg_replace(' ›ho›e', null, $r)) { print "Error with 221 : ›-›\n"; }
if (false === @preg_replace('  ›ho›e', null, $r)) { print "Error with 221 : ›-›\n"; }
if (false === @preg_replace('ﬁhpﬁe', null, $r)) { print "Error with 222 : ﬁ-ﬁ\n"; }
if (false === @preg_replace(' ﬁhpﬁe', null, $r)) { print "Error with 222 : ﬁ-ﬁ\n"; }
if (false === @preg_replace('  ﬁhpﬁe', null, $r)) { print "Error with 222 : ﬁ-ﬁ\n"; }
if (false === @preg_replace('ﬂhqﬂe', null, $r)) { print "Error with 223 : ﬂ-ﬂ\n"; }
if (false === @preg_replace(' ﬂhqﬂe', null, $r)) { print "Error with 223 : ﬂ-ﬂ\n"; }
if (false === @preg_replace('  ﬂhqﬂe', null, $r)) { print "Error with 223 : ﬂ-ﬂ\n"; }
if (false === @preg_replace('‡hr‡e', null, $r)) { print "Error with 224 : ‡-‡\n"; }
if (false === @preg_replace(' ‡hr‡e', null, $r)) { print "Error with 224 : ‡-‡\n"; }
if (false === @preg_replace('  ‡hr‡e', null, $r)) { print "Error with 224 : ‡-‡\n"; }
if (false === @preg_replace('·hs·e', null, $r)) { print "Error with 225 : ·-·\n"; }
if (false === @preg_replace(' ·hs·e', null, $r)) { print "Error with 225 : ·-·\n"; }
if (false === @preg_replace('  ·hs·e', null, $r)) { print "Error with 225 : ·-·\n"; }
if (false === @preg_replace('‚ht‚e', null, $r)) { print "Error with 226 : ‚-‚\n"; }
if (false === @preg_replace(' ‚ht‚e', null, $r)) { print "Error with 226 : ‚-‚\n"; }
if (false === @preg_replace('  ‚ht‚e', null, $r)) { print "Error with 226 : ‚-‚\n"; }
if (false === @preg_replace('„hu„e', null, $r)) { print "Error with 227 : „-„\n"; }
if (false === @preg_replace(' „hu„e', null, $r)) { print "Error with 227 : „-„\n"; }
if (false === @preg_replace('  „hu„e', null, $r)) { print "Error with 227 : „-„\n"; }
if (false === @preg_replace('‰hv‰e', null, $r)) { print "Error with 228 : ‰-‰\n"; }
if (false === @preg_replace(' ‰hv‰e', null, $r)) { print "Error with 228 : ‰-‰\n"; }
if (false === @preg_replace('  ‰hv‰e', null, $r)) { print "Error with 228 : ‰-‰\n"; }
if (false === @preg_replace('ÂhwÂe', null, $r)) { print "Error with 229 : Â-Â\n"; }
if (false === @preg_replace(' ÂhwÂe', null, $r)) { print "Error with 229 : Â-Â\n"; }
if (false === @preg_replace('  ÂhwÂe', null, $r)) { print "Error with 229 : Â-Â\n"; }
if (false === @preg_replace('ÊhxÊe', null, $r)) { print "Error with 230 : Ê-Ê\n"; }
if (false === @preg_replace(' ÊhxÊe', null, $r)) { print "Error with 230 : Ê-Ê\n"; }
if (false === @preg_replace('  ÊhxÊe', null, $r)) { print "Error with 230 : Ê-Ê\n"; }
if (false === @preg_replace('ÁhyÁe', null, $r)) { print "Error with 231 : Á-Á\n"; }
if (false === @preg_replace(' ÁhyÁe', null, $r)) { print "Error with 231 : Á-Á\n"; }
if (false === @preg_replace('  ÁhyÁe', null, $r)) { print "Error with 231 : Á-Á\n"; }
if (false === @preg_replace('ËhzËe', null, $r)) { print "Error with 232 : Ë-Ë\n"; }
if (false === @preg_replace(' ËhzËe', null, $r)) { print "Error with 232 : Ë-Ë\n"; }
if (false === @preg_replace('  ËhzËe', null, $r)) { print "Error with 232 : Ë-Ë\n"; }
if (false === @preg_replace('ÈiaÈe', null, $r)) { print "Error with 233 : È-È\n"; }
if (false === @preg_replace(' ÈiaÈe', null, $r)) { print "Error with 233 : È-È\n"; }
if (false === @preg_replace('  ÈiaÈe', null, $r)) { print "Error with 233 : È-È\n"; }
if (false === @preg_replace('ÍibÍe', null, $r)) { print "Error with 234 : Í-Í\n"; }
if (false === @preg_replace(' ÍibÍe', null, $r)) { print "Error with 234 : Í-Í\n"; }
if (false === @preg_replace('  ÍibÍe', null, $r)) { print "Error with 234 : Í-Í\n"; }
if (false === @preg_replace('ÎicÎe', null, $r)) { print "Error with 235 : Î-Î\n"; }
if (false === @preg_replace(' ÎicÎe', null, $r)) { print "Error with 235 : Î-Î\n"; }
if (false === @preg_replace('  ÎicÎe', null, $r)) { print "Error with 235 : Î-Î\n"; }
if (false === @preg_replace('ÏidÏe', null, $r)) { print "Error with 236 : Ï-Ï\n"; }
if (false === @preg_replace(' ÏidÏe', null, $r)) { print "Error with 236 : Ï-Ï\n"; }
if (false === @preg_replace('  ÏidÏe', null, $r)) { print "Error with 236 : Ï-Ï\n"; }
if (false === @preg_replace('ÌieÌe', null, $r)) { print "Error with 237 : Ì-Ì\n"; }
if (false === @preg_replace(' ÌieÌe', null, $r)) { print "Error with 237 : Ì-Ì\n"; }
if (false === @preg_replace('  ÌieÌe', null, $r)) { print "Error with 237 : Ì-Ì\n"; }
if (false === @preg_replace('ÓifÓe', null, $r)) { print "Error with 238 : Ó-Ó\n"; }
if (false === @preg_replace(' ÓifÓe', null, $r)) { print "Error with 238 : Ó-Ó\n"; }
if (false === @preg_replace('  ÓifÓe', null, $r)) { print "Error with 238 : Ó-Ó\n"; }
if (false === @preg_replace('ÔigÔe', null, $r)) { print "Error with 239 : Ô-Ô\n"; }
if (false === @preg_replace(' ÔigÔe', null, $r)) { print "Error with 239 : Ô-Ô\n"; }
if (false === @preg_replace('  ÔigÔe', null, $r)) { print "Error with 239 : Ô-Ô\n"; }
if (false === @preg_replace('ihe', null, $r)) { print "Error with 240 : -\n"; }
if (false === @preg_replace(' ihe', null, $r)) { print "Error with 240 : -\n"; }
if (false === @preg_replace('  ihe', null, $r)) { print "Error with 240 : -\n"; }
if (false === @preg_replace('ÒiiÒe', null, $r)) { print "Error with 241 : Ò-Ò\n"; }
if (false === @preg_replace(' ÒiiÒe', null, $r)) { print "Error with 241 : Ò-Ò\n"; }
if (false === @preg_replace('  ÒiiÒe', null, $r)) { print "Error with 241 : Ò-Ò\n"; }
if (false === @preg_replace('ÚijÚe', null, $r)) { print "Error with 242 : Ú-Ú\n"; }
if (false === @preg_replace(' ÚijÚe', null, $r)) { print "Error with 242 : Ú-Ú\n"; }
if (false === @preg_replace('  ÚijÚe', null, $r)) { print "Error with 242 : Ú-Ú\n"; }
if (false === @preg_replace('ÛikÛe', null, $r)) { print "Error with 243 : Û-Û\n"; }
if (false === @preg_replace(' ÛikÛe', null, $r)) { print "Error with 243 : Û-Û\n"; }
if (false === @preg_replace('  ÛikÛe', null, $r)) { print "Error with 243 : Û-Û\n"; }
if (false === @preg_replace('ÙilÙe', null, $r)) { print "Error with 244 : Ù-Ù\n"; }
if (false === @preg_replace(' ÙilÙe', null, $r)) { print "Error with 244 : Ù-Ù\n"; }
if (false === @preg_replace('  ÙilÙe', null, $r)) { print "Error with 244 : Ù-Ù\n"; }
if (false === @preg_replace('ıimıe', null, $r)) { print "Error with 245 : ı-ı\n"; }
if (false === @preg_replace(' ıimıe', null, $r)) { print "Error with 245 : ı-ı\n"; }
if (false === @preg_replace('  ıimıe', null, $r)) { print "Error with 245 : ı-ı\n"; }
if (false === @preg_replace('ˆinˆe', null, $r)) { print "Error with 246 : ˆ-ˆ\n"; }
if (false === @preg_replace(' ˆinˆe', null, $r)) { print "Error with 246 : ˆ-ˆ\n"; }
if (false === @preg_replace('  ˆinˆe', null, $r)) { print "Error with 246 : ˆ-ˆ\n"; }
if (false === @preg_replace('˜io˜e', null, $r)) { print "Error with 247 : ˜-˜\n"; }
if (false === @preg_replace(' ˜io˜e', null, $r)) { print "Error with 247 : ˜-˜\n"; }
if (false === @preg_replace('  ˜io˜e', null, $r)) { print "Error with 247 : ˜-˜\n"; }
if (false === @preg_replace('¯ip¯e', null, $r)) { print "Error with 248 : ¯-¯\n"; }
if (false === @preg_replace(' ¯ip¯e', null, $r)) { print "Error with 248 : ¯-¯\n"; }
if (false === @preg_replace('  ¯ip¯e', null, $r)) { print "Error with 248 : ¯-¯\n"; }
if (false === @preg_replace('˘iq˘e', null, $r)) { print "Error with 249 : ˘-˘\n"; }
if (false === @preg_replace(' ˘iq˘e', null, $r)) { print "Error with 249 : ˘-˘\n"; }
if (false === @preg_replace('  ˘iq˘e', null, $r)) { print "Error with 249 : ˘-˘\n"; }
if (false === @preg_replace('˙ir˙e', null, $r)) { print "Error with 250 : ˙-˙\n"; }
if (false === @preg_replace(' ˙ir˙e', null, $r)) { print "Error with 250 : ˙-˙\n"; }
if (false === @preg_replace('  ˙ir˙e', null, $r)) { print "Error with 250 : ˙-˙\n"; }
if (false === @preg_replace('˚is˚e', null, $r)) { print "Error with 251 : ˚-˚\n"; }
if (false === @preg_replace(' ˚is˚e', null, $r)) { print "Error with 251 : ˚-˚\n"; }
if (false === @preg_replace('  ˚is˚e', null, $r)) { print "Error with 251 : ˚-˚\n"; }
if (false === @preg_replace('¸it¸e', null, $r)) { print "Error with 252 : ¸-¸\n"; }
if (false === @preg_replace(' ¸it¸e', null, $r)) { print "Error with 252 : ¸-¸\n"; }
if (false === @preg_replace('  ¸it¸e', null, $r)) { print "Error with 252 : ¸-¸\n"; }
if (false === @preg_replace('˝iu˝e', null, $r)) { print "Error with 253 : ˝-˝\n"; }
if (false === @preg_replace(' ˝iu˝e', null, $r)) { print "Error with 253 : ˝-˝\n"; }
if (false === @preg_replace('  ˝iu˝e', null, $r)) { print "Error with 253 : ˝-˝\n"; }
if (false === @preg_replace('˛iv˛e', null, $r)) { print "Error with 254 : ˛-˛\n"; }
if (false === @preg_replace(' ˛iv˛e', null, $r)) { print "Error with 254 : ˛-˛\n"; }
if (false === @preg_replace('  ˛iv˛e', null, $r)) { print "Error with 254 : ˛-˛\n"; }
if (false === @preg_replace('ˇiwˇe', null, $r)) { print "Error with 255 : ˇ-ˇ\n"; }
if (false === @preg_replace(' ˇiwˇe', null, $r)) { print "Error with 255 : ˇ-ˇ\n"; }
if (false === @preg_replace('  ˇiwˇe', null, $r)) { print "Error with 255 : ˇ-ˇ\n"; }
