<?php 
Label : CODE
  makeSequence T_OPEN_TAG
  Label : ELEMENT
    if
    Label : CONDITION
      (
      Label : CODE
        &&
        Label : RIGHT
          ==
          Label : RIGHT
            'D'
          Label : LEFT
            ->
            Label : OBJECT
              $a
            Label : PROPERTY
              C
        Label : LEFT
          !
          Label : NOT
            empty
            Label : ARGUMENTS
              void
              Label : ARGUMENT
                ->
                Label : OBJECT
                  $a
                Label : PROPERTY
                  B
    Label : THEN
      makeSequence T_COLON
      Label : ELEMENT
        I
      Label : ELEMENT
        echo
        Label : ARGUMENTS
          void
          Label : ARGUMENT
            new
            Label : NEW
              F
              Label : ARGUMENTS
                Arguments
                Label : ARGUMENT
                  ->
                  Label : OBJECT
                    $a
                  Label : PROPERTY
                    B
                Label : ARGUMENT
                  'G'
      Label : ELEMENT
        E
