<?php

$dirname = ('/' == dirname($_SERVER['PHP_SELF']))? '' : dirname($_SERVER['PHP_SELF']);
Header("Location: http://${_SERVER['HTTP_HOST']}${dirname}/?admin", TRUE, 301);

?>
