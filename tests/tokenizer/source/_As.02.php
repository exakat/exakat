<?php
class B
{

    use C {
        C::E as private F;
        C::H as protected I2;
        C::H as public I3;
        C::H as I4;
    }

}
