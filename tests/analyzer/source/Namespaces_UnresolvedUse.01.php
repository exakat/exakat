<?php 

namespace {
    use c\d; // undefined
    use e\f as g;  // undefined
    use h as i;  // undefined
    use j\k as l;  // has a class j\k
    use m\n; // has a class j\k
    use o\p as q;  // namespace o\p but has a namespace o\p 
    
    new m\n;
    new l;
    new q\a;
    
}

namespace a {}
namespace o\p { class a {}}
namespace m { class n{} }

namespace j { class k{}}

?>