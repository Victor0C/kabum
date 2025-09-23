<?php

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/routes.php";
require_once __DIR__ . "/core.php";

$core = new Core($routes);
$core->run();
