<?php

class a {
    function a1() {}
    function a2() {}
    function a3() {}
    function a4() {}
    function a5() {}
}

class b {
    function a1() {}
//    function a2() {}
    function a3() {}
    function a4() {}
    function a5() {}
}

class c {
    function a1() {}
//    function a2() {}
//    function a3() {}
    function a4() {}
    function a5() {}
}

class d {
    function a1() {}
//    function a2() {}
//    function a3() {}
//    function a4() {}
     function a5() {}
 
}

// only a1 is common
class e {
    function a1() {}
//    function a2() {}
//    function a3() {}
//    function a4() {}
     function a6() {}
 
}

// only a6 is common
class f {
//    function a2() {}
//    function a3() {}
//    function a4() {}
     function a6() {}
    function a7() {} 
}

?>