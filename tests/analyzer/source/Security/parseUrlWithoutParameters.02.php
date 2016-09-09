<?php

namespace {
    parse_str($a);
    mb_parse_str($a);
}

namespace X{
    parse_str($ax);
    mb_parse_str($ax);
    
    function mb_parse_str($a) {}
}

?>