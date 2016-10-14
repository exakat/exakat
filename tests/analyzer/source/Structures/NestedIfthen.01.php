<?php

if (1) {} else {}

if (2 == 'then') { if (21) {} } else {}

if (2 == 'else') { } else { if (22) {} }

if (2 == 'thenelse') { if (23) {}} else { if (23) {} }

// 3 levels

if (3 == 'thenthen') { if (32) { if (322) {$a++;} } } else { }
if (3 == 'thenelse') { if (32) { } else { if (322) {$a++;}}} else { }
if (3 == 'elsethen') {} else {  if (32) { if (322) {$a++;} } }
if (3 == 'elseelse') {} else {  if (32) {  } else {if (322) {$a++;}} }
if (3 == 'elseelse2') {} else {  if (32) {  } else {if (322) {$a++;} if (322) {$a++;} } }

?>