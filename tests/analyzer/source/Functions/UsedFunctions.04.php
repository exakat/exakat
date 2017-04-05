<?php

namespace a {    function ba() {}
                 function ca() {}
                  }
namespace b {    function bb() {} 
                 function cb() {}
}
namespace c {    function bc() {} 
                 function cc() {}
}
namespace d {    function bd() {} 
                 function cd() {}
}
namespace {   \a\ba();
              \b\bb();
              \c\bc();
              \d\bd(); 
                 function c() {}
}

?>