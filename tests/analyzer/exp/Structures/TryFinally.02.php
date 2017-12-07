<?php

$expected     = array('try { /**/ } catch (Exception $e) { /**/ } catch (Exception2 $e2) { /**/ } finally { /**/ } ',
                     );

$expected_not = array('try { /**/ } catch (Exception $e) { /**/ } catch (Exception2 $e2) { /**/ } ',
                     );

?>