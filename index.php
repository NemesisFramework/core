<?php

/* NEMESIS INIT */

include_once 'core/init.php';

/* GET NEMESIS INSTANCE */

$app = $NEMESIS->app('blog');

$app->setAsDefault();
$app->run();
echo $app;

?>
