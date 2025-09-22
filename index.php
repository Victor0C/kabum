<?php

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/app/routes.php";
require_once __DIR__ . "/core.php";

spl_autoload_register(function ($file) {
  if (file_exists(__DIR__ . "/app/utils/$file.php")) {
    require_once __DIR__ . "/app/utils/$file.php";
  } else if (file_exists(__DIR__ . "/app/models/$file.php")) {
    require_once __DIR__ . "/app/models/$file.php";
  }
});

$core = new Core($routes);
$core->run();
