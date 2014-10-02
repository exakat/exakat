<?php

namespace a {    function b() {} }
namespace b {    function b() {} }
namespace c {    function b() {} }
namespace d {    function b() {} }
namespace {   \a\b();
              \b\b();
              \c\b();
              \d\b(); }

?>