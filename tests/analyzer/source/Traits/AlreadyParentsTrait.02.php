<?php

trait t {
    use ta;
}

trait t2 {
    use ta, t;
}

trait t3 {
    use t2;
}

trait t4 {
    use ta;
    use t2;
}


?>
