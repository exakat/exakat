<?php

class a
    extends b
    /**
     * @var 1
     */
    implements c
    /**
     * @var 2
     */
     

{
    public $c;
    /**
     * @var 3
     */
    public $d;
    public $e;
    public $f;

    // check and prepare the auth and pref methods only once
    function __construct($a = '', $b = false)
    {
        /**
         * @var 4
         */
        global $request, $b;

        /**
         * @var 5
         */
        static $request, $b;
    }

}
