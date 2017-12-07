<?php

$expected     = array('try { /**/ } catch (FirstException $e) { /**/ } catch (SecondException $e) { /**/ } catch (ThirdException $e) { /**/ } ',
                      'try { /**/ } catch (FirstException $e) { /**/ } catch (SecondException $e) { /**/ } ',
                     );

$expected_not = array('try { /**/ } catch (FirstException $e) { /**/ } ',
                     );

?>