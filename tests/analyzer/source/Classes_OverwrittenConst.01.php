<?php

class A {    const noOverwrite = 0;
             const overwrite1 = 1;
             const overwrite2 = 2;
             const overwrite3 = 3;
             const overwrite4 = 4;
             const overwrite5 = 5;
              }
class B extends A  {    const overwrite1 = 2; }
class C extends B  {    const overwrite2 = 3; }
class D extends C  {    const overwrite3 = 4; }
class E extends D  {    const overwrite4 = 5; }
class F extends E  {    const overwrite5 = 6; }

?>