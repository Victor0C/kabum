<?php
require_once __DIR__ . "/Routes.php";
class Core
{

  public function run()
  {
    (new Routes)->execute();
  }

}
