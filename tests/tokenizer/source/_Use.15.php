<?php

namespace {
    class one_identifier {
        const a = 2;
    }
    class absolute_path {
        const a = 2;
    }
    class one_identifier3 {
        const a = 2;
    }

    class a1 {
        const a = 2;
    }
}

namespace complex {
    class path {
        const a = 2;
    }
}

namespace absolute\complex {
    class path2 {
        const a = 2;
    }
}

namespace x {
    use one_identifier;
    use \absolute_path;
    use complex\path;
    use \absolute\complex\path2;

    use one_identifier3 as a1;
    use \absolute_path3 as a2;
    use complex\path3 as a3;
    use \absolute\complex\path32 as a4;
    
    try {}
    catch(one_identifier $e) {}
    catch(absolute_path $e) {}
    catch(path $e) {}
    catch(path2 $e) {}

    catch(a1 $e) {}
    catch(\a1 $e) {}
    catch(one_identifier3 $e) {}

    catch(a2 $e) {}
    catch(a3 $e) {}
    catch(a4 $e) {}

}