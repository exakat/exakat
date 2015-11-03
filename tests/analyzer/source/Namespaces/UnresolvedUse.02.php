<?php 

namespace {
    use c\d, // undefined
        e\f as g,  // undefined
        h as i,  // undefined
        j\k as l,  // has a class j\k
        m\n, // has a class j\k
        o\p as q;  // namespace o\p but has a namespace o\p 
    
    new m\n;
    new l;
    new q\a;
    
}

namespace a {}
namespace o\p { class a {}}
namespace m { class n{} }

namespace j { class k{}}

?>